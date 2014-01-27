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
class JobPosting_Component_Block_Company_Image extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$iCompany = Phpfox::getLib("database")
                        ->select('company_id')
                        ->from(Phpfox::getT('user_field'))
                        ->where('user_id =' . Phpfox::getUserId())
						->execute('getField');
		$ControllerName = Phpfox::getLib("module")->getFullControllerName();			
		if(!$aCompany = $this->getParam('aCompany'))
        {
            return false;
        }
   		
        if (!$aImages = Phpfox::getService('jobposting.company')->getImages($aCompany['company_id']))
        {
           // return false;
        }
       	if($iCompany!=$aCompany['company_id']){
       		$iCompany = 0;
       	}
        $this->template()->assign(array(
            'aImages' => $aImages,
            'iCompany' => $iCompany,
            'ControllerName' => trim($ControllerName),
        ));
		
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('fevent.component_block_image_clean')) ? eval($sPlugin) : false);
	}
}

?>