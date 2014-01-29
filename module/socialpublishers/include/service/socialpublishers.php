<?php

defined('PHPFOX') or exit('NO DICE!');

class SocialPublishers_Service_Socialpublishers extends Phpfox_Service
{

	public function __construct()
	{
		$this -> _sTable = Phpfox::getT('socialpublishers_services');
	}

	public function getUserConnected($iUserid = null, $iServiceId = null)
	{
		return Phpfox::getService('socialbridge.agents') -> getUserConnected($iUserid, $iServiceId);
	}

	public function getUrlAuth($sService = "", $bRedirect = 0)
	{
		//USING SOCIAL BRIDGE TO GER AUTH URL
		return Phpfox::getService('socialbridge.libs') -> getUrlAuth($sService, $bRedirect, 'publish_stream');
	}

	public function addToken($iUserId = null, $sService = 'facebook', $aParams, $aExtra)
	{
		//USING SOCIAL BRIDGE TO ADD USER TOKEN
		return Phpfox::getService('socialbridge.agents') -> addToken($iUserId, $sService, $aParams, $aExtra);
	}

	public function getRealUser($iUserId = null)
	{
		if ($iUserId == null)
		{
			$iUserId = Phpfox::getUserId();
		}
		$id = (int)$this -> database() -> select('p.user_id') -> from(Phpfox::getT('user'), 'u') -> join(Phpfox::getT('pages'), 'p', 'u.profile_page_id = p.page_id') -> where('u.user_id = ' . $iUserId) -> execute('getField');
		return ($id == 0 ? $iUserId : $id);
	}

	public function getProfile($sService = "", $aParams = null)
	{
		//USING SOCIAL BRIDGE TO GET PROFILE INFO
		return Phpfox::getService('socialbridge.agents') -> getProfile($sService, $aParams);
	}

	public function deleteToken($iUserId = null, $sService = 'facebook')
	{
		// USING SOCIAL BRIDGE TO DELETE USER TOKEN
		return Phpfox::getService('socialbridge.agents') -> deleteToken($iUserId, $sService);
	}

	public function getToken($iUserId = null, $sService = 'facebook')
	{
		$iUserId = $this -> getRealUser((int)$iUserId);
		// USING SOCIAL BRIDGE TO GET USER TOKEN
		return Phpfox::getService('socialbridge.agents') -> getToken($iUserId, $sService);
	}

	function decodeEntities($text)
	{
		$text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
		return $text;
	}

	//show publisher
	public function showPublisher($sType = "", $iUserId = null, $aVals = array(), $bIsFrame = false)
	{
		if (!Phpfox::isModule('socialbridge'))
		{
			return false;
		}

		$sIdCache = Phpfox::getLib('cache') -> set("socialpublishers_feed_" . $iUserId);
		if (!$sType || count($aVals) <= 0)
		{
			Phpfox::getLib('cache') -> remove($sIdCache);
			return false;
		}

		if ($sType == "music_song" || $sType == "music_album")
		{
			$sType = "music";
			$aVals['content'] = '';
		}

		if ($sType == 'user_status')
		{
			$sType = 'status';
		}

		if ($sType == "video")
		{
			$aVals['content'] = '';
		}

		if (isset($aVals['text']) && !empty($aVals['text']))
		{
			$aVals['text'] = Phpfox::getLib('parse.input') -> clean($aVals['text']);
		}

		if (isset($aVals['content']) && !empty($aVals['content']))
		{
			$aVals['content'] = Phpfox::getLib('parse.input') -> clean($aVals['content']);
		}

		$aSupportedModule = Phpfox::getService('socialpublishers.modules') -> getModule($sType);
		if (count($aSupportedModule) > 0 && $aSupportedModule['is_active'] == 0)
		{
			Phpfox::getLib('cache') -> remove($sIdCache);
			return false;
		}

		$aExistSettings = Phpfox::getService('socialpublishers.modules') -> getUserModuleSettings($this -> getRealUser($iUserId), $sType);

		$aShare['type'] = $sType;
		$aShare['user_id'] = $iUserId;
		$aShare['url'] = urlencode($aVals['url']);
		$aShare['title'] = isset($aVals['title']) ? $this -> decodeEntities($aVals['title']) : "";
		$aShare['content'] = isset($aVals['content']) ? $this -> decodeEntities($aVals['content']) : "";

		if (Phpfox::isModule('emoticon'))
		{
			$oEmoticon = Phpfox::getService('emoticon');
			$aPackages = $oEmoticon -> getPackages();
			if ($aPackages)
			{
				foreach ($aPackages as $aPackage)
				{
					if ($aPackage["is_active"] == 1)
					{
						$aEmoticons = $oEmoticon -> getEmoticons($aPackage["package_path"]);
						if ($aEmoticons)
						{
							foreach ($aEmoticons as $aEmoticon)
							{
								$pattern = '/<img src="' . addcslashes(Phpfox::getParam('core.url_emoticon'), '/') . $aPackage["package_name"] . '\/' . $aEmoticon['image'] . '\"[^>]+\>/i';
								$aShare['content'] = preg_replace($pattern, $aEmoticon['text'], $aShare['content']);
								$aShare['title'] = preg_replace($pattern, $aEmoticon['text'], $aShare['title']);
							}
						}
					}
				}
			}
		}

		$aModulePostContent = array(
			'status',
			'photo',
			'link',
			'music'
		);
		if (!in_array($sType, $aModulePostContent))
		{
			$aShare['content'] = $aShare['title'];
		}
		$aShare['content'] = $this -> mbcf_truncate($aShare['content'], 300);

		$aFeed = Phpfox::getLib('cache') -> get($sIdCache);
		if (!$aFeed)
		{
			return false;
		}
		if (isset($aFeed['is_show']) && $aFeed['is_show'] == 1)
		{
			return false;
		}

		$aFeed['params'] = $aShare;

		$sIdCache = Phpfox::getLib('cache') -> set("socialpublishers_feed_" . $this -> getRealUser((int)$iUserId));
		Phpfox::getLib('cache') -> save($sIdCache, $aFeed);

		$oService = Phpfox::getService('socialbridge.libs');
		if (count($aExistSettings) > 0 && $aExistSettings['no_ask'] == 1)
		{
            $aShare['url'] = urldecode($aShare['url']);
            $sPostMessage = Phpfox::getService('socialpublishers') -> getPostMessage($aShare);
            $aShare['status'] = $sPostMessage;
            if ((int)$aExistSettings['facebook'] == 1 && isset($aSupportedModule['facebook']) && (int)$aSupportedModule['facebook'] == 1)
            {
                $sReponse = $oService -> post('facebook', $aShare);
                Phpfox::getService('socialpublishers.statisticdate.process')->updateTotalPostByDate('facebook');
                Phpfox::getService('socialpublishers.statisticuser.process')->updateTotalPostByUser('facebook');
            }
            if ((int)$aExistSettings['twitter'] == 1 && isset($aSupportedModule['twitter']) && (int)$aSupportedModule['twitter'] == 1)
            {
                $sReponse = $oService -> post('twitter', $aShare);
                Phpfox::getService('socialpublishers.statisticdate.process')->updateTotalPostByDate('twitter');
                Phpfox::getService('socialpublishers.statisticuser.process')->updateTotalPostByUser('twitter');
            }
            if ((int)$aExistSettings['linkedin'] == 1 && isset($aSupportedModule['linkedin']) && (int)$aSupportedModule['linkedin'] == 1)
            {
                $sReponse = $oService -> post('linkedin', $aShare);
                Phpfox::getService('socialpublishers.statisticdate.process')->updateTotalPostByDate('linkedin');
                Phpfox::getService('socialpublishers.statisticuser.process')->updateTotalPostByUser('linkedin');
            }
            if (Phpfox::isModule('socialintegration'))
            {
                if ($bIsFrame == false)
                {
                    echo "<script>\$.ajaxCall('socialintegration.showAfterPublisher');</script>";
                }
                else
                {
                    echo "\$.ajaxCall('socialintegration.showAfterPublisher');";
                }
            }
			
            //do show popp;
            if (Phpfox::isModule('socialintegration'))
            {
                echo "<script>\$(this).ajaxCall('socialintegration.showAfterPublisher');</script>";
            }
		}
		if ($bIsFrame === 2)
		{
			$sJsCall = "setTimeout(\"\$Core.box('socialpublishers.share', 500);\",2500);";
		}
		else
		if ($bIsFrame == true)
		{
			$sJsCall = "window.parent.\$Core.box('socialpublishers.share', 500);";
		}
		else
		{
			$sJsCall = "<script>\$Behavior.showSocialPublishersPopup = (function(){\$Core.box('socialpublishers.share', 500);});</script>";
		}
		echo $sJsCall;
	}

	public function showPublisher3rd($aParams)
	{
		if (Phpfox::hasCallback($aParams['type'], 'getPublisherInfo'))
		{
			$aParams = Phpfox::callback($aParams['type'] . '.getPublisherInfo', $aParams);
			$sType = $aParams['type'];
			$iUserId = $aParams['user_id'];
			$bIsFrame = $aParams['bIsFrame'];
			$aVals['url'] = $aParams['url'];
			$aVals['content'] = $aParams['content'];
			$aVals['title'] = $aParams['title'];
			Phpfox::getService('socialpublishers') -> showPublisher($sType, $iUserId, $aVals, $bIsFrame);
		}
	}

	public function getPostMessage($aParams = array())
	{
		if (!isset($aParams['type']))
		{
			return Phpfox::getPhrase('socialpublishers.post_on');
		}
		//for unsuportted module.
		if (Phpfox::hasCallback($aParams['type'], 'getPostMessage'))
		{
			$aPostMessage = Phpfox::callback($aParams['type'] . '.getPostMessage', $aParams);
			return $aPostMessage;
		}
		//end
		switch ($aParams['type'])
		{
			case 'blog' :
				//{full_name} posted a blog {title} on {site_name}
				return Phpfox::getPhrase('socialpublishers.post_a_blog_title_on', array(
					'full_name' => Phpfox::getUserBy('full_name'),
					'title' => empty($aParams['title']) ? '' : ' "' . $aParams['title'] . '"',
					'site_name' => Phpfox::getParam('core.site_title'),
				));
			case 'link' :
				return Phpfox::getPhrase('socialpublishers.share_a_link_title_on', array(
					'full_name' => Phpfox::getUserBy('full_name'),
					'link' => ' "' . $aParams['url'] . '"',
					'site_name' => Phpfox::getParam('core.site_title'),
				));
			case 'music' :
				return Phpfox::getPhrase('socialpublishers.post_a_song_title_on', array(
					'full_name' => Phpfox::getUserBy('full_name'),
					'title' => empty($aParams['title']) ? '' : ' "' . $aParams['title'] . '"',
					'site_name' => Phpfox::getParam('core.site_title'),
				));
			case 'photo' :
				return Phpfox::getPhrase('socialpublishers.post_a_photo_title_on', array(
					'full_name' => Phpfox::getUserBy('full_name'),
					'title' => empty($aParams['title']) ? '' : ' "' . $aParams['title'] . '"',
					'site_name' => Phpfox::getParam('core.site_title'),
				));
			case 'poll' :
				return Phpfox::getPhrase('socialpublishers.post_a_poll_title_on', array(
					'full_name' => Phpfox::getUserBy('full_name'),
					'title' => empty($aParams['title']) ? '' : ' "' . $aParams['title'] . '"',
					'site_name' => Phpfox::getParam('core.site_title'),
				));
			case 'status' :
			case 'user_status' :
				return Phpfox::getPhrase('socialpublishers.post_a_status_title_on', array(
					'full_name' => Phpfox::getUserBy('full_name'),
					'title' => empty($aParams['title']) ? '' : ' "' . $aParams['title'] . '"',
					'site_name' => Phpfox::getParam('core.site_title'),
				));
			case 'video' :
				return Phpfox::getPhrase('socialpublishers.post_a_video_title_on', array(
					'full_name' => Phpfox::getUserBy('full_name'),
					'title' => empty($aParams['title']) ? '' : ' "' . $aParams['title'] . '"',
					'site_name' => Phpfox::getParam('core.site_title'),
				));
			case 'quiz' :
				return Phpfox::getPhrase('socialpublishers.post_a_quiz_title_on', array(
					'full_name' => Phpfox::getUserBy('full_name'),
					'title' => empty($aParams['title']) ? '' : ' "' . $aParams['title'] . '"',
					'site_name' => Phpfox::getParam('core.site_title'),
				));
			case 'event' :
				return Phpfox::getPhrase('socialpublishers.post_a_event_title_on', array(
					'full_name' => Phpfox::getUserBy('full_name'),
					'title' => empty($aParams['title']) ? '' : ' "' . $aParams['title'] . '"',
					'site_name' => Phpfox::getParam('core.site_title'),
				));
			case 'marketplace' :
				return Phpfox::getPhrase('socialpublishers.post_a_marketplace_title_on', array(
					'full_name' => Phpfox::getUserBy('full_name'),
					'title' => empty($aParams['title']) ? '' : ' "' . $aParams['title'] . '"',
					'site_name' => Phpfox::getParam('core.site_title'),
				));
			default :
				return Phpfox::getPhrase('socialpublishers.post_on_site', array(
					'full_name' => Phpfox::getUserBy('full_name'),
					'title' => empty($aParams['title']) ? '' : ' "' . $aParams['title'] . '"',
					'site_name' => Phpfox::getParam('core.site_title'),
				));
		}
		return Phpfox::getPhrase('socialpublishers.post_on');
	}

	public function post($sProvider = "", $aVals = array())
	{
		//USING SOCIAL BRIDGE TO POST CONTENT TO FACEBOOK
		return Phpfox::getService('socialbridge.libs') -> post($sProvider, $aVals);
	}

	public function getShortBitlyUrl($sLongUrl)
	{
		try
		{
			$sLongUrl = urlencode($sLongUrl);
			$url = "http://api.bitly.com/v3/shorten?login=myshortlinkng&apiKey=R_0201be3efbcc7a1a0a0d1816802081d8&longUrl={$sLongUrl}&format=json";
			$result = @file_get_contents($url);
			$obj = json_decode($result, true);
			return ($obj['status_code'] == '200' ? $obj['data']['url'] : "");
		}
		catch (Exception $e)
		{
			return $sLongUrl;
		}
	}

	//get info from added
	public function getAddedInfo($aRecentAddedItem)//$sType,$iItemId,$bIsCallback,$aCallback)
	{
		$sType = $aRecentAddedItem['sType'];
		$aCallback = $aRecentAddedItem['aCallback'];
		$bIsCallback = $aRecentAddedItem['bIsCallback'];
		$iItemId = $aRecentAddedItem['iItemId'];
		$sModule = "";
		$sPrefix = "";

		if ($bIsCallback)
		{
			$sModule = $aCallback['module'];
			$sPrefix = $aCallback['table_prefix'];
		}
		$aFeed = $this -> database() -> select('feed.*,u.user_name,u.full_name,u.user_image,u.profile_page_id, u.gender') -> from(Phpfox::getT($sPrefix . 'feed'), 'feed') -> join(Phpfox::getT('user'), 'u', 'u.user_id = feed.user_id') -> where('feed.item_id =' . (int)$iItemId . ' AND (feed.type_id = "' . $sType . '" )') -> execute('getRow');

		if (count($aFeed) > 0 && $aFeed !== false)
		{
			if ($bIsCallback)
			{
				$aFeedTmp = Phpfox::callback($aFeed['type_id'] . '.getActivityFeed', $aFeed, $sModule);
				return array_merge($aFeedTmp, $aFeed);
			}
			if (!Phpfox::hasCallback($aFeed['type_id'], 'getActivityFeed'))
			{
				return false;
			}
			$aFeedTmp = Phpfox::callback($aFeed['type_id'] . '.getActivityFeed', $aFeed);
			return array_merge($aFeedTmp, $aFeed);
		}
		return array();
	}

	function mbcf_truncate($string, $length = 80, $etc = '...', $charset = 'UTF-8', $break_words = false, $middle = false)
	{
		if ($length == 0)
			return '';

		if (strlen($string) > $length)
		{
			$length -= min($length, strlen($etc));
			if (!$break_words && !$middle)
			{
				$string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length + 1));
			}
			if (!$middle)
			{
				return substr($string, 0, $length) . $etc;
			}
			else
			{
				return substr($string, 0, $length / 2) . $etc . substr($string, -$length / 2);
			}
		}
		else
		{
			return $string;
		}
	}
}