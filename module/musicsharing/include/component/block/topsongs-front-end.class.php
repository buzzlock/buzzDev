<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class musicsharing_component_block_topsongs_front_end extends Phpfox_Component{
  public function process(){
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
		$songs = phpFox::getService('musicsharing.music')->getSongs(null,$settings['number_song_per_page_widget'],"".$prefix."m2bmusic_album_song.play_count DESC",null," search = 1");
		if(!count($songs)) return false;
        $this->template()->assign(array(
            'sDeleteBlock' => 'dashboard',
            'top_songs' => $songs,
            'num_top_songs' => $num_top_songs,
            'aParentModule' => $aParentModule,
            'total_songs' => $total_songs,
			//'top_artists' =>$top_Artist,
            'core_path' =>phpFox::getParam('core.path'),
            'user_id'   =>phpFox::getUserId(),
            'type_title' => "Type :"
        ));
        $this->template()->setHeader(
			array(
				'm2bmusic_tabcontent.js' => 'module_musicsharing' ,
				'm2bmusic_class.js' => 'module_musicsharing' ,
				'music.css' => 'module_musicsharing'
			)
		);
    return 'block';
  }

  public function array_sort($array, $on, $order='SORT_DESC')
    {
      $new_array = array();
      $sortable_array = array();

      if (count($array) > 0) {
          foreach ($array as $k => $v) {
              if (is_array($v)) {
                  foreach ($v as $k2 => $v2) {
                      if ($k2 == $on) {
                          $sortable_array[$k] = $v2;
                      }
                  }
              } else {
                  $sortable_array[$k] = $v;
              }
          }

          switch($order)
          {
              case 'SORT_ASC':
                  asort($sortable_array);
              break;
              case 'SORT_DESC':
                  arsort($sortable_array);
              break;
          }
          $index = 0;
          foreach($sortable_array as $k => $v) {
              $index++;
              $array[$k]['index'] = $index;
              $new_array[] = $array[$k];
          }
      }
      return $new_array;
    } 
}
?>
