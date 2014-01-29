<?php

defined('PHPFOX') or exit('NO DICE!');

function delete_old_cronjob()
{
    $oDb = Phpfox::getLib('phpfox.database');
    $sTable = Phpfox::getT('cron');
    $sql = 'DELETE FROM `' . $sTable . '` WHERE `module_id` = "socialstream" AND `product_id` = "socialstream"';
    $oDb->query($sql);
}

function alter_socialbridge_agents()
{
    $sTable = Phpfox::getT('socialbridge_agents');
    $oDb = Phpfox::getLib('phpfox.database');
	if($oDb->tableExists($sTable))
	{
		if (!$oDb->isField($sTable, 'privacy'))
		{
			$sql = "ALTER TABLE `" . $sTable . "` ADD `privacy` tinyint(1) unsigned DEFAULT '0'";
			$oDb->query($sql);
		}

		if (!$oDb->isField($sTable, 'last_feed'))
		{
			$sql = "ALTER TABLE `" . $sTable . "` ADD `last_feed` VARCHAR( 255 ) NULL";
			$oDb->query($sql);
		}
	}
	else
	{
		 $sql = "CREATE TABLE IF NOT EXISTS `" . $sTable . "` (
            `agent_id` int(11) NOT NULL AUTO_INCREMENT,
            `user_id` int(11) unsigned NOT NULL,
            `identity` varchar(128) NOT NULL,
            `service_id` int(11) unsigned NOT NULL,
            `ordering` int(11) unsigned NOT NULL,
            `token` text,
            `params` text,
            `full_name` varchar(255) DEFAULT NULL,
            `user_name` varchar(75) NULL,
            `img_url` varchar(255) NULL,
            `privacy` tinyint(1) unsigned DEFAULT '0',
            `last_feed` varchar(255) DEFAULT NULL,
            PRIMARY KEY (`agent_id`)
          ) ENGINE=MyISAM AUTO_INCREMENT=1";
		$oDb->query($sql);
	}
}

function remove_admincp_menu()
{
    $sTable = Phpfox::getT('module');
    $oDb = Phpfox::getLib('phpfox.database');

    $sql = "UPDATE `" . $sTable . "` SET `is_menu` = '0' WHERE `module_id` = 'socialstream' AND `product_id` = 'socialstream' AND `is_menu` =1";
    $oDb->query($sql);
}

function remove_homepage_menu()
{
    $sTable = Phpfox::getT('menu');
    $oDb = Phpfox::getLib('phpfox.database');

    $sql = "Delete from `" . $sTable . "` where module_id = 'socialstream' AND product_id = 'socialstream'";
    $oDb->query($sql);
}

delete_old_cronjob();
alter_socialbridge_agents();
remove_admincp_menu();
remove_homepage_menu();
?>