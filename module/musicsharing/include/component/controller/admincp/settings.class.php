<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class Musicsharing_Component_Controller_Admincp_settings extends Phpfox_Component
{
    /**
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
        $aParentModule = $this->getParam('aParentModule');
        if($aParentModule)
        {
            phpFox::getLib('session')->set('pages_msf',$aParentModule);
        }
        else
        {
            phpFox::getLib('session')->remove('pages_msf');
        }
         $group_members = phpFox::getLib('phpfox.database')->select('*')
                                     ->from(phpFox::getT('user_group'),'gr')
                                     ->execute('getRows');
         $default_group  = $default_public = 1;

         if ($this->request()->get('viewgroup'))
         {
             $default_group = $this->request()->get('viewgroup');

         }
         $default_public = $this->request()->get('public');

         if (!empty($default_public) && $default_public !='0')
         {
             $default_public = 1;

         }
         else
         {
             $default_public = 0;

         }
         $default_number_song = $this->request()->get('song');
         $default_number_song_widget = $this->request()->get('song_widget');
        $settings = phpFox::getService('musicsharing.music')->getSettings(0);

		 if (!empty($settings['number_song_per_page']))
         {
             $default_number_song = $settings['number_song_per_page'];
         }
		 if (!empty($settings['number_song_per_page_widget']))
         {
             $default_number_song_widget = $settings['number_song_per_page_widget'];
         }
		 if (!empty($settings['number_album_per_page']))
         {
             $default_number_album = $settings['number_album_per_page'];
         }
		 if (!empty($settings['number_playlist_per_page']))
         {
             $default_number_playlist = $settings['number_playlist_per_page'];
         }
		 if (!empty($settings['number_artist_per_page']))
         {
             $default_number_artist = $settings['number_artist_per_page'];
         }
		 if (!empty($settings['is_public_permission']))
         {
             $default_public = $settings['is_public_permission'];

         }
		 
		 
		 
		if (!empty($default_number_song) && $default_number_song !='0') {
		 //
		} else {
		 $default_number_song = 10;
		}
		if (!empty($default_number_song_widget) && $default_number_song_widget !='0') {
		 //
		} else {
		 $default_number_song_widget = 10;
		}
		 
		if (!empty($default_number_album) && $default_number_album !='0') {
		 //
		} else {
		 $default_number_album = 8;
		}
		 
		if (!empty($default_number_playlist) && $default_number_playlist !='0') {
		 //
		} else {
		 $default_number_playlist = 8;
		}
		 
		if (!empty($default_number_artist) && $default_number_artist !='0') {
		 //
		} else {
		 $default_number_artist = 20;
		}
         if ( $this->request()->get('save_change_group_setting'))
         {
             $val = $this->request()->get('val');
             phpFox::getService('musicsharing.music')->setSettings($val,$val['select_group_member']);
             $default_group  = $val['select_group_member'];
             $this->url()->send('current',array('viewgroup'=>$default_group,'song'=>$default_number_song),'Update User Group settings successful');

         }
		if ($this->request()->get('save_change_global_setings'))
		{
			$val = $this->request()->get('val');
			// echo "text";exit;
			phpFox::getService('musicsharing.music')->setSettings($val,0);
			$default_public = $val['is_public_permission'];
			$default_number_song = $val['number_song_per_page'];
			$default_number_song_widget = $val['number_song_per_page_widget'];
			$default_number_album = $val['number_album_per_page'];
			$default_number_playlist = $val['number_playlist_per_page'];
			$default_number_artist = $val['number_artist_per_page'];
			$this->url()->send('current',array('viewgroup'=>$default_group,'public'=>$default_public,'song'=>$default_number_song,'album'=>$default_number_album,'playlist'=>$default_number_playlist,'artist'=>$default_number_artist),'Update Global settings successful');
         }
          $this->template()->assign(
                            array(
                                'group_members' => $group_members,
                                'default_view_group' => $default_group,
                                'is_public_permission' =>$default_public,
                                'default_number_song'=>$default_number_song,
                                'default_number_song_widget'=>$default_number_song_widget,
                                'default_number_album'=>$default_number_album,
                                'default_number_playlist'=>$default_number_playlist,
                                'default_number_artist'=>$default_number_artist,
                                )
                            );

		$this->template()
			->setBreadCrumb(phpFox::getPhrase('musicsharing.admin_menu_global_settings'), null, true);
    }

    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('musicsharing.component_controller_admincp_settings_clean')) ? eval($sPlugin) : false);
    }
}

?>