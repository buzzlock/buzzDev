<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class AdvancedMarketplace_Component_Block_DetailView extends Phpfox_Component
{
	public function process()
	{
		if (!($aListing = $this->getParam('aListing')))
		{
			return false;
		}
		
		if (empty($aListing['image_path']))
		{
		}
		
		$fAVGRating = PHPFOX::getLib("database")
			->select("AVG(rating)")
			->from(PHPFOX::getT("advancedmarketplace_rate"))
			->where(sprintf("listing_id = %d", $aListing['listing_id']))
			->execute("getSlaveField");
		$iRatingCount = PHPFOX::getLib("database")
			->select("count(*)")
			->from(PHPFOX::getT("advancedmarketplace_rate"))
			->where(sprintf("listing_id = %d", $aListing['listing_id']))
			->execute("getSlaveField");
		
		$this->template()->assign(array(
				'aImages' => Phpfox::getService('advancedmarketplace')->getImages($aListing['listing_id']),
				'advancedmarketplace_url_image' => Phpfox::getParam('core.url_pic') . "advancedmarketplace/",
				'corepath'=>phpfox::getParam('core.path'),
				'rating'=>sprintf("%d", $fAVGRating),
				'iRatingCount'=>$iRatingCount,
			)
		);
		//return 'block';
	}
}
?>
