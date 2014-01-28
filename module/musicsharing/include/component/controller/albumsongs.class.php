<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');
?>
<?php

class musicsharing_Component_Controller_Albumsongs extends Phpfox_Component {

    public function process()
    {
        phpFox::isUser(true);
        $aParentModule = $this->getParam('aParentModule');
        if ($this->request()->get('album'))
        {
            $album_id = $this->request()->get('album');
        }
        else
        {
            $album_id = 0;
        }
        if ($aParentModule)
        {
            phpFox::getLib('session')->set('pages_msf', $aParentModule);
            $aParentModule['msf']['editalbum'] = phpFox::getLib("url")->makeUrl('pages.' . $aParentModule['item_id'] . '.musicsharing.editalbum.album_' . $album_id);
            $aParentModule['msf']['albumsongs'] = phpFox::getLib("url")->makeUrl('pages.' . $aParentModule['item_id'] . '.musicsharing.albumsongs.album_' . $album_id);
            phpFox::getLib('session')->set('pages_msf', $aParentModule);
            $this->template()->assign(array(
                'aParentModule' => $aParentModule,
            ));
        }
        else
        {
            phpFox::getLib('session')->remove('pages_msf');
        }
        phpFox::isUser(true);
        $prefix = phpFox::getParam(array('db', 'prefix'));
        $settings = phpFox::getService('musicsharing.music')->getUserSettings(phpFox::getUserId());
        $this->template()->assign(array('settings' => $settings));

        if (isset($_POST['task']) && $_POST['task'] == "dodelete")
        {
            if (isset($_POST['delete_song']))
            {
                foreach ($_POST['delete_song'] as $sid)
                {
                    phpFox::getService('musicsharing.music')->deleteAlbumSong($sid);
                }
            }
        }
        if (isset($_POST['task']) && $_POST['task'] == "editsong")
        {
            $other_singer = "";
            $singer_id = 0;
            $title = "";
            $category = 0;
            if (isset($_POST['check_other_singer']))
            {
                $other_singer = $_POST['songSingerName'];
                $singer_id = 0;
            }
            else
            {
                $other_singer = "";
                $singer_id = $_POST['songSinger'];
            }
            $title = $_POST['songTitle'];
            $lyric = $_POST['songLyric'];
            $privacy = $_POST['privacy'];
            if ($title == "")
                $title = "Not Updated";
            $category = $_POST['songCat'];
            $song_id = $_POST['song_id'];
            $song = array();
            $song['title'] = $title;
            $song['title_url'] = $title;
            $song['singer_id'] = $singer_id;
            $song['other_singer'] = $other_singer;
            $song['cat_id'] = $category;
            $song['song_id'] = $song_id;
            $song['lyric'] = $lyric;
            $song['privacy'] = $privacy;
            phpFox::getService('musicsharing.music')->updateAlbumSong($song);
        }

        $album_info = phpFox::getService('musicsharing.music')->getAlbumInfo($album_id);
        $user_viewer = phpFox::getUserId();
        if ($album_info['user_id'] == $user_viewer || phpFox::isAdmin(true))
        {
            $where = " " . $prefix . "m2bmusic_album_song.album_id = $album_id";
            $list_total = phpFox::getService('musicsharing.music')->get_total_song($where);

            $iPageSize = 10;
            $iPage = $this->request()->get("page");
            if (!$iPage)
                $iPage = 1;
            $max_page = floor($list_total / $iPageSize) + 1;
            if ($iPage > $max_page)
                $iPage = $max_page;
            //order song in playlist
            if ($this->request()->get('ordersongup'))
            {
                $song_id = $this->request()->get('ordersongup');
                if ($song_id > 0)
                {
                    list($order_songs_up, $song_current) = phpFox::getService('musicsharing.music')->getSongUp($song_id, $album_id, phpFox::getUserId(), false);

                    if ($order_songs_up['order_id'] <= 0)
                    {

                        phpFox::getService('musicsharing.music')->reOrderPlaylistSong($album_id, phpFox::getUserId(), false);
                        list($order_songs_up, $song_current) = phpFox::getService('musicsharing.music')->getSongUp($song_id, $album_id, phpFox::getUserId(), false);
                    }

                    phpFox::getService('musicsharing.music')->switchOrderSongs($order_songs_up, $song_current);
                }
                if (!isset($aParentModule))
                {
                    if ($this->request()->get('page'))
                        $this->url()->send('musicsharing.albumsongs.album_' . $album_id . '/page_' . $this->request()->get('page'), null, null);
                    else
                        $this->url()->send('musicsharing.albumsongs.album_' . $album_id, null, null);
                }
                else
                {
                    if ($this->request()->get('page'))
                    {
                        $this->url()->send($aParentModule['msf']['albumsongs'] . '/page_' . $this->request()->get('page'), null, null);
                    }
                    else
                    {
                        $this->url()->send($aParentModule['msf']['albumsongs'], null, null);
                    }
                }
            }
            if ($this->request()->get('ordersongdown'))
            {
                $song_id = $this->request()->get('ordersongdown');
                if ($song_id > 0)
                {
                    list($order_songs_up, $song_current) = phpFox::getService('musicsharing.music')->getSongDown($song_id, $album_id, phpFox::getUserId(), false);


                    if ($order_songs_up['order_id'] <= 0)
                    {

                        phpFox::getService('musicsharing.music')->reOrderPlaylistSong($album_id, phpFox::getUserId(), false);
                        list($order_songs_up, $song_current) = phpFox::getService('musicsharing.music')->getSongDown($song_id, $album_id, phpFox::getUserId(), false);
                    }
                    phpFox::getService('musicsharing.music')->switchOrderSongs($order_songs_up, $song_current);
                }
                if (!isset($aParentModule))
                {
                    if ($this->request()->get('page'))
                        $this->url()->send('musicsharing.albumsongs.album_' . $album_id . '/page_' . $this->request()->get('page'), null, null);
                    else
                        $this->url()->send('musicsharing.albumsongs.album_' . $album_id, null, null);
                }
                else
                {
                    if ($this->request()->get('page'))
                    {
                        $this->url()->send($aParentModule['msf']['albumsongs'] . 'page_' . $this->request()->get('page'), null, null);
                    }
                    else
                    {
                        $this->url()->send($aParentModule['msf']['albumsongs'], null, null);
                    }
                }
            }
            //end
            $select = phpFox::getParam(array('db', 'prefix')) . "m2bmusic_song_playlist_order.*," . phpFox::getParam(array('db', 'prefix')) . "m2bmusic_album_song.*, " . phpFox::getParam(array('db', 'prefix')) . "m2bmusic_album.album_id," . phpFox::getParam(array('db', 'prefix')) . "m2bmusic_album.is_download, " . phpFox::getParam(array('db', 'prefix')) . "m2bmusic_album.title as album_title, " . phpFox::getParam(array('db', 'prefix')) . "m2bmusic_singer.title as singer_title, " . phpFox::getParam(array('db', 'prefix')) . "m2bmusic_singer.singer_id," . phpFox::getParam(array('db', 'prefix')) . "user.*," . phpFox::getParam(array('db', 'prefix')) . "m2bmusic_category.title as cat_title";
            $where .= " OR " . $prefix . "m2bmusic_album.album_id = $album_id";
            $list_info = phpFox::getService('musicsharing.music')->getSongs(($iPage - 1) * $iPageSize, $iPageSize, 'value ASC', $select, $where, $album_id);
            phpFox::getLib('pager')->set(array('page' => $iPage, 'size' => $iPageSize, 'count' => $list_total));

            $this->template()->assign(array('iPage' => $iPage, 'aRows' => $list_info, 'iCnt' => $list_total))
                    ->setHeader('cache', array(
                        'pager.css' => 'style_css',
                        'suppress_menu.css' => 'module_musicsharing',
                    ));
            $this->template()->setHeader(array(
                'music.css' => 'module_musicsharing',
                'upload.css' => 'module_musicsharing',
                'musicsharing_style.css' => 'module_musicsharing',
            ));
            $this->template()->assign(array(
                'sDeleteBlock' => 'dashboard',
                'album_id' => $album_id,
                'album_info' => $album_info,
                'list_info' => $list_info,
                'page' => $iPage,
                'cur_page' => $iPage,
                'max_page' => $max_page,
                'core_path' => phpFox::getParam('core.path'),
                'mexpect' => "My Albums",
            ));
        }

        //modified section (v 300b1)
        //build filter menu
        phpFox::getService('musicsharing.music')->getSectionMenu($aParentModule);
        $catitle = $this->template()->getBreadCrumb();
        if (!$aParentModule)
        {
            $satitle = phpFox::getPhrase("musicsharing.edit_album"); //isset($catitle[1][0])?$catitle[1][0]:$catitle[0][0];
            $this->template()->clearBreadCrumb();
            $this->template()
                    ->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'), $this->url()->makeUrl('musicsharing'))
                    ->setBreadCrumb("", null, true)
                    ->setBreadCrumb($satitle, null, false);
        }
        else
        {
            $this->template()->clearBreadCrumb();
            $this->template()
                    ->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'), $this->url()->makeUrl('musicsharing'), false);
        }
        ///modified section (v 300b1)
    }

    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('musicsharing.component_controller_albumsongs_clean')) ? eval($sPlugin) : false);
    }

}
