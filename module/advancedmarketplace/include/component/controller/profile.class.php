<?php


defined('PHPFOX') or exit('NO DICE!');


class AdvancedMarketplace_Component_Controller_Profile extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{		
		$this->setParam('bIsProfile', true);
		
		Phpfox::getComponent('advancedmarketplace.index', array('bNoTemplate' => true), 'controller');
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('advancedmarketplace.component_controller_profile_clean')) ? eval($sPlugin) : false);
	}
}

?>