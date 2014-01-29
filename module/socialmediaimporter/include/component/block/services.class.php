<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 *
 *
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		YouNet Company
 * @package 		Phpfox_SocialMediaImporter 
 */

class SocialMediaImporter_Component_Block_Services extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{		
		$aServices = Phpfox::getService('socialmediaimporter.services')->get(Phpfox::getUserId());
		//print_r($aServices );
		$this->template()->assign(array(
			'aServices' => $aServices,
			'sCoreUrl' => Phpfox::getParam('core.path'),                                			
		));
		return 'block';
	}
}
?>