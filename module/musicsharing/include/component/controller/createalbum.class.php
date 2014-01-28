<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');
?>
<?php

class musicsharing_Component_Controller_Createalbum extends Phpfox_Component
{
    private $_aPhotoSizes = array(50, 90, 115, 255, 345);
    
    private function isValidate($value)
    {
        phpFox::isUser(true);

        $strErr = "";
        if (empty($value['title']))
            $strErr .= Phpfox::getPhrase('musicsharing.please_enter_album_name') . '<br />';
        
        if (empty($value['price']))
            $value['price'] = 0;
        
        if (!is_numeric($value['price']) || $value['price'] < 0)
        {
            $strErr .= Phpfox::getPhrase('musicsharing.price_of_album_is_not_valid');
        }
        
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
     * @see Phpfox_Parse_Format
     * @see Phpfox_File
     * @return boolean
     */
    public function process()
    {
        $oSerMusic = phpFox::getService('musicsharing.music');
        
        $aParentModule = $this->getParam('aParentModule');
        if ($aParentModule)
        {
            phpFox::getLib('session')->set('pages_msf', $aParentModule);
            if (!phpFox::getService('pages')->hasPerm($aParentModule['item_id'], 'musicsharing.can_create_album'))
            {
                $this->url()->send("subscribe");
            }
        }
        else
        {
            phpFox::getLib('session')->remove('pages_msf');
        }

        $this->template()->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'), null);
        
        $user_id = phpFox::getUserId();
        $where = " " . Phpfox::getT('m2bmusic_album') . ".user_id = $user_id";
        $list_total = $oSerMusic->get_total_album($where);
        $settings = $oSerMusic->getUserSettings(phpFox::getUserId());
        $this->template()->assign(array('settings' => $settings, 'total_album' => $list_total));

        $this->template()->setHeader(array(
            'music.css' => 'module_musicsharing',
            'm2bmusic_tabcontent.js' => 'module_musicsharing',
            'm2bmusic_class.js' => 'module_musicsharing',
            'music.css' => 'module_musicsharing',
            'suppress_menu.css' => 'module_musicsharing',
        ));
        
        $this->template()->assign(array(
            'sDeleteBlock' => 'dashboard',
            'core_path' => phpFox::getParam('core.path')
        ));
        phpFox::isUser(true);
        $settings = $oSerMusic->getUserSettings(phpFox::getUserId());
        
        if (isset($_POST['submit']))
        {
            $aVals = $this->request()->getArray('val');
            
            if (!isset($aVals['title']) || Phpfox::getLib('parse.format')->isEmpty($aVals['title']))
            {
                return Phpfox_Error::set(phpFox::getPhrase('musicsharing.please_enter_album_name') . '!');
            }
            
            if ($settings['max_album_created'] <= $list_total)
            {
                if ($aParentModule)
                {
                    $this->url()->send('pages.' . $aParentModule['item_id'] . '.musicsharing.myalbums', null, null);
                }
                else
                {
                    $this->url()->send('musicsharing.myalbums', null, null);
                }
            }
            $errorValidate = $this->isValidate($aVals);
            if ($errorValidate != "")
            {
                $this->template()->assign(array(
                    'title' => $aVals['title'],
                    'description' => $aVals['description'],
                    'mexpect' => true,
                    'privacy' => $aVals['privacy']
                ));
                
                Phpfox_Error::set($errorValidate);
                
                return FALSE;
            }
                        
            $sFileName = "";
            $title = trim($aVals['title']);
            $description = trim($aVals['description']);
            $privacy = $aVals['privacy'];
            $privacy_comment = $aVals['privacy_comment'];
            $currentDate = date("Y-m-d H:i:s");
            
            $album_id = $oSerMusic->createAlbum(array(
                'title' => $title,
                'title_url' => $title,
                'user_id' => phpFox::getUserId(),
                'album_image' => $sFileName,
                'description' => $description,
                'search' => $this->request()->getInt('search', 0),
                'is_download' => $this->request()->getInt('is_download'),
                'creation_date' => $currentDate,
                'modified_date' => $currentDate,
                'privacy' => $privacy,
                'privacy_comment' => $privacy_comment
            ));
            
            if (isset($_FILES['album_image']) && trim($_FILES['album_image']['name']) != '')
            {
                $oFile = Phpfox::getLib('file');
                $oImage = phpFox::getLib('image');
                
                $aImage = $oFile->load('album_image', array('jpg', 'gif', 'png'));
                if ($aImage !== false)
                {
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

            $oSerMusic->updateOrderAlbum(phpFox::getUserId());

            $aAlbum = $oSerMusic->getAlbumInfo($album_id);
            
            $aAlbum['name'] = $aAlbum['title'];
            $aAlbum['name_url'] = $aAlbum['title_url'];

            if ($aVals['privacy'] == '4')
            {
                phpFox::getService('privacy.process')->add('musicsharing_album', $album_id, (isset($aVals['privacy_list']) ? $aVals['privacy_list'] : array()));
            }

            if ($aParentModule)
            {
                $this->url()->send('pages.' . $aParentModule['item_id'] . '.musicsharing.editalbum.album_' . $album_id, null);
            }
            else
            {
                $this->url()->send('musicsharing.editalbum.album_' . $album_id, null);
            }
        }
        $this->template()
                ->setHeader(array('musicsharing_style.css' => 'module_musicsharing'))
                ->clearBreadCrumb();

        $oSerMusic->getSectionMenu($aParentModule);
        
        if (!$aParentModule)
        {
            $this->template()->clearBreadCrumb();
            $this->template()
                    ->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'), $this->url()->makeUrl('musicsharing'))
                    ->setBreadCrumb(phpFox::getPhrase('musicsharing.create_album'), null)
                    ->setBreadCrumb(phpFox::getPhrase('musicsharing.create_album'), null, true);
        }
        else
        {
            $this->template()->clearBreadCrumb();
            $this->template()->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'), $this->url()->makeUrl('musicsharing'), false);
        }
    }

}