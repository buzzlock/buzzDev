<?php

defined('PHPFOX') or exit('NO DICE!');

class AdvancedMarketplace_Component_Block_FrontEnd_View_selectcheckbox extends Phpfox_Component
{
	public function process()
	{
		$aField = $this->getParam("aField");
		$cfInfors = $this->getParam("cfInfors");
		
		$sDisplay = $cfInfors[$aField["var_type"]];
		
		// $sDisplay = str_replace("jh_#%id%#_", "custom_field_" . $aField["field_id"], $sDisplay);
		// var_dump($aField);exit;
		// var_dump($cfInfors);
		$this->template()->assign(array(
			"sPhraseVarName" => $aField["phrase_var_name"],
			"aField" => $aField
		));
		// return "block";
	}
}
?>