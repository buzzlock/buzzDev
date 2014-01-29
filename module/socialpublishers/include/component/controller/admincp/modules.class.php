<?php
defined('PHPFOX') or exit('NO DICE!');
class SocialPublishers_Component_Controller_Admincp_Modules extends Phpfox_Component
{
	public function process()
	{
        $oService = phpfox::getService('socialpublishers.modules');
        if($aVals = $this->request()->get('val'))
        {
            $oService->updateSettings($aVals);
            $this->url()->send("admincp.socialpublishers.modules",null,Phpfox::getPhrase('socialpublishers.update_successfully'));
        }
		$aModules = $oService->getModules(false);
		$this->template()->setTitle(Phpfox::getPhrase('socialpublishers.mange_modules'))
			->setBreadcrumb(Phpfox::getPhrase('socialpublishers.mange_modules'), $this->url()->makeUrl('admincp.socialpublishers.providers'))	
			->assign(array(
					'aModules' => $aModules,
                    'sCoreUrl' =>phpfox::getParam('core.path')
				)
			);			
	}
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('socialpublishers.component_controller_admincp_providers_clean')) ? eval($sPlugin) : false);
	}
}

?>