<?php

/**
 * @package mfox
 * @version 3.01
 */
defined('PHPFOX') or exit('NO DICE!');
/**
 * @author ductc@younetco.com
 * @package mfox
 * @subpackage mfox.service
 * @version 3.01
 * @since June 5, 2013
 * @link Mfox Api v2.0
 */
class Mfox_Service_Pages extends Phpfox_Service {
    /**
     * Using in page to get notification.
     * @param array $aNotification
     * @return boolean
     */
    public function doPagesGetNotificationComment_Feed($aNotification)
    {
        /**
         * @var array
         */
        $aRow = $this->database()->select('fc.feed_comment_id, u.user_id, u.gender, u.user_name, u.full_name, e.page_id, e.title, pu.vanity_url')
                ->from(Phpfox::getT('pages_feed_comment'), 'fc')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = fc.user_id')
                ->join(Phpfox::getT('pages'), 'e', 'e.page_id = fc.parent_user_id')
                ->leftJoin(Phpfox::getT('pages_url'), 'pu', 'pu.page_id = e.page_id')
                ->where('fc.feed_comment_id = ' . (int) $aNotification['item_id'])
                ->execute('getSlaveRow');
        if (!isset($aRow['feed_comment_id']))
        {
            return false;
        }
        if ($aNotification['user_id'] == $aRow['user_id'] && isset($aNotification['extra_users']) && count($aNotification['extra_users']))
        {
            $sUsers = Phpfox::getService('notification')->getUsers($aNotification, true);
        }
        else
        {
            $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
        }
        /**
         * @var string
         */
        $sGender = Phpfox::getService('user')->gender($aRow['gender'], 1);
        /**
         * @var string
         */
        $sTitle = Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...');
        /**
         * @var string
         */
        $sPhrase = '';
        if ($aNotification['user_id'] == $aRow['user_id'])
        {
            if (isset($aNotification['extra_users']) && count($aNotification['extra_users']))
            {
                $sPhrase = Phpfox::getPhrase('pages.users_commented_on_span_class_drop_data_user_full_name_s_span_comment_on_the_page_title', array('users' => $sUsers, 'full_name' => $aRow['full_name'], 'title' => $sTitle));
            }
            else
            {
                $sPhrase = Phpfox::getPhrase('pages.users_commented_on_gender_own_comment_on_the_page_title', array('users' => $sUsers, 'gender' => $sGender, 'title' => $sTitle));
            }
        }
        elseif ($aRow['user_id'] == Phpfox::getUserId())
        {
            $sPhrase = Phpfox::getPhrase('pages.users_commented_on_one_of_your_comments_on_the_page_title', array('users' => $sUsers, 'title' => $sTitle));
        }
        else
        {
            $sPhrase = Phpfox::getPhrase('pages.users_commented_on_one_of_full_name', array('users' => $sUsers, 'full_name' => $aRow['full_name'], 'title' => $sTitle));
        }
        return array(
            'link' => array(
                'iPageId' => $aRow['page_id'],
                'sTitle' => $aRow['title']
            ),
            'message' => strip_tags($sPhrase),
            'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
        );
    }
}

