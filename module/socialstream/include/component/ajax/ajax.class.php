<?php

defined('PHPFOX') or exit('NO DICE!');


class SocialStream_Component_Ajax_Ajax extends Phpfox_Ajax
{

    public function filterFeed()
    {
        if (!Phpfox::isModule('socialbridge'))
        {
            return false;
        }

        $aCore = $this->get('core');

        $sViewId = $this->get('viewId');
        $iLimit = null;

        $iUserId = $this->get('profile_user_id');
        if (!$iUserId)
        {
            $iUserId = $aCore['is_user_profile'] ? $aCore['profile_user_id'] : null;
        }

        // Load new display with additional params
        Phpfox::getBlock('socialstream.display', array(
            "user_id" => $iUserId,
            "sViewId" => $sViewId,
            "iLimit" => $iLimit,
            'profile_user_id' => $aCore['profile_user_id']
            ));

        $this->remove('#feed_view_more');
        $this->remove('#feed_filtering_animation');
        $sHtml = $this->getContent(false);

        //Fix Egift ugly error
        if ($this->get('first'))
        {
            $this->html('#js_feed_content', $sHtml);
        }
        else
        {
            $this->append('#js_feed_content', $sHtml);
        }
        
        $this->call('if($(".cover_photo_link").size() > 0){$("#js_photo_cover_position").draggable("destroy");}');
        $this->call('feed_filter_success(); $Core.loadInit();');
    }

    public function viewMore()
    {
        $this->filterFeed();
    }

    public function getFeeds()
    {
        if (!Phpfox::isModule('socialbridge'))
        {
            return false;
        }

        $iUserId = (int)Phpfox::getUserId();

        $aProviders = phpfox::getService('socialbridge')->getAllProviderData($iUserId);

        if (!array_key_exists('facebook', $aProviders))
        {
            $aProviders['facebook'] = null;
        }
        
        if (!array_key_exists('twitter', $aProviders))
        {
            $aProviders['twitter'] = null;
        }

        if ((!$aProviders['facebook'] && !$aProviders['twitter']) || (!$aProviders['facebook']['connected'] && !$aProviders['twitter']['connected']))
        {
            $this->call('$(".socialstream_get_feeds_img").hide(0, function(){$(".socialstream_get_feeds_link").show();});');
            $sLink = Phpfox::getLib('url')->makeUrl('socialbridge.setting', array('tab' => 'socialstream'));
            $this->alert(Phpfox::getPhrase('socialstream.there_are_no_providers_were_enable_please_click_a_href_title_social_stream_settings_here_a', array('link' => $sLink)));
        }
        else
        {
            $oService = Phpfox::getService("socialstream.services");

            $bSuccess = false;

            $aFacebookSetting = Phpfox::getService('socialstream.services')->getSetting('facebook', $iUserId, $aProviders['facebook']['profile']['identity']);
            if ($aFacebookSetting && $aFacebookSetting['enable'])
            {
                if ($oService->getFeed($iUserId, 'facebook'))
                {
                    $bSuccess = true;
                }
            }

            $aTwitterSetting = Phpfox::getService('socialstream.services')->getSetting('twitter', $iUserId, $aProviders['twitter']['profile']['identity']);

            if ($aTwitterSetting && $aTwitterSetting['enable'])
            {
                if ($oService->getFeed($iUserId, 'twitter'))
                {
                    $bSuccess = true;
                }
            }
            $this->call('window.location.href=window.location.href');
        }
    }

    public function updateSetting()
    {
        $aVals = $this->get('val');
        $bSuccess = false;
        $iUserId = Phpfox::getUserId();
        $aProviders = Phpfox::getService('socialbridge')->getAllProviderData($iUserId);

        if (!array_key_exists('facebook', $aProviders))
        {
            $aProviders['facebook'] = null;
        }
        
        if (!array_key_exists('twitter', $aProviders))
        {
            $aProviders['twitter'] = null;
        }

        if ($aProviders['facebook'] && $aProviders['facebook']['connected'])
        {
            $aFacebookSetting = array('privacy' => $aVals['privacy_facebook'], 'enable' => $aVals['facebook']);
            $iFacebookSettingId = isset($aVals['facebook_setting']) ? (int)$aVals['facebook_setting'] : null;
            Phpfox::getService('socialstream.services')->updateSetting($aFacebookSetting, $iFacebookSettingId);
            $bSuccess = true;
        }

        if ($aProviders['twitter'] && $aProviders['twitter']['connected'])
        {
            $aTwitterSetting = array('privacy' => $aVals['privacy_twitter'], 'enable' => $aVals['twitter']);
            $iTwitterSettingId = isset($aVals['twitter_setting']) ? (int)$aVals['twitter_setting'] : null;
            Phpfox::getService('socialstream.services')->updateSetting($aTwitterSetting, $iTwitterSettingId);
            $bSuccess = true;
        }

        if ($bSuccess == true)
        {
            $this->alert(Phpfox::getPhrase('socialbridge.update_successfully'));
        }
        else
        {
            $this->alert(Phpfox::getPhrase('socialbridge.update_unsuccessful'));
        }
    }

}

?>