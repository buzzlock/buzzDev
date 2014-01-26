<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright		[YOUNET_COPYRIGHT]
 * @author  		minhTA	
 */
class Advancedphoto_Component_Ajax_Ynphoto_Ajax extends Phpfox_Ajax
{
	public function saveAlbumPhotoOrder()
	{
		Phpfox::isUser(true);
		$sData = $this->get('data');
		$iIds = explode(',', $sData);
		//album with the biggest order value gonna be the first one
		$iOrder = count($iIds);

		//we should have revert machenism here
		// this is needed to be optimized
		foreach($iIds as $iId)
		{
			Phpfox::getService('advancedphoto.process')->updateOrderOfAlbumPhoto($iId, $iOrder);
			$iOrder--;
		}

	}

	public function loadMorePhotos()	
	{
		$iYear = $this->get('iYear');
		$iPage = $this->get('iPage');
		$iLimit = $this->get('iLimit');

		 Phpfox::getBlock('advancedphoto.yntimelinelistphoto', array(
            'iYear' => $iYear,
            'iPage' => $iPage,
            'iLimit' => $iLimit,
        ));

		 $htmlContent = $this->getContent();
		 $this->call("$('#yn_loadmore_space_holder_" . $iYear . "').append(\" " . $htmlContent . "\");");
		 $this->call("$('#yn_loadmore_phrase_" .  $iYear. "').remove();");
		 $this->call("\$Core.loadInit();");
	}
}

?>
