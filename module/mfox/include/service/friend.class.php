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
class Mfox_Service_Friend extends Phpfox_Service {
    /**
     * Input data:
     * + iFriendListId: int, required.
     * + sFriendId: string, required.
     * 
     * Output data:
     * + error_code: int.
     * + error_message: string.
     * + result: int.
     * + message: string.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see friend/addfriendstolist
     * 
     * @param array $aData
     * @return array
     */
    public function addfriendstolist($aData)
    {
        /**
         * @var int
         */
        $iFriendListId = isset($aData['iFriendListId']) ? (int) $aData['iFriendListId'] : 0;
        if ($iFriendListId < 1)
        {
            return array(
                'error_code' => 1,
                'error_message' => " Parameter(s) is not valid! "
            );
        }
        /**
         * @var array
         */
        $aFriendList = Phpfox::getService('friend.list')->getList($aData['iFriendListId'], Phpfox::getUserId());
        
        if (!isset($aFriendList['list_id']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Friend list is not valid! "
            );
        }
        
        if (!isset($aData['sFriendId']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Friend(s) id is not valid! "
            );
        }
        /**
         * @var array
         */
        $aTemp = explode(',', $aData['sFriendId']);
        /**
         * @var array
         */
        $aFriendId = array();
        
        foreach($aTemp as $iFriendId)
        {
            if (is_numeric($iFriendId))
            {
                $aFriendId[] = $iFriendId;
            }
        }
        
        if (count($aFriendId) == 0)
        {
            return array(
                'error_code' => 1,
                'error_message' => " Friend(s) id is not valid! "
            );
        }
        
        if (Phpfox::getService('friend.list.process')->addFriendsToList((int) $aData['iFriendListId'], (array) $aFriendId))
		{
			return array(
                'result' => true,
                'message' => " Add friend(s) to list successfully! "
            );
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
     * + bAllowCustom: bool.
     * 
     * Output data:
     * + iFriendId: int.
     * + bIsPage: bool.
     * + iListId: int.
     * + iUserId: int.
     * + iFriendUserId: int.
     * + bIsTopFriend: bool.
     * + sFullName: string.
     * + sUserImage: string.
     * 
     * @see Mobile - API phpFox/Api V1.0
     * @see friend/buildcache
     * 
     * @param array $aData
     * @return array
     */
    public function buildcache($aData)
    {
        /**
         * @var bool
         */
        $bAllowCustom = isset($aData['bAllowCustom']) ? (bool) $aData['bAllowCustom'] : false;
        /**
         * @var array
         */
        $aFriends = Phpfox::getService('friend')->getFromCache($bAllowCustom);
        /**
         * @var array
         */
        $aResult = array();
        foreach($aFriends as $aFriend)
        {
            $aResult[] = array(
                'iFriendId' => $aFriend['friend_id'],
                'bIsPage' => $aFriend['is_page'],
                'iListId' => $aFriend['list_id'],
                'iUserId' => $aFriend['user_id'],
                'iFriendUserId' => $aFriend['friend_user_id'],
                'bIsTopFriend' => $aFriend['is_top_friend'],
                'sFullName' => $aFriend['full_name'],
                'sUserImage' => $aFriend['user_image']
            );
        }
        return $aResult;
    }
    /**
     * Input data:
     * + sName: string, required.
     * 
     * Output data:
     * + error_code: int.
     * + error_message: string.
     * + iFriendListId: int.
     * + sMessage: string.
     * 
     * @see Mobile - API phpFox/Api V1.0
     * @see friend/addlist
     * 
     * @param array $aData
     * @return array
     */
    public function addlist($aData)
    {
		if (!Phpfox::getUserParam('friend.can_add_folders'))
        {
            return array(
                'error_code' => 1,
                'error_message' => " You don't have permission to add friend list! "
            );
        }   
		/**
         * @var string
         */
		$sName = isset($aData['sName']) ? $aData['sName'] : '';

		if (Phpfox::getLib('parse.format')->isEmpty($sName))
		{
            return array(
                'error_code' => 1,
                'error_message' => Phpfox::getPhrase('friend.provide_a_name_for_your_list')
            );
		}
		elseif (Phpfox::getService('friend.list')->reachedLimit()) // Did they reach their limit?
		{
            return array(
                'error_code' => 1,
                'error_message' => Phpfox::getPhrase('friend.you_have_reached_your_limit')
            );
		}			
		elseif (Phpfox::getService('friend.list')->isFolder($sName))
		{
			return array(
                'error_code' => 1,
                'error_message' => Phpfox::getPhrase('mail.folder_already_use')
            );
		}
		else 
		{
			if ($iId = Phpfox::getService('friend.list.process')->add($sName))
			{
                return array(
                    'iFriendListId' => $iId,
                    'sMessage' => Phpfox::getPhrase('friend.list_successfully_created')
                );
			}
            else
            {
                return array(
                    'error_code' => 1,
                    'error_message' => Phpfox_Error::get()
                );
            }
		}
    }
    
    
    /**
     * => case sAction = confirm.
     * 
     * Input data:
     * + iPage: int, optional.
     * + iLimit: int, optional.
     * + iRequestId: int, optional.
     * 
     * Output data:
     * + id: int.
     * + sFullName: string.
     * + iUserId: int.
     * + UserProfileImg_Url: string.
     * 
     * @param array $aData
     * @return array
     * 
     * => Case sAction = all.
     * 
     * Input data:
     * + iUserId: int, required.
     * + amountOfFriend: int, optional.
     * + LastFriendIdViewed: int, optional.
     * + sType: string, optional. Ex: "more" or "new".
     * 
     * Output data:
     * + id: int.
     * + sFullName: string.
     * + iFriendId: int.
     * + UserProfileImg_Url: string.
     * + error_message: string.
     * + error_code: int.
     * + result: int.
     * 
     * @see Mobile - API phpFox/Api V1.0 - Restful.
     * @see friend
     * 
     * @param array $aData
     * @return array
     */
    public function getAction($aData)
    {
        if (isset($aData['sAction']))
        {
            switch ($aData['sAction']) {
                case 'confirm':
                    return $this->getfriendsrequest($aData);
                    break;

                case 'all':
                default:
                    return $this->getall($aData);
                    break;
            }
        }
        else
        {
            return array(
                'error_message' => ' Action is not valid! ',
                'error_code' => 1,
                'result' => 0
            );
        }
    }
    /**
     * Input data:
     * + iUserId: int, required.
     * + amountOfFriend: int, optional.
     * + LastFriendIdViewed: int, optional.
     * + sType: string, optional. Ex: "more" or "new".
     * 
     * Output data:
     * + id: int.
     * + sFullName: string.
     * + iFriendId: int.
     * + UserProfileImg_Url: string.
     * 
     * @see Mobile - API phpFox/Api V1.0
     * @see friend/getall
     * 
     * @param array $aData
     * @return array
     */
    public function getall($aData)
    {
        extract($aData, EXTR_SKIP);

        if (!isset($iUserId))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Parameter(s) is not valid! "
            );
        }
        $iUserId = (int) $iUserId;
        
        if (!isset($amountOfFriend))
        {
            $amountOfFriend = 20;
        }
        if (!isset($LastFriendIdViewed))
        {
            $LastFriendIdViewed = 0;
        }
        
        // Condition for "new" and "more" case.
        if (isset($sType) && $sType == 'new')
        {
            $sFriendCondition = ($LastFriendIdViewed > 0 ? ' AND friend.friend_id > ' . (int) $LastFriendIdViewed : '');
        }
        else // "more" case.
        {
            $sFriendCondition = ($LastFriendIdViewed > 0 ? ' AND friend.friend_id < ' . (int) $LastFriendIdViewed : '');
        }
        /**
         * @var array
         */
        $aCond = array('AND friend.is_page = 0 AND friend.user_id = ' . $iUserId . $sFriendCondition);
        $sSort = 'friend.friend_id DESC';
        $iPage = '';
        $iCnt = 0;
        $aFriends = array();

        $iCnt = $this->database()->select('COUNT(DISTINCT u.user_id)')
                ->from(Phpfox::getT('friend'), 'friend')
                ->leftJoin(Phpfox::getT('user'), 'u', 'u.user_id = friend.friend_user_id AND u.status_id = 0')
                ->where($aCond)
                ->execute('getSlaveField');

        if ($iCnt)
        {
            $aFriends = $this->database()->select('uf.dob_setting, friend.friend_id, friend.friend_user_id, friend.is_top_friend, friend.time_stamp, ' . Phpfox::getUserField())
                    ->from(Phpfox::getT('friend'), 'friend')
                    ->leftJoin(Phpfox::getT('user'), 'u', 'u.user_id = friend.friend_user_id AND u.status_id = 0')
                    ->leftJoin(Phpfox::getT('user_field'), 'uf', 'u.user_id = uf.user_id')
                    ->where($aCond)
                    ->limit($iPage, (int) $amountOfFriend, $iCnt)
                    ->order($sSort)
                    ->group('u.user_id')
                    ->execute('getSlaveRows');
        }

        $aResult = array();
        
        foreach ($aFriends as $aFriend)
        {
            $aResult[] = array(
                'id' => $aFriend['user_id'],
                'sFullName' => $aFriend['full_name'],
                'iFriendId' => $aFriend['friend_id'],
                'UserProfileImg_Url' => Phpfox::getLib('image.helper')->display(array(
                    'server_id' => $aFriend['user_server_id'],
                    'path' => 'core.url_user',
                    'file' => $aFriend['user_image'],
                    'suffix' => '_50_square',
                    'return_url' => true
                        )
                )
            );
        }

        return $aResult;
    }
    /**
     * Input data:
     * + iUserId: int, required.
     * + sText: string, optional.
     * 
     * Output data:
     * + result: string.
     * + error_code: int.
     * + error_message: string.
     * 
     * @see Mobile - API phpFox/Api V1.0
     * @see friend/add
     * 
     * @param array $aData
     * @return array
     */
    public function add($aData)
    {
        /**
         * @var int
         */
        $iUserId = isset($aData['iUserId']) ? (int) $aData['iUserId'] : 0;
        /**
         * @var string
         */
        $sText = isset($aData['sText']) ? $aData['sText'] : '';
        /**
         * @var int
         */
        $iListId = isset($aData['iListId']) ? (int) $aData['iListId'] : null;
        
        if ($iUserId < 0)
        {
            return array(
                'error_code' => 1,
                'error_message' => " Parameter(s) is not valid! "
            );
        }
        
        if (Phpfox::getUserId() === $iUserId)
        {
            return array('result' => false);
        }
        
        if (!Phpfox::getUserParam('friend.can_add_friends'))
        {
            return array(
                'error_code' => 1,
                'error_message' => " You don't have permission to add friend! "
            );
        }
        /**
         * @var array
         */
        $aUser = Phpfox::getService('user')->getUser($iUserId, 'u.user_id, u.user_name, u.user_image, u.server_id');
        
        if (Phpfox::getUserId() === $aUser['user_id'])
        {
            return array('result' => false);
        }
        elseif (Phpfox::getService('friend.request')->isRequested(Phpfox::getUserId(), $aUser['user_id']))
        {
            return array('result' => false);
        }
        elseif (Phpfox::getService('friend.request')->isRequested($aUser['user_id'], Phpfox::getUserId()))
        {
            return array('result' => false);
        }
        elseif (Phpfox::getService('friend')->isFriend($aUser['user_id'], Phpfox::getUserId()))
        {
            return array('result' => false);
        }
        else if (Phpfox::getService('user.block')->isBlocked($aUser['user_id'], Phpfox::getUserId()) /* is user blocked */
                && (Phpfox::isModule('friend') && Phpfox::getParam('friend.allow_blocked_user_to_friend_request') == false)
        )
        {
            return array(
                'error_code' => 1,
                'error_message' => Phpfox_Error::set('Unable to send a friend request to this user at this moment.')
            );
        }

        if (Phpfox::getService('friend.request.process')->add(Phpfox::getUserId(), $iUserId, (isset($iListId) ? (int) $iListId : 0), $sText))
        {
            return array('result' => true);
        }

        return array('result' => false);
    }
    /**
     * Input data:
     * + iUserId: int, required.
     * + iRequestId: int, required.
     * + iListId: int, optional.
     * 
     * Output data:
     * + error_code: int.
     * + error_message: string.
     * + result: bool.
     * 
     * @see Mobile - API phpFox/Api V1.0
     * @see friend/confirm
     * 
     * @param array $aData
     * @return array
     */
    public function confirm($aData)
    {
        /**
         * @var int
         */
        $iUserId = isset($aData['iUserId']) ? (int) $aData['iUserId'] : 0;
        /**
         * @var int
         */
        $iRequestId = isset($aData['iRequestId']) ? (int) $aData['iRequestId'] : 0;
        /**
         * @var int
         */
        $iListId = isset($aData['iListId']) ? (int) $aData['iListId'] : null;
        
        if ($iUserId < 1 || $iRequestId < 1)
        {
            return array(
                'error_code' => 1,
                'error_message' => " Parameter(s) is not valid! "
            );
        }

        if (!Phpfox::getUserParam('friend.can_add_friends'))
        {
            return array(
                'error_code' => 1,
                'error_message' => " You don't have permission to add friend! "
            );
        }

        if (Phpfox::getService('friend')->isFriend($iUserId, Phpfox::getUserId()))
        {
            Phpfox::getService('friend.request.process')->delete($iRequestId, $iUserId);

            return array('result' => false);
        }

        if (Phpfox::getService('friend.process')->add(Phpfox::getUserId(), $iUserId, (isset($iListId) ? (int) $iListId : 0)))
        {
            return array('result' => true);
        }

        return array('result' => false);
    }
    /**
     * Input data:
     * + iUserId: int, required.
     * + iRequestId: int, required.
     * 
     * Output data:
     * + result: bool.
     * + error_code: int.
     * + error_message: string.
     * 
     * @see Mobile - API phpFox/Api V1.0
     * @see friend/deny
     * 
     * @param array $aData
     * @return array
     */
    public function deny($aData)
    {
        /**
         * @var int
         */
        $iUserId = isset($aData['iUserId']) ? (int) $aData['iUserId'] : 0;
        /**
         * @var int
         */
        $iRequestId = isset($aData['iRequestId']) ? (int) $aData['iRequestId'] : 0;
        
        if ($iUserId < 1 || $iRequestId < 1)
        {
            return array(
                'error_code' => 1,
                'error_message' => " Parameter(s) is not valid! "
            );
        }

        if (!Phpfox::getUserParam('friend.can_add_friends'))
        {
            return array(
                'error_code' => 1,
                'error_message' => " You don't have permission to add friend! "
            );
        }

        if (Phpfox::getService('friend')->isFriend($iUserId, Phpfox::getUserId()))
        {
            Phpfox::getService('friend.request.process')->delete($iRequestId, $iUserId);

            return array('result' => false);
        }

        if (Phpfox::getService('friend.process')->deny(Phpfox::getUserId(), $iUserId))
        {
            return array('result' => true);
        }

        return array('result' => false);
    }

    /**
     * Input data:
     * + iFriendId: int, required.
     * 
     * Output data:
     * + result: bool.
     * + error_code: int.
     * + error_message: string.
     * 
     * @see Mobile - API phpFox/Api V1.0
     * @see friend/delete
     * 
     * @param array $aData
     * @return array
     */
    public function delete($aData)
    {
        /**
         * @var int
         */
        $iFriendId = isset($aData['iFriendId']) ? (int) $aData['iFriendId'] : 0;
        
        if ($iFriendId < 1)
        {
            return array(
                'error_code' => 1,
                'error_message' => " Parameter(s) is not valid! "
            );
        }

        if (Phpfox::getService('friend.process')->delete($iFriendId))
        {
            return array('result' => true);
        }

        return array('result' => false);
    }
    /**
     * Input data:
     * + iPage: int, optional.
     * + iLimit: int, optional.
     * + iRequestId: int, optional.
     * 
     * Output data:
     * + id: int.
     * + sFullName: string.
     * + iUserId: int.
     * + UserProfileImg_Url: string.
     * 
     * @see Mobile - API phpFox/Api V1.0
     * @see friend/getfriendsrequest
     * 
     * @param array $aData
     * @return array
     */
    public function getfriendsrequest($aData)
    {
        /**
         * @var int
         */
        $iPage = isset($aData['iPage']) ? (int) $aData['iPage'] : 0;
        /**
         * @var int
         */
        $iLimit = isset($aData['iLimit']) ? (int) $aData['iLimit'] : 5;
        /**
         * @var int
         */
        $iRequestId = isset($aData['iRequestId']) ? (int) $aData['iRequestId'] : 0;
        
        list($iCnt, $aFriends) = Phpfox::getService('friend.request')->get($iPage, $iLimit, $iRequestId);
        /**
         * @var array
         */
        $aResult = array();

        foreach($aFriends as $aFriend)
        {
            $aResult[] = array(
                'id' => $aFriend['request_id'],
                'sFullName' => $aFriend['full_name'],
                'iUserId' => $aFriend['user_id'],
                'UserProfileImg_Url' => Phpfox::getLib('image.helper')->display(array(
                    'server_id' => $aFriend['user_server_id'],
                    'path' => 'core.url_user',
                    'file' => $aFriend['user_image'],
                    'suffix' => '_50_square',
                    'return_url' => true
                        )
                )
            );
        }

        return $aResult;
    }

    /**
     * Using for notification.
     * @param array $aNotification
     * @return array
     */
    public function doFriendGetNotificationAccepted($aNotification)
    {
        return array(
            'link' => array('sUserName' => $aNotification['user_name'], 'iUserId' => $aNotification['user_id']),
            'message' => Phpfox::getPhrase('friend.full_name_added_you_as_a_friend', array('full_name' => Phpfox::getLib('parse.output')->shorten($aNotification['full_name'], Phpfox::getParam('user.maximum_length_for_full_name')))),
            'icon' => Phpfox::getLib('template')->getStyle('image', 'misc/user.png')
        );
    }
    
}
