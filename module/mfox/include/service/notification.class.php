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
 * @since May 21, 2013
 * @link Mfox Api v2.0
 */
class Mfox_Service_Notification extends Phpfox_Service {

    /**
     * Callback of notification.
     * @staticvar array $aModules
     * @param string $sCall
     * @param array $aParams
     * @return array
     */
    private function callback($sCall, $aParams = array())
    {
        static $aModules = array();

        // Lets get the module and method we plan on calling
        $aParts1 = explode('.', $sCall);
        $sModule = $aParts1[0];
        $sMethod = $aParts1[1];

        if (strpos($sModule, '_'))
        {
            $aParts = explode('_', $sModule);
            $sModule = $aParts[0];
            $sMethod = $sMethod . ucfirst(strtolower($aParts[1]));
            if (isset($aParts[2]))
            {
                $sMethod .= '_' . ucfirst(strtolower($aParts[2]));
            }
        }

        // Have we cached the object?
        if (!isset($aModules[$sModule]))
        {
            // Make sure its a valid/enabled module
            if (!Phpfox::isModule($sModule))
            {
                echo json_encode(array(
                    'error_message' => 'Invalid module: ' . $sModule,
                    'error_code' => 1
                ));
                die;
            }

            // Cache the object and get the callback service
            $aModules[$sModule] = Phpfox::getService($sModule . '.callback');
        }

        if (!isset($aModules[$sModule]))
        {
            return array();
        }

        $aResult = array();
        
        switch ($sModule) {
            case 'music':
                switch ($sMethod) {
                    case 'getNotificationSong_Like':
                        $aResult = Phpfox::getService('mfox.song')->doSongGetNotificationSong_Like($aParams);

                        break;
                    case 'getNotificationAlbum_Like':
                        $aResult = Phpfox::getService('mfox.album')->doAlbumGetNotificationAlbum_Like($aParams);
                        break;
                    default:
                        break;
                }
            
            case 'video':
                switch ($sMethod) {
                    case 'getNotificationLike':
                        $aResult = Phpfox::getService('mfox.video')->doVideoGetNotificationLike($aParams);
                        break;

                    default:
                        break;
                }
                break;
            
            case 'pages':
                switch ($sMethod) {
                    case 'getNotificationComment_Feed':
                        $aResult = Phpfox::getService('mfox.pages')->doPagesGetNotificationComment_Feed($aParams);
                        break;
                    default:
                        break;
                }
                break;

            case 'friend':
                switch ($sMethod) {
                    case 'getNotificationAccepted':
                        $aResult = Phpfox::getService('mfox.friend')->doFriendGetNotificationAccepted($aParams);
                        break;

                    default:
                        break;
                }


                break;

            case 'feed':
                switch ($sMethod) {
                    case 'getNotificationComment_Profile':
                        $aResult = Phpfox::getService('mfox.feed')->doFeedGetNotificationComment_Profile($aParams);

                        break;
                    case 'getNotificationMini_Like':
                        $aResult = Phpfox::getService('mfox.feed')->doFeedGetNotificationMini_Like($aParams);
                        
                        break;

                    default:
                        break;
                }
                break;
            case 'event':
                switch ($sMethod) {
                    case 'getNotificationLike':
                        $aResult = Phpfox::getService('mfox.event')->doEventGetNotificationLike($aParams);
                        break;

                    case 'getNotificationComment':
                        $aResult = Phpfox::getService('mfox.event')->doEventGetNotificationComment($aParams);

                        break;

                    case 'getNotificationComment_Like':
                        $aResult = Phpfox::getService('mfox.event')->doEventGetNotificationComment_Like($aParams);

                        break;
                    
                    case 'getNotificationComment_Feed':
                        $aResult = Phpfox::getService('mfox.event')->doEventGetNotificationComment_Feed($aParams);
                        break;
                    
                    default:
                        break;
                }
                break;

            case 'photo':
                switch ($sMethod) {
                    case 'getNotificationLike':
                        $aResult = Phpfox::getService('mfox.photo')->doPhotoGetNotificationLike($aParams);
                        break;
                    case 'getNotificationAlbum_Like':
                        $aResult = Phpfox::getService('mfox.photo')->doPhotoAlbumGetNotificationAlbum_Like($aParams);
                        break;
                    default:
                        break;
                }
                break;

            case 'comment':
                switch ($sMethod) {
                    case 'getNotificationUser_Status':
                        $aResult = array(
                            'link' => array('iUserId' => $aParams['user_id'], 'sView' => 'profile'),
                            'message' => '',
                        );
                        break;

                    case 'getNotificationPhoto':
                        $aResult = array(
                            'link' => array('iItemId' => $aParams['item_id'], 'sView' => 'photo'),
                            'message' => '',
                        );
                        break;

                    default:
                        break;
                }
                break;

            case 'user':
                switch ($sMethod) {
                    case 'getCommentNotificationStatus':
                        $aResult = Phpfox::getService('mfox.user')->doUserGetCommentNotificationStatusTag($aParams);
                        break;

                    case 'getNotificationStatus_Like':
                        $aResult = Phpfox::getService('mfox.user')->doUserGetNotificationStatus_Like($aParams);
                        break;

                    default:
                        break;
                }
                break;


            default:
                break;
        }

        // Update method and module.
        $aResult['sMethod'] = $sMethod;
        $aResult['sModule'] = $sModule;

        return $aResult;
    }

    /**
     * Input data: N/A
     * 
     * Output data:
     * + iNumberOfFriendRequest: int.
     * + iNumberOfMessage: int.
     * + iNumberNotification: int.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see notification/get
     * 
     * @param array $aData
     * @return array
     */
    public function get($aData)
    {
        $iNumberOfFriendRequest = Phpfox::getService('friend.request')->getUnseenTotal();
        $iNumberOfMessage = Phpfox::getService('mail')->getUnseenTotal();
        $iNumberNotification = Phpfox::getService('notification')->getUnseenTotal();

        $aError = Phpfox_Error::get();
        if (count($aError))
        {
            return array(
                'error_message' => $aError,
                'error_code' => 1,
                'result' => 0
            );
        }

        return array(
            'iNumberOfFriendRequest' => $iNumberOfFriendRequest,
            'iNumberOfMessage' => $iNumberOfMessage,
            'iNumberNotification' => $iNumberNotification
        );
    }

    /**
     * @see Mail_Service_Mail
     * 
     * Input data: N/A
     * 
     * Output data:
     * + iMailId: int.
     * + sTitle: string.
     * + sPreview: string.
     * + iUserId: int.
     * + sFullName: string.
     * + sUserImage: string.
     * + iTimeStamp: int.
     * + sTime: string.
     * + sTimeConverted: string.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see notification/message
     * 
     * @param array $aData
     * @return array
     */
    public function message($aData)
    {
        $aMessages = Phpfox::getService('mail')->getLatest();

        $aResult = array();
        foreach ($aMessages as $aMessage)
        {
            $aResult[] = array(
                'iMailId' => $aMessage['mail_id'],
                'sTitle' => $aMessage['subject'],
                'sPreview' => $aMessage['preview'],
                'iUserId' => $aMessage['user_id'],
                'sFullName' => $aMessage['full_name'],
                'sUserImage' => Phpfox::getLib('image.helper')->display(array(
                    'server_id' => $aMessage['user_server_id'],
                    'path' => 'core.url_user',
                    'file' => $aMessage['user_image'],
                    'suffix' => MAX_SIZE_OF_USER_IMAGE,
                    'return_url' => true
                )),
                'iTimeStamp' => $aMessage['time_stamp'],
                'sTime' => date('l, F j, o', (int) $aMessage['time_stamp']) . ' at ' . date('h:i a', (int) $aMessage['time_stamp']),
                'sTimeConverted' => Phpfox::getLib('date')->convertTime($aMessage['time_stamp'], 'comment.comment_time_stamp')
            );
        }

        return $aResult;
    }

    /**
     * @see Friend_Service_Request_Request
     * 
     * Input data: N/A
     * 
     * Output data:
     * + iRequestId: int.
     * + iUserId: int.
     * + sFullName: string.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see notification/friendrequested
     * 
     * @param array $aData
     * @return array
     */
    public function friendrequested($aData)
    {
        list($iCnt, $aFriends) = Phpfox::getService('friend.request')->get();
        
        $aResult = array();
        
        foreach ($aFriends as $aFriend)
        {
            $aResult[] = array(
                'iRequestId' => $aFriend['request_id'],
                'iUserId' => $aFriend['user_id'],
                'sFullName' => $aFriend['full_name']
            );
        }

        return $aResult;
    }
    
    /**
     * @see Notification_Service_Notification
     * 
     * Input data: N/A
     * 
     * Output data:
     * + iNotificationId: int.
     * + sMessage: string.
     * + aLink: array.
     * + sCallbackModule: string.
     * + sCallbackMethod: string.
     * + iUserId: int.
     * + iOwnerUserId: int.
     * + sFullName: string.
     * + sUserName: string.
     * + sTypeId: string.
     * + iItemId: int.
     * + iIsSeen: int.
     * + iItemUserId: int.
     * + iTotalExtra: int.
     * + iProfilePageId: int.
     * + sFriendModule: string.
     * + iGender: int.
     * + sIcon: string.
     * + sUserImage: string.
     * + iTimeStamp: int.
     * + sTime: string.
     * + sTimeConverted: string.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see notification/notification
     * 
     * @param array $aData
     * @return array
     */
    public function notification($aData)
    {
        /**
         * @var array
         */
        $aGetRows = $this->database()->select('n.*, n.user_id as item_user_id, COUNT(n.notification_id) AS total_extra, ' . Phpfox::getUserField())
                ->from(Phpfox::getT('notification'), 'n')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = n.owner_user_id')
                ->innerJoin('(SELECT * FROM ' . Phpfox::getT('notification') . ' AS n WHERE n.user_id = ' . Phpfox::getUserId() . ' ORDER BY n.time_stamp DESC)', 'ninner', 'ninner.notification_id = n.notification_id')
                ->where('n.user_id = ' . Phpfox::getUserId() . '')
                ->group('n.type_id, n.item_id')
                ->order('n.is_seen ASC, n.time_stamp DESC')
                ->limit(5)
                ->execute('getSlaveRows');
        /**
         * @var array
         */
        $aRows = array();
        foreach ($aGetRows as $aGetRow)
        {
            $aRows[(int) $aGetRow['notification_id']] = $aGetRow;
        }

        arsort($aRows);

        // Call the callback function.
        $aNotifications = array();
        
        foreach ($aRows as $aRow)
        {
            $aParts1 = explode('.', $aRow['type_id']);
            $sModule = $aParts1[0];
            if (strpos($sModule, '_'))
            {
                $aParts = explode('_', $sModule);
                $sModule = $aParts[0];
            }

            if (Phpfox::isModule($sModule))
            {
                if ((int) $aRow['total_extra'] > 1)
                {
                    $aExtra = $this->database()->select('n.owner_user_id, n.time_stamp, n.is_seen, u.full_name')
                            ->from(Phpfox::getT('notification'), 'n')
                            ->join(Phpfox::getT('user'), 'u', 'u.user_id = n.owner_user_id')
                            ->where('n.type_id = \'' . $this->database()->escape($aRow['type_id']) . '\' AND n.item_id = ' . (int) $aRow['item_id'])
                            ->group('u.user_id')
                            ->order('n.time_stamp DESC')
                            ->limit(10)
                            ->execute('getSlaveRows');

                    foreach ($aExtra as $iKey => $aExtraUser)
                    {
                        if ($aExtraUser['owner_user_id'] == $aRow['user_id'])
                        {
                            unset($aExtra[$iKey]);
                        }

                        if (!$aRow['is_seen'] && $aExtraUser['is_seen'])
                        {
                            unset($aExtra[$iKey]);
                        }
                    }

                    if (count($aExtra))
                    {
                        $aRow['extra_users'] = $aExtra;
                    }
                }

                $aCallBack = array();

                if (substr($aRow['type_id'], 0, 8) != 'comment_' && !Phpfox::hasCallback($aRow['type_id'], 'getNotification'))
                {
                    $aCallBack['link'] = '#';
                    $aCallBack['message'] = '2. Notification is missing a callback. [' . $aRow['type_id'] . '::getNotification]';
                    $aCallBack['sModule'] = '';
                    $aCallBack['sMethod'] = '';
                }
                elseif (substr($aRow['type_id'], 0, 8) == 'comment_' && substr($aRow['type_id'], 0, 12) != 'comment_feed' && !Phpfox::hasCallback(substr_replace($aRow['type_id'], '', 0, 8), 'getCommentNotification'))
                {
                    $aCallBack['link'] = '#';
                    $aCallBack['message'] = 'Notification is missing a callback. [' . substr_replace($aRow['type_id'], '', 0, 8) . '::getCommentNotification]';
                    $aCallBack['sModule'] = '';
                    $aCallBack['sMethod'] = '';
                }
                elseif (Phpfox::hasCallback($aRow['type_id'], 'getNotification'))
                {
                    $aCallBack = $this->callback($aRow['type_id'] . '.getNotification', $aRow);

                    if (count($aCallBack) == 2)
                    {
                        $this->database()->delete(Phpfox::getT('notification'), 'notification_id = ' . (int) $aRow['notification_id']);
                    }
                }
                else
                {
                    $aCallBack = $this->_getExtraCallback($aRow);
                }

                $aNotifications[] = array_merge($aRow, (array) $aCallBack);
            }

            $this->database()->update(Phpfox::getT('notification'), array('is_seen' => '1'), 'type_id = \'' . $this->database()->escape($aRow['type_id']) . '\' AND item_id = ' . (int) $aRow['item_id']);
        }

        $aResult = array();

        foreach ($aNotifications as $aNotification)
        {
            $aResult[] = array(
                'iNotificationId' => $aNotification['notification_id'],
                'sMessage' => $aNotification['message'],
                'aLink' => $aNotification['link'],
                'sCallbackModule' => $aNotification['sModule'],
                'sCallbackMethod' => $aNotification['sMethod'],
                'iUserId' => $aNotification['user_id'],
                'iOwnerUserId' => $aNotification['owner_user_id'],
                'sFullName' => $aNotification['full_name'],
                'sUserName' => $aNotification['user_name'],
                'sTypeId' => $aNotification['type_id'],
                'iItemId' => $aNotification['item_id'],
                'iIsSeen' => $aNotification['is_seen'],
                'iItemUserId' => $aNotification['item_user_id'],
                'iTotalExtra' => $aNotification['total_extra'],
                'iProfilePageId' => $aNotification['profile_page_id'],
                'sFriendModule' => $aNotification['final_module'],
                'iGender' => $aNotification['gender'],
                'sIcon' => isset($aNotification['icon']) ? $aNotification['icon'] : '',
                'sUserImage' => Phpfox::getLib('image.helper')->display(array(
                        'server_id' => $aNotification['user_server_id'],
                        'path' => 'core.url_user',
                        'file' => $aNotification['user_image'],
                        'suffix' => MAX_SIZE_OF_USER_IMAGE,
                        'return_url' => true
                    )
                ),
                'iTimeStamp' => $aNotification['time_stamp'],
                'sTime' => date('l, F j, o', (int) $aNotification['time_stamp']) . ' at ' . date('h:i a', (int) $aNotification['time_stamp']),
                'sTimeConverted' => Phpfox::getLib('date')->convertTime($aNotification['time_stamp'], 'comment.comment_time_stamp')
            );
        }

        return $aResult;
    }

    /**
     * Get extra callback.
     * @param array $aRow
     * @return array
     */
    private function _getExtraCallback($aRow)
    {
        if (!isset($aRow['type_id']))
        {
            return array();
        }
        
        $aCallBack = array();
        
        switch ($aRow['type_id']) {
            case 'comment_photo':
                $aCallBack = Phpfox::getService('mfox.photo')->doPhotoGetCommentNotification($aRow);
                break;
            case 'comment_user_status':
                $aCallBack = Phpfox::getService('mfox.user')->doUserGetCommentNotificationStatus($aRow);
                break;
            case 'comment_photo_album':
                $aCallBack = Phpfox::getService('mfox.photo')->doPhotoAlbumGetCommentNotificationAlbum($aRow);
                break;
            case 'comment_video':
                $aCallBack = Phpfox::getService('mfox.video')->doVideoGetCommentNotification($aRow);
                break;
            case 'comment_music_song':
                $aCallBack = Phpfox::getService('mfox.song')->doSongGetCommentNotificationSong($aRow);
                break;
            case 'comment_music_album':
                $aCallBack = Phpfox::getService('mfox.album')->doMusicAlbumGetCommentNotificationAlbum($aRow);
                break;
        }
        
        return $aCallBack;
    }
    
    /**
     * Input data:
     * + iAmountOfNotification: int, optional.
     * + iLastNotificationTimeStamp: int, optional.
     * 
     * Output data:
     * + iNotificationId: int.
     * + sMessage: string.
     * + aLink: array.
     * + sCallbackModule: string.
     * + sCallbackMethod: string.
     * + iUserId: int.
     * + iOwnerUserId: int.
     * + sFullName: string.
     * + sUserName: string.
     * + sTypeId: string.
     * + iItemId: int.
     * + iIsSeen: int.
     * + iItemUserId: int.
     * + iTotalExtra: int.
     * + iProfilePageId: int.
     * + sFriendModule: string.
     * + iGender: int.
     * + sIcon: string.
     * + sUserImage: string.
     * + iTimeStamp: int.
     * + sTime: string.
     * + sTimeConverted: string.
     * 
     * @see Mobile - API phpFox/Api V3.0
     * @see notification/getall
     * 
     * @param array $aData
     * @return array
     */
    public function getall($aData)
    {
        /**
         * @var int
         */
        $iPage = 0;
        /**
         * @var int
         */
        $iPageTotal = isset($aData['iAmountOfNotification']) ? (int) $aData['iAmountOfNotification'] : 20;
        /**
         * @var int
         */
        $iLastNotificationTimeStamp = isset($aData['iLastNotificationTimeStamp']) ? (int) $aData['iLastNotificationTimeStamp'] : 0;
        /**
         * @var string
         */
        $sCond = $iLastNotificationTimeStamp > 0 ? ' AND n.time_stamp < ' . $iLastNotificationTimeStamp : '';
        /**
         * @var int
         */
        $iCnt = $this->database()->select('COUNT(*)')
                ->from(Phpfox::getT('notification'), 'n')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = n.owner_user_id')
                ->where('n.user_id = ' . Phpfox::getUserId() . '' . $sCond)
                ->execute('getSlaveField');
        /**
         * @var array
         */
        $aRows = $this->database()->select('n.*, n.user_id as item_user_id, ' . Phpfox::getUserField())
                ->from(Phpfox::getT('notification'), 'n')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = n.owner_user_id')
                ->where('n.user_id = ' . Phpfox::getUserId() . '' . $sCond)
                ->order('n.time_stamp DESC')
                ->limit($iPage, $iPageTotal, $iCnt)
                ->execute('getSlaveRows');

        $sIds = '';
        /**
         * @var array
         */
        $aNotifications = array();
        foreach ($aRows as $aRow)
        {
            $sIds .= (int) $aRow['notification_id'] . ',';

            $aParts1 = explode('.', $aRow['type_id']);
            $sModule = $aParts1[0];
            if (strpos($sModule, '_'))
            {
                $aParts = explode('_', $sModule);
                $sModule = $aParts[0];
            }
            
            $aCallBack = array();
            
            if (Phpfox::isModule($sModule))
            {
                if (substr($aRow['type_id'], 0, 8) != 'comment_' && !Phpfox::hasCallback($aRow['type_id'], 'getNotification'))
                {
                    $aCallBack['link'] = '#';
                    $aCallBack['message'] = '2. Notification is missing a callback. [' . $aRow['type_id'] . '::getNotification]';
                    $aCallBack['sModule'] = '';
                    $aCallBack['sMethod'] = '';
                }
                elseif (substr($aRow['type_id'], 0, 8) == 'comment_' && substr($aRow['type_id'], 0, 12) != 'comment_feed' && !Phpfox::hasCallback(substr_replace($aRow['type_id'], '', 0, 8), 'getCommentNotification'))
                {
                    $aCallBack['link'] = '#';
                    $aCallBack['message'] = 'Notification is missing a callback. [' . substr_replace($aRow['type_id'], '', 0, 8) . '::getCommentNotification]';
                    $aCallBack['sModule'] = '';
                    $aCallBack['sMethod'] = '';
                }
                elseif (Phpfox::hasCallback($aRow['type_id'], 'getNotification'))
                {
                    $aCallBack = $this->callback($aRow['type_id'] . '.getNotification', $aRow);
                }
                else
                {
                    $aCallBack = $this->_getExtraCallback($aRow);
                }

                $aNotifications[] = array_merge($aRow, (array) $aCallBack);
            }
        }

        $sIds = rtrim($sIds, ',');

        if (!empty($sIds))
        {
            $this->database()->update(Phpfox::getT('notification'), array('is_seen' => '1'), 'notification_id IN(' . $sIds . ')');
        }

        $aResult = array();

        foreach ($aNotifications as $aNotification)
        {
            $aResult[] = array(
                'iNotificationId' => $aNotification['notification_id'],
                'sMessage' => $aNotification['message'],
                'aLink' => $aNotification['link'],
                'sCallbackModule' => $aNotification['sModule'],
                'sCallbackMethod' => $aNotification['sMethod'],
                'iUserId' => $aNotification['user_id'],
                'iOwnerUserId' => $aNotification['owner_user_id'],
                'sFullName' => $aNotification['full_name'],
                'sUserName' => $aNotification['user_name'],
                'sTypeId' => $aNotification['type_id'],
                'iItemId' => $aNotification['item_id'],
                'iIsSeen' => $aNotification['is_seen'],
                'iItemUserId' => $aNotification['item_user_id'],
                'iTotalExtra' => $aNotification['total_extra'],
                'iProfilePageId' => $aNotification['profile_page_id'],
                'sFriendModule' => $aNotification['final_module'],
                'iGender' => $aNotification['gender'],
                'sIcon' => isset($aNotification['icon']) ? $aNotification['icon'] : '',
                'sUserImage' => Phpfox::getLib('image.helper')->display(array(
                        'server_id' => $aNotification['user_server_id'],
                        'path' => 'core.url_user',
                        'file' => $aNotification['user_image'],
                        'suffix' => MAX_SIZE_OF_USER_IMAGE,
                        'return_url' => true
                    )
                ),
                'iTimeStamp' => $aNotification['time_stamp'],
                'sTime' => date('l, F j, o', (int) $aNotification['time_stamp']) . ' at ' . date('h:i a', (int) $aNotification['time_stamp']),
                'sTimeConverted' => Phpfox::getLib('date')->convertTime($aNotification['time_stamp'], 'comment.comment_time_stamp')
            );
        }

        return $aResult;
    }

    /**
     * Input data:
     * + iNotificationId: int, required.
     * 
     * Output data:
     * + iNotificationId: int.
     * + sMessage: string.
     * + aLink: array.
     * + sCallbackModule: string.
     * + sCallbackMethod: string.
     * + iUserId: int.
     * + iOwnerUserId: int.
     * + sFullName: string.
     * + sUserName: string.
     * + sTypeId: string.
     * + iItemId: int.
     * + iIsSeen: int.
     * + iItemUserId: int.
     * + iTotalExtra: int.
     * + iProfilePageId: int.
     * + sFriendModule: string.
     * + iGender: int.
     * + sIcon: string.
     * + sUserImage: string.
     * + iTimeStamp: int.
     * + sTime: string.
     * + sTimeConverted: string.
     * 
     * @see Mobile - API phpFox/Api V3.0
     * @see notification/getOneNotification
     * 
     * @param array $aData
     * @return array
     */
    public function getOneNotification($aData)
    {
        /**
         * @var int
         */
        $iNotificationId = isset($aData['iNotificationId']) ? (int) $aData['iNotificationId'] : 0;
        
        /**
         * @var int
         */
        $iCnt = $this->database()
                ->select('COUNT(*)')
                ->from(Phpfox::getT('notification'), 'n')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = n.owner_user_id')
                ->where('n.user_id = ' . Phpfox::getUserId() . ' AND n.notification_id = ' . $iNotificationId)
                ->execute('getSlaveField');
        /**
         * @var array
         */
        if ($iCnt > 0)
        {
            $aRow = $this->database()
                    ->select('n.*, n.user_id as item_user_id, ' . Phpfox::getUserField())
                    ->from(Phpfox::getT('notification'), 'n')
                    ->join(Phpfox::getT('user'), 'u', 'u.user_id = n.owner_user_id')
                    ->where('n.user_id = ' . Phpfox::getUserId() . ' AND n.notification_id = ' . $iNotificationId)
                    ->execute('getRow');
        }
        
        if ($aRow == null)
        {
            return array();
        }
        
        /**
         * @var array
         */
        $aNotification = array();
        $aParts1 = explode('.', $aRow['type_id']);
        $sModule = $aParts1[0];
        if (strpos($sModule, '_'))
        {
            $aParts = explode('_', $sModule);
            $sModule = $aParts[0];
        }

        $aCallBack = array();

        if (Phpfox::isModule($sModule))
        {
            if (substr($aRow['type_id'], 0, 8) != 'comment_' && !Phpfox::hasCallback($aRow['type_id'], 'getNotification'))
            {
                $aCallBack['link'] = '#';
                $aCallBack['message'] = '2. Notification is missing a callback. [' . $aRow['type_id'] . '::getNotification]';
                $aCallBack['sModule'] = '';
                $aCallBack['sMethod'] = '';
            }
            elseif (substr($aRow['type_id'], 0, 8) == 'comment_' && substr($aRow['type_id'], 0, 12) != 'comment_feed' && !Phpfox::hasCallback(substr_replace($aRow['type_id'], '', 0, 8), 'getCommentNotification'))
            {
                $aCallBack['link'] = '#';
                $aCallBack['message'] = 'Notification is missing a callback. [' . substr_replace($aRow['type_id'], '', 0, 8) . '::getCommentNotification]';
                $aCallBack['sModule'] = '';
                $aCallBack['sMethod'] = '';
            }
            elseif (Phpfox::hasCallback($aRow['type_id'], 'getNotification'))
            {
                $aCallBack = $this->callback($aRow['type_id'] . '.getNotification', $aRow);
            }
            else
            {
                $aCallBack = $this->_getExtraCallback($aRow);
            }

            $aNotification = array_merge($aRow, (array) $aCallBack);
        }
        
        $this->database()->update(Phpfox::getT('notification'), array('is_seen' => '1'), 'notification_id IN(' . $aRow['notification_id'] . ')');
        
        return array(
            'iNotificationId' => $aNotification['notification_id'],
            'sMessage' => $aNotification['message'],
            'aLink' => $aNotification['link'],
            'sCallbackModule' => $aNotification['sModule'],
            'sCallbackMethod' => $aNotification['sMethod'],
            'iUserId' => $aNotification['user_id'],
            'iOwnerUserId' => $aNotification['owner_user_id'],
            'sFullName' => $aNotification['full_name'],
            'sUserName' => $aNotification['user_name'],
            'sTypeId' => $aNotification['type_id'],
            'iItemId' => $aNotification['item_id'],
            'iIsSeen' => $aNotification['is_seen'],
            'iItemUserId' => $aNotification['item_user_id'],
            'iTotalExtra' => $aNotification['total_extra'],
            'iProfilePageId' => $aNotification['profile_page_id'],
            'sFriendModule' => $aNotification['final_module'],
            'iGender' => $aNotification['gender'],
            'sIcon' => isset($aNotification['icon']) ? $aNotification['icon'] : '',
            'sUserImage' => Phpfox::getLib('image.helper')->display(array(
                    'server_id' => $aNotification['user_server_id'],
                    'path' => 'core.url_user',
                    'file' => $aNotification['user_image'],
                    'suffix' => MAX_SIZE_OF_USER_IMAGE,
                    'return_url' => true
                )
            ),
            'iTimeStamp' => $aNotification['time_stamp'],
            'sTime' => date('l, F j, o', (int) $aNotification['time_stamp']) . ' at ' . date('h:i a', (int) $aNotification['time_stamp']),
            'sTimeConverted' => Phpfox::getLib('date')->convertTime($aNotification['time_stamp'], 'comment.comment_time_stamp')
        );
    }
    
}
