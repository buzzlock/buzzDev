<?php

defined('PHPFOX') or exit('NO DICE!');


class AdvancedMarketplace_Component_Controller_Up extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$iEditId = $this->request()->get('id');
		Phpfox::getService('advancedmarketplace.process')->uploadImages($iEditId, NULL);
		$oTemplate = Phpfox::getLib('template');
		$oTemplate
			->assign(array(
				
			))
			->setHeader('cache', array(
					'pager.css' => 'style_css',
					'country.js' => 'module_core',
					'browse.css' => 'module_advancedmarketplace',
					'comment.css' => 'style_css',
					'feed.js' => 'module_feed'						
				)
			);
		$oTemplate->getTemplate("advancedmarketplace.block.uploadjscontrol");
		(($sPlugin = Phpfox_Plugin::get('advancedmarketplace.component_controller_up_process_end')) ? eval($sPlugin) : false);
		exit;
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('advancedmarketplace.component_controller_up_clean')) ? eval($sPlugin) : false);
	}
}

?>