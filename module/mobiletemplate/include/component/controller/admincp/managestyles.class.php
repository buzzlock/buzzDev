<?php

defined('PHPFOX') or exit('NO DICE!');

class MobileTemplate_Component_Controller_Admincp_Managestyles extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
         
        if ($this->request()->get('deleteSelected') && $aIds = $this->request()->getArray('id'))
        {
            if (Phpfox::getService('mobiletemplate.process')->deleteMobileCustomStyleByListOfID($aIds))
            {
                $this->url()->send('admincp.mobiletemplate.managestyles', null, Phpfox::getPhrase('mobiletemplate.mcs_successfully_deleted'));
            }
        }
		
        if ($this->request()->get('req4')=='delete')
        {
            $iDeleteId = $this->request()->get('id');
            if (Phpfox::getService('mobiletemplate.process')->deleteMobileCustomStyleByID($iDeleteId))
            {
                $this->url()->send('admincp.mobiletemplate.managestyles', null, Phpfox::getPhrase('mobiletemplate.mcs_successfully_deleted'));
            }
        }		
		
		 $aAllCustomStyles = Phpfox::getService('mobiletemplate')->getAllMobileCustomStyles();
		 
		$this->template()->setTitle(Phpfox::getPhrase('mobiletemplate.manage_mobile_custom_styles'));
		$this->template()->setBreadcrumb(Phpfox::getPhrase('mobiletemplate.manage_mobile_custom_styles'), $this->url()->makeUrl('admincp.mobiletemplate.managestyles'));
        $this->template()->assign(array(
                 'aAllCustomStyles' => $aAllCustomStyles 
            )
        );
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('mobiletemplate.component_controller_admincp_managestyles_clean')) ? eval($sPlugin) : false);
	}
}

?>