<?php

defined('PHPFOX') or exit('NO DICE!');


class AdvancedMarketplace_Component_Block_Sponsored extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		if (!Phpfox::isModule('ad'))
		{
			return false;
		}		
		
		$aItems = Phpfox::getService('advancedmarketplace')->getSponsorListings();
		if (empty($aItems))
		{
		    return false;
		}
		
		foreach ($aItems as $aItem)
		{
		    Phpfox::getService('ad.process')->addSponsorViewsCount($aItem['sponsor_id'], 'advancedmarketplace');
		}
		
		$this->template()->assign(array(
            'advancedmarketplace_url_image' => Phpfox::getParam('core.url_pic') . "advancedmarketplace/",
            'corepath' => Phpfox::getParam('core.path'),
			'sHeader' => Phpfox::getPhrase('advancedmarketplace.sponsored_listing'),
			'aFooter' => array(Phpfox::getPhrase('advancedmarketplace.encourage_sponsor') => $this->url()->makeUrl('advancedmarketplace', array('view' => 'my', 'sponsor' => 'help'))),
			'aSponsorListings' => $aItems
			)
		);

		return 'block';
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('advancedmarketplace.component_block_photo_clean')) ? eval($sPlugin) : false);
	}
}

?>