<?php

defined('PHPFOX') or exit('NO DICE!');

class musicsharing_Component_Block_Mobile_Viewmorenewalbumsmobile extends Phpfox_Component {

    public function process()
    {
        Phpfox::getLib('setting')->setParam('musicsharing.url_image', Phpfox::getParam('core.url_pic') . 'musicsharing' . PHPFOX_DS);
        
        $this->template()->assign(array(
            'sDeleteBlock' => 'dashboard',
            'aTopAlbums' => $this->getParam('aNewAlbums', array()),
        ));

        return 'block';
    }

    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('musicsharing.component_block_topalbums_clean')) ? eval($sPlugin) : false);
    }

}

?>