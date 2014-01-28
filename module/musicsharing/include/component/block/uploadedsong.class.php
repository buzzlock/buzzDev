<?php

defined('PHPFOX') or exit('NO DICE!');

class musicsharing_Component_Block_Uploadedsong extends Phpfox_Component {

    public function process() {
        $prefix = phpFox::getParam(array('db', 'prefix'));
		$aParentModule = $this->getParam('aParentModule');
        $a = phpFox::getPhrase('musicsharing.uploaded_song');
        $album_id = $this->getParam('album_id');

        if (!$album_id || $album_id <= 0) {
            return false;
        }
		$where = " ".$prefix."m2bmusic_album_song.album_id = $album_id";
		$where .= " OR ".$prefix."m2bmusic_album.album_id = $album_id";
        $uploadedSong = phpFox::getService('musicsharing.music')->getSongs(null, null, " " . $prefix . "m2bmusic_album_song.play_count DESC", null, $where . $album_id);
        $this->template()->assign(array(
            'sHeader' => $a,
            'sDeleteBlock' => 'dashboard',
            'uploadedSong' => $uploadedSong,
            'aParentModule' => $aParentModule,
        ));
        return 'block';
    }

    public function clean() {
        (($sPlugin = Phpfox_Plugin::get('musicsharing.component_block_topsongs_clean')) ? eval($sPlugin) : false);
    }

}

?>