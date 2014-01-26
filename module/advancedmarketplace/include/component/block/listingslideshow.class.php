<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class AdvancedMarketplace_Component_Block_ListingSlideShow extends Phpfox_Component {

	public function process() 
	{
		$iLimit = 4;
		$aSlideShowListing = PHPFOX::getService("advancedmarketplace")->frontend_getFeatureListings($iLimit);
        
		if(empty($aSlideShowListing))
		{
			return false;
		}
		$this -> template() -> assign(array(
									'sHeader' => Phpfox::getPhrase('advancedmarketplace.feature_listings'), 
									'corepath' => phpfox::getParam('core.path'), 
									'aSlideShowListing' => $aSlideShowListing, 'advancedmarketplace_url_image' => Phpfox::getParam('core.url_pic') . "advancedmarketplace/",
									'core_url' => phpfox::getParam('core.path'),
									 ));

		return 'block';
	}

}
?>
