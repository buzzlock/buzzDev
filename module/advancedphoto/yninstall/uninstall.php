<?php

//check feed share link of module photo exist or not
$aRow = $this->database()->select('share_id')
					   ->from(Phpfox::getT('feed_share'))
					   ->where("module_id ='photo' AND icon = 'photo.png'")
					   ->execute('getRow');
if(!isset($aRow['share_id']))
{
	// insert the feed share link of module photo again
	$this->database()->query("INSERT INTO `".Phpfox::getT('feed_share')."`(`product_id`, `module_id`, `title`, `description`, `block_name`, `no_input`, `is_frame`, `ajax_request`, `no_profile`, `icon`, `ordering`) VALUES ('phpfox', 'photo', '{phrase var=''photo.photo''}', '{phrase var=''photo.say_something_about_this_photo''}', 'share', 0, 1, NULL, 0, 'photo.png', 1)");
}


// make sure the module photo appear 
$this->database()->query("UPDATE `".Phpfox::getT('menu')."` SET is_active = 1 WHERE module_id ='photo' AND m_connection = 'main' AND is_active = 0 AND url_value = 'photo' ");


?>