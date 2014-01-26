<?php
/*
 * @copyright        [YouNet_COPYRIGHT]
 * @author           YouNet Development
 * @package          Module_Contactimporter
 * @version          2.06
 *
 */
defined('PHPFOX') or exit('NO DICE!');

class Contactimporter_Component_Block_Linkedin extends Phpfox_Component
{
	public function process()
	{

		$this -> template() -> assign(array(
			'core_path' => phpfox::getParam('core.path'),
			'sCallback' => urlencode(Phpfox::getLib('url') -> makeUrl('contactimporter.linkedin')),
		));

	}
}