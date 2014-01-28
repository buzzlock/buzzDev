<?php

defined('PHPFOX') or exit('NO DICE!');

class musicsharing_Component_Block_topartistside extends Phpfox_Component {
  public function process(){
      $prefix=phpFox::getParam(array('db', 'prefix'));
        //Search

        $settings = phpFox::getService('musicsharing.music')->getUserSettings(phpFox::getUserId(),false);
        $sets =  phpFox::getService('musicsharing.music')->getUserSettings(phpFox::getUserId());


        if (isset($settings['is_public_permission']) && $settings['is_public_permission'] == 0)
        {
            phpFox::isUser(true);
        }

        $setgs = array_merge($settings,$sets);

        $this->template()->assign(array('settings'=>$setgs));

        $top_Artist = phpFox::getService('musicsharing.music')->getArtists(null,6,null);
        $total_artists = phpFox::getService('musicsharing.music')->get_total_artist(null);
        $top_Artist = $this->array_sort($top_Artist, 'num_album','SORT_DESC');
        $this->template()->assign(array(
            'sDeleteBlock' => 'dashboard',
            'total_artists' => $total_artists,
            'top_artists' =>$top_Artist,
            'core_path' =>phpFox::getParam('core.path'),
            'user_id'   =>phpFox::getUserId(),
            'type_title' => "Type :",
            'sHeader' => phpFox::getPhrase('musicsharing.top_uploaders'),
        ));
		$this->template()->setHeader(array(
			'm2bmusic_tabcontent.js' => 'module_musicsharing' ,
			'm2bmusic_class.js' => 'module_musicsharing' ,
			'music.css' => 'module_musicsharing',
			'musicsharing_style.css' => 'module_musicsharing',
		));
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