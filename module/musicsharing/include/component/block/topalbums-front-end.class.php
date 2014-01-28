<?php
/*
* To change this template, choose Tools | Templates
* and open the template in the editor.
*/
class musicsharing_component_block_topalbums_front_end extends Phpfox_Component{
	public function process(){
		
		$prefix=phpFox::getParam(array('db', 'prefix'));
		//Search

		$settings = phpFox::getService('musicsharing.music')->getUserSettings(phpFox::getUserId(),false);
		$sets =  phpFox::getService('musicsharing.music')->getUserSettings(phpFox::getUserId());


		if (isset($settings['is_public_permission']) && $settings['is_public_permission'] == 0)
		{
			phpFox::isUser(true);
		}
		$list_info=phpFox::getService('musicsharing.music')->getAlbums(null,5,"".$prefix."m2bmusic_album.play_count DESC",null," search = 1");
		$total_albums = phpFox::getService('musicsharing.music')->get_total_album(null);
		$limit=10;
		if(count($list_info)>0)
		{
			foreach($list_info as $Ikey=>$album_info)
			{
				$list_song_info = phpFox::getService('musicsharing.music')->getSongsAlbumId($album_info['album_id'],$limit);
				$list_info[$Ikey]['list_song']=$list_song_info;

				$search=array("\n","\r","&#039;");
				$replace=array("<br/>","","\&#039;");
				$list_info[$Ikey]['description']=str_replace($search, $replace, $list_info[$Ikey]['description']);
				$list_info[$Ikey]['title_replace']=str_replace($search, $replace, $list_info[$Ikey]['title']);

			}
			
		}

		$this->template()->assign(array(
			'sDeleteBlock' => 'dashboard',
			'description' => phpFox::getPhrase('musicsharing.description'),
			'name' => phpFox::getPhrase('musicsharing.name_upper'),
			'listofsongs' => phpFox::getPhrase('musicsharing.list_of_songs'),
			'top_albums' => $list_info,
			'total_albums' => $total_albums,
			//'top_artists' =>$top_Artist,
			'core_path' =>phpFox::getParam('core.path'),
			'user_id'   =>phpFox::getUserId(),
			'type_title' => "Type :"
		));
		$this->template()->setHeader(array(
			'm2bmusic_tabcontent.js' => 'module_musicsharing' ,
			'm2bmusic_class.js' => 'module_musicsharing' ,
			'music.css' => 'module_musicsharing',
			'tooltipalbum.js' => 'module_musicsharing',
			'musicsharing_style.css' => 'module_musicsharing',
		));
		return 'block';
	}


}
?>
