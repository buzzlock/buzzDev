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
class Mfox_Service_Comment extends Phpfox_Service {

    /**
     * Not for feed.
     * @param array $aData
     * @return boolean
     */
    public function checkCanPostCommentOnItem($aItem)
    {
        /**
         * @var bool
         */
        $bCanPostComment = true;
        if (isset($aItem['privacy_comment']) && $aItem['user_id'] != Phpfox::getUserId() && !Phpfox::getUserParam('privacy.can_comment_on_all_items'))
        {
            switch ($aItem['privacy_comment']) {
                // Everyone is case 0. Skipped.
                // Friends only
                case 1:
                    if (!Phpfox::getService('friend')->isFriend(Phpfox::getUserId(), $aItem['user_id']))
                    {
                        $bCanPostComment = false;
                    }
                    break;
                // Friend of friends
                case 2:
                    if (!Phpfox::getService('friend')->isFriendOfFriend($aItem['user_id']))
                    {
                        $bCanPostComment = false;

                        if (Phpfox::getService('friend')->isFriend(Phpfox::getUserId(), $aItem['user_id']))
                        {
                            $bCanPostComment = true;
                        }
                    }
                    break;
                // Only me
                case 3:
                    $bCanPostComment = false;
                    break;
            }
        }

        if (Phpfox::getUserId())
        {
            $bIsBlocked = Phpfox::getService('user.block')->isBlocked($aItem['user_id'], Phpfox::getUserId());
            if ($bIsBlocked)
            {
                $bCanPostComment = false;
            }
        }

        return $bCanPostComment;
    }

    /**
     * Check can post comment or not.
     * @param array $aData
     * @return boolean
     */
    public function checkCanPostComment($aData)
    {
        /**
         * @var bool
         */
        $bCanPostComment = true;
        if (isset($aData['comment_privacy']) && $aData['user_id'] != Phpfox::getUserId() && !Phpfox::getUserParam('privacy.can_comment_on_all_items'))
        {
            switch ($aData['comment_privacy']) {
                case 1:
                    if ((int) $aData['feed_is_friend'] <= 0)
                    {
                        $bCanPostComment = false;
                    }
                    break;
                case 2:
                    if ((int) $aData['feed_is_friend'] > 0)
                    {
                        $bCanPostComment = true;
                    }
                    else
                    {
                        if (!Phpfox::getService('friend')->isFriendOfFriend($aData['user_id']))
                        {
                            $bCanPostComment = false;
                        }
                    }
                    break;
                case 3:
                    $bCanPostComment = false;
                    break;
            }
        }
        $aData['can_post_comment'] = $bCanPostComment;

        if (Phpfox::isModule('comment')
                && isset($aData['comment_type_id'])
                && Phpfox::getParam('feed.allow_comments_on_feeds')
                && Phpfox::isUser()
                && $aData['can_post_comment']
                && Phpfox::getUserParam('feed.can_post_comment_on_feed'))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Change the type when add a new comment.
     * @param string $sType
     * @return string
     */
    public function changeType($sType)
    {
        switch ($sType) {
            case 'user_status':
                break;

            case 'photo':
                break;

            case 'feed_comment':
                return 'feed';
                break;

            default:
                break;
        }

        return $sType;
    }

    /**
     * Input data:
     * + sType: string, required.
     * + iItemId: int, required.
     * + sText: string, required.
     * 
     * Output data:
     * + error_message: string.
     * + error_code: int.
     * + result: int.
     * + lastid: int.
     * 
     * @see Mobile - API phpFox/Api V2.0 - Restful. Method Post.
     * @see comment
     * 
     * @param array $aData
     * @return array
     */
    public function postAction($aData)
    {
        return $this->add($aData);
    }

    /**
     * Input data:
     * + sType: string, required.
     * + iItemId: int, required.
     * + sText: string, required.
     * + iItem: int, required.
     * + iParentId: int, required.
     * 
     * Output data:
     * + error_message: string.
     * + error_code: int.
     * + result: int.
     * + lastid: int.
     * 
     * @see Mobile - API phpFox/Api V2.0.
     * @see comment/add
     * 
     * @param array $aData
     * @return array
     */
    public function add($aData)
    {
        extract($aData, EXTR_SKIP);

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
         * @var int
         */
        $iParentId = isset($aData['iParentId']) ? (int) $aData['iParentId'] : 0;
        
        /**
         * @var string
         */
        $sText = isset($aData['sText']) ? $aData['sText'] : '';

        if (empty($sType) || $iItemId < 1 || empty($sText))
        {
            return array(
                'error_message' => ' Parameter is not valid! ',
                'error_code' => 1,
                'result' => 0
            );
        }
        /**
         * @var string
         */
        $sType = $this->changeType($sType);

        if (Phpfox::hasCallback($sType, 'getAjaxCommentVar'))
        {
            $sVar = Phpfox::callback($sType . '.getAjaxCommentVar');

            if ($sVar !== null)
            {
                Phpfox::getUserParam($sVar, true);
            }
        }

        if ($sType == 'profile' && !Phpfox::getService('user.privacy')->hasAccess($iItemId, 'comment.add_comment'))
        {
            return array(
                'error_message' => Phpfox::getPhrase('bulletin.you_do_not_have_permission_to_add_a_comment_on_this_persons_profile'),
                'error_code' => 1,
                'result' => 0
            );
        }

        if ($sType == 'group' && (!Phpfox::getService('group')->hasAccess($iItemId, 'can_use_comments', true)))
        {
            return array(
                'error_message' => Phpfox::getPhrase('bulletin.only_members_of_this_group_can_leave_a_comment'),
                'error_code' => 1,
                'result' => 0
            );
        }

        if (!Phpfox::getUserParam('comment.can_comment_on_own_profile') && $sType == 'profile' && $iItemId == Phpfox::getUserId() && !isset($iParentId))
        {
            return array(
                'error_message' => Phpfox::getPhrase('comment.you_cannot_write_a_comment_on_your_own_profile'),
                'error_code' => 1,
                'result' => 0
            );
        }

        if (($iFlood = Phpfox::getUserParam('comment.comment_post_flood_control')) !== 0)
        {
            $aFlood = array(
                'action' => 'last_post', // The SPAM action
                'params' => array(
                    'field' => 'time_stamp', // The time stamp field
                    'table' => Phpfox::getT('comment'), // Database table we plan to check
                    'condition' => 'type_id = \'' . Phpfox::getLib('database')->escape($sType) . '\' AND user_id = ' . Phpfox::getUserId(), // Database WHERE query
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

        if (Phpfox::getLib('parse.format')->isEmpty($sText))
        {
            return array(
                'error_message' => Phpfox::getPhrase('comment.add_some_text_to_your_comment'),
                'error_code' => 1,
                'result' => 0
            );
        }

        // Check privacy comment.
        $aError = null;

        switch ($sType) {
            case 'photo':
                $aError = Phpfox::getService('mfox.photo')->checkPrivacyCommentOnPhoto($iItemId);
                break;

            case 'photo_album':
                $aError = Phpfox::getService('mfox.photo')->checkPrivacyCommentOnAlbum($iItemId);
                break;

            case 'music_song':
                $aError = Phpfox::getService('mfox.song')->checkPrivacyCommentOnSong($iItemId);
                break;

            case 'music_album':
                $aError = Phpfox::getService('mfox.album')->checkPrivacyCommentOnMusicAlbum($iItemId);
                break;

            case 'video':
                $aError = Phpfox::getService('mfox.video')->checkPrivacyCommentOnVideo($iItemId, $sModule, $iItem);
                break;
            
            case 'user_status':
                $aError = Phpfox::getService('mfox.feed')->checkPrivacyOnUserStatusFeed($iItemId, $sType, $sModule, $iItem);
                break;
            
            default:

                break;
        }

        if ($aError)
        {
            return $aError;
        }

        $aVals = array(
            'parent_id' => $iParentId,
            'text' => $sText,
            'type' => $sType,
            'item_id' => $iItemId
        );

        if (($mId = $this->addComment($aVals)) === false)
        {
            return false;
        }
        else
        {
            return array('lastid' => $mId);
        }
    }

    /**
     * Add comment.
     * @param array $aVals
     * @param int $iUserId
     * @param string $sUserName
     * @return boolean|string
     */
    public function addComment($aVals, $iUserId = null, $sUserName = null)
    {
        /**
         * @var int
         */
        $iUserId = ($iUserId === null ? Phpfox::getUserId() : (int) $iUserId);
        /**
         * @var string
         */
        $sUserName = ($sUserName === null ? Phpfox::getUserBy('full_name') : $sUserName);

        if (isset($aVals['parent_group_id']) && isset($aVals['group_view_id']) && $aVals['group_view_id'] > 0)
        {
            define('PHPFOX_SKIP_FEED', true);
        }

        if (Phpfox::getParam('comment.comment_hash_check'))
        {
            if (Phpfox::getLib('spam.hash', array(
                        'table' => 'comment_hash',
                        'total' => Phpfox::getParam('comment.comments_to_check'),
                        'time' => Phpfox::getParam('comment.total_minutes_to_wait_for_comments'),
                        'content' => $aVals['text']
                            )
                    )->isSpam())
            {
                return false;
            }
        }
        /**
         * @var array
         */
        $aItem = Phpfox::callback($aVals['type'] . '.getCommentItem', $aVals['item_id']);

        if (!isset($aItem['comment_item_id']))
        {
            return false;
        }
        /**
         * @var bool
         */
        $bIsBlocked = Phpfox::getService('user.block')->isBlocked($aItem['comment_user_id'], Phpfox::getUserId());
        if ($bIsBlocked)
        {
            Phpfox_Error::set('Unable to leave a comment at this time.');
            return false;
        }
        /**
         * @var array
         */
        $aVals = array_merge($aItem, $aVals);
        /**
         * @var bool
         */
        $bCheck = Phpfox::getService('mfox.ban')->checkAutomaticBan($aVals['text']);
        if ($bCheck == false)
        {
            return false;
        }
        /**
         * @var array
         */
        $aInsert = array(
            'parent_id' => $aVals['parent_id'],
            'type_id' => $aVals['type'],
            'item_id' => $aVals['item_id'],
            'user_id' => $iUserId,
            'owner_user_id' => $aItem['comment_user_id'],
            'time_stamp' => PHPFOX_TIME,
            'ip_address' => Phpfox::getLib('request')->getServer('REMOTE_ADDR'),
            'view_id' => (($aItem['comment_view_id'] == 2 && $aItem['comment_user_id'] != $iUserId) ? '1' : '0'),
            'author' => (!empty($aVals['is_via_feed']) ? (int) $aVals['is_via_feed'] : '')
        );

        if (!$iUserId)
        {
            $aInsert['author'] = substr($aVals['author'], 0, 255);
            $aInsert['author_email'] = $aVals['author_email'];
            if (!empty($aVals['author_url']) && Phpfox::getLib('validator')->verify('url', $aVals['author_url']))
            {
                $aInsert['author_url'] = $aVals['author_url'];
            }
        }
        /**
         * @var bool
         */
        $bIsSpam = false;
        if (Phpfox::getParam('comment.spam_check_comments'))
        {
            if (Phpfox::getLib('spam')->check(array(
                        'action' => 'isSpam',
                        'params' => array(
                            'module' => 'comment',
                            'content' => Phpfox::getLib('parse.input')->prepare($aVals['text'])
                        )
                            )
                    )
            )
            {
                $aInsert['view_id'] = '9';
                $bIsSpam = true;
                Phpfox_Error::set(Phpfox::getPhrase('comment.your_comment_has_been_marked_as_spam_it_will_have_to_be_approved_by_an_admin'));
            }
        }

        if (Phpfox::getUserParam('comment.approve_all_comments'))
        {
            $aInsert['view_id'] = '1';
            $bIsSpam = true;
            Phpfox_Error::set(Phpfox::getPhrase('comment.your_comment_has_successfully_been_added_however_it_is_pending_an_admins_approval'));
        }
        /**
         * @var int
         */
        $iId = $this->database()->insert(Phpfox::getT('comment'), $aInsert);

        Phpfox::getLib('parse.bbcode')->useVideoImage(($aVals['type'] == 'feed' ? true : false));

        $aVals['text_parsed'] = Phpfox::getLib('parse.input')->prepare($aVals['text']);

        $this->database()->insert(Phpfox::getT('comment_text'), array(
            'comment_id' => $iId,
            'text' => Phpfox::getLib('parse.input')->clean($aVals['text']),
            'text_parsed' => $aVals['text_parsed']
                )
        );
        $aVals['comment_id'] = $iId;

        if (!empty($aVals['parent_id']))
        {
            $this->database()->updateCounter('comment', 'child_total', 'comment_id', (int) $aVals['parent_id']);
        }

        if ($bIsSpam === true)
        {
            return false;
        }

        Phpfox::getService('user.process')->notifyTagged($aVals['text'], $iId, $aVals['type']);

        // Callback this action to other modules
        Phpfox::callback($aVals['type'] . '.addComment', $aVals, $iUserId, $sUserName);

        if (($aItem['comment_view_id'] == 2 && $aItem['comment_user_id'] != $iUserId))
        {
            (Phpfox::isModule('request') ? Phpfox::getService('request.process')->add('comment_pending', $iId, $aItem['comment_user_id']) : false);

            return 'pending_moderation';
        }

        // Update user activity
        Phpfox::getService('user.activity')->update(Phpfox::getUserId(), 'comment');
        /**
         * @var string
         */
        $sFeedPrefix = '';
        $sNewTypeId = $aVals['type'];
        if (!empty($aItem['parent_module_id']) && ($aItem['parent_module_id'] == 'pages' || $aItem['parent_module_id'] == 'event'))
        {
            $sFeedPrefix = $aItem['parent_module_id'] . '_';
            if ($sNewTypeId == 'pages')
            {
                $sNewTypeId = 'pages_comment';
            }

            if ($sNewTypeId == 'event')
            {
                $sNewTypeId = 'event_comment';
            }
        }

        $this->database()->update(Phpfox::getT($sFeedPrefix . 'feed'), array('time_update' => PHPFOX_TIME), 'type_id = \'' . $this->database()->escape($sNewTypeId) . '\' AND item_id = ' . (int) $aVals['item_id']);

        return $iId;
    }

    /**
     * Input data:
     * + iItemId: int, required.
     * + sText: string, required.
     * 
     * Output data:
     * + error_message: string.
     * + error_code: int.
     * + result: int.
     * 
     * @see Mobile - API phpFox/Api V2.0 - Restful. Method Put.
     * @see comment
     * 
     * @param array $aData
     * @return array
     */
    public function putAction($aData)
    {
        return $this->edit($aData);
    }

    /**
     * Input data:
     * + iItemId: int, required.
     * + sText: string, required.
     * 
     * Output data:
     * + error_message: string.
     * + error_code: int.
     * + result: int.
     * 
     * @see Mobile - API phpFox/Api V2.0.
     * @see comment/edit
     * 
     * @param array $aData
     * @return array
     */
    public function edit($aData)
    {
        if (!Phpfox::isAdmin())
        {
            return array(
                'error_message' => ' Only admin can edit comment! ',
                'error_code' => 1,
                'result' => 0
            );
        }

        $iItemId = isset($aData['iItemId']) ? (int) $aData['iItemId'] : 0;
        $sText = isset($aData['sText']) ? $aData['sText'] : '';

        if ($iItemId < 1 || empty($sText))
        {
            return array(
                'error_message' => ' Parameter is not valid! ',
                'error_code' => 1,
                'result' => 0
            );
        }

        if (Phpfox::getLib('parse.format')->isEmpty($sText))
        {
            return array(
                'error_message' => Phpfox::getPhrase('comment.add_some_text_to_your_comment'),
                'error_code' => 1,
                'result' => 0
            );
        }

        if ($this->updateText($iItemId, $sText))
        {
            return array('result' => 1, 'error_code' => 0, 'error_message' => Phpfox_Error::get());
        }
        else
        {
            return array('result' => 0, 'error_code' => 1, 'error_message' => Phpfox_Error::get());
        }
    }

    /**
     * @param int $iId
     * @param string $sText
     * @return array|bool
     */
    private function updateText($iId, $sText)
    {
        if (Phpfox::getService('comment')->hasAccess($iId, 'edit_own_comment', 'edit_user_comment'))
        {
            $oFilter = Phpfox::getLib('parse.input');

            if (!Phpfox::getService('ban')->checkAutomaticBan($sText))
            {
                return array('result' => 0, 'error_code' => 1, 'error_message' => Phpfox_Error::get());
            }

            if (Phpfox::getParam('comment.spam_check_comments'))
            {
                if (Phpfox::getLib('spam')->check(array(
                            'action' => 'isSpam',
                            'params' => array(
                                'module' => 'comment',
                                'content' => Phpfox::getLib('parse.input')->prepare($sText)
                            )
                                )
                        )
                )
                {
                    $this->database()->update(Phpfox::getT('comment'), array('view_id' => '9'), "comment_id = " . (int) $iId);

                    Phpfox_Error::set(Phpfox::getPhrase('comment.your_comment_has_been_marked_as_spam_it_will_have_to_be_approved_by_an_admin'));
                }
            }
            /**
             * @var array
             */
            $aVals = $this->database()->select('cmt.*')
                    ->from(Phpfox::getT('comment'), 'cmt')
                    ->where('cmt.comment_id = ' . (int) $iId)
                    ->execute('getSlaveRow');

            Phpfox::getLib('parse.bbcode')->useVideoImage(($aVals['type_id'] == 'feed' ? true : false));

            $this->database()->update(Phpfox::getT('comment'), array('update_time' => PHPFOX_TIME, "update_user" => Phpfox::getUserBy("full_name")), "comment_id = " . (int) $iId);
            $this->database()->update(Phpfox::getT('comment_text'), array('text' => $oFilter->clean($sText), "text_parsed" => $oFilter->prepare($sText)), "comment_id = " . (int) $iId);

            if (Phpfox::hasCallback($aVals['type_id'], 'updateCommentText'))
            {
                Phpfox::callback($aVals['type_id'] . '.updateCommentText', $aVals, $oFilter->prepare($sText));
            }

            return true;
        }

        Phpfox_Error::set('You don\'t have permission to edit this comment!');

        return false;
    }

    /**
     * Input data:
     * + iItemId: int, required.
     * 
     * Output data:
     * + error_message: string.
     * + error_code: int.
     * + result: int.
     * 
     * @see Mobile - API phpFox/Api V2.0 - Restful. Method Delete.
     * @see comment
     * 
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
     * 
     * Output data:
     * + error_message: string.
     * + error_code: int.
     * + result: int.
     * 
     * @see Mobile - API phpFox/Api V2.0.
     * @see comment/delete
     * 
     * @param array $aData
     * @return array
     */
    public function delete($aData)
    {
        /**
         * @var int
         */
        $iItemId = isset($aData['iItemId']) ? (int) $aData['iItemId'] : 0;

        if ($iItemId < 1)
        {
            return array(
                'error_message' => ' Parameter is not valid! ',
                'error_code' => 1,
                'result' => 0
            );
        }
        /**
         * @var array
         */
        $aComment = $this->database()
                ->select('c.comment_id, c.type_id')
                ->from(Phpfox::getT('comment'), 'c')
                ->where('c.comment_id = ' . (int) $iItemId)
                ->execute('getRow');

        if (!$aComment)
        {
            return array(
                'error_message' => ' Comment does not exist or has been deleted! ',
                'error_code' => 1,
                'result' => 0
            );
        }

        return array('result' => Phpfox::getService('comment.process')->deleteInline($iItemId, $aComment['type_id']));
    }

    /**
     * Input data:
     * + sType: string, required.
     * + iItemId: int, required.
     * + lastCommentIdViewed: int, optional.
     * + amountOfComment: int, optional.
     * 
     * Output data:
     * + sImage: string.
     * + iTimestamp: int.
     * + sTimeConverted: string.
     * + iCommentId: int.
     * + iUserId: int.
     * + sFullName: string.
     * + sContent: string.
     * + iTotalLike: int.
     * + bIsLiked: bool.
     * 
     * @see Mobile - API phpFox/Api V1.0 - Restful. Method Get.
     * @see comment
     * 
     * @param array $aData
     * @return array
     */
    public function getAction($aData)
    {
        return $this->listallcomments($aData);
    }

    /**
     * Only get more comments.
     * 
     * Input data:
     * + sType: string, required.
     * + iItemId: int, required.
     * + iLastTime: int, optional.
     * + iAmountOfComment: int, optional.
     * 
     * Output data:
     * + sImage: string.
     * + iTimestamp: int.
     * + sTimeConverted: string.
     * + iCommentId: int.
     * + iUserId: int.
     * + sFullName: string.
     * + sContent: string.
     * + iTotalLike: int.
     * + bIsLiked: bool.
     * 
     * @see Mobile - API phpFox/Api V1.0
     * @see comment/listallcomments
     * 
     * @param array $aData
     * @return array
     */
    public function listallcomments($aData)
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
         * @var int
         */
        $iLastTime = isset($aData['iLastTime']) ? (int) $aData['iLastTime'] : 0;

        /**
         * @var int
         */
        $iAmountOfComment = isset($aData['iAmountOfComment']) ? (int) $aData['iAmountOfComment'] : 20;

        if (empty($sType) || $iItemId < 1)
        {
            return array(
                'error_message' => ' Parameter(s) is not valid! ',
                'error_code' => 1,
                'result' => 0
            );
        }

        $sType = $this->changeType($sType);
        $aError = null;

        switch ($sType) {
            case 'photo':
                $aError = Phpfox::getService('mfox.photo')->checkPrivacyOnPhoto($iItemId);
                break;

            case 'photo_album':
                $aError = Phpfox::getService('mfox.photo')->checkPrivacyOnAlbum($iItemId);
                break;

            case 'music_song':
                $aError = Phpfox::getService('mfox.song')->checkPrivacyOnSong($iItemId);
                break;

            case 'music_album':
                $aError = Phpfox::getService('mfox.album')->checkPrivacyOnMusicAlbum($iItemId);
                break;

            case 'video':
                $aError = Phpfox::getService('mfox.video')->checkPrivacyOnVideo($iItemId, $sModule, $iItem);
                break;
            
            case 'user_status':
                $aError = Phpfox::getService('mfox.feed')->checkPrivacyOnUserStatusFeed($iItemId, $sType, $sModule, $iItem);
                break;
            
            default:

                break;
        }

        if (isset($aError))
        {
            return $aError;
        }

        /**
         * @var array
         */
        $aComments = $this->database()
                ->select('c.comment_id AS iCommentId, c.user_id AS iUserId, u.full_name AS sFullName, u.user_image, u.server_id AS user_server_id, ct.text_parsed AS sContent, c.time_stamp AS time, c.total_like AS iTotalLike, l.like_id AS bIsLiked')
                ->from(Phpfox::getT('comment'), 'c')
                ->leftJoin(Phpfox::getT('user'), 'u', 'u.user_id = c.user_id')
                ->leftJoin(Phpfox::getT('comment_text'), 'ct', 'ct.comment_id = c.comment_id')
                ->leftJoin(Phpfox::getT('like'), 'l', 'l.type_id = \'feed_mini\' AND l.item_id = c.comment_id AND l.user_id = ' . Phpfox::getUserId())
                ->where('c.type_id = \'' . $this->database()->escape($sType) . '\' AND c.item_id = ' . (int) $iItemId . ($iLastTime > 0 ? ' AND c.time_stamp > ' . $iLastTime : ''))
                ->order('c.time_stamp ASC')
                ->limit($iAmountOfComment)
                ->execute('getRows');

        /**
         * @var array
         */
        $aResult = array();

        foreach ($aComments as $aComment)
        {
            $aResult[] = array(
                'iCommentId' => $aComment['iCommentId'],
                'sImage' => Phpfox::getLib('image.helper')->display(array(
                    'server_id' => $aComment['user_server_id'],
                    'path' => 'core.url_user',
                    'file' => $aComment['user_image'],
                    'suffix' => '_50_square',
                    'return_url' => true
                        )
                ),
                'iTimestamp' => $aComment['time'],
                'sTime' => date('l, F j, o', (int) $aComment['time']) . ' at ' . date('h:i a', (int) $aComment['time']),
                'sTimeConverted' => Phpfox::getLib('date')->convertTime($aComment['time'], 'comment.comment_time_stamp'),
                'iUserId' => $aComment['iUserId'],
                'sFullName' => $aComment['sFullName'],
                'sContent' => $aComment['sContent'],
                'iTotalLike' => $aComment['iTotalLike'],
                'bIsLiked' => $aComment['bIsLiked']
            );
        }

        return $aResult;
    }

    /**
     * Get redirect request.
     * @param array $aComment
     * @return boolean|string
     */
    public function doCommentGetRedirectRequest($aComment)
    {
        if (!isset($aComment['item_id']))
        {
            return false;
        }

        $aResult = array();

        switch ($aComment['type_id']) {
            case 'music_song':
                $aResult = array(
                    'iSongId' => $aComment['item_id'],
                    'sView' => 'music',
                    'sCommentType' => 'music_song'
                );
                break;

            case 'user_status':
                $aFeeds = Phpfox::getService('mfox.feed')->getfeed(array('status-id' => $aComment['item_id']), $aComment['user_id']);
                if (isset($aFeeds[0]['feed_id']))
                {
                    $aResult = array(
                        'iFeedId' => $aFeeds[0]['feed_id'],
                        'sView' => 'feed',
                        'sCommentType' => 'user_status'
                    );
                }
                else
                {
                    $aResult = array(
                        'iFeedId' => 0,
                        'sView' => 'feed',
                        'sCommentType' => 'user_status'
                    );
                }
                break;

            case 'photo':
                $aResult = array(
                    'iPhotoId' => $aComment['item_id'],
                    'sView' => 'photo',
                    'sCommentType' => 'photo'
                );
                break;

            case 'photo_album':
                $aResult = array(
                    'iPhotoAlbumId' => $aComment['item_id'],
                    'sView' => 'photo.album',
                    'sCommentType' => 'photo_album'
                );
                break;

            case 'event':
                /**
                 * @var array
                 */
                $aTemp = Phpfox::getService('mfox.event')->doEventGetNotificationComment($aComment);
                $iEventId = 0;
                if (isset($aTemp['link']['iEventId']))
                {
                    $iEventId = $aTemp['link']['iEventId'];
                }
                $aResult = array(
                    'iEventId' => $iEventId,
                    'sView' => 'event',
                    'sCommentType' => 'event'
                );
                break;

            case 'video':
                $aResult = Phpfox::getService('mfox.video')->doVideoGetRedirectComment($aComment['item_id']);
                break;

            case 'music_album':
                $aResult = Phpfox::getService('mfox.album')->doMusicAlbumGetRedirectCommentAlbum($aComment['item_id']);
                break;

            default:
                $aResult = array('result' => 0, 'error_code' => 1, 'message' => 'Show unknown module : ' . $aComment['type_id']);
                break;
        }

        return $aResult;
    }

}
