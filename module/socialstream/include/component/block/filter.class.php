<?php

defined('PHPFOX') or exit('NO DICE!');


class SocialStream_Component_Block_Filter extends Phpfox_Component
{

    /**
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
        if (!Phpfox::isModule('socialbridge'))
        {
            return false;
        }

        $sFullControllerName = Phpfox::getLib('module')->getFullControllerName();

        if (Phpfox::getService('socialstream.services')->checkActivityFeedBlock($sFullControllerName))
        {
            return false;
        }
        
        $aProviders = Phpfox::getService('socialbridge')->getAllProviderData(Phpfox::getUserId());

        if (!array_key_exists('facebook', $aProviders))
        {
            $aProviders['facebook'] = null;
        }
        if (!array_key_exists('twitter', $aProviders))
        {
            $aProviders['twitter'] = null;
        }
        if (!$aProviders['facebook'] && !$aProviders['twitter'])
        {
            return false;
        }

        $aUser = array();
        if ($sFullControllerName == 'profile.index')
        {
            $aUser = phpfox::getLib('template')->getVar('aUser');
        }

        $iUserId = Phpfox::getUserId();
        $aFeedTypes = Phpfox::getService('socialstream.services')->getFeedTypes_OnlySocialStream($iUserId);
        $bIsLogged = Phpfox::getService('socialstream.services')->isLogged($iUserId);

        $bIsFacebook = false;
        if (!empty($aProviders['facebook']) && $aProviders['facebook']['connected'])
        {
            $aFacebookSetting = Phpfox::getService('socialstream.services')->getSetting('facebook', $iUserId, $aProviders['facebook']['profile']['identity']);
            $bIsFacebook = (bool)$aFacebookSetting['enable'];
        }
        
        $bIsTwitter = false;
        if (!empty($aProviders['twitter']) && $aProviders['twitter']['connected'])
        {
            $aTwitterSetting = Phpfox::getService('socialstream.services')->getSetting('twitter', $iUserId, $aProviders['twitter']['profile']['identity']);
            $bIsTwitter = (bool)$aTwitterSetting['enable'];
        }

        if (!$bIsFacebook && !$bIsTwitter)
        {
            $bIsLogged = false;
        }

        //Check language is right-to-left or not
        $sFloat = 'right';
        $aCurrLang = Phpfox::getService('language')->getLanguage(Phpfox::getLib('locale')->getLangId());
        if ($aCurrLang['direction'] === 'rtl')
        {
            $sFloat = 'left';
        }

        $bIsUsersProfilePage = (int)Phpfox::getLib('template')->getVar('bIsUsersProfilePage');
        $bOwnProfile = (int)Phpfox::getLib('template')->getVar('bOwnProfile');
        $this->template()->assign(array(
            'aFeedTypes' => $aFeedTypes,
            'corePath' => Phpfox::getParam('core.path'),
            'bIsFacebook' => $bIsFacebook,
            'bIsTwitter' => $bIsTwitter,
            'bIsLogged' => $bIsLogged,
            'sFloat' => $sFloat,
            'bIsUsersProfilePage' => $bIsUsersProfilePage
            ));

        if ($sFullControllerName == 'profile.index')
        {
            $this->template()->assign(array('bOwnProfile' => $bOwnProfile));
        }

        return 'block';
    }

    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('socialstream.component_block_filter_clean')) ? eval($sPlugin) : false);
    }

}

?>
