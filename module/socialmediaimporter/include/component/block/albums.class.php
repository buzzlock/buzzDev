<?php
defined('PHPFOX') or exit('NO DICE!');
class SocialMediaImporter_Component_Block_Albums extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$iUserId = Phpfox::getUserId();
		$oService = $this->getParam("oService");
		$aAlbums = $this->getParam("aAlbums");
		$iCount = $this->getParam("iCount");	
		$iPage = $this->getParam("iPage");		
		if ($iCount && $aAlbums)
		{
			$aTracking = $oService->getTracking($iUserId, 'album');
			foreach ($aAlbums as $i => $aAlbum)
			{
				$aAlbums[$i]['is_imported'] = $oService->isImported($aTracking, $aAlbum['album_id']);														
			}		
		}
		$this->template()->assign(array(
			'iPage' => $iPage,
			'aAlbums' => $aAlbums,
			'iCount' => $iCount,
			'aAlbumTrackerKeys' => array(),
			'aAlbumTracker' => array(),
		));	
	}
}
?>