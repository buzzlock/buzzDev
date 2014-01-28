<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class musicsharing_Component_Controller_Editplaylist extends Phpfox_Component
{
    private $_aPhotoSizes = array(50, 90, 112, 115, 255);
    
    /**
     * Class process method wnich is used to execute this component.
     */
    private function isValidate($value)
    {
        phpFox::isUser(true);

        $strErr = "";
        if (empty($value['title']))
            $strErr .= Phpfox::getPhrase('musicsharing.please_enter_playlist_name') . "<br/>";

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
        Phpfox::getLib('setting')->setParam('musicsharing.url_image', Phpfox::getParam('core.url_pic') . 'musicsharing' . PHPFOX_DS);
        
        $oSerMusic = phpFox::getService('musicsharing.music');
        
        $aParentModule = $this->getParam('aParentModule');
        $playlist_id = $this->request()->getInt('playlist');

        if ($aParentModule)
        {
            $aParentModule['msf']['editplaylist'] = $this->url()->makeUrl('pages.' . $aParentModule['item_id'] . '.musicsharing.editplaylist.playlist_' . $playlist_id);
            $aParentModule['msf']['playlistsongs'] = $this->url()->makeUrl('pages.' . $aParentModule['item_id'] . '.musicsharing.playlistsongs.playlist_' . $playlist_id);

            phpFox::getLib('session')->set('pages_msf', $aParentModule);
            $this->template()->assign(array('aParentModule' => $aParentModule));
        }
        else
        {
            phpFox::getLib('session')->remove('pages_msf');
        }
        $this->template()->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'), null);
        $this->template()->setHeader(array(
            'm2bmusic_tabcontent.js' => 'module_musicsharing',
            'm2bmusic_class.js' => 'module_musicsharing',
            'music.css' => 'module_musicsharing'
        ));
        phpFox::isUser(true);

        $playlist_info = $oSerMusic->getPlaylistInfo($playlist_id);
        $user_viewer = phpFox::getUserId();

        // Set default for result message.
        $result = 0;

        if ($playlist_info['user_id'] == $user_viewer || phpFox::isAdmin(true))
        {
            $this->template()->assign(array('playlist_info' => $playlist_info));
            if (isset($_POST['submit']))
            {
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
                    
                    // Set the variable.
                    $aAssign = array();
                    $aAssign['result'] = $result;
                    $aAssign['aForms'] = $playlist_info;
                    $aAssign['core_path'] = phpFox::getParam('core.path');
                    
                    $this->template()->assign($aAssign);
                    
                    $oSerMusic->getSectionMenu($aParentModule);
                    
                    return FALSE;
                }
                
                $title = trim($aVals['title']);

                if ($title == "")
                {
                    return Phpfox_Error::set(Phpfox::getPhrase('musicsharing.please_enter_playlist_name'));
                }

                $description = trim($aVals['description']);

                $privacy = $aVals['privacy'];
                $privacy_comment = $aVals['privacy_comment'];
                $search = $this->request()->getInt('search');

                $currentDate = date("Y-m-d H:i:s");
                $playlist = array();
                $playlist['playlist_id'] = $playlist_id;
                $playlist['title'] = $title;
                $playlist['title_url'] = $title;
                $playlist['description'] = $description;
                $playlist['search'] = $search;
                $playlist['is_download'] = 1;
                $playlist['modified_date'] = $currentDate;
                $playlist['privacy'] = $privacy;
                $playlist['privacy_comment'] = $privacy_comment;

                $playlist_id = $oSerMusic->editPlaylist($playlist);
                
                if (isset($_FILES['playlist_image']) && trim($_FILES['playlist_image']['name']) != '')
                {
                    $oFile = Phpfox::getLib('file');
                    $oImage = phpFox::getLib('image');

                    $aImage = $oFile->load('playlist_image', array('jpg', 'gif', 'png'));
                    if ($aImage !== false)
                    {
                        $sPath = PHPFOX_DIR_FILE . 'pic' . PHPFOX_DS . 'musicsharing' . PHPFOX_DS . sprintf($playlist_info['playlist_image'], '');
                        is_file($sPath) ? @unlink($sPath) : null;
                        
                        // Delete old images.
                        $aSizes = array(50, 90, 112, 115, 255);
                        foreach($aSizes as $iSize)
                        {
                            $sPath = PHPFOX_DIR_FILE . 'pic' . PHPFOX_DS . 'musicsharing' . PHPFOX_DS . sprintf($playlist_info['playlist_image'], '_' . $iSize);
                            is_file($sPath) ? @unlink($sPath) : null;
                            
                            $sPathSquare = PHPFOX_DIR_FILE . 'pic' . PHPFOX_DS . 'musicsharing' . PHPFOX_DS . sprintf($playlist_info['album_image'], '_' . $iSize . '_square');
                            is_file($sPathSquare) ? @unlink($sPathSquare) : null;
                        }
                        
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
                
                $result = $playlist_id;
                $playlist_info = $oSerMusic->getPlaylistInfo($playlist_id);

                if ($privacy == '4')
                {
                    phpFox::getService('privacy.process')->update('musicsharing_playlist', $playlist_id, (isset($aVals['privacy_list']) ? $aVals['privacy_list'] : array()));
                }
                else
                {
                    phpFox::getService('privacy.process')->delete('musicsharing_playlist', $playlist_id);
                }
            }

            $this->template()->assign(array(
                'sDeleteBlock' => 'dashboard',
                'playlist_info' => $playlist_info,
                'core_path' => phpFox::getParam('core.path'),
                'user_id' => phpFox::getUserId()
            ));
            $this->template()
                    ->setHeader(
                            array(
                                'musicsharing_style.css' => 'module_musicsharing',
                                'suppress_menu.css' => 'module_musicsharing',
                            )
            );
        }

        // Set the variable.
        $aAssign = array();
        $aAssign['result'] = $result;
        $aAssign['aForms'] = $playlist_info;

        $this->template()->assign($aAssign);

        //modified section (v 300b1)
        //build filter menu
        $oSerMusic->getSectionMenu($aParentModule);
        
        if (!$aParentModule)
        {
            $this->template()->clearBreadCrumb();
            $this->template()
                    ->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'), $this->url()->makeUrl('musicsharing'))
                    ->setBreadCrumb(phpFox::getPhrase('musicsharing.edit_playlist'), null, true);
        }
        else
        {
            $this->template()->clearBreadCrumb();
            $this->template()->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'), $this->url()->makeUrl('musicsharing'), false);
        }
        ///modified section (v 300b1)
    }

    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('musicsharing.component_controller_editplaylist_clean')) ? eval($sPlugin) : false);
    }

}

?>