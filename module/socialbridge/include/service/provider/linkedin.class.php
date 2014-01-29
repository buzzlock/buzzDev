<?php
defined('PHPFOX') or exit('NO DICE!');

require_once dirname(dirname(__file__)) . '/provider/abstract.class.php';
require_once dirname(dirname(__file__)) . '/libs/linkedin_3.2.0.class.php';

class Socialbridge_Service_Provider_Linkedin extends SocialBridge_Service_Provider_Abstract
{

	protected $_name = 'linkedin';

	public function getProfile()
	{
		if (NULL == $this -> _profile)
		{
			$oLinkedIn = $this -> getApi();

			$result = $oLinkedIn -> profile('~:(id,first-name,last-name,picture-url,email-address)');

			$me = json_decode(json_encode(simplexml_load_string($result['linkedin'])), 1);

			$aProfile = array();

			$aProfile['full_name'] = isset($me['first-name']) ? $me['first-name'] : '';

			if (isset($me['last-name']))
			{
				$aProfile['full_name'] .= ' ' . $me['last-name'];
			}
			$aProfile['user_name'] = preg_replace("#(\W+)#", '', $aProfile['full_name']);
			$aProfile['identity'] = isset($me['id']) ? $me['id'] : "";
			$aProfile['service'] = 'linkedin';
			$aProfile['email-address'] = isset($me['email-address']) ? $me['email-address'] : "";
			if (isset($me['picture-url']))
			{
				$aProfile['img_url'] = $me['picture-url'];
			}
			$this -> _profile = $aProfile;
		}

		return $this -> _profile;
	}

	/**
	 * get api object
	 * @return LinkedInSBYN
	 */
	public function getApi()
	{
		if (null == $this -> _api)
		{

			$config = $this -> getSetting();

			$config['appKey'] = $config['api_key'];
			$config['appSecret'] = $config['secret_key'];
			$config['callbackUrl'] = Phpfox::getParam('core.path') . 'module/socialbridge/static/php/linkedin.php?lResponse=1';

			$this -> _api = new LinkedInSBYN($config);

			list($token, $profile) = $this -> getTokenData();

			if ($token && is_array($token))
			{
				$this -> _api -> setToken($token);
				$this -> _profile = $profile;
			}
		}
		return $this -> _api;
	}

	/**
	 * get list of conntected twitters friends of current viewer
	 * alias to get contacts
	 * @TODO get a large of contact etc: 100,000 followers
	 * @param int $iPage OPTIONAL DEFAULT  = 1
	 * @param int $iLimit OPTIONAL DEFAULT = 50
	 * @return array
	 */
	public function getFriends($iPage = 1, $iLimit = 50)
	{
		return $this -> getContacts($iPage, $iLimit);
	}

	/**
	 * get list of facebook friends of current viewer
	 * @param int $iPage OPTIONAL DEFAULT  = 1
	 * @param int $iLimit OPTIONAL DEFAULT = 50
	 * @return array
	 */
	public function getContacts($iPage = 1, $iLimit = 50)
	{
		$iCnt = 0;
		$iCountInvited = 0;
		$aRows = $aMails = array();
		$aInviteList = $aJoineds = $aInvalids = $aErrors = array();

		$this -> getProfile();

		$aContacts = array();

		$oLinkedIn = $this -> getApi();

		// RETURN THE CONTENTS OF THE CALL
		$response = $oLinkedIn -> connections();

		/**
		 * @TODO process when failed.
		 */
		if ($response['success'] == FALSE)
		{

		}

		$data = json_decode(json_encode(simplexml_load_string($response['linkedin'])), 1);

		if ($data['@attributes']['total'] <= 0)
		{

		}
		else
		{
			$aInviteds = Phpfox::getService('contactimporter') -> getInviteds();

			foreach ($data['person'] as $item)
			{
				$aContacts[] = array(
					'id' => isset($item['id']) ? $item['id'] : '',
					'name' => sprintf('%s %s', $item['first-name'], $item['last-name']),
					'pic' => isset($item['picture-url']) ? $item['picture-url'] : '',
					'headline' => isset($item['headline']) ? $item['headline'] : '',
				);
			}
		}

		$_SESSION['contactimporter']['linkedin'] = $aContacts;

		$iCnt = count($aContacts);
		$iOffset = ($iPage - 1) * $iLimit;
		$aContacts = array_slice($aContacts, $iOffset, $iLimit);
		$aIds = array();
		$aInviteList = array();
		if(count($aContacts) <= 0)
		{
			$aErrors['contacts'] = Phpfox::getPhrase('contactimporter.there_is_not_contact_in_your_account');
		}
		foreach ($aContacts as $aContact)
		{
			$aInviteList[] = $aContact;
			$aIds[] = $aContact['id'];
		}
		$aJoineds = Phpfox::getService('contactimporter') -> checkSocialJoined($aIds);
		$aInviteList = Phpfox::getService('contactimporter') -> processSocialRows($aInviteList);

		return array(
			'iInvited' => $iCountInvited,
			'iCnt' => $iCnt,
			'aInviteLists' => $aInviteList,
			'aJoineds' => $aJoineds,
			'aInvalids' => $aInvalids,
			'sLinkNext' => '',
			'sLinkPrev' => '',
			'aErrors' => $aErrors,
		);
	}

	/**
	 * @param int $iUserId
	 * @param string $sRecipient
	 * @param string $sSubject
	 * @param string $sMessage
	 * @param string $sLink
	 * @return true|false
	 * @throws Exception
	 */
	public function sendInvitation($iUserId, $sRecipient, $sSubject, $sMessage, $sLink)
	{
		/**
		 * be care if this network does not install contact importer
		 */
		if (!Phpfox::isModule('contactimporter'))
		{
			return FALSE;
		}

		$aProfile = $this -> getProfile();

		$sHost = Phpfox::getParam('core.host');

		$sSubject = $aProfile['full_name'] . ' ' . Phpfox::getPhrase('contactimporter.is_inviting_you_to') . ' ' . Phpfox::getParam('core.site_title');

		$sBody = $aProfile['full_name'] . ' ' . Phpfox::getPhrase('contactimporter.is_inviting_you_to') . ' ' .  Phpfox::getParam('core.site_title') . "\n\r " . Phpfox::getPhrase('contactimporter.to_join_please_follow_the_link') . "\n\r " . $sLink . "\n\r " . Phpfox::getPhrase('contactimporter.message') . ": " . $sMessage;

		$api = $this -> getApi();

		try{
			$result = $api -> message(array($sRecipient), $sSubject, $sBody);
			return $this->generateResult($result, $result['success']);
		}
		catch (Exception $e)
		{
			return $this->generateResult($e -> getMessage(), FALSE);
		}
		// return $api -> message(array($sRecipient), $sSubject, $sBody);
	}
	
	public function post($aVals){
		$oLinkedIn =  $this->getApi();
		
        $aContent = array(
            'title' => html_entity_decode($aVals['status'], ENT_COMPAT, "UTF-8"),
            'submitted-url' => $aVals['url'],
            'description' => html_entity_decode($aVals['content'], ENT_COMPAT, "UTF-8")
        );

        if (strlen($aContent['title']) >= 200)
        {
            $aContent['title'] = substr($aContent['title'], 0, 180) . '...';
        }
        if (isset($aVals['img']) && !empty($aVals['img']))
        {
            $aContent['submitted-image-url'] = $aVals['img'];
        }
		
        $aResponse = $oLinkedIn->share('new', $aContent, false);
        $aResponse['apipublisher'] = 'linkedin';
        return $aResponse;
	}
}
