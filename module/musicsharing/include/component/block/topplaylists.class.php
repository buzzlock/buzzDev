<?php

defined('PHPFOX') or exit('NO DICE!');

class musicsharing_Component_Block_TopPlaylists extends Phpfox_Component {

    public function process()
    {
        Phpfox::getLib('setting')->setParam('musicsharing.url_image', Phpfox::getParam('core.url_pic') . 'musicsharing' . PHPFOX_DS);
        $prefix = phpFox::getParam(array('db', 'prefix'));
        
        $pls = phpFox::getService('musicsharing.music')->getPlaylists(0, Phpfox::getParam('musicsharing.mobile_view_amount_of_item_per_page_in_top_playlists'), $prefix . "m2bmusic_playlist.play_count desc", "", "Where search = 1 AND (SELECT count(*) FROM " . $prefix . "m2bmusic_playlist_song WHERE " . $prefix . "m2bmusic_playlist_song.playlist_id = " . $prefix . "m2bmusic_playlist.playlist_id) > 0");
        if (!count($pls))
            return false;
        $aParentModule = phpFox::getLib('session')->get('pages_msf');
        if ($aParentModule === false)
        {
            $aParentModule = NULL;
        }
        $aMenus = array(
            phpFox::getPhrase('musicsharing.new_playlists') => '#musicsharing.musicnewplaylists?id=js_newplaylists_container',
            phpFox::getPhrase('musicsharing.top_playlists') => '#musicsharing.musictopplaylists?id=js_topplaylists_container',
        );

        if (!phpfox::isMobile())
        {
            $this->template()->assign(array(
                'sHeader' => phpFox::getPhrase('musicsharing.top_playlist')
            ));
        }
        $this->template()->assign(array(
            'sDeleteBlock' => 'dashboard',
            'core_path' => phpFox::getParam('core.path'),
            'aNewPlaylists' => $pls,
            'aParentModule' => $aParentModule,
            'iCount' => count($pls),
            "isOdd" => (count($pls) % 2),
        ));

        return 'block';
    }

    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('musicsharing.component_block_newplaylists_clean')) ? eval($sPlugin) : false);
    }

}

?>