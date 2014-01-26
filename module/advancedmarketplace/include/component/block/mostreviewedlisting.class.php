<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class AdvancedMarketplace_Component_Block_MostReviewedListing extends Phpfox_Component
{
	public function process()
	{
		$bIsViewMore = false;
		list($iCnt, $aReviewedListings) = phpfox::getService('advancedmarketplace')->getMostReviewedListing();
		if(empty($aReviewedListings))
		{
			return false;
		}
		if($iCnt > phpfox::getParam('advancedmarketplace.total_listing_more_from'))
		{
			$bIsViewMore = true;
		}
		$this->template()->assign(array(
                    'sHeader' => Phpfox::getPhrase('advancedmarketplace.most_reviewed_listing'),
                    'corepath'=>phpfox::getParam('core.path'),
                    'aReviewedListings' => $aReviewedListings,
					'bIsViewMore' => $bIsViewMore
								));
		return 'block';
	}
}
?>
