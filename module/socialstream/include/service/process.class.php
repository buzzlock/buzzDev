<?php

defined('PHPFOX') or exit('NO DICE!');


class SocialStream_Service_Process extends Phpfox_Service
{

	/**
	 * Class constructor
	 */
	public function __construct()
	{
		$this -> _sTable = Phpfox::getT('socialstream_feeds');
	}

	public function haveLink($text)
	{
		// force http: on www.
		$text = preg_replace("/www\./", "http://www.", $text);
		// eliminate duplicates after force
		$text = preg_replace("/http\:\/\/http\:\/\/www\./", "http://www.", $text);
		$text = preg_replace("/https\:\/\/http\:\/\/www\./", "https://www.", $text);

		// The Regular Expression filter
		$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,4}(\/\S*)?/";
		// Check if there is a url in the text
		return (preg_match($reg_exUrl, $text, $url));
	}

	public function parseTwitterLink($text)
	{
		// force http: on www.
		$text = preg_replace("/www\./", "http://www.", $text);
		// eliminate duplicates after force
		$text = preg_replace("/http\:\/\/http\:\/\/www\./", "http://www.", $text);
		$text = preg_replace("/https\:\/\/http\:\/\/www\./", "https://www.", $text);

		// The Regular Expression filter
		$reg_exUrl = "/(http|https|ftp|ftps)\:\/\/t\.co\/[a-zA-Z0-9]{10}?/";

		// Check if there is a url in the text
		if (preg_match($reg_exUrl, $text, $url))
		{
			// make the urls hyper links
			$text = preg_replace($reg_exUrl, "<a href=$0 target=_blank>$0</a>", $text);
		}// if no urls in the text just return the text
		return ($text);
	}

	public function getTitterFeedPhoto($aTwitterMedia = array())
	{
		if (empty($aTwitterMedia))
		{
			return false;
		}

		foreach ($aTwitterMedia as $aMedia)
		{
			if (isset($aMedia['type']) && $aMedia['type'] == 'photo')
			{
				if (isset($aMedia['media_url']) && !empty($aMedia['media_url']))
				{
					return array(
						$aMedia['media_url'],
						$aMedia['url'],
						$aMedia['type']
					);
				}
			}
		}
	}

	function decodeFBImageUrl($str)
	{
		$sUrl = html_entity_decode(preg_replace('/\\\\u([a-f0-9]{4})/i', '&#x$1;', $str), ENT_QUOTES, 'UTF-8') . '&cfs=1';
		return $sUrl;
	}

	public function addFeed($iUserId, $sService, $aDatas = array(), $iPrivacy, $aToken, $aProfile, $sIdentity, $aSetting)
	{
		if (!count($aDatas) || !$iUserId)
		{
			return FALSE;
		}

		$iServiceId = ($sService == 'facebook') ? 1 : 2;

		$oParse = Phpfox::getLib('parse.input');

		$iCount = count($aDatas);

		for ($iKey = $iCount - 1; $iKey >= 0; $iKey--)
		{
			$aData = $aDatas[$iKey];

			$sType = isset($aData['type']) ? $aData['type'] : 'status';

			$iSocialFeedId = '';

			if ($sService == 'facebook')
			{
				$iSocialFeedId = $aData['post_id'];
			}
			else
			{
				$iSocialFeedId = $aData['id'];
			}

			if ($this -> isFeedExists($iServiceId, $iSocialFeedId, $iUserId))
			{
				continue;
			}

			$bIsSkip = false;

			if ($sService == 'facebook')
			{
				$aData['from'] = number_format($aData['actor_id'], 0, '', '');

				//Facebook User who posted the feed.
				$aPostedAgent = Phpfox::getService('socialbridge') -> getPostedProfile('facebook', $aData['from']);

				//Facebook attachment
				$aAttachment = isset($aData['attachment']) ? $aData['attachment'] : false;

				$sHref = $aData['permalink'];
				if ($aAttachment && isset($aAttachment['media']) && count($aAttachment['media']))
				{
					if (isset($aAttachment['media'][0]['href']) && !empty($aAttachment['media'][0]['href']))
					{
						$sHref = $aAttachment['media'][0]['href'];
					}
				}

				//Insert array
				$aInsert = array(
					'service_id' => $iServiceId,
					'title' => '',
					'user_id' => $iUserId,
					'service_feed_id' => $aData['post_id'],
					'service_feed_link' => $aPostedAgent['link'],
					'social_agent_full_name' => $aPostedAgent['full_name'],
					'social_agent_id' => $aPostedAgent['identity'],
					'link' => $sHref,
					'message' => '',
					'content' => '',
					'image_url' => '',
					'privacy' => ($aPostedAgent['identity'] == $sIdentity) ? $aSetting['privacy'] : 3,
					'privacy_comment' => ($aPostedAgent['identity'] == $sIdentity) ? 0 : 3,
					'feed_type' => $sType,
					'time_stamp' => PHPFOX_TIME + ($iCount - $iKey)
				);

				//Switch feeds type
				switch ($sType)
				{
					case '65' :
						//Tagged in photo
						//$aInsert['feed_type'] = "Photo";
						$aInsert['message'] = $oParse -> prepare($aData['description']);
						$aInsert['content'] = $oParse -> prepare($aData['description']);
						if ($aAttachment)
						{
							if (isset($aAttachment['media']) && count($aAttachment['media']))
							{
								$aMedia = $aAttachment['media'];
								$aInsert['image_url'] = isset($aMedia[0]['src']) ? $this -> decodeFBImageUrl($aMedia[0]['src']) : '';
								$aInsert['title'] = isset($aAttachment['name']) ? $aAttachment['name'] : "";
								$aInsert['content'] = empty($aAttachment['caption']) ? $aAttachment['description'] : $aAttachment['caption'];
							}
						}
						break;
					case '8' :
						//Make friends
						if (isset($aData['description_tags']) && count($aData['description_tags']))
						{
							foreach ($aData['description_tags'] as $iFbUser)
							{
								$iFacebookFriendId = $iFbUser[0]['id'];
								$aInsert['image_url'] = Phpfox::getService('socialstream.services') -> getFaceBookPicture($iFacebookFriendId);
								$aInsert['link'] = "http://facebook.com/" . $iFacebookFriendId;
								break;
							}
						}
						break;
					case '283' :
					//Likes
					case '80' :
					//Photo
					case '60' :
					//Photo
					case '373' :
					//Photo
					case '237' :
					//Link
					case '247' :
					//Photo
					case '291' :
						$aInsert['message'] = $oParse -> prepare($aData['message']);
						$aInsert['content'] = $oParse -> prepare($aData['description']);
						if ($aAttachment)
						{
							if (isset($aAttachment['media']) && count($aAttachment['media']))
							{
								$aMedia = $aAttachment['media'];
								$aInsert['image_url'] = isset($aMedia[0]['src']) ? $this -> decodeFBImageUrl($aMedia[0]['src']) : '';
								$aInsert['title'] = isset($aAttachment['name']) ? $aAttachment['name'] : "";
								$aInsert['content'] = trim($aAttachment['caption']) != '' ? $aAttachment['caption'] . ' <br/>' . $aAttachment['description'] : $aAttachment['description'];
								$aInsert['title'] = isset($aAttachment['name']) ? $aAttachment['name'] : "";
							}
						}
						break;
					case '46' :
					//status
					case '272' :
					//status
					case '282' :
					//status
					case '280' :
						//Poll
						$aInsert['message'] = !empty($aData['message']) ? $oParse -> prepare($aData['message']) : $oParse -> prepare($aData['description']);
						$aInsert['link'] = '';
						break;
					//case '257':
                    //comment on his own photo
					default :
						$bIsSkip = true;
						continue;
				}

				if ($sType == '8' && empty($aInsert['message']) && empty($aInsert['content']))
				{
					//Fix for don't know an emty feed
					$bIsSkip = true;
				}
                if(isset($aInsert['image_url']))
                    $aInsert['image_url'] = str_replace('&cfs=1', '', $aInsert['image_url']);
			}
			else
			if ($sService == 'twitter')
			{
				$aPostedAgent = $aData['user'];
				$sFeedPhoto = '';

				if (isset($aData['entities']['media']))
				{
					list($sFeedPhoto, $aData['url'], $aData['type']) = $this -> getTitterFeedPhoto($aData['entities']['media']);
				}

				$sContent = "";
				if (isset($aData['text']))
				{
					$sContent = $aData['text'];
					$sContent = $this -> parseTwitterLink($sContent);
					$sContent = $oParse -> clean($sContent);
				}
				$aInsert = array(
					'service_id' => $iServiceId,
					'title' => '',
					'user_id' => $iUserId,
					'service_feed_id' => $aData['id'],
					'service_feed_link' => 'http://www.twitter.com/' . $aPostedAgent['screen_name'],
					'social_agent_full_name' => $aPostedAgent['name'],
					'social_agent_id' => $aPostedAgent['screen_name'],
					'link' => isset($aData['url']) ? $aData['url'] : '',
					'content' => $oParse -> prepare($sContent),
					'image_url' => $sFeedPhoto,
					'privacy' => ($aPostedAgent['screen_name'] == $aProfile['user_name']) ? $aSetting['privacy'] : 3,
					'privacy_comment' => ($aPostedAgent['screen_name'] == $aProfile['user_name']) ? 0 : 3,
					'feed_type' => isset($aData['type']) ? $aData['type'] : 'status',
					'time_stamp' => PHPFOX_TIME + ($iCount - $iKey)
				);
			}
			else
			{
				$bIsSkip = true;
			}

			if (!$bIsSkip && isset($aInsert) && count($aInsert))
			{
				$iId = $this -> database() -> insert($this -> _sTable, $aInsert);

				if ($iId && Phpfox::isModule('feed') && !defined('PHPFOX_SKIP_FEED_ENTRY'))
				{
					$iFeedId = Phpfox::getService('feed.process') -> allowGuest() -> add('socialstream_' . $sService, $iId, $aInsert['privacy'], $aInsert['privacy_comment'], 0, $iUserId);

					$this -> database() -> update(Phpfox::getT('feed'), array('time_stamp' => $aInsert['time_stamp']), 'feed_id = ' . (int)$iFeedId);
				}
			}
		}
	}

	public function deleteFeed($iId = null)
	{
		$this -> database() -> delete($this -> _sTable, 'feed_id=' . (int)$iId);
	}

	public function isFeedExists($iServiceId = 0, $iFeedId = 0, $iUserId = 0)
	{
		if ($iServiceId == 0 || $iFeedId == 0 || $iUserId == 0)
			return false;

		if ($iServiceId == 1)//Facebook
		{
			$arr = explode("_", $iFeedId);
			if (is_array($arr) && isset($arr[1]))
			{
				$sPostId = $arr[1];
				return (int)$this -> database() -> select('count(*)') -> from($this -> _sTable, 'sf') -> where('sf.user_id = ' . (int)$iUserId . ' AND sf.service_id=' . $iServiceId . ' AND service_feed_id LIKE \'%_' . $sPostId . '\'') -> execute('getField');
			}
		}

		return (int)$this -> database() -> select('count(*)') -> from($this -> _sTable, 'sf') -> where('sf.user_id = ' . (int)$iUserId . ' AND sf.service_id=' . $iServiceId . ' AND service_feed_id LIKE \'' . trim($iFeedId) . '\'') -> execute('getField');
	}

	/**
	 * If a call is made to an unknown method attempt to connect
	 * it to a specific plug-in with the same name thus allowing
	 * plug-in developers the ability to extend classes.
	 *
	 * @param string $sMethod is the name of the method
	 * @param array $aArguments is the array of arguments of being passed
	 */
	public function __call($sMethod, $aArguments)
	{
		/**
		 * Check if such a plug-in exists and if it does call it.
		 */
		if ($sPlugin = Phpfox_Plugin::get('socialstream.service_process__call'))
		{
			return eval($sPlugin);
		}

		/**
		 * No method or plug-in found we must throw a error.
		 */
		Phpfox_Error::trigger('Call to undefined method ' . __CLASS__ . '::' . $sMethod . '()', E_USER_ERROR);
	}

}
?>
