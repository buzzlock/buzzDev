<?php 
defined('PHPFOX') or exit('NO DICE!');
class musicsharing_Component_Block_TopPlaylists2 extends Phpfox_Component 
{     
    public function process() 
    {
		// phpFox::isUser(true);
		$prefix=phpFox::getParam(array('db', 'prefix'));
		$aParentModule = phpFox::getLib('session')->get('pages_msf');
		if($aParentModule === false) {
			$aParentModule = NULL;
		}
		$this->template()->assign(array(
			'aParentModule' => $aParentModule,
			'sHeader' => phpFox::getPhrase('musicsharing.top_playlist'),
			'sDeleteBlock' => 'dashboard',
			'aNewPlaylists' =>phpFox::getService('musicsharing.music')->getPlaylists(0,10,$prefix."m2bmusic_playlist.play_count desc","","Where search = 1 AND (SELECT count(*) FROM ".$prefix."m2bmusic_playlist_song WHERE ".$prefix."m2bmusic_playlist_song.playlist_id = ".$prefix."m2bmusic_playlist.playlist_id) > 0"),
		));
		//var_dump(phpFox::getService('musicsharing.music')->getPlaylists(0,10,$prefix."m2bmusic_playlist.creation_date desc","","Where search = 1"));
        return 'block';
    } 
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('musicsharing.component_block_newplaylists_clean')) ? eval($sPlugin) : false);
    }
} 

?>