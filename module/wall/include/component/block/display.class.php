<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 *
 *
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Feed
 * @version 		$Id: display.class.php 4621 2012-09-12 05:34:34Z Raymond_Benc $
 */
class Wall_Component_Block_Display extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		if(Phpfox::VERSION < '3.5.0')
		{
			Phpfox::getLib('setting')->setParam('feed.enable_check_in',false);
		}
		
		$iUserId = $this -> getParam('user_id');
		$iLimit = $this->getParam('iLimit') ? $this->getParam('iLimit') : Phpfox::getParam('feed.feed_display_limit');
        $sViewId = $this->getParam('sViewId') ? $this->getParam('sViewId') : 'all';
		$bIsViewMore  = $this->getParam('bIsViewMore');
		$bIsFilter = $this->getParam('bIsFilter');
		
		$bForceFormOnly = $this->getParam('bForceFormOnly');
		$bIsCustomFeedView = false;
		$sCustomViewType = null;

		if (PHPFOX_IS_AJAX && ($iUserId = $this -> request() -> get('profile_user_id')))
		{
			if (!defined('PHPFOX_IS_USER_PROFILE'))
			{
				define('PHPFOX_IS_USER_PROFILE', true);
			}
			$aUser = Phpfox::getService('user') -> get($iUserId);

			$this -> template() -> assign(array('aUser' => $aUser));
		}

		if (PHPFOX_IS_AJAX && $this -> request() -> get('callback_module_id'))
		{
			$aCallback = Phpfox::callback($this -> request() -> get('callback_module_id') . '.getFeedDisplay', $this -> request() -> get('callback_item_id'));
			$this -> setParam('aFeedCallback', $aCallback);
		}

		$aFeedCallback = $this -> getParam('aFeedCallback', null);

		$bIsProfile = (is_numeric($iUserId) && $iUserId > 0);

		if ($this -> request() -> get('feed') && $bIsProfile)
		{
			switch ($this->request()->get('flike'))
			{
				default :
					if ($sPlugin = Phpfox_Plugin::get('feed.component_block_display_process_flike'))
					{
						eval($sPlugin);
					}
					break;
			}
		}

		if (defined('PHPFOX_IS_USER_PROFILE') && !Phpfox::getService('user.privacy') -> hasAccess($iUserId, 'feed.view_wall'))
		{
			return false;
		}

		if (defined('PHPFOX_IS_PAGES_VIEW') && !Phpfox::getService('pages') -> hasPerm(null, 'pages.share_updates'))
		{
			$aFeedCallback['disable_share'] = true;
		}

		$iFeedPage = $this -> request() -> get('page', 0);

		if ($this -> request() -> getInt('status-id') 
			|| $this -> request() -> getInt('comment-id') 
			|| $this -> request() -> getInt('link-id') 
			|| $this -> request() -> getInt('plink-id') 
			|| $this -> request() -> getInt('poke-id') 
			|| $this -> request() -> getInt('feed')
		)
		{
			$bIsCustomFeedView = true;
			if ($this -> request() -> getInt('status-id'))
			{
				$sCustomViewType = Phpfox::getPhrase('feed.status_update_iid', array('iId' => $this -> request() -> getInt('status-id')));
			}
			elseif ($this -> request() -> getInt('link-id'))
			{
				$sCustomViewType = Phpfox::getPhrase('feed.link_iid', array('iId' => $this -> request() -> getInt('link-id')));
			}
			elseif ($this -> request() -> getInt('plink-id'))
			{
				$sCustomViewType = Phpfox::getPhrase('feed.link_iid', array('iId' => $this -> request() -> getInt('plink-id')));
			}
			elseif ($this -> request() -> getInt('poke-id'))
			{
				$sCustomViewType = Phpfox::getPhrase('feed.poke_iid', array('iId' => $this -> request() -> getInt('poke-id')));
			}
			elseif ($this -> request() -> getInt('comment-id'))
			{
				$sCustomViewType = Phpfox::getPhrase('feed.wall_comment_iid', array('iId' => $this -> request() -> getInt('comment-id')));

				Phpfox::getService('notification.process') -> delete('feed_comment_profile', $this -> request() -> getInt('comment-id'), Phpfox::getUserId());
			}
			elseif ($this -> request() -> getInt('feed'))
			{
				$sCustomViewType = Phpfox::getPhrase('feed.feed');
			}
		}

		/** 
		 * process feed type here
		 */
		$aFeedTypes = array(
            "all" => Phpfox::getPhrase('wall.all_feeds'),
            "user_status" => Phpfox::getPhrase('wall.user_status'),
            "friend"=>Phpfox::getPhrase('wall.friends')
        );
		
		/**
		 * load custom feed type from here.
		 */		
		(($sPlugin = Phpfox_Plugin::get('wall.service_get_feed_types')) ? eval($sPlugin) : false);
		
		$bIsLogged = false;
        $bIsFacebook = false;
        $bIsTwitter = false;

        $havingSocialStream = false; 
        if(Phpfox::isModule('socialstream')){
        	$socialstream = Phpfox::getService('admincp.module')->getForEdit('socialstream');
        	if(isset($socialstream) && $socialstream['product_id'] == 'socialstream'){
        		$havingSocialStream = true; 
        	}
        }

        if ($havingSocialStream && Phpfox::isModule('socialbridge'))
        {
            $iStreamUserId = Phpfox::getUserId();
            $aProviders = Phpfox::getService('socialbridge')->getAllProviderData($iStreamUserId);

            if(!array_key_exists('facebook', $aProviders))
                $aProviders['facebook'] = null;
            if(!array_key_exists('twitter', $aProviders))
                $aProviders['twitter'] = null;

            $oService = Phpfox::getService('socialstream.services');

            $bIsLogged = $oService->isLogged($iStreamUserId);

            if ($aProviders['facebook'] && $aProviders['facebook']['connected'])
            {
                $aFacebookSetting = $oService->getSetting('facebook', $iStreamUserId, $aProviders['facebook']['profile']['identity']);
                if(isset($aFacebookSetting['enable']) && $aFacebookSetting['enable'] == 1)
                    $aFeedTypes['socialstream_facebook'] = Phpfox::getPhrase('socialstream.facebook');

                $bIsFacebook = (bool)$aFacebookSetting['enable'];
            }

            if ($aProviders['twitter'] && $aProviders['twitter']['connected'])
            {
                $aTwitterSetting = $oService->getSetting('twitter', $iStreamUserId, $aProviders['twitter']['profile']['identity']);
                if(isset($aTwitterSetting['enable']) && $aTwitterSetting['enable'] == 1)
                    $aFeedTypes['socialstream_twitter'] = Phpfox::getPhrase('socialstream.twitter');

                $bIsTwitter =  (bool)$aTwitterSetting['enable'];
            }
			
			if($havingSocialStream)
			{
				$aFeedTypes['network_only'] = Phpfox::getPhrase('wall.network_only');
			}
            
            if(!$bIsFacebook && !$bIsTwitter)
                $bIsLogged = false;
        }

		
		 $this->template()->assign(array(
            "aFeedTypes" => $aFeedTypes
            , "havingSocialStream" => $havingSocialStream
            /* ,
                  "aLimits" => $aLimits */
        ));
		
		// Emoticons list for js
        if (Phpfox::isModule('emoticon'))
        {
            $oEmoticon = Phpfox::getService('emoticon');
            $aPackages = $oEmoticon->getPackages();
            $aEmoticons = array();
            foreach ($aPackages as $aPackage)
            {
                if ($aPackage["is_active"] == 1)
                {
                    $aEmoticons = array_merge($aEmoticons, $oEmoticon->getEmoticons($aPackage["package_path"]));
                }
            }
        }
        else
        {
            $aEmoticons = array();
        }
        $this->template()->assign(array(
            "aEmoticons" => json_encode($aEmoticons)
        ));

		if ((!isset($aFeedCallback['item_id']) || $aFeedCallback['item_id'] == 0))
		{
			$aFeedCallback['item_id'] = ((int)$this -> request() -> get('amp;callback_item_id')) > 0 ? $this -> request() -> get('amp;callback_item_id') : $this -> request() -> get('callback_item_id');
		}

		$bForceReloadOnPage = (PHPFOX_IS_AJAX ? false : Phpfox::getParam('feed.force_ajax_on_load'));
		$aRows = array();
		if (PHPFOX_IS_AJAX || !$bForceReloadOnPage || $bIsCustomFeedView)
		{
			$aRows = Phpfox::getService('wall.feed') -> callback($aFeedCallback) -> get(($bIsProfile > 0 ? $iUserId : null), ($this -> request() -> get('feed') ? $this -> request() -> get('feed') : null), $iFeedPage,FALSE, $sViewId);
			if (empty($aRows))
			{
				$iFeedPage++;
				$aRows = Phpfox::getService('wall.feed') -> callback($aFeedCallback) -> get(($bIsProfile > 0 ? $iUserId : null), ($this -> request() -> get('feed') ? $this -> request() -> get('feed') : null), $iFeedPage,FALSE,$sViewId);
			}
		}
		/*
		else
		{
			$aRows = Phpfox::getService('feed')->callback($aFeedCallback)->get(($bIsProfile > 0 ? $iUserId : null), ($this->request()->get('feed') ? $this->request()->get('feed') : null), $iFeedPage);
		}
		*/

		if (($this -> request() -> getInt('status-id') 
			|| $this -> request() -> getInt('comment-id') 
			|| $this -> request() -> getInt('link-id') 
			|| $this -> request() -> getInt('poke-id')
			|| $this->request()->getInt('feed')
			) 
			&& isset($aRows[0])
		)
		{
			$aRows[0]['feed_view_comment'] = true;
			$this -> setParam('aFeed', array_merge(array(
				'feed_display' => 'view',
				'total_like' => $aRows[0]['feed_total_like']
			), $aRows[0]));
		}

		(($sPlugin = Phpfox_Plugin::get('feed.component_block_display_process')) ? eval($sPlugin) : false);

		if ($bIsCustomFeedView && !count($aRows) && $bIsProfile)
		{
			$aUser = $this -> getParam('aUser');

			$this -> url() -> send($aUser['user_name'], null, Phpfox::getPhrase('feed.the_activity_feed_you_are_looking_for_does_not_exist'));
		}

		$iUserid = ($bIsProfile > 0 ? $iUserId : null);
		$iTotalFeeds = (int)Phpfox::getComponentSetting(($iUserid === null ? Phpfox::getUserId() : $iUserid), 'feed.feed_display_limit_' . ($iUserid !== null ? 'profile' : 'dashboard'), Phpfox::getParam('feed.feed_display_limit'));
		
		if(!isset($aUser) || !$aUser)
		{
			$aUser = Phpfox::getService('user')->get($iUserid);
		}

		/*
		 if (!Phpfox::isMobile())
		 {
		 $this->template()->assign(array(
		 'sHeader' => Phpfox::getPhrase('feed.activity_feed')
		 )
		 );
		 }
		 */
		 
		 $aUserLocation = Phpfox::getUserBy('location_latlng');
		if (!empty($aUserLocation))
		{
			$this->template()->assign(array('aVisitorLocation' => json_decode($aUserLocation, true)));
		}
		$bLoadCheckIn = false;
		if (!defined('PHPFOX_IS_PAGES_VIEW') && Phpfox::getParam('feed.enable_check_in') && (Phpfox::getParam('core.ip_infodb_api_key') || Phpfox::getParam('core.google_api_key') ) )
		{
			$bLoadCheckIn = true;
		}
		
		foreach ($aRows as $iKey => $aRow)
		{
			if (!isset($aRow['feed_like_phrase']))
			{
				if(Phpfox::isModule('like'))
				{
					$aRows[$iKey]['feed_like_phrase'] = Phpfox::getService('wall.feed')->getPhraseForLikes($aRow);	
				}				
			}
		}

		$bIsHashTagPop = ($this->request()->get('hashtagpopup') ? true : false);
		if ($bIsHashTagPop)
		{
			define('PHPFOX_FEED_HASH_POPUP', true);
		}
		
		
		$sCorePath = Phpfox::getParam('core.path');
		$sUrlUser = Phpfox::getParam('core.url_user');
		$this -> template() -> assign(array(
			'bForceReloadOnPage' => $bForceReloadOnPage,	
			'bHideEnterComment' => true,			
			'iUserId'=>$iUserId,
			'bIsViewMore'=>$bIsViewMore,
			'bLoadCheckIn' => $bLoadCheckIn,
			'aUser'=>$aUser,
			'aFeeds' => $aRows,
			'bIsLogged'=>$bIsLogged,
			'bIsFilter'=>$bIsFilter,
			'iFeedNextPage' => ($bForceReloadOnPage ? 0 : ($iFeedPage + 1)),
			'iFeedCurrentPage' => $iFeedPage,
			'iTotalFeedPages' => 1,
			'aFeedVals' => $this -> request() -> getArray('val'),
			'sCustomViewType' => $sCustomViewType,
			'aFeedStatusLinks' => Phpfox::getService('wall.feed') -> getShareLinks(),
			'aFeedCallback' => $aFeedCallback,
			'bIsCustomFeedView' => $bIsCustomFeedView,
			'sTimelineYear' => $this -> request() -> get('year'),
			'sTimelineMonth' => $this -> request() -> get('month'),
			'iFeedUserSortOrder' => Phpfox::getUserBy('feed_sort'),
			'bForceFormOnly' => $bForceFormOnly,
			'sCorePath' => $sCorePath,
			'corePath'=>$sCorePath,
			'sIsHashTagSearch' => urlencode(strip_tags((($this->request()->get('hashtagsearch') ? $this->request()->get('hashtagsearch') : ($this->request()->get('req1') == 'hashtag' ? $this->request()->get('req2') : ''))))),
			'sIsHashTagSearchValue' => urldecode(strip_tags((($this->request()->get('hashtagsearch') ? $this->request()->get('hashtagsearch') : ($this->request()->get('req1') == 'hashtag' ? $this->request()->get('req2') : ''))))),
			'bIsHashTagPop' => $bIsHashTagPop
		));

		if (Phpfox::getParam('video.convert_servers_enable') && !PHPFOX_IS_AJAX)
		{
			$aVideoServers = Phpfox::getParam('video.convert_servers');
			$sCustomServerUrl = $aVideoServers[rand(0, (count($aVideoServers) - 1))];
			$this->template()->assign('sVideoServerUrl', $sCustomServerUrl);
			$this->template()->assign('sCustomVideoHash', Phpfox::getService('video')->addCustomHash());
		}		

		$iCurUserId = Phpfox::getUserId();
		if ($iCurUserId)
		{
			// Friends list for js
			$aFriends = Phpfox::getService('friend') -> get(array(), 'friend.time_stamp DESC', '', 500, $bCount = false, true, false, $iCurUserId, false);
			$aRows = array();

			foreach ($aFriends as $aFriend)
			{
				if ($aFriend['full_name'])
				{
					$text = $aFriend['full_name'];
				}
				else
				{
					$text = $aFriend['user_name'];
				}

				if ($aFriend['user_image'])
				{
					$photo = $sUrlUser . sprintf($aFriend['user_image'], '_50_square');
				}
				else
				{
					$photo = $sCorePath . "theme/frontend/default/style/default/image/noimage/profile_50.png";
				}

				$aRows[] = array(
					'id' => $aFriend['user_id'],
					'type' => 'user',
					'photo' => $photo,
					'text' => html_entity_decode(Phpfox::getLib('parse.output')->split($text, 20), null, 'UTF-8'),
				);
			}

			unset($aFriends);

			$this -> template() -> assign(array("aJSONFriends" => json_encode($aRows)));
		}
		if (Phpfox::getService('wall.feed') -> timeline())
		{
			$this -> template() -> assign(array(
				'aFeedTimeline' => Phpfox::getService('wall.feed') -> getTimeline(),
				'sLastDayInfo' => Phpfox::getService('wall.feed') -> getLastDay()
			));

			if (!PHPFOX_IS_AJAX)
			{
				$aUser = $this -> getParam('aUser');

				if( $aUser['birthday'] == null)
				{
					$aTimeline = Phpfox::getService('wall.feed') -> getTimeLineYears($aUser['user_id'], $aUser['joined']);
				}
				else
				{
					$aTimeline = Phpfox::getService('wall.feed') -> getTimeLineYears($aUser['user_id'], $aUser['birthday_search']);
			}

				$this -> template() -> assign(array('aTimelineDates' => $aTimeline));
			}
		}

		if ($bIsProfile)
		{
			if (!Phpfox::getService('user.privacy') -> hasAccess($iUserId, 'feed.display_on_profile'))
			{
				return false;
			}
		}

		return 'block';
	}

	public function clean()
	{
		$this -> template() -> clean(array(
			'sHeader',
			'aFeeds',
			'sBoxJsId'
		));
	}

}
?>