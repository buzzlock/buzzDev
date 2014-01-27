<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright		[YOUNET_COPPYRIGHT]
 * @author  		AnNT
 * @package  		Module_jobposting
 */

class Jobposting_Component_Block_Application_View extends Phpfox_Component 
{
    /**
	 * Class process method wnich is used to execute this component.
	 */
    public function process()
    {
        $iId = $this->request()->get('id');
        
        $aItem = Phpfox::getService('jobposting.application')->getForView($iId);
        
        $this->template()->assign(array(
            'aItem' => $aItem,
            'urlModule' => Phpfox::getParam('core.url_module')
        ));
    }
}