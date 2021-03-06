<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Musicsharing_component_block_Mobile_Viewmoretopsongsmobile extends Phpfox_Component {

    public function process()
    {
        $aParentModule = phpFox::getLib('session')->get('pages_msf');
        if ($aParentModule === false)
        {
            $aParentModule = NULL;
        }

        $aSetting = phpFox::getService('musicsharing.music')->getUserSettings(phpFox::getUserId(), false);
        $aUserSetting = phpFox::getService('musicsharing.music')->getUserSettings(phpFox::getUserId());
        
        if (isset($aSetting['is_public_permission']) && $aSetting['is_public_permission'] == 0)
        {
            phpFox::isUser(true);
        }

        $iLimitPerPage = isset($aSetting['number_song_per_page_widget']) ? $aSetting['number_song_per_page_widget'] : 10;
        $this->template()->assign(array(
            'sDeleteBlock' => 'dashboard',
            'top_songs' => $this->getParam('aSongs', array()),
            'num_top_songs' => $iLimitPerPage,
            'aParentModule' => $aParentModule,
            'total_songs' => phpFox::getService('musicsharing.music')->get_total_song(null),
            'core_path' => phpFox::getParam('core.path'),
            'user_id' => phpFox::getUserId(),
            'settings' => array_merge($aSetting, $aUserSetting),
            'type_title' => "Type :"
        ));
        
        return 'block';
    }
}

?>
