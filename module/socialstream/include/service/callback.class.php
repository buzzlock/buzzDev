<?php

defined('PHPFOX') or exit('NO DICE!');


class SocialStream_Service_Callback extends Phpfox_Service
{

    private $_oService;
    private $_bAutoShowFeeds = true;
    private $_aRequest = array();
    private $_sViewId = '';
    private $_aFacebook = true;
    private $_aTwitter = true;

    /**
     * Class constructor
     */
    public function __construct()
    {
        if (!Phpfox::isModule('socialbridge'))
        {
            return false;
        }
        Phpfox_Error::skip(true);
        $this->_sTable = Phpfox::getT('socialstream_feeds');
        $this->_oService = Phpfox::getService('socialstream.services');
        $this->_aRequest = Phpfox::getLib('request')->get('core');
        $this->_sViewId = Phpfox::getLib('request')->get('viewId');
        $aProviders = Phpfox::getService('socialbridge')->getAllProviderData(Phpfox::getUserId());
        $this->_aFacebook = array_key_exists('facebook', $aProviders) ? $aProviders['facebook'] : null;
        $this->_aTwitter = array_key_exists('twitter', $aProviders) ? $aProviders['twitter'] : null;
        $this->_bAutoShowFeeds = Phpfox::getParam('socialstream.show_feeds_automatically');
        Phpfox_Error::skip(false);
    }

    /**
     * Action to take when user cancelled their account
     * @param int $iUser
     */
    public function onDeleteUser($iUser)
    {
        $this->database()->delete(Phpfox::getT('socialstream_feeds'), 'user_id = ' . (int)$iUser);
        $this->database()->delete(Phpfox::getT('socialstream_agents'), 'user_id = ' . (int)$iUser);
        $this->database()->delete(Phpfox::getT('feed'), "user_id = " . (int)$iUser . " AND type_id like '%socialstream%'");
    }

    public function haveLink($text)
    {
        // force http: on www.
        $text = preg_replace("/www\./", "http://www.", $text);
        // eliminate duplicates after force
        $text = preg_replace("/http\:\/\/http\:\/\/www\./", "http://www.", $text);
        $text = preg_replace("/https\:\/\/http\:\/\/www\./", "https://www.", $text);

        // The Regular Expression filter
        $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
        // Check if there is a url in the text
        return (preg_match($reg_exUrl, $text, $url));
    }

    public function getActivityFeedFacebook($aItem)
    {
        if (!$this->_aFacebook || !$this->_aFacebook['connected'])
        {
            return false;
        }

        $aFacebookSetting = Phpfox::getService('socialstream.services')->getSetting('facebook', Phpfox::getUserId(), $this->_aFacebook['profile']['identity']);
        if (!$aFacebookSetting['enable'])
        {
            return false;
        }

        if (!Phpfox::getUserParam('privacy.can_view_all_items') && !$this->_oService->canView($aItem['user_id'], $aItem['privacy']))
        {
            $aItem = null;
            return false;
        }

        if (($this->_sViewId == '' && !$this->_bAutoShowFeeds) || (!$this->_bAutoShowFeeds && isset($this->_aRequest['ajax']) && $this->_aRequest['ajax'] == true && isset($this->_aRequest['call']) && ($this->_aRequest['call'] === 'socialstream.filterFeed' || $this->_aRequest['call'] === 'socialstream.viewMore' || $this->_aRequest['call'] === 'wall.filterFeed' || $this->_aRequest['call'] === 'wall.viewMore') && $this->_sViewId == 'all'))
        {
            return false;
        }

        $aRow = $this->database()->select('sf.*, l.like_id AS is_liked, ' . Phpfox::getUserField('u', 'parent_'))
            ->from($this->_sTable, 'sf')
            ->leftJoin(Phpfox::getT('user'), 'u', 'u.user_id = sf.user_id')
            ->leftJoin(Phpfox::getT('like'), 'l', 'l.type_id = \'socialstream\' AND l.item_id = sf.feed_id AND l.user_id = ' . Phpfox::getUserId())
            ->where('sf.feed_id = ' . (int)$aItem['item_id'])
            ->execute('getSlaveRow');

        if (!isset($aRow['feed_id']))
        {
            return false;
        }

        if (!empty($aRow['link']) && substr($aRow['link'], 0, 7) != 'http://' && substr($aRow['link'], 0, 8) != 'https://')
        {
            $aRow['link'] = 'http://' . $aRow['link'];
        }

        $sMessage = '';

        $aAgent = $this->_aFacebook['profile'];
        if ($aItem['privacy'] == 3 && (empty($aAgent) || (!empty($aAgent) && strtolower($aAgent['identity']) != strtolower($aRow['social_agent_id']))))
        {
            $sMessage = Phpfox::getPhrase('socialstream.gets_a_href_link_title_title_full_name_s_feed_from_facebook', array(
                'link' => $aRow['service_feed_link'],
                'title' => $aRow['social_agent_full_name'],
                'full_name' => $aRow['social_agent_full_name']
                ));
        }
        $aReturn = array(
            'no_share' => true,
            'feed_info' => $sMessage,
            'feed_title' => $aRow['title'],
            'feed_link' => $aRow['link'],
            'total_comment' => $aRow['total_comment'],
            'feed_total_like' => $aRow['total_like'],
            'feed_is_liked' => $aRow['is_liked'],
            'time_stamp' => $aRow['time_stamp'],
            'enable_like' => true,
            'comment_type_id' => 'socialstream',
            'like_type_id' => 'socialstream',
            'social_agent_full_name' => Phpfox::getPhrase('socialstream.by_full_name_on_facebook', array('full_name' => trim(phpfox::getLib('parse.output')->shorten($aRow['social_agent_full_name'], 30, '...')))),
            'service_feed_link' => $aRow['service_feed_link']
            );

        if (!empty($aRow['link']))
        {
            if (!empty($aRow['message']))
            {
                $aReturn['feed_status'] = $aRow['message'];
            }
            $aReturn['feed_content'] = $aRow['content'];
        }
        else
        {
            $aReturn['feed_status'] = !empty($aRow['content']) ? $aRow['content'] : $aRow['message'];
        }

        $aReturn['feed_icon'] = Phpfox::getLib('template')->getStyle('image', 'facebook_icon.png', 'socialstream');

        if (!empty($aRow['link']))
        {
            $aParts = parse_url($aRow['link']);
            $aReturn['feed_title_extra'] = $aParts['host'];
            $aReturn['feed_title_extra_link'] = $aParts['scheme'] . '://' . $aParts['host'];
        }

        if (Phpfox::getParam('core.warn_on_external_links') && isset($aReturn['feed_title_extra_link']))
        {
            if (!preg_match('/' . preg_quote(Phpfox::getParam('core.host')) . '/i', $aReturn['feed_link']))
            {
                $aReturn['feed_link'] = Phpfox::getLib('url')->makeUrl('core.redirect', array('url' => Phpfox::getLib('url')->encode($aReturn['feed_link'])));
                $aReturn['feed_title_extra_link'] = Phpfox::getLib('url')->makeUrl('core.redirect', array('url' => Phpfox::getLib('url')->encode($aReturn['feed_title_extra_link'])));
            }
        }

        if (!empty($aRow['image_url']))
        {
            $aReturn['feed_image'] = '<img src="' . $aRow['image_url'] . '" alt="" />';
            if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8') !== false))
            {
                $aReturn['feed_custom_width'] = '120px';
            }
        }
        elseif (!empty($aRow['link']))
        {
            $aReturn['feed_image'] = '<img src="' . Phpfox::getLib('template')->getStyle('image', 'no-image.png', 'socialstream') . '" alt="" />';
            $aReturn['feed_custom_width'] = '0px !important';
        }

        $aReturn['feed_display'] = 'default';

        return $aReturn;
    }

    public function getActivityFeedTwitter($aItem)
    {
        if (!$this->_aTwitter || !$this->_aTwitter['connected'])
        {
            return false;
        }

        $aTwitterSetting = Phpfox::getService('socialstream.services')->getSetting('twitter', Phpfox::getUserId(), $this->_aTwitter['profile']['identity']);

        if (!$aTwitterSetting['enable'])
        {
            return false;
        }

        if (!Phpfox::getUserParam('privacy.can_view_all_items') && !$this->_oService->canView($aItem['user_id'], $aItem['privacy']))
        {
            $aItem = null;
            return false;
        }

        if (($this->_sViewId == '' && !$this->_bAutoShowFeeds) || (!$this->_bAutoShowFeeds && isset($this->_aRequest['ajax']) && $this->_aRequest['ajax'] == true && isset($this->_aRequest['call']) && ($this->_aRequest['call'] === 'socialstream.filterFeed' || $this->_aRequest['call'] === 'socialstream.viewMore' || $this->_aRequest['call'] === 'wall.filterFeed' || $this->_aRequest['call'] === 'wall.viewMore') && $this->_sViewId == 'all'))
        {
            return false;
        }

        $aRow = $this->database()->select('sf.*, l.like_id AS is_liked, ' . Phpfox::getUserField('u', 'parent_'))
            ->from($this->_sTable, 'sf')
            ->leftJoin(Phpfox::getT('user'), 'u', 'u.user_id = sf.user_id')
            ->leftJoin(Phpfox::getT('like'), 'l', 'l.type_id = \'socialstream\' AND l.item_id = sf.feed_id AND l.user_id = ' . Phpfox::getUserId())
            ->where('sf.feed_id = ' . (int)$aItem['item_id'])
            ->execute('getSlaveRow');

        if (!isset($aRow['feed_id']))
        {
            return false;
        }

        if (!empty($aRow['link']) && substr($aRow['link'], 0, 7) != 'http://' && substr($aRow['link'], 0, 8) != 'https://')
        {
            $aRow['link'] = 'http://' . $aRow['link'];
        }

        $sMessage = '';
        $aAgent = $this->_aTwitter['profile'];

        if ($aItem['privacy'] == 3 && (empty($aAgent) || (!empty($aAgent) && strtolower($aAgent['identity']) != strtolower($aRow['social_agent_id']))))
        {
            $sMessage = Phpfox::getPhrase('socialstream.gets_a_href_link_title_title_full_name_s_feed_from_twitter', array(
                'link' => $aRow['service_feed_link'],
                'title' => $aRow['social_agent_full_name'],
                'full_name' => $aRow['social_agent_full_name']
                ));
        }
        $aReturn = array(
            'no_share' => true,
            'feed_info' => $sMessage,
            'feed_title' => $aRow['title'],
            'feed_link' => $aRow['link'],
            'total_comment' => $aRow['total_comment'],
            'feed_total_like' => $aRow['total_like'],
            'feed_is_liked' => $aRow['is_liked'],
            'time_stamp' => $aRow['time_stamp'],
            'enable_like' => true,
            'comment_type_id' => 'socialstream',
            'like_type_id' => 'socialstream',
            'social_agent_full_name' => Phpfox::getPhrase('socialstream.by_full_name_on_twitter', array('full_name' => trim(phpfox::getLib('parse.output')->shorten($aRow['social_agent_full_name'], 30, '...')))),
            'service_feed_link' => $aRow['service_feed_link']
            );

        if ($this->haveLink($aRow['content']))
        {
            $aReturn['feed_custom_html'] = $aRow['content'];
        }
        else
        {
            $aReturn['feed_status'] = $aRow['content'];
        }

        $aReturn['feed_icon'] = Phpfox::getLib('template')->getStyle('image', 'twitter_icon.png', 'socialstream');

        if (!empty($aRow['link']))
        {
            $aParts = parse_url($aRow['link']);
            $aReturn['feed_title_extra'] = $aParts['host'];
            $aReturn['feed_title_extra_link'] = $aParts['scheme'] . '://' . $aParts['host'];
        }

        if (Phpfox::getParam('core.warn_on_external_links') && isset($aReturn['feed_title_extra_link']))
        {
            if (!preg_match('/' . preg_quote(Phpfox::getParam('core.host')) . '/i', $aReturn['feed_link']))
            {
                $aReturn['feed_link'] = Phpfox::getLib('url')->makeUrl('core.redirect', array('url' => Phpfox::getLib('url')->encode($aReturn['feed_link'])));
                $aReturn['feed_title_extra_link'] = Phpfox::getLib('url')->makeUrl('core.redirect', array('url' => Phpfox::getLib('url')->encode($aReturn['feed_title_extra_link'])));
            }
        }

        if (!empty($aRow['image_url']))
        {
            $aReturn['feed_image'] = '<img src="' . $aRow['image_url'] . '" alt="" />';

            if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8') !== false))
            {
                $aReturn['feed_custom_width'] = '120px';
            }
        }
        elseif ($this->haveLink($aRow['content']) || !empty($aRow['link']))
        {
            $aReturn['feed_image'] = '<img src="' . Phpfox::getLib('template')->getStyle('image', 'no-image.png', 'socialstream') . '" alt="" />';
            $aReturn['feed_custom_width'] = '0px !important';
        }

        $aReturn['feed_display'] = 'default';
        return $aReturn;
    }

    public function addLike($iItemId, $bDoNotSendEmail = false)
    {
        $aRow = $this->database()->select('feed_id, title, user_id')->from(Phpfox::getT('socialstream_feeds'))->where('feed_id = ' . (int)$iItemId)->execute('getSlaveRow');

        if (!isset($aRow['feed_id']))
        {
            return false;
        }

        $this->database()->updateCount('like', 'type_id = \'socialstream\' AND item_id = ' . (int)$iItemId . '', 'total_like', 'socialstream_feeds', 'feed_id = ' . (int)$iItemId);

        if (!$bDoNotSendEmail)
        {
            Phpfox::getLib('mail')->to($aRow['user_id'])->subject(Phpfox::getPhrase('socialstream.full_name_liked_your_social_feed_title', array('full_name' => Phpfox::getUserBy('full_name'), 'title' => $aRow['title'])))->message(Phpfox::getPhrase('socialstream.full_name_liked_your_social_feed_title', array('full_name' => Phpfox::getUserBy('full_name'), 'title' => $aRow['title'])))->notification('socialstream.new_like')->send();

            Phpfox::getService('notification.process')->add('socialstream_like', $aRow['feed_id'], $aRow['user_id']);
        }
    }

    public function getNotificationLike($aNotification)
    {
        $aRow = $this->database()->select('sf.feed_id, sf.title, sf.user_id, u.gender, u.full_name')
            ->from(Phpfox::getT('socialstream_feeds'), 'sf')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = sf.user_id')
            ->where('sf.feed_id = ' . (int)$aNotification['item_id'])
            ->execute('getSlaveRow');

        $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
        $sTitle = Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...');

        $sPhrase = '';
        if ($aNotification['user_id'] == $aRow['user_id'])
        {
            $sPhrase = Phpfox::getPhrase('socialstream.users_liked_gender_own_social_feed_title', array(
                'users' => $sUsers,
                'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1),
                'title' => $sTitle
                ));
        }
        elseif ($aRow['user_id'] == Phpfox::getUserId())
        {
            $sPhrase = Phpfox::getPhrase('socialstream.users_liked_your_social_feed_title', array('users' => $sUsers, 'title' => $sTitle));
        }
        else
        {
            $sPhrase = Phpfox::getPhrase('socialstream.users_liked_span_class_drop_data_user_row_full_name_s_span_social_feed_title', array(
                'users' => $sUsers,
                'row_full_name' => $aRow['full_name'],
                'title' => $sTitle
                ));
        }

        return array(
            'link' => Phpfox::getLib('url')->makeUrl(''),
            'message' => $sPhrase,
            'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog'
            ));
    }

    public function deleteLike($iItemId)
    {
        $this->database()->updateCount('like', 'type_id = \'socialstream\' AND item_id = ' . (int)$iItemId . '', 'total_like', 'socialstream_feeds', 'feed_id = ' . (int)$iItemId);
    }

    public function deleteComment($iId)
    {
        $this->database()->update(Phpfox::getT('socialstream_feeds'), array('total_comment' => array('= total_comment -', 1)), 'feed_id = ' . (int)$iId);
    }

    public function getAjaxCommentVar()
    {
        return null;
    }

    public function addComment($aVals, $iUserId = null, $sUserName = null)
    {
        $aRow = $this->database()->select('sf.feed_id, sf.title, u.full_name, u.user_id, u.user_name, u.gender')
            ->from(Phpfox::getT('socialstream_feeds'), 'sf')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = sf.user_id')
            ->where('sf.feed_id = ' . (int)$aVals['item_id'])
            ->execute('getSlaveRow');

        // Update the post counter if its not a comment put under moderation or if the person posting the comment is the owner of the item.
        if (empty($aVals['parent_id']))
        {
            $this->database()->updateCounter('socialstream_feeds', 'total_comment', 'feed_id', $aRow['feed_id']);
        }

        // Send the user an email
        $sLink = Phpfox::getLib('url')->makeUrl(''); //permalink('socialstream_feeds', $aRow['feed_id'], $aRow['title']);

        Phpfox::getService('comment.process')->notify(array(
            'user_id' => $aRow['user_id'],
            'item_id' => $aRow['feed_id'],
            'owner_subject' => Phpfox::getPhrase('socialstream.full_name_commented_on_your_social_feed_title', array('full_name' => Phpfox::getUserBy('full_name'), 'title' => $this->preParse()->clean($aRow['title'], 100))),
            'owner_message' => Phpfox::getPhrase('socialstream.full_name_commented_on_your_social_feed_a_href_link_title_a', array(
                'full_name' => Phpfox::getUserBy('full_name'),
                'link' => $sLink,
                'title' => $aRow['title'])
                ),
            'owner_notification' => 'comment.add_new_comment',
            'notify_id' => 'comment_socialstream',
            'mass_id' => 'socialstream',
            'mass_subject' => (Phpfox::getUserId() == $aRow['user_id'] ? Phpfox::getPhrase('socialstream.full_name_commented_on_gender_social_feed', array('full_name' => Phpfox::getUserBy('full_name'), 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1))) : Phpfox::getPhrase('socialstream.full_name_commented_on_row_full_name_s_social_feed', array('full_name' => Phpfox::getUserBy('full_name'), 'row_full_name' => $aRow['full_name']))),
            'mass_message' => (Phpfox::getUserId() == $aRow['user_id'] ? Phpfox::getPhrase('socialstream.full_name_commented_on_gender_social_feed_a_href_link_title_a', array(
                'full_name' => Phpfox::getUserBy('full_name'),
                'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1),
                'link' => $sLink,
                'title' => $aRow['title'])) : Phpfox::getPhrase('socialstream.full_name_commented_on_row_full_name_s_social_feed_a_href_link_title_a_message', array(
                'full_name' => Phpfox::getUserBy('full_name'),
                'row_full_name' => $aRow['full_name'],
                'link' => $sLink,
                'title' => $aRow['title']))
                )
            ));
    }

    public function getCommentItem($iId)
    {
        $aRow = $this->database()->select('feed_id AS comment_item_id, privacy_comment, user_id AS comment_user_id')
            ->from(Phpfox::getT('socialstream_feeds'))
            ->where('feed_id = ' . (int)$iId)
            ->execute('getSlaveRow');

        $aRow['comment_view_id'] = '0';

        if (!Phpfox::getService('comment')->canPostComment($aRow['comment_user_id'], $aRow['privacy_comment']))
        {
            Phpfox_Error::set(Phpfox::getPhrase('socialstream.unable_to_post_a_comment_on_this_item_due_to_privacy_settings'));

            unset($aRow['comment_item_id']);
        }

        return $aRow;
    }

    public function getCommentNotification($aNotification)
    {
        $aRow = $this->database()->select('sf.feed_id, sf.title, u.user_id, u.gender, u.user_name, u.full_name')->from(Phpfox::getT('socialstream_feeds'), 'sf')->join(Phpfox::getT('user'), 'u', 'u.user_id = sf.user_id')->where('sf.feed_id = ' . (int)$aNotification['item_id'])->execute('getSlaveRow');

        $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
        $sTitle = Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...');

        $sPhrase = '';
        if ($aNotification['user_id'] == $aRow['user_id'] && !isset($aNotification['extra_users']))
        {
            $sPhrase = Phpfox::getPhrase('socialstream.users_commented_on_gender_social_feed_title', array(
                'users' => $sUsers,
                'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1),
                'title' => $sTitle
                ));
        }
        elseif ($aRow['user_id'] == Phpfox::getUserId())
        {
            $sPhrase = Phpfox::getPhrase('socialstream.users_commented_on_your_social_feed_title', array('users' => $sUsers, 'title' => $sTitle));
        }
        else
        {
            $sPhrase = Phpfox::getPhrase('socialstream.users_commented_on_span_class_drop_data_user_row_full_name_s_span_social_feed_title', array(
                'users' => $sUsers,
                'row_full_name' => $aRow['full_name'],
                'title' => $sTitle
                ));
        }

        return array(
            'link' => Phpfox::getLib('url')->makeUrl(''),
            'message' => $sPhrase,
            'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog'
            ));
    }

    public function getRedirectComment($iId)
    {
        $sLink = Phpfox::getLib('url')->makeUrl('');
        return $sLink;
    }

    public function getCommentNotificationTag($aNotification)
    {
        $aRow = $this->database()->select('sf.feed_id, sf.title, u.user_name,u.full_name')
            ->from(Phpfox::getT('comment'), 'c')
            ->join(Phpfox::getT('socialstream_feeds'), 'sf', 'sf.feed_id = c.item_id')
            ->join(Phpfox::getT('user'), 'u', 'u.user_id = c.user_id')
            ->where('c.comment_id = ' . (int)$aNotification['item_id'])
            ->execute('getSlaveRow');

        $sPhrase = Phpfox::getPhrase('socialstream.user_name_tagged_you_in_a_comment_in_a_social_feed', array('user_name' => $aRow['full_name']));

        return array(
            'link' => Phpfox::getLib('url')->makeUrl(''),
            'message' => $sPhrase,
            'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog'
            ));
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
        if ($sPlugin = Phpfox_Plugin::get('socialstream.service_callback__call'))
        {
            eval($sPlugin);
            return;
        }

        /**
         * No method or plug-in found we must throw a error.
         */
        Phpfox_Error::trigger('Call to undefined method ' . __class__ . '::' . $sMethod . '()', E_USER_ERROR);
    }

}

?>
