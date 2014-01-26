<?php

defined('PHPFOX') or exit('NO DICE!');

class AdvancedMarketplace_Component_Block_AdminCP_TodayListing extends Phpfox_Component
{
	public function process()
	{
		$iId = $this->getParam("iId");
		$aTListing = PHPFOX::getService("advancedmarketplace")->getTodayListing($iId);
		$this->template()->assign(array(
			"iId" => $iId,
			"aTListing" => $aTListing
		));
		return "block";
	}
}
?>