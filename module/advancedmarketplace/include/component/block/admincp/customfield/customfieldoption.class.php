<?php

defined('PHPFOX') or exit('NO DICE!');

class AdvancedMarketplace_Component_Block_AdminCP_customfield_customfieldoption extends Phpfox_Component
{
	public function process()
	{
		$this->template()->assign(array(
			"iCusfieldId" => $this->getParam("iCusfieldId"),
			"sTextOption" => (($this->getParam("sTextOption"))?($this->getParam("sTextOption")):(PHPFOX::getPhrase($this->getParam("sKeyVarOption")))),
			"sKeyVarOption" => $this->getParam("sKeyVarOption"),
			'corepath'=>phpfox::getParam('core.path'),
		));
		return "block";
	}
}
?>