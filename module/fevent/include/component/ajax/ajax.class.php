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

/**
 * 
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Event
 * @version 		$Id: ajax.class.php 3642 2011-12-02 10:01:15Z Miguel_Espinoza $
 */

class Fevent_Component_Ajax_Ajax extends Phpfox_Ajax
{
	
	public function migrateData()
    {
        $is_migrate = false;
        $is_migrate_album = false;
        $event_list = phpfox::getService('fevent')->getAllEventPhpfox();
		     
		if ( count($event_list)>0)
       	{
        	$is_migrate = true;
           	foreach ($event_list as $event)
           	{
               	$fevent = array();
               	$fevent['view_id'] = $event['view_id'];
				$fevent['is_featured'] = $event['is_featured'];
				$fevent['is_sponsor'] = $event['is_sponsor'];
				$fevent['privacy'] = $event['privacy'];
				$fevent['privacy_comment'] = $event['privacy_comment'];
				$fevent['module_id'] = $event['module_id'];
				$fevent['item_id'] = $event['item_id'];
				$fevent['user_id'] = $event['user_id'];
				$fevent['title'] = $event['title'];
				$fevent['location'] = $event['location'];
				$fevent['country_iso'] = $event['country_iso'];	
				$fevent['country_child_id'] = $event['country_child_id'];
				$fevent['postal_code'] = $event['postal_code'];
				$fevent['city'] = $event['city'];
				$fevent['time_stamp'] = $event['time_stamp'];
				$fevent['start_time'] = $event['start_time'];
				$fevent['end_time'] = $event['end_time'];
				$fevent['image_path'] = $event['image_path'];
				$fevent['server_id'] = $event['server_id'];
				$fevent['total_comment'] = 0;
				$fevent['total_like'] = 0;
				$fevent['total_view'] = 0;
				$fevent['total_attachment'] = 0;
				$fevent['mass_email'] = $event['mass_email'];
				$fevent['start_gmt_offset'] = $event['start_gmt_offset'];
				$fevent['end_gmt_offset'] = $event['end_gmt_offset'];	
				$fevent['gmap'] = $event['gmap'];
				$fevent['address'] = $event['address'];
				$fevent['lat'] = 0;
				$fevent['lng'] = 0;	
				$fevent['gmap_address'] = "";
				$fevent['isrepeat'] = -1;
				$fevent['timerepeat'] = 0;
				$fevent['range_value'] = 0;
				$fevent['range_type'] = 0;
				$fevent['range_value_real'] = 0;
               
               	$last_insert_id_event =  phpfox::getLib('database')->insert(Phpfox::getT('fevent'),$fevent);
				$fevent['event_id']=$last_insert_id_event;
				$aCategory=phpfox::getService("fevent")->getAllCategorydataPhpfox($event['event_id']);
				
				$category_data=array();
				if(isset($aCategory['event_id']))
				{
					$category_data['event_id'] = $fevent['event_id'];
					$category_data['category_id'] = $aCategory['category_id'];
					phpfox::getLib("database")->insert(Phpfox::getT('fevent_category_data'),$category_data);
				}
				
				$aFeedEvent =  phpfox::getService('fevent')->getAllFeedEventPhpfox($event['event_id']);
				
				foreach($aFeedEvent as $FeedEvent)
				{
					$FeedComment=array();
					
					$FeedCommentById=phpfox::getService("fevent")->getFeedCommentPhpfox($FeedEvent['item_id']);
					
					if(isset($FeedCommentById['feed_comment_id']))
					{
//						$FeedComment['privacy']=$FeedCommentById['privacy'];
//						$FeedComment['privacy_comment']=$FeedCommentById['privacy_comment'];
//						$FeedComment['user_id']=$FeedCommentById['user_id'];
//						$FeedComment['parent_user_id']=$fevent['event_id'];
//						$FeedComment['time_stamp']=$FeedCommentById['time_stamp'];
//						$FeedComment['total_comment']=0;
//						$FeedComment['total_like']=0;
//						
//					
//						$feedcomment_id=phpfox::getLib("database")->insert(phpfox::getT('fevent_feed_comment'),$FeedComment);
//							
						$Feed=array();
					
						$Feed['privacy']=$FeedEvent['privacy'];
						$Feed['privacy_comment']=$FeedEvent['privacy_comment'];
						$Feed['user_id']=$FeedEvent['user_id'];
						$Feed['type_id']=$FeedEvent['type_id'];
						$Feed['parent_user_id']=$fevent['event_id'];
						$Feed['item_id']=0;
						$Feed['time_stamp']=$FeedEvent['time_stamp'];
						
						$feed_id=phpfox::getLib("database")->insert(phpfox::getT('fevent_feed'),$Feed);
					}
				}
				
				$event_test=phpfox::getService('fevent')->getEventTextPhpfox($event['event_id']);
				if(isset($event_test['event_id']))
				{
					$eText=array();
					$eText['event_id']=$fevent['event_id'];
					$eText['description']=$event_test['description'];
					$eText['description_parsed']=$event_test['description_parsed'];
					phpfox::getLib("database")->insert(phpfox::getT('fevent_text'),$eText);
				}
				
				$ainviteEvent=phpfox::getService("fevent")->getInviteEventPhpfox($event['event_id']);
				foreach($ainviteEvent as $inviteEvent)
				{
					$InEvent=array();
					$InEvent['event_id']=$fevent['event_id'];
					$InEvent['type_id']=$inviteEvent['type_id'];
					$InEvent['rsvp_id']=$inviteEvent['rsvp_id'];
					$InEvent['user_id']=$inviteEvent['user_id'];
					$InEvent['invited_user_id']=$inviteEvent['invited_user_id'];
					$InEvent['invited_email']=$inviteEvent['invited_email'];
					$InEvent['time_stamp']=$inviteEvent['time_stamp'];
					
					phpfox::getLib("database")->insert(phpfox::getT('fevent_invite'),$InEvent);
				}
				
                if ($last_insert_id_event>0)
                {
                     $this->html('#info_process',Phpfox::getPhrase('fevent.imported_event')." '".$fevent['title']."' ".Phpfox::getPhrase('fevent.successfully'));
                }
           }

       }
       else
       {
       		$is_migrate = false;
            $this->html('#info_process',Phpfox::getPhrase('fevent.there_is_no_event_to_import'));
       }
       if ( $is_migrate == true || $is_migrate_album == true)
       {
            $this->html('#info_process',Phpfox::getPhrase('fevent.import_successfully'));
			$this->html('#contener_pro','<div id="contener_percent" style="background-color: fuchsia;height:100%;width:100%">
                   100%
                </div>') ;
			$this->alert(Phpfox::getPhrase('fevent.import_successfully'));
       }
       else
       {
            if($is_migrate == false && $is_migrate_album == false)
            {
                $this->html('#contener_pro','<div id="contener_percent" style="background-color: fuchsia;height:100%;width:100%">
                       100%
                    </div>') ;
               $this->html('#info_process',"There is no event to import.");
               $this->alert(Phpfox::getPhrase('fevent.there_is_no_event_to_import'));
           }
       }
	
       //finish view
    }

	public function deleteImage()
	{
		Phpfox::isUser(true);
		
		if (Phpfox::getService('fevent.process')->deleteImage($this->get('id')))
		{
			Phpfox::getLib('cache')->remove('event_featured_0');
		}
	}
	
	public function addRsvp()
	{
		Phpfox::isUser(true);
		
		if (Phpfox::getService('fevent.process')->addRsvp($this->get('id'), $this->get('rsvp'), Phpfox::getUserId()))
		{
			if ($this->get('rsvp') == 3)
			{
				$sRsvpMessage = Phpfox::getPhrase('fevent.not_attending');
			}
			elseif ($this->get('rsvp') == 2)
			{
				$sRsvpMessage = Phpfox::getPhrase('fevent.maybe_attending');
			}
			elseif ($this->get('rsvp') == 1)
			{
				$sRsvpMessage = Phpfox::getPhrase('fevent.attending');
			}
			
			if ($this->get('inline'))
			{
				$this->html('#js_event_rsvp_' . $this->get('id'), $sRsvpMessage);
				$this->hide('#js_event_rsvp_invite_image_' . $this->get('id'));
			}
			else 
			{
				$this->html('#js_event_rsvp_update', Phpfox::getPhrase('fevent.done'), '.fadeOut(5000);')
					->html('#js_event_rsvp_' . $this->get('id'), $sRsvpMessage)
					->call('$(\'#js_event_rsvp_button\').find(\'input:first\').attr(\'disabled\', false);')
					->call('tb_remove();');
                    
				$this->call('$.ajaxCall(\'fevent.listGuests\', \'&rsvp=' . $this->get('rsvp') . '&id=' . $this->get('id') . '' . ($this->get('module') ? '&module=' . $this->get('module') . '&item=' . $this->get('item') . '' : '') . '\');')
					->call('$(function(){ $(\'#js_block_border_event_list .menu:first ul li\').removeClass(\'active\'); $(\'#js_block_border_event_list .menu:first ul li a\').each(function() { var aParts = explode(\'rsvp=\', this.href); var aParts2 = explode(\'&\', aParts[1]); if (aParts2[0] == ' . $this->get('rsvp') . ') {  $(this).parent().addClass(\'active\'); } }); });');
                
                if($this->get('rsvp') != 3 && Phpfox::getService('fevent.gapi')->getForManage()) {
                    $this->show('#js_event_gcalendar_button');
                    $this->call('tb_show("'.Phpfox::getPhrase('fevent.google_calendar').'",$.ajaxBox("fevent.glogin","height=300;width=350&id="+'.$this->get('id').'))');
                } else {
                    $this->hide('#js_event_gcalendar_button');
                }
			}
		}
	}
	
	public function listGuests()
	{
		Phpfox::getBlock('fevent.list');
		
		$this->html('#js_event_item_holder', $this->getContent(false));
	}
	
	public function browseList()
	{	
		Phpfox::getBlock('fevent.browse');
		
		if ((int) $this->get('page') > 0)
		{
			$this->html('#js_event_browse_guest_list', $this->getContent(false));
		}
		else 
		{
			$this->setTitle(Phpfox::getPhrase('fevent.guest_list'));	
		}
	}
	
	public function deleteGuest()
	{
		if (Phpfox::getService('fevent.process')->deleteGuest($this->get('id')))
		{
			
		}
	}
	
	public function delete()
	{
		if (Phpfox::getService('fevent.process')->delete($this->get('id')))
		{
			$this->call('$(\'#js_event_item_holder_' . $this->get('id') . '\').html(\'<div class="message" style="margin:0px;">' . Phpfox::getPhrase('fevent.successfully_deleted_event') . '</div>\').fadeOut(5000);');			
		}
	}
	
	public function rsvp()
	{
		Phpfox::getBlock('fevent.rsvp');
	}
	
	public function feature()
	{
		if (Phpfox::getService('fevent.process')->feature($this->get('event_id'), $this->get('type')))
		{
			
		}
	}	

	public function sponsor()
	{
	    if (Phpfox::getService('fevent.process')->sponsor($this->get('event_id'), $this->get('type')))
	    {
		if ($this->get('type') == '1')
		{
		    Phpfox::getService('ad.process')->addSponsor(array('module' => 'fevent', 'item_id' => $this->get('event_id')));
		    $this->call('$("#js_event_unsponsor_'.$this->get('event_id').'").show();');
		    $this->call('$("#js_event_sponsor_'.$this->get('event_id').'").hide();');
		    $this->addClass('#js_event_item_holder_'.$this->get('event_id'), 'row_sponsored');
			$this->show('#js_sponsor_phrase_' . $this->get('event_id'));
		    $this->alert(Phpfox::getPhrase('fevent.event_successfully_sponsored'));
		}
		else
		{
		    Phpfox::getService('ad.process')->deleteAdminSponsor('fevent', $this->get('event_id'));
		    $this->call('$("#js_event_unsponsor_'.$this->get('event_id').'").hide();');
		    $this->call('$("#js_event_sponsor_'.$this->get('event_id').'").show();');
		    $this->removeClass('#js_event_item_holder_'.$this->get('event_id'), 'row_sponsored');
			$this->hide('#js_sponsor_phrase_' . $this->get('event_id'));
		    $this->alert(Phpfox::getPhrase('fevent.event_successfully_un_sponsored'));
		}
	    }
	}
	
	public function approve()
	{
		if (Phpfox::getService('fevent.process')->approve($this->get('event_id')))
		{
			$this->alert(Phpfox::getPhrase('fevent.event_has_been_approved'), Phpfox::getPhrase('fevent.event_approved'), 300, 100, true);
			$this->hide('#js_item_bar_approve_image');
			$this->hide('.js_moderation_off'); 
			$this->show('.js_moderation_on');				
		}
	}
	
	public function moderation()
	{
		Phpfox::isUser(true);	
		
		switch ($this->get('action'))
		{
			case 'approve':
				Phpfox::getUserParam('fevent.can_approve_events', true);
				foreach ((array) $this->get('item_moderate') as $iId)
				{
					Phpfox::getService('fevent.process')->approve($iId);
					$this->remove('#js_event_item_holder_' . $iId);					
				}				
				$this->updateCount();
				$sMessage = Phpfox::getPhrase('fevent.event_s_successfully_approved');
				break;			
			case 'delete':
				Phpfox::getUserParam('fevent.can_delete_other_event', true);
				foreach ((array) $this->get('item_moderate') as $iId)
				{
					Phpfox::getService('fevent.process')->delete($iId);
					$this->slideUp('#js_event_item_holder_' . $iId);
				}				
				$sMessage = Phpfox::getPhrase('fevent.event_s_successfully_deleted');
				break;
		}
		
		$this->alert($sMessage, 'Moderation', 300, 150, true);
		$this->hide('.moderation_process');			
	}	

	public function massEmail()
	{
		$iPage = $this->get('page', 1);
		$sSubject = $this->get('subject');
		$sText = $this->get('text');
		
		if ($iPage == 1 && !Phpfox::getService('fevent')->canSendEmails($this->get('id')))
		{
			$this->hide('#js_event_mass_mail_li');
			$this->alert(Phpfox::getPhrase('fevent.you_are_unable_to_send_out_any_mass_emails_at_the_moment'));
			
			return;
		}
		
		if (empty($sSubject) || empty($sText))
		{
			$this->hide('#js_event_mass_mail_li');
			$this->alert(Phpfox::getPhrase('fevent.fill_in_both_a_subject_and_text_for_your_mass_email'));
			
			return;
		}
		
		$iCnt = Phpfox::getService('fevent.process')->massEmail($this->get('id'), $iPage, $this->get('subject'), $this->get('text'));
		
		if ($iCnt === false)
		{
			$this->hide('#js_event_mass_mail_li');
			$this->alert(Phpfox::getPhrase('fevent.you_are_unable_to_send_a_mass_email_for_this_event'));
			
			return;
		}		
	
		Phpfox::getLib('pager')->set(array('ajax' => 'fevent.massEmail', 'page' => $iPage, 'size' => 20, 'count' => $iCnt));		
		
		if ($iPage < Phpfox::getLib('pager')->getLastPage())
		{
			$this->call('$.ajaxCall(\'fevent.massEmail\', \'id=' . $this->get('id') . '&page=' . ($iPage + 1) . '&subject=' . $this->get('subject') . '&text=' . $this->get('text') . '\');');
			
			$this->html('#js_event_mass_mail_send', Phpfox::getPhrase('fevent.email_progress_page_total', array('page' => $iPage, 'total' => Phpfox::getLib('pager')->getLastPage())));
		}
		else 
		{
			if (!Phpfox::getService('fevent')->canSendEmails($this->get('id'), true))
			{
				$this->hide('#js_send_email')
					->show('#js_send_email_fail')
					->html('#js_time_left', Phpfox::getTime(Phpfox::getParam('mail.mail_time_stamp'), Phpfox::getService('fevent')->getTimeLeft($this->get('id'))));
			}
			
			$this->hide('#js_event_mass_mail_li');
			$this->alert(Phpfox::getPhrase('fevent.done'));
		}
	}	
	
	public function removeInvite()
	{
		Phpfox::getService('fevent.process')->removeInvite($this->get('id'));
	}
	
	public function addFeedComment()
	{
		Phpfox::isUser(true);
		
		$aVals = (array) $this->get('val');	
		
		if (Phpfox::getLib('parse.format')->isEmpty($aVals['user_status']))
		{
			$this->alert(Phpfox::getPhrase('user.add_some_text_to_share'));
			$this->call('$Core.activityFeedProcess(false);');
			return;			
		}		
		
		$aEvent = Phpfox::getService('fevent')->getForEdit($aVals['callback_item_id'], true);
		
		if (!isset($aEvent['event_id']))
		{
			$this->alert(Phpfox::getPhrase('fevent.unable_to_find_the_event_you_are_trying_to_comment_on'));
			$this->call('$Core.activityFeedProcess(false);');
			return;
		}
		
		$sLink = Phpfox::permalink('fevent', $aEvent['event_id'], $aEvent['title']);
		$aCallback = array(
			'module' => 'fevent',
			'table_prefix' => 'fevent_',
			'link' => $sLink,
			'email_user_id' => $aEvent['user_id'],
			'subject' => Phpfox::getPhrase('fevent.full_name_wrote_a_comment_on_your_event_title', array('full_name' => Phpfox::getUserBy('full_name'), 'title' => $aEvent['title'])),
			'message' => Phpfox::getPhrase('fevent.full_name_wrote_a_comment_on_your_event_message', array('full_name' => Phpfox::getUserBy('full_name'), 'link' => $sLink, 'title' => $aEvent['title'])),
			'notification' => 'fevent_comment',
			'feed_id' => 'fevent_comment',
			'item_id' => $aEvent['event_id']
		);
		
		$aVals['parent_user_id'] = $aVals['callback_item_id'];
		
		if (isset($aVals['user_status']) && ($iId = Phpfox::getService('feed.process')->callback($aCallback)->addComment($aVals)))
		{
			Phpfox::getLib('database')->updateCounter('fevent', 'total_comment', 'event_id', $aEvent['event_id']);		
                      
			Phpfox::getService('feed')->callback($aCallback)->processAjax($iId);
		}
		else 
		{
			$this->call('$Core.activityFeedProcess(false);');
		}		
	}
    
    public function setDefault()
    {
        if (Phpfox::getService('fevent.process')->setDefault($this->get('id')))
        {
            Phpfox::getLib('cache')->remove('event_featured_0');
            //Phpfox::getLib('cache')->remove('fevent.past');
            //Phpfox::getLib('cache')->remove('fevent.upcoming');
        }
    }
    
    public function toggleActiveField()
    {
        Phpfox::getUserParam('fevent.can_manage_custom_fields', true);
        if (Phpfox::getService('fevent.custom.process')->toggleActivity($this->get('id')))
        {
            $this->call('$Core.custom.toggleFieldActivity(' . $this->get('id') . ')');
        }
    }
    
    public function deleteField()
    {
        Phpfox::getUserParam('fevent.can_manage_custom_fields', true);
        if (Phpfox::getService('fevent.custom.process')->delete($this->get('id')))
        {
            $this->call('$(\'#js_field_' . $this->get('id') . '\').parents(\'li:first\').remove();');
        }
    }
    
    public function getCustomFields()
    {
        $iId = $this->get("id");
        $aCustomFields = Phpfox::getService('fevent.custom')->getFieldsByCateId($iId);
        Phpfox::getBlock('fevent.custom', array("aCustomFields" => $aCustomFields));
        $this->html('#ajax_custom_fields', $this->getContent(false));
        $aRequired = array();
        foreach($aCustomFields as $iKey => $aField)
        {
            if($aField['is_required'] == 1)
            {
                $aRequired[] = '{"field_name":"'.$aField['field_name'].'", "phrase_name":"'.Phpfox::getPhrase($aField['phrase_var_name']).'","var_type":"'.$aField['var_type'].'"}';
            }
        }
        $sOutJs = '[' . join(',', $aRequired) . ']';
        $this->call('$(\'#required_custom_fields\').val(\''.$sOutJs.'\');');
    }
	
	public function gmap()
	{
		Phpfox::getBlock('fevent.gmap');
	}
	
	public function getEventsForGmap()
	{
		$sIds = $this->get('ids');

		$sIds = trim($sIds, ',');
		$aIds = array();
		$aIds = explode(',', $sIds);
		foreach($aIds as $iKey => $sId)
		{
			$aIds[$iKey] = (int)$sId;
		}
		$aEvents = Phpfox::getService('fevent')->getEventsByIds($aIds);
		
		$sJson = json_encode($aEvents);
		$this->call('displayMarkers("'.str_replace('"', '\\"', $sJson).'");');
	}
	
	public function reloadGmap()
	{
		$sLocation = $this->get('location');
		$sCity = $this->get('city');
		$sRadius = (int)$this->get('radius');
		
		if($sLocation=="Location...")
			$sLocation="";
		if($sCity!="" && $sCity!="City...")
			$sLocation=$sLocation." , ".$sCity;
		
		list($aCoordinates, $sGmapAddress) = Phpfox::getService('fevent.process')->address2coordinates($sLocation);
		$radius=0;
		if (is_int($sRadius))
		{
			$radius=$sRadius;
		}
		
		$sIds = $this->get('ids');

		$sIds = trim($sIds, ',');
		$aIds = array();
		$aIds = explode(',', $sIds);
		foreach($aIds as $iKey => $sId)
		{
			$aIds[$iKey] = (int)$sId;
		}
		$aEvents = Phpfox::getService('fevent')->getEventsByIds($aIds);
		
		$sJson = json_encode($aEvents);
                
		$this->call('panGmapTo('.$aCoordinates[1].','.$aCoordinates[0].','.$radius.','.$sJson.');'); // lat, lng
	}
	
	public function reloadGmapOne()
	{
		$sLocation = $this->get('location');
		$sCity = $this->get('city');
		$sRadius = (int)$this->get('radius');
		
		if($sCity!="" && $sCity!="City...")
			$sLocation=$sLocation.",".$sCity;
		list($aCoordinates, $sGmapAddress) = Phpfox::getService('fevent.process')->address2coordinates($sLocation);
		$radius=0;
		if (is_int($sRadius))
		{
			$radius=$sRadius;
		}
		$this->call('panGmapTo('.$aCoordinates[1].','.$aCoordinates[0].','.$radius.');'); // lat, lng
	}
	
	public function repeat()
	{
		$value=$this->get('value');
		$txtrepeat=$this->get('txtrepeat');
		$daterepeat=$this->get('daterepeat');
		
		phpfox::getBlock("fevent.repeat",array("value"=>$value,"txtrepeat"=>$txtrepeat,"daterepeat"=>$daterepeat));
	}
	
	public function glogin()
	{
        $id = $this->get('id');
		Phpfox::getBlock("fevent.glogin",array("id"=>$id));
	}
	
	public function donerepeat()
	{
		$selrepeat=$this->get('relrepeat');
		$daterepeat=$this->get('txtdisable');
		$bIsEdit=$this->get('bIsEdit');
		if($daterepeat!="")
		{
			if($selrepeat==0)
				$chuoi=Phpfox::getPhrase('fevent.daily');
			else if($selrepeat==1)
				$chuoi=Phpfox::getPhrase('fevent.weekly');
			else {
				$chuoi=Phpfox::getPhrase('fevent.monthly');
			}
			$until="";
			if($daterepeat!="")
				$until=", ".Phpfox::getPhrase('fevent.until')." ".$daterepeat;
		
			$chuoi.=$until;
		
			echo "$('#daterepeat').val('".$daterepeat."');";
			
			echo "$('#txtrepeat').val('".$selrepeat."');";
			echo "$('#chooserepeat').html(': ".$chuoi."');";
			echo "$('#editrepeat').html('".Phpfox::getPhrase('fevent.edit')."');";
			
			if(!$bIsEdit)
			{
				echo "$('#extra_info_date').css('display','none')";
			}
			else {
				echo "$('#js_event_add_end_time').css('display','none');";
			}
		}
		else {
			
			echo "$('#cbrepeat').removeAttr('checked');";
			
				
		}
	}
}

?>