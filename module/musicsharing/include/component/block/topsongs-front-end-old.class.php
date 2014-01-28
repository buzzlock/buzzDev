<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class MusicStore_component_block_topsongs_front_end_old extends Phpfox_Component{
  public function process(){

        $_SESSION['downloadlist_downloadlist']=phpFox::getUserId();
        $hiddencartalbum = phpFox::getService('musicstore.cart.shop')->getHiddenCartItem('album',phpFox::getUserId());
        $this->template()->assign(array('hiddencartalbum'=>$hiddencartalbum));
        //end
        //get songs in downloadlist and in the cart shop
        $hiddencartsong = phpFox::getService('musicstore.cart.shop')->getHiddenCartItem('song',phpFox::getUserId());
        $this->template()->assign(array('hiddencartsong'=>$hiddencartsong));
        //end
        //vudp
        $user_group_id=phpFox::getService('musicstore.cart.account')->getValueUserGroupId(phpFox::getUserId());
        $selling_settings=phpFox::getService("musicstore.cart.music")->getSettingsSelling($user_group_id);
        $this->template()->assign(array('selling_settings'=>$selling_settings));
        //end
        $prefix=phpFox::getParam(array('db', 'prefix'));
        //Search

        $settings = phpFox::getService('musicstore.music')->getUserSettings(phpFox::getUserId(),false);
        $sets =  phpFox::getService('musicstore.music')->getUserSettings(phpFox::getUserId());


        if (isset($settings['is_public_permission']) && $settings['is_public_permission'] == 0)
        {
            phpFox::isUser(true);
        }

        $setgs = array_merge($settings,$sets);

        $this->template()->assign(array('settings'=>$setgs));
        $_settings = phpFox::getService('musicstore.music')->getSettings(0);
        $this->template()->assign(array(
            'sDeleteBlock' => 'dashboard',
            'top_songs' =>phpFox::getService('musicstore.music')->getSongs(null,$_settings['number_top_song_per_page'],"".$prefix."m2bmusic_album_song.play_count DESC",null," search = 1"),
            'core_path' =>phpFox::getParam('core.path'),
            'user_id'   =>phpFox::getUserId(),
            'type_title' => "Type :"  ,
             'currency' => phpFox::getService('core.currency')->getDefault(),
        ));
        $this->template()->setHeader(array(
        'm2bmusic_tabcontent.js' => 'module_musicstore' ,
        'm2bmusic_class.js' => 'module_musicstore' ,
        'music.css' => 'module_musicstore'
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
