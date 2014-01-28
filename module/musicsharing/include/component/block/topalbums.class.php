<?php
defined('PHPFOX') or exit('NO DICE!');
class musicsharing_Component_Block_Topalbums extends Phpfox_Component
{
    public function process()
    {
		//phpFox::isUser(true);
		$prefix=phpFox::getParam(array('db', 'prefix'));
		$this->template()->assign(array(
			'sHeader' => phpFox::getPhrase('musicsharing.top_albums'),
			'sDeleteBlock' => 'dashboard',
             'aTopAlbums' =>phpFox::getService('musicsharing.music')->getAlbums(0,10," ".$prefix."m2bmusic_album.play_count DESC",null," ".$prefix."m2bmusic_album.search = 1 AND (SELECT count(*) FROM ".$prefix."m2bmusic_album_song WHERE ".$prefix."m2bmusic_album_song.album_id = ".$prefix."m2bmusic_album.album_id) > 0"),
		));
        return 'block';
    }
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('musicsharing.component_block_topalbums_clean')) ? eval($sPlugin) : false);
    }
}

?>