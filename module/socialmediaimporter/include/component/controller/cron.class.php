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
class SocialMediaImporter_Component_Controller_Cron extends Phpfox_Component 
{
	public function process() 
	{		
		set_time_limit(15*60*60);
		$sService = $this->request()->get('service', '');
		$iUserId = $this->request()->get('user_id', 0);
		$iLimit = $this->request()->get('limit', 0);
		if (Phpfox::isModule('socialmediaimporter')) 
		{
			Phpfox::getService('socialmediaimporter.process')->cronImportPhoto($sService, $iUserId, $iLimit);
			echo 'Success';
		}
		exit;
	}
}
?>