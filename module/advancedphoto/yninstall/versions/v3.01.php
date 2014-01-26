<?php
defined('PHPFOX') or exit('NO DICE!');


//create table advancedphoto_album_tag
$this->database()->query("CREATE TABLE IF NOT EXISTS `". Phpfox::getT('advancedphoto_album_tag') ."` (
  `tag_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `album_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `tag_user_id` int(10) unsigned NOT NULL,
  content varchar(255) default null, 
  `time_stamp` int(10) unsigned NOT NULL,
  PRIMARY KEY (`tag_id`),
  KEY `album_id` (`album_id`),
  KEY `album_id_3` (`album_id`,`tag_user_id`),
  KEY `album_id_4` (`album_id`,`user_id`)
) AUTO_INCREMENT=1  ;");

/*
//create table advancedphoto_strip
$this->database()->query("CREATE TABLE IF NOT EXISTS `". Phpfox::getT('advancedphoto_strip') ."` (
  `strip_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `mode` tinyint unsigned NOT NULL,
  `is_enable` tinyint(1) NOT NULL,
  `time_stamp` int(10) unsigned NOT NULL,
  PRIMARY KEY (`strip_id`)
) AUTO_INCREMENT=1  ;");

//create table advancedphoto_strip_photo
$this->database()->query("CREATE TABLE IF NOT EXISTS `". Phpfox::getT('advancedphoto_strip_photo') ."` (
  `strip_id` int(10) unsigned NOT NULL,
  `photo_id` int(10) unsigned NOT NULL,
  `ordering` int(10) DEFAULT '0'  ,
  `time_stamp` int(10) unsigned NOT NULL,
  PRIMARY KEY (`strip_id`,`photo_id`)
) ;");

//create table advancedphoto_slide_type
$this->database()->query("CREATE TABLE IF NOT EXISTS `". Phpfox::getT('advancedphoto_slide_type') ."` (
  `type_id` int(10) unsigned NOT NULL ,
  `description` text DEFAULT NULL,
  `title` text NOT NULL,
  PRIMARY KEY (`type_id`)
) AUTO_INCREMENT=1  ; ;");

//create table advancedphoto_album_slide_badge
$this->database()->query("CREATE TABLE IF NOT EXISTS `". Phpfox::getT('advancedphoto_album_slide_badge') ."` (
  `badge_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `album_id` int(10) unsigned NOT NULL,
  `badge_code` mediumtext,
  `slide_type_id` int(3) unsigned NOT NULL,
  PRIMARY KEY (`badge_id`)
) AUTO_INCREMENT=1  ;");
 * 
 */


//ordering field is added to order photos in an album, 
//photo without album will have ordering = 0
if(!$this->database()->isField(Phpfox::getT('photo'),'yn_ordering'))
{
   $this->database()->query("ALTER TABLE `".Phpfox::getT('photo')."` ADD `yn_ordering` INT(10) UNSIGNED DEFAULT '0' ");
}

if(!$this->database()->isField(Phpfox::getT('photo'),'yn_location'))
{
   $this->database()->query("ALTER TABLE `".Phpfox::getT('photo')."` ADD `yn_location` text DEFAULT NULL ");
}

// allow album to be featured
if(!$this->database()->isField(Phpfox::getT('photo_album'),'yn_is_featured'))
{
   $this->database()->query("ALTER TABLE `".Phpfox::getT('photo_album')."` ADD `yn_is_featured` TINYINT(1) UNSIGNED DEFAULT '0' ");
}

//ordering field is added to order albums of an user 
if(!$this->database()->isField(Phpfox::getT('photo_album'),'yn_ordering'))
{
   $this->database()->query("ALTER TABLE `".Phpfox::getT('photo_album')."` ADD `yn_ordering` INT(10) UNSIGNED DEFAULT '0' ");
}

//location of the album
if(!$this->database()->isField(Phpfox::getT('photo_album'),'yn_location'))
{
   $this->database()->query("ALTER TABLE `".Phpfox::getT('photo_album')."` ADD `yn_location` text DEFAULT NULL");
}


//field for activity points
if(!$this->database()->isField(Phpfox::getT('user_activity'),'activity_advancedphoto'))
{
  $this->database()->query("ALTER TABLE  `".Phpfox::getT('user_activity')."` ADD  `activity_advancedphoto` INT( 10 ) UNSIGNED NOT NULL DEFAULT  '0'");
}

if(!$this->database()->isField(Phpfox::getT('user_field'),'total_advancedphoto'))
{
  $this->database()->query("ALTER TABLE  `".Phpfox::getT('user_field')."` ADD  `total_advancedphoto` INT( 10 ) UNSIGNED NOT NULL DEFAULT  '0'");
}

//slide type
if(!$this->database()->isField(Phpfox::getT('photo_album'),'yn_slide_type'))
{
   PHPFOX::getLib("database")->query("ALTER TABLE `".Phpfox::getT('photo_album')."` ADD COLUMN `yn_slide_type` VARCHAR(25) NOT NULL DEFAULT 'random' ");
}

//indexing database
if(!$this->database()->isIndex(Phpfox::getT('photo'), 'yn_ordering'))
{

	 $this->database()->query("CREATE INDEX yn_ordering ON " .PHPFOX::getT('photo')." (yn_ordering) ");

}


if(!$this->database()->isIndex(Phpfox::getT('photo_album'), 'yn_ordering'))
{

	 $this->database()->query("CREATE INDEX yn_ordering ON " .PHPFOX::getT('photo_album')." (yn_ordering) ");

}

// deactivate feed share link of module photo
   $this->database()->query("DELETE FROM `".Phpfox::getT('feed_share')."` WHERE module_id ='photo' AND icon = 'photo.png'");


// make sure the module photo menu won't appear
   $this->database()->query("UPDATE `".Phpfox::getT('menu')."` SET is_active = 0 WHERE module_id ='photo' AND m_connection = 'main' AND is_active =1 AND url_value = 'photo' ");

//adding block pic to view photos, album on profile


   //firstly check whether the block exits
   $aRow = $this->database()->select('block_id')
					   ->from(Phpfox::getT('block'))
					   ->where("m_connection ='advancedphoto.profile' AND product_id = 'advancedphoto' AND module_id ='profile' AND component ='pic'")
					   ->execute('getRow');

	if(!isset($aRow['block_id']))
	{
		// insert the pic block for viewing in profile
		$this->database()->query("INSERT INTO `".Phpfox::getT('block')."` (`title`, `type_id`, `m_connection`, `module_id`, `product_id`, `component`, `location`, `is_active`, `ordering`, `disallow_access`, `can_move`, `version_id`) VALUES ('Profile Photo &amp; Menu', 0, 'advancedphoto.profile', 'profile', 'advancedphoto', 'pic', '1', 1, 1, NULL, 0, NULL)");
	}

?>