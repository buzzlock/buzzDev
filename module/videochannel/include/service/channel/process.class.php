<?php

class Videochannel_Service_Channel_Process extends Phpfox_Service
{

    private $_aCategories = array();
    private $_sOutput = '';
    private $_iCategoryCnt = 0;

    /**
     *
     * @var Videochannel_Service_Channel_Grab 
     */
    public $oSerVideoChannelChannelGrab;
    
    /**
     *
     * @var Videochannel_Service_Channel_Feedprocess 
     */
    public $oSerVideoChannelChannelFeedProcess;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->_sTable = Phpfox::getT('channel_video');

        $this->oSerVideoChannelChannelGrab = Phpfox::getService('videochannel.channel.grab');
    }

    public function removeRemoved($sUrl)
    {
        $youtubeId = $this->getYouTubeId($sUrl);
        $this->database()->delete(Phpfox::getT('channel_video_remove'), 'LOCATE("' . $youtubeId . '", video_url)');
    }

    public function isVideoRemoved($sUrl, $iChannelId = -1)
    {
        $youtubeId = $this->getYouTubeId($sUrl);

        $removeVideos = $this->database()->select('*')
                ->from(Phpfox::getT('channel_video_remove'), 'v')
                ->where('LOCATE("' . $youtubeId . '", v.video_url)')
                ->execute('getRows');

        if (count($removeVideos))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function channelsCount($iUserId)
    {
        $iCount = $this->database()->select('count(*)')
                ->from(Phpfox::getT('channel_channel'), 'ch')
                ->where('user_id = ' . $iUserId)
                ->execute('getField');
        return $iCount;
    }

    public function updateChannels($channelId = 0, $cronjob = false)
    {
        $channels = array();
        
        if ($cronjob)
        {  
            // // do the cron job to update channels by grabbing newest videos
            //get 5 added channels for 1 job
            $channels = $this->database()->select('ch.*, cd.category_id as category_id')
                    ->from(Phpfox::getT('channel_channel'), 'ch')
                    ->leftJoin(Phpfox::getT('channel_category_data'), 'cd', 'ch.channel_id = cd.channel_id')
                    ->limit(5)
                    ->order('ch.time_stamp ASC')
                    ->execute('getRows');
            
            $videoslimit = $this->getVideosLimit();
            if (empty($videoslimit))
                $videoslimit = 6;
        }
        else if ($channelId > 0)
        {
            $channels = $this->database()->select('ch.*, cd.category_id as category_id')
                    ->from(Phpfox::getT('channel_channel'), 'ch')
                    ->leftJoin(Phpfox::getT('channel_category_data'), 'cd', 'ch.channel_id = cd.channel_id')
                    ->where('ch.channel_id =' . $channelId)
                    ->limit(1)
                    ->execute('getRows');
            
            $videoslimit = Phpfox::getUserParam('videochannel.channel_get_videos_limit') ? Phpfox::getUserParam('videochannel.channel_get_videos_limit') : 0;
        }
        else
        {
            $videoslimit = Phpfox::getUserParam('videochannel.channel_get_videos_limit') ? Phpfox::getUserParam('videochannel.channel_get_videos_limit') : 0;
        }
        
        // If channel is available.
        if (count($channels))
        {
            $iTotalAdded = 0;
            
            //add newest videos grabbed from Youtube for these channels
            foreach ($channels as $channel)
            {
                $aAdds = array();
                
                $aVideos = $this->getVideos($channel['url'], $videoslimit, false, true, $channel);
                
                if (count($aVideos))
                {
                    foreach ($aVideos as $iKey => $aVideo)
                    {
                        $aVideos[$iKey]['channel_id'] = $channel['channel_id'];
                        $aVideos[$iKey]['privacy'] = $channel['privacy'];
                        $aVideos[$iKey]['privacy_comment'] = $channel['privacy_comment'];
                        $aVideos[$iKey]['user_id'] = $channel['user_id'];
                        
                        // this is a work around to get all category of a channel
                        $aChannelCategory = $this->database()->select('category_id')
                                ->from(Phpfox::getT('channel_category_data'))
                                ->where('channel_id =' . $channel['channel_id'])
                                ->execute('getRows');
                        
                        $aVideos[$iKey]['category'] = array();

                        foreach ($aChannelCategory as $aCategory)
                        {
                            $aVideos[$iKey]['category'][] = $aCategory['category_id'];
                        }
                        
                        $aVideos[$iKey]['callback_module'] = $channel['module_id'];
                        $aVideos[$iKey]['callback_item_id'] = $channel['item_id'];
                        
                        $aAdds[] = $aVideos[$iKey];
                    }
                }
                
                // Return an array with elements in reverse order.
                $aAdds = array_reverse($aAdds);
                
                // Add videos.
                $iTotalAdded += (int) $this->addVideos($aAdds);
                
                $this->database()->update(Phpfox::getT('channel_channel'), array('time_stamp' => PHPFOX_TIME), 'channel_id = ' . $channel['channel_id']);
            }
            
            return $iTotalAdded;
        }
        else
        {
            return false;
        }
        return true;
    }
    
    public function addVideos($aVideos)
    {
        $iCount = 0;
        if (count($aVideos))
        {
            foreach ($aVideos as $aVideo)
            {
                if ($this->isVideoRemoved($aVideo['url']))
                {
                    $this->removeRemoved($aVideo['url']);
                }
                
                if ($this->addShareVideo($aVideo))
                    $iCount++;
            }
        }
        return $iCount;
    }

    public function isVideoExist($sUrl)
    {
        $youtubeId = $this->getYouTubeId($sUrl);
        $existVideos = $this->database()->select('*')
                ->from(Phpfox::getT('channel_video_embed'), 'v')
                ->where('LOCATE("' . $youtubeId . '", v.video_url)')
                ->execute('getRows');
        return count($existVideos);
    }

    public function addShareVideo($aVals, $bReturnId = false)
    {
        //check for existing videos
        $youtubeId = $this->getYouTubeId($aVals['url']);

        $existVideos = $this->database()->select('*')
                ->from(Phpfox::getT('channel_video_embed'), 'v')
                ->where('LOCATE("' . $youtubeId . '", v.video_url)')
                ->execute('getRows');

        // Don't add it.
        if (count($existVideos))
        {
            return false;
        };

        // parse this video to get all info
        if ($this->oSerVideoChannelChannelGrab->get($aVals['url']))
        {
            $this->oSerVideoChannelChannelGrab->parse();
        }
        else
        {
            //  Error here
            return Phpfox_Error::set(Phpfox::getPhrase('videochannel.provide_a_category_channels_will_belong_to'));
        }

        if (!($sEmbed = $this->oSerVideoChannelChannelGrab->embed()))
        {
            return Phpfox_Error::set(Phpfox::getPhrase('videochannel.unable_to_embed_this_video_due_to_privacy_settings'));
        }

        if ($sPlugin = Phpfox_Plugin::get('videochannel.service_channel_process_addsharevideo__start'))
        {
            eval($sPlugin);
        }

        $sModule = 'videochannel';
        $iItem = 0;
        
        $aCallback = null;
        
        // Get callback.
        if (isset($aVals['callback_module']) && Phpfox::hasCallback($aVals['callback_module'], 'uploadVideo'))
        {
            $aCallback = Phpfox::callback($aVals['callback_module'] . '.uploadVideo', $aVals);
            $sModule = $aVals['callback_module'];
            $iItem = $aVals['callback_item_id'];
        }
        
        if (defined('PHPFOX_GROUP_VIEW'))
        {
            $aVals['module'] = 'group';
            $sModule = 'group';
        }

        // Get the publish time on Youtube.
        $sTimeStamp = $this->oSerVideoChannelChannelGrab->getTimeStamp();

        $sTime = (isset($aVals['time_stamp']) && $aVals['time_stamp']) ? $aVals['time_stamp'] : ($sTimeStamp ? $sTimeStamp : PHPFOX_TIME);

        $aSql = array(
            'is_stream' => 1,
            'view_id' => (($sModule == 'videochannel' && Phpfox::getUserParam('videochannel.approve_video_before_display')) ? 2 : 0),
            'module_id' => $sModule,
            'item_id' => (int) $iItem,
            'privacy' => $aVals['privacy'],
            'privacy_comment' => $aVals['privacy_comment'],
            'user_id' => $aVals['user_id'],
            'total_view' => 1,
            'time_stamp' => $sTime
        );

        if ($sTitle = $this->oSerVideoChannelChannelGrab->title())
        {
            $bAddedTitle = true;
            $aSql['title'] = $this->preParse()->clean($sTitle, 255);
        }

        if ($sDuration = $this->oSerVideoChannelChannelGrab->duration())
        {
            $aSql['duration'] = $sDuration;
        }

        // Inser video to table.
        $iId = $this->database()->insert($this->_sTable, $aSql);

        // Insert false, just return.
        if (!$iId)
        {
            return false;
        }

        //add to table "phpfox_channel_channel_data"
        $this->database()->insert(Phpfox::getT('channel_channel_data'), array('video_id' => $iId, 'channel_id' => $aVals['channel_id']));

        //add to table "phpfox_channel_category_data"
        if (isset($aVals['category']))
        {
            if (is_array($aVals['category']))
            {
                $iCategories = array();
                foreach ($aVals['category'] as $iCategory)
                {
                    if (empty($iCategory))
                    {
                        continue;
                    }

                    if (!is_numeric($iCategory))
                    {
                        continue;
                    }
                    
                    $iCategories[] = $iCategory;
                }

                if (count($iCategories))
                {
                    foreach ($iCategories as $iCat)
                    {
                        $this->database()->insert(Phpfox::getT('channel_category_data'), array('video_id' => $iId, 'category_id' => (int) $iCat));
                    }
                }
            }
            else
            {
                $this->database()->insert(Phpfox::getT('channel_category_data'), array('video_id' => $iId, 'category_id' => (int) $aVals['category']));
            }
        }

        // Update image for video.
        $aUpdate = array();

        if ($this->oSerVideoChannelChannelGrab->image($iId, $sModule))
        {
            $sImageLocation = Phpfox::getLib('file')->getBuiltDir(Phpfox::getParam('video.dir_image')) . md5($iId . 'videochannel') . '%s.jpg';

            $aUpdate['image_path'] = str_replace(Phpfox::getParam('video.dir_image'), '', $sImageLocation);
            $aUpdate['image_server_id'] = Phpfox::getLib('request')->getServer('PHPFOX_SERVER_ID');
        }
        
        // Don't have title, get it as title.
        if (!isset($bAddedTitle))
        {
            $aUpdate['title'] = $iId;

            $sTitle = $iId;
        }

        // If update data is available, update it.
        if (count($aUpdate))
        {
            $this->database()->update($this->_sTable, $aUpdate, 'video_id = ' . $iId);
        }
        
        // Insert to video channel embed.
        $this->database()->insert(Phpfox::getT('channel_video_embed'), array(
            'video_id' => $iId,
            'video_url' => $aVals['url'],
            'embed_code' => $sEmbed
                )
        );

        // Update description.
        if (($sDescription = $this->oSerVideoChannelChannelGrab->description()))
        {
            $this->database()->insert(Phpfox::getT('channel_video_text'), array(
                'video_id' => $iId,
                'text' => $this->preParse()->clean($sDescription),
                'text_parsed' => $this->preParse()->prepare($sDescription)
                    )
            );
        }
        
        // Check has image or not.
        if (!$this->oSerVideoChannelChannelGrab->hasImage())
        {
            $bReturnId = true;
        }

        // Is update points.
        $bUpdatePoints = ($sModule == 'videochannel' ? (Phpfox::getUserParam('videochannel.approve_video_before_display') ? false : true) : true);

        // Check convert video.
        $aCallback = null;
        if ($sModule != 'videochannel' && Phpfox::hasCallback($sModule, 'convertVideo'))
        {
            $aCallback = Phpfox::callback($sModule . '.convertVideo', array('item_id' => $iId));
        }
        
        // If update points, add to feed.
        if ($bUpdatePoints === true)
        {
            // Check feed is available or not.
            if (Phpfox::isModule('feed') && !defined('PHPFOX_SKIP_FEED_ENTRY') && Phpfox::getUserParam('videochannel.approve_video_before_display') == false)
            {
                // Get feed service.
                $this->oSerVideoChannelChannelFeedProcess = Phpfox::getService('videochannel.channel.feedprocess');
                
                // Set privacy comment.
                $iPrivacyComment = (isset($aVals['privacy_comment']) ? (int) $aVals['privacy_comment'] : 0);
                
                // Set parent user id.
                $iParentUserId = ($aCallback === null ? 0 : $aVals['callback_item_id']);
                
                // Update feed.
                $this->oSerVideoChannelChannelFeedProcess->callback($aCallback)->add('videochannel', $iId, $aVals['privacy'], $iPrivacyComment, $iParentUserId);
            }
            
            // Update user activity for video channel module.
            Phpfox::getService('user.activity')->update(Phpfox::getUserId(), 'videochannel');
        }

        // Plugin call
        if ($sPlugin = Phpfox_Plugin::get('videochannel.service_channel_process_addsharevideo__end'))
        {
            eval($sPlugin);
        }

        return $iId; // ($bReturnId ? $iId : (isset($aSql['title_url']) ? $aSql['title_url'] : $iId));
    }

    public function isExist($channelUrl, $sModule = null, $iItem = null)
    {
        $sQuery = (($sModule == null) ? " AND v.module_id = 'videochannel'" : (" AND v.module_id = '" . $sModule . "' AND v.item_id = " . $iItem));
        //check for existing channels
        $existChannel = $this->database()->select('channel_id')
                ->from(Phpfox::getT('channel_channel'), 'v')
                ->where('LOCATE("' . $channelUrl . '", v.url) AND user_id = ' . Phpfox::getUserId() . $sQuery)
                ->execute('getField');

        if ($existChannel)
        {
            return $existChannel;
        }
        else
        {
            return 0;
        }
    }

    public function addChannel($aChannel)
    {
        $iId = 0;
        if (count($aChannel))
        {
            //check categories this channels belongs
            if (!isset($aChannel['category']))
            {
                return Phpfox_Error::set(Phpfox::getPhrase('videochannel.provide_a_category_channels_will_belong_to'));
            }

            foreach ($aChannel['category'] as $iCategory)
            {
                if (empty($iCategory))
                {
                    continue;
                }

                if (!is_numeric($iCategory))
                {
                    continue;
                }
                $iCategories[] = $iCategory;
            }
            if (!count($iCategory))
            {
                return Phpfox_Error::set(Phpfox::getPhrase('videochannel.provide_a_category_channels_will_belong_to'));
            }
            $iVal['url'] = $aChannel['url'];
            $iVal['title'] = $aChannel['title'];
            $iVal['user_id'] = $aChannel['user_id'];
            $iVal['site_id'] = $aChannel['site_id'];
            $iVal['summary'] = $aChannel['summary'];
            $iVal['privacy'] = $aChannel['privacy'];
            $iVal['privacy_comment'] = $aChannel['privacy_comment'];
            $iVal['module_id'] = $aChannel['callback_module'];
            $iVal['item_id'] = $aChannel['callback_item_id'];
            $iVal['time_stamp'] = PHPFOX_TIME;

            $iId = $this->database()->insert(Phpfox::getT('channel_channel'), $iVal);
            if ($iId)
            {
                $aChannel['existing'] = false;
                $aChannel['channel_id'] = $iId;
                foreach ($iCategories as $iCategory)
                {
                    $this->database()->insert(Phpfox::getT('channel_category_data'), array('channel_id' => $iId, 'category_id' => $iCategory));
                }
            }
            else
            {
                return false;
            }
        }
        return $aChannel;
    }

    public function editChannel($aVal)
    {
        if (isset($aVal))
        {   
            // Remove empty category.
            if (isset($aVal['category']))
            {
                foreach ($aVal['category'] as $iKey => $iCategoryId)
                {
                    if (intval($iCategoryId) == 0)
                    {
                        unset($aVal['category'][$iKey]);
                    }
                }
            }
            
            // Validate.
            if (count($aVal['category']) == 0)
            {
                return Phpfox_Error::set(Phpfox::getPhrase('videochannel.provide_a_category_channels_will_belong_to'));
            }
            
            // Edit channel.
            if ($aVal['channel_id'])
            {
                //delete old category_id values of this channel
                $this->database()->delete(Phpfox::getT('channel_category_data'), 'channel_id = ' . $aVal['channel_id']);
                
                //insert the new values for this channel
                if ($aVal['title'] != "")
                {
                    $this->database()->update(Phpfox::getT('channel_channel'), array('privacy_comment' => $aVal['privacy_comment'], 'privacy' => $aVal['privacy'], 'title' => $aVal['title'], 'summary' => $aVal['summary'], 'time_stamp' => PHPFOX_TIME), 'channel_id = ' . $aVal['channel_id']);

                    // Get all videos of channel.
                    $aVideosId = $this->getAllVideoId($aVal['channel_id']);
                    
                    // Update privacy comment for videos.
                    if (count($aVideosId) > 0)
                    {
                        foreach ($aVideosId as $vId)
                        {
                            $this->database()->update(Phpfox::getT('channel_video'), array('privacy_comment' => $aVal['privacy_comment'], 'privacy' => $aVal['privacy']), 'video_id = ' . $vId);
                        }
                    }
                }
                
                // Insert new categories.
                foreach ($aVal['category'] as $iKey => $iCategoryId)
                {
                    $this->database()->insert(Phpfox::getT('channel_category_data'), array('channel_id' => $aVal['channel_id'], 'category_id' => $iCategoryId));
                }

                //Get all videos belong to this channel after insert new categories.
                $aVideoIds = $this->getAllVideoId($aVal['channel_id']);

                //delete all video belong to category
                foreach ($aVideoIds as $iVideoId)
                {
                    $this->database()->delete(Phpfox::getT('channel_category_data'), 'video_id = ' . $iVideoId);

                    //Insert new video id value
                    foreach ($aVal['category'] as $aKey => $category)
                    {
                        if ($category != "")
                        {
                            $this->database()->insert(Phpfox::getT('channel_category_data'), array('video_id' => $iVideoId, 'category_id' => $category));
                        }
                    }
                }
            }
            return true;
        }
        
        return false;
    }

    public function deleteChannel($channelId, $bIsAdmin = false)
    {
        if ($channelId)
        {
            $user = $this->database()->select('user_id')
                    ->from(Phpfox::getT('channel_channel'))
                    ->where('channel_id =' . $channelId)
                    ->execute('getRow');

            if ($bIsAdmin == false)
            {
                // $isEditOtherChannels = Phpfox::getParam('videochannel.users_edit_all_channels');
                if ($user['user_id'] != Phpfox::getUserId())
                {
                    return false;
                }
            }

            $videos = $this->database()->select('video_id')
                    ->from(Phpfox::getT('channel_channel_data'))
                    ->where('channel_id =' . $channelId)
                    ->execute('getRows');

            if (count($videos))
            {
                //delete all videos of this channel            
                foreach ($videos as $video)
                {
                    $temp = null;
                    Phpfox::getService('videochannel.process')->delete($video['video_id'], $temp, false);
                }
            }

            //delete all info of this channels
            $this->database()->delete(Phpfox::getT('channel_channel'), 'channel_id = ' . $channelId);
            $this->database()->delete(Phpfox::getT('channel_category_data'), 'channel_id = ' . $channelId);
            $this->database()->delete(Phpfox::getT('channel_channel_data'), 'channel_id = ' . $channelId);
            $this->database()->delete(Phpfox::getT('channel_video_remove'), 'channel_id = ' . $channelId);
            return true;
        }
        else
        {
            return false;
        }
    }

    public function deleteAllChannels()
    {
        $allChannels = $this->database()->select('channel_id')
                ->from(Phpfox::getT('channel_channel'))
                ->execute('getRows');
        if (count($allChannels))
        {
            foreach ($allChannels as $channel)
            {
                $this->deleteChannel($channel['channel_id']);
            }
            return true;
        }
        else
        {
            return false;
        }
    }

    public function getYouTubeId($iUrl)
    {
        $aUrl = parse_url($iUrl);

        if (!isset($aUrl['query']) && isset($aUrl['path']))
        {
            $aFix = explode('/', $aUrl['path']);
            if (isset($aFix[2]))
                $aUrl['query'] = 'v=' . $aFix[2];
            else
                $aUrl['query'] = "";
        }
        parse_str($aUrl['query'], $aStr);
        return isset($aStr['v']) ? $aStr['v'] : "";
    }

    public function existChannels()
    {
        $total = $this->database()->select('count(*)')
                ->from(Phpfox::getT('channel_channel'), 'ch')
                ->where('ch.user_id =' . Phpfox::getUserId())
                ->execute('getRows');
        return $total[0]['count(*)'];
    }

    public function getChannel($channelId)
    {
        $channel = $this->database()->select('channel.*')
                ->from(Phpfox::getT('channel_channel'), 'channel')
                ->where('channel.channel_id =' . (int) $channelId)
                ->execute('getRow');
        
        if ($channel)
        {
            $videos = $this->database()->select('video.*')
                    ->from(Phpfox::getT('channel_channel_data'), 'channel')
                    ->leftJoin(Phpfox::getT('channel_video'), 'video', 'channel.video_id=video.video_id')
                    ->where('channel.channel_id = ' . (int) $channelId)
                    ->order('video.video_id DESC')
                    ->execute('getRows');
            
            $user_name = Phpfox::getUserBy('user_name');
            for ($i = 0; $i < count($videos); $i++)
            {
                $videos[$i]['user_name'] = $user_name;
                $videos[$i]['full_name'] = $user_name;
                $sTitleUrl = Phpfox::getLib('parse.input')->clean($videos[$i]['title']);
                $videos[$i]['link'] = Phpfox::getLib('url')->makeUrl($user_name, $sTitleUrl);
            }
            
            $channel['videos'] = $videos;
            $channel['videoCnt'] = count($videos);
            
            return $channel;
        }
        else
        {
            return false;
        }
    }

    public function getVideosBelongChannel($channelId, $videoNum = 0)
    {
        $videos = $this->database()->select('video.video_id,video.image_path,video.image_server_id')
                ->from(Phpfox::getT('channel_channel_data'), 'channel')
                ->leftJoin(Phpfox::getT('channel_video'), 'video', 'channel.video_id=video.video_id')
                ->where('channel.channel_id =' . $channelId)
                ->order('video.video_id DESC')
                ->execute('getRows');
        if ($videoNum != 0)
        {
            $Cnt = 0;
            $rVideos = array();
            foreach ($videos as $video)
            {
                if ($Cnt < $videoNum)
                {
                    $rVideos[] = $video;
                    $Cnt++;
                }
            }
            return $rVideos;
        }
        else
            return $videos;
    }

    public function getMostViewed($channelId, $videoNum = 1)
    {
        $videos = $this->database()->select('user.user_name,user.full_name,video.title, video.video_id,video.image_path,video.image_server_id,video.total_view')
                ->from(Phpfox::getT('channel_channel_data'), 'channel')
                ->leftJoin(Phpfox::getT('channel_video'), 'video', 'channel.video_id=video.video_id')
                ->leftJoin(Phpfox::getT('user'), 'user', 'user.user_id=video.user_id')
                ->where('channel.channel_id =' . $channelId)
                ->order('video.total_view DESC')
                ->execute('getRows');
        $Cnt = 0;
        $rVideos = array();
        foreach ($videos as $video)
        {
            $video['link'] = Phpfox::permalink('videochannel', $video['video_id'], $video['title']);

            if ($Cnt < $videoNum)
            {
                $rVideos[] = $video;
                $Cnt++;
            }
        }
        return $rVideos;
    }

    public function genCategory()
    {
        $this->_sOutput = "";
        $this->_get(0, 1);
        return $this->_sOutput;
    }
    
    /**
     * Get all categories in level. Because we have 2 selectboxes so it's difficult to display the default value.
     * @param integer $iDefaultCategoryId Select the default category.
     * @param integer $iActive Select the active categories.
     * @return array
     */
    public function getCategoriesInLevel($iDefaultCategoryId = 0, $iActive = 1)
    {
        $aParentCategories = $this->getCategories(0, $iActive);
        
        if (count($aParentCategories) > 0)
        {
            foreach ($aParentCategories as $iKey => $aCategory)
            {
                // Select the default category in parent.
                if ($aCategory['category_id'] == $iDefaultCategoryId)
                {
                    $aParentCategories[$iKey]['bIsDefault'] = true;
                }
                else
                {
                    $aParentCategories[$iKey]['bIsDefault'] = false;
                }
                
                // Get the sub categories.
                $aChildCategories = array();
                
                $aChildCategories = $this->getCategories($aCategory['category_id'], $iActive);
                
                foreach($aChildCategories as $iSubKey => $aSubCategory)
                {
                    // Check the default value for sub categories.
                    if ($aSubCategory['category_id'] == $iDefaultCategoryId)
                    {
                        // Set the child to default value.
                        $aChildCategories[$iSubKey]['bIsDefault'] = true;
                        
                        // Set the parent to default value. Be careful.
                        $aParentCategories[$iKey]['bIsDefault'] = true;
                    }
                    else
                    {
                        $aChildCategories[$iSubKey]['bIsDefault'] = false;
                    }
                }
                
                $aParentCategories[$iKey]['children'] = $aChildCategories;
                
            }
        }
        
        return $aParentCategories;
    }
    
    public function getCategoriesInHtml($iDefaultCategoryId = 0, $iActive = 1)
    {
        $aCategories = $this->getCategoriesInLevel($iDefaultCategoryId, $iActive);
        
        $sHtml = '';
        
        // Get HTML for parent.
        $sHtml = $this->getCategoryElementInHtml($aCategories, 0, true);
        
        // Get HTML for children.
        foreach($aCategories as $aCategory)
        {
            if (count($aCategory['children']) > 0)
            {
                $bDisplay = false;
                
                if ($aCategory['bIsDefault'])
                {
                    $bDisplay = true;
                }
                
                $sHtml .= $this->getCategoryElementInHtml($aCategory['children'], $aCategory['category_id'], $bDisplay);
            }
        }
        
        return $sHtml;
    }
    
    public function getCategoryElementInHtml($aCategories = array(), $iParentId = 0, $bDisplay = false)
    {
        $sDisplay = ($iParentId == 0) ? '' : ($bDisplay ? '' : ' style="display: none;" ');
        $sSelectClassId = ' js_mp_id_' . $iParentId;
        $sSelectClassParent = ' js_mp' . ($iParentId == 0 ? '_parent' : '') . '_category_list';
        
        $sHtml = '';
        
        $sHtml .= '<div class="js_mp_parent_holder js_mp_holder_' . $iParentId . '">';
        $sHtml .= '<select name="val[category][]" class="' . $sSelectClassParent . $sSelectClassId . '" ' . $sDisplay . ' >' . "\n";
        $sHtml .= '<option value="">' . ($iParentId === 0 ? Phpfox::getPhrase('videochannel.select') : Phpfox::getPhrase('videochannel.select_a_sub_category')) . ':</option>' . "\n";

        foreach ($aCategories as $iKey => $aCategory)
        {
            $sSelected = $aCategory['bIsDefault'] ? 'selected="selected"' : '';
            
            $sHtml .= '<option value="' . $aCategory['category_id'] . '" class="js_mp_category_item_' . $aCategory['category_id'] . '" ' . $sSelected . ' >' . Phpfox::getLib('locale')->convert($aCategory['name']) . '</option>' . "\n";
        }

        $sHtml .= '</select>' . "\n";
        $sHtml .= '</div>';

        return $sHtml;
    }
    
    public function _get($iParentId, $iActive = null)
    {
        $aCategories = $this->getCategories($iParentId, $iActive);
        
        if (count($aCategories))
        {
            $aCache = array();

            if ($iParentId != 0)
            {
                $this->_iCategoryCnt++;
            }

            $this->_sOutput .= '<div class="js_mp_parent_holder js_mp_holder_' . $iParentId . '" ' . ($iParentId > 0 ? ' style="display:none; padding:5px 0px 0px 0px;"' : '') . '>';
            $this->_sOutput .= '<select name="val[category][]" class="js_mp_category_list js_mp_id_' . $iParentId . '" >' . "\n";
            $this->_sOutput .= '<option value="">' . ($iParentId === 0 ? Phpfox::getPhrase('videochannel.select') : Phpfox::getPhrase('videochannel.select_a_sub_category')) . ':</option>' . "\n";
            
            foreach ($aCategories as $iKey => $aCategory)
            {
                $aCache[] = $aCategory['category_id'];
                $this->_sOutput .= '<option value="' . $aCategory['category_id'] . '" class="js_mp_category_item_' . $aCategory['category_id'] . '">' . Phpfox::getLib('locale')->convert($aCategory['name']) . '</option>' . "\n";
            }

            $this->_sOutput .= '</select>' . "\n";
            $this->_sOutput .= '</div>';

            foreach ($aCache as $iCateoryId)
            {
                $this->_get($iCateoryId, $iActive);
            }
            $this->_iCategoryCnt = 0;
        }
    }

    public function getCategories($iParentId, $iActive = null)
    {
        return $this->database()->select('*')
                        ->from(Phpfox::getT('channel_category'))
                        ->where('parent_id = ' . (int) $iParentId . ' AND is_active = ' . (int) $iActive . '')
                        ->order('ordering ASC')
                        ->execute('getRows');
    }

    public function getCategory($channelId)
    {
        return $this->database()->select('cd.*, c.parent_id')
                        ->from(Phpfox::getT('channel_category_data'), 'cd')
                        ->leftJoin(Phpfox::getT('channel_category'), 'c', 'c.category_id = cd.category_id')
                        ->where('channel_id =' . $channelId)
                        ->execute('getRows');
    }

    private function _getParentsUrl($iParentId, $bPassName = false)
    {
        // Cache the round we are going to increment
        static $iCnt = 0;

        // Add to the cached round
        $iCnt++;

        // Check if this is the first round
        if ($iCnt === 1)
        {
            // Cache the cache ID
            static $sCacheId = null;

            // Check if we have this data already cached
            $sCacheId = $this->cache()->set('videochannel_category_url' . ($bPassName ? '_name' : '') . '_' . $iParentId);
            if ($sParents = $this->cache()->get($sCacheId))
            {
                return $sParents;
            }
        }

        // Get the menus based on the category ID
        $aParents = $this->database()->select('category_id, name, name_url, parent_id')
                ->from(Phpfox::getT('channel_category'))
                ->where('category_id = ' . (int) $iParentId)
                ->execute('getRows');

        // Loop thur all the sub menus
        $sParents = '';
        foreach ($aParents as $aParent)
        {
            $sParents .= $aParent['name_url'] . ($bPassName ? '|' . $aParent['name'] . '|' . $aParent['category_id'] : '') . '/' . $this->_getParentsUrl($aParent['parent_id'], $bPassName);
        }

        // Save the cached based on the static cache ID
        if (isset($sCacheId))
        {
            $this->cache()->save($sCacheId, $sParents);
        }

        // Return the loop
        return $sParents;
    }

    /*
     * the maximum number of videos got from YouTube is fixed: 50
     * we will count until the number of new videos equal the expected one
     * everytime we encounter a existed video, we will plus total video in this channel of that user, then keep counting
     * the pre hypothesis is: video is retrieved timing chronologically (published time)
     * some channel can contain hundred of thousand of videos, so brute force searching is impractical
     * to automatically offset updated videos, we should allow duplication and missing some videos
     * hightly recommend NOT to use auto update and manually adding channel together
     * @maxNum int default 8 maximum number of videos we will get
     * @aurl string url of the channel we want to update
     */

    public function getVideos($aUrl, $maxNum = 8, $isMaximun = false, $isUpdateChannel = false, $aChannel = array())
    {
        // this variable is total channel we have get, we will keep updating from this
        $iTotalVideosOfChannel = 0;
        if ($aChannel)
        {
            $iTotalVideosOfChannel = $this->getTotalAddedVideosOfChannel($aChannel['channel_id']);
        }
        $videoCnt = 0;
        $outVideos = array();
        // iterate 1000 times gonna be enough
        $iThreshold = 1000;

        $sUrl = $aUrl;
        $c = $maxNum / 50;

        if ($c > (int) $c)
            $c = (int) $c + 1;

        $i = -1;
        $dem = 0;
        $iMaxResultForEachQuery = 40;

        //total videos in this channel on YouTube
        // 1 is enough to be greater than iStart
        $iTotalVideosOfChannelOnYouTube = 1;

        //this is maximum number of all videos we got 
        $iMaxResult = 0;

        // this flag will true if encoutering the first existing videos
        $bIsEncoutnerFirstExist = false;
        $iStart = 0;

        //for($i = 0; $i <= $c && $videoCnt < $maxNum; $i ++)
        //videoCnt is number of video we got
        // maxNum is number of video we want to get
        // at first iStart have no way to greater than or equal $iTotalVideosOfChannelOnYouTube
        while ($videoCnt < $maxNum && $iStart < $iTotalVideosOfChannelOnYouTube)
        {
            // 0,1 ,2....
            $i++;
            if ($i >= $iThreshold)
            {
                break;
            }
            $dem++;
            
            //we gonna make iMaxResult be standard to calculate iStart
            // each time we set iMaxResult, iStart will be changed accordingly
            $iMaxResult = $iMaxResult + $iMaxResultForEachQuery;
            $iStart = $iMaxResult - $iMaxResultForEachQuery + 1;
            
            // we overwrite iMaxResult here to get 50 query result each time
            $sUrl = $aUrl . '&start-index=' . $iStart . '&max-results=' . $iMaxResultForEachQuery;
            if (!strstr($sUrl, '&format=5'))
                $sUrl .= '&format=5';
            $sUrl .= '&orderby=published';

            $xChannel = Phpfox::getLib('request')->send($sUrl, array(), 'GET');
            $dataChannel = Phpfox::getLib('xml.parser')->parse($xChannel, 'UTF-8');

            if (isset($dataChannel['error']))
            {
                break;
            }
            else if (isset($dataChannel['entry']))
            {

                // it is used to count current position of video in entry array
                $iOffsetInChannel = -1;
                if ($iTotalVideosOfChannelOnYouTube == 1)
                {
                    // we assume everything working correctly, this value will be assigned at the first time running
                    $iTotalVideosOfChannelOnYouTube = (int) (isset($dataChannel['openSearch:totalResults']) ? $dataChannel['openSearch:totalResults'] : 0);
                }
                foreach ($dataChannel['entry'] as $aVideo)
                {
                    $iOffsetInChannel++;

                    if (!is_array($aVideo))
                    {
                        continue;
                    }

                    //Check video info
                    if (!isset($aVideo['title']) || !isset($aVideo['link']) || !isset($aVideo['media:group']))
                    {
                        continue;
                    }


                    $outVideo['video_id'] = $videoCnt;
                    $outVideo['title'] = Phpfox::getLib('parse.input')->clean($aVideo['title']);
                    $outVideo['url'] = $aVideo['link'][0]['href'];

                    $youtubeId = $this->getYouTubeId($outVideo['url']);
                    $existVideos = $this->database()->select('*')
                            ->from(Phpfox::getT('channel_video_embed'), 'v')
                            ->where('LOCATE("' . $youtubeId . '", v.video_url)')
                            ->execute('getRows');


                    //check to see whether we added it or not
                    if (count($existVideos) || ($this->isVideoRemoved($outVideo['url']) && $isUpdateChannel))
                    {
                        //until we found the first existing video
                        //because the newest videos are added to head of queue so we must check this
                        if (!$bIsEncoutnerFirstExist)
                        {
                            $bIsEncoutnerFirstExist = true;
                            // we assume videos are added continuously
                            // this is gonna be the the last one in our video list
                            $iMaxResult = $iStart + $iOffsetInChannel + $iTotalVideosOfChannel - 1;

                            //** should notice that this calculation will show the effectiveness with large channel
                            //we will escape here, and make a request again
                            continue;
                        }
                        //$maxNum++;  
                        continue;
                    };


                    if (isset($aVideo['media:group']['yt:duration']['seconds']))
                    {
                        $outVideo['duration'] = floor($aVideo['media:group']['yt:duration']['seconds'] / 60) . ':' . ($aVideo['media:group']['yt:duration']['seconds'] % 60);
                    }
                    else
                    {
                        $outVideo['duration'] = "";
                    }
                    if (isset($aVideo['media:group']['media:description']['value']))
                    {
                        $outVideo['summary'] = base64_encode(Phpfox::getLib('parse.input')->clean($aVideo['media:group']['media:description']['value']));
                    }
                    else
                    {
                        $outVideo['summary'] = '';
                    }
                    $outVideo['title_encode'] = base64_encode($outVideo['title']);
                    $outVideo['time_stamp'] = isset($aVideo['published']) ? strtotime($aVideo['published']) : 0;
                    $outVideo['user_name'] = Phpfox::getUserBy('user_name');
                    $outVideo['full_name'] = Phpfox::getUserBy('full_name');
                    $outVideo['image_path'] = 'http://img.youtube.com/vi/' . $aVideo['media:group']['yt:videoid'] . '/default.jpg';
                    $outVideos[] = $outVideo;
					
                    $videoCnt++;

                    // get enought data, let's get out of this mess
                    if ($videoCnt >= $maxNum)
                    {
                        break;
                    }
                }
            }
        }

        return $outVideos;
    }

    public function getTotalAddedVideosOfChannel($iChannelId)
    {
        $aTotalExistingVideos = $this->database()->select('COUNT(*) as total')
                ->from(Phpfox::getT('channel_channel_data'))
                ->where('channel_id = ' . (int) $iChannelId)
                ->execute('getRow');

        $aTotalRemovedVideos = $this->database()->select('COUNT(*) as total')
                ->from(Phpfox::getT('channel_video_remove'))
                ->where('channel_id = ' . (int) $iChannelId)
                ->execute('getRow');

        $iTotal = $aTotalExistingVideos['total'] + $aTotalRemovedVideos['total'];

        return $iTotal;
    }

    public function getVideosLimit()
    {
        $aRow = $this->database()->select('*')
                ->from(Phpfox::getT('user_group_setting'), 'ug')
                ->leftJoin(Phpfox::getT('user_setting'), 'u', 'ug.setting_id = u.setting_id')
                ->where('ug.module_id = "videochannel" AND ug.product_id = "younet_videochannel" AND ug.name = "channel_get_videos_limit"')
                ->execute('getRow');
        if ($aRow['value_actual'] == NULL)
        {
            return $aRow['default_admin'];
        }
        else
        {
            return $aRow['value_actual'];
        }
    }

    public function getChannelParentModule($iChannelId)
    {
        $aParentModule = $this->database()->select('module_id, item_id')
                ->from(Phpfox::getT('channel_channel'))
                ->where('channel_id = ' . $iChannelId)
                ->execute('getRows');
        return $aParentModule[0];
    }

    public function getAllVideoId($channelId)
    {

        $videos = $this->database()->select('video.video_id')
                ->from(Phpfox::getT('channel_channel_data'), 'channel')
                ->leftJoin(Phpfox::getT('channel_video'), 'video', 'channel.video_id=video.video_id')
                ->where('channel.channel_id =' . $channelId)
                ->order('video.video_id DESC')
                ->execute('getRows');

        $rVideos = array();

        foreach ($videos as $vId)
        {
            $rVideos[] = $vId['video_id'];
        }
        return $rVideos;
    }

    public function getPreviousLink($aLinks)
    {
        foreach ($aLinks as $aLink)
        {
            if ($aLink['rel'] == 'previous')
                return $aLink['href'];
        }
        return false;
    }

    public function getNextLink($aLinks)
    {
        foreach ($aLinks as $aLink)
        {
            if ($aLink['rel'] == 'next')
                return $aLink['href'];
        }
        return false;
    }

    public function getChannelLink($aLinks)
    {
        foreach ($aLinks as $aLink)
        {
            if ($aLink['rel'] == 'alternate')
                return $aLink['href'];
        }
        return '#';
    }

    public function checkNext($sFeedUrl)
    {
        //Phpfox_Error::skip(true);
        $sxml = @simplexml_load_file($sFeedUrl);
        //Phpfox_Error::skip(false);
        if ($sxml)
        {
            if (count($sxml->entry))
                return true;
            return false;
        }
        return false;
    }

    //Get channels list from youtube feed
    public function getChannels($sFeedUrl, &$bIsPrev, &$bIsNext, $sModule = 'videochannel', $iItem = 0)
    {
        Phpfox_Error::skip(true);
        $sxml = @simplexml_load_file($sFeedUrl);
        Phpfox_Error::skip(false);
        
        $aChannels = array(); //Result Array
        $aExist = array();    //Array for exist channels     
        $iCount = 0;
        if ($sxml)
        {
            $bIsPrev = $this->getPreviousLink($sxml->link);
            $bIsNext = $this->getNextLink($sxml->link);
            foreach ($sxml->entry as $entry)
            {
                if ($entry->title != "")
                {
                    $channel = array();
                    $channel['channel_id'] = $iCount;
                    $channel['link'] = $this->getChannelLink($entry->link);
                    $channel['title'] = Phpfox::getLib('parse.input')->clean(strip_tags($entry->title));
                    $gd = $entry->children('http://schemas.google.com/g/2005');
                    $feedLink = $gd->feedLink->attributes();
                    $channel['url'] = $feedLink['href'];
                    //Check if channel is exist			
                    $existId = Phpfox::getService('videochannel.channel.process')->isExist($channel['url'], $sModule, $iItem);
                    if ($existId)
                    {
                        $channel['isExist'] = $existId;
                        $channel['link'] = Phpfox::permalink('videochannel.channel', $existId, $channel['title']);
                    }
                    //$channel['total_videos'] = $feedLink['countHint'];			   				                

                    $channel['summary'] = Phpfox::getLib('parse.input')->clean($entry->summary);

                    //Get first video thumbnail
                    $channel['video_image'] = Phpfox::getParam('core.url_module') . 'videochannel/static/image/no_item.jpg';
                    $sFirstVideo = $channel['url'] . '&start-index=1&max-results=1';
                    if (!strstr($sFirstVideo, '&format=5'))
                        $sFirstVideo .= '&format=5';
                    
                    Phpfox_Error::skip(true);
                    $channelData = @simplexml_load_file($sFirstVideo);
                    Phpfox_Error::skip(false);
                    
                    if ($channelData)
                    {
                        if (isset($channelData->entry[0]->link[0]['href']))
                        {
                            $aUrl = parse_url($channelData->entry[0]->link[0]['href']);
                            
                            if (!isset($aUrl['query']) && isset($aUrl['path']))
                            {
                                $aFix = explode('/', $aUrl['path']);
                                if (isset($aFix[2]))
                                    $aUrl['query'] = 'v=' . $aFix[2];
                            }
                            if (isset($aUrl['query']))
                            {
                                parse_str($aUrl['query'], $aStr);
                                $channel['video_image'] = 'http://img.youtube.com/vi/' . $aStr['v'] . '/default.jpg';
                            }
                        }
                    }
                    
                    $channel['en_title'] = base64_encode($channel['title']);
                    $channel['en_summary'] = base64_encode($channel['summary']);
                    $channel['en_url'] = base64_encode($channel['url']);
                    $channel['en_video_image'] = base64_encode($channel['video_image']);
                    
                    if ($existId)
                        $aExist[] = $channel;
                    else
                        $aChannels[] = $channel;
                    $iCount++;
                }
            }
        }
        $aChannels = array_reverse($aChannels);
        $aChannels = array_merge($aExist, $aChannels);
        return $aChannels;
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
        if ($sPlugin = Phpfox_Plugin::get('videochannel.service_channel_process__call'))
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
