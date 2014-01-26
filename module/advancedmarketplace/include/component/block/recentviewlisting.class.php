<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class AdvancedMarketplace_Component_Block_RecentViewListing extends Phpfox_Component
{
	public function process()
	{
		$bIsNoRecent = $this->getParam("bIsNoRecent");
		$bIsViewMore = false;
		list($iCnt, $aRecentViewListing) = PHPFOX::getService("advancedmarketplace")->frontend_getRecentViewListings(phpfox::getUserId(), NULL, 3);
		if(empty($aRecentViewListing) || $bIsNoRecent)
		{
			return false;
		}
		if($iCnt > phpfox::getParam('advancedmarketplace.total_listing_more_from'))
		{
			$bIsViewMore = true;
		}
        $this->template()->assign(array(
            'sHeader'                       => Phpfox::getPhrase('advancedmarketplace.recent_viewed_listing'),
            'corepath'                      => phpfox::getParam('core.path'),
            'aRecentViewListing'            => $aRecentViewListing,
            'advancedmarketplace_url_image' => Phpfox::getParam('core.url_pic') . "advancedmarketplace/",
			'bIsViewMore' 					=> $bIsViewMore
        ));
        
        return 'block';
	}
}
?>
