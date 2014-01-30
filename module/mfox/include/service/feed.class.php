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
 * @since May 27, 2013
 * @link Mfox Api v1.0
 */
class Mfox_Service_Feed extends Phpfox_Service {

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->_sTable = Phpfox::getT('feed');
    }

    /**
     * Input data:
     * + module: string.
     * + item_id: int.
     * + table_prefix: string.
     * + feed_comment: string. Type of the feed.
     * 
     * Callback for feed.
     * @var array Callback data
     */
    public $_aCallback;
    
    /**
     *
     * @var array 
     */
    public $_aViewMoreFeeds;

    /**
     * Set table.
     * @param string $sTable
     */
    public function setTable($sTable)
    {
        $this->_sTable = $sTable;
    }

    /**
     * For restful method.
     * 
     * Input data:
     * + iPage: int, optional. Not use.
     * + iLastTime: int, optional.
     * + iAmountOfFeed: int, optional.
     * + sAction: string, optional.
     * + iUserId: int, optional. Not use.
     * + sOrder: string, optional. Ex: "time_stamp" or "time_update".
     * 
     * Output data:
     * + id: int.
     * + iUserId: int.
     * + sUsername: string.
     * + UserProfileImg_Url: string.
     * + sFullName: string.
     * + bCanPostComment: bool.
     * + sPhotoUrl: string.
     * + aAlbum: array. (iAlbumId: int, sAlbumTitle: string).
     * + bReadMore: bool.
     * + sContent: string.
     * + sDescription: string.
	 * + iTimeStamp: int.
	 * + iTimeUpdate: int.
     * + Time: string.
     * + TimeConverted: string.
     * + sTypeId: string.
     * + iItemId: int.
     * + iLikeId: int.
     * 
     * @see Mobile - API phpFox/Api V1.0 - Restful - Get method.
     * @see get
     * 
     * @param array $aData
     * @return string
     */
    function getAction($aData)
    {
        return $this->getMore($aData);
    }
    
    /**
     * Input data:
     * + iItemId: int, optional. It is "iFeedId".
     * 
     * Output data:
	 * + iUserId: int.
	 * + sUsername: string.
	 * + iFeedId: int.
	 * + UserProfileImg_Url: string.
	 * + sFullName: string.
	 * + sContent: string.
	 * + timestamp: int.
	 * + Time: string.
	 * + TimeConverted: string.
	 * + sTypeId: string.
	 * + iItemId: int.
	 * + iLikeId: int.
	 * + iTotalLike: int.
     * 
     * @param array $aData
     * @param int $iFeedId Feed id. Use to replace 'iItemId'. Optional.
     * @return array
     */
    function getByIdAction($aData, $iId)
    {
        return $this->view($aData, $iId);
    }

    /**
     * @see Mfox_Service_Token
     *
     * @see User_Service_Privacy_Privacy
     *
     * @see Mfox_Service_Privacy
     *
     * @see Pages_Service_Pages
     *
     * @see Feed_Service_Feed
     *
     * 
     * 
     * Input data:
     * + iPage: int, optional. Not use.
     * + iLastTime: int, optional.
     * + iAmountOfFeed: int, optional.
     * + sAction: string, optional.
     * + iUserId: int, optional. Not use.
     * + sOrder: string, optional. Ex: "time_stamp" or "time_update".
     * 
     * Output data:
	 * + id: int.
	 * + iUserId: int.
	 * + sUsername: string.
	 * + UserProfileImg_Url: string.
	 * + sFullName: string.
	 * + bCanPostComment: bool.
	 * + timestamp: int.
	 * + Time: string.
	 * + TimeConverted: string.
	 * + sTypeId: string.
	 * + iItemId: int.
	 * + sPhotoUrl: string.
	 * + aAlbum: array (iAlbumId: int, sAlbumTitle: string)
	 * + bReadMore: bool.
	 * + sContent: string.
	 * + sDescription: string.
	 * + iLikeId: int.
     * 
     * @param array $aData {"iUserId":"1","LastFeedIdViewed":"1","amountOfFeed":"5"}
     * @return array
     */
    public function get($aData)
    {
        return $this->getMore($aData);
    }
    
    /**
     * @see Mfox_Service_Token
     * @see User_Service_Privacy_Privacy
     * @see Mfox_Service_Privacy
     * @see Pages_Service_Pages
     * @see Feed_Service_Feed
     * 
     * Input data:
     * + iPage: int, optional.
     * + LastFeedIdViewed: int, optional.
     * + amountOfFeed: int, optional.
     * + sAction: string, optional.
     * 
     * Output data:
	 * + id: int.
	 * + iUserId: int.
	 * + sUsername: string.
	 * + UserProfileImg_Url: string.
	 * + sFullName: string.
	 * + bCanPostComment: bool.
	 * + iTimeStamp: int.
	 * + iTimeUpdate: int.
	 * + Time: string.
	 * + TimeConverted: string.
	 * + sTypeId: string.
	 * + iItemId: int.
	 * + sPhotoUrl: string.
	 * + aAlbum: array (iAlbumId: int, sAlbumTitle: string)
	 * + bReadMore: bool.
	 * + sContent: string.
	 * + sDescription: string.
	 * + iLikeId: int.
     * 
     * @see Mobile - API phpFox/Api V1.0
     * @see feed/getNew
     * 
     * @param array $aData {"iUserId":"1","LastFeedIdViewed":"1","amountOfFeed":"5"}
     */
    public function getNew($aData)
    {
        $aData['sAction'] = 'new';
        
        return $this->getMore($aData);
    }

    /**
     * Input data:
     * + module: string.
     * + item_id: int.
     * + table_prefix: string.
     * + feed_comment: string. Type of the feed.
     * 
     * @param array $aData
     * @return Mfox_Service_Feed
     */
    public function setCallback($aData)
    {
        $this->_aCallback = $aData;

        return $this;
    }

    /**
     * Clear callback.
     * 
     * @return \Mfox_Service_Feed
     */
    public function clearCallback()
    {
        $this->_aCallback = null;

        return $this;
    }

    /**
     * @see Mfox_Service_Token
     * @see User_Service_Privacy_Privacy
     * @see Mfox_Service_Privacy
     * @see Pages_Service_Pages
     * @see Feed_Service_Feed
     * 
     * Input data:
     * + iPage: int, optional. Not use.
     * + iLastTime: int, optional.
     * + iAmountOfFeed: int, optional.
     * + sAction: string, optional.
     * + iUserId: int, optional. Not use.
     * + sOrder: string, optional. Ex: "time_stamp" or "time_update".
     * 
     * 
     * Output data:
	 * + id: int.
	 * + iUserId: int.
	 * + sUsername: string.
	 * + UserProfileImg_Url: string.
	 * + sFullName: string.
	 * + bCanPostComment: bool.
	 * + iTimeStamp: int.
	 * + iTimeUpdate: int.
	 * + Time: string.
	 * + TimeConverted: string.
	 * + sTypeId: string.
	 * + iItemId: int.
	 * + sPhotoUrl: string.
	 * + aAlbum: array (iAlbumId: int, sAlbumTitle: string)
	 * + bReadMore: bool.
	 * + sContent: string.
	 * + sDescription: string.
	 * + iLikeId: int.
     * 
     * @see Mobile - API phpFox/Api V1.0
     * @see feed/getMore
     * 
     * @param array $aData {"iUserId":"1","LastFeedIdViewed":"1","amountOfFeed":"5"}
     */
    public function getMore($aData)
    {
        /**
         * @var int Page will be set by user. Not use.
         */
        $iPage = isset($aData['iPage']) ? (int) $aData['iPage'] : 0;

        /**
         * @var string
         */
        $sAction = isset($aData['sAction']) ? $aData['sAction'] : 'more';
        
        /**
         * @var int
         */
        $iUserId = isset($aData['iUserId']) ? (int) $aData['iUserId'] : null;
        
        /**
         * @var int
         */
        $iPhpfoxUserId = Phpfox::getUserId();
        
        /**
         * @var string
         */
        $sUserField = Phpfox::getUserField();
        
        /**
         * @var bool
         */
        $bIsUser = Phpfox::isUser();

        /**
         * Amount of feed will be set by user.
         * @var int
         */
        $iTotalFeeds = (int) isset($aData['iAmountOfFeed']) ? (int) $aData['iAmountOfFeed'] : 10;

        /**
         * @var int
         */
        $iOffset = (int) ($iPage * $iTotalFeeds);
        
        /**
         * @var int
         */
        $iLastTime = isset($aData['iLastTime']) ? (int) $aData['iLastTime'] : 0;
        
        /**
         * @var string
         */
        $sOrder = isset($aData['sOrder']) && $aData['sOrder'] == 'time_stamp' ? 'feed.time_stamp DESC' : 'feed.time_update DESC';
        
        if (isset($aData['sOrder']) && $aData['sOrder'] == 'time_stamp')
        {
            if ($sAction == 'new')
            {
                $sCond = $iLastTime > 0 ? ' AND feed.time_stamp > \'' . $iLastTime . '\'' : '';
            }
            else
            {
                $sCond = $iLastTime > 0 ? ' AND feed.time_stamp < \'' . $iLastTime . '\'' : '';
            }
        }
        else
        {
            if ($sAction == 'new')
            {
                $sCond = $iLastTime > 0 ? ' AND feed.time_update > \'' . $iLastTime . '\'' : '';
            }
            else
            {
                $sCond = $iLastTime > 0 ? ' AND feed.time_update < \'' . $iLastTime . '\'' : '';
            }
        }
        
        
        /**
         * @var array
         */
        $aCond = array();
        if (isset($this->_aCallback['module']))
        {
            $aNewCond = array();

            $aNewCond[] = 'AND feed.parent_user_id = ' . (int) $this->_aCallback['item_id'];
            if ($iUserId !== null)
            {
                $aNewCond[] = 'AND feed.user_id = ' . (int) $iUserId;
            }
            $aNewCond[] = $sCond;

            $aRows = $this->database()->select('feed.*, ' . Phpfox::getUserField() . ', u.view_id')
                    ->from(Phpfox::getT($this->_aCallback['table_prefix'] . 'feed'), 'feed')
                    ->join(Phpfox::getT('user'), 'u', 'u.user_id = feed.user_id')
                    ->where($aNewCond)
                    ->order($sOrder)
                    ->limit($iOffset, $iTotalFeeds)
                    ->execute('getSlaveRows');
        }
        elseif ($iUserId !== NULL)
        {
            if (!Phpfox::getService('user.privacy')->hasAccess($iUserId, 'feed.view_wall'))
            {
                return array(
                    'error_code' => 1,
                    'error_message' => " You don't have permission to view feed on wall! "
                );
            }

            $aCond[] = $sCond;

            if ($iUserId == Phpfox::getUserId())
            {
                $aCond[] = 'AND feed.privacy IN(0,1,2,3,4)';
            }
            else
            {
                if (Phpfox::getService('user')->getUserObject($iUserId)->is_friend)
                {
                    $aCond[] = 'AND feed.privacy IN(0,1,2)';
                }
                else if (Phpfox::getService('user')->getUserObject($iUserId)->is_friend_of_friend)
                {
                    $aCond[] = 'AND feed.privacy IN(0,2)';
                }
                else
                {
                    $aCond[] = 'AND feed.privacy IN(0)';
                }
            }

            $this->database()->select('feed.*')
                    ->from(Phpfox::getT('feed'), 'feed')
                    ->where(array_merge($aCond, array('AND type_id = \'feed_comment\' AND feed.user_id = ' . (int) $iUserId . '')))
                    ->union();

            $this->database()->select('feed.*')
                    ->from(Phpfox::getT('feed'), 'feed')
                    ->where(array_merge($aCond, array('AND feed.user_id = ' . (int) $iUserId . ' AND feed.parent_user_id = 0')))
                    ->union();

            if (Phpfox::isUser())
            {
                if (Phpfox::isModule('privacy'))
                {
                    $this->database()->join(Phpfox::getT('privacy'), 'p', 'p.module_id = feed.type_id AND p.item_id = feed.item_id')
                            ->join(Phpfox::getT('friend_list_data'), 'fld', 'fld.list_id = p.friend_list_id AND fld.friend_user_id = ' . Phpfox::getUserId() . '');
                }
                $this->database()->select('feed.*')
                        ->from(Phpfox::getT('feed'), 'feed')
                        ->where('feed.privacy IN(4) AND feed.user_id = ' . (int) $iUserId . ' AND feed.feed_reference = 0' . $sCond)
                        ->union();
            }

            $this->database()->select('feed.*')
                    ->from(Phpfox::getT('feed'), 'feed')
                    ->where(array_merge($aCond, array('AND feed.parent_user_id = ' . (int) $iUserId)))
                    ->union();

            $aRows = $this->database()->select('feed.*, apps.app_title,  ' . Phpfox::getUserField())
                    ->unionFrom('feed')
                    ->join(Phpfox::getT('user'), 'u', 'u.user_id = feed.user_id')
                    ->leftJoin(Phpfox::getT('app'), 'apps', 'apps.app_id = feed.app_id')
                    ->where(' TRUE ' . $sCond)
                    ->order('feed.time_stamp DESC')
                    ->group('feed.feed_id')
                    ->limit($iOffset, $iTotalFeeds)
                    ->execute('getSlaveRows');
        }
        else
        {
            // Users must be active within 7 days or we skip their activity feed
			$iLastActiveTimeStamp = ((int) Phpfox::getParam('feed.feed_limit_days') <= 0 ? 0 : (PHPFOX_TIME - (86400 * Phpfox::getParam('feed.feed_limit_days'))));			
			
            if (Phpfox::isModule('privacy') && Phpfox::getUserParam('privacy.can_view_all_items'))
            {
                $aRows = $this->database()->select('feed.*, f.friend_id AS is_friend, apps.app_title, ' . $sUserField)
                        ->from(Phpfox::getT('feed'), 'feed')
                        ->join(Phpfox::getT('user'), 'u', 'u.user_id = feed.user_id')
                        ->leftJoin(Phpfox::getT('friend'), 'f', 'f.user_id = feed.user_id AND f.friend_user_id = ' . $iPhpfoxUserId)
                        ->leftJoin(Phpfox::getT('app'), 'apps', 'apps.app_id = feed.app_id')
                        ->order($sOrder)
                        ->group('feed.feed_id')
                        ->limit($iOffset, $iTotalFeeds)
                        ->where('feed.time_stamp > \'' . $iLastActiveTimeStamp . '\' AND feed.feed_reference = 0' . $sCond)
                        ->execute('getSlaveRows');
            }
            else
            {
                if (Phpfox::getParam('feed.feed_only_friends'))
                {
                    // Get my friends feeds
                    $this->database()->select('feed.*')
                            ->from(Phpfox::getT('feed'), 'feed')
                            ->join(Phpfox::getT('friend'), 'f', 'f.user_id = feed.user_id AND f.friend_user_id = ' . $iPhpfoxUserId)
                            ->where('feed.privacy IN(0,1,2) AND feed.time_stamp > \'' . $iLastActiveTimeStamp . '\' AND feed.feed_reference = 0 ' . $sCond)
                            ->union();

                    // Get my feeds
                    $this->database()->select('feed.*')
                            ->from(Phpfox::getT('feed'), 'feed')
                            ->where('feed.privacy IN(0,1,2,3,4) AND feed.user_id = ' . Phpfox::getUserId() . ' AND feed.time_stamp > \'' . $iLastActiveTimeStamp . '\' AND feed.feed_reference = 0 ' . $sCond)
                            ->union();
                }
                else
                {
                    // Get my friends feeds
                    $this->database()->select('feed.*')
                            ->from(Phpfox::getT('feed'), 'feed')
                            ->join(Phpfox::getT('friend'), 'f', 'f.user_id = feed.user_id AND f.friend_user_id = ' . $iPhpfoxUserId)
                            ->where('feed.privacy IN(1,2) AND feed.time_stamp > \'' . $iLastActiveTimeStamp . '\' AND feed.feed_reference = 0 ' . $sCond)
                            ->union();

                    // Get my friends of friends feeds
                    $this->database()->select('feed.*')
                            ->from(Phpfox::getT('feed'), 'feed')
                            ->join(Phpfox::getT('friend'), 'f1', 'f1.user_id = feed.user_id')
                            ->join(Phpfox::getT('friend'), 'f2', 'f2.user_id = ' . Phpfox::getUserId() . ' AND f2.friend_user_id = f1.friend_user_id')
                            ->where('feed.privacy IN(2) AND feed.time_stamp > \'' . $iLastActiveTimeStamp .  '\' AND feed.feed_reference = 0 ' . $sCond)
                            ->union();

                    // Get my feeds
                    $this->database()->select('feed.*')
                            ->from(Phpfox::getT('feed'), 'feed')
                            ->where('feed.privacy IN(1,2,3,4) AND feed.user_id = ' . Phpfox::getUserId() . ' AND feed.time_stamp > \'' . $iLastActiveTimeStamp . '\' AND feed.feed_reference = 0 ' . $sCond )
                            ->union();

                    // Get public feeds
                    $this->database()->select('feed.*')
                            ->from(Phpfox::getT('feed'), 'feed')
                            ->where('feed.privacy IN(0) AND feed.time_stamp > \'' . $iLastActiveTimeStamp . '\' AND feed.feed_reference = 0 ' . $sCond)
                            ->union();
                    
                    if (Phpfox::isModule('privacy'))
                    {
                        $this->database()->join(Phpfox::getT('privacy'), 'p', 'p.module_id = feed.type_id AND p.item_id = feed.item_id')
                            ->join(Phpfox::getT('friend_list_data'), 'fld', 'fld.list_id = p.friend_list_id AND fld.friend_user_id = ' . Phpfox::getUserId() . '');
                    }

                    // Get feeds based on custom friends lists
                    $this->database()->select('feed.*')
                            ->from(Phpfox::getT('feed'), 'feed')
                            ->where('feed.privacy IN(4) AND feed.time_stamp > \'' . $iLastActiveTimeStamp . '\' AND feed.feed_reference = 0 ' . $sCond)
                            ->union();
                }
                
                $aRows = $this->database()->select('feed.*, f.friend_id AS is_friend, apps.app_title, u.view_id,  ' . $sUserField)
                        ->unionFrom('feed')
                        ->join(Phpfox::getT('user'), 'u', 'u.user_id = feed.user_id')
                        ->leftJoin(Phpfox::getT('friend'), 'f', 'f.user_id = feed.user_id AND f.friend_user_id = ' . $iPhpfoxUserId)
                        ->leftJoin(Phpfox::getT('app'), 'apps', 'apps.app_id = feed.app_id')
                        ->where(' TRUE ' . $sCond)
                        ->order($sOrder)
                        ->group('feed.feed_id')
                        ->limit($iOffset, $iTotalFeeds)
                        ->execute('getSlaveRows');
            }
        }
        /**
         * @var bool
         */
        $bFirstCheckOnComments = false;
        if (Phpfox::getParam('feed.allow_comments_on_feeds') && $bIsUser && Phpfox::isModule('comment'))
        {
            $bFirstCheckOnComments = true;
        }
        
        $aFeedLoop = $aRows;
        $aFeeds = array();

        foreach ($aFeedLoop as $sKey => $aRow)
        {
            $aRow['feed_time_stamp'] = $aRow['time_stamp'];

            if (($aReturn = $this->_processFeed($aRow, $sKey, $iUserId, $bFirstCheckOnComments)))
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

        if (count($aFeeds) == 0)
        {
            return $aFeeds;
        }
        
        $aResult = array();
        foreach ($aFeeds as $aElement)
        {
            if (is_file(Phpfox::getParam('core.dir_pic') . 'user' . PHPFOX_DS . sprintf($aElement['user_image'], MAX_SIZE_OF_USER_IMAGE)))
            {
                $sUserProfileImg_Url = Phpfox::getLib('image.helper')->display(array(
                    'server_id' => $aElement['user_server_id'],
                    'path' => 'core.url_user',
                    'file' => $aElement['user_image'],
                    'suffix' => '_50_square',
                    'return_url' => true
                        )
                );
            }
            else
            {
                $sUserProfileImg_Url = Phpfox::getParam('core.path') . "theme/frontend/default/style/default/image/noimage/profile_50.png";
            }
            
            /**
             * @var array
             */
            $aTemp = array(
                'id' => $aElement['feed_id'],
                'iUserId' => $aElement['user_id'],
                'sUsername' => $aElement['user_name'],
                'UserProfileImg_Url' => $sUserProfileImg_Url,
                'sFullName' => $aElement['full_name'],
                'bCanPostComment' => true,
                'iTimeStamp' => $aElement['time_stamp'],
                'iTimeUpdate' => $aElement['time_update'],
                'Time' => date('l, F j, o', (int) $aElement['time_stamp']) . ' at ' . date('h:i a', (int) $aElement['time_stamp']),
                'TimeConverted' => Phpfox::getLib('date')->convertTime($aElement['time_stamp'], 'comment.comment_time_stamp'),
                'sTypeId' => $aElement['type_id'],
                'iItemId' => $aElement['item_id']
            );
            
            /**
             * @var string
             */
            $sDescription = '';
            /**
             * @var string
             */
            $sContent = '';

            switch ($aElement['type_id']) {
                case 'user_photo':
                    $aTemp['sPhotoUrl'] = Phpfox::getLib('image.helper')->display(array(
                        'server_id' => $aElement['user_server_id'],
                        'path' => 'core.url_user',
                        'file' => $aElement['user_image'],
                        'suffix' => '',
                        'return_url' => true
                            )
                    );
                    $sContent = Phpfox::getPhrase('feed.updated_gender_profile_photo', array('gender' => Phpfox::getService('user')->gender(Phpfox::getUserBy('gender'), 1)));
                    break;

                case 'user_status':
                    $sContent = $this->getContentOfUserStatus($aElement);
                    break;
                
                case 'feed_comment':
                    $sContent = $this->getContentOfFeedComment($aElement);
                    break;
                
                case 'event':
                    list($sContent, $sDescription) = $this->getContentOfEvent($aElement);
                    break;
                
                case 'event_comment':
                    $sContent = $aElement['feed_status'];
                    break;
                
                case 'photo':
                    /**
                     * @var array
                     */
                    $aPhoto = Phpfox::getService('photo')->getPhoto($aElement['item_id']);

                    $aTemp['bCanPostComment'] = Phpfox::getService('mfox.comment')->checkCanPostComment(array(
                        'comment_type_id' => 'photo',
                        'privacy' => $aPhoto['privacy'],
                        'comment_privacy' => $aPhoto['privacy_comment'],
                        'like_type_id' => 'photo',
                        'feed_is_liked' => $aPhoto['is_liked'],
                        'feed_is_friend' => $aPhoto['is_friend'],
                        'item_id' => $aPhoto['photo_id'],
                        'user_id' => $aPhoto['user_id'],
                        'total_comment' => $aPhoto['total_comment'],
                        'total_like' => $aPhoto['total_like'],
                        'feed_link' => Phpfox::getLib('url')->permalink('photo', $aPhoto['photo_id'], $aPhoto['title']),
                        'feed_title' => $aPhoto['title'],
                        'feed_display' => 'view',
                        'feed_total_like' => $aPhoto['total_like'],
                        'report_module' => 'photo',
                        'report_phrase' => Phpfox::getPhrase('photo.report_this_photo'))
                    );

                    if (isset($aPhoto['photo_id']))
                    {
                        $aTemp['sPhotoUrl'] = Phpfox::getLib('image.helper')->display(array(
                            'server_id' => $aPhoto['server_id'],
                            'path' => 'photo.url_photo',
                            'file' => $aPhoto['destination'],
                            'suffix' => '_500',
                            'return_url' => true
                                )
                        );

                        $aTemp['aAlbum'] = array(
                            'iAlbumId' => $aPhoto['album_id'], 
                            'sAlbumTitle' => $aPhoto['album_title']
                        );
                    }
                    else
                    {
                        $aTemp['sPhotoUrl'] = Phpfox::getParam('core.path') . "theme/frontend/default/style/default/image/noimage/item.png";
                    }
                    break;
                
                case 'music_song':
                    $sContent = $aElement['feed_title'];
                    $sDescription = $aElement['feed_content'];
                    $sFeedInfo = $aElement['feed_info'];
                    break;
                
                case 'music_album':
                    $sContent = $aElement['feed_title'];
                    $sDescription = $aElement['feed_content'];
                    $sFeedInfo = $aElement['feed_info'];
                    break;
                
                case 'video':
                    $sContent = $aElement['feed_title'];
                    $sDescription = $aElement['feed_content'];
                    
                    // Get the image of video.
                    $aVideo = Phpfox::getService('video')->getVideo($aElement['item_id']);
                    $aTemp['sVideoImage'] = Phpfox::getLib('image.helper')->display(array(
                        'server_id' => $aVideo['image_server_id'],
                        'path' => 'video.url_image',
                        'file' => $aVideo['image_path'],
                        'suffix' => '_120',
                        'return_url' => true
                            )
                    );
                    
                    break;
                    
                case 'music_song_comment':
                    
                    break;
                    
                default:
                    $sContent = $aElement['feed_content'];
                    break;
            }
            list($aTemp['bReadMore'], $aTemp['sContent']) = $this->word_limiter($sContent, 30);
            $aTemp['sDescription'] = $sDescription;
            $aTemp['sFeedInfo'] = $sFeedInfo;
            $aTemp['iLikeId'] = $this->checkIsLiked($aElement);
            

            $aResult[] = $aTemp;
        }

        return $aResult;
    }

    /**
     * Input data:
     * + type_id: string, required.
     * + item_id: int, required.
     * 
     * Output data:
     * + like_id: int.
     * 
     * @param array $aItem
     * @return array
     */
    public function checkIsLiked($aItem)
    {
        /**
         * @var array
         */
        $aLike = $this->database()
                ->select('l.like_id')
                ->from(Phpfox::getT('like'), 'l')
                ->where('l.type_id = \'' . $this->database()->escape($aItem['type_id']) . '\' AND l.item_id = ' . (int) $aItem['item_id'] . ' AND l.user_id = ' . Phpfox::getUserId())
                ->execute('getRow');

        return isset($aLike['like_id']) ? $aLike['like_id'] : null;
    }

    /**
     * Input data:
     * + item_id: int, required.
     * 
     * Output data:
     * + content: string.
     * 
     * @param array $aItem
     * @return string
     */
    public function getContentOfFeedComment($aItem)
    {
        /**
         * @var array
         */
        $aRow = $this->database()->select('fc.*, l.like_id AS is_liked')
                ->from(Phpfox::getT('feed_comment'), 'fc')
                ->leftJoin(Phpfox::getT('like'), 'l', 'l.type_id = \'feed_comment\' AND l.item_id = fc.feed_comment_id AND l.user_id = ' . Phpfox::getUserId())
                ->where('fc.feed_comment_id = ' . (int) $aItem['item_id'])
                ->execute('getSlaveRow');

        if (isset($aRow['content']))
        {
            return $aRow['content'];
        }

        return '';
    }

    /**
     * Input data:
     * + item_id: int, required.
     * 
     * Output data:
     * + title: string.
     * + description_parsed: string.
     * 
     * @param array $aItem
     * @return array
     */
    public function getContentOfEvent($aItem)
    {
        /**
         * @var array
         */
        $aRow = $this->database()
                ->select('e.title, et.description_parsed')
                ->from(Phpfox::getT('event'), 'e')
                ->leftJoin(Phpfox::getT('event_text'), 'et', 'e.event_id = et.event_id')
                ->where('e.event_id = ' . (int) $aItem['item_id'])
                ->execute('getSlaveRow');

        if (isset($aRow['title']))
        {
            return array($aRow['title'], $aRow['description_parsed']);
        }

        return '';
    }

    /**
     * Input data:
     * + item_id: int, required.
     * 
     * Output data:
     * + content: string.
     * 
     * @param array $aItem
     * @return string
     */
    public function getContentOfUserStatus($aItem)
    {
        /**
         * @var array
         */
        $aRow = $this->database()->select('us.*, l.like_id AS is_liked')
                ->from(Phpfox::getT('user_status'), 'us')
                ->leftJoin(Phpfox::getT('like'), 'l', 'l.type_id = \'user_status\' AND l.item_id = us.status_id AND l.user_id = ' . Phpfox::getUserId())
                ->where('us.status_id = ' . (int) $aItem['item_id'])
                ->execute('getSlaveRow');

        if (isset($aRow['content']))
        {
            return $aRow['content'];
        }

        return '';
    }

    /**
     * Limit the string.
     * @param string $str
     * @param int $limit
     * @param string $end_char
     * @return array (bool, string)
     */
    public function word_limiter($str, $limit = 100, $end_char = '&#8230;')
    {
        if (trim($str) == '')
        {
            return $str;
        }

        preg_match('/^\s*+(?:\S++\s*+){1,' . (int) $limit . '}/', $str, $matches);
        /**
         * @var bool
         */
        $bReadMore = true;
        if (strlen($str) == strlen($matches[0]))
        {
            $end_char = '';
            $bReadMore = false;
        }

        return array($bReadMore, rtrim($matches[0]) . $end_char);
    }

    /**
     * Input data:
     * + iItemId: int, optional. It is "iFeedId".
     * 
     * Output data:
	 * + iUserId: int.
	 * + sUsername: string.
	 * + iFeedId: int.
	 * + UserProfileImg_Url: string.
	 * + sFullName: string.
	 * + sContent: string.
	 * + timestamp: int.
	 * + Time: string.
	 * + TimeConverted: string.
	 * + sTypeId: string.
	 * + iItemId: int.
	 * + iLikeId: int.
	 * + iTotalLike: int.
     * 
     * @see Mobile - API phpFox/Api V1.0 - POST method.
     * @see view
     * 
     * @param array $aData
     * @return array
     */
    public function postAction($aData)
    {
        return $this->view($aData);
    }

    /**
     * Input data:
     * + comment-id: int, optional.
     * + status-id: int, optional.
     * + link-id: int, optional.
     * + plink-id: int, optional.
     * + poke-id: int, optional.
     * + year: int, optional.
     * + month: int, optional.
     * + ids: string, optional.
     * 
     * Output data:
	 * + feed_id: int.
	 * + app_id: int.
	 * + privacy: int.
	 * + privacy_comment: int.
	 * + type_id: int.
	 * + user_id: int.
	 * + parent_user_id: int.
	 * + item_id: int.
	 * + time_stamp: int.
	 * + feed_reference: int.
	 * + parent_feed_id: int.
	 * + parent_module_id: string.
	 * + time_update: string.
	 * + is_friend: bool.
	 * + app_title: string.
	 * + view_id: int.
	 * + profile_page_id: int.
	 * + user_server_id: int.
	 * + user_name: string.
	 * + full_name: string.
	 * + gender: int.
	 * + user_image: string.
	 * + is_invisible: bool.
	 * + user_group_id: int.
	 * + language_id: int.
	 * + feed_time_stamp: int.
	 * + can_post_comment: bool.
	 * + feed_title: string.
	 * + feed_title_sub: string.
	 * + feed_info: string.
	 * + feed_link: string.
	 * + feed_icon: string.
	 * + enable_like: bool.
	 * + feed_image: string.
	 * + bShowEnterCommentBlock: bool.
	 * + feed_month_year: string.
	 * + likes: array.
	 * + total_likes: int.
	 * + feed_like_phrase: string.
	 * + feed_is_liked: bool.
	 * + feed_total_like: int.
     * 
     * @see Mobile - API phpFox/Api V1.0.
     * @see feed/getfeed
     * 
     * @param array $aData
     * @param int $iUserId
     * @param int $iFeedId
     * @param int $iPage
     * @param bool $bForceReturn
     * @return array
     */
    public function getfeed($aData, $iUserId = null, $iFeedId = null, $iPage = 0, $bForceReturn = false)
    {
        if (isset($aData['comment-id']) && ($iCommentId = (int) $aData['comment-id']))
        {
            if (isset($this->_aCallback['feed_comment']))
            {
                $aCustomCondition = array('feed.type_id = \'' . $this->_aCallback['feed_comment'] . '\' AND feed.item_id = ' . (int) $iCommentId . ' AND feed.parent_user_id = ' . (int) $this->_aCallback['item_id']);
            }
            else
            {
                $aCustomCondition = array('feed.type_id IN(\'feed_comment\', \'feed_egift\') AND feed.item_id = ' . (int) $iCommentId . ' AND feed.parent_user_id = ' . (int) $iUserId);
            }

            $iFeedId = true;
        }
        elseif (isset($aData['status-id']) && ($iStatusId = (int) $aData['status-id']))
        {
            $aCustomCondition = array('feed.type_id = \'user_status\' AND feed.item_id = ' . (int) $iStatusId . ' AND feed.user_id = ' . (int) $iUserId);
            $iFeedId = true;
        }
        elseif (isset($aData['link-id']) && ($iLinkId = (int) $aData['link-id']))
        {
            $aCustomCondition = array('feed.type_id = \'link\' AND feed.item_id = ' . (int) $iLinkId . ' AND feed.user_id = ' . (int) $iUserId);
            $iFeedId = true;
        }
        elseif (isset($aData['plink-id']) && ($iLinkId = $aData['plink-id']))
        {
            $aCustomCondition = array('feed.type_id = \'link\' AND feed.item_id = ' . (int) $iLinkId . ' AND feed.parent_user_id  = ' . (int) $iUserId);
            $iFeedId = true;
        }
        elseif (isset($aData['poke-id']) && ($iPokeId = $aData['poke-id']))
        {
            $aCustomCondition = array('feed.type_id = \'poke\' AND feed.item_id = ' . (int) $iPokeId . ' AND feed.user_id = ' . (int) $iUserId);
            $iFeedId = true;
        }

        $iTotalFeeds = (int) Phpfox::getComponentSetting(($iUserId === null ? Phpfox::getUserId() : $iUserId), 'feed.feed_display_limit_' . ($iUserId !== null ? 'profile' : 'dashboard'), Phpfox::getParam('feed.feed_display_limit'));

        $iOffset = (int) ($iPage * $iTotalFeeds);

        $sOrder = 'feed.time_update DESC';
        if (Phpfox::getUserBy('feed_sort') || defined('PHPFOX_IS_USER_PROFILE'))
        {
            $sOrder = 'feed.time_stamp DESC';
        }

        $aCond = array();
        if (isset($this->_aCallback['module']))
        {
            $aNewCond = array();
            if (isset($aData['comment-id']) && ($iCommentId = $aData['comment-id']))
            {
                if (!isset($this->_aCallback['feed_comment']))
                {
                    $aCustomCondition = array('feed.type_id = \'' . $this->_aCallback['module'] . '_comment\' AND feed.item_id = ' . (int) $iCommentId . '');
                }
            }
            $aNewCond[] = 'AND feed.parent_user_id = ' . (int) $this->_aCallback['item_id'];
            if ($iUserId !== null && $iFeedId !== null)
            {
                $aNewCond[] = 'AND feed.feed_id = ' . (int) $iFeedId . ' AND feed.user_id = ' . (int) $iUserId;
            }

            $iTimelineYear = 0;
            if (isset($aData['year']) && ($iTimelineYear = (int) $aData['year']) && !empty($iTimelineYear))
            {
                $iMonth = 12;
                $iDay = 31;
                if (isset($aData['month']) && ($iTimelineMonth = (int) $aData['month']) && !empty($iTimelineMonth))
                {
                    $iMonth = $iTimelineMonth;
                    $iDay = Phpfox::getLib('date')->lastDayOfMonth($iMonth, $iTimelineYear);
                }
                $aNewCond[] = 'AND feed.time_stamp <= \'' . mktime(0, 0, 0, $iMonth, $iDay, $iTimelineYear) . '\'';
            }

            $aRows = $this->database()->select('feed.*, ' . Phpfox::getUserField() . ', u.view_id')
                    ->from(Phpfox::getT($this->_aCallback['table_prefix'] . 'feed'), 'feed')
                    ->join(Phpfox::getT('user'), 'u', 'u.user_id = feed.user_id')
                    ->where((isset($aCustomCondition) ? $aCustomCondition : $aNewCond))
                    ->order($sOrder)
                    ->limit($iOffset, $iTotalFeeds)
                    ->execute('getSlaveRows');
        }
        elseif (isset($aData['ids']) && ($sIds = $aData['ids']))
        {
            $aParts = explode(',', $aData['ids']);
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
        elseif ($iUserId === null && $iFeedId !== null)
        {
            $aRows = $this->database()->select('feed.*, ' . Phpfox::getUserField() . ', u.view_id')
                    ->from($this->_sTable, 'feed')
                    ->join(Phpfox::getT('user'), 'u', 'u.user_id = feed.user_id')
                    ->where('feed.feed_id = ' . (int) $iFeedId)
                    ->order('feed.time_stamp DESC')
                    ->execute('getSlaveRows');
        }
        elseif ($iUserId !== null && $iFeedId !== null)
        {
            $aRows = $this->database()->select('feed.*, apps.app_title, ' . Phpfox::getUserField() . ', u.view_id')
                    ->from($this->_sTable, 'feed')
                    ->join(Phpfox::getT('user'), 'u', 'u.user_id = feed.user_id')
                    ->leftJoin(Phpfox::getT('app'), 'apps', 'apps.app_id = feed.app_id')
                    ->where((isset($aCustomCondition) ? $aCustomCondition : 'feed.feed_id = ' . (int) $iFeedId . ' AND feed.user_id = ' . (int) $iUserId . ''))
                    ->order('feed.time_stamp DESC')
                    ->limit(1)
                    ->execute('getSlaveRows');
        }
        elseif ($iUserId !== null)
        {
            if ($iUserId == Phpfox::getUserId())
            {
                $aCond[] = 'AND feed.privacy IN(0,1,2,3,4)';
            }
            else
            {
                if (Phpfox::getService('user')->getUserObject($iUserId)->is_friend)
                {
                    $aCond[] = 'AND feed.privacy IN(0,1,2)';
                }
                else if (Phpfox::getService('user')->getUserObject($iUserId)->is_friend_of_friend)
                {
                    $aCond[] = 'AND feed.privacy IN(0,2)';
                }
                else
                {
                    $aCond[] = 'AND feed.privacy IN(0)';
                }
            }

            $iTimelineYear = 0;

            if (isset($aData['year']) && ($iTimelineYear = (int) $aData['year']) && !empty($iTimelineYear))
            {
                $iMonth = 12;
                $iDay = 31;
                if (isset($aData['month']) && ($iTimelineMonth = $aData['month']) && !empty($iTimelineMonth))
                {
                    $iMonth = $iTimelineMonth;
                    $iDay = Phpfox::getLib('date')->lastDayOfMonth($iMonth, $iTimelineYear);
                }
                $aCond[] = 'AND feed.time_stamp <= \'' . mktime(0, 0, 0, $iMonth, $iDay, $iTimelineYear) . '\'';
            }

            $this->database()->select('feed.*')
                    ->from($this->_sTable, 'feed')
                    ->where(array_merge($aCond, array('AND type_id = \'feed_comment\' AND feed.user_id = ' . (int) $iUserId . '')))
                    ->union();

            $this->database()->select('feed.*')
                    ->from($this->_sTable, 'feed')
                    ->where(array_merge($aCond, array('AND feed.user_id = ' . (int) $iUserId . ' AND feed.feed_reference = 0 AND feed.parent_user_id = 0')))
                    ->union();

            if (Phpfox::isUser())
            {
                if (Phpfox::isModule('privacy'))
                {
                    $this->database()->join(Phpfox::getT('privacy'), 'p', 'p.module_id = feed.type_id AND p.item_id = feed.item_id')
                            ->join(Phpfox::getT('friend_list_data'), 'fld', 'fld.list_id = p.friend_list_id AND fld.friend_user_id = ' . Phpfox::getUserId() . '');
                }
                $this->database()->select('feed.*')
                        ->from($this->_sTable, 'feed')
                        ->where('feed.privacy IN(4) AND feed.user_id = ' . (int) $iUserId . ' AND feed.feed_reference = 0')
                        ->union();
            }

            $this->database()->select('feed.*')
                    ->from($this->_sTable, 'feed')
                    ->where(array_merge($aCond, array('AND feed.parent_user_id = ' . (int) $iUserId)))
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

            if (Phpfox::isModule('privacy') && Phpfox::getUserParam('privacy.can_view_all_items'))
            {
                $aRows = $this->database()->select('feed.*, f.friend_id AS is_friend, apps.app_title, ' . Phpfox::getUserField())
                        ->from(Phpfox::getT('feed'), 'feed')
                        ->join(Phpfox::getT('user'), 'u', 'u.user_id = feed.user_id')
                        ->leftJoin(Phpfox::getT('friend'), 'f', 'f.user_id = feed.user_id AND f.friend_user_id = ' . Phpfox::getUserId())
                        ->leftJoin(Phpfox::getT('app'), 'apps', 'apps.app_id = feed.app_id')
                        ->order($sOrder)
                        ->group('feed.feed_id')
                        ->limit($iOffset, $iTotalFeeds)
                        ->where('feed.time_stamp > \'' . $iLastActiveTimeStamp . '\' AND feed.feed_reference = 0')
                        ->execute('getSlaveRows');
            }
            else
            {
                if (Phpfox::getParam('feed.feed_only_friends'))
                {
                    // Get my friends feeds
                    $this->database()->select('feed.*')
                            ->from($this->_sTable, 'feed')
                            ->join(Phpfox::getT('friend'), 'f', 'f.user_id = feed.user_id AND f.friend_user_id = ' . Phpfox::getUserId())
                            ->where('feed.privacy IN(0,1,2) AND feed.time_stamp > \'' . $iLastActiveTimeStamp . '\' AND feed.feed_reference = 0')
                            ->limit($iTotalFeeds)
                            ->union();

                    // Get my feeds
                    $this->database()->select('feed.*')
                            ->from($this->_sTable, 'feed')
                            ->where('feed.privacy IN(0,1,2,3,4) AND feed.user_id = ' . Phpfox::getUserId() . ' AND feed.time_stamp > \'' . $iLastActiveTimeStamp . '\' AND feed.feed_reference = 0')
                            ->limit($iTotalFeeds)
                            ->union();
                }
                else
                {
                    $sMyFeeds = '1,2,3,4';

                    // Get my friends feeds
                    $this->database()->select('feed.*')
                            ->from($this->_sTable, 'feed')
                            ->join(Phpfox::getT('friend'), 'f', 'f.user_id = feed.user_id AND f.friend_user_id = ' . Phpfox::getUserId())
                            ->where('feed.privacy IN(1,2) AND feed.time_stamp > \'' . $iLastActiveTimeStamp . '\' AND feed.feed_reference = 0')
                            ->union();

                    // Get my friends of friends feeds
                    $this->database()->select('feed.*')
                            ->from($this->_sTable, 'feed')
                            ->join(Phpfox::getT('friend'), 'f1', 'f1.user_id = feed.user_id')
                            ->join(Phpfox::getT('friend'), 'f2', 'f2.user_id = ' . Phpfox::getUserId() . ' AND f2.friend_user_id = f1.friend_user_id')
                            ->where('feed.privacy IN(2) AND feed.time_stamp > \'' . $iLastActiveTimeStamp . '\' AND feed.feed_reference = 0')
                            ->union();

                    // Get my feeds
                    $this->database()->select('feed.*')
                            ->from($this->_sTable, 'feed')
                            ->where('feed.privacy IN(' . $sMyFeeds . ') AND feed.user_id = ' . Phpfox::getUserId() . ' AND feed.time_stamp > \'' . $iLastActiveTimeStamp . '\' AND feed.feed_reference = 0')
                            ->union();

                    // Get public feeds
                    $this->database()->select('feed.*')
                            ->from($this->_sTable, 'feed')
                            ->where('feed.privacy IN(0) AND feed.time_stamp > \'' . $iLastActiveTimeStamp . '\' AND feed.feed_reference = 0')
                            ->union();

                    if (Phpfox::isModule('privacy'))
                    {
                        $this->database()->join(Phpfox::getT('privacy'), 'p', 'p.module_id = feed.type_id AND p.item_id = feed.item_id')
                                ->join(Phpfox::getT('friend_list_data'), 'fld', 'fld.list_id = p.friend_list_id AND fld.friend_user_id = ' . Phpfox::getUserId() . '');
                    }
                    // Get feeds based on custom friends lists	
                    $this->database()->select('feed.*')
                            ->from($this->_sTable, 'feed')
                            ->where('feed.privacy IN(4) AND feed.time_stamp > \'' . $iLastActiveTimeStamp . '\' AND feed.feed_reference = 0')
                            ->union();
                }

                $aRows = $this->database()->select('feed.*, f.friend_id AS is_friend, apps.app_title, u.view_id,  ' . Phpfox::getUserField())
                        ->unionFrom('feed')
                        ->join(Phpfox::getT('user'), 'u', 'u.user_id = feed.user_id')
                        ->leftJoin(Phpfox::getT('friend'), 'f', 'f.user_id = feed.user_id AND f.friend_user_id = ' . Phpfox::getUserId())
                        ->leftJoin(Phpfox::getT('app'), 'apps', 'apps.app_id = feed.app_id')
                        ->order($sOrder)
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

        $aFeedLoop = $aRows;

        $aFeeds = array();

        if (Phpfox::isModule('like'))
        {
            $oLike = Phpfox::getService('like');
        }
        foreach ($aFeedLoop as $sKey => $aRow)
        {
            $aRow['feed_time_stamp'] = $aRow['time_stamp'];

            if (($aReturn = $this->_processFeed($aRow, $sKey, $iUserId, $bFirstCheckOnComments)))
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

                if (isset($aReturn['like_type_id']) && isset($oLike))
                {
                    $aReturn['marks'] = $oLike->getActionsFor($aReturn['like_type_id'], (isset($aReturn['like_item_id']) ? $aReturn['like_item_id'] : $aReturn['item_id']));
                }

                /* Lets figure out the phrases for like.display right here */
                $this->getPhraseForLikes($aReturn);

                $aFeeds[] = $aReturn;
            }
        }

        if (((isset($aData['status-id']) && $aData['status-id'])
                || (isset($aData['comment-id']) && $aData['comment-id'])
                || (isset($aData['link-id']) && $aData['link-id'])
                || (isset($aData['poke-id']) && $aData['poke-id'])
                )
                && isset($aFeeds[0]))
        {
            $aFeeds[0]['feed_view_comment'] = true;
        }

        return $aFeeds;
    }

    /**
     * This function replaces the routine in the like.block.display template
     * @param array $aFeed
     * @return string
     */
    public function getPhraseForLikes(&$aFeed)
    {
        /**
         * @var string
         */
        $sPhrase = '';
        $oParse = Phpfox::getLib('phpfox.parse.output');

        if (Phpfox::isModule('like'))
        {
            $oLike = Phpfox::getService('like');
        }
        $oUrl = Phpfox::getLib('url');

        if (!isset($aFeed['likes']) && isset($oLike))
        {
            $aFeed['likes'] = $oLike->getLikesForFeed($aFeed['type_id'], $aFeed['item_id']);
            $aFeed['total_likes'] = count($aFeed['likes']);
        }
        if (isset($aFeed['feed_is_liked']) && $aFeed['feed_is_liked'])
        {
            if (count($aFeed['likes']) == 0)
            {
                $sPhrase = Phpfox::getPhrase('like.you');
            }
            else if (count($aFeed['likes']) == 1)
            {
                $sPhrase = Phpfox::getPhrase('like.you_and') . '&nbsp;';
            }
            else
            {
                $sPhrase = Phpfox::getPhrase('like.you_comma');
            }
        }
        else
        {
            $sPhrase = Phpfox::getPhrase('like.article_to_upper');
        }

        if (isset($aFeed['likes']) && is_array($aFeed['likes']) && count($aFeed['likes']))
        {
            foreach ($aFeed['likes'] as $iIteration => $aLike)
            {
                if ((isset($aFeed['feed_is_liked']) && $aFeed['feed_is_liked']) || $iIteration > 0)
                {
                    $sPhrase .= Phpfox::getPhrase('like.article_to_lower');
                }

                $sPhrase .= '<span class="user_profile_link_span" id="js_user_name_link_' . $aLike['user_name'] . '"><a href="' . $oUrl->makeUrl($aLike['user_name']) . '">' . $oParse->shorten($aLike['full_name'], 30) . '</a></span>'; //Phpfox::getParam('user.maximum_length_for_full_name'));
                if (count($aFeed['likes']) > 1 && (1 + $iIteration) == (count($aFeed['likes']) - 1) && isset($aFeed['feed_total_like']) && $aFeed['feed_total_like'] <= Phpfox::getParam('feed.total_likes_to_display'))
                {
                    $sPhrase .= '&nbsp;' . Phpfox::getPhrase('like.and') . '&nbsp;';
                }
                elseif (isset($aFeed['likes']) && (1 + $iIteration) != count($aFeed['likes']))
                {
                    $sPhrase .= ',&nbsp;';
                }
            }
        }

        if (isset($aFeed['feed_total_like']) && $aFeed['feed_total_like'] > Phpfox::getParam('feed.total_likes_to_display'))
        {
            $sPhrase .= '<a href="#" onclick="return $Core.box(\'like.browse\', 400, \'type_id=' . $aFeed['like_type_id'] . '&amp;item_id=' . $aFeed['item_id'] . '\');">';
            $iTotalLeftShow = ($aFeed['feed_total_like'] - Phpfox::getParam('feed.total_likes_to_display'));
            if ($iTotalLeftShow == 1)
            {
                $sPhrase .= '&nbsp;' . Phpfox::getPhrase('like.and') . '&nbsp;' . Phpfox::getPhrase('like.1_other_person') . '&nbsp;';
            }
            else
            {
                $sPhrase .= '&nbsp;' . Phpfox::getPhrase('like.and') . '&nbsp;' . number_format($iTotalLeftShow) . '&nbsp;' . Phpfox::getPhrase('like.others') . '&nbsp;';
            }
            $sPhrase .= '</a>' . Phpfox::getPhrase('like.likes_this');
        }
        else
        {
            if (isset($aFeed['likes']) && count($aFeed['likes']) > 1)
            {
                $sPhrase .= '&nbsp;' . Phpfox::getPhrase('like.like_this');
            }
            else
            {
                if (isset($aFeed['feed_is_liked']) && $aFeed['feed_is_liked'])
                {
                    if (count($aFeed['likes']) == 1)
                    {
                        $sPhrase .= '&nbsp;' . Phpfox::getPhrase('like.like_this');
                    }
                    else
                    {
                        if (count($aFeed['likes']) == 0)
                        {
                            $sPhrase .= '&nbsp;' . Phpfox::getPhrase('like.you_like');
                        }
                        else
                        {
                            $sPhrase .= Phpfox::getPhrase('like.likes_this');
                        }
                    }
                }
                else
                {
                    if (isset($aFeed['likes']) && count($aFeed['likes']) == 1)
                    {
                        $sPhrase .= '&nbsp;' . Phpfox::getPhrase('like.likes_this');
                    }
                    else if (strlen($sPhrase) > 1)
                    {
                        $sPhrase .= Phpfox::getPhrase('like.like_this');
                    }
                }
            }
        }

        $aActions = Phpfox::getService('like')->getActionsFor($aFeed['type_id'], $aFeed['item_id']);

        if (strlen($sPhrase) > 1 || count($aActions) > 0)
        {
            $aFeed['bShowEnterCommentBlock'] = true;
        }
        $sPhrase = str_replace('&nbsp;&nbsp;', '&nbsp;', $sPhrase);
        $aFeed['feed_like_phrase'] = $sPhrase;
        if (empty($sPhrase))
        {
            $aFeed['feed_is_liked'] = false;
            $aFeed['feed_total_like'] = 0;
        }

        return $sPhrase;
    }

    /**
     * Input data:
     * + iFeedId: int, optional.
     * + iItemId: int, required.
     * 
     * Output data:
     * + iUserId: int.
     * + sUsername: string.
     * + iFeedId: int.
     * + UserProfileImg_Url: string.
     * + sFullName: string.
     * + timestamp: int.
     * + Time: string.
     * + TimeConverted: string.
     * + sTypeId: string.
     * + iItemId: int.
     * + iLikeId: int.
     * + iTotalLike: int.
     * + sContent: string.
     * 
     * @param array $aData
     * @param int $iFeedId
     * @return array
     */
    public function getOneFeed($aData, $iFeedId = 0)
    {
        extract($aData, EXTR_SKIP);

        if ($iFeedId > 0)
        {
            $iItemId = $iFeedId;
        }

        $aCond = array();
        $aCond[] = 'feed.feed_id = ' . (int) $iItemId;
        /**
         * @var array
         */
        $aRow = $this->database()->select('feed.*, apps.app_title, ' . Phpfox::getUserField() . ', u.view_id, l.like_id')
                ->from(Phpfox::getT('feed'), 'feed')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = feed.user_id')
                ->leftJoin(Phpfox::getT('app'), 'apps', 'apps.app_id = feed.app_id')
                ->leftJoin(Phpfox::getT('like'), 'l', 'l.type_id = feed.type_id AND l.item_id = feed.item_id AND l.user_id = ' . Phpfox::getUserId())
                ->where($aCond)
                ->order('feed.time_stamp DESC')
                ->limit(1)
                ->execute('getSlaveRow');

        // Count the total like on feed.
        if (isset($aRow['feed_id']))
        {
            $iCount = (int) $this->database()->select('COUNT(l.like_id)')
                            ->from(Phpfox::getT('like'), 'l')
                            ->where('l.type_id = \'' . $this->database()->escape($aRow['type_id']) . '\' AND l.item_id = ' . (int) $aRow['item_id'])
                            ->execute('getfield');
        }
        else
        {
            return array(
                'error_code' => 1,
                'error_message' => " Feed is not valid or you don't have permission to view this feed!"
            );
        }


        if (isset($aRow['type_id']))
        {
            $aModule = explode('_', $aRow['type_id']);
            if (isset($aModule[0]) && Phpfox::isModule($aModule[0]) && Phpfox::hasCallback($aModule[0] . (isset($aModule[1]) ? '_' . $aModule[1] : ''), 'getReportRedirect'))
            {
                $aRow['report_module'] = $aRows[$iKey]['report_module'] = $aModule[0] . (isset($aModule[1]) ? '_' . $aModule[1] : '');
                $aRow['report_phrase'] = $aRows[$iKey]['report_phrase'] = Phpfox::getPhrase('feed.report_this_entry');
                $aRow['force_report'] = $aRows[$iKey]['force_report'] = true;
            }
        }
        /**
         * @var array
         */
        $aTemp = array(
            'iUserId' => $aRow['user_id'],
            'sUsername' => $aRow['user_name'],
            'iFeedId' => $aRow['feed_id'],
            'UserProfileImg_Url' => Phpfox::getLib('image.helper')->display(array(
                    'server_id' => $aRow['user_server_id'],
                    'path' => 'core.url_user',
                    'file' => $aRow['user_image'],
                    'suffix' => MAX_SIZE_OF_USER_IMAGE,
                    'return_url' => true
                )
            ),
            'sFullName' => $aRow['full_name'],
            'timestamp' => $aRow['time_stamp'],
            'Time' => date('l, F j, o', (int) $aRow['time_stamp']) . ' at ' . date('h:i a', (int) $aRow['time_stamp']),
            'TimeConverted' => Phpfox::getLib('date')->convertTime($aRow['time_stamp'], 'comment.comment_time_stamp'),
            'sTypeId' => $aRow['type_id'],
            'iItemId' => $aRow['item_id'],
            'iLikeId' => $aRow['like_id'],
            'iTotalLike' => $iCount
        );
        
        switch ($aRow['type_id']) {
            case 'user_status':
                $aTemp['sContent'] = $this->getContentOfUserStatus($aRow);
                break;
            case 'feed_comment':
                $aTemp['sContent'] = $this->getContentOfFeedComment($aRow);
                break;
            default:
                $aTemp['sContent'] = $aRow['feed_content'];
                break;
        }
        
        return $aTemp;
    }

    /**
     * Input data:
     * + iItemId: int, optional. It is "iFeedId".
     * 
     * Output data:
	 * + iUserId: int.
	 * + sUsername: string.
	 * + iFeedId: int.
	 * + UserProfileImg_Url: string.
	 * + sFullName: string.
	 * + sContent: string.
	 * + timestamp: int.
	 * + Time: string.
	 * + TimeConverted: string.
	 * + sTypeId: string.
	 * + iItemId: int.
	 * + iLikeId: int.
	 * + iTotalLike: int.
     * 
     * @see Mobile - API phpFox/Api V1.0
     * @see feed/view
     * 
     * @param array $aData
     * @param int $iFeedId Feed id. Use to replace 'iItemId'. Optional.
     * @return array
     */
    public function view($aData, $iFeedId = 0)
    {
        extract($aData, EXTR_SKIP);

        if ($iFeedId > 0)
        {
            $iItemId = $iFeedId;
        }

        $aCond = array();
        $aCond[] = 'feed.feed_id = ' . (int) $iItemId;
        
        if (!Phpfox::isAdmin())
        {
            $aCond[] = 'AND (
                feed.user_id = ' . Phpfox::getUserId() . '
                OR
                feed.privacy IN (0) 
                OR (
                    feed.privacy IN (1) 
                    AND 
                    feed.user_id IN (
                        SELECT fr.user_id 
                        FROM ' . Phpfox::getT('friend') . ' AS fr 
                        WHERE fr.friend_user_id = ' . Phpfox::getUserId() . '
                    )
                ) 
                OR (
                    feed.privacy IN (2) 
                    AND (
                        feed.user_id IN (
                            SELECT f.user_id 
                            FROM ' . Phpfox::getT('friend') . ' AS f 
                            INNER JOIN (
                                SELECT ffxf.friend_user_id 
                                FROM ' . Phpfox::getT('friend') . ' AS ffxf 
                                WHERE ffxf.is_page = 0 
                                AND ffxf.user_id = ' . Phpfox::getUserId() . '
                            ) AS sf ON sf.friend_user_id = f.friend_user_id 
                            JOIN ' . Phpfox::getT('user') . ' AS u ON u.user_id = f.friend_user_id
                        )
                        OR feed.user_id IN (
                            SELECT fr.user_id 
                            FROM ' . Phpfox::getT('friend') . ' AS fr 
                            WHERE fr.friend_user_id = ' . Phpfox::getUserId() . '
                        )
                    )
                )
                OR (
                    feed.privacy = 3 AND feed.user_id = ' . Phpfox::getUserId() . '
                ) 
                OR (
                    feed.privacy = 4 
                    AND ' . Phpfox::getUserId() . ' IN (
                        SELECT ' . Phpfox::getT('friend_list_data') . '.friend_user_id 
                        FROM  ' . Phpfox::getT('friend_list') . '
                        INNER JOIN ' . Phpfox::getT('privacy') . ' ON ' . Phpfox::getT('privacy') . '.friend_list_id = ' . Phpfox::getT('friend_list') . '.list_id 
                        INNER JOIN ' . Phpfox::getT('user') . ' ON ' . Phpfox::getT('user') . '.user_id = ' . Phpfox::getT('privacy') . '.user_id 
                        INNER JOIN ' . Phpfox::getT('friend_list_data') . ' ON ' . Phpfox::getT('friend_list') . '.list_id = ' . Phpfox::getT('friend_list_data') . '.list_id 
                        WHERE ' . Phpfox::getT('privacy') . '.module_id = feed.type_id AND ' . Phpfox::getT('privacy') . '.item_id = feed.item_id
                    )
                ) 
            )';
        }
        /**
         * @var array
         */
        $aRow = $this->database()->select('feed.*, apps.app_title, ' . Phpfox::getUserField() . ', u.view_id, l.like_id')
                ->from(Phpfox::getT('feed'), 'feed')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = feed.user_id')
                ->leftJoin(Phpfox::getT('app'), 'apps', 'apps.app_id = feed.app_id')
                ->leftJoin(Phpfox::getT('like'), 'l', 'l.type_id = feed.type_id AND l.item_id = feed.item_id AND l.user_id = ' . Phpfox::getUserId())
                ->where($aCond)
                ->order('feed.time_stamp DESC')
                ->limit(1)
                ->execute('getSlaveRow');

        // Count the total like on feed.
        if (isset($aRow['feed_id']))
        {
            $iCount = (int) $this->database()->select('COUNT(l.like_id)')
                            ->from(Phpfox::getT('like'), 'l')
                            ->where('l.type_id = \'' . $this->database()->escape($aRow['type_id']) . '\' AND l.item_id = ' . (int) $aRow['item_id'])
                            ->execute('getfield');
        }
        else
        {
            return array(
                'error_code' => 1,
                'error_message' => " Feed is not valid or you don't have permission to view this feed!"
            );
        }


        if (isset($aRow['type_id']))
        {
            $aModule = explode('_', $aRow['type_id']);
            if (isset($aModule[0]) && Phpfox::isModule($aModule[0]) && Phpfox::hasCallback($aModule[0] . (isset($aModule[1]) ? '_' . $aModule[1] : ''), 'getReportRedirect'))
            {
                $aRow['report_module'] = $aRows[$iKey]['report_module'] = $aModule[0] . (isset($aModule[1]) ? '_' . $aModule[1] : '');
                $aRow['report_phrase'] = $aRows[$iKey]['report_phrase'] = Phpfox::getPhrase('feed.report_this_entry');
                $aRow['force_report'] = $aRows[$iKey]['force_report'] = true;
            }
        }

        switch ($aRow['type_id']) {
            case 'user_status':
                $sContent = $this->getContentOfUserStatus($aRow);
                break;
            
            case 'feed_comment':
                $sContent = $this->getContentOfFeedComment($aRow);
                break;
            
            default:
                $sContent = $aRow['feed_content'];
                break;
        }
        
        return array(
            'iUserId' => $aRow['user_id'],
            'sUsername' => $aRow['user_name'],
            'iFeedId' => $aRow['feed_id'],
            'UserProfileImg_Url' => Phpfox::getLib('image.helper')->display(array(
                'server_id' => $aRow['user_server_id'],
                'path' => 'core.url_user',
                'file' => $aRow['user_image'],
                'suffix' => MAX_SIZE_OF_USER_IMAGE,
                'return_url' => true
                    )
            ),
            'sFullName' => $aRow['full_name'],
            'sContent' => $sContent,
            'timestamp' => $aRow['time_stamp'],
            'Time' => date('l, F j, o', (int) $aRow['time_stamp']) . ' at ' . date('h:i a', (int) $aRow['time_stamp']),
            'TimeConverted' => Phpfox::getLib('date')->convertTime($aRow['time_stamp'], 'comment.comment_time_stamp'),
            'sTypeId' => $aRow['type_id'],
            'iItemId' => $aRow['item_id'],
            'iLikeId' => $aRow['like_id'],
            'iTotalLike' => $iCount
        );
    }

    /**
     * Post user status.
     * 
     * Input data:
     * + sContent: string, required.
     * + iParentUserId: int, required.
     * 
     * Output data:
     * + error_code: int.
     * + error_message: string.
     * + iUserId: int.
     * + sUsername: string.
     * + iFeedId: int.
     * + UserProfileImg_Url: string.
     * + sFullName: string.
     * + timestamp: int.
     * + Time: string.
     * + TimeConverted: string.
     * + sTypeId: string.
     * + iItemId: int.
     * + iLikeId: int.
     * + iTotalLike: int.
     * + sContent: string.
     * 
     * @see Feed_Service_Process
     * @param array $aData
     * @return array
     */
    public function addComment($aData)
    {
        extract($aData, EXTR_SKIP);
        /**
         * @var string
         */
        $sContent = isset($sContent) ? $sContent : '';
        /**
         * @var int
         */
        $iParentUserId = isset($iParentUserId) ? (int) $iParentUserId : 0;
        /**
         * @var array
         */
        $aVals = array(
            'action' => 'upload_photo_via_share',
            'user_status' => $sContent,
            'parent_user_id' => $iParentUserId,
            'iframe' => 1,
            'method' => 'simple'
        );

        if (Phpfox::getLib('parse.format')->isEmpty($aVals['user_status']))
        {
            return array(
                'error_code' => 1,
                'error_message' => Phpfox::getPhrase('user.add_some_text_to_share')
            );
        }

        if (isset($aVals['user_status']) && ($iId = Phpfox::getService('feed.process')->addComment($aVals)))
        {
            return $this->getOneFeed(array('iItemId' => 0), $iId);
        }
        else
        {
            return array(
                'error_code' => 1,
                'error_message' => Phpfox_Error::get()
            );
        }
    }

    /**
     * Input data:
     * + sContent: string, required.
     * + sTypeId: string, optional.
     * + iPrivacyComment: int, optional.
     * + iPrivacy: int, optional.
     * 
     * Output data:
     * + error_code: int.
     * + error_message: string.
      * + iUserId: int.
     * + sUsername: string.
     * + iFeedId: int.
     * + UserProfileImg_Url: string.
     * + sFullName: string.
     * + timestamp: int.
     * + Time: string.
     * + TimeConverted: string.
     * + sTypeId: string.
     * + iItemId: int.
     * + iLikeId: int.
     * + iTotalLike: int.
     * + sContent: string.
     * 
     * @see Mobile - API phpFox/Api V1.0 - Restful - Put method.
     * @see updatestatus
     * 
     * @param array $aData
     * @return array
     */
    public function putAction($aData)
    {
        return $this->updatestatus($aData);
    }

    /**
     * Input data:
     * + sContent: string, required.
     * + sTypeId: string, optional.
     * + iPrivacyComment: int, optional.
     * + iPrivacy: int, optional.
     * 
     * Output data:
     * + error_code: int.
     * + error_message: string.
      * + iUserId: int.
     * + sUsername: string.
     * + iFeedId: int.
     * + UserProfileImg_Url: string.
     * + sFullName: string.
     * + timestamp: int.
     * + Time: string.
     * + TimeConverted: string.
     * + sTypeId: string.
     * + iItemId: int.
     * + iLikeId: int.
     * + iTotalLike: int.
     * + sContent: string.
     * 
     * @see Mobile - API phpFox/Api V1.0.
     * @see feed/updatestatus
     * 
     * @param array $aData
     * @return array
     */
    public function updatestatus($aData)
    {
        extract($aData, EXTR_SKIP);

        if (!isset($sContent))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Parameter(s) is not valid! "
            );
        }

        if (!isset($sTypeId))
        {
            /**
             * @var string
             */
            $sTypeId = 'user_status';
        }

        if (!isset($iPrivacyComment))
        {
            /**
             * @var int
             */
            $iPrivacyComment = 0;
        }

        if (!isset($iPrivacy))
        {
            /**
             * @var int
             */
            $iPrivacy = 0;
        }

        /**
         * @var array
         */
        $aVals = array(
            'user_status' => $sContent,
            'privacy' => $iPrivacy,
            'privacy_comment' => $iPrivacyComment
        );

        if (Phpfox::getLib('parse.format')->isEmpty($aVals['user_status']))
        {
            return array(
                'error_code' => 1,
                'error_message' => Phpfox::getPhrase('user.add_some_text_to_share')
            );
        }

        if (!Phpfox::getService('ban')->checkAutomaticBan($aVals['user_status']))
        {
            return array(
                'error_code' => 1,
                'error_message' => 'Your account has been banned!'
            );
        }
        /**
         * @var string
         */
        $sStatus = $this->preParse()->prepare($aVals['user_status']);
        /**
         * @var array
         */
        $aUpdates = $this->database()->select('content')
                ->from(Phpfox::getT('user_status'))
                ->where('user_id = ' . (int) Phpfox::getUserId())
                ->limit(Phpfox::getParam('user.check_status_updates'))
                ->order('time_stamp DESC')
                ->execute('getSlaveRows');

        /**
         * @var int
         */
        $iReplications = 0;
        foreach ($aUpdates as $aUpdate)
        {
            if ($aUpdate['content'] == $sStatus)
            {
                $iReplications++;
            }
        }

        if ($iReplications > 0)
        {
            return array(
                'error_code' => 1,
                'error_message' => Phpfox::getPhrase('user.you_have_already_added_this_recently_try_adding_something_else')
            );
        }

        if (empty($aVals['privacy']))
        {
            $aVals['privacy'] = 0;
        }

        if (empty($aVals['privacy_comment']))
        {
            $aVals['privacy_comment'] = 0;
        }
        /**
         * @var int
         */
        $iStatusId = $this->database()->insert(Phpfox::getT('user_status'), array(
            'user_id' => (int) Phpfox::getUserId(),
            'privacy' => $aVals['privacy'],
            'privacy_comment' => $aVals['privacy_comment'],
            'content' => $sStatus,
            'time_stamp' => PHPFOX_TIME
                )
        );

        if (isset($aVals['privacy']) && $aVals['privacy'] == '4')
        {
            Phpfox::getService('privacy.process')->add('user_status', $iStatusId, (isset($aVals['privacy_list']) ? $aVals['privacy_list'] : array()));
        }

        Phpfox::getService('user.process')->notifyTagged($sStatus, $iStatusId, 'status');
        /**
         * @var int
         */
        $iId = Phpfox::getService('feed.process')->allowGuest()->add('user_status', $iStatusId, $aVals['privacy'], $aVals['privacy_comment'], 0, null, 0, (isset($aVals['parent_feed_id']) ? $aVals['parent_feed_id'] : 0), (isset($aVals['parent_module_id']) ? $aVals['parent_module_id'] : null));

        if ($iId)
        {
            return $this->getOneFeed(array('iItemId' => 0), $iId);
        }
        /**
         * @var string
         */
        $sMessage = '';
        $aErrorMessage = Phpfox_Error::get();
        foreach ($aErrorMessage as $sErrorMessage)
        {
            $sMessage .= $sErrorMessage;
        }

        return array(
            'error_code' => 1,
            'error_message' => $sMessage
        );
    }

    /**
     * Input data:
     * + iItemId: int, required.
     * + sModule: string, optional.
     * + iItem: int, optional.
     * 
     * Output data:
     * + result: int.
     * + error_code: int.
     * + error_message: string.
     * 
     * @see Mobile - API phpFox/Api V1.0
     * @see feed/delete
     * 
     * @global type $token
     * @param array $aData
     * @return array
     */
    public function deleteAction($aData)
    {
        return $this->delete($aData);
    }

    /**
     * Input data:
     * + iItemId: int, required.
     * + sModule: string, optional.
     * + iItem: int, optional.
     * 
     * Output data:
     * + result: int.
     * + error_code: int.
     * + error_message: string.
     * 
     * @see Mobile - API phpFox/Api V1.0
     * @see feed/delete
     * 
     * @global type $token
     * @param array $aData
     * @return array
     */
    public function delete($aData)
    {
        if (define('PHPFOX_FEED_CAN_DELETE'))
        {
            return array(
                'result' => 0,
                'error_code' => 1,
                'error_message' => 'You don\'t have permission to delete feed!'
            );
        }

        /**
         * @var int
         */
        $iFeedId = isset($aData['iItemId']) ? (int) $aData['iItemId'] : 0;
        /**
         * @var string
         */
        $sModule = (isset($aData['sModule']) && !empty($aData['sModule'])) ? $aData['sModule'] : null;
        /**
         * @var int
         */
        $iItem = isset($aData['iItem']) ? (int) $aData['iItem'] : 0;

        if ($this->deleteFeed($iFeedId, $sModule, $iItem))
        {
            return array('result' => 1);
        }
        else
        {
            return array(
                'result' => 0,
                'error_code' => 1,
                'error_message' => Phpfox::getPhrase('feed.unable_to_delete_this_entry')
            );
        }
    }

    /**
     * Delete feed.
     * @see Privacy_Service_Privacy
     * @param int $iId
     * @param string $sModule
     * @param int $iItem
     * @return boolean
     */
    public function deleteFeed($iId, $sModule = null, $iItem = 0)
    {
        /**
         * @var array
         */
        $aCallback = null;
        if (!empty($sModule))
        {
            if (Phpfox::hasCallback($sModule, 'getFeedDetails'))
            {
                $aCallback = Phpfox::callback($sModule . '.getFeedDetails', $iItem);
            }
        }
        /**
         * @var array
         */
        $aFeed = Phpfox::getService('feed')->callback($aCallback)->getFeed($iId);

        if (!isset($aFeed['feed_id']))
        {
            return false;
        }

        // Check the privacy.
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check($aFeed['type_id'], $aFeed['feed_id'], $aFeed['user_id'], $aFeed['privacy'], $aFeed['is_friend'], true))
        {
            return false;
        }

        /**
         * @var bool
         */
        $bCanDelete = false;
        if (Phpfox::getUserParam('feed.can_delete_own_feed') && ($aFeed['user_id'] == Phpfox::getUserId()))
        {
            $bCanDelete = true;
        }

        if (defined('PHPFOX_FEED_CAN_DELETE'))
        {
            $bCanDelete = true;
        }

        if (Phpfox::getUserParam('feed.can_delete_other_feeds'))
        {
            $bCanDelete = true;
        }

        if ($bCanDelete === true)
        {
            if (isset($aCallback['table_prefix']))
            {
                $this->database()->delete(Phpfox::getT($aCallback['table_prefix'] . 'feed'), 'feed_id = ' . (int) $iId);
            }

            //$this->database()->delete(Phpfox::getT('feed'), 'feed_id = ' . $aFeed['feed_id'] . ' AND user_id = ' . $aFeed['user_id'] .' AND time_stamp = ' . $aFeed['time_stamp']);
            $this->database()->delete(Phpfox::getT('feed'), 'user_id = ' . $aFeed['user_id'] . ' AND time_stamp = ' . $aFeed['time_stamp']);

            // Delete likes that belonged to this feed
            $this->database()->delete(Phpfox::getT('like'), 'type_id = "' . $aFeed['type_id'] . '" AND item_id = ' . $aFeed['item_id']);

            if (!empty($sModule))
            {
                if (Phpfox::hasCallback($sModule, 'deleteFeedItem'))
                {
                    Phpfox::callback($sModule . '.deleteFeedItem', $iItem);
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Input data:
     * + type_id: string.
     * + feed_id: int.
     * + can_post_comment: bool.
     * 
     * @param array $aRow
     * @param string $sKey
     * @param int $iUserid
     * @param bool $bFirstCheckOnComments
     * @return array 
     */
    private function _processFeed($aRow, $sKey, $iUserid, $bFirstCheckOnComments)
    {
        switch ($aRow['type_id']) {
            case 'comment_profile':
            case 'comment_profile_my':
                $aRow['type_id'] = 'profile_comment';
                break;
            case 'profile_info':
                $aRow['type_id'] = 'custom';
                break;
            case 'comment_photo':
                $aRow['type_id'] = 'photo_comment';
                break;
            case 'comment_blog':
                $aRow['type_id'] = 'blog_comment';
                break;
            case 'comment_video':
                $aRow['type_id'] = 'video_comment';
                break;
            case 'comment_group':
                $aRow['type_id'] = 'pages_comment';
                break;
        }

        if (preg_match('/(.*)_feedlike/i', $aRow['type_id']) || $aRow['type_id'] == 'profile_design')
        {
            $this->database()->delete(Phpfox::getT('feed'), 'feed_id = ' . (int) $aRow['feed_id']);

            return false;
        }

        if (Phpfox::hasCallback($aRow['type_id'], 'getActivityFeed'))
        {
            $aFeed = Phpfox::callback($aRow['type_id'] . '.getActivityFeed', $aRow, (isset($this->_aCallback['module']) ? $this->_aCallback : null));
        }
        else
        {
            $aFeed = FALSE;
        }

        if ($aFeed === false)
        {
            return false;
        }

        if (isset($this->_aViewMoreFeeds[$sKey]))
        {
            foreach ($this->_aViewMoreFeeds[$sKey] as $iSubKey => $aSubRow)
            {
                $mReturnViewMore = $this->_processFeed($aSubRow, $iSubKey, $iUserid, $bFirstCheckOnComments);

                if ($mReturnViewMore === false)
                {
                    continue;
                }

                $aFeed['more_feed_rows'][] = $mReturnViewMore;
            }
        }

        $aRow['can_post_comment'] = true;

        return array_merge($aRow, $aFeed);
    }

    /**
     * Note: feed id in home is different with feed id in page.
     * 
     * Input data:
     * + iFeedId: int, required.
     * + sModule: string, optional. (Required in page).
     * + iItem: int, optional. (Required in page).
     * 
     * Output data:
     * + result: int.
     * + error_code: int.
     * + error_message: string.
     * 
     * @see Mobile - API phpFox/Api V1.0
     * @see feed/like
     * 
     * @param array $aData
     * @return array
     */
    public function like($aData)
    {
        /**
         * @var int
         */
        $iFeedId = isset($aData['iFeedId']) ? (int) $aData['iFeedId'] : 0;
        /**
         * @var string
         */
        $sModule = (isset($aData['sModule']) && !empty($aData['sModule'])) ? $aData['sModule'] : null;
        /**
         * @var int
         */
        $iItem = isset($aData['iItem']) ? (int) $aData['iItem'] : 0;

        /**
         * @var array
         */
        $aCallback = null;
        if (!empty($sModule))
        {
            if (Phpfox::hasCallback($sModule, 'getFeedDetails'))
            {
                $aCallback = Phpfox::callback($sModule . '.getFeedDetails', $iItem);
            }
        }
        /**
         * @var array
         */
        $aFeed = Phpfox::getService('feed')->callback($aCallback)->getFeed($iFeedId);

        if (!isset($aFeed['feed_id']))
        {
            return array(
                'result' => 0,
                'error_code' => 1,
                'error_message' => 'Feed is not valid!'
            );
        }

        $aFeed['is_friend'] = Phpfox::getService('friend')->isFriend(Phpfox::getUserId(), $aFeed['user_id'], $bRedirect = false);
        
        // Check the privacy.
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check($aFeed['type_id'], $aFeed['feed_id'], $aFeed['user_id'], $aFeed['privacy'], $aFeed['is_friend'], true))
        {
            return array(
                'result' => 0,
                'error_code' => 1,
                'error_message' => 'You don\'t have permission to like this feed!'
            );
        }

        return Phpfox::getService('mfox.like')->add(array('sType' => $aFeed['type_id'], 'iItemId' => $aFeed['item_id']));
    }

    /**
     * Check privacy on user status feed.
     * @param int $iFeedId Feed id.
     * @param string $sModule Module name in page.
     * @param int $iItem Item id in page.
     * @return array
     */
    public function checkPrivacyOnUserStatusFeed($iItemId, $sType, $sModule, $iItem)
    {
        /**
         * @var array
         */
        $aCallback = null;
        if (!empty($sModule))
        {
            if (Phpfox::hasCallback($sModule, 'getFeedDetails'))
            {
                $aCallback = Phpfox::callback($sModule . '.getFeedDetails', $iItem);
            }
        }
        /**
         * @var array
         */
        $aFeed = $this->database()
                ->select('*')
                ->from(Phpfox::getT((isset($aCallback['table_prefix']) ? $aCallback['table_prefix'] : '') . 'feed'))
                ->where('item_id =' . (int) $iItemId . ' AND type_id = "' . $sType . '"')
                ->execute('getSlaveRow');
        
        if (!isset($aFeed['feed_id']))
        {
            return array(
                'result' => 0,
                'error_code' => 1,
                'error_message' => 'Feed is not valid!'
            );
        }

        $aFeed['is_friend'] = Phpfox::getService('friend')->isFriend(Phpfox::getUserId(), $aFeed['user_id'], $bRedirect = false);
        
        // Check the privacy.
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check($aFeed['type_id'], $aFeed['feed_id'], $aFeed['user_id'], $aFeed['privacy'], $aFeed['is_friend'], true))
        {
            return array(
                'result' => 0,
                'error_code' => 1,
                'error_message' => 'You don\'t have permission to view this feed!'
            );
        }
        
        return null;
    }
    
    /**
     * Note: feed id in home is different with feed id in page.
     * 
     * Input data:
     * + iFeedId: int, required.
     * + sModule: string, optional. (Required in page).
     * + iItem: int, optional. (Required in page).
     * 
     * Output data:
     * + result: int.
     * + error_code: int.
     * + error_message: string.
     * 
     * @see Mobile - API phpFox/Api V1.0
     * @see feed/unlike
     * 
     * @param array $aData
     * @return array
     */
    public function unlike($aData)
    {
        /**
         * @var int
         */
        $iFeedId = isset($aData['iFeedId']) ? (int) $aData['iFeedId'] : 0;
        /**
         * @var string
         */
        $sModule = (isset($aData['sModule']) && !empty($aData['sModule'])) ? $aData['sModule'] : null;
        /**
         * @var int
         */
        $iItem = isset($aData['iItem']) ? (int) $aData['iItem'] : 0;

        $aCallback = null;
        if (!empty($sModule))
        {
            if (Phpfox::hasCallback($sModule, 'getFeedDetails'))
            {
                $aCallback = Phpfox::callback($sModule . '.getFeedDetails', $iItem);
            }
        }
        /**
         * @var array
         */
        $aFeed = Phpfox::getService('feed')->callback($aCallback)->getFeed($iFeedId);

        if (!isset($aFeed['feed_id']))
        {
            return array(
                'result' => 0,
                'error_code' => 1,
                'error_message' => 'Feed is not valid!'
            );
        }

        // Check the privacy.
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check($aFeed['type_id'], $aFeed['feed_id'], $aFeed['user_id'], $aFeed['privacy'], $aFeed['is_friend'], true))
        {
            return array(
                'result' => 0,
                'error_code' => 1,
                'error_message' => 'You don\'t have permission to unlike this feed!'
            );
        }

        return Phpfox::getService('mfox.like')->delete(array('sType' => $aFeed['type_id'], 'iItemId' => $aFeed['item_id']));
    }
    
    /**
     * Input data:
     * + item_id: int, required.
     * + user_id: int, required.
     * + full_name: string, required.
     * 
     * Output data:
     * + link: array (iFeedId: int, sTypeId: string, iItemId: int)
     * + message: string.
     * + icon: string.
     * 
     * @param array $aNotification
     * @return boolean
     */
    public function doFeedGetNotificationComment_Profile($aNotification)
    {
        /**
         * @var array
         */
        $aRow = $this->database()->select('fc.feed_comment_id, u.user_id, u.gender, u.user_name, u.full_name')
                ->from(Phpfox::getT('feed_comment'), 'fc')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = fc.parent_user_id')
                ->where('fc.feed_comment_id = ' . (int) $aNotification['item_id'])
                ->execute('getSlaveRow');

        /**
         * @var string
         */
        $sType = 'comment-id';
        if (empty($aRow))
        {
            $aRow = $this->database()->select('u.user_id, u.gender, u.user_name, u.full_name')
                    ->from(Phpfox::getT('user_status'), 'fc')
                    ->join(Phpfox::getT('user'), 'u', 'u.user_id = fc.user_id')
                    ->where('fc.status_id = ' . (int) $aNotification['item_id'])
                    ->execute('getSlaveRow');

            $aRow['feed_comment_id'] = (int) $aNotification['item_id'];
            $sType = 'status-id';
            $bWasChanged = true;
        }
        /**
         * @var string
         */
        $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
        if (empty($aRow) || !isset($aRow['user_id']))
        {
            return false;
        }
        /**
         * @var string
         */
        $sPhrase = '';
        if ($aNotification['user_id'] == $aRow['user_id'])
        {
            if (isset($bWasChanged))
            {
                $sPhrase = Phpfox::getPhrase('user.user_name_tagged_you_in_a_status_update', array('user_name' => $aNotification['full_name']));
            }
            else
            {
                $sPhrase = Phpfox::getPhrase('feed.users_commented_on_gender_wall', array('users' => $sUsers, 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1)));
            }
        }
        elseif ($aRow['user_id'] == Phpfox::getUserId())
        {
            $sPhrase = Phpfox::getPhrase('feed.users_commented_on_your_wall', array('users' => $sUsers));
        }
        else
        {
            $sPhrase = Phpfox::getPhrase('feed.users_commented_on_one_span_class_drop_data_user_row_full_name_span_wall', array('users' => $sUsers, 'row_full_name' => $aRow['full_name']));
        }
        /**
         * @var array
         */
        $aFeeds = $this->getfeed(array('comment-id' => $aRow['feed_comment_id']), $aRow['user_id']);
        
        /**
         * @var array
         */
        $aLink = array();
        
        if (isset($aFeeds[0]['feed_id']))
        {
            $aLink = array('iFeedId' => $aFeeds[0]['feed_id'], 'sTypeId' => $aFeeds[0]['type_id'], 'iItemId' => $aFeeds[0]['item_id']);
        }
        else
        {
            $aLink = array('iFeedId' => 0);
        }
        
        return array(
            'link' => $aLink,
            'message' => strip_tags($sPhrase),
            'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
        );
    }
    
    /**
     * Input data:
     * + item_id: int, required.
     * 
     * Output data:
     * + link: array.
     * + message: string.
     * + icon: string.
     * 
     * @param array $aNotification
     * @return array
     */
    public function doFeedGetNotificationMini_Like($aNotification)
    {
        /**
         * @var array
         */
        $aRow = $this->database()->select('c.comment_id, c.user_id, ct.text_parsed AS text, c.type_id, c.item_id')
                ->from(Phpfox::getT('comment'), 'c')
                ->join(Phpfox::getT('comment_text'), 'ct', 'ct.comment_id = c.comment_id')
                ->where('c.comment_id = ' . (int) $aNotification['item_id'])
                ->execute('getSlaveRow');
        /**
         * @var array
         */
        $aLink = Phpfox::getService('mfox.comment')->doCommentGetRedirectRequest($aRow);
        /**
         * @var string
         */
        $sPhrase = Phpfox::getPhrase('feed.users_liked_your_comment_text_that_you_posted', array('users' => Phpfox::getService('notification')->getUsers($aNotification), 'text' => Phpfox::getLib('parse.output')->shorten($aRow['text'], Phpfox::getParam('notification.total_notification_title_length'), '...')));
        
        return array(
            'link' => $aLink,
            'message' => strip_tags($sPhrase),
            'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
        );
    }
    
    /**
     * Note: feed id in home is different with feed id in page.
     * 
     * Input data:
     * + iFeedId: int, required.
     * + sModule: string, optional. (Required in page).
     * + iItem: int, optional. (Required in page)
     * + lastLikeIdViewed: int, optional.
     * + amountOfLike: int, option.
     * 
     * Output data:
	 * + iLikeId: int
	 * + iUserId: int
	 * + sFullName: string
	 * + sImage: string
     * 
     * @see Mobile - API phpFox/Api V1.0
     * @see feed/list_all_likes
     * 
     * @param array $aData
     * @return array
     */
    public function list_all_likes($aData)
    {
        /**
         * @var int
         */
        $iFeedId = isset($aData['iFeedId']) ? (int) $aData['iFeedId'] : 0;
        /**
         * @var string
         */
        $sModule = (isset($aData['sModule']) && !empty($aData['sModule'])) ? $aData['sModule'] : null;
        /**
         * @var int
         */
        $iItem = isset($aData['iItem']) ? (int) $aData['iItem'] : 0;
        /**
         * @var array
         */
        $aCallback = null;
        if (!empty($sModule))
        {
            if (Phpfox::hasCallback($sModule, 'getFeedDetails'))
            {
                $aCallback = Phpfox::callback($sModule . '.getFeedDetails', $iItem);
            }
        }
        /**
         * @var array
         */
        $aFeed = Phpfox::getService('feed')->callback($aCallback)->getFeed($iFeedId);
        
        if (!isset($aFeed['feed_id']))
        {
            return array(
                'result' => 0,
                'error_code' => 1,
                'error_message' => 'Feed is not valid!'
            );
        }

        $aFeed['is_friend'] = Phpfox::getService('friend')->isFriend(Phpfox::getUserId(), $aFeed['user_id'], $bRedirect = false);
        
        // Check the privacy.
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check($aFeed['type_id'], $aFeed['feed_id'], $aFeed['user_id'], $aFeed['privacy'], $aFeed['is_friend'], true))
        {
            return array(
                'result' => 0,
                'error_code' => 1,
                'error_message' => 'You don\'t have permission to view this feed!'
            );
        }
        
        /**
         * @var int
         */
        $lastLikeIdViewed = isset($aData['lastLikeIdViewed']) ? (int) $aData['lastLikeIdViewed'] : 0;
        /**
         * @var int
         */
        $amountOfLike = isset($aData['amountOfLike']) ? (int) $aData['amountOfLike'] : 20;
        
        return Phpfox::getService('mfox.like')->listalllikes(array(
            'sType' => $aFeed['type_id'], 
            'iItemId' => $aFeed['item_id'],
            'lastCommentIdViewed' => $lastLikeIdViewed,
            'amountOfComment' => $amountOfLike
        ));
    }
    /**
     * Push Cloud Message for user status.
     * @param array $aData
     */
    public function doPushCloudMessageUserStatus($aData)
    {
        /**
         * @var string
         */
        $sType = isset($aData['sType']) ? $aData['sType'] : '';

        /**
         * @var int
         */
        $iItemId = isset($aData['iItemId']) ? (int) $aData['iItemId'] : 0;

        /**
         * @var string In page only.
         */
        $sModule = isset($aData['sModule']) ? trim($aData['sModule']) : '';

        /**
         * @var int In page only.
         */
        $iItem = isset($aData['iItem']) ? (int) $aData['iItem'] : 0;
        /**
         * @var array
         */
        $aCallback = null;
        if (!empty($sModule))
        {
            if (Phpfox::hasCallback($sModule, 'getFeedDetails'))
            {
                $aCallback = Phpfox::callback($sModule . '.getFeedDetails', $iItem);
            }
        }
        /**
         * @var array
         */
        $aFeed = $this->database()
                ->select('*')
                ->from(Phpfox::getT((isset($aCallback['table_prefix']) ? $aCallback['table_prefix'] : '') . 'feed'))
                ->where('item_id =' . (int) $iItemId . ' AND type_id = "' . $sType . '"')
                ->execute('getSlaveRow');
        
        if (isset($aFeed['user_id']) && $aFeed['user_id'] != Phpfox::getUserId())
        {
            /**
             * @var int
             */
            $iPushId = Phpfox::getService('mfox.push')->savePush($aData, $aFeed['user_id']);
            // Push cloud message.
            Phpfox::getService('mfox.cloudmessage') -> send(array('message' => 'notification', 'iPushId' => $iPushId), $aFeed['user_id']);
        }
    }
}
