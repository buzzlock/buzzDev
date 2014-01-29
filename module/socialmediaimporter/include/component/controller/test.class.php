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
 * @package         YouNet_SocialMediaImporter
 */
class SocialMediaImporter_Component_Controller_Test extends Phpfox_Component 
{
	public function process() 
	{		
		$o = Phpfox::getService('socialmediaimporter.cache');
		$sId = $o->set('key');
		if (!($value = $o->get($sId, 60)))
		{
			$o->save($sId, 'Hello');	
		}
	}
}
?>