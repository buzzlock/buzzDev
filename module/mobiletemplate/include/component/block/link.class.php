<?php


defined('PHPFOX') or exit('NO DICE!');


class MobileTemplate_Component_Block_Link extends Phpfox_Component
{
    
    public function process()
    {
		$this->template()->assign(array(
				'aNotifications' => Phpfox::getService('notification')->get()			
			)
		);		
    }
    
    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('mobiletemplate.component_block_link_clean')) ? eval($sPlugin) : false);
    }
}

?>