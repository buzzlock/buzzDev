<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class AdvancedMarketplace_Component_Block_InterestedListing extends Phpfox_Component
{
	public function process()
	{
		$bIsViewMore = false;
		$aListing = $this->getParam('aListing');	
		list($iCnt, $aInterestedListings) = PHPFOX::getService("advancedmarketplace")->frontend_getInterestedListings($aListing['listing_id']);
		if($iCnt > phpfox::getParam('advancedmarketplace.total_listing_more_from'))
		{
			$bIsViewMore = true;
		}
		if(empty($aInterestedListings))
		{
			return false;
		}
		
        $this->template()->assign(array(
            'sHeader'                       => Phpfox::getPhrase('advancedmarketplace.listing_you_may_interested'),
            'corepath'                      => phpfox::getParam('core.path'),
            'aInterestedListings'            => $aInterestedListings,
            'advancedmarketplace_url_image' => Phpfox::getParam('core.url_pic') . "advancedmarketplace/",
			'bIsViewMore'					=> $bIsViewMore
        ));

        return 'block';
	}
}
?>
