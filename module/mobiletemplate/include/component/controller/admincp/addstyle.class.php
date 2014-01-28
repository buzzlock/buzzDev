<?php

defined('PHPFOX') or exit('NO DICE!');
class MobileTemplate_Component_Controller_Admincp_Addstyle extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$oService = Phpfox::getService('mobiletemplate');
		$oServiceProcess = Phpfox::getService('mobiletemplate.process');
		
		$bIsEdit = false;
		$iStyleId = $this->request()->getInt('id');
		$aVals = $this->request()->get('val'); 
		$aDefaultStyles = $oService->getDefaultMobileCustomStyle();
        if ($iStyleId)
        {
            $aEditedStyles = $oService->getMobileCustomStyleForEdit($iStyleId);
            if (!isset($aEditedStyles) || !isset($aEditedStyles['name']))
            {
                $this->url()->send('admincp.mobiletemplate.managestyles', null, Phpfox::getPhrase('mobiletemplate.style_is_not_valid'));
            }
			
            $bIsEdit = true;
            $this->template()->assign(array('iStyleId' => $iStyleId, 'aForms' => $aEditedStyles));
			
			//	build style
			$aStyles = unserialize(base64_decode($aEditedStyles['data']));
			foreach ($aDefaultStyles as $key => $value){
				if(isset($aStyles[$key])){
					$aDefaultStyles[$key]['value'] = $aStyles[$key];
				}
			}
        }
		
        // Post data to add or edit.
        if ($aVals)
        {
            if ($bIsEdit)
            {
                if ($oServiceProcess->updatedMobileCustomStyle($iStyleId, $aVals))
                {
                    $this->url()->send('admincp.mobiletemplate.managestyles', null, Phpfox::getPhrase('mobiletemplate.mobile_custom_style_successfully_updated'));
                }
            }
            else
            {
                if ($oServiceProcess->addMobileCustomStyle($aVals))
                {
                    $this->url()->send('admincp.mobiletemplate.managestyles', null, Phpfox::getPhrase('mobiletemplate.custom_style_successfully_added'));
                }
            }
			$this->template()->assign('aForms', $aVals);
        }
		
        $aStyles = array();
        foreach ($aDefaultStyles as $key => $value)
        {
            $aStyles[] = array(
                'name' => Phpfox::getPhrase('mobiletemplate.' . $key),
                // 'name' => $key,
                'key' => $key,
                'value' => (isset($aVals) && is_array($aVals)) ? $aVals[$key] : $value['value'], 
                'type' => $value['type'] 
            );
        }
		$this->template()->setTitle(Phpfox::getPhrase('mobiletemplate.add_mobile_custom_style'));
		$this->template()->setBreadcrumb(Phpfox::getPhrase('mobiletemplate.add_mobile_custom_style'), $this->url()->makeUrl('admincp.mobiletemplate.addstyle'));
        $this->template()->assign(array('bIsEdit' => $bIsEdit, 'aStyles' => $aStyles));
		$this->template()->setHeader('cache', array(
            'jquery.minicolors.css' => 'module_mobiletemplate',
            'jquery.minicolors.js' => 'module_mobiletemplate'
        ));

	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('mobiletemplate.component_controller_admincp_addstyle_clean')) ? eval($sPlugin) : false);
	}
}

?>