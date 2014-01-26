<?php


defined('PHPFOX') or exit('NO DICE!');


class AdvancedMarketplace_Component_Controller_Admincp_Listingstatistics extends Phpfox_Component
{
	public function process()
	{
		$aListingStatistics = phpfox::getService('advancedmarketplace')->getListingStatistics();
		
		$this->template()->assign(array('aListingStatistics'=>$aListingStatistics))
						 ->setBreadcrumb('Listing Statistics', $this->url()->makeUrl('admincp.marketplace.listingstatistics'));
	}
}
?>