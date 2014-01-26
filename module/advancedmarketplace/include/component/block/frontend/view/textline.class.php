<?php

defined('PHPFOX') or exit('NO DICE!');

class AdvancedMarketplace_Component_Block_FrontEnd_View_textline extends Phpfox_Component
{
	public function process()
	{
		$aField = $this->getParam("aField");
		$cfInfors = $this->getParam("cfInfors");
		
		$sDisplay = $cfInfors[$aField["var_type"]]["tag"];
		
		$sDisplay = str_replace("jh_#%id%#_", "custom_field_" . $aField["field_id"], $sDisplay);
		$sDisplay = str_replace("jh_#%name%#_", sprintf("customfield[%s]",$aField["field_id"]), $sDisplay);
		$sDisplay = str_replace("jh_#%value%#_", $aField["field_id"], $sDisplay);
		$sDisplay = str_replace("jh_#%class%#_", "cus_textline", $sDisplay);
		$sDisplay = str_replace("jh_#%custom_attribute%#_", "", $sDisplay);
		$sDisplay = str_replace("jh_#%text%#_", $aField["data"], $sDisplay);
		// var_dump($aField);exit;
		// var_dump($sDisplay);exit;
		// var_dump($cfInfors);
		$this->template()->assign(array(
			"sPhraseVarName" => $aField["phrase_var_name"],
			"sDisplay" => $sDisplay
		));
		// return "block";
	}
}
?>