<?php

/*
 * @copyright        [YouNet_COPYRIGHT]
 * @author           YouNet Development
 * @package          Module_Contactimporter
 * @version          2.06
 *
 */
defined('PHPFOX') or exit('NO DICE!');

class Contactimporter_Component_Block_Home_contact extends Phpfox_Component
{

	public function process()
	{
		$top_5_email = phpfox::getService('contactimporter') -> getTopProviders();
		
		$this -> template() -> assign(array(
			'icon_size' => phpfox::getService('contactimporter') -> getIconSize(),
			'top_5_email' => $top_5_email,
			'more_path' => Phpfox::getLib('url') -> makeUrl('contactimporter'),
			'core_url' => Phpfox::getParam('core.path'),
			'sHeader' => Phpfox::getPhrase('contactimporter.homepage_contact'),
			'sDeleteBlock' => 'dashboard',
			'Ynscontactimporter.css' => 'module_contactimporter',
			'jquery.min.js' => 'module_contactimporter',
			'contactimporter.js' => 'module_contactimporter'
		));

		return 'block';
	}

}
