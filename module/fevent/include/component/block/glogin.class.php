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

class Fevent_Component_Block_Glogin extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
        $event_id = $this->request()->get('id');
        
        $this->template()->assign(array(
            'core_path' => Phpfox::getParam('core.path'),
            'event_id' => $event_id,
        ));
	}
}