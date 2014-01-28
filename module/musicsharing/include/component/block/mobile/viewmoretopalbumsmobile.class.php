<?php

defined('PHPFOX') or exit('NO DICE!');

class Musicsharing_component_block_Mobile_Viewmoretopalbumsmobile extends Phpfox_Component {

    public function process()
    {
        Phpfox::getLib('setting')->setParam('musicsharing.url_image', Phpfox::getParam('core.url_pic') . 'musicsharing' . PHPFOX_DS);
        
        $aNewAlbums = $this->getParam('aNewAlbums', array());

        $this->template()->assign(array(
            'sHeader' => '',
            'sLink2' => phpFox::getParam('core.path'),
            'aParentModule' => $this->getParam('aParentModule'),
            'aNewAlbums' => $aNewAlbums
        ));

        return 'block';
    }

}

?>