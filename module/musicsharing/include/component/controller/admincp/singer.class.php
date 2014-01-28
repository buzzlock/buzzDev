<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class Musicsharing_Component_Controller_Admincp_Singer extends Phpfox_Component
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
        
        $aParentModule = $this->getParam('aParentModule');
        if ($aParentModule)
        {
            phpFox::getLib('session')->set('pages_msf', $aParentModule);
        }
        else
        {
            phpFox::getLib('session')->remove('pages_msf');
        }

        $this->delete();

        $aListInfo = Phpfox::getService('musicsharing.music')->getSingers();
        
        foreach ($aListInfo as $iKey => $aInfo)
        {
            if (isset($aInfo['singer']))
            {
                foreach($aInfo['singer'] as $i => $aSinger)
                {
                    // Get image path.
                    $sPathImage = PHPFOX_DIR_FILE . 'pic' . PHPFOX_DS . 'musicsharing' . PHPFOX_DS . $aSinger['singer_image'];

                    // Get image to display.
                    $sImage = '';

                    // Check image exist.
                    $bImageExist = true;

                    $sImage = Phpfox::getLib('image.helper')->display(array(
                        'server_id' => $aSinger['server_id'],
                        'thickbox' => true,
                        'path' => 'musicsharing.url_image',
                        'file' => $aSinger['singer_image'],
                        'suffix' => '',
                        'max_width' => Phpfox::getParam('musicsharing.musicsharing_max_image_pic_size'),
                        'max_height' => Phpfox::getParam('musicsharing.musicsharing_max_image_pic_size')
                            )
                    );
                    $aListInfo[$iKey]['singer'][$i]['sImage'] = $sImage;
                    $aListInfo[$iKey]['singer'][$i]['bImageExist'] = $bImageExist;
                }
            }
        }

        $this->template()
                ->setTitle(phpFox::getPhrase('musicsharing.add_singer'))
                ->setHeader('cache', array('quick_edit.js' => 'static_script'));

        $this->template()->assign(array(
            'sDeleteBlock' => 'dashboard',
            'list_info' => $aListInfo,
            'core_path' => phpFox::getParam('core.path'),
            'aSingerTypes' => Phpfox::getService('musicsharing.music')->getSingerTypes()
        ));

        $this->template()->setHeader(array(
            'm2bmusic_tabcontent.js' => 'module_musicsharing',
            'm2bmusic_class.js' => 'module_musicsharing',
            'upload.css' => 'module_musicsharing'
        ));

        $this->save();

        $this->template()->setBreadCrumb(phpFox::getPhrase('musicsharing.singers'), null, true);
    }

    public function delete()
    {
        if ($this->request()->get('task') == "dodelete")
        {
            foreach ($this->request()->getArray('delete_singer') as $iSingerId)
            {
                Phpfox::getService('musicsharing.music')->deleteSinger($iSingerId);
            }

            phpFox::getLib('url')->send('admincp.musicsharing.singer');
        }
    }

    public function save()
    {
        $aValue = $this->request()->getArray('val');

        // Nothing to save.
        if (count($aValue) == 0)
            return;

        if ($aValue['title'] == '')
        {
            return Phpfox_Error::set(PhpFox::getPhrase('musicsharing.please_enter_singer_name'));
        }

        if (intval($aValue['songSingerType']) == 0)
        {
            return Phpfox_Error::set(Phpfox::getPhrase('musicsharing.please_select_singer_type'));
        }

        $sUrl = "";
        if ($iSingerId = Phpfox::getService('musicsharing.music')->addSinger($aValue['title'], $aValue['songSingerType'], $sUrl))
        {
            if (isset($_FILES['singer_image']))
            {
                $image = $_FILES['singer_image'];

                if ($image['name'] != '' && !$this->validateImageType($image['name']))
                {
                    return Phpfox_Error::set(Phpfox::getPhrase('musicsharing.image_upload_is_not_valid'));
                }

                $file_tmp = phpFox::getLib('file')->load('singer_image', array('jpg', 'gif', 'png'));
                $p = PHPFOX_DIR_FILE . 'pic' . PHPFOX_DS . 'musicsharing' . PHPFOX_DS;
                $sFileName = phpFox::getLib('file')->upload('singer_image', $p, $image['name']);
            }
            
            Phpfox::getService('musicsharing.music')->updateSingerImage($iSingerId, array('singer_image' => $sFileName, 'server_id' => $this->request()->getServer('PHPFOX_SERVER_ID')));
            
            $this->url()->send('admincp.musicsharing.singer', null, phpFox::getPhrase('musicsharing.singer_successfully_added') . '.');
        }
    }

    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('musicsharing.component_controller_admincp_singer_clean')) ? eval($sPlugin) : false);
    }

}

?>