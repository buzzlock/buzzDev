<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class musicsharing_Component_Controller_Myalbums extends Phpfox_Component {

    /**
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
        Phpfox::getLib('setting')->setParam('musicsharing.url_image', Phpfox::getParam('core.url_pic') . 'musicsharing' . PHPFOX_DS);
        phpFox::isUser(true);
        $aParentModule = $this->getParam('aParentModule');
        if ($aParentModule)
        {
            phpFox::getLib('session')->set('pages_msf', $aParentModule);
            if (!phpFox::getService('pages')->hasPerm($aParentModule['item_id'], 'musicsharing.can_manage_album'))
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
        $settings = phpFox::getService('musicsharing.music')->getUserSettings(phpFox::getUserId());
        
        $this->template()->assign(array('settings' => $settings)); {
            if (isset($_POST['task']) && $_POST['task'] == "dodelete")
            {
                foreach ($_POST['delete_album'] as $aid)
                {
                    phpFox::getService('musicsharing.music')->deleteAlbum($aid);
                }
            }
        }
        if ($this->request()->get("orderalbum"))
        {
            $id_album = $this->request()->get("orderalbum");
            $album_current = phpFox::getService('musicsharing.music')->getAlbums_Id($id_album, phpFox::getUserId());
            $order_album_current = $album_current["order_id"];
            $album_before = phpFox::getService('musicsharing.music')->getAlbumsBefore_Id($id_album, phpFox::getUserId());
            $order_album_before = $album_before["order_id"];
            if ($order_album_before)
            {
                $id_album_before = $album_before["album_id"];
                phpFox::getService('musicsharing.music')->updateAlbum($id_album, $order_album_before);
                phpFox::getService('musicsharing.music')->updateAlbum($id_album_before, $order_album_current);
            }
            if ($this->request()->get('page') > 0)
                $this->url()->send('musicsharing.myalbums.page_' . $this->request()->get('page'), null, null, null);
            else
                $this->url()->send('musicsharing.myalbums', null, null, null);
        }
        else if ($this->request()->get("orderalbumdown"))
        {
            $id_album = $this->request()->get("orderalbumdown");
            $album_current = phpFox::getService('musicsharing.music')->getAlbums_Id($id_album, phpFox::getUserId());
            $order_album_current = $album_current["order_id"];
            $album_after = phpFox::getService('musicsharing.music')->getAlbumsAfter_Id($id_album, phpFox::getUserId());
            $order_album_after = $album_after["order_id"];
            if ($order_album_after)
            {
                $id_album_after = $album_after["album_id"];
                phpFox::getService('musicsharing.music')->updateAlbum($id_album, $order_album_after);
                phpFox::getService('musicsharing.music')->updateAlbum($id_album_after, $order_album_current);
            }
            if ($this->request()->get('page') > 0)
                $this->url()->send('musicsharing.myalbums.page_' . $this->request()->get('page'), null, null, null);
            else
                $this->url()->send('musicsharing.myalbums', null, null, null);
        }
        $user_id = phpFox::getUserId();
        $where = " " . $prefix . "m2bmusic_album.user_id = $user_id";
        $list_total = phpFox::getService('musicsharing.music')->get_total_album($where);
        
        $aGlobalSettings = phpFox::getService('musicsharing.music')->getUserSettings(phpFox::getUserId(), false);
        $iPageSize = isset($aGlobalSettings['number_album_per_page']) ? $aGlobalSettings['number_album_per_page'] : 5;
        $iPage = $this->request()->get("page");
        if (!$iPage)
            $iPage = 1;
        $max_page = floor($list_total / $iPageSize) + 1;
        if ($iPage > $max_page)
            $iPage = $max_page;
        $sort_by = $prefix . 'm2bmusic_album.order_id';
        $list_info = phpFox::getService('musicsharing.music')->getAlbums(($iPage - 1) * $iPageSize, $iPageSize, null, null, $where);
        phpFox::getLib('pager')->set(array('page' => $iPage, 'size' => $iPageSize, 'count' => $list_total));

        $this->template()->assign(array('iPage' => $iPage, 'aRows' => $list_info, 'iCnt' => $list_total))
                ->setHeader('cache', array(
                    'pager.css' => 'style_css'));
        $this->template()->assign(array(
            'sDeleteBlock' => 'dashboard',
            'list_info' => $list_info,
            'core_path' => phpFox::getParam('core.path'),
            'user_id' => phpFox::getUserId(),
            'total_album' => $list_total,
            'cur_page' => $this->request()->get('page') <= 0 ? 1 : $this->request()->get('page')
        ));
        $this->template()->setHeader(array(
            'm2bmusic_tabcontent.js' => 'module_musicsharing',
            'm2bmusic_class.js' => 'module_musicsharing',
            'music.css' => 'module_musicsharing',
            'musicsharing_style.css' => 'module_musicsharing',
            'mobile.css' => 'module_musicsharing'
        ));

        //modified section (v 300b1)
        //build filter menu
        phpFox::getService('musicsharing.music')->getSectionMenu($aParentModule);
        $catitle = $this->template()->getBreadCrumb();

        if (!$aParentModule)
        {
            $satitle = isset($catitle[1][0]) ? $catitle[1][0] : $catitle[0][0];
            $this->template()->clearBreadCrumb();
            $this->template()
                    ->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'), $this->url()->makeUrl('musicsharing'))
                    ->setBreadCrumb($satitle, null)
                    ->setBreadCrumb($satitle, null, true);
        }
        else
        {
            $this->template()->clearBreadCrumb();
            $this->template()
                    ->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'), $this->url()->makeUrl('musicsharing'), false);
        }
        ///modified section (v 300b1)
    }

    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('musicsharing.component_controller_myalbums_clean')) ? eval($sPlugin) : false);
    }

}

?>