<?php

defined('PHPFOX') or exit('NO DICE!');

class musicsharing_Component_Block_newsongs extends Phpfox_Component {

    public function process()
    {
        $bIsProfile = $this->getParam('bIsProfile');
        if ($bIsProfile)
        {
            return false;
        }
        
        $this->template()->assign(array(
            'sHeader' => phpFox::getPhrase('musicsharing.new_songs'),
            'sDeleteBlock' => 'dashboard',
            'aTopSongs' => phpFox::getService('musicsharing.music')->getSongs(0, 10, " " . Phpfox::getT('m2bmusic_album_song') . ".song_id DESC"),
        ));
        return 'block';
    }

    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('musicsharing.component_block_topsongs_clean')) ? eval($sPlugin) : false);
    }

}

?>