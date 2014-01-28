<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class Musicsharing_Component_Controller_Admincp_editsong extends Phpfox_Component{
  public function process()
  {
        phpFox::isUser(true);
        
        $result=0;
        $aParentModule = $this->getParam('aParentModule');
        if($aParentModule)
        {
            phpFox::getLib('session')->set('pages_msf',$aParentModule);
        }
        else
        {
            phpFox::getLib('session')->remove('pages_msf');
        }
        if(isset($_POST['task']) && $_POST['task'] == "editsong")
        {
            $other_singer = "" ;
            $singer_id = 0;
            $title = "";
            $category = 0;
            if($_POST['check_other_singer'])
            {
                $other_singer = $_POST['songSingerName'] ;
                $singer_id = 0;
            }
            else
            {
                $other_singer = "";
                $singer_id = $_POST['songSinger'] ;
            }
            $title = $_POST['songTitle'];
            $price = $_POST['price'];
            $lyric = $_POST['songLyric'];
            if($title == "")
                $title = "Not Updated";
            $category = $_POST['songCat'];
            $song_id =  $_POST['song_id'];
            $song = array();
            $song['price'] = round($price,2);
            $song['title'] = $title ;
            $song['title_url'] = $title ;
            $song['singer_id'] =  $singer_id ;
            $song['other_singer'] = $other_singer;
            $song['cat_id'] =  $category ;
            $song['song_id'] =  $song_id ;
            $song['lyric'] =  $lyric ;
            phpFox::getService('musicsharing.music')->updateAlbumSong($song);
            $result=1;
        }
        $idSong = $this->request()->get('iItemId');
        $song_info = phpFox::getService('musicsharing.music')->song_track_info($idSong);
        $page =   $this->getParam('page');
        $album =   $this->getParam('album');
        $user_group_id=phpFox::getService('musicsharing.cart.account')->getValueUserGroupId(phpFox::getUserId());
        $selling_settings=phpFox::getService("musicsharing.cart.music")->getSettingsSelling($user_group_id);
        $this->template()->assign(array(
                'idSong' => $idSong ,
                'page'   => $page,
                'album'   => $album,
                'aCats' =>phpFox::getService('musicsharing.music')->getCategories(),
                'aSingers' =>phpFox::getService('musicsharing.music')->getSingers(),
                'min_price_song' => $selling_settings['min_price_song'],
                'selling_settings'=>$selling_settings,
                'song_info' => $song_info,
                 'currency' => phpFox::getService('core.currency')->getDefault(),
            'result'    =>$result,
            'core_path' =>phpFox::getParam('core.path'),
            )
        );

  }
}
?>
