<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');
?>
<?php

class musicsharing_Component_Controller_Createplaylist extends Phpfox_Component
{
    private $_aPhotoSizes = array(50, 90, 112, 115, 255);
    
    private function isValidate($value)
    {
        phpFox::isUser(true);

        $strErr = "";
        if (empty($value['title']))
            $strErr .= Phpfox::getPhrase('musicsharing.please_enter_playlist_name') . '<br/>';
        if (isset($_FILES['playlist_image']) && !empty($_FILES['playlist_image']['name']))
        {
            $image = $_FILES['playlist_image']['type'];

            if (!in_array($image, array("image/gif", "image/jpeg", "image/pjpeg", "image/png")))
            {
                $strErr .= Phpfox::getPhrase('musicsharing.invalid_image_file_type');
            }
        }
        return $strErr;
    }

    public function process()
    {
        $oSerMusic = phpFox::getService('musicsharing.music');
        
        $aParentModule = $this->getParam('aParentModule');

        //modified section (v 300b1)
        //build filter menu
        $oSerMusic->getSectionMenu($aParentModule);

        $this->template()->clearBreadCrumb();
        $this->template()
                ->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'), $this->url()->makeUrl('musicsharing'))
                ->setBreadCrumb(phpFox::getPhrase('musicsharing.add_new_playlist'), null)
                ->setBreadCrumb(phpFox::getPhrase('musicsharing.add_new_playlist'), null, true);

        ///modified section (v 300b1)   
        if ($aParentModule)
        {
            phpFox::getLib('session')->set('pages_msf', $aParentModule);
            if (!phpFox::getService('pages')->hasPerm($aParentModule['item_id'], 'musicsharing.can_create_playlist'))
            {
                $this->url()->send("subscribe");
            }
        }
        else
        {
            phpFox::getLib('session')->remove('pages_msf');
        }

        $this->template()->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'), null);

        $prefix = phpFox::getParam(array('db', 'prefix'));
        $settings = $oSerMusic->getUserSettings(phpFox::getUserId());
        $where = " where " . $prefix . "m2bmusic_playlist.user_id = " . phpFox::getUserId();
        $list_total = $oSerMusic->get_total_playlist($where);

        $this->template()->assign(array('settings' => $settings, 'total_playlist' => $list_total));

        $this->template()->setHeader(array('music.css' => 'module_musicsharing'));
        
        $this->template()->assign(array(
            'sDeleteBlock' => 'dashboard',
            'core_path' => phpFox::getParam('core.path'),
            'total_playlist' => $list_total,
            'aForms' => $list_total,
        ));
        
        phpFox::isUser(true);
        $settings = $oSerMusic->getUserSettings(phpFox::getUserId());
        
        if (isset($_POST['submit']))
        {
            if ($settings['max_playlist_created'] <= $list_total)
            {
                if ($aParentModule)
                {
                    $this->url()->send('pages.' . $aParentModule['item_id'] . '.musicsharing.myplaylists', null, null);
                }
                else
                {
                    $this->url()->send('musicsharing.myplaylists', null, null);
                }
            }
            
            $sFileName = "";
            $aVals = $this->request()->getArray('val');
            $errorValidate = $this->isValidate($aVals);
            if ($errorValidate != "")
            {
                $this->template()->assign(array(
                    'title' => $aVals['title'],
                    'description' => $aVals['description'],
                    'privacy' => $aVals['privacy']
                ));
                Phpfox_Error::set($errorValidate);
                return FALSE;
            }

            
            $title = "";

            if ($aVals['title'] != "")
            {
                $title = $aVals['title'];
            }
            $description = "";
            if ($aVals['description'] != "")
            {
                $description = $aVals['description'];
            }
            $privacy = $aVals['privacy'];
            $privacy_comment = $aVals['privacy_comment'];
            $search = $this->request()->getInt('search');

            $profile = 1;
            $user_id = phpFox::getUserId();
            $where = " WHERE " . $prefix . "m2bmusic_playlist.user_id = $user_id";

            if ($oSerMusic->get_total_playlist($where) != 0)
                $profile = 0;

            if (trim($title) == "")
            {
                return Phpfox_Error::set(Phpfox::getPhrase('musicsharing.please_enter_playlist_name'));
            }
            $currentDate = date("Y-m-d H:i:s");
            $playlist = array();
            $playlist['title'] = $title;
            $playlist['title_url'] = $title;
            $playlist['user_id'] = phpFox::getUserId();
            $playlist['playlist_image'] = $sFileName;
            $playlist['description'] = $description;
            $playlist['search'] = $search;
            $playlist['is_download'] = 1; // $download;
            $playlist['profile'] = $profile;
            $playlist['creation_date'] = $currentDate;
            $playlist['modified_date'] = $currentDate;
            $playlist['privacy'] = $privacy;
            $playlist['privacy_comment'] = $privacy_comment;

            $playlist_id = $oSerMusic->createPlaylist($playlist);

            if (isset($_FILES['playlist_image']) && trim($_FILES['playlist_image']['name']) != '')
            {
                $oFile = Phpfox::getLib('file');
                $oImage = phpFox::getLib('image');
                
                $aImage = $oFile->load('playlist_image', array('jpg', 'gif', 'png'));
                if ($aImage !== false)
                {
                    $sPath = PHPFOX_DIR_FILE . 'pic' . PHPFOX_DS . 'musicsharing' . PHPFOX_DS;
                    // Make directory.
                    if (!is_dir($sPath))
                    {
                        @mkdir($sPath, 0777, 1);
                    }
                    
                    // Get url.
                    $sFileName = $oFile->upload('playlist_image', $sPath, $playlist_id);
                    foreach($this->_aPhotoSizes as $iSize)
                    {
                        $oImage->createThumbnail($sPath . sprintf($sFileName, ''), $sPath . sprintf($sFileName, '_' . $iSize), $iSize, $iSize);
                    }
                    
                    // Support CDN.
                    $oSerMusic->updatePlaylistInfo($playlist_id, array('playlist_image' => $sFileName, 'server_id' => $this->request()->getServer('PHPFOX_SERVER_ID')));
                }
            }
            
            $oSerMusic->updateOrderPlaylist(phpFox::getUserId());

            if ($aVals['privacy'] == '4')
            {
                phpFox::getService('privacy.process')->add('musicsharing_playlist', $playlist_id, (isset($aVals['privacy_list']) ? $aVals['privacy_list'] : array()));
            }
            if ($aParentModule)
            {
                $this->url()->send('pages.' . $aParentModule['item_id'] . '.musicsharing.editplaylist.playlist_' . $playlist_id, null, null);
            }
            else
            {
                $this->url()->send('musicsharing.editplaylist.playlist_' . $playlist_id, null, null);
            }
        }
        $this->template()->setHeader(
                array(
                    'musicsharing_style.css' => 'module_musicsharing',
                    'suppress_menu.css' => 'module_musicsharing',
                )
        )->clearBreadCrumb();

        //modified section (v 300b1)
        //build filter menu
        $oSerMusic->getSectionMenu($aParentModule);
        // $catitle = $this->template()->getBreadCrumb();
        if (!$aParentModule)
        {
            // $satitle = isset($catitle[1][0])?$catitle[1][0]:$catitle[0][0];
            $this->template()->clearBreadCrumb();
            $this->template()
                    ->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'), $this->url()->makeUrl('musicsharing'))
                    ->setBreadCrumb(phpFox::getPhrase('musicsharing.create_new_playlist2'), null)
                    ->setBreadCrumb(phpFox::getPhrase('musicsharing.create_new_playlist2'), null, true);
        }
        else
        {
            $this->template()->clearBreadCrumb();
            $this->template()
                    ->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'), $this->url()->makeUrl('musicsharing'), false);
        }
        ///modified section (v 300b1)
    }

}
