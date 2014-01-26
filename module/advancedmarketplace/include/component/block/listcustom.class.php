<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class AdvancedMarketplace_Component_Block_ListCustom extends Phpfox_Component
{
	public function process()
	{
		$aCustomGroup = PHPFOX::getService('advancedmarketplace.custom.group')->getForListing();
		
		$this->template()->assign(array(
			"aCustomGroup" => $aCustomGroup
		));
		
		return 'block';
	}
}
?>