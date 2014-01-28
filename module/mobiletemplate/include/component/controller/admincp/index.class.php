<?php

defined('PHPFOX') or exit('NO DICE!');

class MobileTemplate_Component_Controller_Admincp_Index extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
         $aAllThemes = Phpfox::getService('mobiletemplate')->getAllThemes();
         $aAllStyles = Phpfox::getService('mobiletemplate')->getAllStyles();
        $aActiveStyle = Phpfox::getService('mobiletemplate')->getActiveMobileStyle();

		$this->template()->setTitle(Phpfox::getPhrase('mobiletemplate.mobile_theme'));
		$this->template()->setBreadcrumb(Phpfox::getPhrase('mobiletemplate.mobile_theme'), $this->url()->makeUrl('admincp.mobiletemplate'));
        $this->template()->assign(array(
                 'aAllThemes' => $aAllThemes,
                 'aAllStyles' => $aAllStyles,
                 'aActiveStyle' => $aActiveStyle
            )
        );
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('mobiletemplate.component_controller_admincp_index_clean')) ? eval($sPlugin) : false);
	}
}

?>