<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');
if (file_exists(PHPFOX_DIR . 'module' . PHPFOX_DS . 'socialbridge' . PHPFOX_DS . 'include' . PHPFOX_DS . 'service' . PHPFOX_DS . 'libs' . PHPFOX_DS . 'facebook.php'))
{

	require_once (PHPFOX_DIR . 'module' . PHPFOX_DS . 'socialbridge' . PHPFOX_DS . 'include' . PHPFOX_DS . 'service' . PHPFOX_DS . 'libs' . PHPFOX_DS . 'facebook.php');
}
if (file_exists(PHPFOX_DIR . 'module' . PHPFOX_DS . 'socialbridge' . PHPFOX_DS . 'include' . PHPFOX_DS . 'service' . PHPFOX_DS . 'libs' . PHPFOX_DS . 'twitter.php'))
{
	require_once (PHPFOX_DIR . 'module' . PHPFOX_DS . 'socialbridge' . PHPFOX_DS . 'include' . PHPFOX_DS . 'service' . PHPFOX_DS . 'libs' . PHPFOX_DS . 'twitter.php');
}
if (file_exists(PHPFOX_DIR . 'module' . PHPFOX_DS . 'socialbridge' . PHPFOX_DS . 'include' . PHPFOX_DS . 'service' . PHPFOX_DS . 'libs' . PHPFOX_DS . 'linkedin.php'))
{
	require_once (PHPFOX_DIR . 'module' . PHPFOX_DS . 'socialbridge' . PHPFOX_DS . 'include' . PHPFOX_DS . 'service' . PHPFOX_DS . 'libs' . PHPFOX_DS . 'linkedin.php');
}
if (file_exists(PHPFOX_DIR . 'module' . PHPFOX_DS . 'socialbridge' . PHPFOX_DS . 'include' . PHPFOX_DS . 'service' . PHPFOX_DS . 'libs' . PHPFOX_DS . 'tmhOAuth.php'))
{
	require_once (PHPFOX_DIR . 'module' . PHPFOX_DS . 'socialbridge' . PHPFOX_DS . 'include' . PHPFOX_DS . 'service' . PHPFOX_DS . 'libs' . PHPFOX_DS . 'tmhOAuth.php');
}

class SocialBridge_Service_Libs extends Phpfox_Service
{

	public function __construct()
	{
		$this -> _sTable = phpfox::getT('socialbridge_services');
	}

	public function timeline()
	{
		return Phpfox::getService('socialbridge') -> timeline();
	}

	public function getFBAccessToken()
	{
		$aProvider = phpfox::getService('socialbridge.providers') -> getProvider('facebook');
		$aConfig = $aProvider['params'];
		$oFacebook = new FacebookSBYN($aConfig);
		return $oFacebook -> getUserAccessToken();
	}

}
?>
