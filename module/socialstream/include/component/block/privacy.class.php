<?php

defined('PHPFOX') or exit('NO DICE!');


class SocialStream_Component_Block_Privacy extends Phpfox_Component
{

    /**
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
        $sProvider = $this->getParam('provider');
        $aProvider = phpfox::getService('socialbridge')->getAllProviderData(Phpfox::getUserId());

        $aPrivacyControls = array();
        $aPrivacyControls[] = array(
            'phrase' => Phpfox::getPhrase('privacy.everyone'),
            'value' => '0',
            );
        
        if (Phpfox::isModule('friend'))
        {
            $aPrivacyControls[] = array(
                'phrase' => Phpfox::getPhrase('privacy.friends'),
                'value' => '1',
                );
            
            $aPrivacyControls[] = array(
                'phrase' => Phpfox::getPhrase('privacy.friends_of_friends'),
                'value' => '2',
                );
        }

        $aPrivacyControls[] = array(
            'phrase' => Phpfox::getPhrase('privacy.only_me'),
            'value' => '3',
            );

        if (Phpfox::isModule('friend') && !(bool)$this->getParam('privacy_no_custom', false))
        {
            $aPrivacyControls[] = array(
                'phrase' => Phpfox::getPhrase('privacy.custom_span_click_to_edit_span'),
                'value' => '4',
                );
        }

        $bNoActive = true;
        $aSelectedPrivacyControl = array();
        foreach ($aPrivacyControls as $iKey => $aPrivacyControl)
        {
            if (!empty($aProvider) && isset($aProvider[$sProvider]['profile']['privacy']))
            {
                if ($aPrivacyControl['value'] == $aProvider[$sProvider]['profile']['privacy'])
                {
                    $aPrivacyControl['phrase'] = preg_replace('/<span>(.*)<\/span>/i', '', $aPrivacyControl['phrase']);
                    $aSelectedPrivacyControl = $aPrivacyControl;
                    $aPrivacyControls[$iKey]['is_active'] = true;
                    $bNoActive = false;
                    break;
                }
            }
            else
            {
                $aSelectedPrivacyControl = $aPrivacyControl;
                break;
            }
        }

        if ($bNoActive === true && ($iDefaultValue = $this->getParam('default_privacy')) && $iDefaultValue > 0)
        {
            foreach ($aPrivacyControls as $iKey => $aPrivacyControl)
            {
                if ($aPrivacyControl['value'] == $iDefaultValue)
                {
                    $aPrivacyControl['phrase'] = preg_replace('/<span>(.*)<\/span>/i', '', $aPrivacyControl['phrase']);
                    $aSelectedPrivacyControl = $aPrivacyControl;
                    $aPrivacyControls[$iKey]['is_active'] = true;
                    break;
                }
            }
        }

        $sPrivacyInfo = $this->getParam('privacy_info');
        if (preg_match('/(.*)\.(.*)/i', $sPrivacyInfo, $aMatches) && isset($aMatches[1]) && Phpfox::isModule($aMatches[1]))
        {
            $sPrivacyInfo = Phpfox::getPhrase($sPrivacyInfo);
        }

        $this->template()->assign(array(
            'sPrivacyFormType' => $this->getParam('privacy_type'),
            'sPrivacyFormName' => $this->getParam('privacy_name'),
            'sPrivacyFormInfo' => $sPrivacyInfo,
            'bPrivacyNoCustom' => (bool)$this->getParam('privacy_no_custom', false),
            'aPrivacyControls' => $aPrivacyControls,
            'aSelectedPrivacyControl' => $aSelectedPrivacyControl,
            'sPrivacyArray' => $this->getParam('privacy_array', null)
            ));
    }

    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('socialstream.component_block_privacy_clean')) ? eval($sPlugin) : false);

        $this->template()->clean(array(
            'sPrivacyFormName',
            'sPrivacyFormInfo',
            'bPrivacyNoCustom',
            'sPrivacyArray'
            ));

        $this->clearParam('privacy_no_custom');
        $this->clearParam('privacy_custom_id');
        $this->clearParam('privacy_array');
        $this->clearParam('default_privacy');
    }

}

?>