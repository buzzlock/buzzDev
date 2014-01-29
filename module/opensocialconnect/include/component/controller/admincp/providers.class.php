<?php

defined('PHPFOX') or exit('NO DICE!');

class OpenSocialConnect_Component_Controller_Admincp_Providers extends Phpfox_Component
{
    public function process()
    {
        if (($iDeleteId = $this->request()->get('delete')))
        {
            if (Phpfox::getService('opensocialconnect.providers')->delete($iDeleteId))
            {
                $this->url()->send('admincp.opensocialconnect.providers', null, Phpfox::getPhrase('opensocialconnect.providers_successfully_deleted'));
            }
        }
        
        if ($aFacebook = $this->request()->get('facebook'))
        {
            $aParams = array();
            if (isset($aFacebook['overwrite']) && $aFacebook['overwrite'] == 1)
            {
                Phpfox::getLib('database')->update(Phpfox::getT('setting'), array("value_actual" => $aFacebook['app_id']), 'module_id = "facebook" AND var_name="facebook_app_id"');
                Phpfox::getLib('database')->update(Phpfox::getT('setting'), array("value_actual" => $aFacebook['secret']), 'module_id = "facebook" AND var_name="facebook_secret"');
                $aParams = serialize($aFacebook);
                Phpfox::getLib('cache')->remove();
            }
            else
            {
                $aParams = serialize($aFacebook);
            }
            if (is_array($aParams))
            {
                $aParams = serialize($aParams);
            }
            Phpfox::getService('opensocialconnect.services')->addSettings('facebook', $aParams);
            $this->url()->send("admincp.opensocialconnect.providers", null, Phpfox::getPhrase('opensocialconnect.update_successfully'));
        }
        
        if ($aTwitter = $this->request()->get('twitter'))
        {
            $aParams = serialize($aTwitter);
            Phpfox::getService('opensocialconnect.services')->addSettings('twitter', $aParams);
            $this->url()->send("admincp.opensocialconnect.providers", null, Phpfox::getPhrase('opensocialconnect.update_successfully'));
        }
        
        if ($aLinkedIn = $this->request()->get('linkedin'))
        {
            $aParams = serialize($aLinkedIn);
            Phpfox::getService('opensocialconnect.services')->addSettings('linkedin', $aParams);
            $this->url()->send("admincp.opensocialconnect.providers", null, Phpfox::getPhrase('opensocialconnect.update_successfully'));
        }
        
        $iLimit = 7;
        $iLimitSelected = 39;
        $aOpenProviders = Phpfox::getService('opensocialconnect.providers')->getOpenProviders($iLimit, $iLimitSelected, false);
        
        //facebook setting
        $sFacebookAppID = Phpfox::isModule('facebook') ? Phpfox::getParam('facebook.facebook_app_id') : '';
        $sFacebookSecret = Phpfox::isModule('facebook') ? Phpfox::getParam('facebook.facebook_secret') : '';
        $aFBService = Phpfox::getService('opensocialconnect.providers')->getProvider('facebook');
        if (isset($aFBService['params']) && $aFBService['params'] != "" && $aFBService['params'])
        {
            $aFBService['params'] = unserialize($aFBService['params']);
        }
        if (isset($aFBService['params']) && is_array($aFBService['params']))
        {
            $sFacebookSecret = isset($aFBService['params']['secret']) ? $aFBService['params']['secret'] : "";
            $sFacebookAppID = $aFBService['params']['app_id'] ? $aFBService['params']['app_id'] : "";
        }

        //twitter setting
        $sTwitterkCustomerKey = Phpfox::isModule('share') ? Phpfox::getParam('share.twitter_consumer_key') : '';
        $sTwitterConsumerSecret = Phpfox::isModule('share') ? Phpfox::getParam('share.twitter_consumer_secret') : '';
        $aTwitterService = Phpfox::getService('opensocialconnect.providers')->getProvider('twitter');
        if (isset($aTwitterService['params']) && $aTwitterService['params'] != "" && $aTwitterService['params'])
        {
            $aTwitterService['params'] = unserialize($aTwitterService['params']);
        }
        if (isset($aTwitterService['params']) && is_array($aTwitterService['params']))
        {
            $sTwitterkCustomerKey = isset($aTwitterService['params']['consumer_key']) ? $aTwitterService['params']['consumer_key'] : "";
            $sTwitterConsumerSecret = $aTwitterService['params']['consumer_secret'] ? $aTwitterService['params']['consumer_secret'] : "";
        }

        //likendin setting
        $sLinkedInAppID = "";
        $sLinkedInSecret = "";
        $aLinkedInService = Phpfox::getService('opensocialconnect.providers')->getProvider('linkedin');
        if (isset($aLinkedInService['params']) && $aLinkedInService['params'] != "" && $aLinkedInService['params'])
        {
            $aLinkedInService['params'] = unserialize($aLinkedInService['params']);
        }
        if (isset($aLinkedInService['params']) && is_array($aLinkedInService['params']))
        {
            $sLinkedInSecret = isset($aLinkedInService['params']['secret_key']) ? $aLinkedInService['params']['secret_key'] : "";
            $sLinkedInAppID = $aLinkedInService['params']['app_id'] ? $aLinkedInService['params']['app_id'] : "";
        }

        $this->template()->setTitle(Phpfox::getPhrase('opensocialconnect.mange_social_providers'))
            ->setBreadcrumb(Phpfox::getPhrase('opensocialconnect.mange_social_providers'), $this->url()->makeUrl('admincp.opensocialconnect.providers'))
            ->setHeader('cache', array(
                'drag.js' => 'static_script', 
                '<script type="text/javascript">Core_drag.init({table: \'#js_drag_drop\', ajax: \'opensocialconnect.ordering\'});</script>'
            ))
            ->assign(array(
                'aOpenProviders' => $aOpenProviders,
                'sCoreUrl' => Phpfox::getParam('core.path'),
                'sFacebookSecret' => $sFacebookSecret,
                'sFacebookAppID' => $sFacebookAppID,
                'sTwitterkCustomerKey' => $sTwitterkCustomerKey,
                'sTwitterConsumerSecret' => $sTwitterConsumerSecret,
                'sLinkedInSecret' => $sLinkedInSecret,
                'sLinkedInAppID' => $sLinkedInAppID,
            ));
    }

    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('rss.component_controller_admincp_index_clean')) ? eval($sPlugin) : false);
    }

}

?>