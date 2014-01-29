<?php
defined('PHPFOX') or exit('NO DICE!');
class SocialMediaImporter_Component_Controller_Admincp_Index extends Phpfox_Component
{
	public function process()
	{
        $oService = Phpfox::getService('socialmediaimporter.providers');
        if($aFacebook = $this->request()->get('facebook'))
        {			
            $iStatus = $aFacebook['is_active'];
			$aFacebook['time_to_get'] = (int)$aFacebook['time_to_get'];
			if($aFacebook['time_to_get'] == 0)
				$aFacebook['time_to_get'] = 1;
				
            unset($aFacebook['is_active']);
            $oService->addSetting('facebook',serialize($aFacebook),$iStatus);
            $this->url()->send("admincp.socialmediaimporter",null,Phpfox::getPhrase('socialmediaimporter.update_successfully')); 
        }
        if($aTwitter = $this->request()->get('twitter'))
        {			
            $iStatus = $aTwitter['is_active'];
			$aTwitter['time_to_get'] = (int)$aTwitter['time_to_get'];
			if($aTwitter['time_to_get'] == 0)
				$aTwitter['time_to_get'] = 1;
            unset($aTwitter['is_active']);
            $oService->addSetting('twitter',serialize($aTwitter),$iStatus);
            $this->url()->send("admincp.socialmediaimporter",null,Phpfox::getPhrase('socialmediaimporter.update_successfully'));  
        }
        if($aFlickr = $this->request()->get('flickr'))
        {			
            $iStatus = $aFlickr['is_active'];
			$aFlickr['time_to_get'] = (int)$aTwitter['time_to_get'];
			if($aFlickr['time_to_get'] == 0)
				$aFlickr['time_to_get'] = 1;
            unset($aFlickr['is_active']);
            $oService->addSetting('flickr',serialize($aFlickr),$iStatus);
            $this->url()->send("admincp.socialmediaimporter",null,Phpfox::getPhrase('socialmediaimporter.update_successfully'));  
        }
        if($apicasa = $this->request()->get('picasa'))
        {			
            $iStatus = $apicasa['is_active'];
			$apicasa['time_to_get'] = (int)$aTwitter['time_to_get'];
			if($apicasa['time_to_get'] == 0)
				$apicasa['time_to_get'] = 1;
            unset($apicasa['is_active']);
            $oService->addSetting('picasa',serialize($apicasa),$iStatus);
            $this->url()->send("admincp.socialmediaimporter",null,Phpfox::getPhrase('socialmediaimporter.update_successfully'));  
        }
        if($aInstagr = $this->request()->get('instagr'))
        {			
            $iStatus = $aInstagr['is_active'];
			$aInstagr['time_to_get'] = (int)$aTwitter['time_to_get'];
			if($aInstagr['time_to_get'] == 0)
				$aInstagr['time_to_get'] = 1;
            unset($aInstagr['is_active']);
            $oService->addSetting('instagr',serialize($aInstagr),$iStatus);
            $this->url()->send("admincp.socialmediaimporter",null,Phpfox::getPhrase('socialmediaimporter.update_successfully'));  
        }

		$aSupportedTimes = array(1 => Phpfox::getPhrase('socialmediaimporter.minutes'),
							  2 => Phpfox::getPhrase('socialmediaimporter.hours'),
							  3 => Phpfox::getPhrase('socialmediaimporter.days'),
							  4 => Phpfox::getPhrase('socialmediaimporter.months'),
							  5 => Phpfox::getPhrase('socialmediaimporter.years'));
				
		$aProviders = $oService->getProviders(false);
		$this->template()->setTitle(Phpfox::getPhrase('socialmediaimporter.mange_social_providers'))
			->setBreadcrumb(Phpfox::getPhrase('socialmediaimporter.mange_social_providers'), $this->url()->makeUrl('admincp.socialmediaimporter'))				
			->setPhrase(array(
				'socialmediaimporter.view',
				'socialmediaimporter.hide',
				)
			)
			->assign(array(
					'aProviders' => $aProviders,
                    'sCoreUrl' => Phpfox::getParam('core.path'),
                    'sCallBackUrl' => Phpfox::getParam('core.path').'module/socialmediaimporter/static/php/static.php',
					'aSupportedTimes' => $aSupportedTimes
				)
			);			
	}
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('socialmediaimporter.component_controller_admincp_providers_clean')) ? eval($sPlugin) : false);
	}
}

?>