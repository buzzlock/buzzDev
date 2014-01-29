<?php

defined('PHPFOX') or exit('NO DICE!');

require_once dirname(dirname(__file__)) . '/provider/abstract.class.php';

require_once dirname(dirname(__file__)) . '/libs/twitter.php';
class Socialbridge_Service_Provider_Twitter extends SocialBridge_Service_Provider_Abstract
{

	protected $_name = 'twitter';

	/**
	 * get api object
	 * @return SocialBridge_Service_Provider_Abstract
	 */
	public function getApi($iUserId = null, $bIsCache = true)
	{
		if (null == $this -> _api || !$bIsCache)
		{

			$aConfig = $this -> getSetting();

			$this -> _api = new Twitter($aConfig['consumer_key'], $aConfig['consumer_secret']);

			list($token, $profile) = $this -> getTokenData($iUserId);

			if ($token && is_array($token))
			{
				$this -> _api -> setOAuthToken($token['oauth_token']);
				$this -> _api -> setOAuthTokenSecret($token['oauth_token_secret']);
				$this -> _profile = $profile;
			}
		}
		return $this -> _api;
	}

	/**
	 * get connect twitter API
	 * @return array object
	 */
	public function getProfile()
	{
		if (null == $this -> _profile)
		{
			$aProfile = array();

			$oTwitter = $this -> getApi();

			$me = $oTwitter -> accountVerifyCredentials();

			$aProfile['user_name'] = isset($me['screen_name']) ? $me['screen_name'] : "";
			$aProfile['full_name'] = isset($me['name']) ? $me['name'] : "";
			$aProfile['identity'] = isset($me['id_str']) ? $me['id_str'] : 0;
			$aProfile['service'] = 'twitter';
			$aProfile['img_url'] = isset($me['profile_image_url']) ? $me['profile_image_url'] : "";
			$aProfile['followers_count'] = isset($me['followers_count']) ? $me['followers_count'] : 0;
			$this -> _profile = $aProfile;
		}
		return $this -> _profile;
	}

	/**
	 * get list of conntected twitters friends of current viewer
	 * alias to get contacts
	 * @TODO get a large of contact etc: 100,000 followers
	 * @param int $iPage OPTIONAL DEFAULT  = 1
	 * @param int $iLimit OPTIONAL DEFAULT = 50
	 * @return array
	 */
	public function getFriends($iPage = 1, $iLimit = 50, $sCursor = -1)
	{
		return $this -> getContacts($iPage, $iLimit, $sCursor);
	}

	/**
	 * get list of twitter friends of current viewer
	 * @TODO get a large of contact etc: 100,000 followers
	 * @param int $iPage OPTIONAL DEFAULT  = 1
	 * @param int $iLimit OPTIONAL DEFAULT = 50
	 * @return array
	 */
	public function getContacts($iPage = 1, $iLimit = 50)
	{

		$result = array();

		$oTwitter = $this -> getApi();

		$iOffset = (($iPage - 1) * $iLimit) % 5000;

		if ($iOffset > 5000)
		{
			$iOffset = 0;
		}

//		$aInviteds = Phpfox::getService('contactimporter') -> getInviteds();

		$aProfile = $this -> getProfile();

		$iCount = $aProfile['followers_count'];
		$iUID = $aProfile['identity'];

		$sNextCursor = 0;

		$aFriendIds = $aFriends = $aJoineds = $aInvalids = $aErrors = array();

		$sCursor = ($iOffset > 0 && isset($_SESSION['twitter']['cursor'][$iOffset])) ? $_SESSION['twitter']['cursor'][$iOffset] : -1;

		if (isset($_SESSION['twitter']['data'][$iOffset]))
		{
			$aFriendIds = $_SESSION['twitter']['data'][$iOffset];
		}
		else
		{
			do
			{
				list($sNextCursor, $aFriendIds) = $oTwitter -> followersIds(null, $iUID, null, $sCursor);

				if (!count($aFriendIds))
				{
					$sCursor = $sNextCursor;
				}
			}
			while (count($aFriendIds) == 0 && $sNextCursor > 0);

			if ($sNextCursor)
			{
				$_SESSION['twitter']['cursor'][$iOffset] = $sNextCursor;
			}

			$_SESSION['twitter']['data'][$iOffset] = $aFriendIds;
		}

		if (count($aFriendIds))
		{
			// preg match count friends.
			$aFriendIds = array_slice($aFriendIds, $iOffset, $iLimit + 1);

			// process for large
			$aSlices = array_chunk($aFriendIds, 100);
			foreach ($aSlices as $aSlice)
			{
				$aFriends = array_merge($aFriends, $oTwitter -> usersLookup($aSlice, null));
			}
			$aFriends = Phpfox::getService('contactimporter') -> processSocialRows($aFriends);
			$aJoineds = Phpfox::getService('contactimporter') -> checkSocialJoined($aFriendIds);
		}
		else
		{
			$aErrors['contacts'] = Phpfox::getPhrase('contactimporter.there_is_not_contact_in_your_account');
		}

		return array(
			'iCnt' => $iCount,
			'iInvited' => '',
			'aInviteLists' => $aFriends,
			'aJoineds' => $aJoineds,
			'aInvalids' => $aInvalids,
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

		$iReturn = TRUE;

//		//mmmtest
//		if(Phpfox::getService('contactimporter.test')->isTest())
//		{
//			return Phpfox::getService('contactimporter.test')->getTestInvitationResult();
//			
//		}

		$oTwitter = $this -> getApi();

		if ($oTwitter)
		{
			if ($iUserId)
			{
				// we should use shorten url to reduce inviation.
				$sMessage = substr($sMessage, 0, 120 - strlen($sLink)) . ' ' . $sLink;
				try
				{
					$result = $oTwitter -> directMessagesNew($sMessage, null, $sRecipient);
					if(isset($result['errors']))
					{
						$iReturn = FALSE;
						return $this->generateResult($result, FALSE);
					}

					$iReturn = TRUE;
					return $this->generateResult($result, TRUE);
			

				}
				catch (Exception $e)
				{
					//Phpfox_Error::set($e);
					$iReturn = FALSE;
					return $this->generateResult($e -> getMessage(), FALSE);
				}
			}
		}
		return $iReturn;
	}

	/**
	 * post a message to twitter
	 */
	public function post($aVals)
	{
		$sMessage = html_entity_decode($aVals['status'], ENT_COMPAT, "UTF-8");
        
		//$sMessage = phpfox::getLib('parse.input')->clean($sMessage);
        if (false === strpos($aVals['url'], '://')) {
            $aVals['url'] = 'http://' . $aVals['url'] . '/';
        }
        str_replace('www.', '',$aVals['url']);

		$sBitlyUrl = $this -> getShortBitlyUrl($aVals['url']);

		$iLen = strlen($sBitlyUrl);

		if (strlen($sMessage) > 139 - $iLen)
		{
			$sMessage = substr($sMessage, 0, 130 - $iLen) . '...';
		}
		$sMessage = $sMessage . ' ' . $sBitlyUrl;
		$oTwitter = $this -> getApi();
		$response = $oTwitter -> statusesUpdate($sMessage);
		return $response;
	}

	public function getStatusHomeTimeline($aTwitter, $aParams)
	{
		$oTwitter = $this -> getApi();

		#$oTwitter -> setToken($aTwitter['token'], $aTwitter['secret']);
		$update_status = $oTwitter -> get_statusesHome_timeline($aParams);
		$temp = $update_status -> response;
		return $temp;

	}

	public function getStatusUserTimeline($aTwitter, $aParams)
	{
		$oTwitter = $this -> getApi();

		#$oTwitter -> setToken($aTwitter['token'], $aTwitter['secret']);
		$update_status = $oTwitter -> get_statusesUser_timeline($aParams);
		$temp = $update_status -> response;
		return $temp;

	}

	public function getAccessToken()
	{
		return $this -> getApi() -> getAccessToken();
	}

	public function getSecret()
	{
		return $this -> _sSecret;
	}

	public function getUrl()
	{
		Phpfox_Error::skip(true);
		$mReturn = $this -> getApi() -> getAuthorizationUrl();
		Phpfox_Error::skip(false);
		return $mReturn;
	}

	public function getFeeds($iLasFeedTimestamp, $iLimit, $iPage, $sIdentity, $iUserId = null)
	{
		$aGetParams = array(
			'exclude_replies' => 0,
			'include_rts' => 1,
			'include_entities' => 1
		);
		if ($iLasFeedTimestamp > 0)
		{
			$aGetParams['since_id'] = $iLasFeedTimestamp;
			$aGetParams['page'] = $iPage;
		}
		else
		{
			$aGetParams['count'] = $iLimit;
		}
		// signature
		//$id = null, $userId = null, $screenName = null, $sinceId = null, $maxId = null, $count = null, $page = null, $skipUser = false

        $oObject = $this->getApi($iUserId, false);
	$aDatas = $oObject -> statusesHomeTimeline($sinceId = $iLasFeedTimestamp, $maxId = null, $count = $iLimit, $page = $iPage, $skipUser = false);
        return $aDatas;
	}

    public function getPostedProfile($iUserProfileId)
    {
        $oTwitter = $this -> getApi();
        $me = $oTwitter -> usersShow(null, $iUserProfileId);
        $aToken['token'] = $this -> getAccessToken();
        $aToken['secret'] = $this -> getSecret();
        $aUserProfile['user_name'] = isset($me['screen_name']) ? $me['screen_name'] : "";
        $aUserProfile['full_name'] = isset($me['name']) ? $me['name'] : "";
        $aUserProfile['identity'] = isset($me['id_str']) ? $me['id_str'] : 0;
        $aUserProfile['service'] = 'twitter';
        $aUserProfile['img_url'] = isset($me['profile_image_url']) ? $me['profile_image_url'] : "";

        return $aUserProfile;
    }
    
    
    /**
     * @param string $status, string[optional] $inReplyToStatusId, float[optional] $lat, float[optional] $long, string[optional] $placeId, bool[optional] $displayCoordinates
     * @return array
     */
    public function statusesUpdate($status, $inReplyToStatusId = null, $lat = null, $long = null, $placeId = null, $displayCoordinates = false)
    {
    	$oObject = $this->getApi();
        return $oObject->statusesUpdate($status, $inReplyToStatusId, $lat, $long, $placeId, $displayCoordinates);
    }
    

}
