<?php


defined('PHPFOX') or exit('NO DICE!');


class MobileTemplate_Component_Block_Latest extends Phpfox_Component
{
    
    public function process()
    {
		$aMessages = Phpfox::getService('mail')->getLatest();
		foreach ($aMessages as $iKey => $aMessage)
		{
			$aMessages[$iKey]['preview'] = strip_tags(str_replace(array('&lt;','&gt;'), array('<','> '), $aMessage['preview']));
		}
		
		$this->template()->assign(array(
				'aMessages' => $aMessages
			)
		);	
    }
    
    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('mobiletemplate.component_block_latest_clean')) ? eval($sPlugin) : false);
    }
}

?>