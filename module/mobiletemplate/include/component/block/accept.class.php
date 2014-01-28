<?php


defined('PHPFOX') or exit('NO DICE!');


class MobileTemplate_Component_Block_Accept extends Phpfox_Component
{
    
    public function process()
    {
		list($iCnt, $aFriends) = Phpfox::getService('friend.request')->get();
		
		$this->template()->assign(array(
				'aFriends' => $aFriends
			)
		);
    }
    
    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('mobiletemplate.component_block_accept_clean')) ? eval($sPlugin) : false);
    }
}

?>