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
 * @since May 31, 2013
 * @link Mfox Api v1.0
 */

class Mfox_Service_Event extends Phpfox_Service {
	/**
     *
     * @var bool 
     */
	private $_bHasImage = false;
	/**
     *
     * @var array 
     */
	private $_aCategories = array();
    /**
     *
     * @var bool 
     */
	private $_bIsEndingInThePast = false;
	
    /**
     * Input data:
     * + iEventId: int, required.
     * + image[]: file, required.
     * 
     * Output data:
     * + error_code: int.
     * + error_message: string.
     * + result: bool.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see event/photoprofile
     * 
     * @param array $aData
     * @return array
     */
    public function photoprofile($aData)
    {
        if (!isset($aData['iEventId']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Parameter is not valid! "
            );
        }

        if (Phpfox::isUser())
        {
            $this->database()->select('ei.invite_id, ei.rsvp_id, ')->leftJoin(Phpfox::getT('event_invite'), 'ei', 'ei.event_id = e.event_id AND ei.invited_user_id = ' . Phpfox::getUserId());
        }

        if (Phpfox::isModule('friend'))
        {
            $this->database()->select('f.friend_id AS is_friend, ')->leftJoin(Phpfox::getT('friend'), 'f', "f.user_id = e.user_id AND f.friend_user_id = " . Phpfox::getUserId());
        }
        else
        {
            $this->database()->select('0 as is_friend, ');
        }
        /**
         * @var array
         */
        $aEvent = $this->database()->select('e.*, ' . (Phpfox::getParam('core.allow_html') ? 'et.description_parsed' : 'et.description') . ' AS description, ' . Phpfox::getUserField())
                ->from(Phpfox::getT('event'), 'e')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = e.user_id')
                ->join(Phpfox::getT('event_text'), 'et', 'et.event_id = e.event_id')
                ->where('e.event_id = ' . (int) $aData['iEventId'])
                ->execute('getRow');

        if (!isset($aEvent['event_id']))
        {
            return array(
                'error_code' => 1,
                'error_message' => Phpfox::getPhrase('event.the_event_you_are_looking_for_does_not_exist_or_has_been_removed')
            );
        }
        
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('event', $aEvent['event_id'], $aEvent['user_id'], $aEvent['privacy'], $aEvent['is_friend'], true))
		{
			return array(
                'result' => 0, 
                'error_code' => 1, 
                'message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time')
            );
		}

        // Delete old image.
        if (!empty($aEvent['image_path']))
        {
            if ($this->deleteImage($aEvent['event_id']))
            {
                
            }
        }
        /**
         * @var array|bool
         */
        $aImage = false;
        if (isset($_FILES['image']['name']) && ($_FILES['image']['name'] != ''))
        {
            $aImage = Phpfox::getLib('file')->load('image', array(
                'jpg',
                'gif',
                'png'
                    ), (Phpfox::getUserParam('event.max_upload_size_event') === 0 ? null : (Phpfox::getUserParam('event.max_upload_size_event') / 1024))
            );
        }
        if ($aImage === false)
        {
            return array(
                'error_code' => 1,
                'error_message' => Phpfox_Error::get()
            );
        }

        $oImage = Phpfox::getLib('image');
        /**
         * @var string
         */
        $sFileName = Phpfox::getLib('file')->upload('image', Phpfox::getParam('event.dir_image'), $aEvent['event_id']);
        /**
         * @var int
         */
        $iFileSizes = filesize(Phpfox::getParam('event.dir_image') . sprintf($sFileName, ''));

        $aSql['image_path'] = $sFileName;
        $aSql['server_id'] = Phpfox::getLib('request')->getServer('PHPFOX_SERVER_ID');
        /**
         * @var int
         */
        $iSize = 50;
        $oImage->createThumbnail(Phpfox::getParam('event.dir_image') . sprintf($sFileName, ''), Phpfox::getParam('event.dir_image') . sprintf($sFileName, '_' . $iSize), $iSize, $iSize);
        $iFileSizes += filesize(Phpfox::getParam('event.dir_image') . sprintf($sFileName, '_' . $iSize));

        $iSize = 120;
        $oImage->createThumbnail(Phpfox::getParam('event.dir_image') . sprintf($sFileName, ''), Phpfox::getParam('event.dir_image') . sprintf($sFileName, '_' . $iSize), $iSize, $iSize);
        $iFileSizes += filesize(Phpfox::getParam('event.dir_image') . sprintf($sFileName, '_' . $iSize));

        $iSize = 200;
        $oImage->createThumbnail(Phpfox::getParam('event.dir_image') . sprintf($sFileName, ''), Phpfox::getParam('event.dir_image') . sprintf($sFileName, '_' . $iSize), $iSize, $iSize);
        $iFileSizes += filesize(Phpfox::getParam('event.dir_image') . sprintf($sFileName, '_' . $iSize));

        // Update user space usage
        Phpfox::getService('user.space')->update(Phpfox::getUserId(), 'event', $iFileSizes);
        /**
         * @var bool
         */
        $bResult = $this->database()->update(Phpfox::getT('event'), $aSql, 'event_id = ' . $aEvent['event_id']);

        if ($bResult)
        {
            return array(
                'result' => true,
                'error_code' => 0,
                'error_message' => " Upload image for event successfully! "
            );
        }
        else
        {
            return array(
                'error_code' => 1,
                'error_message' => " Upload fail! "
            );
        }
    }
    /**
     * Delete image.
     * @param int $iId
     * @return boolean
     */
    public function deleteImage($iId)
	{
		$aEvent = $this->database()->select('user_id, image_path')
			->from(Phpfox::getT('event'))
			->where('event_id = ' . (int) $iId)
			->execute('getRow');		
			
		if (!isset($aEvent['user_id']))
		{
			return Phpfox_Error::set('Unable to find the event.');
		}
			
		if (!Phpfox::getService('mfox.auth')->hasAccess('event', 'event_id', $iId, 'event.can_edit_own_event', 'event.can_edit_other_event', $aEvent['user_id']))
		{
			return Phpfox_Error::set(Phpfox::getPhrase('event.you_do_not_have_sufficient_permission_to_modify_this_event'));
		}			
		
		if (!empty($aEvent['image_path']))
		{
            /**
             * @var array
             */
			$aImages = array(
				Phpfox::getParam('event.dir_image') . sprintf($aEvent['image_path'], ''),
				Phpfox::getParam('event.dir_image') . sprintf($aEvent['image_path'], '_50'),
				Phpfox::getParam('event.dir_image') . sprintf($aEvent['image_path'], '_120'),
				Phpfox::getParam('event.dir_image') . sprintf($aEvent['image_path'], '_200')
			);			
			/**
             * @var int
             */
			$iFileSizes = 0;
			foreach ($aImages as $sImage)
			{
				if (file_exists($sImage))
				{
					$iFileSizes += filesize($sImage);
					
					Phpfox::getLib('file')->unlink($sImage);
				}
			}
			
			if ($iFileSizes > 0)
			{
				Phpfox::getService('user.space')->update($aEvent['user_id'], 'event', $iFileSizes, '-');
			}
		}

		$this->database()->update(Phpfox::getT('event'), array('image_path' => null), 'event_id = ' . (int) $iId);	
		
		return true;
	}
    
    /**
     * Input data:
     * + iEventId: int, optional.
     * 
     * Output data:
     * + iNumGoing: int.
     * + iNumAll: int.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see event/getnumberguestlist
     * 
     * @param array $aData
     * @return array
     */
    public function getnumberguestlist($aData)
    {
        if (!isset($aData['iEventId']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Parameter is not valid! "
            );
        }
        /**
         * @var array
         */
        $aEvent = Phpfox::getService('event')->getEvent((int) $aData['iEventId']);

        if (!isset($aEvent['event_id']))
        {
            return array(
                'error_code' => 1,
                'error_message' => Phpfox::getPhrase('event.the_event_you_are_looking_for_does_not_exist_or_has_been_removed')
            );
        }
        
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('event', $aEvent['event_id'], $aEvent['user_id'], $aEvent['privacy'], $aEvent['is_friend'], true))
		{
			return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'));
		}
        /**
         * @var int
         */
        $iTotalGoing = $this->database()
                ->select('count(ei.invite_id)')
                ->from(Phpfox::getT('event_invite'), 'ei')
                ->where('ei.event_id = ' . (int) $aData['iEventId'] . ' AND ei.rsvp_id = 1')
                ->execute('getslavefield');
        /**
         * @var int
         */
        $iTotalAll = $this->database()
                ->select('count(ei.invite_id)')
                ->from(Phpfox::getT('event_invite'), 'ei')
                ->where('ei.event_id = ' . (int) $aData['iEventId'])
                ->execute('getslavefield');

        return array('iNumGoing' => $iTotalGoing, 'iNumAll' => $iTotalAll);
    }

    /**
     * Input data:
     * + iEventId: int, required.
     * + iPage: int, optional. Not use.
     * + iLastTime: int, optional.
     * + iAmountOfFeed: int, optional.
     * + sAction: string, optional. Ex: "more" or "new".
     * + iUserId: int, optional. Not use. Do not send it when get feet event detail
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
     * + error_code: int.
     * + error_message: string.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see event/getfeed
     * 
     * @param array $aData {"iUserId":"1","LastFeedIdViewed":"1","amountOfFeed":"5"}
     * @return array
     */
    public function getfeed($aData)
    {
        if (!isset($aData['iEventId']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Parameter is not valid! "
            );
        }
        /**
         * @var array
         */
        $aEvent = Phpfox::getService('event')->getEvent((int) $aData['iEventId']);
        
        if (!isset($aEvent['event_id']))
        {
            return array(
                'error_code' => 1,
                'error_message' => Phpfox::getPhrase('event.the_event_you_are_looking_for_does_not_exist_or_has_been_removed')
            );
        }

        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('event', $aEvent['event_id'], $aEvent['user_id'], $aEvent['privacy'], $aEvent['is_friend'], true))
		{
			return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'));
		}
        /**
         * @var bool
         */
        $bCanPostComment = true;
        if (isset($aEvent['privacy_comment']) && $aEvent['user_id'] != Phpfox::getUserId() && !Phpfox::getUserParam('privacy.can_comment_on_all_items'))
        {
            switch ($aEvent['privacy_comment']) {
                // Everyone is case 0. Skipped.
                // Friends only
                case 1:
                    if (!Phpfox::getService('friend')->isFriend(Phpfox::getUserId(), $aEvent['user_id']))
                    {
                        $bCanPostComment = false;
                    }
                    break;
                // Friend of friends
                case 2:
                    if (!Phpfox::getService('friend')->isFriendOfFriend($aEvent['user_id']))
                    {
                        $bCanPostComment = false;
                    }
                    break;
                // Only me
                case 3:
                    $bCanPostComment = false;
                    break;
            }
        }
        /**
         * @var array
         */
        $aCallback = false;
        if ($aEvent['item_id'] && Phpfox::hasCallback($aEvent['module_id'], 'viewEvent'))
        {
            $aCallback = Phpfox::callback($aEvent['module_id'] . '.viewEvent', $aEvent['item_id']);

            if ($aEvent['module_id'] == 'pages' && !Phpfox::getService('pages')->hasPerm($aCallback['item_id'], 'event.view_browse_events'))
            {
                return array(
                    'error_code' => 1,
                    'error_message' => 'Unable to view this item due to privacy settings.'
                );
            }
        }

        if (Phpfox::getUserId())
        {
            /**
             * @var bool
             */
            $bIsBlocked = Phpfox::getService('user.block')->isBlocked($aEvent['user_id'], Phpfox::getUserId());
            if ($bIsBlocked)
            {
                $bCanPostComment = false;
            }
        }
        /**
         * @var int
         */
        $iUserId = isset($aData['iUserId']) ? $aData['iUserId'] : 0;
        $bIsCustomFeedView = false;
        $sCustomViewType = null;
        /**
         * @var array
         */
        $aFeedCallback = array(
            'module' => 'event',
            'table_prefix' => 'event_',
            'ajax_request' => 'event.addFeedComment',
            'item_id' => $aData['iEventId'],
            'disable_share' => ($bCanPostComment ? false : true)
        );
        /**
         * @var bool
         */
        $bIsProfile = (is_numeric($iUserId) && $iUserId > 0);

        if ($bIsProfile)
        {
            define('PHPFOX_IS_USER_PROFILE', true);
        }

        if (defined('PHPFOX_IS_USER_PROFILE') && !Phpfox::getService('user.privacy')->hasAccess($iUserId, 'feed.view_wall'))
        {
            return array(
                'error_code' => 1,
                'error_message' => "You can't view feeds because of having privacy problem! "
            );
        }

        if (defined('PHPFOX_IS_PAGES_VIEW') && !Phpfox::getService('pages')->hasPerm(null, 'pages.share_updates'))
        {
            $aFeedCallback['disable_share'] = true;
        }
        /**
         * @var int
         */
        $iFeedPage = isset($aData['iPage']) ? $aData['iPage'] : 0;

        if ($bIsProfile)
        {
            if (!Phpfox::getService('user.privacy')->hasAccess($iUserId, 'feed.display_on_profile'))
            {
                return array(
                    'error_code' => 1,
                    'error_message' => "You can't view feeds because of having privacy problem! "
                );
            }
        }
        if (isset($aData['sAction']) && $aData['sAction'] == 'new')
        {
            return Phpfox::getService('mfox.feed')->setCallback($aFeedCallback)->getNew($aData);
        }
        else
        {
            return Phpfox::getService('mfox.feed')->setCallback($aFeedCallback)->get($aData);
        }
    }

    /**
     * Input data:
     * + sUserStatus: string, required. User will send the message.
     * + iCallbackItemId: int, required. Even id.
     * + sCallbackModule: string, required. Ex: "event".
     * + bIsUserProfile: bool, optional. In profile or not.
     * + iProfileUserId: int, optional. profile user id.
     * + iGroupId: int, optional. Group user id. Not use.
     * + iIframe: int. optional. Not use.
     * + sMethod: string. optional. Not use.
     * 
     * Output data:
     * + iCommentId: int.
     * + result: int.
     * + error_code: int.
     * + error_message: string.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see event/addfeedcomment
     * 
     * @param array $aData
     * @return array
     */
    public function addfeedcomment($aData)
    {
        /**
         * @var array
         */
        $aVals = array(
            'user_status' => isset($aData['sUserStatus']) ? $aData['sUserStatus'] : '',
            'callback_item_id' => isset($aData['iCallbackItemId']) ? $aData['iCallbackItemId'] : '',
            'callback_module' => isset($aData['sCallbackModule']) ? $aData['sCallbackModule'] : 'event',
            'is_user_profile' => isset($aData['bIsUserProfile']) ? $aData['bIsUserProfile'] : 0,
            'profile_user_id' => isset($aData['iProfileUserId']) ? $aData['iProfileUserId'] : 0,
            'group_id' => isset($aData['iGroupId']) ? $aData['iGroupId'] : $aData['iCallbackItemId'],
            'iframe' => isset($aData['iIframe']) ? $aData['iIframe'] : 1,
            'method' => isset($aData['sMethod']) ? $aData['sMethod'] : 'simple'
        );

        if (Phpfox::getLib('parse.format')->isEmpty($aVals['user_status']))
        {
            return array(
                'error_code' => 1,
                'error_message' => Phpfox::getPhrase('user.add_some_text_to_share')
            );
        }
        /**
         * @var array
         */
        $aEvent = Phpfox::getService('event')->getForEdit($aVals['callback_item_id'], true);

        if (!isset($aEvent['event_id']))
        {
            return array(
                'error_code' => 1,
                'error_message' => Phpfox::getPhrase('event.unable_to_find_the_event_you_are_trying_to_comment_on')
            );
        }

        if (($iFlood = Phpfox::getUserParam('comment.comment_post_flood_control')) !== 0)
        {
            /**
             * @var array
             */
            $aFlood = array(
                'action' => 'last_post', // The SPAM action
                'params' => array(
                    'field' => 'time_stamp', // The time stamp field
                    'table' => Phpfox::getT('event_feed'), // Database table we plan to check
                    'condition' => 'type_id = \'' . $this->database()->escape('event_comment') . '\' AND user_id = ' . Phpfox::getUserId(), // Database WHERE query
                    'time_stamp' => $iFlood * 60 // Seconds);
                )
            );

            // actually check if flooding
            if (Phpfox::getLib('spam')->check($aFlood))
            {
                return array(
                    'error_message' => Phpfox::getPhrase('comment.posting_a_comment_a_little_too_soon_total_time', array('total_time' => Phpfox::getLib('spam')->getWaitTime())),
                    'error_code' => 1,
                    'result' => 0
                );
            }
        }
        /**
         * @var bool
         */
        $bCanPostComment = true;
        if (isset($aEvent['privacy_comment']) && $aEvent['user_id'] != Phpfox::getUserId() && !Phpfox::getUserParam('privacy.can_comment_on_all_items'))
        {
            switch ($aEvent['privacy_comment']) {
                // Everyone is case 0. Skipped.
                // Friends only
                case 1:
                    if (!Phpfox::getService('friend')->isFriend(Phpfox::getUserId(), $aEvent['user_id']))
                    {
                        $bCanPostComment = false;
                    }
                    break;
                // Friend of friends
                case 2:
                    if (!Phpfox::getService('friend')->isFriendOfFriend($aEvent['user_id']))
                    {
                        $bCanPostComment = false;
                    }
                    break;
                // Only me
                case 3:
                    $bCanPostComment = false;
                    break;
            }
        }
        /**
         * @var array
         */
        $aCallback = false;
        if ($aEvent['item_id'] && Phpfox::hasCallback($aEvent['module_id'], 'viewEvent'))
        {
            $aCallback = Phpfox::callback($aEvent['module_id'] . '.viewEvent', $aEvent['item_id']);

            if ($aEvent['module_id'] == 'pages' && !Phpfox::getService('pages')->hasPerm($aCallback['item_id'], 'event.view_browse_events'))
            {
                return array(
                    'error_code' => 1,
                    'error_message' => 'Unable to comment this item due to privacy settings.'
                );
            }
        }

        if (Phpfox::getUserId())
        {
            $bIsBlocked = Phpfox::getService('user.block')->isBlocked($aEvent['user_id'], Phpfox::getUserId());
            if ($bIsBlocked)
            {
                $bCanPostComment = false;
            }
        }

        if (!$bCanPostComment)
        {
            return array(
                'error_code' => 1,
                'error_message' => 'Unable to comment this item due to privacy settings.'
            );
        }
        /**
         * @var string
         */
        $sLink = Phpfox::permalink('event', $aEvent['event_id'], $aEvent['title']);
        $aCallback = array(
            'module' => 'event',
            'table_prefix' => 'event_',
            'link' => $sLink,
            'email_user_id' => $aEvent['user_id'],
            'subject' => Phpfox::getPhrase('event.full_name_wrote_a_comment_on_your_event_title', array('full_name' => Phpfox::getUserBy('full_name'), 'title' => $aEvent['title'])),
            'message' => Phpfox::getPhrase('event.full_name_wrote_a_comment_on_your_event_message', array('full_name' => Phpfox::getUserBy('full_name'), 'link' => $sLink, 'title' => $aEvent['title'])),
            'notification' => 'event_comment',
            'feed_id' => 'event_comment',
            'item_id' => $aEvent['event_id']
        );

        $aVals['parent_user_id'] = $aVals['callback_item_id'];

        if (isset($aVals['user_status']) && ($iId = Phpfox::getService('feed.process')->callback($aCallback)->addComment($aVals)))
        {
            $this->database()->updateCounter('event', 'total_comment', 'event_id', $aEvent['event_id']);

            return array(
                'error_code' => 0,
                'iCommentId' => $iId,
                'message' => 'This item has successfully been submitted.'
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
     * + iEventId: int, required.
     * + iRSVP: int, optional.
     * + iAmountOfInvite:: int, optional.
     * + iLastInviteIdViewed: int, optional.
     * 
     * Output data:
     * + iInviteId: int.
     * + iEventId: int.
     * + iTypeId: int.
     * + iRSVP: int.
     * + iUserId: int.
     * + sFullName: string.
     * + sUserImage: string.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see event/viewgetlist
     * 
     * @param array $aData
     * @return array
     */
    public function viewgetlist($aData)
    {
        if (!isset($aData['iEventId']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Parameter is not valid! "
            );
        }
        /**
         * @var bool
         */
        $bUseId = true;

        if (Phpfox::isUser())
        {
            $this->database()->select('ei.invite_id, ei.rsvp_id, ')->leftJoin(Phpfox::getT('event_invite'), 'ei', 'ei.event_id = e.event_id AND ei.invited_user_id = ' . Phpfox::getUserId());
        }

        if (Phpfox::isModule('friend'))
        {
            $this->database()->select('f.friend_id AS is_friend, ')->leftJoin(Phpfox::getT('friend'), 'f', "f.user_id = e.user_id AND f.friend_user_id = " . Phpfox::getUserId());
        }
        else
        {
            $this->database()->select('0 as is_friend, ');
        }
        /**
         * @var array
         */
        $aEvent = $this->database()->select('e.*, c.name AS country_name, ' . (Phpfox::getParam('core.allow_html') ? 'et.description_parsed' : 'et.description') . ' AS description, ' . Phpfox::getUserField())
                ->from(Phpfox::getT('event'), 'e')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = e.user_id')
                ->join(Phpfox::getT('event_text'), 'et', 'et.event_id = e.event_id')
                ->leftJoin(Phpfox::getT('country'), 'c', 'c.country_iso = e.country_iso')
                ->where('e.event_id = ' . (int) $aData['iEventId'])
                ->execute('getRow');

        if (!isset($aEvent['event_id']))
        {
            return array(
                'error_code' => 1,
                'error_message' => Phpfox::getPhrase('event.the_event_you_are_looking_for_does_not_exist_or_has_been_removed')
            );
        }

		if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('event', $aEvent['event_id'], $aEvent['user_id'], $aEvent['privacy'], $aEvent['is_friend'], true))
		{
			return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'));
		}
		/**
         * @var int
         */
        $iRsvp = isset($aData['iRSVP']) ? (int) $aData['iRSVP'] : 1;
        $iPage = 0;
        /**
         * @var int
         */
        $iPageSize = isset($aData['iAmountOfInvite']) ? (int) $aData['iAmountOfInvite'] : 5;

        if (isset($aData['iLastInviteIdViewed']) && $aData['iLastInviteIdViewed'] > 0)
        {
            $sCountCond = ' AND invite_id > ' . (int) $aData['iLastInviteIdViewed'];
            $sGetCond = ' AND ei.invite_id > ' . (int) $aData['iLastInviteIdViewed'];
        }
        else
        {
            $sCountCond = '';
            $sGetCond = '';
        }
        /**
         * @var array
         */
        $aEvent = Phpfox::getService('event')->getEvent($aData['iEventId'], true);

        if (!isset($aEvent['event_id']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Event is not valid! "
            );
        }
        /**
         * @var array
         */
        $aInvites = array();
        /**
         * @var int
         */
        $iCnt = $this->database()->select('COUNT(invite_id)')
                ->from(Phpfox::getT('event_invite'))
                ->where('event_id = ' . (int) $aEvent['event_id'] . ' AND rsvp_id = ' . (int) $iRsvp . $sCountCond)
                ->execute('getSlaveField');

        if ($iCnt)
        {
            /**
             * @var array
             */
            $aInvites = $this->database()
                    ->select('ei.*, ' . Phpfox::getUserField())
                    ->from(Phpfox::getT('event_invite'), 'ei')
                    ->leftJoin(Phpfox::getT('user'), 'u', 'u.user_id = ei.invited_user_id')
                    ->where('ei.event_id = ' . (int) $aEvent['event_id'] . ' AND ei.rsvp_id = ' . (int) $iRsvp . $sGetCond)
                    ->limit($iPage, $iPageSize, $iCnt)
                    ->order('ei.invite_id DESC')
                    ->execute('getSlaveRows');
        }

        /**
         * @var array
         */
        $aResult = array();
        foreach ($aInvites as $aInvite)
        {
            if (is_file(Phpfox::getParam('core.dir_pic') . 'user' . PHPFOX_DS . sprintf($aInvite['user_image'], MAX_SIZE_OF_USER_IMAGE)))
            {
                $sUserImage = Phpfox::getParam('core.url_user') . sprintf($aInvite['user_image'], MAX_SIZE_OF_USER_IMAGE);
            }
            else
            {
                $sUserImage = Phpfox::getParam('core.path') . "theme/frontend/default/style/default/image/noimage/profile_50.png";
            }
            
            $aResult[] = array(
                'iInviteId' => $aInvite['invite_id'],
                'iEventId' => $aInvite['event_id'],
                'iTypeId' => $aInvite['type_id'],
                'iRSVP' => $aInvite['rsvp_id'],
                'iUserId' => $aInvite['user_id'],
                'sFullName' => $aInvite['full_name'],
                'sUserImage' => $sUserImage
            );
        }
        return $aResult;
    }

    /**
     * @see Event_Service_Process
     * Input data:
     * + iEventId: int, required.
     * 
     * Output data:
     * + error_code: int.
     * + error_message: string.
     * + message: string.
     * + result: bool.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see event/delete
     * 
     * @param array $aData
     * @return arra
     */
    public function delete($aData)
    {
        if (!isset($aData['iEventId']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Parameter is not valid! "
            );
        }
		/**
         * @var int
         */
		$iEventId = (int) $aData['iEventId'];
		
        if ($this->deleteEvent($iEventId))
        {
            return array(
                'result' => true, 
                'message' => Phpfox::getPhrase('event.event_successfully_deleted')
            );
        }

        return array(
            'error_code' => 1,
            'error_message' => Phpfox_Error::get()
        );
    }
    
    /**
     * Using to delete event.
     * @param int $iId
     * @param array $aEvent
     * @return string
     */
	public function deleteEvent($iId, &$aEvent = null)
	{
        /**
         * @var mix
         */
		$mReturn = true;
		if ($aEvent === null)
		{
            /**
             * @var array
             */
			$aEvent = $this->database()->select('user_id, module_id, item_id, image_path, is_sponsor, is_featured')
				->from(Phpfox::getT('event'))
				->where('event_id = ' . (int) $iId)
				->execute('getRow');
			
			if ($aEvent['module_id'] == 'pages' && Phpfox::getService('pages')->isAdmin($aEvent['item_id']))
			{
				$mReturn = Phpfox::getService('pages')->getUrl($aEvent['item_id']) . 'event/';
			}
			else
			{
				if (!isset($aEvent['user_id']))
				{
					return Phpfox_Error::set(Phpfox::getPhrase('event.unable_to_find_the_event_you_want_to_delete'));
				}

				if (!Phpfox::getService('mfox.auth')->hasAccess('event', 'event_id', $iId, 'event.can_delete_own_event', 'event.can_delete_other_event', $aEvent['user_id']))
				{
					return Phpfox_Error::set(Phpfox::getPhrase('event.you_do_not_have_sufficient_permission_to_delete_this_listing'));
				}
			}
		}
		
		if (!empty($aEvent['image_path']))
		{
            /**
             * @var array
             */
			$aImages = array(
				Phpfox::getParam('event.dir_image') . sprintf($aEvent['image_path'], ''),
				Phpfox::getParam('event.dir_image') . sprintf($aEvent['image_path'], '_50'),
				Phpfox::getParam('event.dir_image') . sprintf($aEvent['image_path'], '_120'),
				Phpfox::getParam('event.dir_image') . sprintf($aEvent['image_path'], '_200')
			);			
			/**
             * @var int
             */
			$iFileSizes = 0;
			foreach ($aImages as $sImage)
			{
				if (file_exists($sImage))
				{
					$iFileSizes += filesize($sImage);
					
					Phpfox::getLib('file')->unlink($sImage);
				}
			}
			
			if ($iFileSizes > 0)
			{
				Phpfox::getService('user.space')->update($aEvent['user_id'], 'event', $iFileSizes, '-');
			}
		}
		
		(Phpfox::isModule('comment') ? Phpfox::getService('comment.process')->deleteForItem(null, $iId, 'event') : null);		
		(Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->delete('event', $iId) : null);
		(Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->delete('comment_event', $iId) : null);
		/**
         * @var array
         */
		$aInvites = $this->database()->select('invite_id, invited_user_id')
			->from(Phpfox::getT('event_invite'))
			->where('event_id = ' . (int) $iId)
			->execute('getSlaveRows');
			
		foreach ($aInvites as $aInvite)
		{
			(Phpfox::isModule('request') ? Phpfox::getService('request.process')->delete('event_invite', $aInvite['invite_id'], $aInvite['invited_user_id']) : false);			
		}		
		
		$this->database()->delete(Phpfox::getT('event'), 'event_id = ' . (int) $iId);
		$this->database()->delete(Phpfox::getT('event_text'), 'event_id = ' . (int) $iId);
		$this->database()->delete(Phpfox::getT('event_category_data'), 'event_id = ' . (int) $iId);
		$this->database()->delete(Phpfox::getT('event_invite'), 'event_id = ' . (int) $iId);
        /**
         * @var int
         */
		$iTotalEvent = $this->database()
                        ->select('total_event')
                        ->from(Phpfox::getT('user_field'))
                        ->where('user_id =' . (int)$aEvent['user_id'])->execute('getSlaveField');
        $iTotalEvent = $iTotalEvent -1;
        
		if ($iTotalEvent > 0)
		{
			$this->database()->update(Phpfox::getT('user_field'),
                        array('total_event' => $iTotalEvent),
                        'user_id = ' . (int)$aEvent['user_id']);
		}
        
		if (isset($aEvent['is_sponsor']) && $aEvent['is_sponsor'] == 1)
		{
			$this->cache()->remove('event_sponsored');
		}
		if (isset($aEvent['is_featured']) && $aEvent['is_featured'])
		{
			$this->cache()->remove('event_featured', 'substr');
		}
		
		return $mReturn;
	}
	
    /**
     * @see Event_Service_Process
     * 
     * Input data:
     * + iEventId: int, required.
     * + sCategory: string, required.
     * + start_year: int, required.
     * + end_year: int, required.
     * 
     * Output data:
     * + error_code: int.
     * + error_message: string.
     * 
     * @param array $aData
     * @return array|bool
     */
    public function edit($aData)
    {
        if (!isset($aData['iEventId']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Parameter is not valid! "
            );
        }
        /**
         * @var array
         */
        $aEvent = Phpfox::getService('event')->getForEdit($aData['iEventId']);

        if (!$aEvent)
        {
            return array(
                'error_code' => 1,
                'error_message' => " Event is not valid! "
            );
        }
        /**
         * @var array
         */
        $aCategories = array();
        if (isset($aData['sCategory']))
        {
            $aTemp = explode(',', $aData['sCategory']);
            foreach ($aTemp as $iCategory)
            {
                if (is_numeric($iCategory))
                {
                    $aCategories[] = $iCategory;
                }
            }
        }
        $aData['category'] = $aCategories;
        unset($aData['sCategory']);
        unset($aData['iEventId']);

		$iLimitYear = date("Y") + 1;
		if (!isset($aData['start_year']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Start year is not valid! "
            );
        }
		if ($aData['start_year'] >  $iLimitYear)
		{
			return array(
                'error_code' => 1,
                'error_message' => " Start year must be less than or equal to " . $iLimitYear . " !"
            );
		}
		$aData['end_year'] = isset($aData['end_year']) ? (int) $aData['end_year'] : $aData['start_year'];
		// Limit end year.
		if ($aData['end_year'] >  $iLimitYear)
		{
			return array(
                'error_code' => 1,
                'error_message' => " End year must be less than or equal to " . $iLimitYear . " !"
            );
		}
		
        return Phpfox::getService('event.process')->update($aEvent['event_id'], $aData, $aEvent);
    }

    /**
     * Input data:
     * + iAmountOfEvent: int, optional.
     * + iPage: int, optional.
     * + bIsUserProfile: bool, optional. (In profile)
     * + iUserId: int, optional. Default 0. Number 0 is all friend.
     * + bCallback: bool, optional.
     * + iCallbackItem: int, optional.
     * + sView: string, optional.
     * + sModuleId: string, optional. (In page)
     * + iItemId: int, optional. (In page)
     * + iSponsor: int, optional.
     * + iCategoryId: int, optional.
     * + sWhen: string, optional.
     * 
     * Output data:
     * + iEventId: int.
     * + sTitle: string.
     * + bCanPostComment: bool.
     * + sEventImageUrl: string.
     * + sFullName: string.
     * + iUserId: int.
     * + sUserImageUrl: string.
     * + iStartTime: int.
     * + sStartTime: string.
     * + sStartFullTime: string.
     * + iEndTime: int.
     * + sEndTime: string.
     * + sEndFullTime: string.
     * + iTimeStamp: int.
     * + iTotalComment: int.
     * + iTotalLike: int.
     * + iTotalDislike: int.
     * + bIsSponsor: bool.
     * + bIsFeatured: bool.
     * + sCountryISO: string.
     * + sLocation: string.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see event/upcoming
     * 
     * @param array $aData
     * @return array
     */
    public function upcoming($aData)
    {
        return $this->my($aData);
    }

    /**
     * Input data:
     * + iAmountOfEvent: int, optional.
     * + iPage: int, optional.
     * + bIsUserProfile: bool, optional. (In profile)
     * + iUserId: int, optional. Default login user id.
     * + bCallback: bool, optional.
     * + iCallbackItem: int, optional.
     * + sView: string, optional. Default 'my'.
     * + sModuleId: string, optional. (In page)
     * + iItemId: int, optional. (In page)
     * + iSponsor: int, optional.
     * + iCategoryId: int, optional. Default -1.
     * + sWhen: string, optional. Default 'past'.
     * 
     * Output data:
     * + iEventId: int.
     * + sTitle: string.
     * + bCanPostComment: bool.
     * + sEventImageUrl: string.
     * + sFullName: string.
     * + iUserId: int.
     * + sUserImageUrl: string.
     * + iStartTime: int.
     * + sStartTime: string.
     * + sStartFullTime: string.
     * + iEndTime: int.
     * + sEndTime: string.
     * + sEndFullTime: string.
     * + iTimeStamp: int.
     * + iTotalComment: int.
     * + iTotalLike: int.
     * + iTotalDislike: int.
     * + bIsSponsor: bool.
     * + bIsFeatured: bool.
     * + sCountryISO: string.
     * + sLocation: string.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see event/past
     * 
     * @param array $aData
     * @return array
     */
    public function past($aData)
    {
        $aData['iUserId'] = Phpfox::getUserId();
        if (!isset($aData['sWhen']))
        {
            $aData['sWhen'] = 'past';
        }
        return $this->getEvents($aData);
    }
    
     /**
     * Input data:
     * + iAmountOfEvent: int, optional.
     * + iPage: int, optional.
     * + bIsUserProfile: bool, optional. (In profile)
     * + iUserId: int, optional. Default 0. Number 0 is all friend.
     * + bCallback: bool, optional.
     * + iCallbackItem: int, optional.
     * + sView: string, optional. Default 'friend'.
     * + sModuleId: string, optional. (In page)
     * + iItemId: int, optional. (In page)
     * + iSponsor: int, optional.
     * + iCategoryId: int, optional. Default -1.
     * + sWhen: string, optional. Default 'upcoming'.
     * 
     * Output data:
     * + iEventId: int.
     * + sTitle: string.
     * + bCanPostComment: bool.
     * + sEventImageUrl: string.
     * + sFullName: string.
     * + iUserId: int.
     * + sUserImageUrl: string.
     * + iStartTime: int.
     * + sStartTime: string.
     * + sStartFullTime: string.
     * + iEndTime: int.
     * + sEndTime: string.
     * + sEndFullTime: string.
     * + iTimeStamp: int.
     * + iTotalComment: int.
     * + iTotalLike: int.
     * + iTotalDislike: int.
     * + bIsSponsor: bool.
     * + bIsFeatured: bool.
     * + sCountryISO: string.
     * + sLocation: string.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see event/friend
     * 
     * @param array $aData
     * @return array
     */
    public function friend($aData)
    {
        $aData['sView'] = 'friend';
        if (!isset($aData['sWhen']))
        {
            $aData['sWhen'] = 'upcoming';
        }

        return $this->getEvents($aData);
    }

    /**
     * Input data:
     * + iAmountOfEvent: int, optional.
     * + iPage: int, optional.
     * + bIsUserProfile: bool, optional. (In profile)
     * + iUserId: int, optional. (In profile)
     * + bCallback: bool, optional.
     * + iCallbackItem: int, optional.
     * + sView: string, optional.
     * + sModuleId: string, optional. (In page)
     * + iItemId: int, optional. (In page)
     * + iSponsor: int, optional.
     * + iCategoryId: int, optional. Default -1.
     * + sWhen: string, optional. Default ''.
     * 
     * Output data:
     * + iEventId: int.
     * + sTitle: string.
     * + bCanPostComment: bool.
     * + sEventImageUrl: string.
     * + sFullName: string.
     * + iUserId: int.
     * + sUserImageUrl: string.
     * + iStartTime: int.
     * + sStartTime: string.
     * + sStartFullTime: string.
     * + iEndTime: int.
     * + sEndTime: string.
     * + sEndFullTime: string.
     * + iTimeStamp: int.
     * + iTotalComment: int.
     * + iTotalLike: int.
     * + iTotalDislike: int.
     * + bIsSponsor: bool.
     * + bIsFeatured: bool.
     * + sCountryISO: string.
     * + sLocation: string.
     * 
     * @param array $aData
     * @return array
     */
    private function getEvents($aData)
    {
        $sCond = 'TRUE';

        if (!isset($aData['iAmountOfEvent']))
        {
            $aData['iAmountOfEvent'] = 12;
        }
        /**
         * @var int
         */
        $iPage = isset($aData['iPage']) ? (int) $aData['iPage'] - 1 : 0;
        
        if (!isset($aData['bIsUserProfile']))
        {
            $aData['bIsUserProfile'] = false;
        }

        if (!isset($aData['bCallback']))
        {
            $aData['bCallback'] = false;
        }
        else
        {
            if (!isset($aData['iCallbackItem']))
            {
                $aData['iCallbackItem'] = 0;
            }
        }

        if (!isset($aData['sView']))
        {
            $aData['sView'] = '';
        }
        /**
         * @var int
         */
        $iAttending = null;
        /**
         * @var bool
         */
        $bIsUserProfile = false;
        if ($aData['bIsUserProfile'])
        {
            $bIsUserProfile = true;

            if (!isset($aData['iUserId']))
            {
                return array(
                    'error_code' => 1,
                    'error_message' => " User is not valid! "
                );
            }
            /**
             * @var array
             */
            $aUser = Phpfox::getService('user')->getUser($aData['iUserId']);

            if (!$aUser)
            {
                return array(
                    'error_message' => ' User is not valid! ',
                    'error_code' => 1
                );
            }
        }
        
        switch ($aData['sView']) {
            case 'pending':
                if (Phpfox::getUserParam('event.can_approve_events'))
                {
                    $sCond .= ' AND m.view_id = 1 ';
                }
                break;
            case 'my':
                $sCond .= ' AND m.user_id = ' . Phpfox::getUserId() . ' ';
                break;
            default:
                if ($bIsUserProfile)
                {
                    $sCond .= ' AND m.view_id ' . ($aUser['user_id'] == Phpfox::getUserId() ? 'IN(0,2)' : '= 0') . ' AND m.privacy IN(' . (Phpfox::getParam('core.section_privacy_item_browsing') ? '%PRIVACY%' : Phpfox::getService('core')->getForBrowse($aUser)) . ') AND m.user_id = ' . (int) $aUser['user_id'] . ' ';
                }
                elseif (isset($aData['sModuleId']) && isset($aData['iItemId']) && !empty($aData['iItemId']))
                {
                    $sCond .= ' AND m.view_id = 0 AND m.privacy IN(%PRIVACY%) AND m.module_id = \'' . Phpfox::getLib('database')->escape($aData['sModuleId']) . '\' AND m.item_id = ' . (int) $aData['iItemId'] . ' ';
                }
                else
                {
                    switch ($aData['sView']) {
                        case 'attending':
                            $iAttending = 1;
                            break;
                        case 'may-attend':
                            $iAttending = 2;
                            break;
                        case 'not-attending':
                            $iAttending = 3;
                            break;
                        case 'invites':
                            $iAttending = 0;
                            break;
                    }

                    if ($aData['sView'] == 'attending')
                    {
                        $sCond .= ' AND m.view_id = 0 AND m.privacy IN(%PRIVACY%) ';
                    }
                    else
                    {
                        $sCond .= ' AND m.view_id = 0 AND m.privacy IN(%PRIVACY%) AND m.item_id = ' . ($aData['bCallback'] !== false ? (int) $aData['iCallbackItem'] : 0) . ' ';
                    }

                    if ($aData['sView'] == 'friend')
                    {
                        $sCond .= ' AND m.user_id != ' . Phpfox::getUserId() . ' ';
                    }

                    if (isset($aData['iUserId']) && ($aUserSearch = Phpfox::getService('user')->getUser($aData['iUserId'])))
                    {
                        $sCond .= ' AND m.user_id = ' . (int) $aUserSearch['user_id'] . ' ';
                    }
                }
                break;
        }

        if (isset($aData['iSponsor']) == 1)
        {
            $sCond .= ' AND m.is_sponsor != 1 ';
            Phpfox::addMessage(Phpfox::getPhrase('event.sponsor_help'));
        }

        if (isset($aData['iCategoryId']) && $aData['iCategoryId'] > 0)
        {
            $sCond .= ' AND mcd.category_id = ' . (int) $aData['iCategoryId'] . ' ';
        }

        if ($aData['sView'] == 'featured')
        {
            $sCond .= ' AND m.is_featured = 1 ';
        }
        /**
         * @var bool
         */
        $bIsCount = false;

        if (Phpfox::isModule('friend') && $aData['sView'] == 'friend')
        {
            $this->database()->join(Phpfox::getT('friend'), 'friends', 'friends.user_id = m.user_id AND friends.friend_user_id = ' . Phpfox::getUserId());
        }
        
        if (isset($aData['iCategoryId']) && intval($aData['iCategoryId']) >= 0)
        {
            $this->database()->innerJoin(Phpfox::getT('event_category_data'), 'mcd', 'mcd.event_id = m.event_id');

            if (!$bIsCount)
            {
                $this->database()->group('m.event_id');
            }
        }

        if ($iAttending !== null)
        {
            $this->database()->innerJoin(Phpfox::getT('event_invite'), 'ei', 'ei.event_id = m.event_id AND ei.rsvp_id = ' . (int) $iAttending . ' AND ei.invited_user_id = ' . Phpfox::getUserId());

            if (!$bIsCount)
            {
                $this->database()->select('ei.rsvp_id, ');
                $this->database()->group('m.event_id');
            }
        }
        else
        {
            if (Phpfox::isUser())
            {
                $this->database()->leftJoin(Phpfox::getT('event_invite'), 'ei', 'ei.event_id = m.event_id AND ei.invited_user_id = ' . Phpfox::getUserId());

                if (!$bIsCount)
                {
                    $this->database()->select('ei.rsvp_id, ');
                    $this->database()->group('m.event_id');
                }
            }
        }

        if ($aData['sView'] != 'my')
        {
            if ($iAttending !== null)
            {
                $this->database()->group('m.event_id');
            }
        }

        if (Phpfox::isUser() && Phpfox::isModule('like'))
        {
            $this->database()->select('lik.like_id AS is_liked, ')->leftJoin(Phpfox::getT('like'), 'lik', 'lik.type_id = \'event\' AND lik.item_id = m.event_id AND lik.user_id = ' . Phpfox::getUserId());
        }
        
        if (isset($aData['sWhen']))
        {
            /**
             * @var int
             */
            $iTimeDisplay = Phpfox::getLib('date')->mktime(0, 0, 0, Phpfox::getTime('m'), Phpfox::getTime('d'), Phpfox::getTime('Y'));

            $sWhenField = 'm.start_time';
            $sSwitch = $aData['sWhen'];

            switch ($sSwitch) {
                case 'today':
                    $iEndDay = Phpfox::getLib('date')->mktime(23, 59, 0, Phpfox::getTime('m'), Phpfox::getTime('d'), Phpfox::getTime('Y'));
                    $sCond .= ' AND (' . $sWhenField . ' >= \'' . Phpfox::getLib('date')->convertToGmt($iTimeDisplay) . '\' AND ' . $sWhenField . ' < \'' . Phpfox::getLib('date')->convertToGmt($iEndDay) . '\') ';
                    break;
                case 'this-week':
                    $sCond .= ' AND ' . $sWhenField . ' >= \'' . Phpfox::getLib('date')->convertToGmt(Phpfox::getLib('date')->getWeekStart()) . '\' ';
                    break;
                case 'this-month':
                    $sCond .= ' AND ' . $sWhenField . ' >= \'' . Phpfox::getLib('date')->convertToGmt(Phpfox::getLib('date')->getThisMonth()) . '\' ';
                    break;
                case 'upcoming':
                    $sCond .= ' AND ' . $sWhenField . ' >= \'' . Phpfox::getLib('date')->convertToGmt($iTimeDisplay) . '\' ';
                    break;
                case 'past':
                    $sCond .= ' AND ' . $sWhenField . ' < \'' . Phpfox::getLib('date')->convertToGmt($iTimeDisplay) . '\' ';
                    break;
                default:
                    break;
            }
        }

        switch ($aData['sView']) {
            case 'friend':
                $sCond = str_replace('%PRIVACY%', '0,1,2', $sCond);
                break;
            case 'my':
                $sCond = str_replace('%PRIVACY%', '0,1,2,3,4', $sCond);
                break;
            default:
                $sCond = str_replace('%PRIVACY%', '0', $sCond);
                break;
        }
        /**
         * @var int
         */
        $iOffset = ($iPage > 0 ? $iPage * $aData['iAmountOfEvent'] : 0);
        /**
         * @var array
         */
        $aEvents = $this->database()
                ->select('m.*, u.user_id, u.profile_page_id, u.server_id AS user_server_id, u.user_name, u.full_name, u.gender, u.user_image, u.is_invisible, u.user_group_id, u.language_id')
                ->from(Phpfox::getT('event'), 'm')
                ->where($sCond)
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = m.user_id')
                ->group('m.event_id')
                ->order('m.start_time ASC')
                ->limit($iOffset, $aData['iAmountOfEvent'])
                ->execute('getRows');
        /**
         * @var array
         */
        $aResult = array();
        
        foreach ($aEvents as $aEvent)
        {
            if (is_file(Phpfox::getParam('event.dir_image') . sprintf($aEvent['image_path'], MAX_SIZE_OF_USER_IMAGE_EVENT)))
            {
                $sEventImageUrl = Phpfox::getParam('event.url_image') . sprintf($aEvent['image_path'], MAX_SIZE_OF_USER_IMAGE_EVENT);
            }
            else
            {
                $sEventImageUrl = Phpfox::getParam('core.path') . "theme/frontend/default/style/default/image/noimage/profile_50.png";
            }
            
            $aResult[] = array(
                'iEventId' => $aEvent['event_id'],
                'sTitle' => $aEvent['title'],
                'bCanPostComment' => Phpfox::getService('mfox.comment')->checkCanPostCommentOnItem($aEvent),
                'sEventImageUrl' => $sEventImageUrl,
                'sFullName' => $aEvent['full_name'],
                'iUserId' => $aEvent['user_id'],
                'sUserImageUrl' => Phpfox::getLib('image.helper')->display(array(
                    'server_id' => $aEvent['user_server_id'],
                    'path' => 'core.url_user',
                    'file' => $aEvent['user_image'],
                    'suffix' => MAX_SIZE_OF_USER_IMAGE,
                    'return_url' => true
                        )
                ),
                'iStartTime' => $aEvent['start_time'],
                'sStartTime' => date('l, F j', $aEvent['start_time']),
                'sStartFullTime' => date('l, F j', $aEvent['start_time']) . ' at ' . date('g:i a', $aEvent['start_time']),
                'iEndTime' => $aEvent['end_time'],
                'sEndTime' => date('l, F j', $aEvent['end_time']),
                'sEndFullTime' => date('l, F j', $aEvent['end_time'])  . ' at ' . date('g:i a', $aEvent['end_time']),
                'iTimeStamp' => $aEvent['time_stamp'],
                'iTotalComment' => $aEvent['total_comment'],
                'iTotalLike' => $aEvent['total_like'],
                'iTotalDislike' => $aEvent['total_dislike'],
                'bIsSponsor' => $aEvent['is_sponsor'],
                'bIsFeatured' => $aEvent['is_featured'],
                'sCountryISO' => $aEvent['country_iso'],
                'sLocation' => $aEvent['location']
            );
        }

        return $aResult;
    }
    /**
     * Input data:
     * + iAmountOfEvent: int, optional.
     * + iPage: int, optional.
     * + bIsUserProfile: bool, optional. (In profile)
     * + iUserId: int, optional. Default login user id. Number 0 is all friend.
     * + bCallback: bool, optional.
     * + iCallbackItem: int, optional.
     * + sView: string, optional. Default 'my'.
     * + sModuleId: string, optional. (In page)
     * + iItemId: int, optional. (In page)
     * + iSponsor: int, optional.
     * + iCategoryId: int, optional. Default -1.
     * + sWhen: string, optional. Default 'upcoming'.
     * 
     * Output data:
     * + iEventId: int.
     * + sTitle: string.
     * + bCanPostComment: bool.
     * + sEventImageUrl: string.
     * + sFullName: string.
     * + iUserId: int.
     * + sUserImageUrl: string.
     * + iStartTime: int.
     * + sStartTime: string.
     * + sStartFullTime: string.
     * + iEndTime: int.
     * + sEndTime: string.
     * + sEndFullTime: string.
     * + iTimeStamp: int.
     * + iTotalComment: int.
     * + iTotalLike: int.
     * + iTotalDislike: int.
     * + bIsSponsor: bool.
     * + bIsFeatured: bool.
     * + sCountryISO: string.
     * + sLocation: string.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see event/my
     * 
     * @param array $aData
     * @return array
     */
    public function my($aData)
    {
        $aData['iUserId'] = Phpfox::getUserId();
        $aData['sView'] = 'my';
        if (!isset($aData['sWhen']))
        {
            $aData['sWhen'] = 'upcoming';
        }
        return $this->getEvents($aData);
    }

    /**
     * Input data: N/A
     * 
     * Output data:
     * + iCategoryId: int.
     * + sName: string.
     * + iParentId: int.
     * + aChild: array
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see event/listcategories
     * 
     * @param array $aData
     * @return array
     */
    public function listcategories($aData)
    {
        /**
         * @var array
         */
        $aParentCategories = $this->database()->select('category_id AS iCategoryId, name AS sName, parent_id AS iParentId')
                ->from(Phpfox::getT('event_category'))
                ->where('parent_id = 0 AND is_active = 1')
                ->order('ordering ASC')
                ->execute('getRows');
        /**
         * @var array
         */
        $aChildCategories = $this->database()->select('category_id AS iCategoryId, name AS sName, parent_id AS iParentId')
                ->from(Phpfox::getT('event_category'))
                ->where('parent_id != 0 AND is_active = 1')
                ->order('ordering ASC')
                ->execute('getRows');
        /**
         * @var int
         */
        $iTotal = count($aParentCategories);
        for ($i = 0; $i < $iTotal; $i++)
        {
            $aTemp = array();
            foreach ($aChildCategories as $aCategory)
            {
                if ($aParentCategories[$i]['iCategoryId'] == $aCategory['iParentId'])
                {
                    $aTemp[] = $aCategory;
                }
            }

            $aParentCategories[$i]['aChild'] = $aTemp;
        }

        return $aParentCategories;
    }

    /**
     * Input data:
     * + sCategory: string, required.
     * + title: string, required.
     * + description: string, optional.
     * + location: string, optional.
     * + start_month: int, required.
     * + start_day: int, required.
     * + start_year: int, required.
     * + start_hour: int, required.
     * + start_minute: int, required.
     * + end_month: int, optional.
     * + end_day: int, optional.
     * + end_year: int, optional.
     * + end_hour: int, optional.
     * + end_minute: int, optional.
     * + address: string, optional.
     * + city: string, optional.
     * + postal_code: string, optional.
     * + country_iso: string, optional.
     * + privacy: int, optional.
     * + privacy_comment: int, optional.
     * + emails: string, optional.
     * + personal_message: string, optional.
     * + image: file, optional.
     * 
     * Output data:
     * + error_code: int.
     * + error_message: string.
     * + iEventId: int.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see event/create
     * 
     * @see Phpfox_Parse_Format
     * @param array $aData
     * @return array
     */
    public function create($aData)
    {
        /**
         * @var int
         */
		$iLimitYear = date("Y") + 1;
		
        if (!isset($aData['sCategory']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Category is not valid! "
            );
        }

        $aData['category'] = explode(',', $aData['sCategory']);
        if (count($aData['category']) == 0)
        {
            return array(
                'error_code' => 1,
                'error_message' => " Category is not valid! "
            );
        }

        if (!isset($aData['title']) || Phpfox::getLib('parse.format')->isEmpty($aData['title']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Title is not valid! "
            );
        }

        if (!isset($aData['description']))
        {
            $aData['description'] = '';
        }

        if (!isset($aData['location']) || Phpfox::getLib('parse.format')->isEmpty($aData['location']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Location is not valid! "
            );
        }
        // For start time.
        if (!isset($aData['start_month']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Start month is not valid! "
            );
        }
        if (!isset($aData['start_day']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Start day is not valid! "
            );
        }
        if (!isset($aData['start_year']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Start year is not valid! "
            );
        }
		if ($aData['start_year'] >  $iLimitYear)
		{
			return array(
                'error_code' => 1,
                'error_message' => " Start year must be less than or equal to " . $iLimitYear . " !"
            );
		}
        if (!isset($aData['start_hour']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Start hour is not valid! "
            );
        }
        if (!isset($aData['start_minute']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Start minute is not valid! "
            );
        }
        // For end time.
        if (!isset($aData['end_month']))
        {
            $aData['end_month'] = $aData['start_month'];
        }
        if (!isset($aData['end_day']))
        {
            $aData['end_day'] = $aData['start_day'];
        }
		$aData['end_year'] = isset($aData['end_year']) ? (int) $aData['end_year'] : $aData['start_year'];
		
		// Limit end year.
		if ($aData['end_year'] >  $iLimitYear)
		{
			return array(
                'error_code' => 1,
                'error_message' => " End year must be less than or equal to " . $iLimitYear . " !"
            );
		}
		
        if (!isset($aData['end_hour']))
        {
            $aData['end_hour'] = $aData['start_hour'];
        }
        if (!isset($aData['end_minute']))
        {
            $aData['end_minute'] = $aData['start_minute'];
        }
        if (!isset($aData['address']))
        {
            $aData['address'] = '';
        }
        if (!isset($aData['city']))
        {
            $aData['city'] = '';
        }
        if (!isset($aData['postal_code']))
        {
            $aData['postal_code'] = '';
        }
        if (!isset($aData['country_iso']))
        {
            $aData['country_iso'] = '';
        }
        if (!isset($aData['privacy']))
        {
            $aData['privacy'] = 0;
        }
        if (!isset($aData['privacy_comment']))
        {
            $aData['privacy_comment'] = 0;
        }
        if (!isset($aData['emails']))
        {
            $aData['emails'] = '';
        }
        if (!isset($aData['personal_message']))
        {
            $aData['personal_message'] = '';
        }
        if (!isset($aData['image']))
        {
            $aData['image'] = null;
        }

        if (($iFlood = Phpfox::getUserParam('event.flood_control_events')) !== 0)
        {
            /**
             * @var array
             */
            $aFlood = array(
                'action' => 'last_post', // The SPAM action
                'params' => array(
                    'field' => 'time_stamp', // The time stamp field
                    'table' => Phpfox::getT('event'), // Database table we plan to check
                    'condition' => 'user_id = ' . Phpfox::getUserId(), // Database WHERE query
                    'time_stamp' => $iFlood * 60 // Seconds);	
                )
            );

            // actually check if flooding
            if (Phpfox::getLib('spam')->check($aFlood))
            {
                Phpfox_Error::set(Phpfox::getPhrase('event.you_are_creating_an_event_a_little_too_soon') . ' ' . Phpfox::getLib('spam')->getWaitTime());
            }
        }

        if (Phpfox_Error::isPassed())
        {
            /**
             * @var int
             */
            $iId = $this->add($aData);

            return array(
                'iEventId' => $iId
            );
        }

        return array(
            'error_code' => 1,
            'error_message' => Phpfox_Error::get()
        );
    }
    /**
     * Verify data when add event.
     * @param array $aVals
     * @param bool $bIsUpdate
     * @return boolean
     */
	private function _verify(&$aVals, $bIsUpdate = false)
	{
		if (isset($aVals['category']) && is_array($aVals['category']))
		{
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

				$this->_aCategories[] = $iCategory;
			}
		}
		
		if (isset($_FILES['image']['name']) && ($_FILES['image']['name'] != ''))
		{
            /**
             * @var array
             */
			$aImage = Phpfox::getLib('file')->load('image', array(
					'jpg',
					'gif',
					'png'
				), (Phpfox::getUserParam('event.max_upload_size_event') === 0 ? null : (Phpfox::getUserParam('event.max_upload_size_event') / 1024))
			);
			
			if ($aImage === false)
			{
				return false;
			}
			
			$this->_bHasImage = true;
		}
		
		if (true)
		{			
            /**
             * @var int
             */
			$iStartTime = Phpfox::getLib('date')->mktime($aVals['start_hour'], $aVals['start_minute'], 0, $aVals['start_month'], $aVals['start_day'], $aVals['start_year']);
            /**
             * @var int
             */
			$iEndTime = Phpfox::getLib('date')->mktime($aVals['end_hour'], $aVals['end_minute'], 0, $aVals['end_month'], $aVals['end_day'], $aVals['end_year']);			
			
			if ($iEndTime < $iStartTime)
			{
				$this->_bIsEndingInThePast = true;
			}
		}
		
		return true;
	}
	/**
     * Add new event.
     * @param array $aVals
     * @param string $sModule
     * @param int $iItem
     * @return boolean
     */
	public function add($aVals, $sModule = 'event', $iItem = 0)
	{		
		if (!$this->_verify($aVals))
		{
			return false;
		}
		
		if (!isset($aVals['privacy']))
		{
			$aVals['privacy'] = 0;
		}
		
		$oParseInput = Phpfox::getLib('parse.input');
		
		if (!Phpfox::getService('mfox.ban')->checkAutomaticBan($aVals))
		{
			return false;
		}
		/**
         * @var int
         */
		$iStartTime = Phpfox::getLib('date')->mktime($aVals['start_hour'], $aVals['start_minute'], 0, $aVals['start_month'], $aVals['start_day'], $aVals['start_year']);		
		if ($this->_bIsEndingInThePast === true)
		{
			$aVals['end_hour'] = ($aVals['start_hour'] + 1);
			$aVals['end_minute'] = $aVals['start_minute'];
			$aVals['end_day'] = $aVals['start_day'];
			$aVals['end_year'] = $aVals['start_year'];			
		}
		/**
         * @var int
         */
		$iEndTime = Phpfox::getLib('date')->mktime($aVals['end_hour'], $aVals['end_minute'], 0, $aVals['end_month'], $aVals['end_day'], $aVals['end_year']);				
		
		if ($iStartTime > $iEndTime)
		{
			$iEndTime = $iStartTime;
		}
		/**
		 * @var array
		 */
		$aSql = array(
			'view_id' => (($sModule == 'event' && Phpfox::getUserParam('event.event_must_be_approved')) ? '1' : '0'),
			'privacy' => (isset($aVals['privacy']) ? $aVals['privacy'] : '0'),
			'privacy_comment' => (isset($aVals['privacy_comment']) ? $aVals['privacy_comment'] : '0'),
			'module_id' => $sModule,
			'item_id' => $iItem,
			'user_id' => Phpfox::getUserId(),
			'title' => $oParseInput->clean($aVals['title'], 255),
			'location' => $oParseInput->clean($aVals['location'], 255),
			'country_iso' => (empty($aVals['country_iso']) ? Phpfox::getUserBy('country_iso') : $aVals['country_iso']),
			'country_child_id' => (isset($aVals['country_child_id']) ? (int) $aVals['country_child_id'] : 0),
			'postal_code' => (empty($aVals['postal_code']) ? null : Phpfox::getLib('parse.input')->clean($aVals['postal_code'], 20)),
			'city' => (empty($aVals['city']) ? null : $oParseInput->clean($aVals['city'], 255)),
			'time_stamp' => PHPFOX_TIME,
			'start_time' => Phpfox::getLib('date')->convertToGmt($iStartTime),
			'end_time' => Phpfox::getLib('date')->convertToGmt($iEndTime),
			'start_gmt_offset' => Phpfox::getLib('date')->getGmtOffset($iStartTime),
			'end_gmt_offset' => Phpfox::getLib('date')->getGmtOffset($iEndTime),
			'address' => (empty($aVals['address']) ? null : Phpfox::getLib('parse.input')->clean($aVals['address']))
		);
		
		if (Phpfox::getUserParam('event.can_add_gmap') && isset($aVals['gmap']) 
				&& is_array($aVals['gmap']) && isset($aVals['gmap']['latitude'])
				&& isset($aVals['gmap']['longitude']))
		{
			$aSql['gmap'] = serialize($aVals['gmap']);
		}
		
		if (!Phpfox_Error::isPassed())
		{
			return false;
		}
		/**
         * @var int
         */
		$iId = $this->database()->insert(Phpfox::getT('event'), $aSql);
		
		if (!$iId)
		{
			return false;
		}
		
		$this->database()->insert(Phpfox::getT('event_text'), array(
				'event_id' => $iId,
				'description' => (empty($aVals['description']) ? null : $oParseInput->clean($aVals['description'])),
				'description_parsed' => (empty($aVals['description']) ? null : $oParseInput->prepare($aVals['description']))
			)
		);		
		
		foreach ($this->_aCategories as $iCategoryId)
		{
			$this->database()->insert(Phpfox::getT('event_category_data'), array('event_id' => $iId, 'category_id' => $iCategoryId));
		}		
		/**
         * @var bool
         */
		$bAddFeed = ($sModule == 'event' ? (Phpfox::getUserParam('event.event_must_be_approved') ? false : true) : true);
		
		if ($bAddFeed === true)
		{
			if ($sModule == 'event' && Phpfox::isModule('feed'))
			{
				Phpfox::getService('feed.process')->add('event', $iId, $aVals['privacy'], (isset($aVals['privacy_comment']) ? (int) $aVals['privacy_comment'] : 0));
			}
			else if (Phpfox::isModule('feed'))
			{
				Phpfox::getService('feed.process')
                        ->callback(Phpfox::callback($sModule . '.getFeedDetails', $iItem))
                        ->add('event', $iId, $aVals['privacy'], (isset($aVals['privacy_comment']) ? (int) $aVals['privacy_comment'] : 0), $iItem);
			}			
			
			Phpfox::getService('user.activity')->update(Phpfox::getUserId(), 'event');
		}
		
		Phpfox::getService('event.process')->addRsvp($iId, 1, Phpfox::getUserId());

		return $iId;
	}
	/**
     * Input data:
     * + iEventId: int, required.
     * 
     * Output data:
     * + iInviteId: int.
     * + iRsvpId: int.
     * + bIsFriend: bool.
     * + iEventId: int.
     * + iViewId: int.
     * + iPrivacy: int.
     * + iPrivacyComment: int.
     * + bIsFeatured: bool.
     * + bIsSponsor: bool.
     * + sEventImageUrl: string.
     * + sFullName: string.
     * + iUserId: int.
     * + sUserImageUrl: string.
     * + iStartTime: int.
     * + sStartTime: string.
     * + sStartFullTime: string.
     * + iEndTime: int.
     * + sEndTime: string.
     * + sEndFullTime: string.
     * + iTimeStamp: int.
     * + iTotalComment: int.
     * + iTotalLike: int.
     * + iTotalDislike: int.
     * + sTitle: string.
     * + sDescription: string.
     * + sCountryISO: string.
     * + sCountryName: string.
     * + sPostalCode: string.
     * + sCity: string.
     * + sAddress: string.
     * + bIsInvisible: bool.
     * + sEventDate: string.
     * + aCategory: array.
     * + sMapLocation: string.
     * + sLocation: string.
     * + iStartYear: int.
     * + iStartMonth: int.
     * + iStartDate: int.
     * + iStartHour: int.
     * + iStartMinute: int.
     * + iEndYear: int.
     * + iEndMonth: int.
     * + iEndDate: int.
     * + iEndHour: int.
     * + iEndMinute: int.
     * + bCanPostComment: int.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see event/view
     * 
     * @param array $aData
     * @return boolean|array
     */
    public function view($aData)
    {
        if (!isset($aData['iEventId']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Parameter is not valid! "
            );
        }
        
        if (Phpfox::isUser())
        {
            $this->database()->select('ei.invite_id, ei.rsvp_id, ')->leftJoin(Phpfox::getT('event_invite'), 'ei', 'ei.event_id = e.event_id AND ei.invited_user_id = ' . Phpfox::getUserId());
        }

        if (Phpfox::isModule('friend'))
        {
            $this->database()->select('f.friend_id AS is_friend, ')->leftJoin(Phpfox::getT('friend'), 'f', "f.user_id = e.user_id AND f.friend_user_id = " . Phpfox::getUserId());
        }
        else
        {
            $this->database()->select('0 as is_friend, ');
        }
        /**
         * @var array
         */
        $aEvent = $this->database()->select('e.*, c.name AS country_name, ' . (Phpfox::getParam('core.allow_html') ? 'et.description_parsed' : 'et.description') . ' AS description, ' . Phpfox::getUserField())
                ->from(Phpfox::getT('event'), 'e')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = e.user_id')
                ->join(Phpfox::getT('event_text'), 'et', 'et.event_id = e.event_id')
                ->leftJoin(Phpfox::getT('country'), 'c', 'c.country_iso = e.country_iso')
                ->where('e.event_id = ' . (int) $aData['iEventId'])
                ->execute('getRow');

        if (!isset($aEvent['event_id']))
        {
            return array(
                'error_code' => 1,
                'error_message' => Phpfox::getPhrase('event.the_event_you_are_looking_for_does_not_exist_or_has_been_removed')
            );
        }

		if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('event', $aEvent['event_id'], $aEvent['user_id'], $aEvent['privacy'], $aEvent['is_friend'], true))
		{
			return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'));
		}
		/**
         * @var bool
         */
        $bCanPostComment = true;
        if (isset($aEvent['privacy_comment']) && $aEvent['user_id'] != Phpfox::getUserId() && !Phpfox::getUserParam('privacy.can_comment_on_all_items'))
        {
            switch ($aEvent['privacy_comment']) {
                // Everyone is case 0. Skipped.
                // Friends only
                case 1:
                    if (!Phpfox::getService('friend')->isFriend(Phpfox::getUserId(), $aEvent['user_id']))
                    {
                        $bCanPostComment = false;
                    }
                    break;
                // Friend of friends
                case 2:
                    if (!Phpfox::getService('friend')->isFriendOfFriend($aEvent['user_id']))
                    {
                        $bCanPostComment = false;
                    }
                    break;
                // Only me
                case 3:
                    $bCanPostComment = false;
                    break;
            }
        }
        /**
         * @var array|bool
         */
        $aCallback = false;
        if ($aEvent['item_id'] && Phpfox::hasCallback($aEvent['module_id'], 'viewEvent'))
        {
            $aCallback = Phpfox::callback($aEvent['module_id'] . '.viewEvent', $aEvent['item_id']);

            if ($aEvent['module_id'] == 'pages' && !Phpfox::getService('pages')->hasPerm($aCallback['item_id'], 'event.view_browse_events'))
            {
                return array(
                    'error_code' => 1,
                    'error_message' => 'Unable to view this item due to privacy settings.'
                );
            }
        }

        if (Phpfox::getUserId())
        {
            /**
             * @var bool
             */
            $bIsBlocked = Phpfox::getService('user.block')->isBlocked($aEvent['user_id'], Phpfox::getUserId());
            if ($bIsBlocked)
            {
                $bCanPostComment = false;
            }
        }

        if (!isset($aEvent['event_id']))
        {
            return false;
        }

        if (!Phpfox::isUser())
        {
            $aEvent['invite_id'] = 0;
            $aEvent['rsvp_id'] = 0;
        }

        if ($aEvent['view_id'] == '1')
        {
            if ($aEvent['user_id'] == Phpfox::getUserId() || Phpfox::getUserParam('event.can_approve_events') || Phpfox::getUserParam('event.can_view_pirvate_events'))
            {
                
            }
            else
            {
                return false;
            }
        }

        $aEvent['event_date'] = Phpfox::getTime(Phpfox::getParam('event.event_basic_information_time'), $aEvent['start_time']);
        if ($aEvent['start_time'] < $aEvent['end_time'])
        {
            $aEvent['event_date'] .= ' - ';
            if (date('dmy', $aEvent['start_time']) === date('dmy', $aEvent['end_time']))
            {
                $aEvent['event_date'] .= Phpfox::getTime(Phpfox::getParam('event.event_basic_information_time_short'), $aEvent['end_time']);
            }
            else
            {
                $aEvent['event_date'] .= Phpfox::getTime(Phpfox::getParam('event.event_basic_information_time'), $aEvent['end_time']);
            }
        }

        if (isset($aEvent['gmap']) && !empty($aEvent['gmap']))
        {
            $aEvent['gmap'] = unserialize($aEvent['gmap']);
        }
        /**
         * @var array
         */
        $aCategories = $this->database()->select('pc.parent_id AS iParentId, pc.category_id AS iCategoryId, pc.name AS sName')
                ->from(Phpfox::getT('event_category_data'), 'pcd')
                ->join(Phpfox::getT('event_category'), 'pc', 'pc.category_id = pcd.category_id')
                ->where('pcd.event_id = ' . (int) $aEvent['event_id'])
                ->order('pc.parent_id ASC, pc.ordering ASC')
                ->execute('getRows');
        
        $aEvent['categories'] = $aCategories;

        if (!empty($aEvent['address']))
        {
            $aEvent['map_location'] = $aEvent['address'];
            if (!empty($aEvent['city']))
            {
                $aEvent['map_location'] .= ',' . $aEvent['city'];
            }
            if (!empty($aEvent['postal_code']))
            {
                $aEvent['map_location'] .= ',' . $aEvent['postal_code'];
            }
            if (!empty($aEvent['country_child_id']))
            {
                $aEvent['map_location'] .= ',' . Phpfox::getService('core.country')->getChild($aEvent['country_child_id']);
            }
            if (!empty($aEvent['country_iso']))
            {
                $aEvent['map_location'] .= ',' . Phpfox::getService('core.country')->getCountry($aEvent['country_iso']);
            }

            $aEvent['map_location'] = urlencode($aEvent['map_location']);
        }
        
        if (!($aEvent))
        {
            return array(
                'error_code' => 1,
                'error_message' => Phpfox::getPhrase('event.the_event_you_are_looking_for_does_not_exist_or_has_been_removed')
            );
        }
        
        if (is_file(Phpfox::getParam('event.dir_image') . sprintf($aEvent['image_path'], '_200')))
        {
            $sEventImageUrl = Phpfox::getParam('event.url_image') . sprintf($aEvent['image_path'], '_200');
        }
        else
        {
            $sEventImageUrl = Phpfox::getParam('core.path') . "theme/frontend/default/style/default/image/noimage/profile_50.png";
        }
        
        if (is_file(Phpfox::getParam('core.dir_pic') . 'user' . PHPFOX_DS . sprintf($aEvent['user_image'], MAX_SIZE_OF_USER_IMAGE)))
        {
            $sUserImageUrl = Phpfox::getParam('core.url_user') . sprintf($aEvent['user_image'], MAX_SIZE_OF_USER_IMAGE);
        }
        else
        {
            $sUserImageUrl = Phpfox::getParam('core.path') . "theme/frontend/default/style/default/image/noimage/profile_50.png";
        }
        
        return array(
            'iInviteId' => $aEvent['invite_id'],
            'iRsvpId' => $aEvent['rsvp_id'],
            'bIsFriend' => $aEvent['is_friend'],
            'iEventId' => $aEvent['event_id'],
            'iViewId' => $aEvent['view_id'],
            'iPrivacy' => $aEvent['privacy'],
            'iPrivacyComment' => $aEvent['privacy_comment'],
            'bIsFeatured' => $aEvent['is_featured'],
            'bIsSponsor' => $aEvent['is_sponsor'],
            'sEventImageUrl' => $sEventImageUrl,
            'sFullName' => $aEvent['full_name'],
            'iUserId' => $aEvent['user_id'],
            'sUserImageUrl' => $sUserImageUrl,
            'iStartTime' => $aEvent['start_time'],
            'sStartTime' => date('l, F j', $aEvent['start_time']),
            'sStartFullTime' => date('l, F j', $aEvent['start_time']) . ' at ' . date('g:i a', $aEvent['start_time']),
            'iEndTime' => $aEvent['end_time'],
            'sEndTime' => date('l, F j', $aEvent['end_time']),
            'sEndFullTime' => date('l, F j', $aEvent['end_time']) . ' at ' . date('g:i a', $aEvent['end_time']),
            'iTimeStamp' => $aEvent['time_stamp'],
            'iTotalComment' => $aEvent['total_comment'],
            'iTotalLike' => $aEvent['total_like'],
            'iTotalDislike' => $aEvent['total_dislike'],
            'sTitle' => $aEvent['title'],
            'sDescription' => $aEvent['description'],
            'sCountryISO' => $aEvent['country_iso'],
            'sCountryName' => $aEvent['country_name'],
            'sPostalCode' => $aEvent['postal_code'],
            'sCity' => $aEvent['city'],
            'sAddress' => $aEvent['address'],
            'bIsInvisible' => $aEvent['is_invisible'],
            'sEventDate' => $aEvent['event_date'],
            'aCategory' => $aEvent['categories'],
            'sMapLocation' => $aEvent['map_location'],
            'sLocation' => $aEvent['location'],
            'iStartYear' => date('Y', $aEvent['start_time']),
            'iStartMonth' => date('n', $aEvent['start_time']),
            'iStartDate' => date('j', $aEvent['start_time']),
            'iStartHour' => date('G', $aEvent['start_time']),
            'iStartMinute' => date('i', $aEvent['start_time']),
            'iEndYear' => date('Y', $aEvent['end_time']),
            'iEndMonth' => date('n', $aEvent['end_time']),
            'iEndDate' => date('j', $aEvent['end_time']),
            'iEndHour' => date('G', $aEvent['end_time']),
            'iEndMinute' => date('i', $aEvent['end_time']),
            'bCanPostComment' => $bCanPostComment
        );
    }

    /**
     * @see Event_Service_Process
     * 
     * Input data:
     * + iEventId: int, required.
     * + iRsvp: int, required.
     * 
     * Output data:
     * + error_code: int.
     * + error_message: string.
     * + result: int.
     * + message: string.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see event/addrsvp
     * 
     * @param array $aData
     * @return array
     */
    public function addrsvp($aData)
    {
        if (!isset($aData['iEventId']) || !isset($aData['iRsvp']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Parameter is not valid! "
            );
        }
        
        $bUseId = true;

        if (Phpfox::isUser())
        {
            $this->database()->select('ei.invite_id, ei.rsvp_id, ')->leftJoin(Phpfox::getT('event_invite'), 'ei', 'ei.event_id = e.event_id AND ei.invited_user_id = ' . Phpfox::getUserId());
        }

        if (Phpfox::isModule('friend'))
        {
            $this->database()->select('f.friend_id AS is_friend, ')->leftJoin(Phpfox::getT('friend'), 'f', "f.user_id = e.user_id AND f.friend_user_id = " . Phpfox::getUserId());
        }
        else
        {
            $this->database()->select('0 as is_friend, ');
        }
        /**
         * @var array
         */
        $aEvent = $this->database()->select('e.*, c.name AS country_name, ' . (Phpfox::getParam('core.allow_html') ? 'et.description_parsed' : 'et.description') . ' AS description, ' . Phpfox::getUserField())
                ->from(Phpfox::getT('event'), 'e')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = e.user_id')
                ->join(Phpfox::getT('event_text'), 'et', 'et.event_id = e.event_id')
                ->leftJoin(Phpfox::getT('country'), 'c', 'c.country_iso = e.country_iso')
                ->where('e.event_id = ' . (int) $aData['iEventId'])
                ->execute('getRow');

        if (!isset($aEvent['event_id']))
        {
            return array(
                'error_code' => 1,
                'error_message' => Phpfox::getPhrase('event.the_event_you_are_looking_for_does_not_exist_or_has_been_removed')
            );
        }

		if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('event', $aEvent['event_id'], $aEvent['user_id'], $aEvent['privacy'], $aEvent['is_friend'], true))
		{
			return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'));
		}
		/**
         * @var bool
         */
        $bResult = Phpfox::getService('event.process')->addRsvp($aData['iEventId'], $aData['iRsvp'], Phpfox::getUserId());

        return array('result' => $bResult);
    }
    
    /**
     * Input data:
     * + iEventId: int, required.
     * + sUserId: string, required. (string split by comma)
     * 
     * Output data:
     * + error_code: int.
     * + error_message: string.
     * + result: int.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see event/invite
     * 
     * @param array $aData
     * @return array
     */
    public function invite($aData)
    {
        if (!isset($aData['iEventId']) || !isset($aData['sUserId']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Parameter is not valid! "
            );
        }
        /**
         * @var object
         */
        $oParseInput = Phpfox::getLib('parse.input');
        /**
         * @var array
         */
        $aEvent = $this->database()->select('event_id, user_id, title, module_id')
                ->from(Phpfox::getT('event'))
                ->where('event_id = ' . (int) $aData['iEventId'])
                ->execute('getSlaveRow');

        if (!$aEvent)
        {
            return array(
                'error_code' => 1,
                'error_message' => " Event is not available! "
            );
        }
        
        /**
         * @var array
         */
        $aVals = array('invite' => explode(',', $aData['sUserId']));

        if (isset($aVals['invite']))
        {
            /**
             * @var array
             */
            $aInvites = $this->database()->select('invited_user_id, invited_email')
                    ->from(Phpfox::getT('event_invite'))
                    ->where('event_id = ' . (int) $aData['iEventId'])
                    ->execute('getRows');
            /**
             * @var array
             */
            $aInvited = array();
            foreach ($aInvites as $aInvite)
            {
                $aInvited[(empty($aInvite['invited_email']) ? 'user' : 'email')][(empty($aInvite['invited_email']) ? $aInvite['invited_user_id'] : $aInvite['invited_email'])] = true;
            }
        }

        if (isset($aVals['invite']) && is_array($aVals['invite']))
        {
            /**
             * @var string
             */
            $sUserIds = '';
            foreach ($aVals['invite'] as $iUserId)
            {
                if (!is_numeric($iUserId))
                {
                    continue;
                }
                $sUserIds .= $iUserId . ',';
            }
            $sUserIds = rtrim($sUserIds, ',');
            /**
             * @var array
             */
            $aUsers = $this->database()->select('user_id, email, language_id, full_name')
                    ->from(Phpfox::getT('user'))
                    ->where('user_id IN(' . $sUserIds . ')')
                    ->execute('getSlaveRows');

            foreach ($aUsers as $aUser)
            {
                if (isset($aInvited['user'][$aUser['user_id']]))
                {
                    continue;
                }
                /**
                 * @var string
                 */
                $sLink = Phpfox::getLib('url')->permalink('event', $aEvent['event_id'], $aEvent['title']);
                /**
                 * @var string
                 */
                $sMessage = Phpfox::getPhrase('event.full_name_invited_you_to_the_title', array(
                            'full_name' => Phpfox::getUserBy('full_name'),
                            'title' => $oParseInput->clean($aEvent['title'], 255),
                            'link' => $sLink
                                ), false, null, $aUser['language_id']);
                /**
                 * @var bool
                 */
                $bSent = Phpfox::getLib('mail')->to($aUser['user_id'])
                        ->subject(array('event.full_name_invited_you_to_the_event_title', array('full_name' => Phpfox::getUserBy('full_name'), 'title' => $oParseInput->clean($aEvent['title'], 255))))
                        ->message($sMessage)
                        ->notification('event.invite_to_event')
                        ->send();

                if ($bSent)
                {
                    /**
                     * @var int
                     */
                    $iInviteId = $this->database()->insert(Phpfox::getT('event_invite'), array(
                        'event_id' => $aData['iEventId'],
                        'user_id' => Phpfox::getUserId(),
                        'invited_user_id' => $aUser['user_id'],
                        'time_stamp' => PHPFOX_TIME
                            )
                    );

                    (Phpfox::isModule('request') ? Phpfox::getService('request.process')->add('event_invite', $aData['iEventId'], $aUser['user_id']) : null);
                }
            }
        }

        return array('result' => true);
    }
    /**
     * Input data:
     * + iEventId: int, required.
     * + sFeedback: string, optional.
     * + iReport: int, required.
     * - 1: Nudity or Pornography
     * - 2: Drug Use
     * - 3: Violence
     * - 4: Attacks Individual or Group
     * - 5: Copyright Infringement
     * 
     * Output data:
     * + error_code: int.
     * + error_message: string.
     * + result: int.
     * + message: string.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see event/report
     * 
     * @param array $aData
     * @return array
     */
    public function report($aData)
    {
        if (!isset($aData['iEventId']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Parameter is not valid! "
            );
        }
        /**
         * @var array
         */
        $aEvent = Phpfox::getService('event')->getEvent((int) $aData['iEventId']);

        if (!isset($aEvent['event_id']))
        {
            return array(
                'error_code' => 1,
                'error_message' => " Event is not valid or has been deleted! "
            );
        }

        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('event', $aEvent['event_id'], $aEvent['user_id'], $aEvent['privacy'], $aEvent['is_friend'], true))
		{
			return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'));
		}
        
        $oReport = Phpfox::getService('report');
        /**
         * @var array
         */
        $aVals = array(
            'type' => 'event',
            'id' => $aData['iEventId']
        );

        if (isset($aData['sFeedback']) && !Phpfox::getLib('parse.format')->isEmpty($aData['sFeedback']))
        {
            $aVals['feedback'] = $aData['sFeedback'];
        }
        else
        {
            $aVals['feedback'] = '';
            /**
             * @var array
             */
            $aReasons = $oReport->getOptions($aVals['type']);
            $aReasonId = array();
            foreach ($aReasons as $aReason)
            {
                $aReasonId[$aReason['report_id']] = $aReason['report_id'];
            }

            if (!isset($aData['iReport']) || !isset($aReasonId[$aData['iReport']]))
            {
                return array(
                    'error_code' => 1,
                    'error_message' => " Reason is not valid! "
                );
            }
        }

        $aVals['report'] = isset($aData['iReport']) ? $aData['iReport'] : '';
        /**
         * @var bool
         */
        $bCanReport = $oReport->canReport($aVals['type'], $aVals['id']);

        if ($bCanReport)
        {
            if ($bResult = Phpfox::getService('report.data.process')->add($aVals['report'], $aVals['type'], $aVals['id'], $aVals['feedback']))
            {
                return array(
                    'result' => $bResult,
                    'message' => "Report successfully!"
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
        else
        {
            return array(
                'error_code' => 1,
                'error_message' => Phpfox::getPhrase('report.you_have_already_reported_this_item')
            );
        }
    }
    /**
     * Using for notification.
     * @param array $aNotification
     * @return boolean|array
     */
    public function doEventGetCommentNotification($aNotification)
    {
        /**
         * @var array
         */
        $aRow = $this->database()->select('fc.feed_comment_id, u.user_id, u.gender, u.user_name, u.full_name, e.event_id, e.title')
                ->from(Phpfox::getT('event_feed_comment'), 'fc')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = fc.user_id')
                ->join(Phpfox::getT('event'), 'e', 'e.event_id = fc.parent_user_id')
                ->where('fc.feed_comment_id = ' . (int) $aNotification['item_id'])
                ->execute('getSlaveRow');

        if (!isset($aRow['feed_comment_id']))
        {
            return false;
        }
        /**
         * @var string
         */
        $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
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
                $sPhrase = Phpfox::getPhrase('event.users_commented_on_span_class_drop_data_user_row_full_name_s_span_comment_on_the_event_title', array('users' => Phpfox::getService('notification')->getUsers($aNotification, true), 'row_full_name' => $aRow['full_name'], 'title' => $sTitle));
            }
            else
            {
                $sPhrase = Phpfox::getPhrase('event.users_commented_on_gender_own_comment_on_the_event_title', array('users' => $sUsers, 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'title' => $sTitle));
            }
        }
        elseif ($aRow['user_id'] == Phpfox::getUserId())
        {
            $sPhrase = Phpfox::getPhrase('event.users_commented_on_one_of_your_comments_on_the_event_title', array('users' => $sUsers, 'title' => $sTitle));
        }
        else
        {
            $sPhrase = Phpfox::getPhrase('event.users_commented_on_one_of_span_class_drop_data_user_row_full_name_s_span_comments_on_the_event_title', array('users' => $sUsers, 'row_full_name' => $aRow['full_name'], 'title' => $sTitle));
        }

        return array(
            'link' => array(
                'comment-id' => $aRow['feed_comment_id'],
                'iEventId' => $aRow['event_id'],
                'sTitle' => $aRow['title']
            ),
            'message' => strip_tags($sPhrase),
            'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
        );
    }
    /**
     * Using for notification.
     * @param array $aNotification
     * @return array
     */
    public function doEventGetNotificationComment($aNotification)
    {
        /**
         * @var array
         */
        $aRow = $this->database()->select('fc.feed_comment_id, u.user_id, u.gender, u.user_name, u.full_name, e.event_id, e.title')
                ->from(Phpfox::getT('event_feed_comment'), 'fc')
                ->join(Phpfox::getT('event'), 'e', 'e.event_id = fc.parent_user_id')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = e.user_id')
                ->where('fc.feed_comment_id = ' . (int) $aNotification['item_id'])
                ->execute('getSlaveRow');
        /**
         * @var string
         */
        $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
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
                $sPhrase = Phpfox::getPhrase('event.users_commented_on_span_class_drop_data_user_row_full_name_s_span_event_title', array('users' => Phpfox::getService('notification')->getUsers($aNotification, true), 'row_full_name' => $aRow['full_name'], 'title' => $sTitle));
            }
            else
            {
                $sPhrase = Phpfox::getPhrase('event.users_commented_on_gender_own_event_title', array('users' => $sUsers, 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'title' => $sTitle));
            }
        }
        elseif ($aRow['user_id'] == Phpfox::getUserId())
        {
            $sPhrase = Phpfox::getPhrase('event.users_commented_on_your_event_title', array('users' => $sUsers, 'title' => $sTitle));
        }
        else
        {
            $sPhrase = Phpfox::getPhrase('event.users_commented_on_span_class_drop_data_user_row_full_name_s_span_event_title', array('users' => $sUsers, 'row_full_name' => $aRow['full_name'], 'title' => $sTitle));
        }

        return array(
            'link' => array('comment-id' => $aRow['feed_comment_id'],
                'iEventId' => $aRow['event_id'],
                'sTitle' => $aRow['title']),
            'message' => strip_tags($sPhrase),
            'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
        );
    }
    /**
     * Using for notification.
     * @param array $aNotification
     * @return array
     */
    public function doEventGetNotificationComment_Feed($aNotification)
	{
		return $this->doEventGetCommentNotification($aNotification);	
	}
    /**
     * Using for notification.
     * @param array $aNotification
     * @return array
     */
    public function doEventGetNotificationComment_Like($aNotification)
	{
        /**
         * @var array
         */
		$aRow = $this->database()->select('fc.feed_comment_id, u.user_id, u.gender, u.user_name, u.full_name, e.event_id, e.title')
			->from(Phpfox::getT('event_feed_comment'), 'fc')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = fc.user_id')
			->join(Phpfox::getT('event'), 'e', 'e.event_id = fc.parent_user_id')
			->where('fc.feed_comment_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
        /**
         * @var string
         */
		$sUsers = Phpfox::getService('notification')->getUsers($aNotification);
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
				$sPhrase = Phpfox::getPhrase('event.users_liked_span_class_drop_data_user_row_full_name_s_span_comment_on_the_event_title', array('users' => Phpfox::getService('notification')->getUsers($aNotification, true), 'row_full_name' => $aRow['full_name'], 'title' => $sTitle));
			}
			else 
			{
				$sPhrase = Phpfox::getPhrase('event.users_liked_gender_own_comment_on_the_event_title', array('users' => $sUsers, 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'title' => $sTitle));
			}
		}
		elseif ($aRow['user_id'] == Phpfox::getUserId())		
		{
			$sPhrase = Phpfox::getPhrase('event.users_liked_one_of_your_comments_on_the_event_title', array('users' => $sUsers, 'title' => $sTitle));
		}
		else 
		{
			$sPhrase = Phpfox::getPhrase('event.users_liked_one_on_span_class_drop_data_user_row_full_name_s_span_comments_on_the_event_title', array('users' => $sUsers, 'row_full_name' => $aRow['full_name'], 'title' => $sTitle));
		}
			
        return array(
            'link' => array(
                'iCommentId' => $aRow['feed_comment_id'],
                'iEventId' => $aRow['event_id'],
                'sTitle' => $aRow['title']
            ),
            'message' => strip_tags($sPhrase),
            'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
        );
	}	
    /**
     * Using for notification.
     * @param array $aNotification
     * @return boolean|array
     */
    public function doEventGetNotificationLike($aNotification)
    {
        /**
         * @var array
         */
        $aRow = $this->database()->select('e.event_id, e.title, e.user_id, u.gender, u.full_name')
                ->from(Phpfox::getT('event'), 'e')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = e.user_id')
                ->where('e.event_id = ' . (int) $aNotification['item_id'])
                ->execute('getSlaveRow');

        if (!isset($aRow['event_id']))
        {
            return false;
        }
        /**
         * @var string
         */
        $sUsers = Phpfox::getService('notification')->getUsers($aNotification);
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
            $sPhrase = Phpfox::getPhrase('event.users_liked_gender_own_event_title', array('users' => $sUsers, 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'title' => $sTitle));
        }
        elseif ($aRow['user_id'] == Phpfox::getUserId())
        {
            $sPhrase = Phpfox::getPhrase('event.users_liked_your_event_title', array('users' => $sUsers, 'title' => $sTitle));
        }
        else
        {
            $sPhrase = Phpfox::getPhrase('event.users_liked_span_class_drop_data_user_row_full_name_s_span_event_title', array('users' => $sUsers, 'row_full_name' => $aRow['full_name'], 'title' => $sTitle));
        }

        return array(
            'link' => array('iEventId' => $aRow['event_id'], 'sTitle' => $aRow['title']),
            'message' => strip_tags($sPhrase),
            'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
        );
    }
}
