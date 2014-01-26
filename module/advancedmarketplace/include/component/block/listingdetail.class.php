<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class AdvancedMarketplace_Component_Block_ListingDetail extends Phpfox_Component
{
	public function process()
	{
		$aListing = ($this->getParam("aListing"));
		$aMenus = array(
			Phpfox::getPhrase('advancedmarketplace.listing_detail')=>'#advancedmarketplace.listingdetail?id=js_listingdetail_container&lid=' . $aListing['listing_id'],
			Phpfox::getPhrase('advancedmarketplace.review')=>'#advancedmarketplace.review?id=js_review_container&lid=' . $aListing['listing_id'],
		);
		// custom field
		$iCatId = $aListing["category"]["category_id"];
		$iListingId = $aListing["listing_id"];
		// var_dump($iCatId);exit;
		$aCustomFields = PHPFOX::getService("advancedmarketplace.customfield.advancedmarketplace")->frontend_loadCustomFields($iCatId, $iListingId);
		$cfInfors = PHPFOX::getService("advancedmarketplace")->backend_getcustomfieldinfos();
		///custom field
		$this->template()->assign(array(
			'corepath'=>phpfox::getParam('core.path'),
			// 'aMenu'=>$aMenus,
			'aListing' => $aListing,
			'aCustomFields' => $aCustomFields,
			'cfInfors' => $cfInfors,
			'sHeader' => ''
		));
		return 'block';
	}
}
?>
