<?php


defined('PHPFOX') or exit('NO DICE!');

class MobileTemplate_Component_Block_Status extends Phpfox_Component
{
    
    public function process()
    {
    	$fullControllerName = $this->request()->get('fullControllerName');
		//	get privacy form
		$aPrivacyControls = array();
		if (!Phpfox::getParam('core.friends_only_community'))
		{
			$aPrivacyControls[] = array(
				'phrase' => Phpfox::getPhrase('privacy.everyone'),
				'value' => '0'
			);
		}
		if (Phpfox::isModule('friend'))
		{
			$aPrivacyControls[] = array(
			'phrase' => Phpfox::getPhrase('privacy.friends'),
			'value' => '1'
			);
			$aPrivacyControls[] = array(
				'phrase' => Phpfox::getPhrase('privacy.friends_of_friends'),
				'value' => '2'
			);
		}
		
		$aPrivacyControls[] = array(
			'phrase' => Phpfox::getPhrase('privacy.only_me'),
			'value' => '3'
		);
		
		if (Phpfox::isModule('friend') && !(bool) $this->getParam('privacy_no_custom', false))
		{
			$mCustomPrivacyId = $this->getParam('privacy_custom_id', null);

			$aPrivacyControls[] = array(
				'phrase' => Phpfox::getPhrase('privacy.custom_span_click_to_edit_span'),
				'value' => '4',
				'onclick' => '$Core.box(\'privacy.getFriends\', \'\', \'no_page_click=true' . ($mCustomPrivacyId === null ? '' : '&amp;custom-id=' . $mCustomPrivacyId) . '&amp;privacy-array=' . $this->getParam('privacy_array', '') . '\');'
			);

			//	get custom privacy 
			$aListOfCustom = Phpfox::getService('friend.list')->get();
			if(is_array($aListOfCustom) && count($aListOfCustom) > 0){
				$this->template()->assign(array(
						'aListOfCustom' => $aListOfCustom
					)
				);
			}
		}

		$bNoActive = true;
		if ($bNoActive === true && $this->getParam('default_privacy') != '' && ($iDefaultValue = Phpfox::getService('user.privacy')->getValue($this->getParam('default_privacy'))) && $iDefaultValue > 0)
		{
			foreach ($aPrivacyControls as $iKey => $aPrivacyControl)
			{
				if ($aPrivacyControl['value'] == $iDefaultValue)
				{
					$aPrivacyControl['phrase'] = preg_replace('/<span>(.*)<\/span>/i', '', $aPrivacyControl['phrase']);
					$aSelectedPrivacyControl = $aPrivacyControl;
					$aPrivacyControls[$iKey]['is_active'] = true;
					$bNoActive = false;
					break;					
				}		
			}	
		}

		$sPrivacyInfo = $this->getParam('privacy_info');
		if (preg_match('/(.*)\.(.*)/i', $sPrivacyInfo, $aMatches) && isset($aMatches[1]) && Phpfox::isModule($aMatches[1]))
		{
			$sPrivacyInfo = Phpfox::getPhrase($sPrivacyInfo);
		}
        
        if (empty($aSelectedPrivacyControl))
        {
            $aSelectedPrivacyControl = $aPrivacyControls[0];
        }
        
		$this->template()->assign(array(
				'sPrivacyFormType' => $this->getParam('privacy_type'),
				'sPrivacyFormName' => $this->getParam('privacy_name'),
				'sPrivacyFormInfo' => $sPrivacyInfo,
				'bPrivacyNoCustom' => (bool) $this->getParam('privacy_no_custom', false),
				'aPrivacyControls' => $aPrivacyControls,
				'aSelectedPrivacyControl' => $aSelectedPrivacyControl,
				'sPrivacyArray' => $this->getParam('privacy_array', null),
				'bNoActive' => $bNoActive, 
				'fullControllerName' => $fullControllerName
			)
		);
		return 'block';        
    }
    
    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('mobiletemplate.component_block_status_clean')) ? eval($sPlugin) : false);
    }
}

?>