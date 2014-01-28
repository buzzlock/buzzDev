<?php 
defined('PHPFOX') or exit('NO DICE!');
class musicsharing_Component_Block_Share extends Phpfox_Component 
{     
    public function process() 
    { 
		// phpFox::isUser(true);
        $settings = phpFox::getService('musicsharing.music')->getUserSettings(phpFox::getUserId());
        $this->template()->assign(array('settings'=>$settings));
        $iSong = $this->getParam('iItemId');
		$aParentModule = $this->getParam('aParentModule');
        $music_info = phpFox::getService('musicsharing.music')->song_track_info($iSong);  
        if(isset($music_info['album_id']) && $music_info['album_id'] >0) 
            {
                $info = phpFox::getService('musicsharing.music')->getAlbumInfo($music_info['album_id']);    
            }
        $this->template()->assign(array(
            'core_path' =>phpFox::getParam('core.path'),
            'music_info' => $music_info,
            'boolAlbum' => true,
            'aParentModule' => $aParentModule,
            'user_id'   =>phpFox::getUserId(),  
        )); 
        $this->template()
        ->setHeader(array(
                    'jquery/plugin/jquery.highlightFade.js' => 'static_script',    
                    'jquery/plugin/jquery.scrollTo.js' => 'static_script',
                    'jquery/plugin/imgnotes/jquery.tag.js' => 'static_script',                        
                    'quick_edit.js' => 'static_script',
                    'comment.css' => 'style_css',
                    'pager.css' => 'style_css',
                    'switch_legend.js' => 'static_script',
                    'switch_menu.js' => 'static_script',
                  
                )
            )
            ->setEditor(array(
                    'load' => 'simple'                    
                )
            );    
        return 'block'  ;
    } 
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('musicsharing.component_block_share_clean')) ? eval($sPlugin) : false);
    }
} 

?>