<?php
/*
 * @copyright        [YouNet_COPYRIGHT]
 * @author           YouNet Company
 * @package          Module_FeedBack
 * @version          2.01
 *
 */
defined('PHPFOX') or exit('NO DICE!');

class AdvancedMarketplace_Component_Block_AdminCP_customfield_customfieldcell extends Phpfox_Component
{
	public function process()
	{
		$aCustomFields = $this->getParam('aCellCustomFields');
		$isAdd = $this->getParam('isAdd');
		/* var_dump($aCustomFields); */
		/* Phpfox::getLib('cache')->remove(); */
		$this->template()->assign(array(
			'aCellCustomFields'       => $aCustomFields,
			"sKeyVarCell" => $this->getParam("sKeyVarCell"),
			"aCustomFieldInfors" => PHPFOX::getService("advancedmarketplace")->backend_getcustomfieldinfos(),
			'corepath'=>phpfox::getParam('core.path'),
			"isAdd" => isset($isAdd),
		));
		return "block";
	}
}
?>