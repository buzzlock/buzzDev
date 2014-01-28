<?php
if (Phpfox::isMobile()) {
	//	OVERRIDE PHPFOX_TMP_DIR
	$ynmtdirCacheTemplateMT = PHPFOX_DIR_CACHE . 'ynmttemplate' . PHPFOX_DS;
	if (!is_dir($ynmtdirCacheTemplateMT))
	{
		mkdir($ynmtdirCacheTemplateMT);
		chmod($ynmtdirCacheTemplateMT, 0777);
	}
	if (!defined('PHPFOX_TMP_DIR'))
	{
		define('PHPFOX_TMP_DIR', $ynmtdirCacheTemplateMT);	
	}	

    $aActiveStyle = Phpfox::getService('mobiletemplate')->getActiveMobileStyle();
	if(isset($aActiveStyle) && isset($aActiveStyle['style_id'])){
	    $this->_sStyleFolder = $aActiveStyle['style_folder_name'];
	    $this->_sThemeFolder = $aActiveStyle['theme_folder_name'];
	}
}
?>