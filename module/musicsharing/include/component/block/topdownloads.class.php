<?php 
defined('PHPFOX') or exit('NO DICE!');
class musicsharing_Component_Block_Topdownloads extends Phpfox_Component 
{     
    public function process() 
    { 
		// phpFox::isUser(true);
		$prefix=phpFox::getParam(array('db', 'prefix'));
		$this->template()->assign(array(
			'sHeader' => phpFox::getPhrase('musicsharing.top_downloads'),
			'sDeleteBlock' => 'dashboard',
             'aTopDownloads' =>phpFox::getService('musicsharing.music')->getSongs(0,5,"".$prefix."m2bmusic_album_song.download_count DESC"),
		));
        return 'block';
    } 
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('musicsharing.component_block_topdownloads_clean')) ? eval($sPlugin) : false);
    }
} 

?>