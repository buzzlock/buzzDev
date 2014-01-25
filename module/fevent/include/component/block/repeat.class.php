<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');
/**
 * 
 * 
 * @copyright       [YOUNET_COPYRIGHT]
 * @author          YouNet Company
 * @package         YouNet_Event
 */
class Fevent_Component_Block_Repeat extends Phpfox_Component
{
	public function process()
	{
		$value=$this->getParam("value");
		$txtrepeat=$this->getParam("txtrepeat");
	
		$daterepeat=$this->getParam("daterepeat");
		$this->template()->assign(array(
			'core_path' => phpfox::getParam("core.path"),
			'value' => $value,
			'txtrepeat' => $txtrepeat,
			'daterepeat' => $daterepeat,
		));
		return 'block';
	}
}
