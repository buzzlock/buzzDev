<?php

defined('PHPFOX') or exit('NO DICE!');

class musicsharing_component_block_Mobile_Topalbumsmobile extends Phpfox_Component {

    public function process()
    {
        Phpfox::getLib('setting')->setParam('musicsharing.url_image', Phpfox::getParam('core.url_pic') . 'musicsharing' . PHPFOX_DS);

        $aParentModule = $this->getParam('aParentModule');
        
        $aNewAlbums = phpFox::getService('musicsharing.music')->getAlbums(0, Phpfox::getParam('musicsharing.mobile_view_amount_of_item_per_page_in_top_albums'), " " . Phpfox::getT('m2bmusic_album') . ".play_count DESC", null, " " . Phpfox::getT('m2bmusic_album') . ".search = 1");
        
        $this->template()->assign(array(
            'sHeader' => '',
            'sLink2' => phpFox::getParam('core.path'),
            'aParentModule' => $aParentModule,
            'aNewAlbums' => $aNewAlbums
                )
        );

        return 'block';
    }

}

?>