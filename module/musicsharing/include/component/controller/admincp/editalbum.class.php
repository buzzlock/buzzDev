<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class Musicsharing_Component_Controller_Admincp_EditAlbum extends Phpfox_Component
{

    /**
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
        phpFox::isUser(true);
        $aParentModule = $this->getParam('aParentModule');
        if ($aParentModule)
        {
            phpFox::getLib('session')->set('pages_msf', $aParentModule);
        }
        else
        {
            phpFox::getLib('session')->remove('pages_msf');
        }
        $user_group_id = phpFox::getService('musicsharing.cart.account')->getValueUserGroupId(phpFox::getUserId());
        $selling_settings = phpFox::getService("musicsharing.cart.music")->getSettingsSelling($user_group_id);


        $settings = phpFox::getService('musicsharing.music')->getUserSettings(phpFox::getUserId());
        $this->template()->assign(array('settings' => $settings));

        if ($this->request()->get('album'))
        {
            $album_id = $this->request()->get('album');
        }
        else
        {
            $album_id = 0;
        }
        $album_info = phpFox::getService('musicsharing.music')->getAlbumInfo($album_id);

        $user_viewer = phpFox::getUserId();
        if ($album_info['user_id'] == $user_viewer || phpFox::isAdmin(true))
        {
            $result = 0;
            $this->template()->setHeader(array(
                'm2bmusic_tabcontent.js' => 'module_musicsharing',
                'm2bmusic_class.js' => 'module_musicsharing',
                'music.css' => 'module_musicsharing'
            ));
            $this->template()->assign(array(
                'album_info' => $album_info
            ));
            if (isset($_POST['submit']))
            {
                $url = $album_info['album_image'];
                if (isset($_FILES['album_image']) && $_FILES['album_image'] != null && $_FILES['album_image']['error'] == 0)
                {
                    $image = $_FILES['album_image'];
                    if (in_array($image, array("image/gif", "image/jpeg", "image/png")))
                    {
                        Phpfox_Error::set('Invalid file image type!');
                        return false;
                    }
                    $file_tmp = phpFox::getLib('file')->load('album_image', array('jpg', 'gif', 'png'));
                    $p = PHPFOX_DIR_FILE . PHPFOX_DS . 'pic' . PHPFOX_DS . 'musicsharing' . PHPFOX_DS;
                    if (!is_dir($p))
                    {
                        if (!@mkdir($p, 0777, 1))
                        {
                            //$log->lwrite('error create path');
                        }
                    }
                    $url = phpFox::getLib('file')->upload('album_image', $p, $image['name']);

                    $org_url = $p . PHPFOX_DS . $url;
                    /**
                     * @see Phpfox_Image
                     */
                    $oImage = phpFox::getLib('image');
                    if ($oImage->createThumbnail($p . PHPFOX_DS . sprintf($url, ''), $p . PHPFOX_DS . sprintf($url, '_' . 'thumb'), 112, 150) === false)
                    {
                        $url = sprintf($url, '');
                    }
                    else
                    {
                        $url = sprintf($url, '_thumb');
                    }
                }
                $title = "";
                $aVals = $this->request()->getArray('val');
                if ($aVals['title'] != "")
                {
                    $title = $aVals['title'];
                }
                if (trim($title) == "")
                {
                    return Phpfox_Error::set('Please enter album name!');
                }
                $description = "";
                if ($aVals['description'] != "")
                {
                    $description = $aVals['description'];
                }
                $price = 0;
                if ($aVals['price'] != "")
                {
                    $price = (float) $aVals['price'];
                    $price = round($price, 2);
                }
                $search = 1;
                $download = 1;
                if ($_POST['search'])
                    $search = $_POST['search'];
                else
                    $search = 0;
                if ($_POST['is_download'])
                    $download = $_POST['is_download'];
                else
                    $download = 0;
                $currentDate = date("Y-m-d H:i:s");
                $album = array();
                $album['album_id'] = $album_id;
                $album['title'] = substr($title, 0, 50);
                $album['title_url'] = $title;
                $album['album_image'] = $url;
                $album['description'] = $description;
                $album['search'] = $search;
                $album['is_download'] = $download;
                $album['modified_date'] = $currentDate;

                $album['price'] = $price;
                if (!is_numeric($price) || $price < 0)
                {
                    Phpfox_Error::set('Invalid number price!');
                    return false;
                }
                $album_id = phpFox::getService('musicsharing.music')->editAlbum($album);
                $result = $album_id;
                $album_info = phpFox::getService('musicsharing.music')->getAlbumInfo($album_id);
            }
            $this->template()->assign(array(
                'sDeleteBlock' => 'dashboard',
                'album_info' => $album_info,
                'core_path' => phpFox::getParam('core.path'),
                'user_id' => phpFox::getUserId(),
                'min_price_song' => $selling_settings['min_price_song'],
                'selling_settings' => $selling_settings,
                'result' => $result,
                'currency' => phpFox::getService('core.currency')->getDefault(),
            ));
        }
        else
        {
            $this->url()->send('subscribe', null, null);
        }
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
