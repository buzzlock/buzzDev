<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class Videochannel_Component_Controller_View extends Phpfox_Component {

    /**
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
        Phpfox::getUserParam('videochannel.can_access_videos', true);
        $aCallback = $this->getParam('aCallback', false);
        $iVideo = $this->request()->getInt(($aCallback !== false ? $aCallback['request'] : 'req2'));
        if (Phpfox::isUser() && Phpfox::isModule('notification'))
        {
            Phpfox::getService('notification.process')->delete('videochannel_like', $this->request()->getInt('req2'), Phpfox::getUserId());
            Phpfox::getService('notification.process')->delete('videochannel', $this->request()->getInt('req2'), Phpfox::getUserId());
        }
        // Get video item.
        if (!($aVideo = Phpfox::getService('videochannel')->callback($aCallback)->getVideo($iVideo)))
        {
            return Phpfox_Error::display(Phpfox::getPhrase('videochannel.the_video_you_are_looking_for_does_not_exist_or_has_been_removed'));
        }
        // Delete notification.
        if (Phpfox::getUserId() == $aVideo['user_id'] && Phpfox::isModule('notification'))
        {
            Phpfox::getService('notification.process')->delete('videochannel_approved', $this->request()->getInt('req2'), Phpfox::getUserId());
        }
        // Check title.
        Phpfox::getService('core.redirect')->check($aVideo['title']);
        // Check privacy.
        if (Phpfox::isModule('privacy'))
        {
            Phpfox::getService('privacy')->check('channel_video', $aVideo['video_id'], $aVideo['user_id'], $aVideo['privacy'], $aVideo['is_friend']);
        }
        $this->setParam('aVideo', $aVideo);
        $this->setParam('sGroup', ($this->request()->get('req1') == 'group') ? $this->request()->get('req2') : '');
        $arRatingCallback = array(
            'type' => 'videochannel',
            'total_rating' => Phpfox::getPhrase('videochannel.total_rating_ratings', array('total_rating' => $aVideo['total_rating'])), //$aVideo['total_rating'] . ' Ratings',
            'default_rating' => $aVideo['total_score'],
            'item_id' => $aVideo['video_id'],
            'stars' => array(
                '2' => Phpfox::getPhrase('videochannel.poor'),
                '4' => Phpfox::getPhrase('videochannel.nothing_special'),
                '6' => Phpfox::getPhrase('videochannel.worth_watching'),
                '8' => Phpfox::getPhrase('videochannel.pretty_cool'),
                '10' => Phpfox::getPhrase('videochannel.awesome')
            )
        );
        $this->setParam('aRatingCallback', $arRatingCallback);
        $arFeed = array(
            'comment_type_id' => 'videochannel',
            'privacy' => $aVideo['privacy'],
            'comment_privacy' => $aVideo['privacy_comment'],
            'like_type_id' => 'videochannel',
            'feed_is_liked' => (isset($aVideo['is_liked']) ? $aVideo['is_liked'] : false),
            'feed_is_friend' => $aVideo['is_friend'],
            'item_id' => $aVideo['video_id'],
            'user_id' => $aVideo['user_id'],
            'total_comment' => $aVideo['total_comment'],
            'total_like' => $aVideo['total_like'],
            'feed_link' => Phpfox::permalink('videochannel', $aVideo['video_id'], $aVideo['title']),
            'feed_title' => $aVideo['title'],
            'feed_display' => 'view',
            'feed_total_like' => $aVideo['total_like'],
            'report_module' => 'videochannel',
            'report_phrase' => Phpfox::getPhrase('videochannel.report_this_video')
        );
        $this->setParam('aFeed', $arFeed);
        if (!empty($aVideo['module_id']) && $aVideo['module_id'] != 'videochannel')
        {
            if ($aCallback = Phpfox::callback($aVideo['module_id'] . '.getVideoDetails', $aVideo))
            {
                $this->template()
                        ->setBreadcrumb($aCallback['breadcrumb_title'], $aCallback['breadcrumb_home'])
                        ->setBreadcrumb($aCallback['title'], $aCallback['url_home']);
                if ($aVideo['module_id'] == 'pages' && !Phpfox::getService('pages')->hasPerm($aCallback['item_id'], 'videochannel.view_browse_videos'))
                {
                    return Phpfox_Error::display(Phpfox::getPhrase('videochannel.unable_to_view_this_item_due_to_privacy_settings'));
                }
            }
        }
        if (Phpfox::isModule('rate'))
        {
            $this->template()->setPhrase(array('rate.thanks_for_rating'));
        }
        $bIsFavourite = Phpfox::getService('videochannel')->isFavourite($aVideo['video_id']);
        $this->template()
                ->setTitle($aVideo['title'])
                ->setTitle(Phpfox::getPhrase('videochannel.videochannel'))
                ->setBreadcrumb(Phpfox::getPhrase('videochannel.videochannel'), ($aCallback === false ? $this->url()->makeUrl('videochannel') : $aCallback['url_home'] . 'videochannel'))
                ->setBreadcrumb($aVideo['title'], $this->url()->permalink('videochannel', $aVideo['video_id'], $aVideo['title']), true)
                ->setMeta('description', $aVideo['title'] . '.' . (!empty($aVideo['text']) ? $aVideo['text'] : ''))
                ->setMeta('keywords', $this->template()->getKeywords($aVideo['title']))
                ->setHeader('cache', array(
            'video.js' => 'module_videochannel',
            'videochannel.js' => 'module_videochannel',
            'jquery.rating.css' => 'style_css',
            'jquery/plugin/star/jquery.rating.js' => 'static_script',
            'jquery/plugin/jquery.highlightFade.js' => 'static_script',
            'rate.js' => 'module_rate',
            'jquery/plugin/jquery.scrollTo.js' => 'static_script',
            'quick_edit.js' => 'static_script',
            'comment.css' => 'style_css',
            'pager.css' => 'style_css',
            'switch_legend.js' => 'static_script',
            'switch_menu.js' => 'static_script',
            'videochannel.css' => 'module_videochannel',
            'view.css' => 'module_videochannel',
            'feed.js' => 'module_feed'
                )
        )
                ->setEditor(array('load' => 'simple'))
                ->assign(array(
            'aVideo' => $aVideo,
            'bIsFavourite' => $bIsFavourite,
            'sCorePath' => Phpfox::getParam('core.path')
        ));
        (($sPlugin = Phpfox_Plugin::get('videochannel.component_controller_view_end')) ? eval($sPlugin) : false);
        if (Phpfox::isModule('rate'))
        {
            $this->template()->setHeader(array('<script type="text/javascript">$Behavior.rateVideo = function() { $Core.rate.init({module: \'videochannel\', display: ' . ($aVideo['has_rated'] ? 'false' : ($aVideo['user_id'] == Phpfox::getUserId() ? 'false' : 'true')) . ', error_message: \'' . ($aVideo['has_rated'] ? Phpfox::getPhrase('videochannel.you_have_already_voted', array('phpfox_squote' => true)) : Phpfox::getPhrase('videochannel.you_cannot_rate_your_own_video', array('phpfox_squote' => true))) . '\'}); };</script>'));
        }
        if (!$aVideo['is_stream'])
        {
            $sVideoPath = (preg_match("/\{file\/videos\/(.*)\/(.*)\.flv\}/i", $aVideo['destination'], $aMatches) ? Phpfox::getParam('core.path') . str_replace(array('{', '}'), '', $aMatches[0]) : Phpfox::getParam('video.url') . $aVideo['destination']);
            if (Phpfox::getParam('core.allow_cdn') && !empty($aVideo['server_id']))
            {
                $sTempVideoPath = Phpfox::getLib('cdn')->getUrl($sVideoPath, $aVideo['server_id']);
                if (!empty($sTempVideoPath))
                {
                    $sVideoPath = $sTempVideoPath;
                }
            }
            (($sPlugin = Phpfox_Plugin::get('videochannel.component_controller_view_video_path')) ? eval($sPlugin) : false);
            $this->template()->setHeader(
                    array(
                        'player/flowplayer/flowplayer.js' => 'static_script',
                        'player/' . Phpfox::getParam('core.default_music_player') . '/core.js' => 'static_script',
                        '<script type="text/javascript">$Behavior.playVideo = function() { $Core.player.load({id: \'js_video_player\', auto: true, type: \'video\', play: \'' . $sVideoPath . '\'}); }</script>'
                    )
            );
        }
        //to make facebook know the image
        $sImageUrl = str_replace('%s', '_480', Phpfox::getParam('core.path') . 'file' . PHPFOX_DS . 'pic' . PHPFOX_DS . 'video' . PHPFOX_DS . $aVideo['image_path']);
        $this->template()->setHeader(array('<meta property="og:image" content="' . $sImageUrl . '" />'));
        if (isset($aVideo['breadcrumb']) && is_array($aVideo['breadcrumb']))
        {
            foreach ($aVideo['breadcrumb'] as $aParentCategory)
            {
                if (isset($aParentCategory[0]))
                {
                    $strCategoryLink = '';
                    if ($aVideo['module_id'] == 'pages' && $aCallback !== false)
                    {
                        $strCategoryLink = $aCallback['url_home'] . 'videochannel/category/' . $aParentCategory[2] . '/' . $this->url()->cleanTitle($aParentCategory[0]);
                    }
                    else
                    {
                        $strCategoryLink = $aParentCategory[1];
                    }
                    $this->template()
                            ->setBreadcrumb($aParentCategory[0], $strCategoryLink)
                            ->setMeta('description', $aParentCategory[0])
                            ->setMeta('keywords', $this->template()->getKeywords($aParentCategory[0]));
                }
            }
        }
    }

    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('videochannel.component_controller_view_clean')) ? eval($sPlugin) : false);
    }

}

?>
