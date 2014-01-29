<?php

defined('PHPFOX') or exit('NO DICE!');

class SocialBridge_Component_Controller_Admincp_Providers extends Phpfox_Component
{

	public function process()
	{
		$oService = phpfox::getService('socialbridge.providers');
		
		$aParam = array(
            'maxInvite' => array(
            	'def' => 'int',
            	'pattern' => '/^\d+$/',
            	'title' => Phpfox::getPhrase('socialbridge.max_invite_is_numberic')
            ));

        $oFbValid = Phpfox::getLib('validator')->set(array(
                'sFormName' => 'provider_facebook',
                'aParams' => $aParam
            )
        );
		$oTwValid = Phpfox::getLib('validator')->set(array(
                'sFormName' => 'provider_twitter',
                'aParams' => $aParam
            )
        );
		$oLiValid = Phpfox::getLib('validator')->set(array(
                'sFormName' => 'provider_linkedin',
                'aParams' => $aParam
            )
        );
		if ($aFacebook = $this -> request() -> get('facebook'))
		{
			if(!isset($aFacebook['maxInvite']) || $oFbValid->isValid($aFacebook))
			{
				if(isset($aFacebook['maxInvite']))
				{
					if($aFacebook['maxInvite'] < 1 || $aFacebook['maxInvite'] > 20)
					{
						$this -> url() -> send("admincp.socialbridge.providers", null, Phpfox::getPhrase('socialbridge.fill_max_invite_for_facebook'));
						return;
					}
				}
				if(isset($aFacebook['app_id']) === false || strlen(trim($aFacebook['app_id'])) == 0){
					$aFacebook['is_active'] = 0;
				}
				if(isset($aFacebook['secret']) === false || strlen(trim($aFacebook['secret'])) == 0){
					$aFacebook['is_active'] = 0;
				}
				
				$fb_settings = $oService -> getProvider('facebook');
				$sUploadDir = Phpfox::getParam('photo.dir_photo');
				if (isset($_FILES['fb_pic']['name']) && ($_FILES['fb_pic']['name'] != ''))
				{
					$oFile = Phpfox::getLib('file');
					$oImage = Phpfox::getLib('image');
					$aImage = $oFile -> load('fb_pic', $aExts = array(
						'jpg',
						'gif',
						'png'
					));
					$sFileName = $oFile -> upload('fb_pic', $sUploadDir, '');
					$aFacebook['pic'] = $sFileName;
				} elseif (isset($fb_settings['params']['pic']) && $fb_settings['params']['pic'])
				{
					$aFacebook['pic'] = $fb_settings['params']['pic'];
					if (isset($aFacebook['delete_pic']) && $aFacebook['delete_pic'])
					{
						@unlink($sUploadDir . sprintf($aFacebook['pic'], ''));
						$aFacebook['pic'] = '';
					}
				} else
				{
					$aFacebook['pic'] = '';
				}
	
				$iStatus = $aFacebook['is_active'];
				unset($aFacebook['is_active']);
				$oService -> addSetting('facebook', serialize($aFacebook), $iStatus);
				$this -> url() -> send("admincp.socialbridge.providers", null, Phpfox::getPhrase('socialbridge.update_successfully'));
			}
		}
		if ($aTwitter = $this -> request() -> get('twitter'))
		{
			if(!isset($aTwitter['maxInvite']) || $oTwValid->isValid($aTwitter))
			{
				if(isset($aTwitter['maxInvite']))
				{
					if($aTwitter['maxInvite'] < 1 || $aTwitter['maxInvite'] > 250)
					{
						$this -> url() -> send("admincp.socialbridge.providers", null, Phpfox::getPhrase('socialbridge.fill_max_invite_for_twitter'));
						return;
					}
				}
				if(isset($aTwitter['consumer_key']) === false || strlen(trim($aTwitter['consumer_key'])) == 0){
					$aTwitter['is_active'] = 0;
				}
				if(isset($aTwitter['consumer_secret']) === false || strlen(trim($aTwitter['consumer_secret'])) == 0){
					$aTwitter['is_active'] = 0;
				}
				
				$iStatus = $aTwitter['is_active'];
				unset($aTwitter['is_active']);
				$oService -> addSetting('twitter', serialize($aTwitter), $iStatus);
				$this -> url() -> send("admincp.socialbridge.providers", null, Phpfox::getPhrase('socialbridge.update_successfully'));
			}
		}
		if ($aLinkedIn = $this -> request() -> get('linkedin'))
		{
			if(!isset($aLinkedIn['maxInvite']) || $oLiValid->isValid($aLinkedIn))
			{
				if(isset($aLinkedIn['maxInvite']))
				{
					if($aLinkedIn['maxInvite'] < 1 || $aLinkedIn['maxInvite'] > 10)
					{
						$this -> url() -> send("admincp.socialbridge.providers", null, Phpfox::getPhrase('socialbridge.fill_max_invite_for_linkedin'));
						return;
					}
				}
				if(isset($aLinkedIn['api_key']) === false || strlen(trim($aLinkedIn['api_key'])) == 0){
					$aLinkedIn['is_active'] = 0;
				}
				if(isset($aLinkedIn['secret_key']) === false || strlen(trim($aLinkedIn['secret_key'])) == 0){
					$aLinkedIn['is_active'] = 0;
				}
				
				$iStatus = $aLinkedIn['is_active'];
				unset($aLinkedIn['is_active']);
				$oService -> addSetting('linkedin', serialize($aLinkedIn), $iStatus);
				$this -> url() -> send("admincp.socialbridge.providers", null, Phpfox::getPhrase('socialbridge.update_successfully'));
			}
		}
		$aPublisherProviders = $oService -> getProviders(false);

		$this -> template() -> setTitle(Phpfox::getPhrase('socialbridge.manage_social_api_keys')) -> setBreadcrumb(Phpfox::getPhrase('socialbridge.manage_social_api_keys'), $this -> url() -> makeUrl('admincp.socialbridge.providers')) -> setPhrase(array(
			'socialbridge.view',
			'socialbridge.hide',
		)) -> assign(array(
			'aPublisherProviders' => $aPublisherProviders,
			'sCoreUrl' => phpfox::getParam('core.path'),
			'sCallBackUrl' => phpfox::getParam('core.path') . 'module/socialbridge/static/php/twitter.php',
		));
	}

	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('socialbridge.component_controller_admincp_providers_clean')) ? eval($sPlugin) : false);
	}

}
?>