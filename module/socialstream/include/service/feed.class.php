<?php

defined('PHPFOX') or exit('NO DICE!');


class SocialStream_Service_Feed extends Phpfox_Service
{

    private $_aViewMoreFeeds = array();
    private $_aCallback = array();
    //TIMELINE FIX
    private $_sLastDayInfo = '';
    //TIMELINE FIX
    private $_aFeedTimeline = array('left' => array(), 'right' => array());

    public function __construct()
    {
        $this->_sTable = Phpfox::getT('feed');

        (($sPlugin = Phpfox_Plugin::get('feed.service_feed___construct')) ? eval($sPlugin) : false);
    }

    public function callback($aCallback)
    {
        $this->_aCallback = $aCallback;

        return $this;
    }

    public function setTable($sTable)
    {
        $this->_sTable = $sTable;
    }

    public function fillVisibility($aFeeds, $iViewerId, $sView, $iOwnerId)
    {
        if(Phpfox::isModule('wall'))
        {
            $aHiddenFeedIds = $this->database()->select('feed_id')
                ->from(Phpfox::getT('wall_hidden'))
                ->where("viewer_id = '$iViewerId' AND view_id = '$sView' AND owner_id = '$iOwnerId'")
                ->execute("getRows");
            
            foreach ($aFeeds as $iKey => $aFeed)
            {
                // Also truncate html
                if (!empty($aFeeds[$iKey]['feed_status']))
                {
                    //$aFeeds[$iKey]['feed_status'] = $this->truncate($aFeeds[$iKey]['feed_status'], 200);
                }
                
                if (in_array(array('feed_id' => $aFeed['feed_id']), $aHiddenFeedIds))
                {
                    $aFeeds[$iKey]["visible"] = false;
                    continue;
                }
                
                $aFeeds[$iKey]["visible"] = true;
                if (isset($aFeeds[$iKey]["more_feed_rows"]))
                {
                    foreach ($aFeeds[$iKey]["more_feed_rows"] as $iKey2 => $aFeed2)
                    {
                        // Also truncate html
                        if (!empty($aFeeds[$iKey]["more_feed_rows"][$iKey2]['feed_status']))
                        {
                            //$aFeeds[$iKey]["more_feed_rows"][$iKey2]['feed_status'] = $this->truncate($aFeeds[$iKey]["more_feed_rows"][$iKey2]['feed_status'], 200);
                        }
                        if (in_array(array('feed_id' => $aFeed2['feed_id']), $aHiddenFeedIds))
                        {
                            $aFeeds[$iKey]["more_feed_rows"][$iKey2]["visible"] = false;
                            continue;
                        }
                        $aFeeds[$iKey]["more_feed_rows"][$iKey2]["visible"] = true;
                    }
                }
            }
        }
        return $aFeeds;
    }

    public function displayLinks($aFeeds)
    {
        foreach ($aFeeds as $iKey => $aFeed)
        {
            if (isset($aFeeds[$iKey]["feed_status"]))
            {
                // Freeze the tags
                $aFeed["feed_status"] = preg_replace("/(src|href)=\"http/i", "$1=\"h--p", $aFeed["feed_status"]);
                // Convert links to anchors
                $aFeed["feed_status"] = preg_replace("/(http[s]*:\/\/[a-zA-Z0-9\-\._]{2,255}\.[a-zA-Z]{2,5}[a-zA-Z0-9\.\-\_#\?&=\|\/\(\)]*)/i", "<a href=\"$1\" target=\"_blank\">$1</a>", $aFeed["feed_status"]);
                // Freeze the www
                $aFeed["feed_status"] = preg_replace("/:\/\/www\./i", "://___.", $aFeed["feed_status"]);
                // Convert links to anchors (non http)
                $aFeed["feed_status"] = preg_replace("/(www\.[a-zA-Z0-9\-\._]{2,255}\.[a-zA-Z]{2,5}[a-zA-Z0-9\.\-\_#\?&=\|\/\(\)]*)/i", "<a href=\"http://$1\" target=\"_blank\">$1</a>", $aFeed["feed_status"]);
                // Unfreeze the www
                $aFeed["feed_status"] = preg_replace("/:\/\/___\./i", "://www.", $aFeed["feed_status"]);
                // Unfreeze the tags
                $aFeed["feed_status"] = preg_replace("/(src|href)=\"h--p/i", "$1=\"http", $aFeed["feed_status"]);
                $aFeeds[$iKey]["feed_status"] = $aFeed["feed_status"];
            }
            $aFeed["feed_status"] = "http://www.google.com.vn";
        }
        return $aFeeds;
    }

    public function get($iUserid = null, $iFeedId = null, $iPage = 0, $bForceReturn = false, $sViewId = 'all', $iLimit = null)
    {
        $oUrl = Phpfox::getLib('url');
        $oReq = Phpfox::getLib('request');
        $oParseOutput = Phpfox::getLib('parse.output');

        $sDiffCond = '';
        $aDiffCond = array();
        if (!Phpfox::isModule('socialstream'))
        {
            $aDiffCond[] = "AND feed.type_id != 'socialstream_facebook'";
            $aDiffCond[] = "AND feed.type_id != 'socialstream_twitter'";
            $sDiffCond .= "feed.type_id != 'socialstream_facebook' AND feed.type_id != 'socialstream_twitter' AND ";
        }

        Phpfox_Error::skip(true);
        if (Phpfox::isModule('socialstream') && Phpfox::getParam('socialstream.show_feeds_automatically') == false && $sViewId != 'socialstream_facebook' && $sViewId != 'socialstream_twitter')
        {
            $aDiffCond[] = "AND feed.type_id != 'socialstream_facebook'";
            $aDiffCond[] = "AND feed.type_id != 'socialstream_twitter'";
            $sDiffCond .= "feed.type_id != 'socialstream_facebook' AND feed.type_id != 'socialstream_twitter' AND ";
        }
        Phpfox_Error::skip(false);
        if ($sViewId != 'all')
        {
        	if($sViewId!="network_only")
            {
            	$aDiffCond[] = "AND feed.type_id = '$sViewId'";
            	$sDiffCond .= "feed.type_id = '$sViewId' AND ";
            }
			else {
				  $aDiffCond[] = "AND feed.type_id != 'socialstream_facebook'";
            	$aDiffCond[] = "AND feed.type_id != 'socialstream_twitter'";
				$sDiffCond .= "feed.type_id != 'socialstream_facebook' AND feed.type_id != 'socialstream_twitter' And ";
			}
        }
		
        if (($iCommentId = $oReq->getInt('comment-id')))
        {
            if (isset($this->_aCallback['feed_comment']))
            {
                $aCustomCondition = array('feed.type_id = \'' . $this->_aCallback['feed_comment'] . '\' AND feed.item_id = ' . (int) $iCommentId . ' AND feed.parent_user_id = ' . (int) $this->_aCallback['item_id']);
            }
            else
            {
                $aCustomCondition = array('feed.type_id IN(\'feed_comment\', \'feed_egift\') AND feed.item_id = ' . (int) $iCommentId . ' AND feed.parent_user_id = ' . (int) $iUserid);
            }

            $iFeedId = true;
        }
        elseif (($iStatusId = $oReq->getInt('status-id')))
        {
            $aCustomCondition = array('feed.type_id = \'user_status\' AND feed.item_id = ' . (int) $iStatusId . ' AND feed.user_id = ' . (int) $iUserid);
            $iFeedId = true;
        }
        elseif (($iLinkId = $oReq->getInt('link-id')))
        {
            $aCustomCondition = array('feed.type_id = \'link\' AND feed.item_id = ' . (int) $iLinkId . ' AND feed.user_id = ' . (int) $iUserid);
            $iFeedId = true;
        }
        elseif (($iLinkId = $oReq->getInt('plink-id')))
        {
            $aCustomCondition = array('feed.type_id = \'link\' AND feed.item_id = ' . (int) $iLinkId . ' AND feed.parent_user_id  = ' . (int) $iUserid);
            $iFeedId = true;
        }
        elseif (($iPokeId = $oReq->getInt('poke-id')))
        {
            $aCustomCondition = array('feed.type_id = \'poke\' AND feed.item_id = ' . (int) $iPokeId . ' AND feed.user_id = ' . (int) $iUserid);
            $iFeedId = true;
        }

        $iTotalFeeds = (int) Phpfox::getComponentSetting(($iUserid === null ? Phpfox::getUserId() : $iUserid), 'feed.feed_display_limit_' . ($iUserid !== null ? 'profile' : 'dashboard'), Phpfox::getParam('feed.feed_display_limit'));

        $iOffset = ($iPage * $iTotalFeeds);

        (($sPlugin = Phpfox_Plugin::get('feed.service_feed_get_start')) ? eval($sPlugin) : false);


        $aCond = array();
        if (isset($this->_aCallback['module']))
        {
            $aNewCond = array();
            if (($iCommentId = $oReq->getInt('comment-id')))
            {
                if (!isset($this->_aCallback['feed_comment']))
                {
                    $aCustomCondition = array('feed.type_id = \'event_comment\' AND feed.item_id = ' . (int) $iCommentId . '');
                }
            }
            $aNewCond[] = $sDiffCond . 'feed.parent_user_id = ' . (int) $this->_aCallback['item_id'];
            if ($iUserid !== null && $iFeedId !== null)
            {
                $aNewCond[] = 'AND feed.feed_id = ' . (int) $iFeedId . ' AND feed.user_id = ' . (int) $iUserid;
            }
		
            $aRows = $this->database()->select('feed.*, ' . Phpfox::getUserField() . ', u.view_id')
                ->from(Phpfox::getT($this->_aCallback['table_prefix'] . 'feed'), 'feed')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = feed.user_id')
                ->where((isset($aCustomCondition) ? $aCustomCondition : $aNewCond))
                ->order('feed.time_stamp DESC')
                ->limit($iOffset, $iTotalFeeds)
                ->execute('getSlaveRows');
        }
        elseif (($sIds = $oReq->get('ids')))
        {
            $aParts = explode(',', $oReq->get('ids'));
            $sNewIds = '';
            foreach ($aParts as $sPart)
            {
                $sNewIds .= (int) $sPart . ',';
            }
            $sNewIds = rtrim($sNewIds, ',');

            $aRows = $this->database()->select('feed.*, ' . Phpfox::getUserField() . ', u.view_id')
                ->from($this->_sTable, 'feed')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = feed.user_id')
                ->where('feed.feed_id IN(' . $sNewIds . ')')
                ->order('feed.time_stamp DESC')
                ->execute('getSlaveRows');
        }
        elseif ($iUserid !== null && $iFeedId !== null)
        {
            $aRows = $this->database()->select('feed.*, apps.app_title, ' . Phpfox::getUserField() . ', u.view_id')
                ->from($this->_sTable, 'feed')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = feed.user_id')
                ->leftJoin(Phpfox::getT('app'), 'apps', 'apps.app_id = feed.app_id')
                ->where((isset($aCustomCondition) ? $aCustomCondition : $sDiffCond . 'feed.feed_id = ' . (int) $iFeedId . ' AND feed.user_id = ' . (int) $iUserid))
                ->order('feed.time_stamp DESC')
                ->limit(1)
                ->execute('getSlaveRows');
        }
        elseif ($iUserid !== null)
        {
            $aProviders = Phpfox::getService('socialbridge')->getAllProviderData(Phpfox::getUserId());

            if(!array_key_exists('facebook', $aProviders))
            {
                $aCond[] = "AND (feed.type_id != 'socialstream_facebook') ";
                $aProviders['facebook'] = null;
            }
            elseif(!array_key_exists('twitter', $aProviders))
            {
                $aCond[] = "AND (feed.type_id != 'socialstream_twitter') ";
                $aProviders['twitter'] = null;
            }
            else
            {
                $aFacebookSetting = $aTwitterSetting = null;
                if($aProviders['facebook'])
                    $aFacebookSetting = Phpfox::getService('socialstream.services')->getSetting('facebook', Phpfox::getUserId(), $aProviders['facebook']['profile']['identity']);
                if($aProviders['twitter'])
                    $aTwitterSetting = Phpfox::getService('socialstream.services')->getSetting('twitter', Phpfox::getUserId(), $aProviders['twitter']['profile']['identity']);

                if(!$aFacebookSetting)
                {
                    $aCond[] = "AND (feed.type_id != 'socialstream_facebook') ";
                }

                if(!$aTwitterSetting)
                {
                    $aCond[] = "AND (feed.type_id != 'socialstream_twitter') ";
                }

                if($aFacebookSetting && !$aFacebookSetting['enable'])
                {
                    $aCond[] = "AND (feed.type_id != 'socialstream_facebook') ";
                }

                if($aTwitterSetting && !$aTwitterSetting['enable'])
                {
                    $aCond[] = "AND (feed.type_id != 'socialstream_twitter') ";
                }
            }

            if ($iUserid == Phpfox::getUserId())
            {
                $aCond[] = 'AND feed.privacy IN(0,1,2,3,4)';
            }
            else
            {
                $bCanViewAllFeeds = false;
                
                if($bCanViewAllFeeds)
                {
                    $aCond[] = 'AND feed.privacy IN(0,1,2,3)';
                }
                else
                {
                    if (Phpfox::getService('friend')->isFriend(Phpfox::getUserId(), $iUserid))
                    {
                        $aCond[] = 'AND feed.privacy IN(0,1,2)';
                    }
                    elseif (Phpfox::getService('friend')->isFriendOfFriend($iUserid))
                    {
                        $aCond[] = 'AND feed.privacy IN(0,2)';
                    }
                    else
                    {
                        $aCond[] = 'AND feed.privacy IN(0)';
                    }
                }
            }

            if (Phpfox::getService('socialbridge')->timeline())
            {
                $iTimelineYear = 0;
                if (($iTimelineYear = Phpfox::getLib('request')->get('year')) && !empty($iTimelineYear))
                {
                    $iMonth = 12;
                    $iDay = 31;
                    if (($iTimelineMonth = Phpfox::getLib('request')->get('month')) && !empty($iTimelineMonth))
                    {
                        $iMonth = $iTimelineMonth;
                        $iDay = Phpfox::getLib('date')->lastDayOfMonth($iMonth, $iTimelineYear);
                    }
                    $aCond[] = 'AND feed.time_stamp <= \'' . mktime(0, 0, 0, $iMonth, $iDay, $iTimelineYear) . '\'';
                }
            }

            $this->database()->select('feed.*')
                ->from($this->_sTable, 'feed')
                ->where(array_merge($aCond, $aDiffCond, array('AND feed.user_id = ' . (int) $iUserid)))
                ->union();

            if (Phpfox::isUser())
            {
                $this->database()->select('feed.*')
                    ->from($this->_sTable, 'feed')
                    ->join(Phpfox::getT('privacy'), 'p', 'p.module_id = feed.type_id AND p.item_id = feed.item_id')
                    ->join(Phpfox::getT('friend_list_data'), 'fld', 'fld.list_id = p.friend_list_id AND fld.friend_user_id = ' . Phpfox::getUserId() . '')
                    ->where($sDiffCond . 'feed.privacy IN(4) AND feed.user_id = ' . (int) $iUserid . ' AND feed.feed_reference = 0')
                    ->union();
            }
            $aCond[] =
                $this->database()->select('feed.*')
                    ->from($this->_sTable, 'feed')
                    ->where(array_merge($aCond, $aDiffCond, array('AND feed.parent_user_id = ' . (int) $iUserid)))
                    ->union();

            $aRows = $this->database()->select('feed.*, apps.app_title,  ' . Phpfox::getUserField())
                ->unionFrom('feed')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = feed.user_id')
                ->leftJoin(Phpfox::getT('app'), 'apps', 'apps.app_id = feed.app_id')
                ->order('feed.time_stamp DESC')
                ->group('feed.feed_id')
                ->limit($iOffset, $iTotalFeeds)
                ->execute('getSlaveRows');
        }
        else
        {
            // Users must be active within 7 days or we skip their activity feed
            $iLastActiveTimeStamp = ((int) Phpfox::getParam('feed.feed_limit_days') <= 0 ? 0 : (PHPFOX_TIME - (86400 * Phpfox::getParam('feed.feed_limit_days'))));
            if ($sViewId == 'socialstream_facebook' || $sViewId == 'socialstream_twitter')
            {
                // Get my feeds
                $this->database()->select('feed.*')
                    ->from($this->_sTable, 'feed')
                    ->where($sDiffCond . 'feed.privacy IN(1,2,3,4) AND feed.user_id = ' . Phpfox::getUserId() . ' AND feed.time_stamp > \'' . $iLastActiveTimeStamp . '\' AND feed.feed_reference = 0')
                    ->union();

                // Get my friends feeds
                $this->database()->select('feed.*')
                    ->from($this->_sTable, 'feed')
                    ->join(Phpfox::getT('friend'), 'f', 'f.user_id = feed.user_id AND f.friend_user_id = ' . Phpfox::getUserId())
                    ->where($sDiffCond . 'feed.privacy IN(1,2) AND feed.time_stamp > \'' . $iLastActiveTimeStamp . '\' AND feed.feed_reference = 0')
                    ->union();

                // Get my friends of friends feeds
                $this->database()->select('feed.*')
                    ->from($this->_sTable, 'feed')
                    ->join(Phpfox::getT('friend'), 'f1', 'f1.user_id = feed.user_id')
                    ->join(Phpfox::getT('friend'), 'f2', 'f2.user_id = ' . Phpfox::getUserId() . ' AND f2.friend_user_id = f1.friend_user_id')
                    ->where($sDiffCond . 'feed.privacy IN(2) AND feed.time_stamp > \'' . $iLastActiveTimeStamp . '\' AND feed.feed_reference = 0')
                    ->union();
                
                // Get public feeds
				$bCanViewAllFeeds = false; 
                $this->database()->select('feed.*')
                    ->from($this->_sTable, 'feed')
                    ->where($sDiffCond . 'feed.privacy IN' . ($bCanViewAllFeeds ? '(0,1,2,3)' : '(0)') . ' AND feed.time_stamp > \'' . $iLastActiveTimeStamp . '\' AND feed.feed_reference = 0')
                    ->union();

                $aRows = $this->database()->select('feed.*, f.friend_id AS is_friend, apps.app_title, u.view_id,  ' . Phpfox::getUserField())
                    ->unionFrom('feed')
                    ->join(Phpfox::getT('user'), 'u', 'u.user_id = feed.user_id')
                    ->leftJoin(Phpfox::getT('friend'), 'f', 'f.user_id = feed.user_id AND f.friend_user_id = ' . Phpfox::getUserId())
                    ->leftJoin(Phpfox::getT('app'), 'apps', 'apps.app_id = feed.app_id')
                    ->order('feed.time_stamp DESC')
                    ->group('feed.feed_id')
                    ->limit($iOffset, $iTotalFeeds)
                    ->execute('getSlaveRows');
            }
            else if (Phpfox::getUserParam('privacy.can_view_all_items'))
            {
                #Check privacy
                $bCanViewAllFeeds = false;
                
                $sDiffCond .= '((feed.type_id != "socialstream_facebook" AND feed.type_id != "socialstream_twitter") OR ((feed.type_id = "socialstream_facebook" OR feed.type_id = "socialstream_twitter") AND (feed.user_id = '.Phpfox::getUserId().' OR ';
                $sDiffCond .= $bCanViewAllFeeds ? 'feed.privacy IN(0,1,2,3)))) AND ' : 'feed.privacy IN(0)))) AND ';
                
                #Check provider
                $aProviders = Phpfox::getService('socialbridge')->getAllProviderData(Phpfox::getUserId());

                if(!array_key_exists('facebook', $aProviders))
                {
                    $sDiffCond .= "(feed.type_id != 'socialstream_facebook') AND ";
                }
                else
                {
                    $aFacebookSetting = Phpfox::getService('socialstream.services')->getSetting('facebook', Phpfox::getUserId(), $aProviders['facebook']['profile']['identity']);
                    if(!$aFacebookSetting || !$aFacebookSetting['enable'])
                    {
                        $sDiffCond .= "(feed.type_id != 'socialstream_facebook') AND ";
                    }
                }
                
                if(!array_key_exists('twitter', $aProviders))
                {
                    $sDiffCond .= "(feed.type_id != 'socialstream_twitter') AND ";
                }
                else
                {
                    $aTwitterSetting = Phpfox::getService('socialstream.services')->getSetting('twitter', Phpfox::getUserId(), $aProviders['twitter']['profile']['identity']);
                    if(!$aTwitterSetting || !$aTwitterSetting['enable'])
                    {
                        $sDiffCond .= "(feed.type_id != 'socialstream_twitter') AND ";
                    }
                }

                $aRows = $this->database()->select('feed.*, f.friend_id AS is_friend, apps.app_title, ' . Phpfox::getUserField())
                    ->from(Phpfox::getT('feed'), 'feed')
                    ->join(Phpfox::getT('user'), 'u', 'u.user_id = feed.user_id')
                    ->leftJoin(Phpfox::getT('friend'), 'f', 'f.user_id = feed.user_id AND f.friend_user_id = ' . Phpfox::getUserId())
                    ->leftJoin(Phpfox::getT('app'), 'apps', 'apps.app_id = feed.app_id')
                    ->order('feed.time_stamp DESC')
                    ->group('feed.feed_id')
                    ->limit($iOffset, $iTotalFeeds)
                    ->where($sDiffCond . 'feed.time_stamp > \'' . $iLastActiveTimeStamp . '\' AND feed.feed_reference = 0')
                    ->execute('getSlaveRows');
            }
            else
            {
                #Check provider
                $aProviders = Phpfox::getService('socialbridge')->getAllProviderData(Phpfox::getUserId());

                if(!array_key_exists('facebook', $aProviders))
                {
                    $sDiffCond .= "(feed.type_id != 'socialstream_facebook') AND ";
                }
                else
                {
                    $aFacebookSetting = Phpfox::getService('socialstream.services')->getSetting('facebook', Phpfox::getUserId(), $aProviders['facebook']['profile']['identity']);
                    if(!$aFacebookSetting || !$aFacebookSetting['enable'])
                    {
                        $sDiffCond .= "(feed.type_id != 'socialstream_facebook') AND ";
                    }
                }
                
                if(!array_key_exists('twitter', $aProviders))
                {
                    $sDiffCond .= "(feed.type_id != 'socialstream_twitter') AND ";
                }
                else
                {
                    $aTwitterSetting = Phpfox::getService('socialstream.services')->getSetting('twitter', Phpfox::getUserId(), $aProviders['twitter']['profile']['identity']);
                    if(!$aTwitterSetting || !$aTwitterSetting['enable'])
                    {
                        $sDiffCond .= "(feed.type_id != 'socialstream_twitter') AND ";
                    }
                }
                
                if (Phpfox::getParam('feed.feed_only_friends'))
                {
                    // Get my friends feeds
                    $this->database()->select('feed.*')
                        ->from($this->_sTable, 'feed')
                        ->join(Phpfox::getT('friend'), 'f', 'f.user_id = feed.user_id AND f.friend_user_id = ' . Phpfox::getUserId())
                        ->where($sDiffCond . 'feed.privacy IN(0,1,2) AND feed.time_stamp > \'' . $iLastActiveTimeStamp . '\' AND feed.feed_reference = 0')
                        ->union();

                    // Get my feeds
                    $this->database()->select('feed.*')
                        ->from($this->_sTable, 'feed')
                        ->where($sDiffCond . 'feed.privacy IN(0,1,2,3,4) AND feed.user_id = ' . Phpfox::getUserId() . ' AND feed.time_stamp > \'' . $iLastActiveTimeStamp . '\' AND feed.feed_reference = 0')
                        ->union();
                }
                else
                {
                    // Get my friends feeds
                    $this->database()->select('feed.*')
                        ->from($this->_sTable, 'feed')
                        ->join(Phpfox::getT('friend'), 'f', 'f.user_id = feed.user_id AND f.friend_user_id = ' . Phpfox::getUserId())
                        ->where($sDiffCond . 'feed.privacy IN(1,2) AND feed.time_stamp > \'' . $iLastActiveTimeStamp . '\' AND feed.feed_reference = 0')
                        ->union();

                    // Get my friends of friends feeds
                    $this->database()->select('feed.*')
                        ->from($this->_sTable, 'feed')
                        ->join(Phpfox::getT('friend'), 'f1', 'f1.user_id = feed.user_id')
                        ->join(Phpfox::getT('friend'), 'f2', 'f2.user_id = ' . Phpfox::getUserId() . ' AND f2.friend_user_id = f1.friend_user_id')
                        ->where($sDiffCond . 'feed.privacy IN(2) AND feed.time_stamp > \'' . $iLastActiveTimeStamp . '\' AND feed.feed_reference = 0')
                        ->union();

                    // Get my feeds
                    $this->database()->select('feed.*')
                        ->from($this->_sTable, 'feed')
                        ->where($sDiffCond . 'feed.privacy IN(1,2,3,4) AND feed.user_id = ' . Phpfox::getUserId() . ' AND feed.time_stamp > \'' . $iLastActiveTimeStamp . '\' AND feed.feed_reference = 0')
                        ->union();

                    // Get public feeds
                    $this->database()->select('feed.*')
                        ->from($this->_sTable, 'feed')
                        ->where($sDiffCond . 'feed.privacy IN(0) AND feed.time_stamp > \'' . $iLastActiveTimeStamp . '\' AND feed.feed_reference = 0')
                        ->union();

                    // Get feeds based on custom friends lists
                    $this->database()->select('feed.*')
                        ->from($this->_sTable, 'feed')
                        ->join(Phpfox::getT('privacy'), 'p', 'p.module_id = feed.type_id AND p.item_id = feed.item_id')
                        ->join(Phpfox::getT('friend_list_data'), 'fld', 'fld.list_id = p.friend_list_id AND fld.friend_user_id = ' . Phpfox::getUserId() . '')
                        ->where($sDiffCond . 'feed.privacy IN(4) AND feed.time_stamp > \'' . $iLastActiveTimeStamp . '\' AND feed.feed_reference = 0')
                        ->union();
                }

                $aRows = $this->database()->select('feed.*, f.friend_id AS is_friend, apps.app_title, u.view_id,  ' . Phpfox::getUserField())
                    ->unionFrom('feed')
                    ->join(Phpfox::getT('user'), 'u', 'u.user_id = feed.user_id')
                    ->leftJoin(Phpfox::getT('friend'), 'f', 'f.user_id = feed.user_id AND f.friend_user_id = ' . Phpfox::getUserId())
                    ->leftJoin(Phpfox::getT('app'), 'apps', 'apps.app_id = feed.app_id')
                    ->order('feed.time_stamp DESC')
                    ->group('feed.feed_id')
                    ->limit($iOffset, $iTotalFeeds)
                    ->execute('getSlaveRows');

            }
        }

        if ($bForceReturn === true)
        {
            return $aRows;
        }

        $bFirstCheckOnComments = false;
        if (Phpfox::getParam('feed.allow_comments_on_feeds') && Phpfox::isUser() && Phpfox::isModule('comment'))
        {
            $bFirstCheckOnComments = true;
        }

        $iLoopMaxCount = Phpfox::getParam('feed.group_duplicate_feeds');

        //TIMELINE FIX
        if (Phpfox::getService('socialbridge')->timeline())
        {
            $iLoopMaxCount = 0;
        }

        $aFeedLoop = array();
        $aLoopHistory = array();
        if ($iLoopMaxCount > 0)
        {
            foreach ($aRows as $iKey => $aRow)
            {
                $sFeedKey = $aRow['user_id'] . $aRow['type_id'] . date('dmyH', $aRow['time_stamp']);
                if (isset($aRow['type_id']))
                {
                    $aModule = explode('_', $aRow['type_id']);
                    if (isset($aModule[0]) && Phpfox::isModule($aModule[0]) && Phpfox::hasCallback($aModule[0] . (isset($aModule[1]) ? '_' . $aModule[1] : ''), 'getReportRedirect'))
                    {
                        $aRow['report_module'] = $aRows[$iKey]['report_module'] = $aModule[0] . (isset($aModule[1]) ? '_' . $aModule[1] : '');
                        $aRow['report_phrase'] = $aRows[$iKey]['report_phrase'] = 'Report this entry';
                        $aRow['force_report'] = $aRows[$iKey]['force_report'] = true;
                    }
                }

                if (isset($aFeedLoop[$sFeedKey]))
                {
                    if (!isset($aLoopHistory[$sFeedKey]))
                    {
                        $aLoopHistory[$sFeedKey] = 0;
                    }

                    $aLoopHistory[$sFeedKey]++;

                    if ($aLoopHistory[$sFeedKey] >= ($iLoopMaxCount - 1))
                    {
                        $bIsLoop = true;

                        $this->_aViewMoreFeeds[$sFeedKey][] = $aRow;
                    }
                    else
                    {
                        $aFeedLoop[$sFeedKey . $aLoopHistory[$sFeedKey]] = $aRow;

                        continue;
                    }
                }
                else
                {
                    $aFeedLoop[$sFeedKey] = $aRow;
                }

                if (isset($bIsLoop))
                {
                    unset($bIsLoop);
                }
            }
        }
        else
        {
            $aFeedLoop = $aRows;
        }

        $aFeeds = array();
        $aCacheData = array();
        $sLastFriendId = '';
        $sLastPhotoId = 0;

        foreach ($aFeedLoop as $sKey => $aRow)
        {
            $aRow['feed_time_stamp'] = $aRow['time_stamp'];
            if (($aReturn = $this->_processFeed($aRow, $sKey, $iUserid, $bFirstCheckOnComments)))
            {
                if (isset($aReturn['force_user']))
                {
                    $aReturn['user_name'] = $aReturn['force_user']['user_name'];
                    $aReturn['full_name'] = $aReturn['force_user']['full_name'];
                    $aReturn['user_image'] = $aReturn['force_user']['user_image'];
                    $aReturn['server_id'] = $aReturn['force_user']['server_id'];
                }

                $aReturn['feed_month_year'] = date('m_Y', $aRow['feed_time_stamp']);
                $aReturn['feed_time_stamp'] = $aRow['feed_time_stamp'];
                $aFeeds[] = $aReturn;
            }
        }

        //TIMELINE FIX
        if (Phpfox::getService('socialbridge')->timeline())
        {

            $iSubCnt = 0;
            foreach ($aFeeds as $iKey => $aFeed)
            {
                if (is_int($iKey / 2))
                {
                    $this->_aFeedTimeline['left'][] = $aFeed;
                }
                else
                {
                    $this->_aFeedTimeline['right'][] = $aFeed;
                }

                $iSubCnt++;
                if ($iSubCnt === 1)
                {
                    $sMonth = date('m', $aFeed['feed_time_stamp']);
                    $sYear = date('Y', $aFeed['feed_time_stamp']);
                    if ($sMonth == date('m', PHPFOX_TIME) && $sYear == date('Y', PHPFOX_TIME))
                    {
                        $this->_sLastDayInfo = '';
                    }
                    elseif ($sYear == date('Y', PHPFOX_TIME))
                    {
                        $this->_sLastDayInfo = Phpfox::getTime('F', $aFeed['feed_time_stamp'], false);
                    }
                    else
                    {
                        $this->_sLastDayInfo = Phpfox::getTime('F Y', $aFeed['feed_time_stamp'], false);
                    }
                }
            }
        }

        return $aFeeds;
    }

    //TIMELINE FIX
    public function getTimeline()
    {
        return $this->_aFeedTimeline;
    }

    //TIMELINE FIX
    public function getLastDay()
    {
        return $this->_sLastDayInfo;
    }

    //TIMELINE FIX
    public function getTimeLineYears($iUserId, $iLastTimeStamp)
    {
        $aNewYears = array();
        $sCacheId = $this->cache()->set(array('timeline', $iUserId));
        if (!($aNewYears = $this->cache()->get($sCacheId)))
        {
            $aYears = range(date('Y', PHPFOX_TIME), date('Y', $iLastTimeStamp));
            foreach ($aYears as $iYear)
            {
                $iStartYear = mktime(0, 0, 0, 1, 1, $iYear);
                $iEndYear = mktime(0, 0, 0, 12, 31, $iYear);

                $iCnt = $this->database()->select('COUNT(*)')
                        ->from(Phpfox::getT('feed'))
                        ->where('user_id = ' . (int) $iUserId . ' AND feed_reference = 0 AND time_stamp > \'' . $iStartYear . '\' AND time_stamp <= \'' . $iEndYear . '\'')
                        ->execute('getSlaveField');

                if ($iCnt)
                {
                    $aNewYears[] = $iYear;
                }
            }

            $this->cache()->save($sCacheId, $aNewYears);
        }

        if (!is_array($aNewYears))
        {
            $aNewYears = array();
        }

        $iBirthYear = date('Y', $iLastTimeStamp);
        $iDOB = $this->database()->select('dob_setting')->from(Phpfox::getT('user_field'))->execute('getSlaveField');

        if (!in_array($iBirthYear, $aNewYears) && ($iDOB == 2 || $iDOB == 4))
        {
            $aNewYears[] = $iBirthYear;
        }

        $aYears = array();
        foreach ($aNewYears as $iYear)
        {
            $aMonths = array();
            foreach (range(1, 12) as $iMonth)
            {
                if ($iYear == date('Y', PHPFOX_TIME) && $iMonth > date('n', PHPFOX_TIME))
                {

                }
                elseif ($iYear == date('Y', $iLastTimeStamp) && $iMonth > date('n', $iLastTimeStamp))
                {

                }
                else
                {
                    $aMonths[] = array(
                        'id' => $iMonth,
                        'phrase' => Phpfox::getTime('F', mktime(0, 0, 0, $iMonth, 1, $iYear), false)
                    );
                }
            }

            $aMonths = array_reverse($aMonths);

            $aYears[] = array(
                'year' => $iYear,
                'months' => $aMonths
            );
        }

        return $aYears;
    }

    public function __call($sMethod, $aArguments)
    {
        if ($sPlugin = Phpfox_Plugin::get('feed.service_feed__call'))
        {
            return eval($sPlugin);
        }

        Phpfox_Error::trigger('Call to undefined method ' . __CLASS__ . '::' . $sMethod . '()', E_USER_ERROR);
    }

    private function _processFeed($aRow, $sKey, $iUserid, $bFirstCheckOnComments)
    {
        if (!Phpfox::hasCallback($aRow['type_id'], 'getActivityFeed'))
        {
            return false;
        }

        $aFeed = Phpfox::callback($aRow['type_id'] . '.getActivityFeed', $aRow, (isset($this->_aCallback['module']) ? $this->_aCallback : null));

        if ($aFeed === false)
        {
            return false;
        }

        if (isset($this->_aViewMoreFeeds[$sKey]))
        {
            foreach ($this->_aViewMoreFeeds[$sKey] as $iSubKey => $aSubRow)
            {
                $aFeed['more_feed_rows'][] = $this->_processFeed($aSubRow, $iSubKey, $iUserid, $bFirstCheckOnComments);
            }
        }

        if (Phpfox::isModule('like') && isset($aFeed['like_type_id']) && (int) $aFeed['feed_total_like'] > 0)
        {
            $aFeed['likes'] = Phpfox::getService('like')->getLikesForFeed($aFeed['like_type_id'], (isset($aFeed['like_item_id']) ? $aFeed['like_item_id'] : $aRow['item_id']), ((int) $aFeed['feed_is_liked'] > 0 ? true : false), Phpfox::getParam('feed.total_likes_to_display'));
        }

        if (isset($aFeed['comment_type_id']) && (int) $aFeed['total_comment'] > 0)
        {
            $aFeed['comments'] = Phpfox::getService('comment')->getCommentsForFeed($aFeed['comment_type_id'], $aRow['item_id'], Phpfox::getParam('comment.total_comments_in_activity_feed'));
        }

        if (isset($aRow['app_title']) && $aRow['app_id'])
        {
            $sLink = '<a href="' . Phpfox::permalink('apps', $aRow['app_id'], $aRow['app_title']) . '">' . $aRow['app_title'] . '</a>';
            $aFeed['app_link'] = $sLink;
        }

        // Check if user can post comments on this feed/item
        $bCanPostComment = false;
        if ($bFirstCheckOnComments)
        {
            $bCanPostComment = true;
        }
        if ($iUserid !== null && $iUserid != Phpfox::getUserId())
        {
            switch ($aRow['privacy_comment'])
            {
                case '1':
                    if (!Phpfox::getService('friend')->isFriend(Phpfox::getUserId(), $iUserid))
                    {
                        $bCanPostComment = false;
                    }
                    break;
                case '2':
                    if (!Phpfox::getService('friend')->isFriend(Phpfox::getUserId(), $iUserid) && !Phpfox::getService('friend')->isFriendOfFriend($iUserid))
                    {
                        $bCanPostComment = false;
                    }
                    break;
                case '3':
                    $bCanPostComment = false;
                    break;
            }
        }

        if ($iUserid === null)
        {
            if ($aRow['user_id'] != Phpfox::getUserId())
            {
                switch ($aRow['privacy_comment'])
                {
                    case '1':
                    case '2':
                        if (!$aRow['is_friend'])
                        {
                            $bCanPostComment = false;
                        }
                        break;
                    case '3':
                        $bCanPostComment = false;
                        break;
                }
            }
        }

        $aRow['can_post_comment'] = $bCanPostComment;

        return array_merge($aRow, $aFeed);
    }

}

