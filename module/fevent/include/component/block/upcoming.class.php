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
class Fevent_Component_Block_Upcoming extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$aParentModule = $this->getParam('aParentModule');
		$bIsPage = $aParentModule['module_id'] == 'pages' ? $aParentModule['item_id'] : 0;
		//$aUser = $this->getParam('aUser');
		//$bIsProfile = !empty($aUser['user_id']) ?  $aUser['user_id'] : false;
		list($iTotal, $aUpcoming) = Phpfox::getService('fevent')->getUpcoming($bIsPage, false);
		
		if (!$iTotal)
		{
			return false;
		}
		
		$this->template()->assign(array(
				'sHeader' => Phpfox::getPhrase('fevent.upcoming_events'),
				'aUpcoming' => $aUpcoming,
				'bViewMore' => $iTotal > 7
			)
		);
		
		return 'block';		
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('fevent.component_block_upcoming_clean')) ? eval($sPlugin) : false);
	}
}

?>