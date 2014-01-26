<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class AdvancedMarketplace_Component_Block_MoreFromSeller extends Phpfox_Component
{
	public function process()
	{
		$bIsViewMore = false;
		$aListing = $this->getParam('aListing');	
		list($iCnt, $aSellerListings) = PHPFOX::getService("advancedmarketplace")->frontend_getSellerListings($aListing['listing_id'], $aListing['user_id']);
		if($iCnt > phpfox::getParam('advancedmarketplace.total_listing_more_from'))
		{
			$bIsViewMore = true;
		}
		if(empty($aSellerListings))
		{
			return false;
		}
		
        $this->template()->assign(array(
            'sHeader'                       => Phpfox::getPhrase('advancedmarketplace.more_from_seller'),
            'corepath'                      => phpfox::getParam('core.path'),
            'aInterestedListing'            => $aSellerListings,
            'advancedmarketplace_url_image' => Phpfox::getParam('core.url_pic') . "advancedmarketplace/",
			'bIsViewMore' => $bIsViewMore
        ));
        
        return 'block';
	
	}
}
?>
