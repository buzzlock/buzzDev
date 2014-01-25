<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');
/**
 * 
 * 
 * @copyright       [YOUNET_COPYRIGHT]
 * @author          YouNet Company
 * @package         YouNet_Event
 */
class Fevent_Component_Controller_Add extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		
		Phpfox::isUser(true);
		
		Phpfox::getUserParam('fevent.can_create_event', true);
		
		$bIsEdit = false;
		$bIsSetup = ($this->request()->get('req4') == 'setup' ? true : false);
		$sAction = $this->request()->get('req3');
		$aCallback = false;		
		$sModule = $this->request()->get('module', false);
		$iItem =  $this->request()->getInt('item', false);
		
		$until="";
		if ($iEditId = $this->request()->get('id'))
		{
			
			if (($aEvent = Phpfox::getService('fevent')->getForEdit($iEditId)))
			{
				
				$content_repeat="";
				
				if($aEvent['isrepeat']==0)
				{
					$content_repeat=Phpfox::getPhrase('fevent.daily');
				}
				else if($aEvent['isrepeat']==1)
				{
					$content_repeat=Phpfox::getPhrase('fevent.weekly');
				}
				else if($aEvent['isrepeat']==2)
                {
					$content_repeat=Phpfox::getPhrase('fevent.monthly');
				}
				if($content_repeat!="")
				{
					if($aEvent['timerepeat']!=0)
					{
						$sDefault = null;
                        $until = Phpfox::getTime("m/d/Y", $aEvent['timerepeat']);
                        $content_repeat .= ", " . Phpfox::getPhrase('fevent.until') . " " . $until;
					}
				}
				$bIsEdit = true;
				$this->setParam('aEvent', $aEvent);
				$this->setParam(array(
						'country_child_value' => $aEvent['country_iso'],
						'country_child_id' => $aEvent['country_child_id']
					)
				);
             
				$this->template()->setHeader(array(
							'<script type="text/javascript">$Behavior.eventEditCategory = function(){  var aCategories = explode(\',\', \'' . $aEvent['categories'] . '\'); for (i in aCategories) { $(\'#js_mp_holder_\' + aCategories[i]).show(); $(\'#js_mp_category_item_\' + aCategories[i]).attr(\'selected\', true); } }</script>'
						)
					)
					->assign(array(
						'aForms' => $aEvent,
						'aEvent' => $aEvent,
						'content_repeat' => $content_repeat,
						
					)
				);
				
				if ($aEvent['module_id'] != 'fevent')
				{
					$sModule = $aEvent['module_id'];
					$iItem = $aEvent['item_id'];	
				}
                
                if($aCustomFields = Phpfox::getService('fevent.custom')->getCustomFieldsForEdit($iEditId))
                {
                    $this->template()->assign(array(
                        'aCustomFields' => $aCustomFields
                    ));
                }
			}
		}		
		$this->template()->assign(array(
			'until' => $until,
		));
		if ($sModule && $iItem && Phpfox::hasCallback($sModule, 'viewEvent'))
		{
			$aCallback = Phpfox::callback($sModule . '.viewEvent', $iItem);		
			$this->template()->setBreadcrumb($aCallback['breadcrumb_title'], $aCallback['breadcrumb_home']);
			$this->template()->setBreadcrumb($aCallback['title'], $aCallback['url_home']);		
			if ($sModule == 'pages' && !Phpfox::getService('pages')->hasPerm($iItem, 'fevent.share_events'))
			{
				return Phpfox_Error::display(Phpfox::getPhrase('fevent.unable_to_view_this_item_due_to_privacy_settings'));
			}				
		}		
		
		$aValidation = array(
			'title' => Phpfox::getPhrase('fevent.provide_a_name_for_this_event'),
			// 'country_iso' => Phpfox::getPhrase('fevent.provide_a_country_location_for_this_event'),			
			'location' => Phpfox::getPhrase('fevent.provide_a_location_for_this_event')
		);
		
		$oValidator = Phpfox::getLib('validator')->set(array(
				'sFormName' => 'js_event_form',
				'aParams' => $aValidation
			)
		);		
		
		if ($aVals = $this->request()->get('val'))
		{
			
			if ($oValidator->isValid($aVals))
			{
				$aVals['event_id'] = $iEditId;
				$this->template()->assign(array('aForms' => $aVals, 'aEvent' => $aVals));
                
				if($aVals['txtrepeat']>=-1)
				{
					$daterepeat=explode("/",$aVals['daterepeat']);
                    
                    if(count($daterepeat)>1)
					{
                        $idate_repeat = mktime(0, 0, 0, $daterepeat[0], $daterepeat[1], $daterepeat[2]);
                        $istart_repeat = mktime(0, 0, 0, $aVals['start_month'], $aVals['start_day'], $aVals['start_year']);
                    }
                    
                    $bAllowed = false;
                    
					if($aVals['txtrepeat']==0)
					{
						if($idate_repeat<$istart_repeat)
						{
							Phpfox_Error::set(Phpfox::getPhrase('fevent.daily_repeat_date_have_to_be_larger_or_equal_than_start_date'));
                            $bAllowed = true;
						}
					}
					else if($aVals['txtrepeat']==1){
						$istart_repeat=$istart_repeat+(3600*24*7);
						if($idate_repeat<$istart_repeat)
						{
							Phpfox_Error::set(Phpfox::getPhrase('fevent.weekly_repeat_date_have_to_be_larger_or_equal_than_start_date_in_about_1_week'));
                            $bAllowed = true;
						}
					}
					else if($aVals['txtrepeat']==2){
						$istart_repeat=$istart_repeat+(3600*24*7);
						$month=$aVals['start_month'];
						$year=$aVals['start_year'];
						$month=$month+1;
						if($month==13)
						{
							$month=1;
							$year++;
						}
						$idate_repeat = mktime(0, 0, 0, $daterepeat[0], $daterepeat[1], $daterepeat[2]);
						$istart_repeat = mktime(0, 0, 0, $month, $aVals['start_day'], $year);
						if($idate_repeat<$istart_repeat)
						{
							Phpfox_Error::set(Phpfox::getPhrase('fevent.monthly_repeat_date_have_to_be_larger_or_equal_than_start_date_in_about_1_month'));
                            $bAllowed = true;
						}
					}
				}	
					
				if ($bIsEdit && !$bAllowed)
				{
					if (Phpfox::getService('fevent.process')->update($iEditId, $aVals, $aEvent))
					{
						if(isset($aVals['update_detail']))
                        {
                            $this->url()->send('fevent.add', array('id' => $iEditId), Phpfox::getPhrase('fevent.event_successfully_updated'));
                        }
                        elseif(isset($aVals['upload_photo']))
                        {
                            if($aErrors = phpfox_error::get())
							{
								Phpfox::getLib('session')->set('aErrors', $aErrors);
								$this->url()->send('fevent.add/tab_photo', array('id' => $iEditId), Phpfox::getPhrase('fevent.some_of_images_haven_t_been_uploaded'));
							}
							else
							{
							    $this->url()->send('fevent.add/tab_photo', array('id' => $iEditId), Phpfox::getPhrase('fevent.successfully_added_photo_s_to_your_event'));
						    }
                        }
                        elseif(isset($aVals['send_invitations']))
                        {
                            $this->url()->send('fevent.add.invite', array('id' => $iEditId), Phpfox::getPhrase('fevent.successfully_invited_guests_to_this_event'));
                        }
                        else
                        {
                            switch ($sAction)
    						{
    							case 'customize':
    								$this->url()->send('fevent.add.invite.setup', array('id' => $iEditId), Phpfox::getPhrase('fevent.successfully_added_a_photo_to_your_event'));	
    								break;
    							case 'invite':
    								$this->url()->send('fevent.add.invite', array('id' => $iEditId), Phpfox::getPhrase('fevent.successfully_invited_guests_to_this_event'));
    								break;
    							default:
                                    $this->url()->send('fevent.add', array('id' => $iEditId), Phpfox::getPhrase('fevent.event_successfully_updated'));
    						}
                        }
					}
					else
					{
						//Phpfox_Error::set(Phpfox::getPhrase('fevent.there_are_some_errors_when_update_event_please_try_again'));
                        //$this->url()->send('current', null, null);
					}
				}
				else 
				{
					if (($iFlood = Phpfox::getUserParam('fevent.flood_control_events')) !== 0)
					{
						$aFlood = array(
							'action' => 'last_post', // The SPAM action
							'params' => array(
								'field' => 'time_stamp', // The time stamp field
								'table' => Phpfox::getT('fevent'), // Database table we plan to check
								'condition' => 'user_id = ' . Phpfox::getUserId(), // Database WHERE query
								'time_stamp' => $iFlood * 60 // Seconds);	
							)
						);
							 			
						// actually check if flooding
						if (Phpfox::getLib('spam')->check($aFlood))
						{
							Phpfox_Error::set(Phpfox::getPhrase('fevent.you_are_creating_an_event_a_little_too_soon') . ' ' . Phpfox::getLib('spam')->getWaitTime());	
						}
					}					
					
					if (Phpfox_Error::isPassed())
					{	
						if ($iId = Phpfox::getService('fevent.process')->add($aVals, ($aCallback !== false ? $sModule : 'fevent'), ($aCallback !== false ? $iItem : 0)))
						{
							$aEvent = Phpfox::getService('fevent')->getForEdit($iId);
							//$this->url()->permalink('fevent', $aEvent['event_id'], $aEvent['title'], true, Phpfox::getPhrase('fevent.event_successfully_added'));
                            $this->url()->send('fevent.add', array('tab' => 'photo', 'id' => $aEvent['event_id']), Phpfox::getPhrase('fevent.event_successfully_added'));
						}
					}
				}
			}
			
			$sStep = (isset($aVals['step']) ? $aVals['step'] : '');
			$sAction = (isset($aVals['action']) ? $aVals['action'] : '');	
			$this->template()->assign('aForms', $aVals);		
		}		
		
		if ($bIsEdit)
		{
			$aMenus = array(
				'detail' => Phpfox::getPhrase('fevent.event_details'),
				'customize' => Phpfox::getPhrase('fevent.photo'),
				'invite' => Phpfox::getPhrase('fevent.invite_guests')
			);
			
			if (!$bIsSetup)
			{
				$aMenus['manage'] = Phpfox::getPhrase('fevent.manage_guest_list');
				$aMenus['email'] = Phpfox::getPhrase('fevent.mass_email');
			}
			
			$this->template()->buildPageMenu('js_event_block', 
				$aMenus,
				array(
					'link' => $this->url()->permalink('fevent', $aEvent['event_id'], $aEvent['title']),
					'phrase' => Phpfox::getPhrase('fevent.view_this_event')
				)				
			);		
		}
		
        $sTab = $this->request()->get('tab');
		if($sTab=='photo' && $aErrors = Phpfox::getLib('session')->get('aErrors'))
		{
			foreach ($aErrors as $sError) {
				Phpfox_Error::set($sError);
			}
			Phpfox::getLib('session')->remove('aErrors');
		}
		$bCanAddMap = Phpfox::getUserParam('fevent.can_add_gmap');
        
		$this->template()->setTitle(($bIsEdit ? Phpfox::getPhrase('fevent.managing_event') . ': ' . $aEvent['title'] : Phpfox::getPhrase('fevent.create_an_event')))
			->setFullSite()			
			->setBreadcrumb(Phpfox::getPhrase('fevent.events'), ($aCallback === false ? $this->url()->makeUrl('fevent.when_upcoming') : $this->url()->makeUrl($aCallback['url_home_pages'])))
			->setBreadcrumb(($bIsEdit ? Phpfox::getPhrase('fevent.managing_event') . ': ' . $aEvent['title'] : Phpfox::getPhrase('fevent.create_new_event')), ($bIsEdit ? $this->url()->makeUrl('fevent.add', array('id' => $aEvent['event_id'])) : $this->url()->makeUrl('fevent.add')), true)
			->setEditor(array('wysiwyg' => Phpfox::getUserParam('fevent.can_use_editor_on_event')))
			->setPhrase(array(
					'core.select_a_file_to_upload'
				)
			)				
			->setHeader('cache', array(	
					'add.js' => 'module_fevent',
                    'add.css' => 'module_fevent',
                    'map.js' => 'module_fevent',
					'pager.css' => 'style_css',
					'progress.js' => 'static_script',					
					'country.js' => 'module_core'					
				)
			)		
			//	window.external = false;
			->setHeader(array(
					'<script type="text/javascript">$Behavior.eventProgressBarSettings = function(){ if ($Core.exists(\'#js_event_block_customize_holder\')) { oProgressBar = {holder: \'#js_event_block_customize_holder\', progress_id: \'#js_progress_bar\', uploader: \'#js_progress_uploader\', add_more: false, max_upload: 6, total: 1, frame_id: \'js_upload_frame\', file_id: \'image[]\'}; $Core.progressBarInit(); } }</script>'
				)
			)
			->assign(array(
					'sCreateJs' => $oValidator->createJS(),
					'sGetJsForm' => $oValidator->getJsForm(false),
					'bCanAddMap' => $bCanAddMap,
					'bIsEdit' => $bIsEdit,
                    'sTab' => $sTab,
					'bIsSetup' => $bIsSetup,
					'sCategories' => Phpfox::getService('fevent.category')->get(),
					'sModule' => ($aCallback !== false ? $sModule : ''),
					'iItem' => ($aCallback !== false ? $iItem : ''),
					'aCallback' => $aCallback,
					'iMaxFileSize' => (Phpfox::getUserParam('fevent.max_upload_size_event') === 0 ? null : Phpfox::getLib('phpfox.file')->filesize((Phpfox::getUserParam('fevent.max_upload_size_event') / 1024) * 1048576)),
					'bCanSendEmails' => ($bIsEdit ? Phpfox::getService('fevent')->canSendEmails($aEvent['event_id']) : false),
					'iCanSendEmailsTime' => ($bIsEdit ? Phpfox::getService('fevent')->getTimeLeft($aEvent['event_id']) : false),
					'sJsEventAddCommand' => (isset($aEvent['event_id']) ? "if (confirm('" . Phpfox::getPhrase('fevent.are_you_sure', array('phpfox_squote' => true)) . "')) { $('#js_submit_upload_image').show(); $('#js_event_upload_image').show(); $('#js_event_current_image').remove(); $.ajaxCall('fevent.deleteImage', 'id={$aEvent['event_id']}'); } return false;" : ''),
					'sTimeSeparator' => Phpfox::getPhrase('fevent.time_separator')
				)
			);

		//if(false)            
        if (Phpfox::isModule('attachment') && Phpfox::getUserParam('fevent.can_attach_on_event'))
        {
            $this->setParam('attachment_share', array(
                    'type' => 'fevent',
                    'id' => 'js_event_form',
                    'edit_id' => ($bIsEdit ? $iEditId : 0)
                )
            );
        }
        $this->template()->setPhrase(array(
            "fevent.the_field_field_name_is_required"
        ));
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('fevent.component_controller_add_clean')) ? eval($sPlugin) : false);
	}
}

?>