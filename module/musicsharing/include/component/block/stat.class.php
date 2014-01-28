<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class MusicSharing_Component_Block_Stat extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{	
		// phpFox::isUser(true);
		$this->template()->assign(array( "sHeader" => phpFox::getPhrase('musicsharing.site_stats'),"core_path" => phpFox::getParam('core.path')));
		$this->template()->setHeader("stype.css", "module_profile");
                $album=phpFox::getService("musicsharing.music")->getTotalAlbums();
                $song=phpFox::getService("musicsharing.music")->getTotalSongs();
                $playlist=phpFox::getService("musicsharing.music")->getTotalPlaylist();
                $this->template()->assign(array("album"=>$album,"song"=>$song,"playlist"=>$playlist));
		return "block"; 
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	
}

?>