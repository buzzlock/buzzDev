<?php

defined('PHPFOX') or exit('NO DICE!');

class SocialMediaImporter_Component_Block_ImportAlbumsPopup extends Phpfox_Component
{
	public function process()
	{
		$iDefaultPrivacy = 3;
		switch(Phpfox::getParam('socialmediaimporter.default_privacy'))
		{
			case "Everyone":
				$iDefaultPrivacy = 0;
				break;
			case "Friends":
				$iDefaultPrivacy = 1;
				break;
			case "Friends of Friends":
				$iDefaultPrivacy = 2;
				break;			
			default:				
				$iDefaultPrivacy = 3;				
		}
		
		$this->template()->assign(array(
			"sCorePath" => Phpfox::getParam('core.path'),
			"iDefaultPrivacy" => $iDefaultPrivacy,
			"sService" => $this->getParam("service"),
			"aForms" => array ("privacy" => $iDefaultPrivacy),
			"iMaxImport" => Phpfox::getParam('socialmediaimporter.max_import_per_time'),
		));
	}
}
?>