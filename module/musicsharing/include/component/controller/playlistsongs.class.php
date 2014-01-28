<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');
?>
<?php

class musicsharing_Component_Controller_Playlistsongs extends Phpfox_Component {

    public function process()
    {
        if ($this->request()->get('playlist'))
        {
            $playlist_id = $this->request()->get('playlist');
        }
        else
        {
            $playlist_id = 0;
        }
        $aParentModule = $this->getParam('aParentModule');
        if ($aParentModule)
        {
            $aParentModule['msf']['editplaylist'] = phpFox::getLib("url")->makeUrl('pages.' . $aParentModule['item_id'] . '.musicsharing.editplaylist.playlist_' . $playlist_id);
            $aParentModule['msf']['playlistsongs'] = phpFox::getLib("url")->makeUrl('pages.' . $aParentModule['item_id'] . '.musicsharing.playlistsongs.playlist_' . $playlist_id);
            phpFox::getLib('session')->set('pages_msf', $aParentModule);
            $this->template()->assign(array(
                'aParentModule' => $aParentModule,
            ));
            phpFox::getLib('session')->set('pages_msf', $aParentModule);
        }
        else
        {
            phpFox::getLib('session')->remove('pages_msf');
        }
        $this->template()->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'), null);
        phpFox::isUser(true);
        if ($this->request()->get('task') == "dodelete")
        {
            foreach ($this->request()->getArray('delete_song') as $sid)
            {
                phpFox::getService('musicsharing.music')->deletePlaylistSong($sid);
            }
        }

        $playlist_info = phpFox::getService('musicsharing.music')->getPlaylistInfo($playlist_id);
        $user_viewer = phpFox::getUserId();
        if ($playlist_info['user_id'] == $user_viewer || phpFox::isAdmin(true))
        {
            $list_total = phpFox::getService('musicsharing.music')->get_total_playlistsong($playlist_id);
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
                    list($order_songs_up, $song_current) = phpFox::getService('musicsharing.music')->getSongUp($song_id, $playlist_id, phpFox::getUserId());
                    if ($order_songs_up['order_id'] <= 0)
                    {
                        phpFox::getService('musicsharing.music')->reOrderPlaylistSong($playlist_id, phpFox::getUserId());
                        list($order_songs_up, $song_current) = phpFox::getService('musicsharing.music')->getSongUp($song_id, $playlist_id, phpFox::getUserId());
                    }
                    phpFox::getService('musicsharing.music')->switchOrderSongs($order_songs_up, $song_current);
                }
                if (!isset($aParentModule))
                {
                    if ($this->request()->get('page'))
                        $this->url()->send('musicsharing.playlistsongs.playlist_' . $playlist_id . '/page_' . $this->request()->get('page'), null, null);
                    else
                        $this->url()->send('musicsharing.playlistsongs.playlist_' . $playlist_id, null, null);
                }
                else
                {
                    if ($this->request()->get('page'))
                    {
                        $this->url()->send($aParentModule['msf']['playlistsongs'] . 'page_' . $this->request()->get('page'), null, null);
                    }
                    else
                    {
                        $this->url()->send($aParentModule['msf']['playlistsongs'], null, null);
                    }
                }
            }
            if ($this->request()->get('ordersongdown'))
            {
                $song_id = $this->request()->get('ordersongdown');
                if ($song_id > 0)
                {
                    list($order_songs_up, $song_current) = phpFox::getService('musicsharing.music')->getSongDown($song_id, $playlist_id, phpFox::getUserId());
                    if ($order_songs_up['order_id'] <= 0)
                    {
                        phpFox::getService('musicsharing.music')->reOrderPlaylistSong($playlist_id, phpFox::getUserId());
                        list($order_songs_up, $song_current) = phpFox::getService('musicsharing.music')->getSongDown($song_id, $playlist_id, phpFox::getUserId());
                    }
                    phpFox::getService('musicsharing.music')->switchOrderSongs($order_songs_up, $song_current);
                }
                if (!isset($aParentModule))
                {
                    if ($this->request()->get('page'))
                        $this->url()->send('musicsharing.playlistsongs.playlist_' . $playlist_id . '/page_' . $this->request()->get('page'), null, null);
                    else
                        $this->url()->send('musicsharing.playlistsongs.playlist_' . $playlist_id, null, null);
                }
                else
                {
                    if ($this->request()->get('page'))
                    {
                        $this->url()->send($aParentModule['msf']['playlistsongs'] . 'page_' . $this->request()->get('page'), null, null);
                    }
                    else
                    {
                        $this->url()->send($aParentModule['msf']['playlistsongs'], null, null);
                    }
                }
            }
            // singer_title, cat_title
            $sSelect = Phpfox::getT('m2bmusic_song_playlist_order') . ".*," . 
                    Phpfox::getT('m2bmusic_album_song') . ".*," . 
                    Phpfox::getT('m2bmusic_playlist_song') . ".song_id AS playlist_song_id," . 
                    Phpfox::getT('m2bmusic_category') . '.title AS cat_title,' . 
                    Phpfox::getT('m2bmusic_singer') . '.title AS singer_title';
            
            $list_info = phpFox::getService('musicsharing.music')->getPlaylistSongs($playlist_id, ($iPage - 1) * $iPageSize, $iPageSize, 'value ASC', $sSelect);
            
            if ($list_info)
            {
                $iTotal = count($list_info);
                
                for ($i = 0; $i < $iTotal; $i++)
                {
                    if ($list_info[$i]['singer_id'] < 1)
                    {
                        $list_info[$i]['singer_title'] = $list_info[$i]['other_singer'];
                    }
                }
            }
            phpFox::getLib('pager')->set(array('page' => $iPage, 'size' => $iPageSize, 'count' => $list_total));

            $this->template()->assign(array('iPage' => $iPage, 'aRows' => $list_info, 'iCnt' => $list_total))
                    ->setHeader('cache', array(
                        'pager.css' => 'style_css'));
            $this->template()->setHeader(array(
                'music.css' => 'module_musicsharing',
                'musicsharing_style.css' => 'module_musicsharing',
                'suppress_menu.css' => 'module_musicsharing',
            ));

            $pages_msf = phpFox::getLib('session')->get('pages_msf');
            $this->template()->assign(array(
                'sDeleteBlock' => 'dashboard',
                'playlist_id' => $playlist_id,
                'playlist_info' => $playlist_info,
                "pages_msf" => phpFox::getLib('session')->get('pages_msf'),
                "aParentModule" => $aParentModule,
                "url_msf" => $pages_msf['url'] . "musicsharing/song",
                'list_info' => $list_info,
                'core_path' => phpFox::getParam('core.path'),
                'cur_page' => $this->request()->get('page') <= 0 ? 1 : $this->request()->get('page'),
                'max_page' => $max_page,
                'mexpect' => "My Playlists",
            ));
        }

        //modified section (v 300b1)
        //build filter menu
        phpFox::getService('musicsharing.music')->getSectionMenu($aParentModule);
        $catitle = $this->template()->getBreadCrumb();

        if (!$aParentModule)
        {
            $satitle = phpFox::getPhrase("musicsharing.edit_playlist"); //isset($catitle[1][0])?$catitle[1][0]:$catitle[0][0];
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
        (($sPlugin = Phpfox_Plugin::get('musicsharing.component_controller_playlistsongs_clean')) ? eval($sPlugin) : false);
    }

}
