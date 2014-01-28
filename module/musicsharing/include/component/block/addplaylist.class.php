<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class musicsharing_Component_Block_Addplaylist extends Phpfox_Component {

    /**
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
        $aParentModule = $this->getParam('aParentModule');
        
        $where = " WHERE " . Phpfox::getT('m2bmusic_playlist') . ".user_id = " . phpFox::getUserId();
        
        $aPlaylists = phpFox::getService('musicsharing.music')->getPlaylists(NULL, NULL, NULL, NULL, $where);
        
        $iSong = $this->getParam('iItemId');
        $aTemp = array();
        
        foreach ($aPlaylists as $playlist)
        {
            if (phpFox::getService('musicsharing.music')->checkPlaylist($playlist['playlist_id'], $iSong) <= 0)
                $aTemp[] = $playlist;
        }
        
        $this->template()->assign(array(
            'aPlaylists' => $aTemp,
            'count_playlist' => count($aTemp),
            'aParentModule' => $aParentModule,
            'iSong' => $iSong
                )
        );
    }

}

?>