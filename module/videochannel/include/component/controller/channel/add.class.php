<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class Videochannel_Component_Controller_Channel_Add extends Phpfox_Component
{

    /**
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
        Phpfox::isUser(true);

        $sModule = $this->request()->get('module', false);
        $iItem = $this->request()->getInt('item', false);

        $aInPage = Phpfox::getService('videochannel')->getIsInPageModule($sModule, $iItem, $this->request()->get('val'));
        $bCanAddChannelInPage = false;
        if (isset($aInPage['module_id']))
        {
            $sModule = $aInPage['module_id'];
            $iItem = $aInPage['item_id'];
            if ((Phpfox::getService('videochannel')->isPageOwner($aInPage['item_id']) && Phpfox::getUserParam('videochannel.can_add_channel_on_page', true)) || Phpfox::isAdmin())
            {
                $bCanAddChannelInPage = true;
            }
            else
            {
                $this->url()->send('videochannel');
            }
        }
        else
        {
            Phpfox::getUserParam('videochannel.can_add_channels', true);
        }

        $aCallback = false;
        if ($sModule !== false && $iItem !== false && Phpfox::hasCallback($sModule, 'getVideoDetails'))
        {
            if (($aCallback = Phpfox::callback($sModule . '.getVideoDetails', array('item_id' => $iItem))))
            {
                $this->template()->setBreadcrumb($aCallback['breadcrumb_title'], $aCallback['breadcrumb_home']);
                $this->template()->setBreadcrumb($aCallback['title'], $aCallback['url_home']);
            }
        }

        $iChannelCount = Phpfox::getService('videochannel.channel.process')->channelsCount(Phpfox::getUserId());

        if ($iChannelCount == Phpfox::getUserParam('videochannel.channels_limit'))
        {
            Phpfox_Error::set(Phpfox::getPhrase('videochannel.added_channels_already_reached_the_limit'));
            $this->template()->assign('bIsLimited', true);
            return;
        }
        //Added channels already reached the limit.
        //set url when currently in page
        if (($sModule == 'pages') && $iItem != false)
        {

            $sSubmitUrl = Phpfox::getLib('url')->makeUrl('videochannel.channel.add', array('module' => 'pages', 'item' => $iItem));
        }
        else
        {
            //if not in page
            $sSubmitUrl = $this->url()->makeUrl('videochannel.channel.add');
        }
        $sKeyword = "";
        if (isset($_SESSION['keyword']))
            $sKeyword = $_SESSION['keyword'];

        //Search channels
        if (isset($_POST['find_channels']) || isset($_POST['next_channels']) || isset($_POST['prev_channels']))
        {
            $aVals = $this->request()->getArray('val');

            if (isset($aVals['keyword']) && $aVals['keyword'] != "")
            {
                $sKeyword = Phpfox::getLib('parse.input')->clean(preg_replace('/\'/', "", $aVals['keyword']));
                $_SESSION['keyword'] = $sKeyword;
            }

            $sTitle = $sKeyword; //Phpfox::getLib('parse.input')->clean(preg_replace('/\'/',"",$sKeyword));		   	

            if ($sTitle != "")
            {
                $aChannels = array();  //Array for found channels
                $sQuery = urlencode($sTitle); //Set search query

                $iMaxResult = Phpfox::getUserParam('videochannel.channel_search_results'); //Set max result per page
                if ($iMaxResult > 50)
                    $iMaxResult = 50;
                //Set start index
                if (isset($_POST['find_channels']))
                    $iStartIndex = 1;
                else
                    $iStartIndex = isset($aVals['currIndex']) ? $aVals['currIndex'] : 1;

                if (isset($_POST['next_channels']))
                {
                    $iStartIndex += $iMaxResult;
                }
                else if (isset($_POST['prev_channels']))
                {
                    $iStartIndex = $iStartIndex - $iMaxResult > 0 ? $iStartIndex - $iMaxResult : 1;
                }

                $bIsNext = false;
                $bIsPrev = false;

                //Set api version
                $iApiVersion = 2;
                //Generate feed URL
                $sFeedUrl = 'http://gdata.youtube.com/feeds/api/channels/?q=' . $sQuery . '&start-index='
                        . $iStartIndex . '&max-results=' . $iMaxResult . '&v=' . $iApiVersion;
                //Find channels			   
                $aChannels = Phpfox::getService('videochannel.channel.process')->getChannels($sFeedUrl, $bIsPrev, $bIsNext, $sModule, $iItem);

                //Try too search with stage gdata server if not found any channels

                if (count($aChannels) == 0)
                {
                    $sFeedUrl = 'http://stage.gdata.youtube.com/feeds/api/channels/?q=' . $sQuery . '&start-index='
                            . $iStartIndex . '&max-results=' . $iMaxResult . '&v=' . $iApiVersion;
                    //Find channels			   
                    $aChannels = Phpfox::getService('videochannel.channel.process')->getChannels($sFeedUrl, $bIsPrev, $bIsNext, $sModule, $iItem);
                }

                /*
                  if($iStartIndex + $iMaxResult > 50)
                  {
                  $bIsNext = false;
                  }
                 */
                elseif (!empty($bIsNext))
                {
                    $bCheckNext = Phpfox::getService('videochannel.channel.process')->checkNext($bIsNext);
                    if (!$bCheckNext)
                        $bIsNext = "";
                }

                //echo "Prev: $bIsPrev<br/>Next: $bIsNext";
                $aChannels = array_reverse($aChannels);
                $this->template()->assign(array('aChannels' => $aChannels, 'currIndex' => $iStartIndex, 'bIsPrev' => $bIsPrev, 'bIsNext' => $bIsNext,));
            }
        }
        else
        {
            $sKeyword = "";
        }

        //End Search channels
        $this->template()->assign(array(
            'sSubmitUrl' => $sSubmitUrl,
            'sKeyword' => $sKeyword,
            'sModule' => ($sModule) ? ($sModule) : 'videochannel',
            'iItem' => ($iItem) ? ($iItem) : 0,
            'bCanAddChannelInPage' => $bCanAddChannelInPage
        ));

        $this->template()->setTitle(Phpfox::getPhrase('videochannel.add_a_channel'))
                ->setBreadcrumb(Phpfox::getPhrase('videochannel.videochannel'), ($aCallback === false ? $this->url()->makeUrl('videochannel') : $aCallback['url_home'] . "videochannel/"))
                ->setBreadcrumb(Phpfox::getPhrase('videochannel.add_a_channel'), ($aCallback === false ? $this->url()->makeUrl('videochannel.channel.add') : $this->url()->makeUrl('videochannel.channel.add', array('module' => $sModule, 'item' => $iItem))), true)
                ->setHeader('cache', array(
                    'videochannel.css' => 'module_videochannel',
                    'channel.js' => 'module_videochannel',
                    'videochannel.js' => 'module_videochannel'
                        )
        );
    }

    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('videochannel.component_controller_channel_add_clean')) ? eval($sPlugin) : false);
    }

}

?>
