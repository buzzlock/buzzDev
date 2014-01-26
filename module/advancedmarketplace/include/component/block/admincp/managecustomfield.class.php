<?php

defined('PHPFOX') or exit('NO DICE!');

class AdvancedMarketplace_Component_Block_AdminCP_ManageCustomField extends Phpfox_Component
{
	public function process()
	{
		$iCatId = $this->getParam("lid");
		$aCustomFieldGroups = PHPFOX::getService("advancedmarketplace.customfield.advancedmarketplace")->loadAllCustomFieldGroup($iCatId);
		$this->template()->assign(array(
			'corepath'=>phpfox::getParam('core.path'),
			'iListingId'=> $iCatId,
			'sKeyVar'=> $this->getParam("sKeyVar"),
			'sText'=> $this->getParam("sText"),
			'aCustomFieldGroups'=> $aCustomFieldGroups,
			"aCustomFieldInfors" => PHPFOX::getService("advancedmarketplace")->backend_getcustomfieldinfos(),
		));
		return "block";
	}
}
?>