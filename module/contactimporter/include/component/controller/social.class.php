<?php

/*
 * @copyright        [YouNet_COPYRIGHT]
 * @author           YouNet Company
 * @package          Module_Contactimporter
 * @version          2.06
 *
 */
defined('PHPFOX') or exit('NO DICE!');

class Contactimporter_Component_Controller_Social extends Phpfox_Component
{

	public function process()
	{
		//Phpfox_Error::skip(true);
		$sProvider = $this -> request() -> get('req2');
		$iPage = $this -> request() -> get('page', 1);
		$iLimit = $iMaxInvitation = Phpfox::getService('contactimporter') -> getMaxInvitation();

		
		$iOffset = ($iPage - 1) * $iLimit;
		$sPrevPage = $sNexrPage = '';
		$aJoineds = array();
		if ($sProvider == 'facebook')
		{
			$oFacebook = Phpfox::getService('contactimporter.facebook');
			$aProfile = $oFacebook -> getProfile();
			$iCnt = $aProfile['friend_count'];
			if ($iCnt > 0)
			{
				list($aInviteLists, $aJoineds, $iCountInvited) = $oFacebook -> getFriends($iPage, $iLimit);
				$iCnt = $_SESSION['contactimporter']['facebook']['total_friends'];
			}
			if (!$iCnt)
			{
				if ($iCountInvited > 0)
				{
					$this -> url() -> send('contactimporter', null, Phpfox::getPhrase('contactimporter.you_have_sent_the_invitations_to_all_of_your_friends'));
				}
				else
				{
					$this -> url() -> send('contactimporter', null, Phpfox::getPhrase('contactimporter.there_is_not_contact_in_your_account'));
				}
				exit ;
			}
		}
		if ($sProvider == 'twitter')
		{
			$oTwitter = Phpfox::getService('contactimporter.twitter');
			$aProfile = $oTwitter -> getProfile();
			$iCnt = $aProfile['friend_count'];
			if ($iCnt > 0)
			{
				list($aInviteLists, $sPrevPage, $sNexrPage, $aJoineds, $iCountInvited) = $oTwitter -> getFriends($iPage, $iLimit);
				$iCnt -= $iCountInvited;
				// $_SESSION['contactimporter']['twitter']['invited_friends'];
			}

			if (!$iCnt)
			{
				if ($iCountInvited > 0)
				{
					$this -> url() -> send('contactimporter', null, Phpfox::getPhrase('contactimporter.you_have_sent_the_invitations_to_all_of_your_friends'));
				}
				else
				{
					$this -> url() -> send('contactimporter', null, Phpfox::getPhrase('contactimporter.there_is_not_contact_in_your_account'));
				}
				exit ;
			}
		}

		if ($sProvider == 'linkedin')
		{
			$oLinkedIn = Phpfox::getService('contactimporter.linkedin');
			list($iCnt, $aInviteLists, $aJoineds, $aErrors, $iCountInvited) = $oLinkedIn -> getFriends($iPage, $iLimit);
			if (!$iCnt)
			{
				if ($iCountInvited > 0)
				{
					$this -> url() -> send('contactimporter', null, Phpfox::getPhrase('contactimporter.you_have_sent_the_invitations_to_all_of_your_friends'));
				}
				else
				{
					$this -> url() -> send('contactimporter', null, Phpfox::getPhrase('contactimporter.there_is_not_contact_in_your_account'));
				}
				exit ;
			}
		}


		$this -> template() -> assign(array(
			'sPrevPage' => $sPrevPage,
			'sNexrPage' => $sNexrPage,
			'iCnt' => $iCnt,
			'iLimit' => $iLimit,
			'iPage' => $iPage,
			'sProvider' => $sProvider,
			'aInviteLists' => $aInviteLists,
			'aJoineds' => $aJoineds,
			'provider_box' => $sProvider,
			'sCoreUrl' => phpfox::getParam('core.path'),
			'sIniviteLink' => Phpfox::getLib('url') -> makeUrl('contactimporter.inviteuser', array('id' => Phpfox::getUserId())),
		));
		$this -> template() -> setHeader(array(
			'Ynscontactimporter.css' => 'module_contactimporter',
			'contactimporter.js' => 'module_contactimporter',
		));

		Phpfox::getLib('pager') -> set(array(
			'page' => $iPage,
			'size' => $iLimit,
			'count' => $iCnt
		));
		$this -> template() -> setPhrase(array(
			'contactimporter.are_you_sure_you_want_to_delete',
			'contactimporter.are_you_sure_you_want_to_delete_this_action_will_delete_all_feeds_belong_to',
			'contactimporter.you_can_send',
			'contactimporter.invitations_per_time',
			'contactimporter.you_have_selected',
			'contactimporter.contacts',
			'contactimporter.select_current_page',
			'contactimporter.unselect_current_page',
			'contactimporter.your_email_is_empty',
			'contactimporter.this_mail_domain_is_not_supported',
			'contactimporter.email_should_not_be_left_blank',
			'contactimporter.no_contacts_were_selected',
			'contactimporter.updating'
		));
	}
}