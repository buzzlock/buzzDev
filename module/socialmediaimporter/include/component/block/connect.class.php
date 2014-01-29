<?php

defined('PHPFOX') or exit('NO DICE!');

class SocialMediaImporter_Component_Block_Connect extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{	
		$sService = $this->getParam("service", 'facebook');
		$this->template()->assign(array(
			'sService' => $sService,			
			'aProvider' => $aProvider = Phpfox::getService('socialmediaimporter.providers')->getProvider($sService),
			'sConnectUrl' => Phpfox::getLib('url')->makeUrl('socialmediaimporter.' . $sService, array('status' => 1, 'redirect' => 1))			
		));
		return 'block';
	}
}
?>