<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class AdvancedMarketplace_Component_Block_Review extends Phpfox_Component
{
	public function process()
	{
		$aListing = ($this->getParam("aListing"));
		$aRating = ($this->getParam("aRating"));
		$iCount = ($this->getParam("iCount"));
		$iPage = ($this->getParam("iPage", "0"));
		$iSize = ($this->getParam("iSize"));
		
		$aMenus = array(
			Phpfox::getPhrase('advancedmarketplace.listing_detail')=>'#advancedmarketplace.listingdetail?id=js_listingdetail_container&lid=' . $aListing['listing_id'],
			Phpfox::getPhrase('advancedmarketplace.review')=>'#advancedmarketplace.review?id=js_review_container&lid=' . $aListing['listing_id'],
		);
		
		$aParam = array(
			'ajax' => 'advancedmarketplace.reviewpaging',
			'page' => $iPage,
			'size' => $iSize,
			'count' => $iCount,
			'aParams' => array(
				'lid' => $aListing['listing_id']
			)
		);
		Phpfox::getLib('pager')->set($aParam);
		
		$this->template()->assign(array(
			'corepath'=>phpfox::getParam('core.path'),
			// 'aMenu'=>$aMenus,
			'aListing' => $aListing,
			'iCount' => $iCount,
			'aRating' => $aRating,
			'page' => $iPage,
			'sHeader' => '',
			"iCurrentUserId" => PHPFOX::getUserId()
		));
		// return 'block';
	}
}
?>
