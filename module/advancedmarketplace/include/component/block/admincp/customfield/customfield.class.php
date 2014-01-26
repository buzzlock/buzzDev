<?php

defined('PHPFOX') or exit('NO DICE!');

class AdvancedMarketplace_Component_Block_AdminCP_customfield_customfieldgroup extends Phpfox_Component
{
	public function process()
	{
		$this->template()->assign(array(
			'corepath'       => phpfox::getParam('core.path'),
			"sKeyVar" => $this->getParam("sKeyVar"),
			"sText" => $this->getParam("sText")
		));
		return "block";
	}
}
?>