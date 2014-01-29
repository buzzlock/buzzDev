<?php
defined('PHPFOX') or exit('NO DICE!');
class SocialMediaImporter_Component_Block_Photos extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$iUserId = Phpfox::getUserId();
		$oService = $this->getParam("oService");
		$iCount = $this->getParam("iCount");
		$aPhotos = $this->getParam("aPhotos");
		if ($iCount && $aPhotos)
		{
			$aTracking = $oService->getTracking($iUserId, 'photo');	
			foreach ($aPhotos as $i => $aPhoto)
			{
				$aPhotos[$i]['is_imported'] = $oService->isImported($aTracking, $aPhoto['photo_id']);														
			}		
		}
		$this->template()->assign(array(
			'aPhotos' => $aPhotos,
			'iCount' => $iCount			
		));
	}
}
?>