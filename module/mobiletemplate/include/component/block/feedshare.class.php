<?php


defined('PHPFOX') or exit('NO DICE!');


class MobileTemplate_Component_Block_Feedshare extends Phpfox_Component
{
    
    public function process()
    {
        $iFeedId = $this->request()->getInt('feed_id');
        $sShareModule = $this->request()->get('sharemodule');
        $aShareModule = explode('_', $sShareModule);
        if (!Phpfox::isModule($aShareModule[0]))
        {
            return false;
        }
        
        $this->template()->assign(array(
                'iFeedId' => $iFeedId,
                'sShareModule' => $sShareModule
            )
        );        
    }
    
    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('mobiletemplate.component_block_feedshare_clean')) ? eval($sPlugin) : false);
    }
}

?>