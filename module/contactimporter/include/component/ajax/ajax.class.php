<?php

/*
 * @copyright        [YouNet_COPYRIGHT]
 * @author           YouNet Development
 * @package          Module_Contactimporter
 * @version          2.06
 *
 */
defined('PHPFOX') or exit('NO DICE!');
?>

<?php

class Contactimporter_Component_Ajax_Ajax extends Phpfox_Ajax
{

	public function callLinkedIn()
	{
		//Phpfox::isUser(true);
		Phpfox::getBlock('contactimporter.linkedin', array());
	}
	
	public function callCsv()
	{
		//Phpfox::isUser(true);
		Phpfox::getBlock('contactimporter.csv', array());
	}
	
	public function callTypingmanual()
	{
		//Phpfox::isUser(true);
		Phpfox::getBlock('contactimporter.typingmanual', array());
	}
	
	public function callFacebook()
	{
		//Phpfox::isUser(true);
		Phpfox::getBlock('contactimporter.facebook', array());
	}

	public function updateOrderProviders()
	{
		$providerSort = $_REQUEST['order'];
		$order = array();
		$providerSort = trim($providerSort);
		$order = split(' ', $providerSort);
		foreach ($order as $key => $value)
		{
			phpfox::getService('contactimporter') -> updateOrderProviders($key, $value);
		}
	}

	public function updateProviderActive()
	{
		$provider_name = $this -> get('provider_name');
		$is_actived = (int)$this -> get('is_actived');
		if ($provider_name)
		{
			$is_actived = (int)(!$is_actived);
			Phpfox::getLib('phpfox.database') -> update(Phpfox::getT('contactimporter_providers'), array('enable' => $is_actived), 'name ="' . $provider_name . '"');
			Phpfox::getService('contactimporter') -> removeCache();
			$str = $is_actived ? Phpfox::getPhrase('core.yes') : Phpfox::getPhrase('core.no');
			$this -> html('#update_active_' . $provider_name, '<a href="javascript:updateprovideractive(' . "'" . $provider_name . "'," . $is_actived . ');">' . $str . '</a>');
		}
	}

	public function callYahoo()
	{
		/**
		 * skip checking isUser
		 */
		//Phpfox::isUser(true);
		Phpfox::getBlock('contactimporter.yahoo', array());
	}

	public function callGmail()
	{
		/**
		 * skip checking isUser
		 */
		// Phpfox::isUser(true);
		Phpfox::getBlock('contactimporter.gmail', array());
	}

	public function callHotmail()
	{
		/**
		 * skip checking isUser
		 */
		//Phpfox::isUser(true);
		Phpfox::getBlock('contactimporter.hotmail', array());
	}

	public function callImporterForm()
	{
		Phpfox::getBlock('contactimporter.importerform', array());
	}

	public function callTwitter()
	{
		/**
		 * skip checking isUser
		 */
		//Phpfox::isUser(true);

		if(isset($_SESSION['twitter']['data']))
		{
			unset($_SESSION['twitter']['data']);
		}
		Phpfox::getBlock('contactimporter.twitter', array());
	}

	public function reSendInvitation()
	{

		$message = Phpfox::getPhrase('contactimporter.default_invite_message_text');
		$iInvite = $this -> get('invite_id');
		$sMail = Phpfox::getLib('phpfox.database') -> select('email') -> from(Phpfox::getT('invite'), 'invite') -> where('invite.invite_id = ' . $iInvite . ' AND is_resend = 0') -> execute('getSlaveRow');

		if (empty($sMail))
		{
			$this -> alert(Phpfox::getPhrase('contactimporter.you_have_resend_this_invitation_before'));
		}
		else
		{
			if (Phpfox::getLib('mail') -> checkEmail($sMail))
			{
				$sLink = Phpfox::getLib('url') -> makeUrl('invite', array('id' => $iInvite));
				$subcribe_message = "";
				$is_unsubcribe = phpfox::getLib('database') -> select('param_values') -> from(phpfox::getT('contactimporter_settings')) -> where('settings_type="is_unsubcribed"') -> execute('getRow');
				if (isset($is_unsubcribe['param_values']) && $is_unsubcribe['param_values'])
				{
					$encoded = PREG_REPLACE("'(.)'e", "dechex(ord('\\1'))", $sMail['email']);
					//$whyyouwereinvited = Phpfox::getParam('core.path').'contactimporter/whyyouwereinvited?id='. $encoded;
					$whyyouwereinvited = phpfox::getLib('url') -> makeURL('contactimporter/whyyouwereinvited');
					$whyyouwereinvited .= '?id=' . $encoded;
					//$blockall = Phpfox::getParam('core.path').'contactimporter/blockallfurtheremailmessages?id='. $encoded;
					$blockall = Phpfox::getLib('url') -> makeURL('contactimporter/blockallfurtheremailmessages');
					$blockall .= '?id=' . $encoded;
					$subcribe_message = '<a target="_blank" href="' . $whyyouwereinvited . '">' . Phpfox::getPhrase('contactimporter.find_out_why_you_were_invited_by_clicking_here') . '</a><br /><a target="_blank" href="' . $blockall . '">' . Phpfox::getPhrase('contactimporter.block_all_further_email_mesages') . '</a>';
				}
				$unsubscribeEmail = phpfox::getService('contactimporter') -> getEmailUnsubscribe();
				if (!in_array($sMail['email'], $unsubscribeEmail))
				{

					$bSent = Phpfox::getLib('mail') -> to($sMail['email'])
					// ->  fromEmail(Phpfox::getUserBy('email'))
					//->fromName(Phpfox::getUserBy('full_name'))
					-> subject(array(
						'invite.full_name_invites_you_to_site_title',
						array(
							'full_name' => Phpfox::getUserBy('full_name'),
							'site_title' => Phpfox::getParam('core.site_title')
						)
					)) -> message(array(
						'invite.full_name_invites_you_to_site_title_link',
						array(
							'full_name' => Phpfox::getUserBy('full_name'),
							'site_title' => Phpfox::getParam('core.site_title') . '<br/>' . $message . '<br/>' . $subcribe_message,
							'link' => $sLink
						)
					)) -> send();

					if ($bSent)
					{
						Phpfox::getLib('database') -> update(Phpfox::getT('invite'), array('is_resend' => 1), 'invite_id=' . $iInvite);
						echo Phpfox::getPhrase('contactimporter.the_invitation_message_was_resent_successfully');
					}
					else
						echo Phpfox::getPhrase('contactimporter.the_invitaion_message_was_not_resent');
				}
				else
				{
					echo Phpfox::getPhrase('contactimporter.the_invitation_was_added_in_the_unsubscribe_please_do_not_send_anything_else');
				}
			}
		}
	}

	public function subscribe()
	{
		$email = $this -> get('email');
		$del = phpfox::getService('contactimporter') -> subscribe($email);
		if ($del)
		{
			echo Phpfox::getPhrase('contactimporter.email_is_subscribed_successfullly');
		}
		else
		{
			echo Phpfox::getPhrase('contactimporter.email_did_not_subscribe');
		}
	}

	public function sendPopup()
	{
		$sProvider = $this -> get("provider");
		$iTotal = $this -> get("total");
		$sSelectedContacts = $this->get('selecteds');
		$aContacts = explode(',', $sSelectedContacts);
		$iRemain = Phpfox::getService('contactimporter')->getNumberOfRemainingInvitationInPeriod($sProvider);
		if($iRemain < 0)
		{
			$iRemain = 0 ;
		}

		$sNoticeQuota = '';
		$iTotalForQueue = 0;
		if($iTotal > $iRemain)	
		{
			$iTotalForQueue = $iTotal - $iRemain;
			$iTotal = $iRemain;
			$sNoticeQuota = Phpfox::getPhrase('contactimporter.notice_quota_total_remain', array('total' => $iRemain, 'remain' => $iTotalForQueue));
			$sMessage = $this -> get("message");
			// we send iTotal invitation and store the remainings in queue
			$aContactForQueue = array_slice($aContacts, $iTotal);
			$iQueueId = Phpfox::getService('contactimporter.process') -> addQueue($sProvider, $iTotalForQueue, $sMessage, serialize($aContactForQueue));
			Phpfox::getService('contactimporter.process')->insertFriendListIntoQueue($aContactForQueue, $sProvider, $iQueueId);
		}

			
		$sTitle = ucfirst($sProvider);
		$this -> setTitle($sTitle);

		if ($iTotal == 0 && $iTotalForQueue == 0)
		{
			echo '<div class="error_message">' . Phpfox::getPhrase('contactimporter.no_contacts_were_selected') . '</div>';
		}
		else
		{
			/**
			 * skip checking isUser
			 */
			// Phpfox::isUser(true);

			Phpfox::getBlock('contactimporter.sendpopup', array(
				"sProvider" => $sProvider,
				"iTotal" => $iTotal,
				'sNoticeQuota' => $sNoticeQuota
			));
			$this -> call('<script type="text/javascript">$Core.loadInit();</script>');
		}
	}

	public function sendInvite()
	{
		$iLimit = 5;
		$sProvider = $this -> get("provider");
		$sMessage = $this -> get("message");
		$sContacts = $this -> get("contacts");
		$iTotal = $this -> get("total");
		$iFail = $this -> get("fail");

		if($iTotal < $iLimit)
		{
			$iLimit = $iTotal;
		}
		$aContacts = explode(',', $sContacts);
		$aEmails = $aTemp = array();
		$iUserId = Phpfox::getUserId();
		$sSubject = NULL;
		$iTotalContact = count($aContacts);

		for ($i = 0; $i < $iTotalContact; $i++)
		{
			if ($i < $iLimit)
			{
				$aEmails[] = $aContacts[$i];
			}
			else
			{
				$aTemp[] = $aContacts[$i];
			}
		}

		$sError = '';

		$result = Phpfox::getService('contactimporter.process') -> sendInvite($sProvider, $iUserId, $aEmails, $sSubject = NULL, $sMessage, $sLink = NULL);
		if(is_array($result))
		{
			Phpfox::getService('contactimporter.process')->insertFailedInvitationIntoQueue($result['aFaileds'], $sProvider);

			Phpfox::getService('contactimporter.process')->insertSuccessedInvitationIntoQueue($result['aSuccesseds'], $sProvider);
			$iFail = $iFail + count($result['aFaileds']);
			$aContacts = $aTemp;
			$sContacts = implode(',', $aContacts);
			$iTotalSent = $iTotal - count($aContacts);
			$iSuccess = $iTotalSent - $iFail;
			$iPercent = (($iTotalSent * 100) / $iTotal) . '%';	
		}
		else
		{
			// it is not a array
			if($result)
			{
				$aContacts = $aTemp;
				$sContacts = implode(',', $aContacts);
				$iSuccess = $iTotal - count($aContacts);
				$iPercent = (($iSuccess * 100) / $iTotal) . '%';
			}
		}
		
		
		
		echo json_encode(array(
			'success' => $iSuccess,
			'totalsent' => $iTotalSent,
			'percent' => $iPercent,
			'contacts' => $sContacts,
			'fail' => $iFail,
			'error' => $sError
		));
	}

	public function sendallPopup()
	{
		$provider = $this -> get("provider");
		$friends_count = $this -> get("friends_count");
		$sTitle = ucfirst($provider);
		$this -> setTitle($sTitle);
		
		# skip checking user Phpfox::isUser(true);
		
		Phpfox::getBlock('contactimporter.sendallpopup', array(
			"provider" => $provider,
			"friends_count" => $friends_count
		));
		
		$this -> call('<script type="text/javascript">$Core.loadInit();</script>');
	}

	public function addQueue()
	{
		$provider = $this -> get("provider");
		
		$friends_count = $this -> get("friends_count");
		$friends_ids = '';
		
		if (isset($_SESSION['contactimporter'][$provider]) && ($provider == 'yahoo' || $provider == 'hotmail' || $provider == 'gmail'))
		{
			$friends_ids = serialize($_SESSION['contactimporter'][$provider]);
		}
		
		$message = $this -> get("message");
		Phpfox::getService('contactimporter.process') -> addQueue($provider, $friends_count, $message, $friends_ids);
	}

	public function removeRemainingInvitations(){
		$user_id = Phpfox::getUserId();
		PHpfox::getService('contactimporter.process')->removeRemainingInvitations($user_id);
		$this->call("$('#remainInvitations').html('0');");
		$this->alert(Phpfox::getPhrase('contactimporter.remove_remaining_invitations_successfully'));
	}
}
?>