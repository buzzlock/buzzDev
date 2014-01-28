<?php

defined('PHPFOX') or exit('NO DICE!');

class musicsharing_Component_Block_Mobile_Viewmorenewplaylistsmobile extends Phpfox_Component {

    public function process()
    {
        Phpfox::getLib('setting')->setParam('musicsharing.url_image', Phpfox::getParam('core.url_pic') . 'musicsharing' . PHPFOX_DS);

        $aParentModule = phpFox::getLib('session')->get('pages_msf');
        if ($aParentModule === false)
        {
            $aParentModule = NULL;
        }
        $aPlaylist = $this->getParam('aPlaylist', array());
        $this->template()->assign(array(
            'sHeader' => '',
            'core_path' => phpFox::getParam('core.path'),
            'sDeleteBlock' => 'dashboard',
            'aNewPlaylists' => $aPlaylist,
            'aParentModule' => $aParentModule,
            'iCount' => count($aPlaylist),
            "isOdd" => (count($aPlaylist) % 2),
        ));
        return 'block';
    }

    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('musicsharing.component_block_mobile_newplaylists_clean')) ? eval($sPlugin) : false);
    }

}

?>