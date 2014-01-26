<?php

defined('PHPFOX') or exit('NO DICE!');


class AdvancedMarketplace_Component_Controller_Add extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		Phpfox::isUser(true);
		Phpfox::getUserParam('advancedmarketplace.can_create_listing', true);
		$bIsEdit = false;
		$bIsSetup = ($this->request()->get('req4') == 'setup' ? true : false);
		$sAction = $this->request()->get('req3');

        $sModule = $this->request()->get('module', false);
        $iItem =  $this->request()->getInt('item', false);

		$cfInfors = PHPFOX::getService("advancedmarketplace")->backend_getcustomfieldinfos();
		if ($iEditId = $this->request()->get('id'))
		{
			if (($aListing = Phpfox::getService('advancedmarketplace')->getForEdit($iEditId)))
			{
				$bIsEdit = true;
				if (Phpfox::isModule('tag'))
				{
					$aTags = Phpfox::getService('tag')->getTagsById('advancedmarketplace', $aListing['listing_id']);

					if (isset($aTags[$aListing['listing_id']]))
					{
						$aListing['tag_list'] = '';
						foreach ($aTags[$aListing['listing_id']] as $aTag)
						{
							$aListing['tag_list'] .= ' ' . $aTag['tag_text'] . ',';
						}
						$aListing['tag_list'] = trim(trim($aListing['tag_list'], ','));
					}
				}
				$this->setParam('aListing', $aListing);
				$this->setParam(array(
						'country_child_value' => $aListing['country_iso'],
						'country_child_id' => $aListing['country_child_id']
					)
				);
				// custom field
				$iCatId = $aListing["category"]["category_id"];
				$iListingId = $aListing["listing_id"];
				$aCustomFields = PHPFOX::getService("advancedmarketplace.customfield.advancedmarketplace")->frontend_loadCustomFields($iCatId, $iListingId);
				///custom field
				$this->template()->setHeader(array(
							'<script type="text/javascript">$Behavior.advancedmarketplaceEditCategory = function(){ var aCategories = explode(\',\', \'' . $aListing['categories'] . '\'); for (i in aCategories) { $(\'#js_mp_holder_\' + aCategories[i]).show(); $(\'#js_mp_category_item_\' + aCategories[i]).attr(\'selected\', true); } }</script>'
						)
					)
					->assign(array(
						'aForms' => $aListing,
						'aCustomFields' => $aCustomFields,
						'cfInfors' => $cfInfors,
					)
				);
			}
		}
		else
		{
			$this->template()->assign('aForms', array('price' => '0.00'));
			$this->template()
				->assign(array(
					'aCustomFields' => array(),
					'cfInfors' => $cfInfors,
				)
			);
		}
		$aValidation = array(
			'title' => Phpfox::getPhrase('advancedmarketplace.provide_a_name_for_this_listing'),
			'country_iso' => Phpfox::getPhrase('advancedmarketplace.provide_a_location_for_this_listing'),
			'price' => array(
				'def' => 'money'
			)
		);
		$oValidator = Phpfox::getLib('validator')->set(array(
				'sFormName' => 'js_advancedmarketplace_form',
				'aParams' => $aValidation
			)
		);
		if ($aVals = $this->request()->get('val'))
		{
			$aCustomFields = $this->request()->get('customfield');

			// valid for custom field...
			$aCustomFieldsReq = $this->request()->get('customfield_req');
            if(!$aCustomFieldsReq)
                $aCustomFieldsReq = array();
			$aCusValidation = array();
			foreach($aCustomFieldsReq as $key=>$aReq) {
				$aCusValidation[$key] = PHPFOX::getPhrase("advancedmarketplace.afield_is_required", array("afield"=>PHPFOX::getPhrase($aReq)));
			}
			// bad way to valid... :(
			$oCusValidator = clone Phpfox::getLib('validator');
			$oCusValidator = $oCusValidator->set(array(
					'sFormName' => 'js_advancedmarketplace_form',
					'aParams' => $aCusValidation
				)
			);
			$cFieldValid = $oCusValidator->isValid($aCustomFields);
			///valid for custom field...
			if ($cFieldValid && $oValidator->isValid($aVals))
			{
				if ($bIsEdit)
				{
					if (isset($aVals['draft_publish']))
					{
						$aVals['post_status'] = 1;
					} else {
						$aVals['time_stamp'] = PHPFOX_TIME;
						$aVals['update_timestamp'] = PHPFOX_TIME;
					}
					if (Phpfox::getService('advancedmarketplace.process')->update($aListing['listing_id'], $aVals, $aListing['user_id'], $aListing))
					{
						if($aCustomFields) {
							Phpfox::getService('advancedmarketplace.customfield.process')->frontend_updateCustomFieldData($aCustomFields, $aListing['listing_id']);
						}
						$aCustom = $this->request()->get('custom');
						if(!empty($aCustom))
						{
							phpfox::getService('advancedmarketplace.custom.process')->addCustomListing($aListing['listing_id'], $aCustom);
						}

						(($sPlugin = Phpfox_Plugin::get('advancedmarketplace.component_controller_add_process_update_complete')) ? eval($sPlugin) : false);
                        
                        if (Phpfox::isMobile())
                        {
                            $this->url()->send('advancedmarketplace.add', array('id' => $aListing['listing_id']), Phpfox::getPhrase('advancedmarketplace.listing_successfully_updated'));
                        }
                        else
                        {
                            if ($bIsSetup)
                            {
                                switch ($sAction)
                                {
                                    case 'customize':
                                        $this->url()->send('advancedmarketplace.add.invite.setup', array('id' => $aListing['listing_id']), Phpfox::getPhrase('advancedmarketplace.successfully_uploaded_images_for_this_listing'));
                                        break;
                                    case 'invite':
                                        $this->url()->permalink('advancedmarketplace', $aListing['listing_id'], $aListing['title'], true, Phpfox::getPhrase('advancedmarketplace.successfully_invited_users_for_this_listing'));
                                        break;
                                }
                            }
                            else
                            {
                                switch ($this->request()->get('page_section_menu'))
                                {
                                    case 'js_mp_block_customize':
                                        $this->url()->send('advancedmarketplace.add.customize', array('id' => $aListing['listing_id']), Phpfox::getPhrase('advancedmarketplace.successfully_uploaded_images'));
                                        break;
                                    case 'js_mp_block_invite':
                                        $this->url()->send('advancedmarketplace.add.invite', array('id' => $aListing['listing_id']), Phpfox::getPhrase('advancedmarketplace.successfully_invited_users'));
                                        break;
                                    default:
                                        $this->url()->send('advancedmarketplace.add', array('id' => $aListing['listing_id']), Phpfox::getPhrase('advancedmarketplace.listing_successfully_updated'));
                                        break;
                                }
                            }
                        }
					}
				}
				else
				{
					if (($iFlood = Phpfox::getUserParam('advancedmarketplace.flood_control_advancedmarketplace')) !== 0)
					{
						$aFlood = array(
							'action' => 'last_post', // The SPAM action
							'params' => array(
								'field' => 'time_stamp', // The time stamp field
								'table' => Phpfox::getT('advancedmarketplace'), // Database table we plan to check
								'condition' => 'user_id = ' . Phpfox::getUserId(), // Database WHERE query
								'time_stamp' => $iFlood * 60 // Seconds);
							)
						);

						// actually check if flooding
						if (Phpfox::getLib('spam')->check($aFlood))
						{
							Phpfox_Error::set(Phpfox::getPhrase('advancedmarketplace.you_are_creating_a_listing_a_little_too_soon') . ' ' . Phpfox::getLib('spam')->getWaitTime());
						}
					}

					if (Phpfox_Error::isPassed())
					{
						if(isset($aVals['draft']))
						{
							$aVals['post_status'] = 2;
						}

						if ($iId = Phpfox::getService('advancedmarketplace.process')->add($aVals))
						{
							if($aCustomFields) {
								Phpfox::getService('advancedmarketplace.customfield.process')->frontend_updateCustomFieldData($aCustomFields, $iId);
							}
							$aCustom = $this->request()->get('custom');
							if(!empty($aCustom))
							{

								phpfox::getService('advancedmarketplace.custom.process')->addCustomListing($aListing['listing_id'], $aCustom);
							}
                            if (Phpfox::isMobile())
                            {
                                $this->url()->send('advancedmarketplace.add', array('id' => $iId), Phpfox::getPhrase('advancedmarketplace.listing_successfully_added'));
                            }
                            else
                            {
                                $this->url()->send('advancedmarketplace.add.customize.setup', array('id' => $iId), Phpfox::getPhrase('advancedmarketplace.listing_successfully_added'));
                            }
						}
					}
				}
			}
		}

		$aCurrencies = Phpfox::getService('core.currency')->get();
		foreach ($aCurrencies as $iKey => $aCurrency)
		{
			$aCurrencies[$iKey]['is_default'] = '0';

			if (Phpfox::getService('core.currency')->getDefault() == $iKey)
			{
				$aCurrencies[$iKey]['is_default'] = '1';
			}
		}

		if ($bIsEdit)
		{
            $aMenus['detail'] = Phpfox::getPhrase('advancedmarketplace.listing_details');
            if (!Phpfox::isMobile())
            {
                $aMenus['customize'] = Phpfox::getPhrase('advancedmarketplace.photos');
                $aMenus['invite'] = Phpfox::getPhrase('advancedmarketplace.invite');
            }
			if (!$bIsSetup)
			{
				$aMenus['manage'] = Phpfox::getPhrase('advancedmarketplace.manage_invites');
			}

			$this->template()->buildPageMenu('js_mp_block',
				$aMenus,
				array(
					'link' => $this->url()->permalink('advancedmarketplace.detail', $aListing['listing_id'], $aListing['title']),
					'phrase' => Phpfox::getPhrase('advancedmarketplace.view_this_listing')
				)
			);
		}

		$iMaxFileSize = (Phpfox::getUserParam('advancedmarketplace.max_upload_size_listing') === 0 ? null : ((Phpfox::getUserParam('advancedmarketplace.max_upload_size_listing') / 1024) * 1048576));
		$sInviteLink = "\"window.location\"";
		if ($bIsEdit) {
			$sInviteLink = $bIsSetup?
				($this->url()->permalink('advancedmarketplace.add.invite.setup', "id_" . $aListing['listing_id'], $aListing['title'])):
				($this->url()->permalink('advancedmarketplace.add.customize', "id_" . $aListing['listing_id'], $aListing['title']));
		}
		$this->template()
			->setPhrase(array(
					'advancedmarketplace.you_can_upload_a_jpg_gif_or_png_file',
					'core.name',
					'core.status',
					'core.in_queue',
					'core.upload_failed_your_file_size_is_larger_then_our_limit_file_size',
					'core.more_queued_than_allowed'
				)
			)
			->setHeader(array(
				'massuploader/swfupload.js' => 'static_script',
				'massuploader/upload.js' => 'static_script',
				'
					<script type="text/javascript">
						$oSWF_settings =
						{
							object_holder: function()
							{
								return \'swf_msf_upload_button_holder\';
							},

							div_holder: function()
							{
								return \'swf_msf_upload_button\';
							},

							get_settings: function()
							{
								swfu.setUploadURL("' . $this->url()->makeUrl('advancedmarketplace.up') . '");
								swfu.setFileSizeLimit("' . $iMaxFileSize . ' B");
								swfu.setFileUploadLimit(' . Phpfox::getUserParam('advancedmarketplace.total_photo_upload_limit') . ');
								swfu.setFileQueueLimit(' . Phpfox::getUserParam('advancedmarketplace.total_photo_upload_limit') . ');
								swfu.customSettings.flash_user_id = ' . Phpfox::getUserId() . ';
								swfu.customSettings.sHash = "' . Phpfox::getService('core')->getHashForUpload() . '";
								swfu.customSettings.sAjaxCall = "advancedmarketplace.massUploadProcess";
								swfu.customSettings.sAjaxCallParams = "' . ($bIsEdit ? (isset($aListing['listing_id']) ? 'iEditId=' . $aListing['listing_id'] : '') . (isset($sInviteLink) ? '&sInviteLink=' . $sInviteLink : '') : '' ) . '";
								swfu.customSettings.sAjaxCallAction = function(){
									tb_show(\'\', \'\', null, \''.Phpfox::getPhrase('advancedmarketplace.please_hold_while_your_files_are_being_proccessed').'\');

									$Core.loadInit();
								}
								swfu.atFileQueue = function()
								{
									$(\'#js_advancedmarketplace_form :input\').each(function(iKey, oObject)
									{
										swfu.addPostParam($(oObject).attr(\'name\'), $(oObject).val());
									});
								}
							}
						}
					</script>
				'
			)
        );
		$this->template()->setTitle(($bIsEdit ? Phpfox::getPhrase('advancedmarketplace.editing_listing') . ': ' . $aListing['title'] : Phpfox::getPhrase('advancedmarketplace.create_a_advancedmarketplace_listing')))
			->setBreadcrumb(Phpfox::getPhrase('advancedmarketplace.advancedmarketplace'), $this->url()->makeUrl('advancedmarketplace'))
			->setBreadcrumb(($bIsEdit ? Phpfox::getPhrase('advancedmarketplace.editing_listing') . ': ' . $aListing['title'] : Phpfox::getPhrase('advancedmarketplace.create_a_listing')), $this->url()->makeUrl('advancedmarketplace.add'), true)
			->setEditor()
			->setFullSite()
			->setPhrase(array(
					'core.select_a_file_to_upload'
				)
			)
			->setHeader(array(
					'add.js' => 'module_advancedmarketplace',
					'progress.js' => 'static_script',
					'map.js' => 'module_advancedmarketplace',
					'add.css' => 'module_advancedmarketplace',
					'pager.css' => 'style_css',
					'country.js' => 'module_core'
				)
			)
			->assign(array(
					'sMyEmail' => Phpfox::getUserBy('email'),
					'sCreateJs' => $oValidator->createJS(),
					'sGetJsForm' => $oValidator->getJsForm(false),
					'bIsEdit' => $bIsEdit,
					'sCategories' => Phpfox::getService('advancedmarketplace.category')->get(),
					'iMaxFileSize' => (Phpfox::getUserParam('advancedmarketplace.max_upload_size_listing') === 0 ? null : ((Phpfox::getUserParam('advancedmarketplace.max_upload_size_listing') / 1024) * 1048576)),
					'aCurrencies' => $aCurrencies,
					'sUserSettingLink' => $this->url()->makeUrl('user.setting'),
					'advancedmarketplace_url_image' => Phpfox::getParam('core.url_pic') . "advancedmarketplace/",
				)
			);
        
		(($sPlugin = Phpfox_Plugin::get('advancedmarketplace.component_controller_add_process')) ? eval($sPlugin) : false);
	}

	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('advancedmarketplace.component_controller_add_clean')) ? eval($sPlugin) : false);
	}
}

?>
