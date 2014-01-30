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
 * @link Mfox Api v3.0
 */
class Mfox_Service_Video extends Phpfox_Service {
    
    /**
     * <code>
     * Phpfox::getService('mfox.video')->create(array('sModule'=>'user', 'iItemId'=>12));
     * </code>
     * 
     * input data:
     * + sModule: string, required, In page.
     * + iItem: int, required. In page.
     * + iCategoryId: int, optional.
     * + sUrl: string, required.
	 * + iPrivacy: int, optional.
	 * + iPrivacyComment: int, optional.
     * 
     * output data:
     * + result: 1 if success and 0 otherwise.
     * + error_code: 1 if error, and 0 otherwise.
     * + message: Message to show the bug.
     * + iVideoId: Video id.
     * + sTitle: Title of video.
     * + aCallback: The callback info.
     * 
     * @see Mobile - API phpFox/Api V3.0
     * @see video/create
     * 
     * @param array $aData
     * @return array
     * 
     */
    public function create($aData)
    {
        /**
         * check permission
         */
        if (!Phpfox::getUserParam('video.can_upload_videos'))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => 'You don\'t have permission to access video module!');
        }
        /**
         * @var string
         */
        $sModule = isset($aData['sModule']) ? $aData['sModule'] : false;
        /**
         * @var int
         */
        $iItem = isset($aData['iItem']) ? (int) $aData['iItem'] : false;
        /**
         * @var int
         */
        $iCategoryId = isset($aData['iCategoryId']) ? (int) $aData['iCategoryId'] : 0;
        // Get the callback.
        $aCallback = false;
        if ($sModule !== false && $iItem !== false && Phpfox::hasCallback($sModule, 'getVideoDetails'))
        {
            if (($aCallback = Phpfox::callback($sModule . '.getVideoDetails', array('item_id' => $iItem))))
            {
                if ($sModule == 'pages' && !Phpfox::getService('pages')->hasPerm($iItem, 'video.share_videos'))
                {
                    return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('video.unable_to_view_this_item_due_to_privacy_settings'));
                }
            }
        }
        
        $aDataCategories = $this->category(array());
        $aCategory = array();
        
        // Check parent.
        $iCount = count($aDataCategories);
        $iParentId = $iCategoryId;
        for ($i = $iCount - 1; $i >= 0; $i--)
        {
            if ($aDataCategories[$i]['iCategoryId'] == $iParentId)
            {
                $aCategory[] = $iParentId;
                // Update new parent.
                $iParentId = $aDataCategories[$i]['iParentId'];
            }
        }
        
        /**
         * @var array
         */
        $aVals = array(
            'url' => isset($aData['sUrl']) ? $aData['sUrl'] : '',
            'privacy' => isset($aData['iPrivacy']) ? (int) $aData['iPrivacy'] : 0,
            'privacy_comment' => isset($aData['iPrivacyComment']) ? (int) $aData['iPrivacyComment'] : 0,
            'category' => $aCategory,
            'module' => isset($aData['sModule']) ? $aData['sModule'] : null,
            'item' => isset($aData['iItem']) ? (int) $aData['iItem'] : null,
            'callback_item_id' => isset($aData['iItem']) ? (int) $aData['iItem'] : null,
            'callback_module' => isset($aData['sModule']) ? $aData['sModule'] : null,
        );
        // Check flood.
        if (($iFlood = Phpfox::getUserParam('video.flood_control_videos')) !== 0)
        {
            /**
             * @var array
             */
            $aFlood = array(
                'action' => 'last_post', // The SPAM action
                'params' => array(
                    'field' => 'time_stamp', // The time stamp field
                    'table' => Phpfox::getT('video'), // Database table we plan to check
                    'condition' => 'user_id = ' . Phpfox::getUserId(), // Database WHERE query
                    'time_stamp' => $iFlood * 60 // Seconds);	
                )
            );
            // actually check if flooding
            if (Phpfox::getLib('spam')->check($aFlood))
            {
                return array(
                    'result' => 0, 
                    'error_code' => 1, 
                    'message' => Phpfox::getPhrase('video.you_are_sharing_a_video_a_little_too_soon') . ' ' . Phpfox::getLib('spam')->getWaitTime());
            }
        }
        if (Phpfox_Error::isPassed())
        {
            /**
             * Get the video information by the link.
             */
            if (Phpfox::getService('video.grab')->get($aVals['url']))
            {
                // Add share video.
                if ($iId = Phpfox::getService('video.process')->addShareVideo($aVals))
                {
                    /**
                     * @var array
                     */
                    $aVideo = Phpfox::getService('video')->getForEdit($iId);
                    // Check image:
                    if (Phpfox::getService('video.grab')->hasImage())
                    {
                        if (isset($aVals['module']) && isset($aVals['item']) && Phpfox::hasCallback($aVals['module'], 'uploadVideo'))
                        {
                            $aCallback = Phpfox::callback($aVals['module'] . '.uploadVideo', $aVals['item']);
                            if ($aCallback !== false)
                            {
                                return array(
                                    'result' => 1,
                                    'error_code' => 0,
                                    'message' => Phpfox::getPhrase('video.video_successfully_added'),
                                    'iVideoId' => $aVideo['video_id'],
                                    'sTitle' => $aVideo['title'],
                                    'aCallback' => $aCallback);
                            }
                        }
                        return array(
                            'result' => 1,
                            'error_code' => 0,
                            'message' => Phpfox::getPhrase('video.video_successfully_added'),
                            'iVideoId' => $aVideo['video_id'],
                            'sTitle' => $aVideo['title']
                        );
                    }
                    else
                    {
                        return array(
                            'result' => 1,
                            'error_code' => 0,
                            'message' => Phpfox::getPhrase('video.video_successfull_added_however_you_will_have_to_manually_upload_a_photo_for_it'),
                            'iVideoId' => $aVideo['video_id'],
                            'sTitle' => $aVideo['title']
                        );
                    }
                }
            }
        }
        return array('result' => 0, 'error_code' => 1, 'message' => Phpfox_Error::get());
    }

    /**
     * input data:
     * + iVideoId: int, required.
     * + sTitle: string, required.
     * + iCategoryId: int, optional.
     * + sDescription: string, optional
     * + sTopic: string, optional.
     * + image: file, optional. To change image default.
     * 
     * output data: 
     * + result: int.
     * + error_code: int.
     * + message: string.
     * 
     * @see Mobile - API phpFox/Api V3.0
     * @see video/edit
     * 
     * @param array $aData  
     * @return array
     */
    public function edit($aData)
    {
        /**
         * @var int
         */
        $iVideoId = isset($aData['iVideoId']) ? (int) $aData['iVideoId'] : 0;
        // Get the video by id.
        $aVideo = Phpfox::getService('video')->getForEdit($iVideoId);
        /**
         * @var int
         */
        $iCategoryId = isset($aData['iCategoryId']) ? (int) $aData['iCategoryId'] : 0;
        if (!isset($aVideo['video_id']))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('video.the_video_you_are_looking_for_does_not_exist_or_has_been_removed'));
        }
        // Check privacy for song.
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('video', $aVideo['song_id'], $aVideo['user_id'], $aVideo['privacy'], $aVideo['is_friend'], true))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'));
        }
        
        $aDataCategories = $this->category(array());
        $aCategory = array();
        
        // Check parent.
        $iCount = count($aDataCategories);
        $iParentId = $iCategoryId;
        for ($i = $iCount - 1; $i >= 0; $i--)
        {
            if ($aDataCategories[$i]['iCategoryId'] == $iParentId)
            {
                $aCategory[] = $iParentId;
                // Update new parent.
                $iParentId = $aDataCategories[$i]['iParentId'];
            }
        }
        
        /**
         * @var array
         */
        $aVals = array(
            'title' => isset($aData['sTitle']) ? $aData['sTitle'] : '',
            'category' => $aCategory,
            'text' => isset($aData['sDescription']) ? $aData['sDescription'] : '',
            'privacy' => isset($aData['iPrivacy']) ? (int) $aData['iPrivacy'] : 0,
            'privacy_comment' => isset($aData['iPrivacyComment']) ? (int) $aData['iPrivacyComment'] : 0,
            'tag_list' => isset($aData['sTopic']) ? $aData['sTopic'] : '',
        );
        if (($mReturn = Phpfox::getService('video.process')->update($aVideo['video_id'], $aVals)))
        {
            return array('result' => 1, 'error_code' => 0, 'message' => Phpfox::getPhrase('video.video_successfully_updated'));
        }
        return array('result' => 0, 'error_code' => 1, 'message' => Phpfox_Error::get());
    }

    /**
     * Delete one video.
     * 
     * input data:
     * + iVideoId: int, required.
     * + sModule: string, optional.
     * + iItem: int, optional
     * 
     * output data:
     * + result: int.
     * + error_code: int.
     * + message: string.
     * 
     * @see Mobile - API phpFox/Api V3.0
     * @see video/delete
     * 
     * @param array $aData
     * @return array
     */
    public function delete($aData)
    {
        /**
         * @var int
         */
        $iVideoId = isset($aData['iVideoId']) ? (int) $aData['iVideoId'] : 0;
        /**
         * @var string
         */
        $sModule = isset($aData['sModule']) ? trim($aData['sModule']) : '';
        /**
         * @var int
         */
        $iItem = isset($aData['iItem']) ? (int) $aData['iItem'] : 0;
        // Get the callback.
        if ($iItem > 0 && !empty($sModule))
        {
            $aCallback = array(
                'module' => $sModule,
                'item' => $iItem
            );
        }
        else
        {
            $aCallback = false;
        }
        // Get the video.
        if (!($aVideo = Phpfox::getService('video')->callback($aCallback)->getVideo($iVideoId)))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('video.the_video_you_are_looking_for_does_not_exist_or_has_been_removed'));
        }
        // Check the privacy.
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('video', $aVideo['video_id'], $aVideo['user_id'], $aVideo['privacy'], $aVideo['is_friend'], true))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'));
        }
        // Delete video.
        if (Phpfox::getService('video.process')->delete($iVideoId))
        {
            return array('result' => 1, 'error_code' => 0, 'message' => Phpfox::getPhrase('video.video_successfully_deleted'));
        }
        return array('result' => 0, 'error_code' => 1, 'message' => Phpfox_Error::get());
    }

    /**
     * input data:
     * + iVideoId: int, required.
     * + sModule: string, optional.
     * + iItem: int, optional.
     * 
     * output data:
     * + result: int.
     * + error_code: int.
     * + message: string.
     * 
     * @see Mobile - API phpFox/Api V3.0
     * @see video/like
     * 
     * @param array $aData
     * @return array
     */
    public function like($aData)
    {
        /**
         * @var int
         */
        $iVideoId = isset($aData['iVideoId']) ? (int) $aData['iVideoId'] : 0;
        /**
         * @var string
         */
        $sModule = isset($aData['sModule']) ? trim($aData['sModule']) : '';
        /**
         * @var int
         */
        $iItem = isset($aData['iItem']) ? (int) $aData['iItem'] : 0;
        // Get the callback.
        if ($iItem > 0 && !empty($sModule))
        {
            $aCallback = array(
                'module' => $sModule,
                'item' => $iItem
            );
        }
        else
        {
            $aCallback = false;
        }
        // Get the video.
        if (!($aVideo = Phpfox::getService('video')->callback($aCallback)->getVideo($iVideoId)))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('video.the_video_you_are_looking_for_does_not_exist_or_has_been_removed'));
        }
        // Check the privacy.
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('video', $aVideo['video_id'], $aVideo['user_id'], $aVideo['privacy'], $aVideo['is_friend'], true))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'));
        }
        return Phpfox::getService('mfox.like')->add(array('sType' => 'video', 'iItemId' => $iVideoId));
    }

    /**
     * input data:
     * + iVideoId: int, required.
     * + sModule: string, optional.
     * + iItem: int, optional.
     * 
     * output data:
     * + result: int.
     * + error_code: int.
     * + message: string.
     * 
     * @see Mobile - API phpFox/Api V3.0
     * @see video/unlike
     * 
     * @param array $aData
     * @return array
     */
    public function unlike($aData)
    {
        /**
         * @var int
         */
        $iVideoId = isset($aData['iVideoId']) ? (int) $aData['iVideoId'] : 0;

        /**
         * @var string
         */
        $sModule = isset($aData['sModule']) ? trim($aData['sModule']) : '';

        /**
         * @var int
         */
        $iItem = isset($aData['iItem']) ? (int) $aData['iItem'] : 0;

        // Get the callback.
        if ($iItem > 0 && !empty($sModule))
        {
            $aCallback = array(
                'module' => $sModule,
                'item' => $iItem
            );
        }
        else
        {
            $aCallback = false;
        }

        // Get the video.
        if (!($aVideo = Phpfox::getService('video')->callback($aCallback)->getVideo($iVideoId)))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('video.the_video_you_are_looking_for_does_not_exist_or_has_been_removed'));
        }

        // Check the privacy.
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('video', $aVideo['video_id'], $aVideo['user_id'], $aVideo['privacy'], $aVideo['is_friend'], true))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'));
        }

        if (isset($aVideo['is_liked']) && $aVideo['is_liked'])
        {
            return Phpfox::getService('mfox.like')->delete(array('sType' => 'video', 'iItemId' => $iVideoId));
        }
        else
        {
            return array('result' => 0, 'error_code' => 1, 'message' => 'You have already "unliked" this item!');
        }
    }

    /**
     * Need to be changed.
     * 
     * input data:
     * + iVideoId: int, required.
     * + sModule: string, optional. In page.
     * + iItem: int, optional. In page.
     * 
     * output data:
     * + result: int.
     * + error_code: int.
     * + message: string.
     * + lastid: int. Last comment id.
     * 
     * @param int $iVideoId
     * @param string $sModule
     * @param int $iItem
     * @return array
     */
    public function checkPrivacyCommentOnVideo($iVideoId, $sModule = '', $iItem = 0)
    {
        // Get the callback.
        if ($iItem > 0 && !empty($sModule))
        {
            $aCallback = array(
                'module' => $sModule,
                'item' => $iItem
            );
        }
        else
        {
            $aCallback = false;
        }

        // Get the video.
        if (!($aVideo = Phpfox::getService('video')->callback($aCallback)->getVideo($iVideoId)))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('video.the_video_you_are_looking_for_does_not_exist_or_has_been_removed'));
        }

        // Check the privacy.
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('video', $aVideo['video_id'], $aVideo['user_id'], $aVideo['privacy'], $aVideo['is_friend'], true))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'));
        }

        if (!Phpfox::getService('mfox.comment')->checkCanPostCommentOnItem($aVideo))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => 'You don\'t have permission to post comment on this item!');
        }

        return null;
    }

    /**
     * Need to be changed.
     * 
     * input data:
     * + iVideoId: int, required.
     * + sModule: string, optional. In page.
     * + iItem: int, optional. In page.
     * 
     * output data:
     * + result: int.
     * + error_code: int.
     * + message: string.
     * + lastid: int. Last comment id.
     * 
     * @param int $iVideoId
     * @param string $sModule
     * @param int $iItem
     * @return array
     */
    public function checkPrivacyOnVideo($iVideoId, $sModule = '', $iItem = 0)
    {
        // Get the callback.
        if ($iItem > 0 && !empty($sModule))
        {
            $aCallback = array(
                'module' => $sModule,
                'item' => $iItem
            );
        }
        else
        {
            $aCallback = false;
        }

        // Get the video.
        if (!($aVideo = Phpfox::getService('video')->callback($aCallback)->getVideo($iVideoId)))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('video.the_video_you_are_looking_for_does_not_exist_or_has_been_removed'));
        }

        // Check the privacy.
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('video', $aVideo['video_id'], $aVideo['user_id'], $aVideo['privacy'], $aVideo['is_friend'], true))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'));
        }

        return null;
    }
    
    /**
     * Input data: N/A
     * 
     * Output data:
     * + iCategoryId: int.
     * + iParentId: int.
     * + bIsActive: bool.
     * + sName: string.
     * + iLevel: int.
     * + iOrdering: int.
     * 
     * @see Mobile - API phpFox/Api V3.0
     * @see video/category
     * 
     * @param array $aData
     * @return array
     */
    public function category($aData)
    {
        $aCategories = $this->_get(0, 1);
        return $this->getCategory($aCategories);
    }

    public function getCategory($aCategories)
    {
        $aResult = array();
        if ($aCategories)
        {
            foreach($aCategories as $aCategory)
            {
                $aResult[] = array(
                    'iParentId' => $aCategory['iParentId'],
                    'iCategoryId' => $aCategory['iCategoryId'],
                    'bIsActive' => $aCategory['bIsActive'],
                    'sName' => $aCategory['sName'],
                    'iLevel' => $aCategory['iLevel'],
                    'iOrdering' => $aCategory['iOrdering']
                );
                
                if ($aCategory['aChild'])
                {
                    $aTemp = $this->getCategory($aCategory['aChild']);
                    
                    foreach($aTemp as $aItem)
                    {
                        $aResult[] = $aItem;
                    }
                }
            }
        }
        return $aResult;
    }
    
    /**
     * Input data:
     * + iParentId: int.
     * + iActive: int.
     * 
     * Output data:
     * + iCategoryId: int.
     * + iParentId: int.
     * + bIsActive: bool.
     * + sName: string.
     * + iOrdering: int.
     * + aChild: array of sub categories.
     * 
     * @param int $iParentId Parent id of category.
     * @param int $iActive Is active category?
     * @return array
     */
    private function _get($iParentId, $iActive, $iLevel = 0)
    {
        /**
         * @var array
         */
        $aCategories = $this->database()
                ->select('category_id AS iCategoryId, parent_id AS iParentId, is_active AS bIsActive, name AS sName, ordering AS iOrdering')
                ->from(Phpfox::getT('video_category'))
                ->where('parent_id = ' . (int) $iParentId . ' AND is_active = ' . (int) $iActive . '')
                ->order('ordering ASC')
                ->execute('getRows');

        if (count($aCategories))
        {
            foreach ($aCategories as $iKey => $aCategory)
            {
                $aCategories[$iKey]['iLevel'] = $iLevel;
                $aCategories[$iKey]['aChild'] = $this->_get($aCategory['iCategoryId'], $iActive, $iLevel + 1);
            }
        }

        return $aCategories;
    }

    /**
     * Input data:
     * + sAction: string, optional, ex: "more" or "new".
     * + iLastTimeStamp: int, optional.
     * + iAmountOfVideo: int, optional.
     * + sView: string, optional.
     * + sTag: string, optional.
     * + iCategory: int, optional.
     * + iSponsor: int, optional.
     * + sModule: string, optional.
     * + iItem: int, optional.
     * + sSearch: string, optional.
     * + bIsUserProfile: string, optional, ex: "true" or "false".
     * + iProfileId: int, optional.
     * 
     * Output data:
     * + iVideoId: int.
     * + bInProcess: bool.
     * + bIsStream: bool.
     * + bIsFeatured: bool.
     * + bIsSpotlight: bool.
     * + bIsSponsor: bool.
     * + iViewId: bool.
     * + sModuleId: string.
     * + iItemId: int.
     * + iPrivacy: int.
     * + iPrivacyComment: int.
     * + sTitle: string.
     * + iUserId: int.
     * + iParentUserId: int.
     * + sDestination: string.
     * + sFileExt: string.
     * + sDuration: string.
     * + sResolutionX: string.
     * + sResolutionY: string.
     * + iTotalComment: int.
     * + iTotalLike: int.
     * + iTotalDislike: int.
     * + sVideoImage: string.
     * + iTotalScore: int.
     * + iTotalRating: int.
     * + iTimeStamp: int.
     * + sTimeStamp: string.
     * + iTotalView: int.
     * + bIsViewed: bool.
     * + iProfilePageId: int.
     * + sUserImage: string.
     * + sUsername: string.
     * + sFullname: string.
     * + iGender: int.
     * + bIsInvisible: bool.
     * + iUserGroupId: int.
     * + iLanguageId: int.
     * 
     * @see Mobile - API phpFox/Api V3.0
     * @see video/search
     * 
     * @param array $aData
     * @return array
     */
    public function search($aData)
    {
        return $this->getVideos($aData);
    }

    /**
     * Input data:
     * + sAction: string, optional, ex: "more" or "new".
     * + iLastTimeStamp: int, optional.
     * + iAmountOfVideo: int, optional.
     * + sView: string, optional.
     * + sTag: string, optional.
     * + iCategory: int, optional.
     * + iSponsor: int, optional.
     * + sModule: string, optional.
     * + iItem: int, optional.
     * + sSearch: string, optional.
     * + bIsUserProfile: string, optional, ex: "true" or "false".
     * + iProfileId: int, optional.
     * 
     * Output data:
     * + iVideoId: int.
     * + bInProcess: bool.
     * + bIsStream: bool.
     * + bIsFeatured: bool.
     * + bIsSpotlight: bool.
     * + bIsSponsor: bool.
     * + iViewId: bool.
     * + sModuleId: string.
     * + iItemId: int.
     * + iPrivacy: int.
     * + iPrivacyComment: int.
     * + sTitle: string.
     * + iUserId: int.
     * + iParentUserId: int.
     * + sDestination: string.
     * + sFileExt: string.
     * + sDuration: string.
     * + sResolutionX: string.
     * + sResolutionY: string.
     * + iTotalComment: int.
     * + iTotalLike: int.
     * + iTotalDislike: int.
     * + sVideoImage: string.
     * + iTotalScore: int.
     * + iTotalRating: int.
     * + iTimeStamp: int.
     * + sTimeStamp: string.
     * + iTotalView: int.
     * + bIsViewed: bool.
     * + iProfilePageId: int.
     * + sUserImage: string.
     * + sUsername: string.
     * + sFullname: string.
     * + iGender: int.
     * + bIsInvisible: bool.
     * + iUserGroupId: int.
     * + iLanguageId: int.
     * 
     * 
     * @param array $aData
     * @return array
     */
    private function getVideos($aData)
    {
        if (!Phpfox::getUserParam('video.can_access_videos'))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => 'You don\'t have permission to access videos!');
        }
        /**
         * @var string
         */
        $sAction = (isset($aData['sAction']) && $aData['sAction'] == 'new') ? 'new' : 'more';
        /**
         * @var int
         */
        $iLastTimeStamp = isset($aData['iLastTimeStamp']) ? (int) $aData['iLastTimeStamp'] : 0;
        /**
         * @var int
         */
        $iAmountOfVideo = isset($aData['iAmountOfVideo']) ? (int) $aData['iAmountOfVideo'] : 10;
        /**
         * @var string
         */
        $sView = isset($aData['sView']) ? $aData['sView'] : '';
        /**
         * @var string
         */
        $sTag = isset($aData['sTag']) ? $this->_tag($aData['sTag']) : '';
        /**
         * @var int
         */
        $iCategory = isset($aData['iCategory']) ? (int) $aData['iCategory'] : 0;
        /**
         * @var int
         */
        $iSponsor = isset($aData['iSponsor']) ? (int) $aData['iSponsor'] : 0;
        /**
         * @var string
         */
        $sModule = isset($aData['sModule']) ? trim($aData['sModule']) : '';
        /**
         * @var int
         */
        $iItem = isset($aData['iItem']) ? (int) $aData['iItem'] : 0;
        /**
         * @var string
         */
        $sSearch = isset($aData['sSearch']) ? $aData['sSearch'] : '';
        /**
         * @var bool
         */
        $bIsUserProfile = (isset($aData['bIsUserProfile']) && $aData['bIsUserProfile'] == 'true') ? true : false;

        // Get the callback.
        if ($iItem > 0 && !empty($sModule))
        {
            $aParentModule = array(
                'module_id' => $sModule,
                'item_id' => $iItem
            );
        }
        else
        {
            $aParentModule = false;
        }

        if ($bIsUserProfile)
        {
            /**
             * @var int
             */
            $iProfileId = isset($aData['iProfileId']) ? (int) $aData['iProfileId'] : 0;

            $aUser = Phpfox::getService('user')->get($iProfileId);

            if (!isset($aUser['user_id']))
            {
                return array('result' => 0, 'error_code' => 1, 'message' => 'Profile is not valid!');
            }
        }
        /**
         * @var array
         */
        $aCond = array();

        // For search.
        if (!empty($sSearch))
        {
            $aCond[] = 'm.title LIKE "' . Phpfox::getLib('parse.input')->clean('%' . $sSearch . '%') . '"';
        }

        if ($iLastTimeStamp > 0)
        {
            if ($sAction == 'more')
            {
                $aCond[] = 'm.time_stamp < ' . $iLastTimeStamp;
            }
            else
            {
                $aCond[] = 'm.time_stamp > ' . $iLastTimeStamp;
            }
        }

        switch ($sView) {
            case 'pending':
                if (Phpfox::getUserParam('video.can_approve_videos'))
                {
                    $aCond[] = 'm.view_id = 2';
                }
                break;
            case 'my':
                $aCond[] = 'm.user_id = ' . Phpfox::getUserId();
                break;
            default:
                if ($bIsUserProfile)
                {
                    $aCond[] = 'm.in_process = 0 AND m.view_id ' . ($aUser['user_id'] == Phpfox::getUserId() ? 'IN(0,2)' : '= 0') . ' AND m.item_id = 0 AND m.privacy IN(' . (Phpfox::getParam('core.section_privacy_item_browsing') ? '%PRIVACY%' : Phpfox::getService('core')->getForBrowse($aUser)) . ') AND m.user_id = ' . (int) $aUser['user_id'];
                }
                else
                {
                    if ($aParentModule !== false)
                    {
                        $aCond[] = 'm.in_process = 0 AND m.view_id = 0 AND m.module_id = \'' . Phpfox::getLib('database')->escape($aParentModule['module_id']) . '\' AND m.item_id = ' . (int) $aParentModule['item_id'] . ' AND m.privacy IN(%PRIVACY%)';
                    }
                    else
                    {
                        $aCond[] = 'm.in_process = 0 AND m.view_id = 0 AND m.item_id = 0 AND m.privacy IN(%PRIVACY%)';
                    }
                }
                break;
        }

        if ($iSponsor == 1)
        {
            $aCond[] = 'm.is_sponsor != 1';
        }

        if ($sView == 'featured')
        {
            $aCond[] = 'm.is_featured = 1';
        }

        foreach ($aCond as $iKey => $sCond)
        {
            switch ($sView) {
                case 'friend':
                    $aCond[$iKey] = str_replace('%PRIVACY%', '0,1,2', $sCond);
                    break;
                case 'my':
                    $aCond[$iKey] = str_replace('%PRIVACY%', '0,1,2,3,4', $sCond);
                    break;
                default:
                    $aCond[$iKey] = str_replace('%PRIVACY%', '0', $sCond);
                    break;
            }
        }

        if ($iCategory > 0)
        {
            $aCond[] = 'mcd.category_id = ' . (int) $iCategory;
        }

        // Get number of the video.
        $this->database()
                ->select('COUNT(*)')
                ->from(Phpfox::getT('video'), 'm');

        if ($iCategory > 0)
        {
            $this->database()->innerJoin(Phpfox::getT('video_category_data'), 'mcd', 'mcd.video_id = m.video_id');
        }
        if ($sView == 'friend' && Phpfox::isModule('friend'))
        {
            $this->database()->join(Phpfox::getT('friend'), 'friends', 'friends.user_id = m.user_id AND friends.friend_user_id = ' . Phpfox::getUserId());
        }
        if (!empty($sTag))
        {
            $this->database()->innerJoin(Phpfox::getT('tag'), 'tag', 'tag.item_id = m.video_id AND tag.category_id = \'' . (defined('PHPFOX_GROUP_VIEW') ? 'video_group' : 'video') . '\' AND tag_text = "' . $sTag . '"');
        }
        /**
         * @var int
         */
        $iCount = $this->database()
                ->where(implode(' AND ', $aCond))
                ->limit(1)
                ->execute('getField');
        /**
         * @var array
         */
        $aRows = array();

        if ($iCount > 0)
        {
            // Get array of the video.
            $this->database()
                    ->select('m.*, u.user_id, u.profile_page_id, u.server_id AS user_server_id, u.user_name, u.full_name, u.gender, u.user_image, u.is_invisible, u.user_group_id, u.language_id')
                    ->from(Phpfox::getT('video'), 'm');

            if ($iCategory > 0)
            {
                $this->database()->innerJoin(Phpfox::getT('video_category_data'), 'mcd', 'mcd.video_id = m.video_id');
            }
            if (!empty($sTag))
            {
                $this->database()->innerJoin(Phpfox::getT('tag'), 'tag', 'tag.item_id = m.video_id AND tag.category_id = \'' . (defined('PHPFOX_GROUP_VIEW') ? 'video_group' : 'video') . '\' AND tag_text = "' . $sTag . '"');
            }
            if ($sView == 'friend' && Phpfox::isModule('friend'))
            {
                $this->database()->join(Phpfox::getT('friend'), 'friends', 'friends.user_id = m.user_id AND friends.friend_user_id = ' . Phpfox::getUserId());
            }
            /**
             * @var array
             */
            $aRows = $this->database()
                    ->join(Phpfox::getT('user'), 'u', 'u.user_id = m.user_id')
                    ->where(implode(' AND ', $aCond))
                    ->order('m.time_stamp DESC')
                    ->limit(0, $iAmountOfVideo, $iCount)
                    ->execute('getRows');
        }
        /**
         * @var array
         */
        $aResult = array();

        foreach ($aRows as $aRow)
        {
            $aResult[] = array(
                'iVideoId' => $aRow['video_id'],
                'bInProcess' => $aRow['in_process'],
                'bIsStream' => $aRow['is_stream'],
                'bIsFeatured' => $aRow['is_featured'],
                'bIsSpotlight' => $aRow['is_spotlight'],
                'bIsSponsor' => $aRow['is_sponsor'],
                'iViewId' => $aRow['view_id'],
                'sModuleId' => $aRow['module_id'],
                'iItemId' => $aRow['item_id'],
                'iPrivacy' => $aRow['privacy'],
                'iPrivacyComment' => $aRow['privacy_comment'],
                'sTitle' => $aRow['title'],
                'iUserId' => $aRow['user_id'],
                'iParentUserId' => $aRow['parent_user_id'],
                'sDestination' => $aRow['destination'],
                'sFileExt' => $aRow['file_ext'],
                'sDuration' => $aRow['duration'],
                'sResolutionX' => $aRow['resolution_x'],
                'sResolutionY' => $aRow['resolution_y'],
                'iTotalComment' => $aRow['total_comment'],
                'iTotalLike' => $aRow['total_like'],
                'iTotalDislike' => $aRow['total_dislike'],
                'sVideoImage' => Phpfox::getLib('image.helper')->display(array(
                    'server_id' => $aRow['image_server_id'],
                    'path' => 'video.url_image',
                    'file' => $aRow['image_path'],
                    'suffix' => '_120',
                    'return_url' => true
                        )
                ),
                'iTotalScore' => $aRow['total_score'],
                'iTotalRating' => $aRow['total_rating'],
                'iTimeStamp' => $aRow['time_stamp'],
                'sTimeStamp' => date('l, F j, o', (int) $aRow['time_stamp']) . ' at ' . date('h:i a', (int) $aRow['time_stamp']),
                'iTotalView' => $aRow['total_view'],
                'bIsViewed' => $aRow['is_viewed'],
                'iProfilePageId' => $aRow['profile_page_id'],
                'sUserImage' => Phpfox::getLib('image.helper')->display(array(
                    'server_id' => $aRow['user_server_id'],
                    'path' => 'core.url_user',
                    'file' => $aRow['user_image'],
                    'suffix' => '_50_square',
                    'return_url' => true
                        )
                ),
                'sUsername' => $aRow['user_name'],
                'sFullname' => $aRow['full_name'],
                'iGender' => $aRow['gender'],
                'bIsInvisible' => $aRow['is_invisible'],
                'iUserGroupId' => $aRow['user_group_id'],
                'iLanguageId' => isset($aRow['language_id']) ? $aRow['language_id'] : 0
            );
        }

        return $aResult;
    }

    /**
     * 
     * @param string $sTag
     * @return string
     */
    private function _tag($sTag)
    {
        /**
         * @var array
         */
        $aTag = Phpfox::getService('tag')->getTagInfo('video', $sTag);

        if (!empty($aTag['tag_text']))
        {
            return $aTag['tag_text'];
        }

        return '';
    }

    /**
     * Input data:
     * + sAction: string, optional, ex: "more" or "new".
     * + iLastTimeStamp: int, optional.
     * + iAmountOfVideo: int, optional.
     * + sView: string, optional. Ex: "friend", "my" and "all".
     * + sTag: string, optional.
     * + iCategory: int, optional.
     * + iSponsor: int, optional.
     * + sModule: string, optional.
     * + iItem: int, optional.
     * + sSearch: string, optional.
     * + bIsUserProfile: string, optional, ex: "true" or "false".
     * + iProfileId: int, optional.
     * 
     * Output data:
     * + iVideoId: int.
     * + bInProcess: bool.
     * + bIsStream: bool.
     * + bIsFeatured: bool.
     * + bIsSpotlight: bool.
     * + bIsSponsor: bool.
     * + iViewId: bool.
     * + sModuleId: string.
     * + iItemId: int.
     * + iPrivacy: int.
     * + iPrivacyComment: int.
     * + sTitle: string.
     * + iUserId: int.
     * + iParentUserId: int.
     * + sDestination: string.
     * + sFileExt: string.
     * + sDuration: string.
     * + sResolutionX: string.
     * + sResolutionY: string.
     * + iTotalComment: int.
     * + iTotalLike: int.
     * + iTotalDislike: int.
     * + sVideoImage: string.
     * + iTotalScore: int.
     * + iTotalRating: int.
     * + iTimeStamp: int.
     * + sTimeStamp: string.
     * + iTotalView: int.
     * + bIsViewed: bool.
     * + iProfilePageId: int.
     * + sUserImage: string.
     * + sUsername: string.
     * + sFullname: string.
     * + iGender: int.
     * + bIsInvisible: bool.
     * + iUserGroupId: int.
     * + iLanguageId: int.
     * 
     * @see Mobile - API phpFox/Api V3.0
     * @see video/filter
     * 
     * @param array $aData
     * @return array
     */
    public function filter($aData)
    {
        return $this->getVideos($aData);
    }

    /**
     * Using in notification.
     * @param array $aNotification
     * @return boolean|array
     */
    public function doVideoGetNotificationLike($aNotification)
    {
        /**
         * @var array
         */
        $aRow = $this->database()->select('v.video_id, v.title, v.user_id, u.gender, u.full_name')
                ->from(Phpfox::getT('video'), 'v')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = v.user_id')
                ->where('v.video_id = ' . (int) $aNotification['item_id'])
                ->execute('getSlaveRow');

        if (!isset($aRow['video_id']))
        {
            return false;
        }
        /**
         * @var string
         */
        $sPhrase = '';
        if ($aNotification['user_id'] == $aRow['user_id'])
        {
            $sPhrase = Phpfox::getPhrase('video.user_name_liked_gender_own_video_title', array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...'), 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1)));
        }
        elseif ($aRow['user_id'] == Phpfox::getUserId())
        {
            $sPhrase = Phpfox::getPhrase('video.user_name_liked_your_video_title', array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...')));
        }
        else
        {
            $sPhrase = Phpfox::getPhrase('video.user_name_liked_span_class_drop_data_user_full_name_s_span_video_title', array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'full_name' => $aRow['full_name'], 'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...')));
        }

        return array(
            'link' => array(
                'iVideoId' => $aRow['video_id'],
                'sVideoTitle' => $aRow['title']
            ),
            'message' => strip_tags($sPhrase),
            'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
        );
    }

    /**
     * 
     * @param array $aNotification
     * @return array
     */
    public function doVideoGetCommentNotification($aNotification)
    {
        /**
         * @var array
         */
        $aRow = $this->database()->select('l.video_id, l.title, u.user_id, u.gender, u.user_name, u.full_name')
                ->from(Phpfox::getT('video'), 'l')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = l.user_id')
                ->where('l.video_id = ' . (int) $aNotification['item_id'])
                ->execute('getSlaveRow');
        /**
         * @var string
         */
        $sPhrase = '';
        if ($aNotification['user_id'] == $aRow['user_id'] && !isset($aNotification['extra_users']))
        {
            $sPhrase = Phpfox::getPhrase('video.user_name_commented_on_gender_video_title', array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...')));
        }
        elseif ($aRow['user_id'] == Phpfox::getUserId())
        {
            $sPhrase = Phpfox::getPhrase('video.user_name_commented_on_your_video_title', array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...')));
        }
        else
        {
            $sPhrase = Phpfox::getPhrase('video.user_name_commented_on_span_class_drop_data_user_full_name_s_span_video_title', array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'full_name' => $aRow['full_name'], 'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...')));
        }

        return array(
            'link' => array(
                'iVideoId' => $aRow['video_id'],
                'sVideoTitle' => $aRow['title']
            ),
            'message' => strip_tags($sPhrase),
            'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog'),
            'sModule' => 'video',
            'sMethod' => 'getCommentNotification'
        );
    }

    /**
     * Using in notification.
     * @param int $iId
     * @param int $iChild
     * @return boolean|array
     */
    public function doVideoGetFeedRedirect($iId, $iChild = 0)
    {
        /**
         * @var array
         */
        $aRow = $this->database()
                ->select('m.video_id, m.title')
                ->from(Phpfox::getT('video'), 'm')
                ->where('m.video_id = ' . (int) $iId)
                ->execute('getSlaveRow');

        if (!isset($aRow['video_id']))
        {
            return false;
        }

        return array(
            'sModule' => 'video',
            'iVideoId' => $aRow['video_id'],
            'sTitle' => $aRow['title'],
            'sCommentType' => 'video'
        );
    }

    /**
     * Using in notification comment.
     * @param int $iId
     * @return boolean|array
     */
    public function doVideoGetRedirectComment($iId)
    {
        return $this->doVideoGetFeedRedirect($iId);
    }
    
    /**
     * Input data:
	 * + sVideoTitle: string, optional.
	 * + sTitle: string, optional.
	 * + sCallbackModule: string, optional.
	 * + iParentUserId: int, optional.
	 * + iPrivacy: int, optional.
	 * + iPrivacyComment: int, optional.
	 * + sPrivacyList: string, optional.
	 * + sStatusInfo: string, optional.
	 * + sCategory: string, optional.
	 * + sTagList: string, optional.
     * + video: file, required.
     * + iTwitter: int, optional.
     * + iFacebook: int, optional.
     * + sCustomPagesPostAsPage: string, optional.
     * 
     * Output data:
     * + result: int.
     * + error_code: int.
     * + iVideoId: int.
     * + message: string.
     * 
     * @param array $aData
     * @return array
     */
    public function uploadVideoFromWall($aData)
    {
        if (!Phpfox::getParam('video.allow_video_uploading') || !Phpfox::getUserParam('video.can_upload_videos'))
		{
			return array('result' => 0, 'error_code' => 1, 'message' => 'You don\'t have permission to upload video!');
		}
        
        if (!isset($_FILES['video']))
		{
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('video.upload_failed_file_is_too_large'));
        }
        
        if (($iFlood = Phpfox::getUserParam('video.flood_control_videos')) !== 0)
		{
            /**
             * @var array
             */
			$aFlood = array(
				'action' => 'last_post', // The SPAM action
				'params' => array(
					'field' => 'time_stamp', // The time stamp field
					'table' => Phpfox::getT('video'), // Database table we plan to check
					'condition' => 'view_id = 0 AND user_id = ' . Phpfox::getUserId(), // Database WHERE query
					'time_stamp' => $iFlood * 60 // Seconds);	
				)
			);
							 			
			// actually check if flooding
			if (Phpfox::getLib('spam')->check($aFlood))
			{
                return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('video.you_are_uploading_a_video_a_little_too_soon') . ' ' . Phpfox::getLib('spam')->getWaitTime());
			}
		}
        
        if (!empty($_FILES['video']['tmp_name']))
        {
            Phpfox::getService('video.process')->delete();
        }
        /**
         * @var int
         */
        $iTwitter = isset($aData['iTwitter']) ? $aData['iTwitter'] : null;
        /**
         * @var int
         */
        $iFacebook = isset($aData['iFacebook']) ? $aData['iFacebook'] : null;
        /**
         * @var string
         */
        $sCustomPagesPostAsPage = isset($aData['sCustomPagesPostAsPage']) ? $aData['sCustomPagesPostAsPage'] : '';
        /**
         * @var array
         */
        $aVals = array(
            'video_title' => isset($aData['sVideoTitle']) ? $aData['sVideoTitle'] : '',
            'title' => isset($aData['sTitle']) ? $aData['sTitle'] : '',
            'callback_module' => isset($aData['sCallbackModule']) ? $aData['sCallbackModule'] : null,
            'parent_user_id' => isset($aData['iParentUserId']) ? (int) $aData['iParentUserId'] : 0,
            'privacy' => isset($aData['iPrivacy']) ? (int) $aData['iPrivacy'] : 0,
            'privacy_comment' => isset($aData['iPrivacyComment']) ? (int) $aData['iPrivacyComment'] : 0,
            'privacy_list' => isset($aData['sPrivacyList']) ? $aData['sPrivacyList'] : '',
            'status_info' => isset($aData['sStatusInfo']) ? $aData['sStatusInfo'] : null,
            'category' => isset($aData['sCategory']) ? explode(',', $aData['sCategory']) : null,
            'tag_list' => isset($aData['sTagList']) ? explode(',', $aData['sTagList']) : null
        );
        
        if ($iId = Phpfox::getService('video.process')->add($aVals))
		{
            if (Phpfox::getParam('video.vidly_support'))
			{
                /**
                 * @var array
                 */
				$aVideo = Phpfox::getService('video')->getVideo($iId, true);
				
				Phpfox::getLib('database')->insert(Phpfox::getT('vidly_url'), array(
						'video_id' => $aVideo['video_id'],
						'video_url' => Phpfox::getParam('video.url') . sprintf($aVideo['destination'], ''),
						'upload_video_id' => '0'
					)
				);				
				
				Phpfox::getService('video')->vidlyPost('AddMedia', array('Source' => array(
							'SourceFile' => Phpfox::getParam('video.url') . sprintf($aVideo['destination'], ''),
							'CDN' => 'RS'
						)
					), 'vidid_' . $aVideo['video_id'] . '/'
				);
			}
            
            return array(
                'result' => 1,
                'error_code' => 0,
                'iVideoId' => $iId,
                'message' => Phpfox::getPhrase('video.your_video_has_successfully_been_uploaded_please_standby_while_we_convert_your_video')
            );
        }
        else
		{
            if (!empty($_FILES['video']['tmp_name']))
            {
                Phpfox::getService('video.process')->delete();
            }
            
            return array(
                'result' => 0, 
                'error_code' => 1, 
                'message' => Phpfox_Error::get()
            );
		}
    }
    /**
     * Input data:
     * + 
     * @param array $aData
     * @return array
     */
    public function convertVideo($aData)
    {
        /**
         * @var int
         */
        $iAttachmentId = isset($aData['iAttachmentId']) ? (int) $aData['iAttachmentId'] : 0;
        
		if (Phpfox::getService('video.convert')->process($iAttachmentId, true))
		{
            /**
             * @var int
             */
			$iFeedId = Phpfox::getService('feed.process')->getLastId();
            
            return Phpfox::getService('mfox.feed')->getOneFeed(array('iItemId' => 0), $iFeedId);
		}
        
        return array(
            'result' => 0, 
            'error_code' => 1, 
            'message' => Phpfox_Error::get()
        );
    }
    /**
     * Delete image only.
     * 
     * Input data:
     * + iVideoId: int, required.
     * 
     * Output data:
     * + result: int.
     * + error_code: int.
     * + message: string.
     * 
     * @see Mobile - API phpFox/Api V3.0
     * @see video/deleteImage
     * 
     * @param array $aData
     * @return array
     */
    public function deleteImage($aData)
	{
        /**
         * @var int
         */
        $iVideoId = isset($aData['iVideoId']) ? (int) $aData['iVideoId'] : 0;
        
		if (Phpfox::getService('video.process')->deleteImage($iVideoId))
		{
			return array(
                'result' => 1, 
                'error_code' => 0, 
                'message' => 'Delete image successfully!'
            );
		}
        
        return array(
            'result' => 0, 
            'error_code' => 1, 
            'message' => Phpfox_Error::get()
        );
	}
    /**
     * Input data:
     * + iVideoId: int, required.
     * 
     * Output data:
     * + bVideoIsViewed: bool.
     * + bIsFriend: bool.
     * + bIsLiked: bool.
     * + iVideoId: int.
     * + iCategoryId: int.
     * + bInProcess: bool.
     * + bIsStream: bool.
     * + bIsFeatured: bool.
     * + bIsSpotlight: bool.
     * + bIsSponsor: bool.
     * + iViewId: bool.
     * + sModuleId: string.
     * + iItemId: int.
     * + iPrivacy: int.
     * + iPrivacyComment: int.
     * + sTitle: string.
     * + iUserId: int.
     * + iParentUserId: int.
     * + sDestination: string.
     * + sFileExt: string.
     * + sDuration: string.
     * + sResolutionX: string.
     * + sResolutionY: string.
     * + iTotalComment: int.
     * + iTotalLike: int.
     * + iTotalDislike: int.
     * + sVideoImage: string.
     * + iTotalScore: int.
     * + iTotalRating: int.
     * + iTimeStamp: int.
     * + sTimeStamp: string.
     * + iTotalView: int.
     * + bIsViewed: bool.
     * + iProfilePageId: int.
     * + sUserImage: string.
     * + sUsername: string.
     * + sFullname: string.
     * + iGender: int.
     * + bIsInvisible: bool.
     * + iUserGroupId: int.
     * + iLanguageId: int.
     * + sYoutubeVideoUrl: string.
     * + sEmbed: string.
     * + iTotalUserVideos: int.
     * 
     * @var Mobile - API phpFox/Api V3.0
     * @var video/details
     * 
     * @see Phpfox_Parse_Format
     * 
     * @param array $aData
     * @return array
     */
    public function details($aData)
    {
        Phpfox::getLib('setting')->setParam('core.allow_html', false);
        
        /**
         * @var int
         */
        $iVideoId = isset($aData['iVideoId']) ? (int) $aData['iVideoId'] : 0;
        /**
         * @var string
         */
        $sModule = isset($aData['sModule']) ? $aData['sModule'] : '';
        /**
         * @var int
         */
        $iItem = isset($aData['iItem']) ? (int) $aData['iItem'] : 0;
        // Get the video by id.
        $aVideo = Phpfox::getService('video')->callback(array('sModule' => $sModule, 'iItem' => $iItem))->getVideo($iVideoId);
        if (!isset($aVideo['video_id']))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('video.the_video_you_are_looking_for_does_not_exist_or_has_been_removed'));
        }
        // Check privacy for song.
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('video', $aVideo['song_id'], $aVideo['user_id'], $aVideo['privacy'], $aVideo['is_friend'], true))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'));
        }
        $sVideoPath = '';
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
        }
        $aTagList = isset($aVideo['tag_list']) ? $aVideo['tag_list'] : 0;
        $aTags = array();
        foreach($aTagList as $aTag)
        {
            $aTags[] = $aTag['tag_text'];
        }
        $sTags = implode(', ', $aTags);
        
        $sVideoPath = '';
        
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
        }
        $aCategories = $this->database()
                ->select('pc.parent_id AS iParentId, pc.category_id AS iCategoryId, pc.name AS sName')
				->from(Phpfox::getT('video_category_data'), 'pcd')
				->join(Phpfox::getT('video_category'), 'pc', 'pc.category_id = pcd.category_id')
				->where('pcd.video_id = ' . (int) $aVideo['video_id'])
				->order('pc.parent_id ASC, pc.ordering ASC')
				->execute('getSlaveRows');

        $iCategoryId = 0;
        foreach($aCategories as $aCategory)
        {
            if ($iCategoryId <= $aCategory['iParentId'])
            {
                $iCategoryId = $aCategory['iCategoryId'];
            }
        }
        
        return array(
            'bVideoIsViewed' => $aVideo['video_is_viewed'],
            'bIsFriend' => $aVideo['is_friend'],
            'bIsLiked' => $aVideo['is_liked'],
            'iVideoId' => $aVideo['video_id'],
            'iCategoryId' => $iCategoryId,
            'bInProcess' => $aVideo['in_process'],
            'bIsStream' => $aVideo['is_stream'],
            'bIsFeatured' => $aVideo['is_featured'],
            'bIsSpotlight' => $aVideo['is_spotlight'],
            'bIsSponsor' => $aVideo['is_sponsor'],
            'iViewId' => $aVideo['view_id'],
            'sModuleId' => $aVideo['module_id'],
            'iItemId' => $aVideo['item_id'],
            'iPrivacy' => $aVideo['privacy'],
            'iPrivacyComment' => $aVideo['privacy_comment'],
            'sTitle' => $aVideo['title'],
            'iUserId' => $aVideo['user_id'],
            'iParentUserId' => $aVideo['parent_user_id'],
            'sDestination' => $sVideoPath,
            'sFileExt' => $aVideo['file_ext'],
            'sDuration' => $aVideo['duration'],
            'sResolutionX' => $aVideo['resolution_x'],
            'sResolutionY' => $aVideo['resolution_y'],
            'iTotalComment' => $aVideo['total_comment'],
            'iTotalLike' => $aVideo['total_like'],
            'iTotalDislike' => $aVideo['total_dislike'],
            'sVideoImage' => Phpfox::getLib('image.helper')->display(array(
                'server_id' => $aVideo['image_server_id'],
                'path' => 'video.url_image',
                'file' => $aVideo['image_path'],
                'suffix' => '_120',
                'return_url' => true
                    )
            ),
            'iTotalScore' => $aVideo['total_score'],
            'iTotalRating' => $aVideo['total_rating'],
            'iTimeStamp' => $aVideo['time_stamp'],
            'sTimeStamp' => date('l, F j, o', (int) $aVideo['time_stamp']) . ' at ' . date('h:i a', (int) $aVideo['time_stamp']),
            'iTotalView' => $aVideo['total_view'],
            'bIsViewed' => $aVideo['is_viewed'],
            'sText' => $aVideo['text'],
            'iProfilePageId' => $aVideo['profile_page_id'],
            'sUserImage' => Phpfox::getLib('image.helper')->display(array(
                'server_id' => $aVideo['user_server_id'],
                'path' => 'core.url_user',
                'file' => $aVideo['user_image'],
                'suffix' => '_50_square',
                'return_url' => true
                    )
            ),
            'sUsername' => $aVideo['user_name'],
            'sFullname' => $aVideo['full_name'],
            'iGender' => $aVideo['gender'],
            'bIsInvisible' => $aVideo['is_invisible'],
            'iUserGroupId' => $aVideo['user_group_id'],
            'iLanguageId' => isset($aVideo['language_id']) ? $aVideo['language_id'] : 0,
            'sYoutubeVideoUrl' => $aVideo['youtube_video_url'],
            'sEmbed' => empty($aVideo['youtube_video_url']) ? '' : '<iframe width="420" height="315" src="http://www.youtube.com/embed/' . $aVideo['youtube_video_url'] . '" frameborder="0" allowfullscreen></iframe>',
            'iTotalUserVideos' => $aVideo['total_user_videos'],
            'sVideoWebLink' => Phpfox::getLib('url')->permalink('mobile.video', $aVideo['video_id'], $aVideo['title']),
            'sTopic' => $sTags,
            'bCanPostComment' => Phpfox::getService('mfox.comment')->checkCanPostCommentOnItem($aVideo)
        );
    }
    /**
     * Push Cloud Message for video.
     * @param int $iVideoId
     * @param string $sModule
     * @param int $iItem
     */
    public function doPushCloudMessageVideo($aData)
    {
        /**
         * @var string
         */
        $sModule = isset($aData['sModule']) ? $aData['sModule'] : '';
        /**
         * @var int
         */
        $iItem = isset($aData['iItem']) ? (int) $aData['iItem'] : 0;
        /**
         * @var int
         */
        $iVideoId = isset($aData['iItemId']) ? (int) $aData['iItemId'] : 0;
        
        // Get the callback.
        if ($iItem > 0 && !empty($sModule))
        {
            $aCallback = array(
                'module' => $sModule,
                'item' => $iItem
            );
        }
        else
        {
            $aCallback = false;
        }

        // Get the video.
        $aVideo = Phpfox::getService('video')->callback($aCallback)->getVideo($iVideoId);
        if (isset($aVideo['user_id']) && $aVideo['user_id'] != Phpfox::getUserId())
        {
            /**
             * @var int
             */
            $iPushId = Phpfox::getService('mfox.push')->savePush($aData, $aVideo['user_id']);
            
            Phpfox::getService('mfox.cloudmessage') -> send(array('message' => 'notification', 'iPushId' => $iPushId), $aVideo['user_id']);
        }
    }
}
