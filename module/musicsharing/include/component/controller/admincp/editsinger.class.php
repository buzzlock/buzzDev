<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class Musicsharing_Component_Controller_Admincp_Editsinger extends Phpfox_Component
{

    public function validateImageType($sFileName)
    {
        $aImageExtensionsAllowed = array('jpg', 'jpeg', 'png', 'gif','bmp');

        $aPathInfo = pathinfo($sFileName);

        if (!isset($aPathInfo['extension']))
        {
            return false;
        }

        if (in_array($aPathInfo['extension'], $aImageExtensionsAllowed))
        {
            return true;
        }

        return false;
    }

    /**
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
        Phpfox::getLib('setting')->setParam('musicsharing.url_image', Phpfox::getParam('core.url_pic') . 'musicsharing' . PHPFOX_DS);
        
        PhpFox::isUser(true);

        $aParentModule = $this->getParam('aParentModule');
        if ($aParentModule)
        {
            phpFox::getLib('session')->set('pages_msf', $aParentModule);
        }
        else
        {
            phpFox::getLib('session')->remove('pages_msf');
        }

        $this->save();

        $this->template()->assign(array(
            'sDeleteBlock' => 'dashboard',
            'core_path' => phpFox::getParam('core.path'),
            'user_id' => phpFox::getUserId(),
            'aSingerTypes' => Phpfox::getService('musicsharing.music')->getSingerTypes()
        ));

        $this->template()->setHeader(array(
            'm2bmusic_tabcontent.js' => 'module_musicsharing',
            'm2bmusic_class.js' => 'module_musicsharing'
        ));

        // Set javascript for singer.
        $this->template()->setHeader(array('singer.js' => 'module_musicsharing'));
        $this->template()->setPhrase(array('musicsharing.are_you_sure'));

        $iSingerId = $this->request()->getInt('singerid');

        // Get singer info.
        $aSingerInfo = Phpfox::getService('musicsharing.music')->getSingerInfo($iSingerId);
        
        // Get image to display.
        $sImage = '';

        // Check image exist.
        $bImageExist = false;
        if (isset($aSingerInfo['singer_image']))
        {
            $bImageExist = true;
            
            $sImage = Phpfox::getLib('image.helper')->display(array(
                'server_id' => $aSingerInfo['server_id'],
                'thickbox' => true,
                'path' => 'musicsharing.url_image',
                'file' => $aSingerInfo['singer_image'],
                'suffix' => '',
                'max_width' => Phpfox::getParam('musicsharing.musicsharing_max_image_pic_size'),
                'max_height' => Phpfox::getParam('musicsharing.musicsharing_max_image_pic_size')
                    )
            );
        }
        $this->template()->setBreadCrumb(phpFox::getPhrase('musicsharing.singers'), null, true);

        $this->template()->assign(array(
            'singer_info' => $aSingerInfo,
            'bImageExist' => $bImageExist,
            'sImage' => $sImage
        ));
    }

    /**
     * Save post data.
     * @return type
     */
    public function save()
    {
        if ($this->request()->get('submit') == '')
            return;

        $iSingerId = $this->request()->getInt('singerid');

        // Get singer info.
        $aSingerInfo = Phpfox::getService('musicsharing.music')->getSingerInfo($iSingerId);

        // Validate singer.
        if (count($aSingerInfo) == 0)
        {
            return Phpfox_Error::set(Phpfox::getPhrase('musicsharing.song_is_not_valid'));
        }

        $aVals = $this->request()->getArray('val');

        // Validate title.
        if (!isset($aVals['title']) || trim($aVals['title']) == '')
        {
            return Phpfox_Error::set(PhpFox::getPhrase('musicsharing.please_enter_singer_name'));
        }

        $aSinger = array(
            'singer_id' => $aSingerInfo['singer_id'],
            'title' => trim($aVals['title']),
            'title_url' => trim($aVals['title']),
            'singer_type' => $this->request()->getInt('songSingerType')
        );
        
        $bUpdate = Phpfox::getService('musicsharing.music')->editSinger($aSinger);
        
        if ($bUpdate)
        {
            if (isset($_FILES['singer_image']) && $_FILES['singer_image'] != null && $_FILES['singer_image']['error'] == 0)
            {
                $image = $_FILES['singer_image'];
                if ($image['name'] != '' && !$this->validateImageType($image['name']))
                {
                    return Phpfox_Error::set(Phpfox::getPhrase('musicsharing.image_upload_is_not_valid'));
                }
                
                $file_tmp = Phpfox::getLib('file')->load('singer_image', array('jpg', 'gif', 'png'));
                $p = PHPFOX_DIR_FILE . 'pic' . PHPFOX_DS . 'musicsharing' . PHPFOX_DS;
                $sFileName = Phpfox::getLib('file')->upload('singer_image', $p, $image['name']);
                
                Phpfox::getService('musicsharing.music')->updateSingerImage($iSingerId, array('singer_image' => $sFileName, 'server_id' => $this->request()->getServer('PHPFOX_SERVER_ID')));
            }
            
            $this->url()->send('admincp.musicsharing.singer', null, Phpfox::getPhrase('musicsharing.singer_successfully_edited'));
        }
    }

    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('musicsharing.component_controller_admincp_editsinger_clean')) ? eval($sPlugin) : false);
    }

}

?>
