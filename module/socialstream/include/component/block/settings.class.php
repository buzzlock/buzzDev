<?php

defined('PHPFOX') or exit('NO DICE!');


class SocialStream_Component_Block_Settings extends Phpfox_Component
{
    /**
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
        Phpfox::isUser(true);

        $iUserId = Phpfox::getUserId();

        $aProviders = Phpfox::getService('socialbridge')->getAllProviderData($iUserId);

        $aFacebookSetting = $aTwitterSetting = null;
        
        if (!array_key_exists('facebook', $aProviders))
        {
            $aProviders['facebook'] = null;
        }

        if ($aProviders['facebook'] && $aProviders['facebook']['connected'])
        {
            $aFacebookSetting = Phpfox::getService('socialstream.services')->getSetting($aProviders['facebook']['service'], $iUserId, $aProviders['facebook']['profile']['identity']);
        }

        if (!array_key_exists('twitter', $aProviders))
        {
            $aProviders['twitter'] = null;
        }

        if ($aProviders['twitter'] && $aProviders['twitter']['connected'])
        {
            $aTwitterSetting = Phpfox::getService('socialstream.services')->getSetting($aProviders['twitter']['service'], $iUserId, $aProviders['twitter']['profile']['identity']);
        }

        $this->template()->assign(array(
            'aFacebook' => $aProviders['facebook'],
            'aFacebookSetting' => $aFacebookSetting,
            'aTwitter' => $aProviders['twitter'],
            'aTwitterSetting' => $aTwitterSetting,
            ));
    }

    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('socialstream.component_block_settings_clean')) ? eval($sPlugin) : false);
    }

}

?>