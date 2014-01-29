<?php
defined('PHPFOX') or exit('NO DICE!');
if (isset($aMenus))
{
    $aProvider = Phpfox::getService('socialbridge')->getAllProviderData(Phpfox::getUserId());

    if(!array_key_exists('facebook', $aProviders))
        $aProviders['facebook'] = null;
    if(!array_key_exists('twitter', $aProviders))
        $aProviders['twitter'] = null;

    $bIsShow = false;

    if($aProviders['facebook'])
    {
        if ($aProvider['facebook']['connected'])
            $bIsShow = true;
    }
    if($aProviders['twitter'])
    {
        if($aProvider['twitter']['connected'])
            $bIsShow = true;
    }

    if($bIsShow)
    {
        $aMenus['socialstream'] = Phpfox::getPhrase('socialstream.social_stream_settings');
    }
}
?>