<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class Fundraising_Component_Controller_Test extends Phpfox_Component {

	public function process() {

		
//		Phpfox::getService('fundraising.mail.send')->sendEmailsInQueue();
//		Phpfox::getService('fundraising.image.process')->delete(67);
//		Phpfox::getService('fundraising.cache')->remove('featured', 'featured');

//		$bResult = Phpfox::getService('fundraising.mail.process')->sendEmailTo($sTemplateType = Phpfox::getService('fundraising.mail')->getTypesCode('createcampaignsuccessful_owner'), 1, $aReceivers = array(1));
//		$bResult = Phpfox::getService('fundraising.campaign.process')->closeCampaign(3, 'fdsafsdafafsdaf');
//		var_dump($bResult);
//		Phpfox::getService('fundraising.user.process')->addDonor($iTransactionId = 7, $iAmount = 2);

//		$aParam = array(
//			'gateway' => 'paypal',
//			'ref' => 423432,
//			'status' => 'completed',
//			'item_number' => 1,
//			'custom' => 11,
//			'total_paid' => 2,
//			'currency' => 'USD',
//			'payer_email' => 'minhtruonganh7@gmail.com',
//			'transaction_id' => 423432,
//			//in case need more infor
//			'aTransactionDetail' => array()
//		);
//		Phpfox::getService('fundraising.callback')->paymentApiCallback($aParam);
//		 $oMail = Phpfox::getLib('mail');
//		 $bIsSent = $oMail->to('minh.truonganh7@gmail.com')
//                        ->subject('testset')
//                        ->message('dsfadsaf')
//                        ->send();
////
//		 var_dump($bIsSent);
//		Phpfox::getService('fundraising.campaign')->notifyToAllFollowers(14);
//		Phpfox::getService('fundraising.user')->getIdsOfUserDonors(20);
//		Phpfox::getService('fundraising.mail.process')->sendEmailToAllDonors($iTemplateType = 2, $iCampaignId = 20);
//		echo (Phpfox::getService('fundraising.category.display')->getMenu());

//		Phpfox::getService('fundraising.mail.process')->sendEmailTo(Phpfox::getService('fundraising.mail')->getTypesCode('createcampaignsuccessful_owner'), $iCampaignId = 20, 3);
//		Phpfox::getService('fundraising.mail.process')->sendEmailTo(Phpfox::getService('fundraising.mail')->getTypesCode('thankdonor_donor'), $iCampaignId = 20, 3, NULL, 21);
//				Phpfox::getService('fundraising.mail.process')->sendEmailTo(Phpfox::getService('fundraising.mail')->getTypesCode('updatedonor_owner'), $iCampaignId = 20, 3, NULL, 21);
//				Phpfox::getService('fundraising.mail.process')->sendEmailTo(Phpfox::getService('fundraising.mail')->getTypesCode('campaignexpired_owner'), $iCampaignId = 20, 3);
//				Phpfox::getService('fundraising.mail.process')->sendEmailTo(Phpfox::getService('fundraising.mail')->getTypesCode('campaignexpired_donor'), $iCampaignId = 20, 3, NULL, 21);
//				Phpfox::getService('fundraising.mail.process')->sendEmailTo(Phpfox::getService('fundraising.mail')->getTypesCode('campaigncloseduetoreach_owner'), $iCampaignId = 20, 3);
//				Phpfox::getService('fundraising.mail.process')->sendEmailTo(Phpfox::getService('fundraising.mail')->getTypesCode('campaigncloseduetoreach_donor'), $iCampaignId = 20, 3, NULL, 21);

		/*
	$oDatabase = Phpfox::getLib('database') ;
		
    $createcampaignsuccessful_owner = array(
        'type' => 1,
        'email_subject' => "Your Campaign has been created on [site_name] ",
        'email_template' => "
 Hello [owner_name]

 Congratulations on launching your \"[title]\"

 Here's a list of this to do to get you off to a great start:
 <ul>
     <li>Make your Campaign look great - add Photo galleries, YouTube URL and descriptions </li>
     <li>Send a donation email to everyone you know with our email feature </li>
     <li>Share your Campaign on Facebook, Twitter and other social networks </li>
     <li>Use Promote feature to get more donation </li>
 </ul>

 Let the fundraising begin",
    );

    $thankdonor_donor = array(
        'type' => 2,
        'email_subject' => "Thank you for contributing a campaign ",
	'email_template' => "
 Dear [donor_name]

 Thank you for choosing to contribute [title] of [owner_name]. Your contribution is very much appreciated and the money you will raise will go towards our much need [description].
 Every dollar you raise makes a real difference for our campaign.

 [campaign_url]

 Thank you for making a difference and good luck."
    );

    $updatedonor_owner = array(
        'type' => 3,
        'email_subject' => "You have a new contributor ",
	'email_template' => "
 Hello [owner_name]

 [donor_name] has been contributed to your Campaign.

 [campaign_url]",
    );

    $campaignexpired_owner = array(
        'type' => 4,
        'email_subject' => "Your Campaign Expired",
	'email_template' => "
 Hello [owner_name]

 Your campaign has been expired and hidden from listings:

 [campaign_url]",
    );

    $campaignexpired_donor = array(
        'type' => 5,
        'email_subject' => "The campaign which you donated has been expired",
	'email_template' => "
 Dear [donor_name]

 The Campaign \"[title]\" is expired, please go to this link to check the status of this campaign:

 [campaign_url]

 Thank you for making difference and good luck

 Regards",
    );

    $campaigncloseduetoreach_owner = array(
        'type' => 6,
        'email_subject' => "Your Campaign Reached the Fundraising Goal",
	'email_template' => "
 Hello [owner_name],

 Your campaign has been closed due to reaching the fundraising goal. Please check it here:

 [campaign_url]"
    );

    $campaigncloseduetoreach_donor = array(
        'type' => 7,
        'email_subject' => "The Campaign which you donated has been reached goal",
	'email_template' => "
 Dear all donors

 Your campaign that you donated has been closed due to reaching the fundraising goal. Please check it here:

 [campaign_url]",
    );

    $campaignclose_owner = array(
        'type' => 8,
        'email_subject' => "Your Campaign has been closed ",
	'email_template' => "
 Hello [owner_name]

 Your campaign has been closed due to [admin_reason]. Please check it here:

 [campaign_url]",
    );

    $invitefriendletter_template = array(
        'type' => 9,
        'email_subject' => "[inviter_name] invited you to the fundraising campaign [title]",
        'email_template' => "
 Hello,

 [inviter_name] invited you to \"[title]\" 

 To check out this fundraising campaign, follow the link below:
[campaign_url]

 In addition, [inviter_name] added the following personal message

 Friends,

 I have just created a fundraising campaign: \"[title]\", since I care
deeply about this crucial issue

 I'm trying to collect money for this issue, and I could really use your help

 To read more about what I am trying to do and to donate my fundraising , click here:
 [campaign_url]

 It will just take a minute

 One you are done, please ask your friends to donate the fundraising campaign as well.

 Thank you for making a difference and good luck.

 Regards, 
 [owner_name]
		",
    );

	//make sure this table empty before inserting
	$oDatabase->query(" TRUNCATE " . Phpfox::getT('fundraising_email_template'). "	");	

    $aInsertEmails = array($createcampaignsuccessful_owner,$thankdonor_donor,$updatedonor_owner,$campaignexpired_owner,$campaignexpired_donor,$campaigncloseduetoreach_owner,$campaigncloseduetoreach_donor,$campaignclose_owner,$invitefriendletter_template);

	 foreach($aInsertEmails as $aInsertEmail) {
        $oDatabase->insert(Phpfox::getT('fundraising_email_template') , $aInsertEmail);
    }
		
		 
	}

	*/
	}
}

?>
