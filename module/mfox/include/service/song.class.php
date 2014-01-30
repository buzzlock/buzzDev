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
 * @link Mfox Api v3.0
 */
class Mfox_Service_Song extends Phpfox_Service {

    /**
     * Input data:
     * + sCallbackModule: string, optional.
     * + iCallbackItemId: int, optional.
     * + iAlbumId: int, optional.
     * + sNewAlbumTitle: string, optional.
     * // In song view.
     * + sTitle: string, required.
     * + iPrivacy: int, optional.
     * + iPrivacyComment: int, optional.
     * + sPrivacyList: string, optional, ex: "5,4,9".
     * // On wall only.
     * + sMusicTitle: string, required.
     * + sStatusInfo: string, optional.
     * + iGenreId: int, optional.
     * + iExplicit: int, optional.
     * + bIsProfile: bool, optional.
     * + mp3: mp3 file, required.
     * 
     * Output data:
     * + result: int.
     * + error_code: int.
     * + message: string.
     * + iSongId: int.
     * + sSongTitle: string.
     * 
     * @see Mobile - API phpFox/Api V3.0
     * @see song/create
     * 
     * @param array $aData
     * @return array
     */
    public function create($aData)
    {
        if (!Phpfox::getUserParam('music.can_upload_music_public'))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => 'You don\'t have permission to upload music public!');
        }

        /**
         * @var array
         */
        $aVals = array(
            'callback_module' => isset($aData['sCallbackModule']) ? $aData['sCallbackModule'] : '',
            'callback_item_id' => isset($aData['iCallbackItemId']) ? (int) $aData['iCallbackItemId'] : 0,
            'album_id' => isset($aData['iAlbumId']) ? (int) $aData['iAlbumId'] : 0,
            'new_album_title' => isset($aData['sNewAlbumTitle']) ? trim($aData['sNewAlbumTitle']) : '',
            'title' => isset($aData['sTitle']) ? $aData['sTitle'] : '',
            'privacy' => isset($aData['iPrivacy']) ? (int) $aData['iPrivacy'] : 0,
            'privacy_comment' => isset($aData['iPrivacyComment']) ? (int) $aData['iPrivacyComment'] : 0,
            'privacy_list' => isset($aData['sPrivacyList']) ? explode(',', $aData['sPrivacyList']) : array(),
            // Avaliable on wall only.
            'music_title' => isset($aData['sMusicTitle']) && !empty($aData['sMusicTitle']) ? $aData['sMusicTitle'] : null,
            'status_info' => isset($aData['sStatusInfo']) ? $aData['sStatusInfo'] : '',
            'genre_id' => isset($aData['iGenreId']) ? (int) $aData['iGenreId'] : 0,
            'explicit' => isset($aData['iExplicit']) ? (int) $aData['iExplicit'] : null,
            'is_profile' => (isset($aData['bIsProfile']) && $aData['bIsProfile'] == 'true') ? 'yes' : 'no'
        );
        /**
         * @var array
         */
        $aValidation = array(
            'title' => Phpfox::getPhrase('music.provide_a_name_for_this_song')
        );
        /**
         * @var object
         */
        $oValidator = Phpfox::getLib('validator')->set(array(
            'sFormName' => 'js_music_form',
            'aParams' => $aValidation
                )
        );

        if (isset($aVals['music_title']))
        {
            $aVals['title'] = $aVals['music_title'];
        }

        if ($oValidator->isValid($aVals))
        {
            if (($aSong = $this->upload($aVals, (isset($aVals['album_id']) ? (int) $aVals['album_id'] : 0))))
            {
                return array(
                    'result' => 1,
                    'error_code' => 0,
                    'message' => Phpfox::getPhrase('music.song_successfully_uploaded'),
                    'iSongId' => $aSong['song_id'],
                    'sSongTitle' => $aSong['title']
                );
            }
            else
            {
                return array('result' => 0, 'error_code' => 1, 'message' => Phpfox_Error::get());
            }
        }
        else
        {
            Phpfox_Error::set('Title or music title variable are empty!');
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox_Error::get());
        }
    }

    /**
     * Input data:
     * + mp3: mp3 file, required. In POST method.
     * + aVals: array of data:
     *   - title: string, required.
     *   - privacy: int, required.
     *   - privacy_comment: int, required.
     *   - privacy_list: array, optional.
     *   - new_album_title: string, optional.
     *   - genre_id: int, optional.
     *   - callback_module: string, optional.
     *   - callback_item_id: int, optional.
     *   - status_info: string, optional.
     *   - explicit: bool, optional.
     * 
     * @see Phpfox_File
     * @param array $aVals
     * @param int $iAlbumId
     * @return boolean
     */
    private function upload($aVals, $iAlbumId = 0)
    {
        if (!isset($_FILES['mp3']))
        {
            return Phpfox_Error::set(Phpfox::getPhrase('music.select_an_mp3'));
        }
        /**
         * @var array
         */
        $aSong = Phpfox::getLib('file')->load('mp3', 'mp3', Phpfox::getUserParam('music.music_max_file_size'));
        if ($aSong === false)
        {
            return false;
        }
        if (empty($aVals['title']))
        {
            $aVals['title'] = $aSong['name'];
        }
        if (!isset($aVals['privacy']))
        {
            $aVals['privacy'] = 0;
        }
        if (!isset($aVals['privacy_comment']))
        {
            $aVals['privacy_comment'] = 0;
        }
        if ($iAlbumId > 0)
        {
            /**
             * @var array
             */
            $aAlbum = $this->database()->select('*')
                    ->from(Phpfox::getT('music_album'))
                    ->where('album_id = ' . (int) $iAlbumId)
                    ->execute('getSlaveRow');

            $aVals['privacy'] = $aAlbum['privacy'];
            $aVals['privacy_comment'] = $aAlbum['privacy_comment'];
        }

        if (!empty($aVals['new_album_title']))
        {
            $iAlbumId = $this->database()->insert(Phpfox::getT('music_album'), array(
                'user_id' => Phpfox::getUserId(),
                'name' => $this->preParse()->clean($aVals['new_album_title']),
                'privacy' => (isset($aVals['privacy']) ? $aVals['privacy'] : '0'),
                'privacy_comment' => (isset($aVals['privacy_comment']) ? $aVals['privacy_comment'] : '0'),
                'time_stamp' => PHPFOX_TIME,
                'module_id' => (isset($aVals['callback_module']) ? $aVals['callback_module'] : null),
                'item_id' => (isset($aVals['callback_item_id']) ? (int) $aVals['callback_item_id'] : '0')
                    )
            );

            $aAlbum = $this->database()->select('*')
                    ->from(Phpfox::getT('music_album'))
                    ->where('album_id = ' . (int) $iAlbumId)
                    ->execute('getSlaveRow');

            $this->database()->insert(Phpfox::getT('music_album_text'), array(
                'album_id' => $iAlbumId
                    )
            );

            if ($aVals['privacy'] == '4')
            {
                // Phpfox::getService('privacy.process')->add('music_album', $iAlbumId, (isset($aVals['privacy_list']) ? $aVals['privacy_list'] : array()));			
            }
        }

        if (!Phpfox::getService('ban')->checkAutomaticBan($aVals['title']))
        {
            return false;
        }
        /**
         * @var array
         */
        $aInsert = array(
            'view_id' => (Phpfox::getUserParam('music.music_song_approval') ? '1' : '0'),
            'privacy' => (isset($aVals['privacy']) ? $aVals['privacy'] : '0'),
            'privacy_comment' => (isset($aVals['privacy_comment']) ? $aVals['privacy_comment'] : '0'),
            'album_id' => $iAlbumId,
            'genre_id' => (isset($aVals['genre_id']) ? (int) $aVals['genre_id'] : '0'),
            'user_id' => Phpfox::getUserId(),
            'title' => Phpfox::getLib('parse.input')->clean($aVals['title'], 255),
            'description' => (isset($aVals['status_info']) ? Phpfox::getLib('parse.input')->clean($aVals['status_info'], 255) : null),
            'explicit' => ((isset($aVals['explicit']) && $aVals['explicit']) ? 1 : 0),
            'time_stamp' => PHPFOX_TIME,
            'module_id' => (isset($aVals['callback_module']) ? $aVals['callback_module'] : null),
            'item_id' => (isset($aVals['callback_item_id']) ? (int) $aVals['callback_item_id'] : '0')
        );
        /**
         * @var int
         */
        $iId = $this->database()->insert(Phpfox::getT('music_song'), $aInsert);
        if (!$iId)
        {
            return false;
        }
        /**
         * @var string
         */
        $sFileName = Phpfox::getLib('file')->upload('mp3', Phpfox::getParam('music.dir'), $iId);
        /**
         * @var string
         */
        $sDuration = null;
        if (file_exists(PHPFOX_DIR_LIB . 'getid3' . PHPFOX_DS . 'getid3' . PHPFOX_DS . 'getid3.php'))
        {
            // Temp. disable error reporting
            Phpfox_Error::skip(true);

            require_once(PHPFOX_DIR_LIB . 'getid3' . PHPFOX_DS . 'getid3' . PHPFOX_DS . 'getid3.php');
            /**
             * @var object getID3
             */
            $oGetId3 = new getID3;
            /**
             * @var array
             */
            $aMetaData = $oGetId3->analyze(Phpfox::getParam('music.dir') . sprintf($sFileName, ''));

            if (isset($aMetaData['playtime_string']))
            {
                $sDuration = $aMetaData['playtime_string'];
            }
        }

        $aInsert['song_id'] = $iId;
        $aInsert['duration'] = $sDuration;
        $aInsert['song_path'] = $sFileName;
        $aInsert['full_name'] = $sFileName;
        $aInsert['is_featured'] = 0;
        $aInsert['user_name'] = Phpfox::getUserBy('user_name');
        // Return back error reporting
        Phpfox_Error::skip(false);

        $this->database()->update(Phpfox::getT('music_song'), array('song_path' => $sFileName, 'server_id' => Phpfox::getLib('request')->getServer('PHPFOX_SERVER_ID'), 'duration' => $sDuration), 'song_id = ' . (int) $iId);

        // Update user space usage
        if (!Phpfox::getUserParam('music.music_song_approval'))
        {
            Phpfox::getService('user.space')->update(Phpfox::getUserId(), 'music', filesize(Phpfox::getParam('music.dir') . sprintf($sFileName, '')));
        }

        if ($aVals['privacy'] == '4')
        {
            Phpfox::getService('privacy.process')->add('music_song', $iId, (isset($aVals['privacy_list']) ? $aVals['privacy_list'] : array()));
        }
        /**
         * @var array
         */
        $aCallback = null;
        if (!empty($aVals['callback_module']) && Phpfox::hasCallback($aVals['callback_module'], 'uploadSong'))
        {
            $aCallback = Phpfox::callback($aVals['callback_module'] . '.uploadSong', $aVals['callback_item_id']);
        }
        if ($iAlbumId > 0)
        {
            if (!Phpfox::getUserParam('music.music_song_approval'))
            {
                $this->database()->updateCounter('music_album', 'total_track', 'album_id', $iAlbumId);
                (Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->callback($aCallback)->add('music_album', $iId, $aAlbum['privacy'], (isset($aAlbum['privacy_comment']) ? (int) $aAlbum['privacy_comment'] : 0), (isset($aVals['callback_item_id']) ? (int) $aVals['callback_item_id'] : '0')) : null);
            }
        }
        else
        {
            if (!Phpfox::getUserParam('music.music_song_approval'))
            {
                (Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->callback($aCallback)->add('music_song', $iId, $aVals['privacy'], (isset($aVals['privacy_comment']) ? (int) $aVals['privacy_comment'] : 0), (isset($aVals['callback_item_id']) ? (int) $aVals['callback_item_id'] : '0')) : null);
            }
        }
        if (!Phpfox::getUserParam('music.music_song_approval'))
        {
            Phpfox::getService('user.activity')->update(Phpfox::getUserId(), 'music_song');
        }
        return $aInsert;
    }

    /**
     * Input data: N/A.
     * 
     * Output data:
     * + iGenreId: int.
     * + sName: string.
     * 
     * @see Mobile - API phpFox/Api V3.0
     * @see song/genres
     * 
     * @param array $aData
     * @return array
     */
    public function genres($aData = array())
    {
        /**
         * @var array
         */
        $aGenres = Phpfox::getService('music.genre')->getList();
        /**
         * @var array
         */
        $aResult = array();
        foreach ($aGenres as $aGenre)
        {
            $aResult[] = array('iGenreId' => $aGenre['genre_id'], 'sName' => $aGenre['name']);
        }

        return $aResult;
    }

    /**
     * Input data:
     * + iSongId: int, required.
     * + sCallbackModule: string, optional, in page.
     * + iCallbackItemId: int, optional, in page.
     * + iAlbumId: int, optional.
     * + sNewAlbumTitle: string, optional.
     * + sTitle: string, required.
     * + iPrivacy: int, optional.
     * + iPrivacyComment: int, optional.
     * + sPrivacyList: string, optional.
     * + iGenreId: int, optional.
     * + iExplicit: int, optional.
     * 
     * Output data:
     * + result: int.
     * + error_code: int.
     * + message: string.
     * 
     * @see Mobile - API phpFox/Api V3.0
     * @see song/edit
     * 
     * @param array $aData
     * @return array
     */
    public function edit($aData)
    {
        if (!Phpfox::getUserParam('music.can_upload_music_public'))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => 'You don\'t have permission to upload music public!');
        }
        /**
         * @var int
         */
        $iSongId = isset($aData['iSongId']) ? (int) $aData['iSongId'] : 0;
        if ($iSongId < 1)
        {
            return array('result' => 0, 'error_code' => 1, 'message' => 'Song id is not valid!');
        }
        /**
         * @var array
         */
        $aEditSong = Phpfox::getService('music')->getForEdit($iSongId);
        if (!isset($aEditSong['song_id']))
        {
            Phpfox_Error::set('Song is not valid or has been deleted!');

            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox_Error::get());
        }
        // Check privacy for song.
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('music_song', $aEditSong['song_id'], $aEditSong['user_id'], $aEditSong['privacy'], $aEditSong['is_friend'], true))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'));
        }
        if ($aEditSong['module_id'] == 'pages')
        {
            Phpfox::getService('pages')->setIsInPage();
        }
        /**
         * @var array
         */
        $aVals = array(
            'callback_module' => isset($aData['sCallbackModule']) ? $aData['sCallbackModule'] : '',
            'callback_item_id' => isset($aData['iCallbackItemId']) ? (int) $aData['iCallbackItemId'] : 0,
            'album_id' => isset($aData['iAlbumId']) ? (int) $aData['iAlbumId'] : 0,
            'new_album_title' => isset($aData['sNewAlbumTitle']) ? trim($aData['sNewAlbumTitle']) : '',
            'title' => isset($aData['sTitle']) ? $aData['sTitle'] : '',
            'privacy' => isset($aData['iPrivacy']) ? (int) $aData['iPrivacy'] : 0,
            'privacy_comment' => isset($aData['iPrivacyComment']) ? (int) $aData['iPrivacyComment'] : 0,
            'privacy_list' => isset($aData['sPrivacyList']) ? explode('|', $aData['sPrivacyList']) : array(),
            'genre_id' => isset($aData['iGenreId']) ? (int) $aData['iGenreId'] : 0,
            'explicit' => isset($aData['iExplicit']) ? (int) $aData['iExplicit'] : null
        );
        /**
         * @var array
         */
        $aValidation = array(
            'title' => Phpfox::getPhrase('music.provide_a_name_for_this_song')
        );
        $oValidator = Phpfox::getLib('validator')->set(array(
            'sFormName' => 'js_music_form',
            'aParams' => $aValidation
                )
        );
        if ($oValidator->isValid($aVals))
        {
            if (Phpfox::getService('music.process')->update($aEditSong['song_id'], $aVals))
            {
                return array(
                    'result' => 1,
                    'error_code' => 0,
                    'message' => 'Song successfully updated.',
                    'iSongId' => $aEditSong['song_id'],
                    'sSongTitle' => $aEditSong['title']
                );
            }
        }

        return array('result' => 0, 'error_code' => 1, 'message' => Phpfox_Error::get());
    }

    /**
     * Input data:
     * + sCallbackModule: string, optional.
     * + iCallbackItemId: int, optional.
     * 
     * Output data:
     * + iAlbumId: int.
     * + sName: string.
     * 
     * @see Mobile - API phpFox/Api V3.0
     * @see song/albums
     * 
     * @param array $aData
     * @return array
     */
    public function albums($aData)
    {
        /**
         * @var string
         */
        $sModule = isset($aData['sCallbackModule']) ? $aData['sCallbackModule'] : false;
        /**
         * @var int
         */
        $iItem = isset($aData['iCallbackItemId']) ? (int) $aData['iCallbackItemId'] : false;
        /**
         * @var bool|array
         */
        $aCallback = false;
        if ($sModule !== false && $iItem !== false && Phpfox::hasCallback($sModule, 'getMusicDetails'))
        {
            if (($aCallback = Phpfox::callback($sModule . '.getMusicDetails', array('item_id' => $iItem))))
            {
                if ($sModule == 'pages' && !Phpfox::getService('pages')->hasPerm($iItem, 'music.share_music'))
                {
                    Phpfox_Error::set('Unable to view this item due to privacy settings.');

                    return array('result' => 0, 'error_code' => 1, 'message' => Phpfox_Error::get());
                }
            }
        }
        /**
         * @var array
         */
        $aAlbums = Phpfox::getService('music.album')->getForUpload($aCallback);
        $aResult = array();
        foreach ($aAlbums as $aAlbum)
        {
            $aResult[] = array('iAlbumId' => $aAlbum['album_id'], 'sName' => $aAlbum['name']);
        }
        return $aResult;
    }

    /**
     * Input data:
     * + iSongId: int, required.
     * 
     * Output data:
     * + iSongId: int.
     * + iGenreId: int.
     * + sTitle: string.
     * + iAlbumId: int.
     * + iPrivacy: int.
     * + iPrivacyComment: int.
     * + aAlbumList: array (sCallbackModule: string, iCallbackItemId: int)
     * + aGenreList: array
     * + aPrivacyList: array
     * + aPrivacyCommentList: array
     * 
     * @see Mobile - API phpFox/Api V3.0
     * @see song/get_song_for_edit
     * 
     * @param array $aData
     * @return array
     */
    public function getSongForEdit($aData)
    {
        if (!Phpfox::getUserParam('music.can_upload_music_public'))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => 'You don\'t have permission to upload music public!');
        }
        /**
         * @var int
         */
        $iSongId = isset($aData['iSongId']) ? (int) $aData['iSongId'] : 0;
        if ($iSongId < 1)
        {
            return array('result' => 0, 'error_code' => 1, 'message' => 'Song id is not valid!');
        }
        /**
         * @var array
         */
        $aSong = Phpfox::getService('music')->getForEdit($iSongId);
        if (!isset($aSong['song_id']))
        {
            Phpfox_Error::set('Song is not valid or has been deleted!');

            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox_Error::get());
        }

        // Check privacy for song.
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('music_song', $aSong['song_id'], $aSong['user_id'], $aSong['privacy'], $aSong['is_friend'], true))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'));
        }

        if ($aSong['module_id'] == 'pages')
        {
            Phpfox::getService('pages')->setIsInPage();
        }

        return array(
            'iSongId' => $aSong['song_id'],
            'iGenreId' => $aSong['genre_id'],
            'sTitle' => $aSong['title'],
            'iAlbumId' => $aSong['album_id'],
            'iPrivacy' => $aSong['privacy'],
            'iPrivacyComment' => $aSong['privacy_comment'],
            'aAlbumList' => $this->albums(array('sCallbackModule' => $aSong['module_id'], 'iCallbackItemId' => $aSong['item_id'])),
            'aGenreList' => $this->genres(),
            'aPrivacyList' => Phpfox::getService('mfox.privacy')->privacy(array()),
            'aPrivacyCommentList' => Phpfox::getService('mfox.privacy')->privacycomment(array())
        );
    }

    /**
     * Input data:
     * + iSongId: int, required.
     * 
     * Output data:
     * + result: int.
     * + error_code: int.
     * + message: string.
     * 
     * @see Mobile - API phpFox/Api V3.0
     * @see song/delete
     * 
     * @param array $aData
     * @return array
     */
    public function delete($aData)
    {
        /**
         * @var int
         */
        $iSongId = isset($aData['iSongId']) ? (int) $aData['iSongId'] : 0;
        if ($iSongId < 1)
        {
            return array('result' => 0, 'error_code' => 1, 'message' => 'Song id is not valid!');
        }
        /**
         * @var array
         */
        $aSong = Phpfox::getService('music')->getForEdit($iSongId);
        if (!isset($aSong['song_id']))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => 'Song has been deleted or you don\'t have permission to delete it!');
        }

        // Check privacy for song.
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('music_song', $aSong['song_id'], $aSong['user_id'], $aSong['privacy'], $aSong['is_friend'], true))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'));
        }
        /**
         * @var bool
         */
        $mResult = Phpfox::getService('music.process')->delete($iSongId);
        if ($mResult !== false)
        {
            return array('result' => 1, 'error_code' => 0, 'message' => Phpfox::getPhrase('music.song_successfully_deleted'));
        }

        return array('result' => 0, 'error_code' => 1, 'message' => 'Song has been deleted or you don\'t have permission to delete it!');
    }

    /**
     * Input data:
     * + sAction: string, optional.
     * + iLastTimeStamp: int, optional.
     * + iAmountOfSong: int, optional.
     * + iGenre: int, optional.
     * + sSearch: string, optional.
     * + sCallbackModuleId: string, optional.
     * + iCallbackItemId: int, optional.
     * + sView: string, optional.
     * + bIsProfile: bool, optional.
     * + iProfileId: int, optional.
     * 
     * Output data:
     * + result: int.
     * + error_code: int.
     * + message: string.
     * + bIsLiked: bool.
     * + sAlbumName: string.
     * + bIsOnProfile: bool.
     * + iSongId: int.
     * + iViewId: int.
     * + iPrivacy: int.
     * + iPrivacyComment: int.
     * + bIsFeatured: bool.
     * + bIsSponser: bool.
     * + iAlbumId: int.
     * + iGenreId: int.
     * + iUserId: int.
     * + sTitle: string.
     * + sDescription: string.
     * + sSongPath: string.
     * + iExplicit: int.
     * + sDuration: string.
     * + iOrdering: int.
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
     * + sUsername: string.
     * + sFullname: string.
     * + iGender: int.
     * + sUserImage: string.
     * + bIsInvisible: bool.
     * + iUserGroupId: int.
     * + iLanguageId: int.
     * 
     * @see Mobile - API phpFox/Api V3.0
     * @see song/searchSong
     * 
     * @see Phpfox_Parse_Input
     * @param array $aData
     * @return array
     */
    public function searchSong($aData)
    {
        return $this->getSongs($aData);
    }

    /**
     * Input data:
     * + sAction: string, optional.
     * + iLastTimeStamp: int, optional.
     * + iAmountOfSong: int, optional.
     * + iGenre: int, optional.
     * + sSearch: string, optional.
     * + sCallbackModuleId: string, optional.
     * + iCallbackItemId: int, optional.
     * + sView: string, optional.
     * + bIsProfile: bool, optional.
     * + iProfileId: int, optional.
     * 
     * Output data:
     * + result: int.
     * + error_code: int.
     * + message: string.
     * + bIsLiked: bool.
     * + sAlbumName: string.
     * + bIsOnProfile: bool.
     * + iSongId: int.
     * + iViewId: int.
     * + iPrivacy: int.
     * + iPrivacyComment: int.
     * + bIsFeatured: bool.
     * + bIsSponser: bool.
     * + iAlbumId: int.
     * + iGenreId: int.
     * + iUserId: int.
     * + sTitle: string.
     * + sDescription: string.
     * + sSongPath: string.
     * + iExplicit: int.
     * + sDuration: string.
     * + iOrdering: int.
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
     * + sUsername: string.
     * + sFullname: string.
     * + iGender: int.
     * + sUserImage: string.
     * + bIsInvisible: bool.
     * + iUserGroupId: int.
     * + iLanguageId: int.
     * 
     * @see Mobile - API phpFox/Api V3.0
     * @see song/searchSong
     * 
     * @see Phpfox_Parse_Input
     * @param array $aData
     * @return array
     */
    public function filter($aData)
    {
        return $this->getSongs($aData);
    }

    /**
     * Input data:
     * + sAction: string, optional.
     * + iLastTimeStamp: int, optional.
     * + iAmountOfSong: int, optional.
     * + iGenre: int, optional.
     * + sSearch: string, optional.
     * + sCallbackModuleId: string, optional.
     * + iCallbackItemId: int, optional.
     * + sView: string, optional.
     * + bIsProfile: bool, optional.
     * + iProfileId: int, optional.
     * 
     * Output data:
     * + result: int.
     * + error_code: int.
     * + message: string.
     * + bIsLiked: bool.
     * + sAlbumName: string.
     * + bIsOnProfile: bool.
     * + iSongId: int.
     * + iViewId: int.
     * + iPrivacy: int.
     * + iPrivacyComment: int.
     * + bIsFeatured: bool.
     * + bIsSponser: bool.
     * + iAlbumId: int.
     * + iGenreId: int.
     * + iUserId: int.
     * + sTitle: string.
     * + sDescription: string.
     * + sSongPath: string.
     * + iExplicit: int.
     * + sDuration: string.
     * + iOrdering: int.
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
     * + sUsername: string.
     * + sFullname: string.
     * + iGender: int.
     * + sUserImage: string.
     * + bIsInvisible: bool.
     * + iUserGroupId: int.
     * + iLanguageId: int.
     * 
     * @see Phpfox_Parse_Input
     * @param array $aData
     * @return array
     */
    private function getSongs($aData)
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
         * @var int
         */
        $iAmountOfSong = isset($aData['iAmountOfSong']) ? (int) $aData['iAmountOfSong'] : 10;
        /**
         * @var int
         */
        $iGenre = isset($aData['iGenre']) ? (int) $aData['iGenre'] : 0;
        /**
         * @var string
         */
        $sSearch = isset($aData['sSearch']) ? $aData['sSearch'] : '';
        /**
         * @var array
         */
        $aParentModule = array(
            'module_id' => isset($aData['sCallbackModuleId']) ? $aData['sCallbackModuleId'] : '',
            'item_id' => isset($aData['iCallbackItemId']) ? (int) $aData['iCallbackItemId'] : 0
        );
        /**
         * @var bool
         */
        $bIsPage = $aParentModule['module_id'] == 'pages' && $aParentModule['item_id'] > 0;
        /**
         * @var string
         */
        $sView = isset($aData['sView']) ? $aData['sView'] : '';
        /**
         * @var bool
         */
        $bIsProfile = (isset($aData['bIsProfile']) && $aData['bIsProfile'] == 'true') ? true : false;
        if ($bIsProfile)
        {
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
        // Check the action.
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
        // Search case.
        if (!empty($sSearch))
        {
            $aCond[] = 'm.title LIKE "' . Phpfox::getLib('parse.input')->clean('%' . $sSearch . '%') . '"';
        }
        // Profile case.
        if ($bIsProfile)
        {
            $aCond[] = ($aUser['user_id'] == Phpfox::getUserId() ? 'm.view_id IN(0,1)' : 'm.view_id IN(0)');
            $aCond[] = 'm.privacy IN(0,1,2,3,4)';
            $aCond[] = 'm.user_id = ' . $aUser['user_id'];
            $aCond[] = 'm.item_id = 0';
        }
        else
        {
            if ($bIsPage)
            {
                $aCond[] = "m.view_id = 0 AND m.privacy IN(0) AND m.module_id = 'pages' AND m.item_id = " . $aParentModule['item_id'];
            }
            else
            {
                if ($iGenre && ($aGenre = Phpfox::getService('music.genre')->getGenre($iGenre)))
                {
                    $aCond[] = 'm.genre_id = ' . (int) $iGenre;
                }
                // Check privacy.
                switch ($sView) {
                    case 'friend':
                        $aCond[] = 'm.view_id = 0';
                        $aCond[] = 'm.privacy IN(0,1,2)';
                        break;
                    case 'my':
                        $aCond[] = 'm.user_id = ' . Phpfox::getUserId();
                        break;
                    case 'pending':
                        if (!Phpfox::getUserParam('music.can_approve_songs'))
                        {
                            return array('result' => 0, 'error_code' => 1, 'message' => 'You don\'t have permission to approve songs!');
                        }
                        $aCond[] = 'm.view_id = 1';
                        break;
                    case 'all':
                    default:
                        $aCond[] = 'm.view_id = 0';
                        $aCond[] = 'm.privacy IN(0)';
                        break;
                }
                $aCond[] = 'm.item_id = 0';
            }
        }
        $this->database()
                ->select('COUNT(*)')
                ->from(Phpfox::getT('music_song'), 'm');
        if (!$bIsProfile && $sView == 'friend')
        {
            $this->database()->join(Phpfox::getT('friend'), 'friends', 'friends.user_id = m.user_id AND friends.friend_user_id = ' . Phpfox::getUserId());
        }
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
        $this->database()
                ->select('lik.like_id AS is_liked, ma.name AS album_name, mp.play_id AS is_on_profile, m.*, u.user_id, u.profile_page_id, u.server_id AS user_server_id, u.user_name, u.full_name, u.gender, u.user_image, u.is_invisible, u.user_group_id, u.language_id')
                ->from(Phpfox::getT('music_song'), 'm');
        // Check friend condition.
        if (!$bIsProfile && $sView == 'friend')
        {
            $this->database()->join(Phpfox::getT('friend'), 'friends', 'friends.user_id = m.user_id AND friends.friend_user_id = ' . Phpfox::getUserId());
        }
        /**
         * @var array
         */
        $aRows = $this->database()
                ->leftJoin(Phpfox::getT('like'), 'lik', "lik.type_id = 'music_song' AND lik.item_id = m.song_id AND lik.user_id = " . Phpfox::getUserId())
                ->leftJoin(Phpfox::getT('music_album'), 'ma', 'ma.album_id = m.album_id')
                ->leftJoin(Phpfox::getT('music_profile'), 'mp', 'mp.song_id = m.song_id AND mp.user_id = ' . Phpfox::getUserId())
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = m.user_id')
                ->where(implode(' AND ', $aCond))
                ->order('m.time_stamp DESC')
                ->limit(0, $iAmountOfSong, $iCount)
                ->execute('getRows');

        $aResult = array();

        foreach ($aRows as $aRow)
        {
            $aResult[] = array(
                'bIsLiked' => isset($aRow['is_liked']) ? true : false,
                'sAlbumName' => isset($aRow['album_name']) ? $aRow['album_name'] : 'No name',
                'bIsOnProfile' => isset($aRow['is_on_profile']) ? true : false,
                'iSongId' => $aRow['song_id'],
                'iViewId' => $aRow['view_id'],
                'iPrivacy' => $aRow['privacy'],
                'iPrivacyComment' => $aRow['privacy_comment'],
                'bIsFeatured' => (bool) $aRow['is_featured'],
                'bIsSponser' => (bool) $aRow['is_sponsor'],
                'iAlbumId' => $aRow['album_id'],
                'iGenreId' => $aRow['genre_id'],
                'iUserId' => $aRow['user_id'],
                'sTitle' => $aRow['title'],
                'sDescription' => $aRow['description'],
                'sSongPath' => Phpfox::getService('music')->getSongPath($aRow['song_path'], $aRow['server_id']),
                'iExplicit' => $aRow['explicit'],
                'sDuration' => $aRow['duration'],
                'iOrdering' => $aRow['ordering'],
                'iTotalPlay' => $aRow['total_play'],
                'iTotalComment' => $aRow['total_comment'],
                'iTotalLike' => $aRow['total_like'],
                'iTotalDislike' => $aRow['total_dislike'],
                'iTotalScore' => $aRow['total_score'],
                'iTotalRating' => $aRow['total_rating'],
                'iTimeStamp' => $aRow['time_stamp'],
                'sTimeStamp' => date('l, F j', $aRow['time_stamp']),
                'sFullTimeStamp' => date('l, F j', $aRow['time_stamp']) . ' at ' . date('g:i a', $aRow['time_stamp']),
                'sModuleId' => isset($aRow['module_id']) ? $aRow['module_id'] : '',
                'iItemId' => $aRow['item_id'],
                'iProfilePageId' => $aRow['profile_page_id'],
                'sUsername' => $aRow['user_name'],
                'sFullname' => $aRow['full_name'],
                'iGender' => $aRow['gender'],
                'sUserImage' => Phpfox::getLib('image.helper')->display(array(
                    'server_id' => $aRow['user_server_id'],
                    'path' => 'core.url_user',
                    'file' => $aRow['user_image'],
                    'suffix' => '_20_square',
                    'return_url' => true
                        )
                ),
                'bIsInvisible' => (bool) $aRow['is_invisible'],
                'iUserGroupId' => (int) $aRow['user_group_id'],
                'iLanguageId' => isset($aRow['language_id']) ? $aRow['language_id'] : 0,
            );
        }

        return $aResult;
    }

    /**
     * Input data:
     * + iSongId: int, required.
     * 
     * Output data:
     * + result: int.
     * + error_code: int.
     * + message: string.
     * 
     * @see Mobile - API phpFox/Api V3.0
     * @see song/like
     * 
     * @param array $aData
     * @return array
     */
    public function like($aData)
    {
        /**
         * @var int
         */
        $iSongId = isset($aData['iSongId']) ? (int) $aData['iSongId'] : 0;

        if (!Phpfox::getUserParam('music.can_access_music'))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => 'You don\'t have permission to access music!');
        }

        if (!($aSong = Phpfox::getService('music')->getSong($iSongId)))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('music.the_song_you_are_looking_for_cannot_be_found'));
        }

        // Check privacy for song.
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('music_song', $aSong['song_id'], $aSong['user_id'], $aSong['privacy'], $aSong['is_friend'], true))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'));
        }

        return Phpfox::getService('mfox.like')->add(array('sType' => 'music_song', 'iItemId' => $iSongId));
    }

    /**
     * Input data:
     * + iSongId: int, required.
     * 
     * Output data:
     * + result: int.
     * + error_code: int.
     * + message: string.
     * 
     * @see Mobile - API phpFox/Api V3.0
     * @see song/unlike
     * 
     * @param array $aData
     * @return array
     */
    public function unlike($aData)
    {
        /**
         * @var int
         */
        $iSongId = isset($aData['iSongId']) ? (int) $aData['iSongId'] : 0;

        if (!Phpfox::getUserParam('music.can_access_music'))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => 'You don\'t have permission to access music!');
        }

        if (!($aSong = Phpfox::getService('music')->getSong($iSongId)))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('music.the_song_you_are_looking_for_cannot_be_found'));
        }

        // Check privacy for song.
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('music_song', $aSong['song_id'], $aSong['user_id'], $aSong['privacy'], $aSong['is_friend'], true))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'));
        }

        if (isset($aSong['is_liked']) && $aSong['is_liked'])
        {
            return Phpfox::getService('mfox.like')->delete(array('sType' => 'music_song', 'iItemId' => $iSongId));
        }
        else
        {
            return array('result' => 0, 'error_code' => 1, 'message' => 'You have already "unliked" this item!');
        }
    }

    /**
     * Input data:
     * + iSongId: int, required.
     * 
     * Output data:
     * + result: int.
     * + error_code: int.
     * + message: string.
     * 
     * @param int $iSongId
     * @return array
     */
    public function checkPrivacyCommentOnSong($iSongId)
    {
        if (!Phpfox::getUserParam('music.can_access_music'))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => 'You don\'t have permission to access music!');
        }

        if (!($aSong = Phpfox::getService('music')->getSong($iSongId)))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('music.the_song_you_are_looking_for_cannot_be_found'));
        }

        // Check privacy for song.
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('music_song', $aSong['song_id'], $aSong['user_id'], $aSong['privacy'], $aSong['is_friend'], true))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'));
        }

        if (!Phpfox::getService('mfox.comment')->checkCanPostCommentOnItem($aSong))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => 'You don\'t have permission to post comment on this item!');
        }

        return null;
    }

    /**
     * Input data:
     * + iSongId: int, required.
     * 
     * Output data:
     * + result: int.
     * + error_code: int.
     * + message: string.
     * 
     * @param int $iSongId
     * @return array
     */
    public function checkPrivacyOnSong($iSongId)
    {
        if (!Phpfox::getUserParam('music.can_access_music'))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => 'You don\'t have permission to access music!');
        }

        if (!($aSong = Phpfox::getService('music')->getSong($iSongId)))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('music.the_song_you_are_looking_for_cannot_be_found'));
        }

        // Check privacy for song.
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('music_song', $aSong['song_id'], $aSong['user_id'], $aSong['privacy'], $aSong['is_friend'], true))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'));
        }

        return null;
    }

    /**
     * Input data:
     * + iSongId: int, required.
     * 
     * Output data:
     * + result: int.
     * + error_code: int.
     * + message: string.
     * 
     * @see Mobile - API phpFox/Api V3.0
     * @see song/listComment
     * 
     * @param array $aData
     * @return array
     */
    public function listComment($aData)
    {
        /**
         * @var int
         */
        $iSongId = isset($aData['iSongId']) ? (int) $aData['iSongId'] : 0;

        if (!Phpfox::getUserParam('music.can_access_music'))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => 'You don\'t have permission to access music!');
        }

        if (!($aSong = Phpfox::getService('music')->getSong($iSongId)))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('music.the_song_you_are_looking_for_cannot_be_found'));
        }

        // Check privacy for song.
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('music_song', $aSong['song_id'], $aSong['user_id'], $aSong['privacy'], $aSong['is_friend'], true))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'));
        }

        return Phpfox::getService('mfox.comment')->listallcomments(array('sType' => 'music_song', 'iItemId' => $iSongId));
    }

    /**
     * Using in notification.
     * @param array $aNotification
     * @return boolean|array
     */
    public function doSongGetNotificationSong_Like($aNotification)
    {
        /**
         * @var array
         */
        $aRow = $this->database()->select('ms.song_id, ms.title, ms.user_id, u.gender, u.full_name')
                ->from(Phpfox::getT('music_song'), 'ms')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = ms.user_id')
                ->where('ms.song_id = ' . (int) $aNotification['item_id'])
                ->execute('getSlaveRow');

        if (!isset($aRow['song_id']))
        {
            return false;
        }
        /**
         * @var string
         */
        $sPhrase = '';
        if ($aNotification['user_id'] == $aRow['user_id'])
        {
            $sPhrase = Phpfox::getPhrase('music.user_name_liked_gender_own_song_title', array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], (Phpfox::isModule('notification') ? Phpfox::getParam('notification.total_notification_title_length') : $this->_iFallbackLength), '...')));
        }
        elseif ($aRow['user_id'] == Phpfox::getUserId())
        {
            $sPhrase = Phpfox::getPhrase('music.users_liked_your_song_title', array('users' => Phpfox::getService('notification')->getUsers($aNotification), 'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], (Phpfox::isModule('notification') ? Phpfox::getParam('notification.total_notification_title_length') : $this->_iFallbackLength), '...')));
        }
        else
        {
            $sPhrase = Phpfox::getPhrase('music.user_name_liked_span_class_drop_data_user_full_name_s_span_song_title', array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'full_name' => $aRow['full_name'], 'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], (Phpfox::isModule('notification') ? Phpfox::getParam('notification.total_notification_title_length') : $this->_iFallbackLength), '...')));
        }

        return array(
            'link' => array(
                'iSongId' => $aRow['song_id'],
                'sSongTitle' => $aRow['title']
            ),
            'message' => strip_tags($sPhrase),
            'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog'),
            'sModule' => 'music',
            'sMethod' => 'getNotificationSong_Like'
        );
    }

    /**
     * Using in notification.
     * @param array $aNotification
     * @return array
     */
    public function doSongGetCommentNotificationSong($aNotification)
    {
        /**
         * @var array
         */
        $aRow = $this->database()->select('l.song_id, l.title, u.user_id, u.gender, u.user_name, u.full_name')
                ->from(Phpfox::getT('music_song'), 'l')
                ->join(Phpfox::getT('user'), 'u', 'u.user_id = l.user_id')
                ->where('l.song_id = ' . (int) $aNotification['item_id'])
                ->execute('getSlaveRow');
        /**
         * @var string
         */
        $sPhrase = '';
        if ($aNotification['user_id'] == $aRow['user_id'] && !isset($aNotification['extra_users']))
        {
            $sPhrase = Phpfox::getPhrase('music.users_commented_on_gender_song_title', array('users' => Phpfox::getService('notification')->getUsers($aNotification), 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], (Phpfox::isModule('notification') ? Phpfox::getParam('notification.total_notification_title_length') : $this->_iFallbackLength), '...')));
        }
        elseif ($aRow['user_id'] == Phpfox::getUserId())
        {
            $sPhrase = Phpfox::getPhrase('music.users_commented_on_your_song_title', array('users' => Phpfox::getService('notification')->getUsers($aNotification), 'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], (Phpfox::isModule('notification') ? Phpfox::getParam('notification.total_notification_title_length') : $this->_iFallbackLength), '...')));
        }
        else
        {
            $sPhrase = Phpfox::getPhrase('music.user_name_commented_on_span_class_drop_data_user_full_name_s_span_song_title', array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'full_name' => $aRow['full_name'], 'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], (Phpfox::isModule('notification') ? Phpfox::getParam('notification.total_notification_title_length') : $this->_iFallbackLength), '...')));
        }

        return array(
            'link' => array(
                'iSongId' => $aRow['song_id'],
                'sSongTitle' => $aRow['title']
            ),
            'message' => strip_tags($sPhrase),
            'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog'),
            'sModule' => 'music',
            'sMethod' => 'getCommentNotificationSong'
        );
    }

    /**
     * Using to get notification when comment on song.
     * @param int $iId
     * @return array
     */
    public function doSongGetRedirectCommentSong($iId)
    {
        return $this->getFeedRedirectSong($iId);
    }

    /**
     * Using to get feed redirect song.
     * @param int $iId
     * @return boolean|array
     */
    public function getFeedRedirectSong($iId)
    {
        /**
         * @var array
         */
        $aRow = $this->database()->select('m.song_id, m.title')
                ->from(Phpfox::getT('music_song'), 'm')
                ->where('m.song_id = ' . (int) $iId)
                ->execute('getSlaveRow');
        ;
        if (!isset($aRow['song_id']))
        {
            return false;
        }
        return array(
            'sModule' => 'music',
            'iAlbumId' => $aRow['song_id'],
            'sTitle' => $aRow['title'],
            'sCommentType' => 'music'
        );
    }

    /**
     * Input data:
     * + iSongId: int, required.
     * 
     * Output data:
     * + bIsLiked: bool.
     * + bIsFriend: bool.
     * + iSongId: int.
     * + iViewId: int.
     * + iPrivacy: int.
     * + iPrivacyComment: int.
     * + bIsFeatured: bool.
     * + bIsSponsor: bool.
     * + iAlbumId: int.
     * + iGenreId: int.
     * + iUserId: int.
     * + sTitle: string.
     * + sDescription: string.
     * + sSongPath: string.
     * + iExplicit: int.
     * + sDuration: string.
     * + iOrdering: int.
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
     * + iSongTotalComment: int.
     * + iSongTotalPlay: int.
     * + iSongTimeStamp: int.
     * + sSongTimeStamp: string.
     * + sFullSongTimeStamp: string.
     * + bSongIsSponsor: bool.
     * + sAlbumUrl: string.
     * + sAlbumName: string.
     * + bIsOnProfile: bool.
     * + bProfileUserId: bool.
     * + bHasRated: bool.
     * + iProfilePageId: int.
     * + sUserName: string.
     * + sFullName: string.
     * + iGender: int.
     * + sUserImage: string.
     * + bIsInvisible: bool.
     * + iUserGroupId: int.
     * + iLanguageId: int.
     * + bCanPostComment: bool.
     * 
     * @see Mobile - API phpFox/Api V3.0
     * @see song/details
     * 
     * @param array $aData
     * @return array
     */
    public function details($aData)
    {
        /**
         * @var int
         */
        $iSongId = isset($aData['iSongId']) ? (int) $aData['iSongId'] : 0;
        if (!Phpfox::getUserParam('music.can_access_music'))
        {
            return array(
                'result' => 0,
                'error_code' => 1,
                'message' => 'You don\'t have permission to access music!'
            );
        }
        if (Phpfox::isModule('notification'))
        {
            Phpfox::getService('notification.process')->delete('comment_music_song', $iSongId, Phpfox::getUserId());
            Phpfox::getService('notification.process')->delete('music_song_like', $iSongId, Phpfox::getUserId());
        }
        if (!($aSong = Phpfox::getService('music')->getSong($iSongId)))
        {
            return array(
                'result' => 0,
                'error_code' => 1,
                'message' => Phpfox::getPhrase('music.the_song_you_are_looking_for_cannot_be_found')
            );
        }
        if (Phpfox::isModule('notification') && $aSong['user_id'] == Phpfox::getUserId())
        {
            Phpfox::getService('notification.process')->delete('music_songapproved', $aSong['song_id'], Phpfox::getUserId());
        }
        /**
         * @var array
         */
        $aCallback = false;
        if (!empty($aSong['module_id']))
        {
            if ($aCallback = Phpfox::callback($aSong['module_id'] . '.getMusicDetails', $aSong))
            {
                if ($aSong['module_id'] == 'pages' && !Phpfox::getService('pages')->hasPerm($aCallback['item_id'], 'music.view_browse_music'))
                {
                    return array(
                        'result' => 0,
                        'error_code' => 1,
                        'message' => Phpfox::getPhrase('music.unable_to_view_this_item_due_to_privacy_settings')
                    );
                }
            }
        }
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('music_song', $aSong['song_id'], $aSong['user_id'], $aSong['privacy'], $aSong['is_friend'], true))
        {
            return array(
                'result' => 0,
                'error_code' => 1,
                'message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time')
            );
        }
        if (!$aSong)
        {
            return array();
        }
        
        // Update play time.
        Phpfox::getService('music.process')->play($aSong['song_id']);
        
        return array(
            'bIsLiked' => $aSong['is_liked'],
            'bIsFriend' => $aSong['is_friend'],
            'iSongId' => $aSong['song_id'],
            'iViewId' => $aSong['view_id'],
            'iPrivacy' => $aSong['privacy'],
            'iPrivacyComment' => $aSong['privacy_comment'],
            'bIsFeatured' => $aSong['is_featured'],
            'bIsSponsor' => $aSong['is_sponsor'],
            'iAlbumId' => $aSong['album_id'],
            'iGenreId' => $aSong['genre_id'],
            'iUserId' => $aSong['user_id'],
            'sTitle' => $aSong['title'],
            'sDescription' => $aSong['description'],
            'sSongPath' => $aSong['song_path'],
            'iExplicit' => $aSong['explicit'],
            'sDuration' => $aSong['duration'],
            'iOrdering' => $aSong['ordering'],
            'iTotalPlay' => $aSong['total_play'],
            'iTotalComment' => $aSong['total_comment'],
            'iTotalLike' => $aSong['total_like'],
            'iTotalDislike' => $aSong['total_dislike'],
            'fTotalScore' => $aSong['total_score'],
            'iTotalRating' => $aSong['total_rating'],
            'iTimeStamp' => $aSong['time_stamp'],
            'sTimeStamp' => date('l, F j', $aSong['time_stamp']),
            'sFullTimeStamp' => $aSong['sTimeStamp'] . ' at ' . date('g:i a', $aSong['time_stamp']),
            'sModuleId' => $aSong['module_id'],
            'iItemId' => $aSong['item_id'],
            'iSongTotalComment' => $aSong['song_total_comment'],
            'iSongTotalPlay' => $aSong['song_total_play'],
            'iSongTimeStamp' => $aSong['song_time_stamp'],
            'sSongTimeStamp' => date('l, F j', $aSong['song_time_stamp']),
            'sFullSongTimeStamp' => date('l, F j', $aSong['song_time_stamp']) . ' at ' . date('g:i a', $aSong['song_time_stamp']),
            'bSongIsSponsor' => $aSong['song_is_sponsor'],
            'sAlbumUrl' => $aSong['album_url'],
            'sAlbumName' => empty($aSong['album_url']) ? 'No name' : $aSong['album_url'],
            'bIsOnProfile' => $aSong['is_on_profile'],
            'bProfileUserId' => $aSong['profile_user_id'],
            'bHasRated' => $aSong['has_rated'],
            'iProfilePageId' => $aSong['profile_page_id'],
            'sUserName' => $aSong['user_name'],
            'sFullName' => $aSong['full_name'],
            'iGender' => $aSong['gender'],
            'sUserImage' => Phpfox::getLib('image.helper')->display(array(
                'server_id' => $aSong['user_server_id'],
                'path' => 'core.url_user',
                'file' => $aSong['user_image'],
                'suffix' => '_50_square',
                'return_url' => true
                    )
            ),
            'bIsInvisible' => $aSong['is_invisible'],
            'iUserGroupId' => $aSong['user_group_id'],
            'iLanguageId' => $aSong['language_id'],
            'bCanPostComment' => Phpfox::getService('mfox.comment')->checkCanPostCommentOnItem($aSong),
            'aEditAlbums' => $this->getAlbumsForEdit($aCallback),
        );
    }

    /**
     * Get albums for edit when edit.
     * @param array $aCallback
     * @return array
     */
    public function getAlbumsForEdit($aCallback = null)
    {
        /**
         * @var array
         */
        $aCond = array();
        if (isset($aCallback['module_id']))
        {
            $aCond[] = 'ma.view_id = 0 AND ma.user_id = ' . Phpfox::getUserId() . ' AND ma.item_id = 0';
        }
        else
        {
            $aCond[] = 'ma.view_id = 0 AND ma.user_id = ' . Phpfox::getUserId() . ' AND ma.item_id = 0';
        }
        /**
         * @var array Get albums.
         */
        $aAlbums = $this->database()
                        ->select('ma.album_id AS iAlbumId, ma.name AS sName')
                        ->from(Phpfox::getT('music_album'), 'ma')
                        ->where($aCond)
                        ->order('ma.name ASC')
                        ->execute('getSlaveRows');
        // Insert default.
        array_unshift($aAlbums, array('iAlbumId' => 0, 'sName' => 'Select:'));
        
        return $aAlbums;
    }
    /**
     * Input data:
     * + sModuleId: string, optional. In pages. Default ''.
     * + iItem: int, optional. In pages. Default 0.
     * 
     * Output data:
     * + iAlbumId: int.
     * + sName: string.
     * 
     * @see Mobile - API phpFox/Api V3.0
     * @see song/getAlbumsForUpload
     * 
     * @param array $aData
     * @return array
     */
    public function getAlbumsForUpload($aData)
    {
        /**
         * @var array
         */
        $aCallback = array(
            'sModuleId' => isset($aData['sModuleId']) && !empty($aData['sModuleId']) ? $aData['sModuleId'] : null,
            'iItem' => isset($aData['iItem']) && $aData['iItem'] > 0 ? (int) $aData['iItem'] : null
        );
        return $this->getAlbumsForEdit($aCallback);
    }
    /**
     * Input data:
     * + iSongId: int, required.
     * 
     * Output data:
     * + result: int.
     * + error_code: int.
     * + message: string.
     * 
     * @see Mobile - API phpFox/Api V3.0
     * @see song/updateCounterMusic
     * 
     * @param array $aData
     * @return array
     */
    public function updateCounterMusic($aData)
    {
        /**
         * @var int
         */
        $iSongId = isset($aData['iSongId']) ? (int) $aData['iSongId'] : 0;
        
        if (!Phpfox::getUserParam('music.can_access_music'))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => 'You don\'t have permission to access music!');
        }

        if (!($aSong = Phpfox::getService('music')->getSong($iSongId)))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('music.the_song_you_are_looking_for_cannot_be_found'));
        }

        // Check privacy for song.
        if (Phpfox::isModule('privacy') && !Phpfox::getService('privacy')->check('music_song', $aSong['song_id'], $aSong['user_id'], $aSong['privacy'], $aSong['is_friend'], true))
        {
            return array('result' => 0, 'error_code' => 1, 'message' => Phpfox::getPhrase('privacy.the_item_or_section_you_are_trying_to_view_has_specific_privacy_settings_enabled_and_cannot_be_viewed_at_this_time'));
        }
        
        // Update play time.
        Phpfox::getService('music.process')->play($iSongId);
        
        return array('result' => 1, 'error_code' => 0, 'message' => 'Update counter music successfully!');
    }
}
