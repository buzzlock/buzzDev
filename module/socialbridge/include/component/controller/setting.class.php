<?php

defined('PHPFOX') or exit('NO DICE!');

class SocialBridge_Component_Controller_Setting extends Phpfox_Component
{

	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		Phpfox::isUser(true);

		$oRequest = $this -> request();

		$iUserId = Phpfox::getUserId();

		$oService = Phpfox::getService('socialbridge');

		$aVals = $oRequest -> getArray('val');

		$sTab = $oRequest -> get('tab', '');
		
		if ($sDisconnectService = $oRequest -> get('disconnect'))
		{
			$oService -> removeTokenData($sDisconnectService, $iUserId);
			
			$this -> url() -> send('socialbridge.setting', null);
		}

		$aProviders = $oService -> getAllProviderData($iUserId);
		

		(($sPlugin = Phpfox_Plugin::get('socialbridge.component_controller_setting_process_supported_modules')) ? eval($sPlugin) : false);

		if (count($aProviders))
		{
			$aMenus = array('connections' => Phpfox::getPhrase('socialbridge.connections'));

			(($sPlugin = Phpfox_Plugin::get('socialbridge.component_controller_setting_process')) ? eval($sPlugin) : false);

			$this -> template() -> buildPageMenu('js_setting_block', $aMenus, array(
				'no_header_border' => true,
				'link' => $this -> url() -> makeUrl('socialbridge.setting'),
				'phrase' => Phpfox::getPhrase('socialbridge.view_your_settings')
			));
			/*
			$this -> template() -> buildPageMenu('js_setting_block', $aMenus, NULL);
			*/
		}
        
		$this -> template() -> setTitle(Phpfox::getPhrase('socialbridge.manage_social_accounts')) -> setBreadcrumb(Phpfox::getPhrase('socialbridge.manage_social_accounts')) -> setFullSite() -> assign(array(
			'aProviders' => $aProviders,
			'sCoreUrl' => Phpfox::getParam('core.path'),
			'sTab' => $sTab,
		));
		$this->template()->setHeader('cache', array(
            'socialbridge.js' => 'module_socialbridge'
        ));
		
	}

	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('socialbridge.component_controller_setting_clean')) ? eval($sPlugin) : false);
	}

}
?>