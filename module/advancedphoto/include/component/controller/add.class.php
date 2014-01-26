<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond_Benc
 * @package 		Phpfox_Component
 * @version 		$Id: add.class.php 4449 2012-07-02 13:49:23Z Raymond_Benc $
 */ 
class Advancedphoto_Component_Controller_Add extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		if ($this->request()->get('picup') == '1')
		{
			// This redirects the user when Picup has finished uploading the photo
			if ($this->request()->isIOS())
			{
				die("<script type='text/javascript'>window.location.href = '" . $this->url()->makeUrl('advancedphoto.converting') . "'; </script> ");
			}
			else
			{
				die("<script type='text/javascript'>window.open('" . $this->url()->makeUrl('advancedphoto.converting') . "', 'my_form'); </script> ");
			}
		}
		// Make sure the user is allowed to upload an image
		Phpfox::isUser(true);
		Phpfox::getUserParam('advancedphoto.can_upload_photos', true);		

		$sModule = $this->request()->get('module', false);
		$iItem =  $this->request()->getInt('item', false);	
		
		$bCantUploadMore = (Phpfox::getParam('advancedphoto.total_photo_input_bars') > Phpfox::getUserParam('advancedphoto.max_images_per_upload'));
		$iMaxFileSize = (Phpfox::getUserParam('advancedphoto.photo_max_upload_size') === 0 ? null : ((Phpfox::getUserParam('advancedphoto.photo_max_upload_size') / 1024) * 1048576));
		$sMethod = Phpfox::getParam('advancedphoto.enable_mass_uploader') && $this->request()->get('method','') != 'simple' ? 'massuploader' : 'simple';
		$sMethodUrl = str_replace(array('method_simple/','method_massuploader/'), '',$this->url()->getFullUrl()) . 'method_' . ($sMethod == 'simple' ? 'massuploader' : 'simple') . '/';
		
		if (Phpfox::isMobile() || $this->request()->isIOS())
		{
			$sMethod = 'simple';
			$this->template()->setHeader(array(
				'<script type="text/javascript">
						var flash_user_id = '.Phpfox::getUserId() .';
						var sHash = "'.Phpfox::getService('core')->getHashForUpload().'";window.name="my_form";</script>',
						));
			if ( ($sBrowser = Phpfox::getLib('request')->getBrowser()) && strpos($sBrowser, 'Safari') !== false)
			{
				$this->template()->setHeader(array(
					'mobile.js' => 'module_photo'
					))
				->assign(array('bRawFileInput' => true));
			}
		}
		$this->template()->setPhrase(array(
			'core.select_a_file_to_upload'
		));
		if ($sMethod == 'massuploader')
		{			
			$this->template()->setPhrase(array(							
						'advancedphoto.you_can_upload_a_jpg_gif_or_png_file',
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
				'<script type="text/javascript">
						// test for Firebug Lite (when preset it reloads the page so the user hash is not valid)
						if (typeof window.Firebug !="undefined" && window.Firebug.Lite != "undefined")
						{
							alert("You are using Firebug Lite which is known to have problems with our mass uploader. Please use the basic uploader or disable Firebug Lite and reload this page.");
						}
					$oSWF_settings =
					{
						object_holder: function()
						{
							return \'swf_photo_upload_button_holder\';
						},
						
						div_holder: function()
						{
							return \'swf_photo_upload_button\';
						},
						
						get_settings: function()
						{		
							swfu.setUploadURL("' . $this->url()->makeUrl('advancedphoto.frame') . '");
							swfu.setFileSizeLimit("'.$iMaxFileSize .' B");
							swfu.setFileUploadLimit('.Phpfox::getUserParam('advancedphoto.max_images_per_upload').');								
							swfu.setFileQueueLimit('.Phpfox::getUserParam('advancedphoto.max_images_per_upload').');
							swfu.customSettings.flash_user_id = '.Phpfox::getUserId() .';
							swfu.customSettings.sHash = "'.Phpfox::getService('core')->getHashForUpload().'";
							swfu.customSettings.sAjaxCall = "advancedphoto.process";
							swfu.customSettings.sAjaxCallParams = "' . ($sModule !== false ? '&callback_module=' . $sModule . '&callback_item_id=' . $iItem . '&parent_user_id=' . $iItem . '': '') . '";
							swfu.customSettings.sAjaxCallAction = function(iTotalImages){								
								tb_show(\'\', \'\', null, \'' . Phpfox::getLib('image.helper')->display(array('theme' => 'ajax/add.gif', 'class' => 'v_middle')) . ' ' . Phpfox::getPhrase('advancedphoto.please_hold_while_your_images_are_being_processed_processing_image') . ' <span id="js_photo_upload_process_cnt">1</span> ' . Phpfox::getPhrase('advancedphoto.out_of') . ' \' + iTotalImages + \'.\', true);
								$Core.loadInit();
							}
							swfu.atFileQueue = function()
							{
								$(\'#js_photo_form :input\').each(function(iKey, oObject)
								{
									swfu.addPostParam($(oObject).attr(\'name\'), $(oObject).val());
								});
							}
						}
					}
				</script>',
				)
			);			
		}
		else if ($this->request()->isIOS() == false)
		{
			 $this->template()->setHeader('<script type="text/javascript">$Behavior.photoProgressBarSettings = function(){ if ($Core.exists(\'#js_photo_form_holder\')) { oProgressBar = {holder: \'#js_photo_form_holder\', progress_id: \'#js_progress_bar\', uploader: \'#js_photo_upload_input\', add_more: ' . ($bCantUploadMore ? 'false' : 'true') . ', max_upload: ' . Phpfox::getUserParam('advancedphoto.max_images_per_upload') . ', total: 1, frame_id: \'js_upload_frame\', file_id: \'image[]\', valid_file_ext: new Array(\'gif\', \'png\', \'jpg\', \'jpeg\')}; $Core.progressBarInit(); } }</script>');	
		}
		
		$aCallback = false;
		if ($sModule !== false && $iItem !== false && Phpfox::hasCallback($sModule, 'getPhotoDetails'))
		{
			if (($aCallback = Phpfox::callback($sModule . '.getPhotoDetails', array('group_id' => $iItem))))
			{
				$this->template()->setBreadcrumb($aCallback['breadcrumb_title'], $aCallback['breadcrumb_home']);
				$this->template()->setBreadcrumb($aCallback['title'], $aCallback['url_home']);	
				if ($sModule == 'pages' && !Phpfox::getService('pages')->hasPerm($iItem, 'advancedphoto.share_photos'))
				{
					return Phpfox_Error::display(Phpfox::getPhrase('advancedphoto.unable_to_view_this_item_due_to_privacy_settings'));
				}				
			}
		}		
		
		$aPhotoAlbums = Phpfox::getService('advancedphoto.album')->getAll(Phpfox::getUserId(), $sModule, $iItem);
		foreach ($aPhotoAlbums as $iAlbumKey => $aPhotoAlbum)
		{
			if ($aPhotoAlbum['profile_id'] > 0)
			{
				unset($aPhotoAlbums[$iAlbumKey]);
			}
		}
		
		$this->template()->setTitle(Phpfox::getPhrase('advancedphoto.upload_photos'))	
			->setFullSite()	
			->setBreadcrumb(Phpfox::getPhrase('advancedphoto.photo'), $this->url()->makeUrl(($sModule !== false ? $sModule .'.': '') . ($iItem !== false ? $iItem .'.': '') . 'advancedphoto'))
			->setBreadcrumb(Phpfox::getPhrase('advancedphoto.upload_photos'), $this->url()->makeUrl('advancedphoto.add'), true)
			->setHeader('cache', array(
					'progress.js' => 'static_script'
				)
			)			
			->assign(array(
					'iMaxFileSize' => $iMaxFileSize,
					'iAlbumId' => $this->request()->getInt('album'),
					'aAlbums' => $aPhotoAlbums, // Get all the photo albums for this specific user
					'sModule' => $sModule,
					'iItem' => $iItem,
					'sMethod' => $sMethod,
					'sMethodUrl' => $sMethodUrl,
					'sCategories' => Phpfox::getService('advancedphoto.category')->get(false, true),
				)
			);
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('advancedphoto.component_controller_add_clean')) ? eval($sPlugin) : false);
	}
}

?>