<?php

defined('PHPFOX') or exit('NO DICE!');

function socialstream_agents()
{
    $sTable = Phpfox::getT('socialstream_agents');
    $sql = "CREATE TABLE IF NOT EXISTS `" . $sTable . "` (
		`agent_id` int(11) NOT NULL auto_increment,
		`user_id` int(11) unsigned NOT NULL,
		`identity` varchar(128) NOT NULL,
		`service_id` int(11) unsigned NOT NULL,
		`ordering` int(11) unsigned NOT NULL,
		`token` text,
		`params` text,
		`full_name` varchar(255) default NULL,
		`user_name` varchar(75) NOT NULL,
		`img_url` varchar(255) NOT NULL,
		`privacy` int(1) unsigned NOT NULL default '3',
		`cron_id` int(10) unsigned NOT NULL default '0',
		`last_feed` varchar(100) NOT NULL default '0',
		PRIMARY KEY  (`agent_id`)
	) ENGINE=MyISAM AUTO_INCREMENT=1;";

    Phpfox::getLib('phpfox.database')->query($sql);
}

function socialstream_feeds()
{
    $sTable = Phpfox::getT('socialstream_feeds');
    $sql = "CREATE TABLE IF NOT EXISTS `" . $sTable . "` (
		`feed_id` int(10) unsigned NOT NULL auto_increment,
		`service_id` int(10) unsigned NOT NULL,
		`user_id` int(10) unsigned NOT NULL,
		`service_feed_id` varchar(75) NOT NULL default '0',
		`service_feed_link` mediumtext NOT NULL,
		`social_agent_full_name` varchar(255) NOT NULL,
		`social_agent_id` varchar(255) NOT NULL,
		`title` varchar(255) NOT NULL,
		`link` varchar(255) NOT NULL,
		`message` text,
		`content` mediumtext NOT NULL,
		`total_comment` int(10) unsigned NOT NULL default '0',
		`total_like` int(10) unsigned NOT NULL default '0',
		`image_url` mediumtext NOT NULL,
		`privacy` int(1) unsigned NOT NULL default '0',
		`privacy_comment` int(1) unsigned NOT NULL default '0',
		`feed_type` varchar(255) NOT NULL,
		`time_stamp` int(10) unsigned NOT NULL default '0',
		PRIMARY KEY  (`feed_id`)
	  ) ENGINE=MyISAM  AUTO_INCREMENT=1;";
    Phpfox::getLib('phpfox.database')->query($sql);
}

function socialstream_services()
{
    $sTable = Phpfox::getT('socialstream_services');
    $sql = "CREATE TABLE IF NOT EXISTS `" . $sTable . "` (
		`service_id` int(11) unsigned NOT NULL auto_increment,
		`name` varchar(32) character set utf8 NOT NULL,
		`title` varchar(128) NOT NULL,
		`privacy` tinyint(1) NOT NULL default '0',
		`connect` int(11) NOT NULL default '0',
		`protocol` varchar(32) NOT NULL default 'openid',
		`mode` varchar(32) NOT NULL default 'popup',
		`w` int(11) NOT NULL default '800',
		`h` int(11) NOT NULL default '450',
		`ordering` int(11) NOT NULL default '0',
		`is_active` int(11) NOT NULL default '1',
		`params` text,
		PRIMARY KEY  (`service_id`)
	  ) ENGINE=MyISAM  AUTO_INCREMENT=3;";
    Phpfox::getLib('phpfox.database')->query($sql);

    $sql = "INSERT IGNORE INTO `" . $sTable . "` (`service_id`, `name`, `title`, `privacy`, `connect`, `protocol`, `mode`, `w`, `h`, `ordering`, `is_active`, `params`) VALUES
(1, 'facebook', 'Facebook', 1, 1, 'oauth', 'popup', 800, 450, 1, 0, ''),
(2, 'twitter', 'Twitter', 1, 1, 'oauth', 'popup', 800, 450, 2, 0, '');";
    Phpfox::getLib('phpfox.database')->query($sql);
}

socialstream_agents();
socialstream_feeds();
socialstream_services();
?>
