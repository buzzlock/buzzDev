<?php
defined('PHPFOX') or exit('NO DICE!');
class musicsharing_Component_Block_Mobile_Newplaylists extends Phpfox_Component
{
    public function process()
    {
		// phpFox::isUser(true);
		$prefix=phpFox::getParam(array('db', 'prefix'));
		$pls = phpFox::getService('musicsharing.music')->getPlaylists(0, Phpfox::getParam('musicsharing.mobile_view_amount_of_item_per_page_in_new_playlist'),$prefix."m2bmusic_playlist.creation_date desc","","Where search = 1 AND (SELECT count(*) FROM ".$prefix."m2bmusic_playlist_song WHERE ".$prefix."m2bmusic_playlist_song.playlist_id = ".$prefix."m2bmusic_playlist.playlist_id) > 0");
		if(!count($pls)) return false;
		$aParentModule = phpFox::getLib('session')->get('pages_msf');
		if($aParentModule === false) {
			$aParentModule = NULL;
		}
		$this->template()->assign(array(
			'sHeader' => '',			
			'core_path'=>phpFox::getParam('core.path'),
			'sDeleteBlock' => 'dashboard',
			'aNewPlaylists' => $pls,
			'aParentModule' => $aParentModule,
            'iCount' => count($pls),
			"isOdd" => (count($pls)%2),
		));
		//var_dump(phpFox::getService('musicsharing.music')->getPlaylists(0,10,$prefix."m2bmusic_playlist.creation_date desc","","Where search = 1"));
        return 'block';
    }
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('musicsharing.component_block_mobile_newplaylists_clean')) ? eval($sPlugin) : false);
    }
}

?>