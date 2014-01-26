<?php

defined('PHPFOX') or exit('NO DICE!');

/**
 * create DATABASE For version 3.01
 * @by datlv
 *
 */

function ynam_install301() {
    $oDatabase = Phpfox::getLib('database');

    $oDatabase->query(
        "CREATE TABLE IF NOT EXISTS `" . phpfox::getT("advancedmarketplace") . "` (
					  `listing_id` int(10) unsigned NOT NULL auto_increment,
					  `view_id` tinyint(3) NOT NULL default '0',
					  `privacy` tinyint(1) NOT NULL default '0',
					  `privacy_comment` tinyint(1) NOT NULL default '0',
					  `group_id` int(10) unsigned NOT NULL default '0',
					  `user_id` int(10) unsigned NOT NULL,
					  `is_featured` tinyint(1) NOT NULL default '0',
					  `is_sponsor` tinyint(1) NOT NULL default '0',
					  `title` varchar(255) NOT NULL,
					  `currency_id` char(3) NOT NULL default 'USD',
					  `price` decimal(14,2) NOT NULL default '0.00',
					  `country_iso` char(2) default NULL,
					  `country_child_id` mediumint(8) unsigned NOT NULL default '0',
					  `postal_code` varchar(20) default NULL,
					  `city` varchar(255) default NULL,
					  `time_stamp` int(10) unsigned NOT NULL,
					  `image_path` varchar(75) default NULL,
					  `server_id` tinyint(1) NOT NULL default '0',
					  `total_comment` int(10) unsigned NOT NULL default '0',
					  `total_like` int(10) unsigned NOT NULL default '0',
					  `is_sell` tinyint(1) NOT NULL default '0',
					  `is_closed` tinyint(1) NOT NULL default '0',
					  `auto_sell` tinyint(1) NOT NULL default '0',
					  `total_rate` int(10) unsigned NOT NULL default '0',
					  `privacy_rating` tinyint(3) unsigned NOT NULL default '0',
					  `post_status` tinyint(3) default NULL,
					  `tag` varchar(100) default NULL,
					  `update_timestamp` int(10) default NULL,
					  `total_view` int(10) default '0',
					  `total_score` decimal(4,2) default '0.00',
					  PRIMARY KEY  (`listing_id`)
					);"
    );

    $oDatabase->query(
        "CREATE TABLE IF NOT EXISTS `" . phpfox::getT("advancedmarketplace_category") . "` (
					  `category_id` int(10) unsigned NOT NULL auto_increment,
					  `parent_id` mediumint(8) unsigned NOT NULL default '0',
					  `is_active` tinyint(1) NOT NULL default '0',
					  `name` varchar(255) NOT NULL,
					  `name_url` varchar(255) default NULL,
					  `time_stamp` int(10) unsigned NOT NULL default '0',
					  `used` int(10) unsigned NOT NULL default '0',
					  `ordering` int(11) unsigned NOT NULL default '0',
					  PRIMARY KEY  (`category_id`)
					);"
    );

    $oDatabase->query(
        "CREATE TABLE IF NOT EXISTS `" . phpfox::getT("advancedmarketplace_category_customgroup_data") . "` (
					  `category_id` int(10) NOT NULL,
					  `group_id` int(10) NOT NULL,
					  PRIMARY KEY  (`category_id`,`group_id`)
					);"
    );

    $oDatabase->query(
        "CREATE TABLE IF NOT EXISTS `" . phpfox::getT("advancedmarketplace_category_data") . "` (
					  `category_id` int(10) unsigned NOT NULL,
					  `listing_id` int(10) unsigned NOT NULL,
					  PRIMARY KEY  (`category_id`,`listing_id`)
					);"
    );

    $oDatabase->query(
        "CREATE TABLE IF NOT EXISTS `" . phpfox::getT("advancedmarketplace_custom_field") . "` (
					  `field_id` int(10) unsigned NOT NULL auto_increment,
					  `var_type` varchar(250) default NULL,
					  `is_required` tinyint(1) NOT NULL,
					  `field_name` varchar(75) NOT NULL,
					  `type_name` varchar(75) default NULL,
					  `ordering` tinyint(3) default NULL,
					  `phrase_var_name` varchar(75) default NULL,
					  `is_active` tinyint(1) default NULL,
					  `group_id` int(11) NOT NULL,
					  `field_info` text,
					  PRIMARY KEY  (`field_id`)
					);"
    );

    $oDatabase->query(
        "CREATE TABLE IF NOT EXISTS `" . phpfox::getT("advancedmarketplace_custom_field_data") . "` (
					  `custom_field_data_id` int(10) unsigned NOT NULL auto_increment,
					  `data` text NOT NULL,
					  `custom_field_id` int(10) unsigned NOT NULL,
					  `field_id` int(10) unsigned NOT NULL,
					  `listing_id` int(10) unsigned NOT NULL,
					  PRIMARY KEY  (`custom_field_data_id`)
					);"
    );

    $oDatabase->query(
        "CREATE TABLE IF NOT EXISTS `" . phpfox::getT("advancedmarketplace_custom_group") . "` (
					  `group_id` int(11) NOT NULL auto_increment,
					  `phrase_var_name` varchar(250) default NULL,
					  `is_active` tinyint(1) default '1',
					  `ordering` tinyint(3) default '0',
					  `category_id` int(10) unsigned NOT NULL,
					  PRIMARY KEY  (`group_id`)
					);"
    );

    $oDatabase->query(
        "CREATE TABLE IF NOT EXISTS `" . phpfox::getT("advancedmarketplace_custom_option") . "` (
					  `option_id` int(10) unsigned NOT NULL auto_increment,
					  `field_id` int(10) unsigned NOT NULL,
					  `phrase_var_name` varchar(250) NOT NULL,
					  PRIMARY KEY  (`option_id`)
					);"
    );

    $oDatabase->query(
        "CREATE TABLE IF NOT EXISTS `" . phpfox::getT("advancedmarketplace_follow") . "` (
					  `user_id` int(10) unsigned NOT NULL,
					  `user_follow_id` int(10) unsigned NOT NULL,
					  PRIMARY KEY  (`user_id`,`user_follow_id`)
					);"
    );

    $oDatabase->query(
        "CREATE TABLE IF NOT EXISTS `" . phpfox::getT("advancedmarketplace_image") . "` (
					  `image_id` int(10) unsigned NOT NULL auto_increment,
					  `listing_id` int(10) unsigned NOT NULL,
					  `image_path` varchar(50) NOT NULL,
					  `server_id` tinyint(1) NOT NULL,
					  `ordering` tinyint(3) NOT NULL default '0',
					  `is_primary` tinyint(1) default '0',
					  PRIMARY KEY  (`image_id`)
					);"
    );

    $oDatabase->query(
        "CREATE TABLE IF NOT EXISTS `" . phpfox::getT("advancedmarketplace_invite") . "` (
					  `invite_id` int(10) unsigned NOT NULL auto_increment,
					  `listing_id` int(10) unsigned NOT NULL,
					  `type_id` tinyint(1) NOT NULL default '0',
					  `visited_id` tinyint(1) NOT NULL default '0',
					  `user_id` int(10) unsigned NOT NULL default '0',
					  `invited_user_id` int(10) unsigned NOT NULL default '0',
					  `invited_email` varchar(100) default NULL,
					  `time_stamp` int(10) unsigned NOT NULL,
					  PRIMARY KEY  (`invite_id`)
					);"
    );

    $oDatabase->query(
        "CREATE TABLE IF NOT EXISTS `" . phpfox::getT("advancedmarketplace_invoice") . "` (
					  `invoice_id` int(10) unsigned NOT NULL auto_increment,
					  `listing_id` int(10) unsigned NOT NULL,
					  `user_id` int(10) unsigned NOT NULL,
					  `currency_id` char(3) NOT NULL,
					  `price` decimal(14,2) NOT NULL,
					  `status` varchar(20) default NULL,
					  `time_stamp` int(10) unsigned NOT NULL,
					  `time_stamp_paid` int(10) unsigned NOT NULL default '0',
					  PRIMARY KEY  (`invoice_id`)
					);"
    );

    $oDatabase->query(
        "CREATE TABLE IF NOT EXISTS `" . phpfox::getT("advancedmarketplace_rate") . "` (
					  `rate_id` int(10) unsigned NOT NULL auto_increment,
					  `listing_id` int(10) unsigned NOT NULL,
					  `user_id` int(10) unsigned NOT NULL,
					  `timestamp` int(10) unsigned NOT NULL,
					  `rating` int(10) unsigned NOT NULL,
					  `content` text NOT NULL,
					  PRIMARY KEY  (`rate_id`)
					);"
    );

    $oDatabase->query(
        "CREATE TABLE IF NOT EXISTS `" . phpfox::getT("advancedmarketplace_recent_view") . "` (
					  `user_id` int(11) NOT NULL default '0',
					  `listing_id` int(11) NOT NULL default '0',
					  `timestamp` int(11) default NULL,
					  PRIMARY KEY  (`user_id`,`listing_id`)
					);"
    );

    $oDatabase->query(
        "CREATE TABLE IF NOT EXISTS `" . phpfox::getT("advancedmarketplace_text") . "` (
					  `listing_id` int(10) unsigned NOT NULL,
					  `description` mediumtext,
					  `description_parsed` mediumtext,
					  `short_description` mediumtext,
					  `short_description_parsed` mediumtext
					);"
    );

    $oDatabase->query(
        "CREATE TABLE IF NOT EXISTS `" . phpfox::getT("advancedmarketplace_today_listing") . "` (
					  `today_listing_id` int(10) unsigned NOT NULL auto_increment,
					  `listing_id` int(10) unsigned NOT NULL,
					  `time_stamp` int(10) unsigned NOT NULL,
					  PRIMARY KEY  (`today_listing_id`)
					);"
    );

    $oDatabase->query(
        "CREATE TABLE IF NOT EXISTS `" . phpfox::getT("advancedmarketplace_track") . "` (
            `item_id` int(10) unsigned NOT NULL,
            `user_id` int(10) unsigned NOT NULL,
            `time_stamp` int(10) unsigned NOT NULL,
            PRIMARY KEY  (`item_id`,`user_id`)
        );"
    );

    if(!$oDatabase->isField(PHPFOX::getT("user_activity"),'activity_advancedmarketplace'))
    {
        $oDatabase->query("ALTER TABLE `" . phpfox::getT("user_activity") . "` ADD COLUMN `activity_advancedmarketplace` INTEGER(10) UNSIGNED NOT NULL;");
    }

    if(!$oDatabase->isField(PHPFOX::getT("user_count"),'advancedmarketplace_invite'))
    {
        $oDatabase->query("ALTER TABLE `" . phpfox::getT("user_count") . "` ADD COLUMN `advancedmarketplace_invite` INTEGER(10) UNSIGNED NOT NULL default '0';");
    }

    if(!$oDatabase->isField(PHPFOX::getT("user_field"),'total_advlisting'))
    {
        $oDatabase->query("ALTER TABLE `" . phpfox::getT("user_field") . "` ADD COLUMN `total_advlisting` INTEGER(10) UNSIGNED NOT NULL default '0';");
    }

    if(!$oDatabase->isField(PHPFOX::getT("user_space"),'space_advancedmarketplace'))
    {
        $oDatabase->query("ALTER TABLE `" . phpfox::getT("user_space") . "` ADD COLUMN `space_advancedmarketplace` INTEGER(10) UNSIGNED NOT NULL default '0';");
    }

    $rows = $oDatabase
        ->select("name")
        ->from(phpfox::getT('advancedmarketplace_category'))
        ->execute("getSlaveRows");
    $cr = array();
    foreach ($rows as $row) {
        $cr[] = $row["name"];
    }

    $aCategories = array(
        'Community',
        'Houses',
        'Jobs',
        'Pets',
        'Rentals',
        'Services',
        'Stuff',
        'Tickets',
        'Vehicle'
    );
    $iCategoryOrder = 0;
    foreach ($aCategories as $sCategory)
    {
        if(!in_array($sCategory, $cr)){
            $iCategoryId = $oDatabase->insert(phpfox::getT('advancedmarketplace_category'), array(
                    'name' => $sCategory,
                    'is_active' => 1,
                    'ordering' => $iCategoryOrder++
                )
            );
        }
    }

    $path = phpfox::getParam('core.dir_pic') . "advancedmarketplace/";
    $mode = 0775;

    if(Phpfox::getLib('file')->getFiles($path) === false)
    {
        Phpfox::getLib('file')->mkdir($path, $mode);
    }

    //cheat block
    $oDatabase->query("INSERT INTO `" . phpfox::getT('block') . "` (`title`, `type_id`, `m_connection`, `module_id`, `product_id`, `component`, `location`, `is_active`, `ordering`, `disallow_access`, `can_move`, `version_id`) VALUES
        ('Profile Photo & Menu', 0, 'advancedmarketplace.all', 'profile', 'advanced_marketplace', 'pic', '1', 1, 1, NULL, 0, NULL);
    ");

}

ynam_install301();

?>