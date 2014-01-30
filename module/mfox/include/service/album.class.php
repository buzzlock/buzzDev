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
 * @since May 24, 2013
 * @link Mfox Api v2.0
 */
class Mfox_Service_Album extends Phpfox_Service {

    /**
     * Use to set the size of photos.
     * @var array Photo sizes.
     */
    private $_aPhotoSizes = array(50, 120, 200);

    /**
     * Input data:
     * + sName: string, required.
     * + sYear: string, required.
     * + sText: string, optional.
     * + iPrivacy: int, optional.
     * + iPrivacyComment: int, optional.
     * + sPrivacyList: string, optional, ex: "5,4,9".
     * + image: file, optional.
     * 
     * Output data:
     * + result: int.
     * + error_code: int.
     * + message: string.
     * 
     * @see Mobile - API phpFox/Api V3.0
     * @see album/create
     * 
     * @param array $aData
     * @return array
     */
    public function create($aData)
    {
        if (!Phpfox::getUserParam('music.can_access_music') || !Phpfox::getUserParam('music.can_upload_music_public'))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => 'You don\'t have permission to access music module!');
        }

        /**
         * @var array
         */
        $aVals = array(
            'name' => isset($aData['sName']) ? $aData['sName'] : '',
            'year' => isset($aData['sYear']) ? $aData['sYear'] : '',
            'text' => isset($aData['sText']) ? $aData['sText'] : '',
            'privacy' => isset($aData['iPrivacy']) ? (int) $aData['iPrivacy'] : 0,
            'privacy_comment' => isset($aData['iPrivacyComment']) ? (int) $aData['iPrivacyComment'] : 0,
            'privacy_list' => isset($aData['sPrivacyList']) ? explode(',', $aData['sPrivacyList']) : array()
        );

        /**
         * @var array
         */
        $aValidation = array(
            'name' => Phpfox::getPhrase('music.provide_a_name_for_this_album'),
            'year' => array(
                'def' => 'year'
            )
        );

        /**
         * @var Phpfox_Validator
         */
        $oValidator = Phpfox::getLib('validator')->set(array(
            'sFormName' => 'js_album_form',
            'aParams' => $aValidation
                )
        );

        // Validate data.
        if ($oValidator->isValid($aVals))
        {
            if ($iId = $this->add($aVals))
            {
                return array('result' => 1, 'error_code' => 0, 'message' => Phpfox::getPhrase('music.album_successfully_added'), 'iAlbumId' => $iId);
            }
        }

        return array('result' => 0, 'error_code' => 1, 'message' => Phpfox_Error::get());
    }

    /**
     * Input data:
     * + iAlbumId: int, required.
     * + sName: string, required.
     * + sYear: string, required.
     * + sText: string, optional.
     * + iPrivacy: int, optional.
     * + iPrivacyComment: int, optional.
     * + sPrivacyList: string, optional, ex: "5,4,9".
     *
     * Output data:
     * + result: int.
     * + error_code: int.
     * + message: string.
     * 
     * @see Mobile - API phpFox/Api V3.0
     * @see album/edit
     * 
     * @param array $aData
     * @return array
     */
    public function edit($aData)
    {
        if (!Phpfox::getUserParam('music.can_access_music') || !Phpfox::getUserParam('music.can_upload_music_public'))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => 'You don\'t have permission to access music module!');
        }

        /**
         * @var int
         */
        $iAlbumId = isset($aData['iAlbumId']) ? (int) $aData['iAlbumId'] : 0;

        /**
         * @var array
         */
        $aVals = array(
            'name' => isset($aData['sName']) ? $aData['sName'] : '',
            'year' => isset($aData['sYear']) ? $aData['sYear'] : '',
            'text' => isset($aData['sText']) ? $aData['sText'] : '',
            'privacy' => isset($aData['iPrivacy']) ? (int) $aData['iPrivacy'] : 0,
            'privacy_comment' => isset($aData['iPrivacyComment']) ? (int) $aData['iPrivacyComment'] : 0,
            'privacy_list' => isset($aData['sPrivacyList']) ? explode(',', $aData['sPrivacyList']) : array()
        );
        /**
         * @var array
         */
        $aValidation = array(
            'name' => Phpfox::getPhrase('music.provide_a_name_for_this_album'),
            'year' => array(
                'def' => 'year'
            )
        );

        /**
         * @var Phpfox_Validator
         */
        $oValidator = Phpfox::getLib('validator')->set(array(
            'sFormName' => 'js_album_form',
            'aParams' => $aValidation
                )
        );

        if ($oValidator->isValid($aVals))
        {
            if ($this->update($iAlbumId, $aVals))
            {
                return array('result' => 1, 'error_code' => 0, 'message' => Phpfox::getPhrase('music.album_successfully_updated'), 'iAlbumId' => $iAlbumId);
            }
        }

        return array('result' => 0, 'error_code' => 1, 'message' => Phpfox_Error::get());
    }

    /**
     * 
     * @see Mfox_Service_Ban
     * @see Privacy_Service_Privacy
     * 
     * @param int $iId Album id.
     * @param array $aVals
     * Input data:
     * + name: string, required.
     * + year: string, required.
     * + text: string, optional.
     * + privacy: int, optional.
     * + privacy_comment: int, optional.
     * + privacy_list: string, optional, ex: "5,4,9".
     * + image: file upload.
     * 
     * @return boolean
     */
    private function update($iId, $aVals)
    {
        /**
         * @var array
         */
        $aAlbum = $this->database()->select('*')
                ->from(Phpfox::getT('music_album'))
                ->where('album_id = ' . (int) $iId)
                ->execute('getSlaveRow');

        if (!isset($aAlbum['album_id']))
        {
            return Phpfox_Error::set(Phpfox::getPhrase('music.unable_to_find_the_album_you_want_to_edit'));
        }

        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('music_album', $aAlbum['album_id'], $aAlbum['user_id'], $aAlbum['privacy'], $aAlbum['is_friend'], true))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'));
        }

        if (!Phpfox::getService('mfox.ban')->checkAutomaticBan($aVals['name'] . ' ' . $aVals['text']))
        {
            return false;
        }

        if (($aAlbum['user_id'] == Phpfox::getUserId() && Phpfox::getUserParam('music.can_edit_own_albums')) || Phpfox::getUserParam('music.can_edit_other_music_albums'))
        {
            if (empty($aVals['privacy']))
            {
                $aVals['privacy'] = 0;
            }

            if (empty($aVals['privacy_comment']))
            {
                $aVals['privacy_comment'] = 0;
            }

            $this->database()->update(Phpfox::getT('music_album'), array(
                'privacy' => (isset($aVals['privacy']) ? $aVals['privacy'] : '0'),
                'privacy_comment' => (isset($aVals['privacy_comment']) ? $aVals['privacy_comment'] : '0'),
                'name' => $this->preParse()->clean($aVals['name'], 255),
                'year' => $aVals['year']
                    ), 'album_id = ' . $aAlbum['album_id']
            );

            $this->database()->update(Phpfox::getT('music_album_text'), array(
                'text' => (empty($aVals['text']) ? null : $this->preParse()->clean($aVals['text'])),
                'text_parsed' => (empty($aVals['text']) ? null : $this->preParse()->prepare($aVals['text']))
                    ), 'album_id = ' . $aAlbum['album_id']
            );
            /**
             * @var array
             */
            $aSongs = $this->database()->select('song_id, user_id')
                    ->from(Phpfox::getT('music_song'))
                    ->where('album_id = ' . (int) $aAlbum['album_id'])
                    ->execute('getSlaveRows');

            if (count($aSongs))
            {
                foreach ($aSongs as $aSong)
                {
                    $this->database()->update(Phpfox::getT('music_song'), array(
                        'privacy' => (isset($aVals['privacy']) ? $aVals['privacy'] : '0'),
                        'privacy_comment' => (isset($aVals['privacy_comment']) ? $aVals['privacy_comment'] : '0'),
                            ), 'song_id = ' . $aSong['song_id']
                    );

                    (Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->update('music_album', $aSong['song_id'], $aVals['privacy'], $aVals['privacy_comment'], 0, $aSong['user_id']) : null);

                    if (Phpfox::isModule('privacy'))
                    {
                        if ($aVals['privacy'] == '4')
                        {
                            Phpfox::getService('privacy.process')->update('music_song', $aSong['song_id'], (isset($aVals['privacy_list']) ? $aVals['privacy_list'] : array()));
                        }
                        else
                        {
                            Phpfox::getService('privacy.process')->delete('music_song', $aSong['song_id']);
                        }
                    }
                }
            }

            if (Phpfox::isModule('privacy'))
            {
                if ($aVals['privacy'] == '4')
                {
                    Phpfox::getService('privacy.process')->update('music_album', $iId, (isset($aVals['privacy_list']) ? $aVals['privacy_list'] : array()));
                }
                else
                {
                    Phpfox::getService('privacy.process')->delete('music_album', $iId);
                }
            }

            /**
             * Check the upload file image.
             */
            if (!empty($_FILES['image']['name']))
            {
                /**
                 * @var array
                 */
                $aImage = Phpfox::getLib('file')->load('image', array(
                    'jpg',
                    'gif',
                    'png'
                        )
                );

                if ($aImage === false)
                {
                    return false;
                }

                $oImage = Phpfox::getLib('image');
                $oFile = Phpfox::getLib('file');
                /**
                 * @var string
                 */
                $sFileName = Phpfox::getLib('file')->upload('image', Phpfox::getParam('music.dir_image'), $iId);
                /**
                 * @var int
                 */
                $iFileSizes = filesize(Phpfox::getParam('music.dir_image') . sprintf($sFileName, ''));

                foreach ($this->_aPhotoSizes as $iSize)
                {
                    $oImage->createThumbnail(Phpfox::getParam('music.dir_image') . sprintf($sFileName, ''), Phpfox::getParam('music.dir_image') . sprintf($sFileName, '_' . $iSize), $iSize, $iSize);
                    $oImage->createThumbnail(Phpfox::getParam('music.dir_image') . sprintf($sFileName, ''), Phpfox::getParam('music.dir_image') . sprintf($sFileName, '_' . $iSize . '_square'), $iSize, $iSize, false);

                    $iFileSizes += filesize(Phpfox::getParam('music.dir_image') . sprintf($sFileName, '_' . $iSize));
                }

                $this->database()->update(Phpfox::getT('music_album'), array('image_path' => $sFileName, 'server_id' => Phpfox::getLib('request')->getServer('PHPFOX_SERVER_ID')), 'album_id = ' . $iId);

                // Update user space usage
                Phpfox::getService('user.space')->update($aAlbum['user_id'], 'music_image', $iFileSizes);
            }

            return true;
        }

        return Phpfox_Error::set(Phpfox::getPhrase('music.unable_to_edit_this_album'));
    }

    /**
     * @see Ban_Service_Ban
     * @param array $aVals
     * Input data:
     * + name: string, required.
     * + year: string, required.
     * + text: string, optional.
     * + privacy: int, optional.
     * + privacy_comment: int, optional.
     * + privacy_list: string, optional, ex: "5,4,9".
     * + image: file upload.
     * 
     * @return boolean
     */
    private function add($aVals)
    {
        if (!empty($_FILES['image']['name']))
        {
            $aImage = Phpfox::getLib('file')->load('image', array(
                'jpg',
                'gif',
                'png'
                    )
            );

            if ($aImage === false)
            {
                return false;
            }
        }

        if (empty($aVals['privacy']))
        {
            $aVals['privacy'] = 0;
        }

        if (empty($aVals['privacy_comment']))
        {
            $aVals['privacy_comment'] = 0;
        }

        if (!Phpfox::getService('mfox.ban')->checkAutomaticBan($aVals['name'] . ' ' . $aVals['text']))
        {
            return false;
        }
        /**
         * @var int
         */
        $iId = $this->database()->insert(Phpfox::getT('music_album'), array(
            'view_id' => 0,
            'privacy' => (isset($aVals['privacy']) ? $aVals['privacy'] : '0'),
            'privacy_comment' => (isset($aVals['privacy_comment']) ? $aVals['privacy_comment'] : '0'),
            'user_id' => Phpfox::getUserId(),
            'name' => $this->preParse()->clean($aVals['name'], 255),
            'year' => $aVals['year'],
            'time_stamp' => PHPFOX_TIME
                )
        );

        if (!$iId)
        {
            return false;
        }

        $this->database()->insert(Phpfox::getT('music_album_text'), array(
            'album_id' => $iId,
            'text' => (empty($aVals['text']) ? null : $this->preParse()->clean($aVals['text'])),
            'text_parsed' => (empty($aVals['text']) ? null : $this->preParse()->prepare($aVals['text']))
                )
        );

        if (isset($aImage))
        {
            $oImage = Phpfox::getLib('image');

            $sFileName = Phpfox::getLib('file')->upload('image', Phpfox::getParam('music.dir_image'), $iId);

            $iFileSizes = filesize(Phpfox::getParam('music.dir_image') . sprintf($sFileName, ''));

            foreach ($this->_aPhotoSizes as $iSize)
            {
                $oImage->createThumbnail(Phpfox::getParam('music.dir_image') . sprintf($sFileName, ''), Phpfox::getParam('music.dir_image') . sprintf($sFileName, '_' . $iSize), $iSize, $iSize);
                $oImage->createThumbnail(Phpfox::getParam('music.dir_image') . sprintf($sFileName, ''), Phpfox::getParam('music.dir_image') . sprintf($sFileName, '_' . $iSize . '_square'), $iSize, $iSize, false);

                $iFileSizes += filesize(Phpfox::getParam('music.dir_image') . sprintf($sFileName, '_' . $iSize));
            }

            $this->database()->update(Phpfox::getT('music_album'), array('image_path' => $sFileName, 'server_id' => Phpfox::getLib('request')->getServer('PHPFOX_SERVER_ID')), 'album_id = ' . $iId);

            // Update user space usage
            Phpfox::getService('user.space')->update(Phpfox::getUserId(), 'music_image', $iFileSizes);
        }

        if ($aVals['privacy'] == '4')
        {
            Phpfox::getService('privacy.process')->add('music_album', $iId, (isset($aVals['privacy_list']) ? $aVals['privacy_list'] : array()));
        }

        return $iId;
    }

    /**
     * Input data: 
     * + iAlbumId: int, required.
     * 
     * Output data:
     * + result: int.
     * + error_code: int.
     * + message: string.
     * 
     * @see Mobile - API phpFox/Api V3.0
     * @see album/delete
     * 
     * @param array $aData
     * @return array
     */
    public function delete($aData)
    {
        if (!Phpfox::getUserParam('music.can_access_music'))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => 'You don\'t have permission to access music module!');
        }
        /**
         * @var int
         */
        $iAlbumId = isset($aData['iAlbumId']) ? (int) $aData['iAlbumId'] : 0;
        /**
         * @var array
         */
        $aAlbum = Phpfox::getService('music.album')->getAlbum($iAlbumId);

        if (!isset($aAlbum['album_id']))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => 'Unable to find the album you want to delete!');
        }

        // Check privacy.
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('music_album', $aAlbum['album_id'], $aAlbum['user_id'], $aAlbum['privacy'], $aAlbum['is_friend'], true))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'));
        }

        if (Phpfox::getService('music.album.process')->delete($iAlbumId))
        {
            return array('result' => 1, 'error_code' => 0, 'message' => Phpfox::getPhrase('music.album_successfully_deleted'));
        }

        return array('result' => 0, 'error_code' => 1, 'message' => Phpfox_Error::get());
    }

    /**
     * Input data:
     * + iAlbumId: int, required.
     * 
     * Output data:
     * + result: int.
     * + error_code: int.
     * + message: string.
     * + iSongId: int.
     * + iUserId: int.
     * + iAlbumId: int.
     * + sTitle: string.
     * + iTotalPlay: int.
     * + sSongPath: strint.
     * + bIsFeatured: bool.
     * + sSongPath: string.
     * + iViewId: int.
     * + iServerId: int.
     * + iExplicit: int.
     * + sDuration: string.
     * + iTimeStamp: int.
     * + sTimeStamp: string.
     * + sAlbumUrl: string.
     * + sUsername: string.
     * + bIsOnProfile: bool.
     * + iProfileUserId: int.
     * + iProfilePageId: int.
     * + iUserServerId: int.
     * + sFullname: string.
     * + iGender: int.
     * + sUserImage: string.
     * + bIsInvisible: bool.
     * + iUserGroupId: int.
     * + iLanguageId: int.
     * 
     * @see Mobile - API phpFox/Api V3.0
     * @see album/list_songs
     * 
     * @param array $aData
     * @return array
     */
    public function list_songs($aData)
    {
        if (!Phpfox::getUserParam('music.can_access_music'))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => 'You don\'t have permission to access music module!');
        }
        /**
         * @var int
         */
        $iAlbumId = isset($aData['iAlbumId']) ? (int) $aData['iAlbumId'] : 0;
        /**
         * @var array
         */
        $aAlbum = Phpfox::getService('music.album')->getAlbum($iAlbumId);
        if (!isset($aAlbum['album_id']))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => 'Unable to find the album you want to get songs!');
        }
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('music_album', $aAlbum['album_id'], $aAlbum['user_id'], $aAlbum['privacy'], $aAlbum['is_friend'], true))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'));
        }
        /**
         * @var array
         */
        $aSongs = Phpfox::getService('music.album')->getTracks($aAlbum['user_id'], $aAlbum['album_id'], true);
        
        // Update play time for song and album.
        if (isset($aSongs[0]))
        {
            Phpfox::getService('music.process')->play($aSongs[0]['song_id']);
        }
        
        /**
         * @var array
         */
        $aResult = array();
        foreach ($aSongs as $aSong)
        {
            $aResult[] = array(
                'iSongId' => $aSong['song_id'],
                'iUserId' => $aSong['user_id'],
                'iAlbumId' => $aSong['album_id'],
                'sTitle' => $aSong['title'],
                'iTotalPlay' => $aSong['total_play'],
                'sSongPath' => $aSong['song_path'],
                'bIsFeatured' => (bool) $aSong['is_featured'],
                'sSongPath' => $aSong['song_path'],
                'iViewId' => $aSong['view_id'],
                'iServerId' => $aSong['server_id'],
                'iExplicit' => $aSong['explicit'],
                'sDuration' => $aSong['duration'],
                'iTimeStamp' => $aSong['time_stamp'],
                'sTimeStamp' => date('l, F j, o', (int) $aSong['time_stamp']) . ' at ' . date('h:i a', (int) $aSong['time_stamp']),
                'sAlbumUrl' => $aSong['album_url'],
                'sUsername' => $aSong['user_name'],
                'bIsOnProfile' => isset($aSong['is_on_profile']) ? true : false,
                'iProfileUserId' => isset($aSong['profile_user_id']) ? (int) $aSong['profile_user_id'] : 0,
                'iProfilePageId' => $aSong['profile_page_id'],
                'iUserServerId' => $aSong['user_server_id'],
                'sFullname' => $aSong['full_name'],
                'iGender' => $aSong['gender'],
                'sUserImage' => Phpfox::getLib('image.helper')->display(array(
                    'server_id' => $aSong['user_server_id'],
                    'path' => 'core.url_user',
                    'file' => $aSong['user_image'],
                    'suffix' => '_50_square',
                    'return_url' => true
                        )
                ),
                'bIsInvisible' => (bool) $aSong['is_invisible'],
                'iUserGroupId' => $aSong['user_group_id'],
                'iLanguageId' => isset($aSong['language_id']) ? $aSong['language_id'] : 0
            );
        }
        return $aResult;
    }

    /**
     * Input data:
     * + iAlbumId: int, required.
     * 
     * Output data:
     * + result: int.
     * + error_code: int.
     * + message: string.
     * 
     * @param int $iAlbumId
     * @return array
     */
    public function checkPrivacyCommentOnMusicAlbum($iAlbumId)
    {
        if (!Phpfox::getUserParam('music.can_access_music'))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => 'You don\'t have permission to access music module!');
        }
        /**
         * @var array
         */
        $aAlbum = Phpfox::getService('music.album')->getAlbum($iAlbumId);

        if (!isset($aAlbum['album_id']))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => 'Unable to find the album you want to get songs!');
        }

        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('music_album', $aAlbum['album_id'], $aAlbum['user_id'], $aAlbum['privacy'], $aAlbum['is_friend'], true))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'));
        }

        if (!Phpfox::getService('mfox.comment')->checkCanPostCommentOnItem($aAlbum))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => 'You don\'t have permission to post comment on this item!');
        }

        return null;
    }

    /**
     * Input data:
     * + iAlbumId: int, required.
     * 
     * Output data:
     * + result: int.
     * + error_code: int.
     * + message: string.
     * 
     * @param int $iAlbumId
     * @return array
     */
    public function checkPrivacyOnMusicAlbum($iAlbumId)
    {
        if (!Phpfox::getUserParam('music.can_access_music'))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => 'You don\'t have permission to access music module!');
        }
        /**
         * @var array
         */
        $aAlbum = Phpfox::getService('music.album')->getAlbum($iAlbumId);
        if (!isset($aAlbum['album_id']))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => 'Unable to find the album you want to get songs!');
        }
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('music_album', $aAlbum['album_id'], $aAlbum['user_id'], $aAlbum['privacy'], $aAlbum['is_friend'], true))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'));
        }
        return null;
    }
    
    /**
     * Input data:
     * + iAlbumId: int, required.
     * + lastCommentIdViewed: int, optional.
     * + amountOfComment: int, optional.
     * 
     * Output data:
     * + result: int.
     * + error_code: int.
     * + message: string.
     * + sImage: string.
     * + iTimestamp: int.
     * + sTime: string.
     * + sTimeConverted: string.
     * 
     * @see Mobile - API phpFox/Api V3.0
     * @see album/list_comment
     * 
     * @param array $aData
     * @return array
     */
    public function list_comment($aData)
    {
        if (!Phpfox::getUserParam('music.can_access_music'))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => 'You don\'t have permission to access music module!');
        }
        /**
         * @var int
         */
        $iAlbumId = isset($aData['iAlbumId']) ? (int) $aData['iAlbumId'] : 0;
        /**
         * @var array
         */
        $aAlbum = Phpfox::getService('music.album')->getAlbum($iAlbumId);
        if (!isset($aAlbum['album_id']))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => 'Unable to find the album you want to get songs!');
        }
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('music_album', $aAlbum['album_id'], $aAlbum['user_id'], $aAlbum['privacy'], $aAlbum['is_friend'], true))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'));
        }
        $aData['sType'] = 'music_album';
        $aData['iItemId'] = $iAlbumId;
        return Phpfox::getService('mfox.comment')->listallcomments($aData);
    }

    /**
     * Input data:
     * + sAction: string, optional.
     * + iLastTimeStamp: int, optional.
     * + sView: string, optional.
     * + iAmountOfAlbum: int, optional.
     * + sSearch: string, optional.
     * 
     * Output data:
     * + bIsLiked: bool.
     * + iAlbumId: int.
     * + iViewId: int.
     * + iPrivacy: int.
     * + iPrivacyComment: int.
     * + bIsFeatured: bool.
     * + bIsSponsor: bool.
     * + iUserId: int.
     * + sName: string.
     * + iYear: int.
     * + sImagePath: string.
     * + iTotalTrack: int.
     * + iTotalPlay: int.
     * + iTotalComment: int.
     * + iTotalLike: int.
     * + iTotalDislike: int.
     * + iTotalScore: int.
     * + iTotalRating: int.
     * + iTimeStamp: int.
     * + sTimeStamp: string.
     * + sFullTimeStamp: string.
     * + sModuleId: string.
     * + iItemId: int.
     * + iProfilePageId: int.
     * + iUserServerId: int.
     * + sUsername: string.
     * + sFullname: string.
     * + iGender: int.
     * + sUserImage: string.
     * + bIsInvisible: bool.
     * + iUserGroupId: int.
     * + iLanguageId: int.
     * 
     * @see Mobile - API phpFox/Api V3.0
     * @see album/search
     * 
     * @param array $aData
     * @return array
     */
    public function search($aData)
    {
        return $this->getAlbums($aData);
    }

    /**
     * Input data:
     * + sAction: string, optional.
     * + iLastTimeStamp: int, optional.
     * + sView: string, optional.
     * + iAmountOfAlbum: int, optional.
     * + sSearch: string, optional.
     * 
     * Output data:
     * + bIsLiked: bool.
     * + iAlbumId: int.
     * + iViewId: int.
     * + iPrivacy: int.
     * + iPrivacyComment: int.
     * + bIsFeatured: bool.
     * + bIsSponsor: bool.
     * + iUserId: int.
     * + sName: string.
     * + iYear: int.
     * + sImagePath: string.
     * + iTotalTrack: int.
     * + iTotalPlay: int.
     * + iTotalComment: int.
     * + iTotalLike: int.
     * + iTotalDislike: int.
     * + iTotalScore: int.
     * + iTotalRating: int.
     * + iTimeStamp: int.
     * + sTimeStamp: string.
     * + sFullTimeStamp: string.
     * + sModuleId: string.
     * + iItemId: int.
     * + iProfilePageId: int.
     * + iUserServerId: int.
     * + sUsername: string.
     * + sFullname: string.
     * + iGender: int.
     * + sUserImage: string.
     * + bIsInvisible: bool.
     * + iUserGroupId: int.
     * + iLanguageId: int.
     * 
     * @param array $aData
     * @return array
     */
    private function getAlbums($aData)
    {
        /**
         * @var string
         */
        $sAction = (isset($aData['sAction']) && $aData['sAction'] == 'new') ? 'new' : 'more';
        /**
         * @var int
         */
        $iLastTimeStamp = isset($aData['iLastTimeStamp']) ? (int) $aData['iLastTimeStamp'] : 0;
        /**
         * @var string
         */
        $sView = isset($aData['sView']) ? $aData['sView'] : '';
        /**
         * @var int
         */
        $iAmountOfAlbum = isset($aData['iAmountOfAlbum']) ? (int) $aData['iAmountOfAlbum'] : 10;
        /**
         * @var string
         */
        $sSearch = isset($aData['sSearch']) ? $aData['sSearch'] : '';
        /**
         * @var array
         */
        $aCond = array();

        if (!empty($sSearch))
        {
            $aCond[] = 'm.name LIKE "' . Phpfox::getLib('parse.input')->clean('%' . $sSearch . '%') . '"';
        }
        switch ($sView) {
            case 'my':
                $aCond[] = 'm.user_id = ' . Phpfox::getUserId();
                break;

            case 'all':
            default:
                $aCond[] = 'm.view_id = 0';
                $aCond[] = 'm.privacy IN(0)';
                break;
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
        $this->database()
                ->select('COUNT(*)')
                ->from(Phpfox::getT('music_album'), 'm');
        /**
         * @var int
         */
        $iCount = $this->database()
                ->where(implode(' AND ', $aCond))
                ->limit(1)
                ->execute('getField');
        if ($iCount == 0)
        {
            return array();
        }
        /**
         * @var array
         */
        $aAlbums = $this->database()
                ->select('lik.like_id AS is_liked, m.*, u.user_id, u.profile_page_id, u.server_id AS user_server_id, u.user_name, u.full_name, u.gender, u.user_image, u.is_invisible, u.user_group_id, u.language_id')
                ->from(Phpfox::getT('music_album'), 'm')
                ->leftJoin(Phpfox::getT('like'), 'lik', 'lik.type_id = \'music_album\' AND lik.item_id = m.album_id AND lik.user_id = ' . Phpfox::getUserId())
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = m.user_id')
                ->where(implode(' AND ', $aCond))
                ->order('m.time_stamp DESC')
                ->limit(0, $iAmountOfAlbum, $iCount)
                ->execute('getRows');
        /**
         * @var array
         */
        $aResult = array();
        foreach ($aAlbums as $aAlbum)
        {
            $aResult[] = array(
                'bIsLiked' => isset($aAlbum['is_liked']) ? (bool) $aAlbum['is_liked'] : false,
                'iAlbumId' => $aAlbum['album_id'],
                'iViewId' => $aAlbum['view_id'],
                'iPrivacy' => $aAlbum['privacy'],
                'iPrivacyComment' => $aAlbum['privacy_comment'],
                'bIsFeatured' => (bool) $aAlbum['is_featured'],
                'bIsSponsor' => (bool) $aAlbum['is_sponsor'],
                'iUserId' => $aAlbum['user_id'],
                'sName' => $aAlbum['name'],
                'iYear' => $aAlbum['year'],
                'sImagePath' => Phpfox::getLib('image.helper')->display(array(
                    'server_id' => $aAlbum['server_id'],
                    'path' => 'music.url_image',
                    'file' => $aAlbum['image_path'],
                    'suffix' => '_50_square',
                    'return_url' => true
                        )
                ),
                'iTotalTrack' => $aAlbum['total_track'],
                'iTotalPlay' => $aAlbum['total_play'],
                'iTotalComment' => $aAlbum['total_comment'],
                'iTotalLike' => $aAlbum['total_like'],
                'iTotalDislike' => $aAlbum['total_dislike'],
                'iTotalScore' => $aAlbum['total_score'],
                'iTotalRating' => $aAlbum['total_rating'],
                'iTimeStamp' => $aAlbum['time_stamp'],
                'sTimeStamp' => date('l, F j', $aAlbum['time_stamp']),
                'sFullTimeStamp' => date('l, F j', $aAlbum['time_stamp']) . ' at ' . date('g:i a', $aAlbum['time_stamp']),
                'sModuleId' => isset($aAlbum['module_id']) ? $aAlbum['module_id'] : 0,
                'iItemId' => $aAlbum['item_id'],
                'iProfilePageId' => $aAlbum['profile_page_id'],
                'iUserServerId' => $aAlbum['user_server_id'],
                'sUsername' => $aAlbum['user_name'],
                'sFullname' => $aAlbum['full_name'],
                'iGender' => $aAlbum['gender'],
                'sUserImage' => Phpfox::getLib('image.helper')->display(array(
                    'server_id' => $aAlbum['user_server_id'],
                    'path' => 'core.url_user',
                    'file' => $aAlbum['user_image'],
                    'suffix' => '_50_square',
                    'return_url' => true
                        )
                ),
                'bIsInvisible' => $aAlbum['is_invisible'],
                'iUserGroupId' => $aAlbum['user_group_id'],
                'iLanguageId' => isset($aAlbum['language_id']) ? $aAlbum['language_id'] : 0
            );
        }
        return $aResult;
    }

    /**
     * Using for notification.
     * @param array $aNotification
     * @return array|bool
     */
    public function doAlbumGetNotificationAlbum_Like($aNotification)
    {
        /**
         * @var array
         */
        $aRow = $this->database()->select('ms.album_id, ms.name, ms.user_id, u.gender, u.full_name')
                ->from(Phpfox::getT('music_album'), 'ms')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = ms.user_id')
                ->where('ms.album_id = ' . (int) $aNotification['item_id'])
                ->execute('getSlaveRow');
        if (!isset($aRow['album_id']))
        {
            return false;
        }
        /**
         * @var string
         */
        $sPhrase = '';
        if ($aNotification['user_id'] == $aRow['user_id'])
        {
            $sPhrase = Phpfox::getPhrase('music.user_name_liked_gender_own_album_title', array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'title' => Phpfox::getLib('parse.output')->shorten($aRow['name'], (Phpfox::isModule('notification') ? Phpfox::getParam('notification.total_notification_title_length') : $this->_iFallbackLength), '...')));
        }
        elseif ($aRow['user_id'] == Phpfox::getUserId())
        {
            $sPhrase = Phpfox::getPhrase('music.user_name_liked_your_album_title', array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'title' => Phpfox::getLib('parse.output')->shorten($aRow['name'], (Phpfox::isModule('notification') ? Phpfox::getParam('notification.total_notification_title_length') : $this->_iFallbackLength), '...')));
        }
        else
        {
            $sPhrase = Phpfox::getPhrase('music.user_name_liked_span_class_drop_data_user_full_name_s_span_album_title', array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'full_name' => $aRow['full_name'], 'title' => Phpfox::getLib('parse.output')->shorten($aRow['name'], (Phpfox::isModule('notification') ? Phpfox::getParam('notification.total_notification_title_length') : $this->_iFallbackLength), '...')));
        }
        return array(
            'link' => array(
                'iAlbumId' => $aRow['album_id'],
                'sAlbumTitle' => $aRow['name']
            ),
            'message' => strip_tags($sPhrase),
            'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog'),
            'sModule' => 'music.album',
            'sMethod' => 'getNotificationAlbum_Like'
        );
    }
    /**
     * Using for notification.
     * @param array $aNotification
     * @return array
     */
    public function doMusicAlbumGetCommentNotificationAlbum($aNotification)
	{
        /**
         * @var array
         */
		$aRow = $this->database()
                ->select('l.album_id, l.name, u.user_id, u.gender, u.user_name, u.full_name')	
                ->from(Phpfox::getT('music_album'), 'l')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = l.user_id')
                ->where('l.album_id = ' . (int) $aNotification['item_id'])
                ->execute('getSlaveRow');
        /**
         * @var string
         */
		$sPhrase = '';
		if ($aNotification['user_id'] == $aRow['user_id'] && !isset($aNotification['extra_users']))
		{ 
			$sPhrase = Phpfox::getPhrase('music.user_name_commented_on_gender_album_title',array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'title' => Phpfox::getLib('parse.output')->shorten($aRow['name'], (Phpfox::isModule('notification') ? Phpfox::getParam('notification.total_notification_title_length') : $this->_iFallbackLength ), '...')));			
		}
		elseif ($aRow['user_id'] == Phpfox::getUserId())
		{
			$sPhrase = Phpfox::getPhrase('music.user_name_commented_on_your_album_title',array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'title' => Phpfox::getLib('parse.output')->shorten($aRow['name'], (Phpfox::isModule('notification') ? Phpfox::getParam('notification.total_notification_title_length') : $this->_iFallbackLength ), '...')));
		}
		else 
		{
			$sPhrase = Phpfox::getPhrase('music.user_name_commented_on_span_class_drop_data_user_full_name_s_album_title',array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'full_name' => $aRow['full_name'], 'title' => Phpfox::getLib('parse.output')->shorten($aRow['name'], (Phpfox::isModule('notification') ? Phpfox::getParam('notification.total_notification_title_length') : $this->_iFallbackLength ), '...')));			
		}
		return array(
			'link' => array(
                'iAlbumId' => $aRow['album_id'],
                'sName' => $aRow['name']
            ),
			'message' => strip_tags($sPhrase),
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog'),
            'sModule' => 'music.album',
            'sMethod' => 'getCommentNotificationAlbum'
		);
	}
    
    public function doMusicAlbumGetRedirectCommentAlbum($iId)
	{
		return $this->getFeedRedirectAlbum($iId);
	}
    /**
     * Get feed redirect album.
     * @param int $iId
     * @return boolean|array
     */
    public function getFeedRedirectAlbum($iId)
	{
        /**
         * @var array
         */
		$aRow = $this->database()->select('m.album_id, name')
			->from(Phpfox::getT('music_album'), 'm')
			->where('m.album_id = ' . (int) $iId)
			->execute('getSlaveRow');
		if (!isset($aRow['album_id']))
		{
			return false;
		}
		return array(
            'sModule' => 'music.album',
            'iAlbumId' => $aRow['album_id'],
            'sTitle' => $aRow['name'],
            'sCommentType' => 'music_album'
        );
	}
    /**
     * Input data:
     * + sAction: string, optional.
     * + iLastTimeStamp: int, optional.
     * + sView: string, optional.
     * + iAmountOfAlbum: int, optional.
     * + sSearch: string, optional.
     * 
     * Output data:
     * + bIsLiked: bool.
     * + iAlbumId: int.
     * + iViewId: int.
     * + iPrivacy: int.
     * + iPrivacyComment: int.
     * + bIsFeatured: bool.
     * + bIsSponsor: bool.
     * + iUserId: int.
     * + sName: string.
     * + iYear: int.
     * + sImagePath: string.
     * + iTotalTrack: int.
     * + iTotalPlay: int.
     * + iTotalComment: int.
     * + iTotalLike: int.
     * + iTotalDislike: int.
     * + iTotalScore: int.
     * + iTotalRating: int.
     * + iTimeStamp: int.
     * + sTimeStamp: string.
     * + sFullTimeStamp: string.
     * + sModuleId: string.
     * + iItemId: int.
     * + iProfilePageId: int.
     * + iUserServerId: int.
     * + sUsername: string.
     * + sFullname: string.
     * + iGender: int.
     * + sUserImage: string.
     * + bIsInvisible: bool.
     * + iUserGroupId: int.
     * + iLanguageId: int.
     * 
     * @see Mobile - API phpFox/Api V3.0
     * @see album/filter
     * 
     * @param array $aData
     * @return array
     */
    public function filter($aData)
    {
        return $this->getAlbums($aData);
    }
    /**
     * Push Cloud Message for music album.
     * @param int $iAlbumId
     */
    public function doPushCloudMessageMusicAlbum($aData)
    {
        /**
         * @var int
         */
        $iAlbumId = isset($aData['iItemId']) ? (int) $aData['iItemId'] : 0;
        /**
         * @var array
         */
        $aAlbum = Phpfox::getService('music.album')->getAlbum($iAlbumId);
        
        if (isset($aAlbum['user_id']) && $aAlbum['user_id'] != Phpfox::getUserId())
        {
            /**
             * @var int
             */
            $iPushId = Phpfox::getService('mfox.push')->savePush($aData, $aAlbum['user_id']);
            
            Phpfox::getService('mfox.cloudmessage') -> send(array('message' => 'notification', 'iPushId' => $iPushId), $aAlbum['user_id']);
        }
    }
    
    /**
     * Input data:
     * + iAlbumId: int, required.
     * 
     * Output data:
	 * + bIsLiked: bool.
	 * + bIsFriend: bool.
	 * + iAlbumId: int.
	 * + iViewId: int.
	 * + iPrivacy: int.
	 * + iPrivacyComment: int.
	 * + bIsFeatured: bool.
	 * + bIsSponsor: bool.
	 * + iUserId: int.
	 * + sName: string.
	 * + iYear: int.
	 * + sImagePath: string.
	 * + iTotalTrack: int.
	 * + iTotalPlay: int.
	 * + iTotalComment: int.
	 * + iTotalLike: int.
	 * + iTotalDislike: int.
	 * + fTotalScore: float.
	 * + iTotalRating: int.
	 * + iTimeStamp: int.
	 * + sTimeStamp: string.
	 * + sFullTimeStamp: string.
	 * + sModuleId: string.
	 * + iItemId: int.
	 * + sDescription: string.
	 * + sUserName: string.
	 * + bHasRated: bool.
	 * + iProfilePageId: int.
	 * + sUserImage: string.
	 * + sFullName: string.
	 * + iGender: int.
	 * + bIsInvisible: bool.
	 * + iUserGroupId: int.
	 * + iLanguageId: int.
	 * + bCanPostComment: bool.
     * 
     * @see Mobile - API phpFox/Api V3.0
     * @see album/details
     * 
     * @param array $aData
     * @return array
     */
    public function details($aData)
    {
        /**
         * @var int
         */
        $iAlbumId = isset($aData['iAlbumId']) ? (int) $aData['iAlbumId'] : 0;
        
        if (!Phpfox::getUserParam('music.can_access_music'))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => 'You don\'t have permission to access music module!');
        }
		
		if (Phpfox::isUser() && Phpfox::isModule('notification'))
		{
			Phpfox::getService('notification.process')->delete('comment_music_album', $iAlbumId, Phpfox::getUserId());
			Phpfox::getService('notification.process')->delete('music_song_album', $iAlbumId, Phpfox::getUserId());
			Phpfox::getService('notification.process')->delete('music_album_like', $iAlbumId, Phpfox::getUserId());
		}			
		/**
         * @var array
         */
		$aAlbum = Phpfox::getService('music.album')->getAlbum($iAlbumId);
		
		if (!isset($aAlbum['album_id']))
		{
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('music.unable_to_find_the_album_you_are_looking_for'));
		}
        
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('music_album', $aAlbum['album_id'], $aAlbum['user_id'], $aAlbum['privacy'], $aAlbum['is_friend'], true))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'));
        }
        
        return array(
            'bIsLiked' => $aAlbum['is_liked'],
            'bIsFriend' => $aAlbum['is_friend'],
            'iAlbumId' => $aAlbum['album_id'],
            'iViewId' => $aAlbum['view_id'],
            'iPrivacy' => $aAlbum['privacy'],
            'iPrivacyComment' => $aAlbum['privacy_comment'],
            'bIsFeatured' => (bool) $aAlbum['is_featured'],
            'bIsSponsor' => (bool) $aAlbum['is_sponsor'],
            'iUserId' => $aAlbum['user_id'],
            'sAlbumName' => $aAlbum['name'],
            'iYear' => $aAlbum['year'],
            'sImagePath' => Phpfox::getLib('image.helper')->display(array(
                    'server_id' => $aAlbum['server_id'],
                    'path' => 'music.url_image',
                    'file' => $aAlbum['image_path'],
                    'suffix' => '_200',
                    'return_url' => true
                )
            ),
            'iTotalTrack' => $aAlbum['total_track'],
            'iTotalPlay' => $aAlbum['total_play'],
            'iTotalComment' => $aAlbum['total_comment'],
            'iTotalLike' => $aAlbum['total_like'],
            'iTotalDislike' => $aAlbum['total_dislike'],
            'fTotalScore' => $aAlbum['total_score'],
            'iTotalRating' => $aAlbum['total_rating'],
            'iTimeStamp' => $aAlbum['time_stamp'],
            'sTimeStamp' => date('l, F j', $aAlbum['time_stamp']),
            'sFullTimeStamp' => date('l, F j', $aAlbum['time_stamp']) . ' at ' . date('g:i a', $aAlbum['time_stamp']),
            'sModuleId' => $aAlbum['module_id'],
            'iItemId' => $aAlbum['item_id'],
            'sDescription' => $aAlbum['text'],
            'sUserName' => $aAlbum['user_name'],
            'bHasRated' => $aAlbum['has_rated'],
            'iProfilePageId' => $aAlbum['profile_page_id'],
            'sUserImage' => Phpfox::getLib('image.helper')->display(array(
                    'server_id' => $aAlbum['user_server_id'],
                    'path' => 'core.url_user',
                    'file' => $aAlbum['user_image'],
                    'suffix' => '_20_square',
                    'return_url' => true
                )
            ),
            'sFullname' => $aAlbum['full_name'],
            'iGender' => $aAlbum['gender'],
            'bIsInvisible' => $aAlbum['is_invisible'],
            'iUserGroupId' => $aAlbum['user_group_id'],
            'iLanguageId' => $aAlbum['language_id'],
            'bCanPostComment' => Phpfox::getService('mfox.comment')->checkCanPostCommentOnItem($aAlbum),
            'sTitle' => ''
        );
    }
}

