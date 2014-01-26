<?php


defined('PHPFOX') or exit('NO DICE!');


class AdvancedMarketplace_Component_Block_My extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$aListing = $this->getParam('aListing');
		
		list($iCnt, $aListings) = Phpfox::getService('advancedmarketplace')->getUserListings($aListing['listing_id'], $aListing['user_id']);
		
		if (!$iCnt)
		{
			return false;
		}
		
		$this->template()->assign(array(
				'sHeader' => Phpfox::getPhrase('advancedmarketplace.more_from_seller'),
				'aMyListings' => $aListings
			)
		);
		
		if ($iCnt > Phpfox::getParam('advancedmarketplace.total_listing_more_from'))
		{
			$this->template()->assign(array(
					'aFooter' => array(
						Phpfox::getPhrase('advancedmarketplace.view_more') => $this->url()->makeUrl($aListing['user_name'], array('advancedmarketplace'))
					)
				)
			);
		}
		
		return 'block';				
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('advancedmarketplace.component_block_my_clean')) ? eval($sPlugin) : false);
	}
}

?>