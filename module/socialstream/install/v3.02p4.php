<?php

defined('PHPFOX') or exit('NO DICE!');

function alter_socialstream_setting()
{
	$sTable = Phpfox::getT('socialstream_setting');
	$oDb = Phpfox::getLib('phpfox.database');
	$sTableAgent = Phpfox::getT('socialbridge_agents');

	if ($oDb -> tableExists($sTable))
	{

	}
	else
	{
		$sql = "CREATE TABLE `" . $sTable . "` (
			  `setting_id` int(11) NOT NULL AUTO_INCREMENT,
			  `user_id` int(11) NOT NULL,
			  `service` varchar(32) NOT NULL DEFAULT 'facebook',
			  `identity` varchar(64) DEFAULT '',
			  `enable` int(11) NOT NULL DEFAULT '1',
			  `privacy` int(11) NOT NULL DEFAULT '0',
			  `lastfeed_timestamp` int(11) NOT NULL DEFAULT '0',
			  `lastcheck_timestamp` int(11) NOT NULL DEFAULT '0',
			  PRIMARY KEY (`setting_id`),
			  KEY `user_id_service_identity` (`user_id`,`service`,`identity`)
			) ENGINE=MyISAM";
		$oDb -> query($sql);
	}

    if(!$oDb->isField($sTable,'enable'))
    {
        $oDb->query("ALTER TABLE `" . $sTable . "` ADD COLUMN `enable` int(11) NOT NULL DEFAULT '1';");
    }
}

function hide_admin_setting()
{
    $sTable = Phpfox::getT('setting');
    $oDb = Phpfox::getLib('phpfox.database');

    $AdminSetting = null;
    $AdminSetting = $oDb->select('*')->from($sTable)->where('var_name = "default_privacy"')->execute('getRow');

    if($AdminSetting)
    {
        $AdminSetting['is_hidden'] = 1;
        $oDb->update($sTable, $AdminSetting, 'var_name = "default_privacy"');
    }

    unset($AdminSetting);

    $AdminSetting = null;
    $AdminSetting = $oDb->select('*')->from($sTable)->where('var_name = "admin_can_view_any_socialstream_feeds"')->execute('getRow');

    if($AdminSetting)
    {
        $AdminSetting['is_hidden'] = 1;
        $oDb->update($sTable, $AdminSetting, 'var_name = "admin_can_view_any_socialstream_feeds"');
    }
}

alter_socialstream_setting();
hide_admin_setting();
