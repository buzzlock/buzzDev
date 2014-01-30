<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class Videochannel_Component_Ajax_Channel_Ajax extends Phpfox_Ajax
{
    /**
     *
     * @var Videochannel_Service_Channel_Process 
     */
    public $oSerVideoChannelChannelProcess;
    
    /**
     *
     * @var Videochannel_Service_Videochannel 
     */
    public $oSerVideoChannel;
    
    /**
     *
     * @var Phpfox_Parse_Input 
     */
    public $oLibParseInput;
    
    /**
     *
     * @var Phpfox_Parse_Output 
     */
    public $oLibParseOutput;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->oSerVideoChannelChannelProcess = Phpfox::getService('videochannel.channel.process');
        $this->oSerVideoChannel = Phpfox::getService('videochannel');
        $this->oLibParseInput = Phpfox::getLib('parse.input');
        $this->oLibParseOutput = Phpfox::getLib('parse.output');
    }
    
    public function addChannelUrl()
    {
        Phpfox::isUser();
        $sModule = 'videochannel';
        $iItem = 0;
        if ($this->get('module'))
        {
            $sModule = $this->get('module');
            $iItem = $this->get('item');
        }
        Phpfox::getService('videochannel')->getCanAddChannel($sModule, $iItem);
        $sUrl = $this->get('url');
		$sUrl = base64_decode($sUrl);
        if (!empty($sUrl))
        {
            if (Phpfox::getService('videochannel.channel.grab')->getGdataUrl($sUrl))
            {
                $pPattern = '/\/([a-zA-Z0-9_]+)(\?|(\/videos)|(\/feeds))/';
                $aMatch = array();
                preg_match($pPattern, $sUrl . '?', $aMatch);
                $sChannelFeedUrl = "http://gdata.youtube.com/feeds/api/users/" . $aMatch[1] . "/uploads?v=2";
                $sChannelInfoFeedUrl = "http://gdata.youtube.com/feeds/api/users/" . $aMatch[1];

                $sModule = ($this->get('module') ? $this->get('module') : 'videochannel');
                $iItem = ($this->get('item') ? $this->get('item') : 0);
                if (Phpfox::getService('videochannel.channel.process')->isExist($sChannelFeedUrl, $sModule, $iItem))
                {

                    return Phpfox_Error::display(Phpfox::getPhrase("videochannel.this_channel_is_already_added"));
                }

                $sXml = @simplexml_load_file($sChannelInfoFeedUrl);
                $sTitle = null;
                $sDescription = null;
                $sImg = null;
                if ($sXml)
                {
                    $sTitle = $sXml->title;
                    $sDescription = $sXml->content;

                    $sFirstVideo = $sChannelFeedUrl . '&start-index=1&max-results=1';
                    if (!strstr($sFirstVideo, '&format=5'))
                        $sFirstVideo .= '&format=5';
                    //Phpfox_Error::skip(true);
                    $channelData = @simplexml_load_file($sFirstVideo);

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
                                $sImg = 'http://img.youtube.com/vi/' . $aStr['v'] . '/default.jpg';
                            }
                        }
                    }
                }
                //$aChannel = phpfox::getService('videochannel.channel.process')->getChannel($sChannelFeedUrl);
                //echo $sChannelFeedUrl . $sXml ;
                Phpfox::getBlock('videochannel.channel.addchannel', array(
                    'site_id' => 'youtube',
                    'title' => base64_encode($sTitle),
                    'url' => base64_encode($sChannelFeedUrl),
                    'description' => base64_encode($sDescription),
                    'img' => base64_encode($sImg),
                    'iChannelId' => 0
                        )
                );
                if (!empty($act))
                {
                    if ($act == 'no')
                        $this->setTitle(Phpfox::getPhrase('videochannel.edit_channel'));
                    else
                        $this->setTitle(Phpfox::getPhrase('videochannel.add_more_videos'));
                }
                else
                {
                    $this->setTitle(Phpfox::getPhrase('videochannel.add_a_channel'));
                }

                $this->call('<script type="text/javascript">$Core.loadInit();</script>');
            }
        }
    }

    public function deleteAllVideos()
    {
        Phpfox::isUser(true);


        $iId = (int) $this->get('id');
        if (empty($iId))
        {
            return false;
        }
        else
        {
            $aVideoId = Phpfox::getService('videochannel.channel.process')->getAllVideoId($iId);

            if (!empty($aVideoId))
            {
                foreach ($aVideoId as $vId)
                {
                    if ((int) Phpfox::getService('videochannel.process')->delete($vId))
                    {
                        $this->fadeOut('#js_video_id_' . $vId);
                    }
                }
                $this->remove('#js_channel_btn_deleteall');
                $this->hide('#img_action');
                $this->show('.btn_submit');
                $this->html('#channel_video_list', Phpfox::getPhrase('videochannel.no_videos_found'));
            }
        }
    }

    public function deleteChannel($id = null)
    {
        Phpfox::isUser(true);
        $sModule = 'videochannel';
        $iItem = 0;
        
        if ($this->get('module'))
        {
            $sModule = $this->get('module');
            $iItem = $this->get('item');
        }
        
        $this->oSerVideoChannel->getCanAddChannel($sModule, $iItem);

        if ($id == null)
            $iId = (int) $this->get('id');
        else
            $iId = $id;

        if (empty($iId))
        {
            return false;
        }

        if ($this->oSerVideoChannelChannelProcess->deleteChannel($iId, true))
        {
            $this->remove('#js_channel_entry_' . $iId)
                    ->call('$(\'#js_pager_to\').html((parseInt($(\'#js_pager_to\').html()) - 1));')
                    ->call('$(\'#js_pager_total\').html((parseInt($(\'#js_pager_total\').html()) - 1));');

            return $this->alert(Phpfox::getPhrase('videochannel.the_channel_successfully_deleted'), 'Moderation', 400, 150, true);
        }
        else
        {
            return $this->alert(Phpfox::getPhrase('videochannel.could_not_delete_this_channel'), 'Moderation', 400, 150);
        }
    }

    public function addChannel()
    {
        Phpfox::isUser(true);
        $sModule = 'videochannel';
        
        $iItem = 0;
        if ($this->get('module'))
        {
            $sModule = $this->get('module');
            $iItem = $this->get('item');
        }
        
        $this->oSerVideoChannel->getCanAddChannel($sModule, $iItem);

        $sTitle = $this->get('title');
        $sUrl = $this->get('url');
        $sDescription = $this->get('des');
        $sImg = $this->get('img');
        $sSiteId = "youtube";
        $iChannelId = (int) $this->get('iChannelId');
        
        $act = $this->get('act');
        
        if (empty($act))
        {
            $iChannelCount = $this->oSerVideoChannelChannelProcess->channelsCount(Phpfox::getUserId());

            if ($iChannelCount == Phpfox::getUserParam('videochannel.channels_limit'))
            {
                $this->setTitle(Phpfox::getPhrase('videochannel.add_a_channel'));
                return Phpfox_Error::display('<div class="error_message">'
                                . Phpfox::getPhrase('videochannel.added_channels_already_reached_the_limit') .
                                '</div>'
                );
            }
        }


        if (empty($sTitle) || empty($sUrl))
        {
            return Phpfox_Error::display(Phpfox::getPhrase('videochannel.invalid_channel_information'));
        }
        
        Phpfox::getBlock('videochannel.channel.addchannel', array(
            'site_id' => $sSiteId,
            'title' => $sTitle,
            'url' => $sUrl,
            'description' => $sDescription,
            'img' => $sImg,
            'iChannelId' => $iChannelId,
            'act' => $act
                )
        );

        if (!empty($act))
        {
            if ($act == 'no')
                $this->setTitle(Phpfox::getPhrase('videochannel.edit_channel'));
            else
                $this->setTitle(Phpfox::getPhrase('videochannel.add_more_videos'));
        }
        else
        {
            $this->setTitle(Phpfox::getPhrase('videochannel.add_a_channel'));
        }

        $this->call('<script type="text/javascript">$Core.loadInit();</script>');
    }

    public function loadVideoList()
    {
        $sUrl = $this->get('url');
        if (empty($sUrl))
        {
            return Phpfox_Error::display(Phpfox::getPhrase('videochannel.invalid_channel_link'));
        }

        Phpfox::getBlock("videochannel.channel.videolist", array('sUrl' => $sUrl));
        
        $this->html("div#channel_video_list", $this->getContent(false));
    }

    public function saveChannel()
    {
        Phpfox::isUser(true);
        
        $sModule = 'videochannel';
        $iItem = 0;
        $sVals = $this->get('val');
        
        if (!empty($sVals))
        {
            if (isset($sVals['callback_module']))
            {
                $sModule = $sVals['callback_module'];
                $iItem = $sVals['callback_item_id'];
            }
        }

        $this->oSerVideoChannel->getCanAddChannel($sModule, $iItem);

        $aChannel = array(); //Channel information        
        $aVideos = array(); //Output video list

        $sVideos = $this->get("video"); //Selected videos
        
        if (!empty($sVals))
        {
            $sChannelTitle = $this->oLibParseInput->clean($sVals['title']);
            
            if (empty($sChannelTitle))
            {
                $this->show('.btn_submit');
                $this->hide('#img_action');
                
                return Phpfox_Error::set(Phpfox::getPhrase('videochannel.enter_channel_title'));
            }
            
            //Check if not provide a category
            if (empty($sVals['category'][0]))
            {
                $this->show('.btn_submit');
                $this->hide('#img_action');
                
                return Phpfox_Error::set(Phpfox::getPhrase('videochannel.provide_a_category_channels_will_belong_to'));
            }
            
            //Set channel info
            $aChannel['site_id'] = $sVals['site_id'];
            $aChannel['url'] = $sVals['url'];
            $aChannel['title'] = $sChannelTitle;
            $aChannel['summary'] = $this->oLibParseInput->clean($sVals['description']);
            $aChannel['user_id'] = Phpfox::getUserId();
            $aChannel['category'] = $sVals['category'];
            $aChannel['privacy'] = (isset($sVals['privacy']) ? $sVals['privacy'] : '0');
            $aChannel['privacy_comment'] = (isset($sVals['privacy_comment']) ? $sVals['privacy_comment'] : '0');
            
            if (isset($sVals['callback_module']) && ($sVals['callback_module'] != ""))
            {
                $aChannel['callback_module'] = $sVals['callback_module'];
                $aChannel['callback_item_id'] = $sVals['callback_item_id'];
            }
            else
            {
                $aChannel['callback_module'] = 'videochannel';
                $aChannel['callback_item_id'] = 0;
            }
            
            //If channel is exits (edit action)
            if (($iChannelId = $this->oSerVideoChannelChannelProcess->isExist($aChannel['url'], $sModule, $iItem)))
            {
                $aChannel['channel_id'] = $iChannelId;
                
                $oEditedCh = $this->oSerVideoChannelChannelProcess->editChannel($aChannel);
                
                $aParentModule = $this->oSerVideoChannelChannelProcess->getChannelParentModule($iChannelId);
                
                if (!empty($sVideos))
                {
                    foreach ($sVideos as $key => $sValue)
                    {
                        $aVideo = array();
                        $aVideo['category'] = $aChannel['category'];
                        $aVideo['url'] = $sValue;
                        $aVideo['user_id'] = $aChannel['user_id'];
                        $aVideo['channel_id'] = $aChannel['channel_id'];
                        $aVideo['privacy'] = $aChannel['privacy'];
                        $aVideo['privacy_comment'] = $aChannel['privacy_comment'];
                        
                        if ($aParentModule)
                        {
                            $aVideo['callback_module'] = $aParentModule['module_id'];
                            $aVideo['callback_item_id'] = $aParentModule['item_id'];
                        }
                        $aVideos[] = $aVideo;
                    }

                    $aVideos = array_reverse($aVideos); //latest videos will be inserted last.
                    
                    $this->oSerVideoChannelChannelProcess->addVideos($aVideos);
                }
                
                
                //Added success                
                $this->setMessage(Phpfox::getPhrase('videochannel.channel_successfully_updated'));

                $sSummary = $this->oLibParseOutput->clean($aChannel['summary']);

                $sEle = '#js_channel_entry_' . $iChannelId;
                $this->call('$(\'' . $sEle . '\').find(\'div.extra_info\') .html(\'' . $this->oLibParseOutput->shorten($sSummary, 300, '...') . '\');')
                        ->call('$(\'' . $sEle . '\').find(\'div.en_summary\') .html(\'' . base64_encode($aChannel['summary']) . '\');')
                        ->call('$(\'' . $sEle . '\').find(\'div.en_title\') .html(\'' . base64_encode($aChannel['title']) . '\');')
                        ->call('$(\'' . $sEle . '\').find(\'a.channel_title\') .html(\'' . $aChannel['title'] . '\');')
                        ->call('$(\'' . $sEle . '\').find(\'a.channel_title\') .attr(\'title\',\'' . $aChannel['title'] . '\');');
            }
            else
            {
                //add a channel and its categories
                $oAddedCh = $this->oSerVideoChannelChannelProcess->addChannel($aChannel);
                
                if (!$oAddedCh['channel_id'])
                {
                    return Phpfox_Error::set(Phpfox::getPhrase('videochannel.add_channel_failed'));
                }
                
                if (!empty($sVideos))
                {
                    foreach ($sVideos as $key => $sValue)
                    {
                        $aVideo = array();
                        $aVideo['category'] = $aChannel['category'];
                        $aVideo['url'] = $sValue;
                        $aVideo['user_id'] = $aChannel['user_id'];
                        $aVideo['channel_id'] = $oAddedCh['channel_id'];
                        $aVideo['privacy'] = $aChannel['privacy'];
                        
                        if (isset($sVals['callback_module']) && ($sVals['callback_module'] != ""))
                        {
                            $aVideo['callback_module'] = $sVals['callback_module'];
                            $aVideo['callback_item_id'] = $sVals['callback_item_id'];
                        }
                        else
                        {
                            $aVideo['callback_module'] = 'videochannel';
                            $aVideo['callback_item_id'] = 0;
                        }
                        
                        $aVideo['privacy_comment'] = (isset($sVals['privacy_comment']) ? $sVals['privacy_comment'] : '0');
                        $aVideos[] = $aVideo;
                    }

                    $aVideos = array_reverse($aVideos); //latest videos will be inserted last.
                    
                    $this->oSerVideoChannelChannelProcess->addVideos($aVideos);
                }
                //Added success                
                $this->setMessage(Phpfox::getPhrase('videochannel.channel_added'));
                
                $iIndex = $this->get('iIndex');
                
                $this->remove('#js_channel_entry_' . $iIndex);
            }
        }
        else
        {
            $this->alert(Phpfox::getPhrase('videochannel.can_not_add_this_channel'));
        }
    }

    public function moderation()
    {
        Phpfox::isUser(true);
        Phpfox::getUserParam('videochannel.can_add_channels', true);

        switch ($this->get('action'))
        {
            case 'deleteChannel':
                //Phpfox::getUserParam('videochannel.can_add_channels', true);
                foreach ((array) $this->get('item_moderate') as $iId)
                {
                    $this->deleteChannel($iId);
                }
                $this->updateCount();
                break;
            case 'autoUpdate':
                //Phpfox::getUserParam('videochannel.can_add_channels', true);
                $iTotalAdded = 0;
                foreach ((array) $this->get('item_moderate') as $iId)
                {
                    $iTotalAdded += $this->autoUpdate($iId);
                }
                if ($iTotalAdded > 0)
                    $sMessage = Phpfox::getPhrase('videochannel.total_x_videos_successfully_added', array('iTotal' => $iTotalAdded));
                else
                    $sMessage = Phpfox::getPhrase('videochannel.no_new_videos_found');
                $this->alert($sMessage, 'Moderation', 300, 150, false);
                $this->hide('#js_channel_processing_' . $iId);
                break;
        }
        $this->hide('.moderation_process');
    }

    public function autoupdate($id = null)
    {
        Phpfox::isUser(true);
        $sModule = 'videochannel';
        $iItem = 0;
        if ($this->get('module'))
        {
            $sModule = $this->get('module');
            $iItem = $this->get('item');
        }
        
        $this->oSerVideoChannel->getCanAddChannel($sModule, $iItem);

        if ($id == null)
            $iId = $this->get('id') ? (int) $this->get('id') : 0;
        else
            $iId = $id;
        
        if (!empty($iId))
        {
            // Get the channel.
            $aChannel = $this->oSerVideoChannelChannelProcess->getChannel($iId);
            
            if (($aChannel['user_id'] != Phpfox::getUserId()) && !Phpfox::isAdmin())
            {
                $sMessage = Phpfox::getPhrase('videochannel.invalid_permissions');
            }
            else
            {
                $iTotalAdded = (int) $this->oSerVideoChannelChannelProcess->updateChannels($iId);
                
                if ($id != null)
                {
                    return $iTotalAdded;
                }

                if ($iTotalAdded > 0)
                {
                    $sMessage = Phpfox::getPhrase('videochannel.total_x_videos_successfully_added', array('iTotal' => $iTotalAdded));
                }
                else
                {
                    $sMessage = Phpfox::getPhrase('videochannel.no_new_videos_found');
                }
            }
            
            // Show the message after get videos.
            $this->alert($sMessage, 'Moderation', 300, 150, false);
            
            // Hide the process bar.
            $this->hide('#js_channel_processing_' . $iId);
        }
    }

}

?>
