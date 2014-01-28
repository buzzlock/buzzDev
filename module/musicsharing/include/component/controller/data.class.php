<?php

/**
 * [PHPFOX_HEADER]
 */
if (!isset($_SESSION))
{
    session_start();
}
defined('PHPFOX') or exit('NO DICE!');
?> 
<?php

class Musicsharing_Component_Controller_Data extends Phpfox_Component {

    public function process()
    {
        $idalbum = $this->request()->getInt('idalbum', 0);
        $name = $this->request()->get('name', "album");
        $user_name = $this->request()->get('user', '');
        $idsong = $this->request()->get('idsong', '');
        $idplaylist = $this->request()->getInt('idplaylist', 0);
        $user_id = $this->request()->getInt('user_id', 0);
        $vote = $this->request()->get('vote', '');
        $p = $this->request()->getInt('p', 1);

        $oMusic = Phpfox::getService('musicsharing.music');
        switch ($name) {
            case "getalbum":
                $oMusic->embedServiceGetAlbum($idalbum, Phpfox::getUserId());
                die();
                break;
            case "getplaylist":
                $oMusic->embedServiceGetPlaylist($idplaylist, Phpfox::getUserId());
                die();
                break;
            case "playscount":
                $oMusic->embedServicePlayCount($idsong);
                break;
        }
    }
}

?>