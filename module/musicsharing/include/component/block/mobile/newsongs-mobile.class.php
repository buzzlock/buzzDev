<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class musicsharing_component_block_Mobile_newsongs_mobile extends Phpfox_Component{
   public function process(){
  
		// phpFox::isUser(true);
        $prefix=phpFox::getParam(array('db', 'prefix'));
        //Search
		$aParentModule = phpFox::getLib('session')->get('pages_msf');
		if($aParentModule === false) {
			$aParentModule = NULL;
		}
        $settings = phpFox::getService('musicsharing.music')->getUserSettings(phpFox::getUserId(),false);
        $sets =  phpFox::getService('musicsharing.music')->getUserSettings(phpFox::getUserId());
        if (isset($settings['is_public_permission']) && $settings['is_public_permission'] == 0)
        {
            phpFox::isUser(true);
        }
		 
		if(!isset($settings['number_song_per_page_widget'])){
			$settings['number_song_per_page_widget'] = 10;
		}

        $setgs = array_merge($settings,$sets);
        $num_top_songs = $settings['number_song_per_page_widget'];
        $total_songs = phpFox::getService('musicsharing.music')->get_total_song(null);
        $this->template()->assign(array('settings'=>$setgs));    
       
        $songs = phpFox::getService('musicsharing.music')->getSongs(null,$settings['number_song_per_page_widget'],"".$prefix."m2bmusic_album_song.song_id DESC",null," search = 1");
		
		if(!count($songs)) return false;
		
        $this->template()->assign(array(
            'sDeleteBlock' => 'dashboard',
            'top_songs' => $songs,
            'num_top_songs' => $num_top_songs,
            'total_songs' => $total_songs,
            'aParentModule' => $aParentModule,
			// 'top_artists' =>$top_Artist,
            'core_path' =>phpFox::getParam('core.path'),
            'user_id'   =>phpFox::getUserId(),
            'type_title' => "Type :",
            'sHeader'=> '',
        ));
        
    return 'block';
 }
 }
  