<?php
defined('PHPFOX') or exit('NO DICE!');
class musicsharing_Component_Block_newalbums extends Phpfox_Component
{
    public function process()
    {
		$prefix=phpFox::getParam(array('db', 'prefix'));
        if (!phpfox::isMobile())
        {
            $this->template()->assign(array('sHeader' => phpFox::getPhrase('musicsharing.new_albums')));
        }
		$this->template()->assign(array(
			'sDeleteBlock' => 'dashboard',
            'aTopAlbums' =>phpFox::getService('musicsharing.music')->getAlbums(0, Phpfox::getParam('musicsharing.mobile_view_amount_of_item_per_page_in_new_albums')," ".$prefix."m2bmusic_album.creation_date DESC",null," ".$prefix."m2bmusic_album.search = 1 AND (SELECT count(*) FROM ".$prefix."m2bmusic_album_song WHERE ".$prefix."m2bmusic_album_song.album_id = ".$prefix."m2bmusic_album.album_id) > 0"),
		));
        return 'block';
    }
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('musicsharing.component_block_topalbums_clean')) ? eval($sPlugin) : false);
    }
}

?>