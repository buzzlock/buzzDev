<?php

defined('PHPFOX') or exit('NO DICE!');

class Contest_Component_Controller_Test extends Phpfox_component{


	private function _testPaypalCallback()
	{
		$aParam = array(
			'status' => 'completed',
			'aTransactionDetail' => 'he he',
			'total_paid' => 5,
			'payer_email' => 'minh@fda,.com',
			'transaction_id' => '1fads',
			'custom' =>	1
			);

		Phpfox::getService('contest.callback')->paymentApiCallback($aParam);
	}

	private function _insertPreconfiguredData()
	{
		$oDatabase = Phpfox::getLib('phpfox.database');


		$create_contest_successfully = array(
			'type_id' => 1,
			'subject' => "Contest is Successfully Created ",
			'content' => "
Hello [owner_name],

Congratulations!

Your contest [title] has been successfully created. 

[contest_url]

Regards,

[site_name]

			",
			);

		$thanks_participant = array(
			'type_id' => 2,
			'subject' => "Thanks for joining the contest ",
			'content' => "
Hello [participant_name],

Thanks for joining [title] contest. Please find the contest details, awards, participants, entries and submit an entry at [contest_url].

For further information feel free to contact [owner_name].

Have a nice day!

Regards,

[owner_name]

			"
			);

		$thanks_for_submitting_entry = array(
			'type_id' => 3,
			'subject' => "Thanks For Submitting Entry ",
			'content' => "
Hell [participant_name],

Thanks for submitting the entry. Please find more entries at [contest_url].
For further information feel free to contact [owner_name]. 

Have a nice day!

Regards,

[owner_name]

			",
			);

		$contest_closed = array(
			'type_id' => 4,
			'subject' => "Contest is Closed",
			'content' => "
Hello,

The Contest [title] is close. Please find the final results at [contest_url]

Regards,

[site_name]
			",
			);

		$contest_approved = array(
			'type_id' => 5,
			'subject' => "Contest is Approved ",
			'content' => "
Hello [owner_name],

Your contest [title] has been approved.

[contest_url]

 Regards,

[site_name]

			",
			);

		$contest_denied = array(
			'type_id' => 6,
			'subject' => "Contest is Denied ",
			'content' => "
Hello [owner_name],

Your contest [title] was denied. Please contact admin for more information. To re-publish please edit it and click Publish.

Regards,

[site_name]

			"
			);

		$invite_friend_letter = array(
			'type_id' => 7,
			'subject' => "Invite Friend Letter",
			'content' => "
Hello,

You’re invited to join contest [title]. Please find further information at [contest_url]

Regards,

[site_name]

			",
			);

		$entry_denied = array(
			'type_id' => 8,
			'subject' => "Entry is denied",
			'content' => "
Hello [entry_owner_name],

Your entry was denied. Don’t worry, please submit another one before the end date!

Regards,

[owner_name]

			",
			);

		$invite_friend_view_entry_letter = array(
			'type_id' => 9,
			'subject' => "[inviter_name] invited you to view his/her entry [entry_name] in contest [title]",
			'content' => "

You’re invited to view entry [entry_name] in contest [title]. Please find the link below

[entry_url]

Regards,

[inviter_name]


			",
			);


		$inform_winning_entries = array(
			'type_id' => 10,
			'subject' => "Congratulations! The Contest [title] has Winners!",
			'content' => "

Hi all,

We’re so glad to inform the results of the contest [title]. Please find the link below to view as details.

[contest_url]

Congratulations! 

Regards,

[owner_name]


			",
			);

		

	//make sure this table empty before inserting
		$oDatabase->query(" TRUNCATE " . Phpfox::getT('contest_emailtemplate'). "	");	

		$aInsertEmails = array($create_contest_successfully,
			$thanks_participant,
			$thanks_for_submitting_entry,
			$contest_closed,
			$contest_approved,
			$contest_denied,
			$invite_friend_letter,
			$entry_denied,
			$invite_friend_view_entry_letter,
			$inform_winning_entries
			);

		foreach($aInsertEmails as $aInsertEmail) {
			$oDatabase->insert(Phpfox::getT('contest_emailtemplate') , $aInsertEmail);
		}

		$oDatabase->query("INSERT IGNORE INTO `". Phpfox::getT('contest_category') ."` (`category_id`, `name`, `parent_id`, `time_stamp`, `used`, `is_active`) VALUES
			(11, 'Sustainable Food', 0, 1328241168, 0, 1),
			(10, 'Immigrant Rights', 0, 1328241173, 0, 1),
			(9, 'Human Trafficking', 0, 1328241176, 0, 1),
			(8, 'Human Rights', 0, 1328241180, 0, 1),
			(7, 'Health', 0, 1328241185, 0, 1),
			(6, 'Gay Rights', 0, 1328241187, 0, 1),
			(5, 'Environment', 0, 1328241191, 0, 1),
			(4, 'Education', 0, 1328241194, 0, 1),
			(3, 'Economic Justice', 0, 1328241197, 0, 1),
			(2, 'Criminal Justice', 0, 1328241200, 0, 1),
			(1, 'Animals', 0, 1328241203, 0, 1);");

	}

	public function create_some_additional_data()
	{
		$oDatabase = Phpfox::getLib('phpfox.database');

		if(!$oDatabase->isField(Phpfox::getT('user_field'),'total_contest'))
		{
			$oDatabase->query("ALTER TABLE  `".Phpfox::getT('user_field')."` ADD  `total_contest` INT( 10 ) UNSIGNED NOT NULL DEFAULT  '0'");
		}

		if(!$oDatabase->isField(Phpfox::getT('user_activity'),'activity_contest'))
		{
			$oDatabase->query("ALTER TABLE  `".Phpfox::getT('user_activity')."` ADD  `activity_contest` INT( 10 ) UNSIGNED NOT NULL DEFAULT  '0'");
		}


		$aRow = $oDatabase->select('block_id')
		->from(Phpfox::getT('block'))
		->where("m_connection ='contest.profile' AND product_id = 'younet_contest' AND module_id ='profile' AND component ='pic'")
		->execute('getRow');

		if(!isset($aRow['block_id']))
		{
		// insert the pic block for viewing in profile
			$oDatabase->query("INSERT INTO `".Phpfox::getT('block')."` (`title`, `type_id`, `m_connection`, `module_id`, `product_id`, `component`, `location`, `is_active`, `ordering`, `disallow_access`, `can_move`, `version_id`) VALUES ('Profile Photo &amp; Menu', 0, 'contest.profile', 'profile', 'younet_contest', 'pic', '1', 1, 1, NULL, 0, NULL)");
		}
	}

	public function _testsendmail()
	{
		// Phpfox::getService('contest.mail.send')->send('test', 'test', array('minhta@younetco.com'));
		Phpfox::getService('contest.mail.send')->send('test', 'test', array('minhta@younetco.com'));
	}

	public function _testsendmailFox()
	{
		Phpfox::getLib('mail')->to(2)
						->subject('test')
						->message('test')
						->send();
	}
	public function testSendAllKindOfEmailTo()
	{
		$aTypes = Phpfox::getService('contest.constant')->getAllEmailTemplateTypesWithPhrases();	

		Phpfox::getService('contest.mail.process')->setParticipant(5);

		Phpfox::getService('contest.mail.process')->setEntryOwner(1);

		foreach ($aTypes as $key => $aType) {
			Phpfox::getService('contest.mail.process')->sendEmailTo(
				$iTemplateType = $aType['id'], 
				15, 
				$aReceivers =  2
			);
		}
	}

	private function deleteAllContestTable()
	{
		$aRows = Phpfox::getLib('phpfox.database')->select("concat('DROP TABLE ', TABLE_NAME, ';') as drop_query")
					->from('INFORMATION_SCHEMA.TABLES')
					->where('TABLE_NAME like \'' . Phpfox::getT('contest') . '%\' AND table_schema = \'' . Phpfox::getParam(array('db', 'name')) . '\'')
					->execute('getSlaveRows');
		
		foreach($aRows as $aRow)
		{

				Phpfox::getLib('phpfox.database')->query($aRow['drop_query']);
		}
	}
	public function process ()
	{
		// var_dump($_SESSION['yncontest_video']);
		// var_dump(Phpfox::getService('contest.constant')->getContestTypeIdByTypeName('video'));

		// Phpfox::getService('contest.entry.item.blog')->test();

		// var_dump(Phpfox::getService('contest.constant')->getContestTypeNameByTypeId(1));
		 // $sImageName = md5(PHPFOX_TIME . 'contest_video') . '%s.jpg';
		 // $sImagePath = Phpfox::getLib('file')->getBuiltDir(Phpfox::getService('contest.contest')->getContestImageDir()) . $sImageName;
		// var_dump($sImagePath);

		// $sFullSourcePath =Phpfox::getParam('core.dir_pic'). 'video' . PHPFOX_DS . '2013\01\45c48cce2e2d7fbdea1afc51c7c6ad26%s.jpg';

		// $spath = Phpfox::getService('contest.entry.process')->copyImageToContest($sFullSourcePath, '_');

		// var_dump($spath);
		// 
		// $this->_testPaypalCallback();
		// 
		// var_dump(Phpfox::getService('contest.constant')->getAllEmailTemplateTypesWithPhrases());

		// $this->_insertPreconfiguredData()


		// var_dump(Phpfox::getService('contest.helper')->getUserImagePath(3));
		// var_dump(Phpfox::getService('contest.transaction')->getUserRequestsFromTransaction(1));


		// var_dump(Phpfox::getService('contest.mail')->getEmailTemplateByTypeId(2, 1));

		// var_dump(Phpfox::getService('contest.mail')->getEmailMessageAndSubjectFromTemplate(2, 1));
		// $this->_testsendmail();

		// Phpfox::getService('contest.contest.process')->deleteContest(1);
		// Phpfox::getService('contest.mail.process')->setInviter(1);
		// Phpfox::getService('contest.mail.process')->setEntry(16);
		// // Phpfox::getService('contest.mail.process')->setParticipant(5);
		// Phpfox::getService('contest.mail.process')->sendEmailTo(
		// 	$iTemplateType = Phpfox::getService('contest.constant')->getEmailTemplateTypeIdByTypeName('invite_friend_view_entry_letter'), 
		// 	15, 
		// 	$aReceivers =  2
		// 	);

		// $this->testSendAllKindOfEmailTo();
		// $this->create_some_additional_data();

		// Phpfox::getService('contest.contest.process')->checkAndUpdateStatusOfContests();
			// Phpfox::getService('contest.contest.process')->sendNotificationAndEmail('join_contest', 40);
			// Phpfox::getService('contest.contest.process')->sendNotificationAndEmail('approve_contest', 42);
			// Phpfox::getService('contest.contest.process')->sendNotificationAndEmail('deny_contest', 42);
			// Phpfox::getService('contest.contest.process')->sendNotificationAndEmail('approve_entry', 42, 23);
			// Phpfox::getService('contest.contest.process')->sendNotificationAndEmail('inform_winning_entry', 42);
		// $this->_testsendmailFox();
		// var_dump($aContest = Phpfox::getService('contest.contest')->getContestById(42));

		// Phpfox::getService('contest.entry.process')->duplicateBlogAttachment(8,2);
		// $iCurrentUserId = Phpfox::getUserId();
		// var_dump($_SESSION[Phpfox::getParam('core.session_prefix')]['yncontest']['submit_entry'][$iCurrentUserId]['type_id']);

		// $sTimeZone = Phpfox::getUserBy('time_zone');	

		// $aTimeZones = Phpfox::getService('core')->getTimeZones();
		// var_dump($aTimeZones[$sTimeZone]);

		// var_dump(Phpfox::getLib('date')->getTimeZone());
		// var_dump(Phpfox::getService('contest.helper')->getSessionAfterUserAddNewItem(Phpfox::getService('contest.constant')->getContestTypeIdByTypeName('video')));

		// var_dump(Phpfox::getService('contest.contest')->isShowContestEndingSoonLabel(75));
		// 	
		// var_dump(Phpfox::getService('contest.helper')->checkFeedExist(25, $sTypeId = 'contest_entry'));
		// var_dump(Phpfox::getService('contest.participant')->getListFollowingByContestId(34));
		 // Phpfox::getService('contest.contest.process')->sendNotificationAndEmail('approve_entry', 34, 37);

		Phpfox::getService('contest.category.process')->delete(6);
	}
}