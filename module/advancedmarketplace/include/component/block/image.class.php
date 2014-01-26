<?php


defined('PHPFOX') or exit('NO DICE!');


class AdvancedMarketplace_Component_Block_Image extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		if (!($aListing = $this->getParam('aListing')))
		{
			return false;
		}
		
		if (empty($aListing['image_path']))
		{
			// return false;
		}
		
		$this->template()->assign(array(
				'aImages' => Phpfox::getService('advancedmarketplace')->getImages($aListing['listing_id']),
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
		(($sPlugin = Phpfox_Plugin::get('advancedmarketplace.component_block_image_clean')) ? eval($sPlugin) : false);
	}
}

?>