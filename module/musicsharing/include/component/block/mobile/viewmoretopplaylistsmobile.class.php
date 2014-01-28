<?php

defined('PHPFOX') or exit('NO DICE!');

class Musicsharing_Component_Block_Mobile_Viewmoretopplaylistsmobile extends Phpfox_Component {

    public function process()
    {
        Phpfox::getLib('setting')->setParam('musicsharing.url_image', Phpfox::getParam('core.url_pic') . 'musicsharing' . PHPFOX_DS);
        
        $aPlaylist = $this->getParam('aPlaylist', array());
        
        $aParentModule = phpFox::getLib('session')->get('pages_msf');
        if ($aParentModule === false)
        {
            $aParentModule = NULL;
        }
        
        $this->template()->assign(array(
            'sDeleteBlock' => 'dashboard',
            'core_path' => phpFox::getParam('core.path'),
            'aNewPlaylists' => $aPlaylist,
            'aParentModule' => $aParentModule,
            'iCount' => count($aPlaylist),
            "isOdd" => (count($aPlaylist) % 2),
        ));

        return 'block';
    }

    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('musicsharing.component_block_newplaylists_clean')) ? eval($sPlugin) : false);
    }

}

?>