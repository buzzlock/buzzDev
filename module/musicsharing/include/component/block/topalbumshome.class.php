<?php

defined('PHPFOX') or exit('NO DICE!');

class musicsharing_component_block_topalbumshome extends Phpfox_Component {

    public function process()
    {
        if(phpfox::isMobile()){	
			return false;
		}
        Phpfox::getLib('setting')->setParam('musicsharing.url_image', Phpfox::getParam('core.url_pic') . 'musicsharing' . PHPFOX_DS);
        $aParentModule = $this->getParam('aParentModule');
        $aNewAlbums = phpFox::getService('musicsharing.music')->getAlbums(0, 10, " " . Phpfox::getT('m2bmusic_album') . ".play_count DESC", null, " " . Phpfox::getT('m2bmusic_album') . ".search = 1");
        
        $this->template()->assign('aNewAlbums', $aNewAlbums);
        $sLink2 = phpFox::getParam('core.path');
        $sDefaultImage = 'module/musicsharing/static/image/music.png';
        $this->template()->assign(array(
            'sHeader' => '',
            'sLink2' => $sLink2,
            'aParentModule' => $aParentModule,
            'sDefaultImage' => $sDefaultImage,
                )
        );

        return 'block';
    }

    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('musicsharing.component_block_topalbums_clean')) ? eval($sPlugin) : false);
    }

}

?>