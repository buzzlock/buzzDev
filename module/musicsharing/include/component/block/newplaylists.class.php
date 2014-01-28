<?php
defined('PHPFOX') or exit('NO DICE!');
class musicsharing_Component_Block_Newplaylists extends Phpfox_Component
{
    public function process()
    {
        if(phpfox::isMobile()){
			return false;
		}
        
		Phpfox::getLib('setting')->setParam('musicsharing.url_image', Phpfox::getParam('core.url_pic') . 'musicsharing' . PHPFOX_DS);
		$prefix=phpFox::getParam(array('db', 'prefix'));
		$pls = phpFox::getService('musicsharing.music')->getPlaylists(0,10,$prefix."m2bmusic_playlist.creation_date desc","","Where search = 1 AND (SELECT count(*) FROM ".$prefix."m2bmusic_playlist_song WHERE ".$prefix."m2bmusic_playlist_song.playlist_id = ".$prefix."m2bmusic_playlist.playlist_id) > 0");
		if(!count($pls)) return false;
		$aParentModule = phpFox::getLib('session')->get('pages_msf');
		if($aParentModule === false) {
			$aParentModule = NULL;
		}
		$aMenus = array(
			phpFox::getPhrase('musicsharing.new_playlists')=>'#musicsharing.musicnewplaylists?id=js_newplaylists_container',
			phpFox::getPhrase('musicsharing.top_playlists')=>'#musicsharing.musictopplaylists?id=js_topplaylists_container',

                );
        
        if(!phpfox::isMobile()){
			$this->template()->assign(array(
				'aMenu'=>$aMenus
			));
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
        (($sPlugin = Phpfox_Plugin::get('musicsharing.component_block_newplaylists_clean')) ? eval($sPlugin) : false);
    }
}

?>