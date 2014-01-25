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
class Fevent_Component_Block_Map extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		return false;		
		
		$aEvent = $this->getParam('aEvent');
		if (Phpfox::getUserParam('fevent.can_view_gmap') == false || !isset($aEvent['gmap']['latitude']))
		{
			return false;
		}
		$this->template()->assign(array(
				'sHeader' => Phpfox::getPhrase('fevent.find_on_map')
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
		(($sPlugin = Phpfox_Plugin::get('fevent.component_block_info_clean')) ? eval($sPlugin) : false);
	}
}

?>