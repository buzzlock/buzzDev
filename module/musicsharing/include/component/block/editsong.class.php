<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class musicsharing_Component_Block_Editsong extends Phpfox_Component
{
    /**
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
        // phpFox::isUser(true);
        $idSong = $this->getParam('iItemId');
        $song_info = Phpfox::getService('musicsharing.music')->song_track_info($idSong, "no");

        $page = $this->getParam('page');
        $album = $this->getParam('album');
        $inMySong = $this->getParam('inMySong');
        $aParentModule = $this->getParam('aParentModule');

        if (!isset($aParentModule))
        {
            $aParentModule = phpFox::getLib('session')->get('pages_msf');
        }

        if ($aParentModule === false)
        {
            $aParentModule = NULL;
        }

        $aSingerTypes = Phpfox::getService('musicsharing.music')->getAllSingerTypes();

        foreach($aSingerTypes as $iKey => $aType)
        {
            $aSingerTypes[$iKey]['singer'] = Phpfox::getService('musicsharing.music')->getSingersByTypeId($aType['singertype_id']);

            foreach($aSingerTypes[$iKey]['singer'] as $i => $aSinger)
            {
                $aSingerTypes[$iKey]['singer'][$i]['bDefault'] = $aSinger['singer_id'] == $song_info['singer_id'];
            }
        }

        $this->template()->assign(array(
            'idSong' => $idSong,
            'page' => $page,
            'album' => $album,
            'aCats' => Phpfox::getService('musicsharing.music')->getCategories(),
            'aSingers' => $aSingerTypes,
            'song_info' => $song_info,
            'inMySong' => $inMySong,
            'aParentModule' => $aParentModule,
                )
        );
    }

}

?>