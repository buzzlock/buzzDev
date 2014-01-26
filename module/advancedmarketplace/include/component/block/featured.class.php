<?php


defined('PHPFOX') or exit('NO DICE!');


class AdvancedMarketplace_Component_Block_Featured extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$aFeatured = Phpfox::getService('advancedmarketplace')->getFeatured();
		
		if (!count($aFeatured))
		{
			return false;
		}
		
		$this->template()->assign(array(
                'advancedmarketplace_url_image' => Phpfox::getParam('core.url_pic') . "advancedmarketplace/",
                'corepath' => Phpfox::getParam('core.path'),
				'sHeader' => Phpfox::getPhrase('advancedmarketplace.featured_listings'),
				'aFeatured' => $aFeatured
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
		(($sPlugin = Phpfox_Plugin::get('advancedmarketplace.component_block_featured_clean')) ? eval($sPlugin) : false);
	}
}

?>