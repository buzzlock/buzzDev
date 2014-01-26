<?php

defined('PHPFOX') or exit('NO DICE!');

class AdvancedMarketplace_Component_Block_AdminCP_customfield_groupcustomfields extends Phpfox_Component
{
	public function process()
	{
		$aCustomFields = $this->getParam("aCustomFields");
		$sKeyVar = $this->getParam("sKeyVar");
		$this->template()->assign(array(
			'aCustomFields'       => $aCustomFields,
			"sKeyVar" => $sKeyVar,
			"sGroupName" =>PHPFOX::getPhrase("advancedmarketplace." . $sKeyVar),
			'corepath'=>phpfox::getParam('core.path'),
		));
		
		return "block";
	}
}
?>