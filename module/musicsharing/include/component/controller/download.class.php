<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class Musicsharing_Component_Controller_Download extends Phpfox_Component
{
    
	public function process()
	{
        $iSongId = $this->request()->getInt('iSongId');
        
        $aSong = phpFox::getService('musicsharing.music')->song_track_info($iSongId);
        
        if (!isset($aSong['song_id']))
        {
            exit;
        }
        
        // Increment the download counter
		Phpfox::getService('musicsharing.music')->updateDownloadCount($aSong['song_id']);
        
		// Prepare the song path
        if ($aSong['phpfox_music_id'] > 0)
        {
            $sPath = phpFox::getParam('core.dir_file') . 'music' . PHPFOX_DS . sprintf($aSong['url'], '');
        }
        else
        {
            $sPath = phpFox::getParam('core.dir_file') . 'musicsharing' . PHPFOX_DS . sprintf($aSong['url'], '');
        }
		// Download the photo
		Phpfox::getLib('file')->forceDownload($sPath, $aSong['title'] . '.mp3', 'audio/mpeg', $aSong['filesize'], $aSong['server_id']);
		
		// We are done, lets get out of here
		exit;
	}
    	
}

?>