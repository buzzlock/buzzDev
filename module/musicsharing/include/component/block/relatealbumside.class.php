<?php

defined('PHPFOX') or exit('NO DICE!');

class musicsharing_Component_Block_Relatealbumside extends Phpfox_Component {

    public function process()
    {
        Phpfox::getLib('setting')->setParam('musicsharing.url_image', Phpfox::getParam('core.url_pic') . 'musicsharing' . PHPFOX_DS);
        
        $albumId = $this->request()->get('album');
        $musicId = $this->request()->get('music');
        if ($albumId || $musicId)
        {
            $table = phpFox::getT('m2bmusic_album');
            $aAlbums = null;
            if ($albumId)
            {
                $album_info = phpFox::getService('musicsharing.music')->getAlbumInfo($albumId);
                $where = " $table.user_id = " . $album_info["user_id"];
                $where .= " AND $table.album_id <> " . (isset($albumId) ? $albumId : 0);
                $aAlbums = phpFox::getService('musicsharing.music')->getAlbums(null, $limit = 7, null, null, $where);
            }
            else
            {
                $music_info = phpFox::getService('musicsharing.music')->song_track_info($musicId);
                $album_info = phpFox::getService('musicsharing.music')->getAlbumInfo($music_info['album_id']);
                $where = " $table.user_id = " . $album_info["user_id"]; //phpFox::getUserId();
                $where .= " AND $table.album_id <> " . $album_info["album_id"];
                $aAlbums = phpFox::getService('musicsharing.music')->getAlbums(null, $limit = 7, null, null, $where);
            }
                        
            if (!count($aAlbums))
            {
                return "block";
            }
            $this->template()->assign(array(
                'sHeader' => phpFox::getPhrase('musicsharing.related_albums'),
                'sDeleteBlock' => 'dashboard',
                'aAlbums' => $aAlbums
            ));
        }
        return 'block';
    }

    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('musicsharing.component_block_topsongs_clean')) ? eval($sPlugin) : false);
    }

}

?>