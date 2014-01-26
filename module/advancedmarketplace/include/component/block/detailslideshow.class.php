<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class AdvancedMarketplace_Component_Block_DetailSlideShow extends Phpfox_Component
{
	public function process()
	{
		$aListing = $this->getParam('aListing');
		$aImage = array();
		$aImages = phpfox::getService('advancedmarketplace')->getImagesOfListing($aListing['listing_id']);
		//var_dump($aImage); die();
		$this->template()->assign(array(
                    'corepath'=>phpfox::getParam('core.path'),
                    'aListing' => $aListing,
                    'advancedmarketplace_url_image' => Phpfox::getParam('core.url_pic') . "advancedmarketplace/",
                    'aImages' => $aImages
								));
		// return 'block';
	}
}
?>
