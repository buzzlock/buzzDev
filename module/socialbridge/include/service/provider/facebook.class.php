<?php

defined('PHPFOX') or exit('NO DICE!');

require_once dirname(dirname(__file__)) . '/libs/facebook.php';
require_once dirname(dirname(__file__)) . '/provider/abstract.class.php';

class Socialbridge_Service_Provider_Facebook extends SocialBridge_Service_Provider_Abstract
{

	protected $_name = 'facebook';

	protected $_appPicture = NULL;

	public function getAppPicture()
	{
		if (null == $this -> _appPicture)
		{
			$this -> _appPicture = Phpfox::getParam('core.path') . 'theme/frontend/default/style/default/image/noimage/item.png';
		}
		return $this -> _appPicture;
	}

	/**
	 * get api object
	 * @return FacebookSBYN
	 */
	public function getApi($iUserId = null, $bIsCache = true)
	{
        $config = $this -> getSetting();

        if (isset($config['pic']) && $config['pic'])
        {
            $this -> _appPicture = Phpfox::getParam('core.path') . 'file/pic/photo/' . str_replace('%s', '', $config['pic']);
        }
        else
        {
            $this -> _appPicture = Phpfox::getParam('core.path') . 'theme/frontend/default/style/default/image/noimage/item.png';
        }

		if (null == $this -> _api || !$bIsCache)
		{

			if (isset($config['app_id']))
			{
				$config['appId'] = $config['app_id'];
			}

			$this -> _api = new FacebookSBYN($config);

			list($token, $profile) = $this -> getTokenData($iUserId);

			if ($token)
			{
				$this -> _api -> setAccessToken($token);
				$this -> _profile = $profile;
			}
		}
		return $this -> _api;
	}

	/**
	 * get connected facebook profile as array or object
	 * @param $iFacebookUID
	 * @return array|NULL
	 */
	public function getProfile($iFacebookUID = 'me', $bIsGetNew = false)
	{
		if (NULL == $this -> _profile || $bIsGetNew)
		{
			$oFacebook = $this -> getApi();

			$aProfile = array();

			if ($iFacebookUID == null)
			{
				$iFacebookUID = "me";
			}

			try{
				$me = $oFacebook -> api('/' . $iFacebookUID);
			}
			catch (exception $e)
			{
				return array();
			}

			$iFacebookUID = isset($me['id']) ? $me['id'] : "";

			if (!isset($me['link']))
			{
				$me['link'] = "http://facebook.com/" . $iFacebookUID;
			}

			$aProfile['user_name'] = isset($me['username']) ? $me['username'] : "";
			$aProfile['full_name'] = isset($me['name']) ? $me['name'] : "";
			$aProfile['email'] = isset($me['email']) ? $me['email'] : "";
			$aProfile['identity'] = $iFacebookUID;
			$aProfile['service'] = 'facebook';
			$imgLink = "http://graph.facebook.com/%s/picture";
			$imgLink = sprintf($imgLink, $aProfile['identity']);
			$aProfile['img_url'] = $imgLink;
			$aProfile['link'] = isset($me['link']) ? $me['link'] : "";
			

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
		$aFriends = $aJoineds = $aInvalids = $aErrors = NULL;

		$aIds = array();

		$iOffset = ($iPage - 1) * $iLimit;

		$iUserId = Phpfox::getUserId();

		$iCnt = 0;

		$token = $this -> getApi() -> getAccessToken();

		try
		{
			$sCacheId = $this -> cache() -> set('facebook.' . (string)$token);

			$profile = $this -> getProfile();
			$aData = $this -> cache() -> get($sCacheId, 5);

			// notice that facebook is set maximum 5,000 friends for member so we should cache all to a file with session

			if ($aData == FALSE)
			{
				$aData = array();

				$oFacebook = $this -> getApi();

				$iCountInvited = 0;

				$aFriends = $oFacebook -> api(array(
					'method' => 'fql.query',
					'query' => "SELECT name,uid,pic_small FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1=me()) ORDER BY name",
                    'access_token' => $token,
				));

				//$friends = $oFacebook -> api('/me/friends?token=' . $token);
				$aUninvited = array();

				$aInviteds = Phpfox::getService('contactimporter') -> getInviteds();

				if ($iUserId)
				{
					$sServerId = Phpfox::getLib('request') -> getServer('PHPFOX_SERVER_ID');
					$aConds[] = 'AND status = "pending"';
					$aConds[] = 'AND (server_id IS NULL OR server_id = "' . $sServerId . '")';
					$aConds[] = 'AND provider = "facebook"';
					$aConds[] = 'AND user_id = "' . $iUserId . '"';

				}

				$iCount = 0;
				
				if(count($aFriends) <= 0)
				{
					$aErrors['contacts'] = Phpfox::getPhrase('contactimporter.there_is_not_contact_in_your_account');
				}

				foreach ($aFriends as $index => $aFriend)
				{
//					if (!in_array($aFriend['uid'], $aInviteds))
					if (!Phpfox::getService('contactimporter')->checkInviteIdExist($aFriend['uid'], Phpfox::getUserId(), 'facebook'))
					{
						//$aUninvited[] = array('id'=>$aFriend['uid'],'name'=>$aFriend['name'],'pic'=>sprintf($imgLink, $aFriend['uid']));
						// improve image loading speed
						$aUninvited[] = array(
							'id' => $aFriend['uid'],
							'name' => $aFriend['name'],
							'pic' => $aFriend['pic_small']
						);
						$iCount++;
					}
					else
					{
						$iCountInvited++;
					}
				}

				$aFriends = $aUninvited;

				$aData['friends'] = $aFriends;
				$aData['total_friends'] = $iCount;

				$this -> cache() -> save($sCacheId, $aData);
			}
			else
			{
				$aFriends = $aData['friends'];
				$iCount = $aData['total_friends'];
			}

			foreach ($aFriends as $aFriend)
			{
				$aIds[] = $aFriend['id'];
			}

			$iCnt = count($aFriends);
			$aFriends = array_slice($aFriends, $iOffset, $iLimit);
			$aFriends = Phpfox::getService('contactimporter') -> processSocialRows($aFriends);
			$aJoineds = Phpfox::getService('contactimporter') -> checkSocialJoined($aIds);

		}
		catch (Exception $e)
		{
			Phpfox_Error::set($e);
		}
		
		return array(
			'iCnt' => $iCnt,
			'aInviteLists' => $aFriends,
			'aJoineds' => $aJoineds,
			'aInvalids' => $aInvalids,
			'sLinkNext' => Phpfox::getLib('url') -> makeUrl('contactimporter.facebook', array('page' => $iPage + 1)),
			'sLinkPrev' => Phpfox::getLib('url') -> makeUrl('contactimporter.facebook', array('page' => $iPage - 1 > 0 ? $iPage - 1 : 1)),
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

		if (!$sRecipient)
		{
			return FALSE;
		}
		// return $this->generateResult('send message failed due to some error, will be checked later', TRUE);

		$facebook = $this -> getApi();

		$sName = Phpfox::getPhrase('contactimporter.you_are_invited_to_message_from_host', array(
			'host' => phpfox::getParam('core.path'),
			'link' => phpfox::getParam('core.path')
		));

		$sName = html_entity_decode($sName, ENT_NOQUOTES, 'UTF-8');

		$param = array(
			'picture' => $this -> getAppPicture(),
			'message' => $sMessage ,
			'link' => $sLink,
			'name' => $sName,
			'caption' => Phpfox::getParam('core.path'),
			'description' => Phpfox::getParam('core.global_site_title')
		);

		/**
		 * @TODO: send inviation with
		 */

		try
		{
			$iType = 3;

			if( $iType == 1)
			{
				$result = $facebook -> api('/' . $sRecipient . '/feed', 'POST', $param);	
			}
			else if($iType == 2)
			{
				$param['tags'] = $sRecipient;
				$param['place'] = 155021662189;
				$param['is_hidden'] = TRUE;
//			'place' => '155021662189',

				$result = $facebook -> api('/' . 'me' . '/feed', 'POST', $param);	
			}
			else if ($iType == 3)
			{
				$options = array(
					'uid' => $facebook->getUser(),
					'app_id' => $facebook->getAppId(),
					'server' => 'chat.facebook.com',
				);

				$access_token = $facebook->getAccessToken();
				$connectResult = Phpfox::getService('socialbridge.helpers.facebookchat')->xmpp_connect($options, $access_token);
				if(!$connectResult)
				{
					return $this->generateResult('connect failed', FALSE);
				}

				$sMessage = $sMessage . ' ' . $sLink;
				$sendMessageResult = Phpfox::getService('socialbridge.helpers.facebookchat')->xmpp_message($to = $sRecipient, $body = $sMessage );

				if(!$sendMessageResult)
				{
					return $this->generateResult('send message failed due to some error, will be checked later', FALSE);
				}
				else
				{
					return $this->generateResult($sendMessageResult, TRUE);
				}

			}

			if(isset($result['error']))
			{
				return $this->generateResult($result, FALSE);
			}

			return $this->generateResult($result, TRUE);
		}
		catch (Exception $e)
		{
			$bResult = FALSE;
			return $this->generateResult($e -> getMessage(), FALSE);
		}
		return $bResult;
	}

	public function post($aVals)
	{
		try
		{
			$oFacebook = $this -> getApi();

			$sType = $aVals['type'];

            if(!isset($aVals['picture']))
                $aVals['picture'] = '';

            $sIdCache = Phpfox::getLib('cache')->set("socialpublishers_feed_" . Phpfox::getUserId());
            $aFeed = Phpfox::getLib('cache')->get($sIdCache);
            Phpfox::getLib('cache')->remove($sIdCache);
            
            if(isset($aFeed['url']))
                $aVals['picture'] = $aFeed['url'];
			if (isset($aFeed['iItemId']) && $aFeed)
			{
				$iItemId = $aFeed['iItemId'];
				if ($sType == 'link')
				{
					$aRow = Phpfox::getLib('phpfox.database') -> select('*') -> from(Phpfox::getT('link')) -> where('link_id = ' . $iItemId) -> execute('getRow');
					if (isset($aRow['image']) && $aRow['image'])
					{
						$aVals['picture'] = $aRow['image'];
					}
				}

				if ($sType == 'photo')
				{
					$aRow = Phpfox::getLib('phpfox.database') -> select('*') -> from(Phpfox::getT('photo')) -> where('photo_id = ' . $iItemId) -> execute('getRow');
					if (isset($aRow['destination']) && $aRow['destination'])
					{
						$sDirImage = Phpfox::getParam('photo.dir_photo') . $aRow['destination'];
						$sUrlImage = Phpfox::getParam('photo.url_photo') . $aRow['destination'];
						$aVals['picture'] = sprintf($sUrlImage, '_100');
					}
				}

				if ($sType == 'poll')
				{
					$aRow = Phpfox::getLib('phpfox.database') -> select('*') -> from(Phpfox::getT('poll')) -> where('poll_id = ' . $iItemId) -> execute('getRow');
					if (isset($aRow['image_path']) && $aRow['image_path'])
					{
						$sDirImage = Phpfox::getParam('poll.dir_image') . $aRow['image_path'];
						$sUrlImage = Phpfox::getParam('poll.url_image') . $aRow['image_path'];
						$aVals['picture'] = sprintf($sUrlImage, '_75');
					}
				}

				if ($sType == 'quiz')
				{
					$aRow = Phpfox::getLib('phpfox.database') -> select('*') -> from(Phpfox::getT('quiz')) -> where('quiz_id = ' . $iItemId) -> execute('getRow');
					if (isset($aRow['image_path']) && $aRow['image_path'])
					{
						$sDirImage = Phpfox::getParam('quiz.dir_image') . $aRow['image_path'];
						$sUrlImage = Phpfox::getParam('quiz.url_image') . $aRow['image_path'];
						$aVals['picture'] = sprintf($sUrlImage, '_75');
					}
				}

				if ($sType == 'video')
				{
					$aRow = Phpfox::getLib('phpfox.database') -> select('*') -> from(Phpfox::getT('video')) -> where('video_id = ' . $iItemId) -> execute('getRow');
					if (isset($aRow['image_path']) && $aRow['image_path'])
					{
						$sDirImage = Phpfox::getParam('video.dir_image') . $aRow['image_path'];
						$sUrlImage = Phpfox::getParam('video.url_image') . $aRow['image_path'];
						$aVals['picture'] = sprintf($sUrlImage, '_120');
					}
				}

                if ($sType == 'status')
				{
					$aRow = $this->database()
                            ->select('*')
                            ->from(Phpfox::getT('user_status'))
                            ->where('status_id = ' . (int)$iItemId)
                            ->execute('getRow');
				}
                
                if ($sType == 'petition')
				{
					$aRow = $this->database()
                            ->select('*')
                            ->from(Phpfox::getT('petition'))
                            ->where('petition_id = ' . (int)$iItemId)
                            ->execute('getRow');
				}
                
                if ($sType == 'event')
				{
					$aRow = $this->database()
                            ->select('*')
                            ->from(Phpfox::getT('event'))
                            ->where('event_id = ' . (int)$iItemId)
                            ->execute('getRow');
				}
                
                if ($sType == 'fevent')
				{
					$aRow = $this->database()
                            ->select('*')
                            ->from(Phpfox::getT('fevent'))
                            ->where('event_id = ' . (int)$iItemId)
                            ->execute('getRow');
				}
                
                if ($sType == 'marketplace')
				{
					$aRow = $this->database()
                            ->select('*')
                            ->from(Phpfox::getT('marketplace'))
                            ->where('listing_id = ' . (int)$iItemId)
                            ->execute('getRow');
				}
                
                if ($sType == 'advancedphoto')
                {
                    $aRow = Phpfox::getLib('phpfox.database') -> select('*') -> from(Phpfox::getT('photo')) -> where('photo_id = ' . $iItemId) -> execute('getRow');
                    if (isset($aRow['destination']) && $aRow['destination'])
                    {
                        $sDirImage = Phpfox::getParam('photo.dir_photo') . $aRow['destination'];
                        $sUrlImage = Phpfox::getParam('photo.url_photo') . $aRow['destination'];
                        $aVals['picture'] = sprintf($sUrlImage, '_150');
                    }
                }

                if ($sType == 'advancedmarketplace')
                {
                    $aImageRow = $this->database()
                            ->select('*')
                            ->from(Phpfox::getT('advancedmarketplace_image'))
                            ->where('listing_id = ' . $iItemId)
                            ->execute('getRow');
                    
                    if (isset($aImageRow['image_path']) && $aImageRow['image_path'])
                    {
                        $sDirImage = Phpfox::getParam('core.dir_pic') . 'advancedmarketplace' . PHPFOX_DS . $aImageRow['image_path'];
                        $sUrlImage = Phpfox::getParam('core.url_pic') . 'advancedmarketplace' . PHPFOX_DS . $aImageRow['image_path'];
                        $aVals['picture'] = sprintf($sUrlImage, '_200');
                    }
                    
                    $aRow = $this->database()
                            ->select('*')
                            ->from(Phpfox::getT('advancedmarketplace'))
                            ->where('listing_id = ' . $iItemId)
                            ->execute('getRow');
                }

                if ($sType == 'karaoke_song')
                {
                    $aRow = Phpfox::getLib('phpfox.database') -> select('*') -> from(Phpfox::getT('karaoke_song')) -> where('song_id = ' . $iItemId) -> execute('getRow');
                    if (isset($aRow['image_path']) && $aRow['image_path'])
                    {
                        $sDirImage = Phpfox::getParam('core.dir_file') . 'karaoke/image' . $aRow['image_path'];
                        $sUrlImage = Phpfox::getParam('core.url_file') . 'karaoke/image' . $aRow['image_path'];
                        $aVals['picture'] = sprintf($sUrlImage, '_thumb_225x225');
                    }
                }
                
                if ($sType == 'blog')
                {
                    $aRow = $this->database()
                            ->select('*')
                            ->from(Phpfox::getT('blog'))
                            ->where('blog_id = ' . (int) $iItemId)
                            ->execute('getRow');
                }
                
                if ($sType == 'music')
                {
                    $aRow = $this->database()
                            ->select('*')
                            ->from(Phpfox::getT('music_song'))
                            ->where('song_id = ' . (int) $iItemId)
                            ->execute('getRow');
                }
                
                if ($sType == 'karaoke_recording')
                {
                    $aRow = Phpfox::getLib('phpfox.database') -> select('*') -> from(Phpfox::getT('karaoke_recording')) -> where('recording_id = ' . $iItemId) -> execute('getRow');
                    if (isset($aRow['image_path']) && $aRow['image_path'])
                    {
                        $sDirImage = Phpfox::getParam('core.dir_file') . 'karaoke/image' . $aRow['image_path'];
                        $sUrlImage = Phpfox::getParam('core.url_file') . 'karaoke/image' . $aRow['image_path'];
                        $aVals['picture'] = sprintf($sUrlImage, '_thumb_225x225');
                    }
                }

                if ($sType == 'musicsharing_album' || $sType == "musicstore_album")
                {
                    $aRow = Phpfox::getLib('phpfox.database') -> select('*') -> from(Phpfox::getT('m2bmusic_album')) -> where('album_id = ' . $iItemId) -> execute('getRow');
                    if (isset($aRow['album_image']) && $aRow['album_image'])
                    {
                        if($sType == 'musicsharing_album')
                        {
                            $sDirImage = Phpfox::getParam('core.dir_pic') . 'musicsharing' . PHPFOX_DS . $aRow['album_image'];
                            $sUrlImage = Phpfox::getParam('core.url_pic') . 'musicsharing' . PHPFOX_DS . $aRow['album_image'];
                        }
                        if($sType == 'musicstore_album')
                        {
                            $sDirImage = Phpfox::getParam('core.dir_pic') . 'musicstore' . PHPFOX_DS . $aRow['album_image'];
                            $sUrlImage = Phpfox::getParam('core.url_pic') . 'musicstore' . PHPFOX_DS . $aRow['album_image'];
                        }

                        $aVals['picture'] = sprintf($sUrlImage, '_thumb_345x250');
                    }
                }

                if ($sType == 'musicsharing_playlist' || $sType == "musicstore_playlist")
                {
                    $aRow = Phpfox::getLib('phpfox.database') -> select('*') -> from(Phpfox::getT('m2bmusic_playlist')) -> where('playlist_id = ' . $iItemId) -> execute('getRow');
                    if (isset($aRow['playlist_image']) && $aRow['playlist_image'])
                    {
                        if($sType == 'musicsharing_playlist')
                        {
                            $sDirImage = Phpfox::getParam('core.dir_pic') . 'musicsharing' . PHPFOX_DS . $aRow['playlist_image'];
                            $sUrlImage = Phpfox::getParam('core.url_pic') . 'musicsharing' . PHPFOX_DS . $aRow['playlist_image'];
                        }
                        if($sType == 'musicstore_playlist')
                        {
                            $sDirImage = Phpfox::getParam('core.dir_pic') . 'musicstore' . PHPFOX_DS . $aRow['playlist_image'];
                            $sUrlImage = Phpfox::getParam('core.url_pic') . 'musicstore' . PHPFOX_DS . $aRow['playlist_image'];
                        }
                        $aVals['picture'] = sprintf($sUrlImage, '_thumb_345x250');
                    }
                }

			}

			if ($aVals['picture'] == '')
			{
                $aConfig = Phpfox::getService('socialbridge')->getSetting('facebook');
				if (isset($aConfig['pic']))
				{
					$aVals['picture'] = Phpfox::getLib('image.helper') -> display(array(
						'path' => 'photo.url_photo',
						'file' => $aConfig['pic'],
						'max_width' => 100,
						'max_height' => 100,
						'return_url' => true
					));
				}
				else
				{
					$aVals['picture'] = Phpfox::getParam('core.path') . 'theme/frontend/default/style/default/image/noimage/item.png';
				}
			}
            
			$aPostParam = array(
				// 'picture'=> '',
				'name'=> html_entity_decode($aVals['content'], ENT_COMPAT, "UTF-8"),
				'message' => html_entity_decode($aVals['content'], ENT_COMPAT, "UTF-8"),
				'link' => $aVals['url'],
				'caption' => html_entity_decode($aVals['status'], ENT_COMPAT, "UTF-8"),
				'description' => phpfox::getParam('core.global_site_title')
			);

			if (isset($aVals['picture']) && $aVals['picture'])
			{
				$aPostParam['picture'] = $aVals['picture'];
			}
			
			(($sPlugin = Phpfox_Plugin::get('socialpublishers.component_controller_privacy_settings')) ? eval($sPlugin) : false);
			
			$oFacebook -> api('/me/feed', 'POST', $aPostParam);
		}
		catch (exception $ex)
		{
			$aResponse['error'] = $ex -> getMessage();
			$aResponse['apipublisher'] = 'facebook';
			return $aResponse;
		}
		return true;
	}

	function getFeeds($iLastFeedTimestamp = 0, $iLimit = 100, $iUserId = null)
	{
		$sFQL = "SELECT post_id,actor_id,target_id,message,description,created_time,attachment,permalink,description_tags,type FROM stream WHERE filter_key IN (SELECT filter_key FROM stream_filter WHERE uid= me() AND type='newsfeed') AND is_hidden = 0";

		if ((int)$iLastFeedTimestamp > 0)
		{
			$sFQL .= ' AND created_time > ' . $iLastFeedTimestamp;
			$sFQL .= ' ORDER BY created_time ASC';
		}
		$sFQL .= ' LIMIT 0,' . $iLimit;

		$result = $this -> getApi($iUserId, false) -> api(array(
			'method' => 'fql.query',
			'query' => $sFQL
		));
		return $result;
	}

    /**
     * @param null $aToken
     * @param null $aUserProfileId
     * @return array|NULL
     */

    function getPostedProfile($aUserProfileId = null)
    {
        if ($aUserProfileId == null)
        {
            $aUserProfileId = "me";
        }

        $me = $this -> getApi() -> api('/' . $aUserProfileId);

        $aUserProfileId = $me['id'];

        if (!isset($me['link']))
        {
            $me['link'] = "http://facebook.com/" . $aUserProfileId;
        }

        $aUserProfile['user_name'] = isset($me['username']) ? $me['username'] : "";
        $aUserProfile['full_name'] = isset($me['name']) ? $me['name'] : "";
        $aUserProfile['email'] = isset($me['email']) ? $me['email'] : "";
        $aUserProfile['identity'] = $aUserProfileId;
        $aUserProfile['service'] = 'facebook';
        $imgLink = "http://graph.facebook.com/%s/picture";
        $imgLink = sprintf($imgLink, $aUserProfile['identity']);
        $aUserProfile['img_url'] = $imgLink;
        $aUserProfile['link'] = $me['link'];
        return $aUserProfile;
    }
    
    
    /**
     * @param string $sMessage, string $sPostId
     * @return string
     */
    public function comments($sMessage, $sPostId)
    {
        return $this->getApi()->api('/'.$sPostId.'/comments', 'post', array('message' => $sMessage), false);
    }
    

}
