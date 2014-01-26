<?php

defined('PHPFOX') or exit('NO DICE!');

/**
 * create DATABASE For version 3.01
 * @by datlv
 *
 */

function ync_install301()
{
    $oDatabase = Phpfox::getLib('database') ;

	$oDatabase->query("
	CREATE TABLE IF NOT EXISTS `".Phpfox::getT('birthdayreminder_event')."` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`user_id` int(11) NOT NULL,
		`event_id` int(11) NOT NULL,
		`event_type` text NOT NULL,
		PRIMARY KEY (`id`)
		);
	");
	
	If(!$oDatabase->tableExists(Phpfox::getT('birthdayreminder_setting')))
	{
		$oDatabase->query("
			CREATE TABLE IF NOT EXISTS `".Phpfox::getT('birthdayreminder_setting')."` (
				`create_event` int(11) NOT NULL,
				`create_event_date` int(11) NOT NULL,
				`send_mail_date` int(11) NOT NULL
				);
			");
		
		$oDatabase->query("
		INSERT INTO `".Phpfox::getT('birthdayreminder_setting')."` (`create_event`, `create_event_date`, `send_mail_date`) VALUES(0, 5, 2);");
	}
	
	If(!$oDatabase->tableExists(Phpfox::getT('birthdayreminder')))
	{
		 $oDatabase->query("
			CREATE TABLE IF NOT EXISTS `".Phpfox::getT('birthdayreminder')."` (
				`subject` text NOT NULL,
				`text` text NOT NULL
			);
			");
	
		$oDatabase->query("
		INSERT INTO `".Phpfox::getT('birthdayreminder')."` (`subject`, `text`) VALUES('Happy birthday [full_name]', 'We wish you have a happy day, here is the event that we have made for you: [event_link]. Invite your friends and enjoy your special day!');");
	}
}

ync_install301();

?>