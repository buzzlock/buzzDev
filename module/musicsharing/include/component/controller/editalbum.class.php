<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class musicsharing_Component_Controller_Editalbum extends Phpfox_Component {

    private $_aPhotoSizes = array(50, 90, 115, 255, 345);
    
    /**
     * Class process method wnich is used to execute this component.
     */
    private function isValidate($value)
    {
        phpFox::isUser(true);

        $strErr = "";
        if (empty($value['title']))
            $strErr .= Phpfox::getPhrase('musicsharing.please_enter_album_name') . '<br />';

        if (isset($_FILES['album_image']) && !empty($_FILES['album_image']['name']))
        {
            $image = $_FILES['album_image']['type'];

            if (!in_array($image, array("image/gif", "image/jpeg", "image/pjpeg", "image/png")))
            {
                $strErr .= Phpfox::getPhrase('musicsharing.invalid_image_file_type');
            }
        }

        return $strErr;
    }
    
    /**
     * @see Phpfox_Image_Helper
     * @see Phpfox_Image_Library_Gd
     * @see Phpfox_Cdn_Module_Phpfox
     */
    public function process()
    {
        Phpfox::getLib('setting')->setParam('musicsharing.url_image', Phpfox::getParam('core.url_pic') . 'musicsharing' . PHPFOX_DS);
        
        $oSerMusic = Phpfox::getService('musicsharing.music');
        
        $album_id = $this->request()->getInt('album');
        $aParentModule = $this->getParam('aParentModule');
        if ($aParentModule)
        {
            $aParentModule['msf']['editalbum'] = $this->url()->makeUrl('pages.' . $aParentModule['item_id'] . '.musicsharing.editalbum.album_' . $album_id);
            $aParentModule['msf']['albumsongs'] = $this->url()->makeUrl('pages.' . $aParentModule['item_id'] . '.musicsharing.albumsongs.album_' . $album_id);

            phpFox::getLib('session')->set('pages_msf', $aParentModule);

            $this->template()->assign(array('aParentModule' => $aParentModule));
        }
        else
        {
            phpFox::getLib('session')->remove('pages_msf');
        }

        $this->template()->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'), null);
        phpFox::isUser(true);

        $settings = $oSerMusic->getUserSettings(phpFox::getUserId());

        $this->template()->assign(array('settings' => $settings));

        $album_info = $oSerMusic->getAlbumInfo($album_id);
        
        $user_viewer = phpFox::getUserId();
        if ($album_info['user_id'] == $user_viewer || phpFox::isAdmin(true))
        {
            $result = 0;
            $this->template()->setHeader(array(
                'm2bmusic_tabcontent.js' => 'module_musicsharing',
                'm2bmusic_class.js' => 'module_musicsharing',
                'music.css' => 'module_musicsharing'
            ));
            
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
                }
                else
                {
                    $album = array();

                    $title = "";
                    $aVals = $this->request()->getArray('val');

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
                    $download = $this->request()->getInt('is_download');

                    $currentDate = date("Y-m-d H:i:s");

                    $album['album_id'] = $album_id;
                    $album['title'] = $title;
                    $album['title_url'] = $title;
                    $album['description'] = $description;
                    $album['search'] = $search;
                    $album['is_download'] = $download;
                    $album['modified_date'] = $currentDate;
                    $album['privacy'] = $privacy;
                    $album['privacy_comment'] = $privacy_comment;

                    $album_id = $oSerMusic->editAlbum($album);

                    if (isset($_FILES['album_image']) && $_FILES['album_image'] != null && $_FILES['album_image']['error'] == 0)
                    {
                        $oFile = Phpfox::getLib('file');
                        $oImage = phpFox::getLib('image');

                        $aImage = $oFile->load('album_image', array('jpg', 'gif', 'png'));
                        if ($aImage !== false)
                        {
                            // Delete main file.
                            $sPath = PHPFOX_DIR_FILE . 'pic' . PHPFOX_DS . 'musicsharing' . PHPFOX_DS . sprintf($album_info['album_image'], '');
                            is_file($sPath) ? @unlink($sPath) : null;
                            
                            // Delete old images.
                            $aSizes = array(50, 90, 115, 255, 345);
                            foreach($aSizes as $iSize)
                            {
                                $sPath = PHPFOX_DIR_FILE . 'pic' . PHPFOX_DS . 'musicsharing' . PHPFOX_DS . sprintf($album_info['album_image'], '_' . $iSize);
                                is_file($sPath) ? @unlink($sPath) : null;
                                
                                $sPathSquare = PHPFOX_DIR_FILE . 'pic' . PHPFOX_DS . 'musicsharing' . PHPFOX_DS . sprintf($album_info['album_image'], '_' . $iSize . '_square');
                                is_file($sPathSquare) ? @unlink($sPathSquare) : null;
                            }
                            
                            $sPath = PHPFOX_DIR_FILE . 'pic' . PHPFOX_DS . 'musicsharing' . PHPFOX_DS;
                            
                            // Make directory.
                            if (!is_dir($sPath))
                            {
                                @mkdir($sPath, 0777, 1);
                            }

                            // Get url.
                            $sFileName = $oFile->upload('album_image', $sPath, $album_id);

                            foreach($this->_aPhotoSizes as $iSize)
                            {
                                $oImage->createThumbnail($sPath . sprintf($sFileName, ''), $sPath . sprintf($sFileName, '_' . $iSize), $iSize, $iSize);
                            }
                            
                            // Support CDN.
                            $oSerMusic->updateAlbumInfo($album_id, array('album_image' => $sFileName, 'server_id' => $this->request()->getServer('PHPFOX_SERVER_ID')));
                        }
                    }
                    
                    if ($aVals['privacy'] == '4')
                    {
                        phpFox::getService('privacy.process')->update('musicsharing_album', $album_id, (isset($aVals['privacy_list']) ? $aVals['privacy_list'] : array()));
                    }
                    else
                    {
                        phpFox::getService('privacy.process')->delete('musicsharing_album', $album_id);
                    }

                    $result = $album_id;
                    $album_info = $oSerMusic->getAlbumInfo($album_id);
                }
            }
            
            $this->template()->assign(array(
                'sDeleteBlock' => 'dashboard',
                'album_info' => $album_info,
                'aForms' => $album_info,
                'core_path' => phpFox::getParam('core.path'),
                'user_id' => phpFox::getUserId(),
                'result' => $result,
                'mexpect' => "My Albums"
            ));
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
        $catitle = $this->template()->getBreadCrumb();

        // $satitle = isset($catitle[1])?(isset($catitle[1][0])?$catitle[1][0]:(isset($catitle[0][0])?$catitle[0][0]:"")):"";
        if ($aParentModule)
        {
            $this->template()->clearBreadCrumb();
            $this->template()
                    ->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'), $this->url()->makeUrl('musicsharing'))
                    ->setBreadCrumb(phpFox::getPhrase('musicsharing.edit_album'), null, true);
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
        (($sPlugin = Phpfox_Plugin::get('musicsharing.component_controller_edit_clean')) ? eval($sPlugin) : false);
    }

}

?>
