<?php

defined('PHPFOX') or exit('NO DICE!');

function ynmt_install301()
{
    $oDatabase = Phpfox::getLib('database') ;
	
	if (!$oDatabase->tableExists(Phpfox::getT('mobiletemplate_active_theme_style')))
	{
		//	create active theme table
	    $oDatabase->query("
		CREATE TABLE IF NOT EXISTS `". Phpfox::getT('mobiletemplate_active_theme_style') ."` (
				`active_theme_style_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`style_id` smallint(4) unsigned NOT NULL,
				`theme_id` smallint(4) unsigned NOT NULL,
				PRIMARY KEY (`active_theme_style_id`),
				KEY `style_id` (`style_id`) 
			)  AUTO_INCREMENT=1 ;
		");
			
		//	insert data
		$oDatabase->query("
		INSERT IGNORE INTO `".Phpfox::getT('mobiletemplate_active_theme_style')."`(`active_theme_style_id`, `style_id`, `theme_id`) VALUES
			(NULL, 1, 1);
		");
	} 
	
	if (!$oDatabase->tableExists(Phpfox::getT('mobiletemplate_mobile_custom_style')))
	{
	    $oDatabase->query("
		CREATE TABLE IF NOT EXISTS `". Phpfox::getT('mobiletemplate_mobile_custom_style') ."` (
				`style_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`name` varchar(255) NOT NULL,
				`data` text NOT NULL,
				`time_stamp` int(10) unsigned NOT NULL,
				`is_active` tinyint(1) unsigned NOT NULL DEFAULT '0',
				PRIMARY KEY (`style_id`) 
			)  AUTO_INCREMENT=1 ;
		");	
	}

	if (!$oDatabase->tableExists(Phpfox::getT('mobiletemplate_menu_navigation')))
	{
	    $oDatabase->query("
		CREATE TABLE IF NOT EXISTS `". Phpfox::getT('mobiletemplate_menu_navigation') ."` (
				`menu_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
				`url` varchar(255) NOT NULL,
				`orginal_var_name` varchar(255),
				`display_name` varchar(255),
				`is_active` tinyint(1) unsigned NOT NULL DEFAULT '1',
				`ordering` int(11) unsigned NOT NULL DEFAULT '0',
				PRIMARY KEY (`menu_id`) 
			)  AUTO_INCREMENT=1 ;
		");	
	}
}

ynmt_install301();

?>