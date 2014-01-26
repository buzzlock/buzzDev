<?php


defined('PHPFOX') or exit('NO DICE!');


class AdvancedMarketplace_Component_Block_Photo extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$aListing = $this->getParam('aListing');
		
		if (!($aImages = Phpfox::getService('advancedmarketplace')->getImages($aListing['listing_id'])))
		{
			return false;
		}
		
		$this->template()->assign(array(
				'aImages' => $aImages,
				'advancedmarketplace_url_image' => Phpfox::getParam('core.url_pic') . "advancedmarketplace/",
			)
		);
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