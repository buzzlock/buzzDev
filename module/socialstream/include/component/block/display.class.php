<?php

defined('PHPFOX') or exit('NO DICE!');


class SocialStream_Component_Block_Display extends Phpfox_Component
{

    /**
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
        if (!Phpfox::isModule('socialbridge'))
        {
            return false;
        }
        
        // Additional params
        $iLimit = $this->getParam('iLimit') ? $this->getParam('iLimit') : Phpfox::getParam('feed.feed_display_limit');
        $sViewId = $this->getParam('sViewId') ? $this->getParam('sViewId') : 'all';
        $iUserId = intval($this->getParam('user_id'));
        $bIsCustomFeedView = false;
        $sCustomViewType = null;

        if (PHPFOX_IS_AJAX && ($iUserId = $this->getParam('profile_user_id')))
        {
            if (!defined('PHPFOX_IS_USER_PROFILE'))
            {
                define('PHPFOX_IS_USER_PROFILE', true);
            }
            $aUser = Phpfox::getService('user')->get($iUserId);
            $this->template()->assign(array('aUser' => $aUser));
        }

        if (PHPFOX_IS_AJAX && $this->request()->get('callback_module_id'))
        {
            $aCallback = Phpfox::callback($this->request()->get('callback_module_id') . '.getFeedDisplay', $this->request()->get('callback_item_id'));
            $this->setParam('aFeedCallback', $aCallback);
        }

        $aFeedCallback = $this->getParam('aFeedCallback', null);

        $bIsProfile = (is_numeric($iUserId) && $iUserId > 0);

        if ($this->request()->get('feed') && $bIsProfile)
        {
            switch ($this->request()->get('flike'))
            {
                default:
                    if ($sPlugin = Phpfox_Plugin::get('feed.component_block_display_process_flike'))
                    {
                        eval($sPlugin);
                    }
            }
        }

        if (defined('PHPFOX_IS_USER_PROFILE') && !Phpfox::getService('user.privacy')->hasAccess($iUserId, 'feed.view_wall'))
        {
            return false;
        }

        if (defined('PHPFOX_IS_PAGES_VIEW') && !Phpfox::getService('pages')->hasPerm(null, 'pages.share_updates'))
        {
            $aFeedCallback['disable_share'] = true;
        }

        $iFeedPage = $this->request()->get('page', 0);

        if ($this->request()->getInt('status-id') 
        || $this->request()->getInt('comment-id') 
        || $this->request()->getInt('link-id') 
        || $this->request()->getInt('plink-id') //Fix TIMELINE
        || $this->request()->getInt('poke-id') 
        || $this->request()->getInt('feed'))
        {
            $bIsCustomFeedView = true;
            if ($this->request()->getInt('status-id'))
            {
                $sCustomViewType = Phpfox::getPhrase('socialstream.status_update') . ': #' . $this->request()->getInt('status-id');
            }
            elseif ($this->request()->getInt('link-id'))
            {
                $sCustomViewType = Phpfox::getPhrase('socialstream.link') . ': #' . $this->request()->getInt('link-id');
            }
            elseif ($this->request()->getInt('poke-id'))
            {
                $sCustomViewType = Phpfox::getPhrase('socialstream.poke') . ': #' . $this->request()->getInt('poke-id');
            }
            elseif ($this->request()->getInt('comment-id'))
            {
                $sCustomViewType = Phpfox::getPhrase('socialstream.wall_comment') . ': #' . $this->request()->getInt('comment-id');

                Phpfox::getService('notification.process')->delete('feed_comment_profile', $this->request()->getInt('comment-id'), Phpfox::getUserId());
            }
            elseif ($this->request()->getInt('feed'))
            {
                $sCustomViewType = Phpfox::getPhrase('socialstream.feed');
            }
        }

        $iUserId = intval($this->getParam('user_id')); // fix an unknown error

        $aRows = Phpfox::getService('socialstream.feed')->callback($aFeedCallback)->get(($iUserId > 0 ? $iUserId : null), ($this->request()->get('feed') ? $this->request()->get('feed') : null), $iFeedPage, false, $sViewId);

        header('c-arows: ' . count($aRows));

        if (($this->request()->getInt('status-id') || $this->request()->getInt('comment-id') || $this->request()->getInt('link-id') || $this->request()->getInt('poke-id')) && isset($aRows[0]))
        {
            $aRows[0]['feed_view_comment'] = true;
            $this->setParam('aFeed', array_merge(array('feed_display' => 'view', 'total_like' => $aRows[0]['feed_total_like']), $aRows[0]));
        }

        (($sPlugin = Phpfox_Plugin::get('feed.component_block_display_process')) ? eval($sPlugin) : false);

        if ($bIsCustomFeedView && !count($aRows) && $bIsProfile)
        {
            $aUser = $this->getParam('aUser');

            $this->url()->send($aUser['user_name'], null, Phpfox::getPhrase('feed.the_activity_feed_you_are_looking_for_does_not_exist'));
        }

        $iUserid = ($bIsProfile > 0 ? $iUserId : null);
        $iTotalFeeds = (int)Phpfox::getComponentSetting(($iUserid === null ? Phpfox::getUserId() : $iUserid), 'feed.feed_display_limit_' . ($iUserid !== null ? 'profile' : 'dashboard'), $iLimit);

        if (!Phpfox::isMobile())
        {
            $this->template()->assign(array('sHeader' => ''));
        }

        $iCurUserId = Phpfox::getUserId();
        $sView = $bIsProfile ? "profile" : "index";

        $oUser = Phpfox::getService('user');
        $aUser = $oUser->get($iUserId);
        //Fix for egift module
        if (isset($aUser['birthday']))
        {
            $aUser['birthday_time_stamp'] = $aUser['birthday'];
            $aUser['birthday'] = $oUser->age($aUser['birthday']);
            $aUser['is_user_birthday'] = ((empty($aUser['birthday_time_stamp']) ? false : (int)floor(Phpfox::getLib('date')->daysToDate($aUser['birthday_time_stamp'], null, false)) === 0 ? true : false));
        }
        $this->setParam('aUser', $aUser);

        $iWallOwnerId = !empty($aUser['user_id']) ? $aUser['user_id'] : $iCurUserId;
        
        $this->template()->assign(array(
            'sView' => $sView,
            'iWallOwnerId' => $iWallOwnerId,
            'iCurUserId' => $iCurUserId
            ));
        
        $aRows = Phpfox::getService('socialstream.feed')->fillVisibility($aRows, $iCurUserId, $sView, $iWallOwnerId);
        $aRows = Phpfox::getService('socialstream.feed')->displayLinks($aRows);

        if (Phpfox::getService('socialbridge')->timeline())
        {
            $this->template()->assign(array('aFeedTimeline' => Phpfox::getService('socialstream.feed')->getTimeline(), 'sLastDayInfo' => Phpfox::getService('socialstream.feed')->getLastDay()));

            if (!PHPFOX_IS_AJAX)
            {
                $aUser = $this->getParam('aUser');

                $aTimeline = Phpfox::getService('socialstream.feed')->getTimeLineYears($aUser['user_id'], $aUser['birthday_search']);

                $this->template()->assign(array('aTimelineDates' => $aTimeline));
            }
        }
        
        $this->template()->assign(array(
            'iUserId' => $iUserId,
            'aFeeds' => $aRows,
            'iFeedNextPage' => ($iFeedPage + 1),
            'iFeedCurrentPage' => $iFeedPage,
            'iTotalFeedPages' => 1,
            'aFeedVals' => $this->request()->getArray('val'),
            'sCustomViewType' => $sCustomViewType,
            'aFeedStatusLinks' => Phpfox::getService('feed')->getShareLinks(),
            'aFeedCallback' => $aFeedCallback,
            'bIsCustomFeedView' => $bIsCustomFeedView,
            'sTimelineYear' => $this->request()->get('year'),
            'sTimelineMonth' => $this->request()->get('month'),
            'sFeedType' => 'normal'
            ));

        //Fix for timeline
        if ($iFeedPage == 0)
        {
            Phpfox::getLib('request')->set('resettimeline', true);
        }

        if ($bIsProfile && Phpfox::getService('socialbridge')->timeline())
        {
            if (!Phpfox::getService('user.privacy')->hasAccess($iUserId, 'feed.display_on_profile'))
            {
                return false;
            }
        }

        return 'block';
    }

    public function clean()
    {
        $this->template()->clean(array(
            'sHeader',
            'aFeeds',
            'sBoxJsId'
            ));
    }

}

?>