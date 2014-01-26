<?php

defined('PHPFOX') or exit('NO DICE!');

class AdvancedMarketplace_Component_Block_FrontEnd_ViewCustomField extends Phpfox_Component
{
	public function process()
	{
		$aCustomFields = $this->getParam("aCustomFields");
		$cfInfors = $this->getParam("cfInfors");
		// var_dump($aCustomFields);exit;
		$this->template()->assign(array(
			'aCustomFields'=> $aCustomFields,
			'cfInfors'=> $cfInfors,
		));
		//return "block";
	}
}
?>