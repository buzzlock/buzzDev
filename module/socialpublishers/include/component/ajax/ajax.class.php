<?php

defined('PHPFOX') or exit('NO DICE!');

class SocialPublishers_Component_Ajax_Ajax extends Phpfox_Ajax
{

    public function share()
    {
        $this->setTitle(Phpfox::getPhrase('feed.what_s_on_your_mind'));
		
        Phpfox::getBlock('socialpublishers.share');
        
		if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false))
        {
            $this->call('<script type="text/javascript">$Core.loadInit();</script>');
        }        
    }

    public function updateModulesSettings()
    {
        $aVals = $this->get('val');

        if (count($aVals) && phpfox::getService('socialpublishers.modules')->updateUserSettings(phpfox::getUserId(), $aVals))
        {
            $this->alert(Phpfox::getPhrase('socialbridge.update_successfully'));
        }
        else
        {
            $this->alert(Phpfox::getPhrase('socialbridge.update_unsuccessful'));
        }
    }

    public function cancelpublish()
    {
        $aVals = $this->getAll();
		
		$iUserId =  Phpfox::getUserId();
		
        $oService = Phpfox::getService('socialpublishers');
		
        $aUserSelectedSetting['no_ask'] = isset($aVals['val']['no_ask']) ? $aVals['val']['no_ask'] : 0;
		
        $sType = isset($aVals['val']['type']) ? $aVals['val']['type'] : "";
		
        if ($aUserSelectedSetting['no_ask'] == 1)
        {
            $aUserSelectedSetting['facebook'] = isset($aVals['val']['provider']['facebook']) ? 1 : 0;
            $aUserSelectedSetting['twitter'] = isset($aVals['val']['provider']['twitter']) ? 1 : 0;
            $aUserSelectedSetting['linkedin'] = isset($aVals['val']['provider']['linkedin']) ? 1 : 0;
			
            $aExistSettings = Phpfox::getService('socialpublishers.modules')->getUserModuleSettings($iUserId, $sType);
			
            if (count($aExistSettings) <= 0)
            {
                Phpfox::getService('socialpublishers.modules')->insertUserSetting($iUserId, $sType, $aUserSelectedSetting);
            }
            else
            {
                Phpfox::getService('socialpublishers.modules')->updateUserSetting($iUserId, $sType, $aUserSelectedSetting);
            }
        }

        $sIdCache = Phpfox::getLib('cache')->set("socialpublishers_feed_" . $iUserId);
		
        Phpfox::getLib('cache')->remove($sIdCache);

        $this->call("\$Behavior.showSocialPublishersPopup = function(){};");
		
        if (Phpfox::isModule('socialintegration'))
        {
            $this->call("$(this).ajaxCall('socialintegration.showAfterPublisher');");
        }
        $this->call("tb_remove();");
        return true;
    }
    /**
     * @see Socialbridge_Service_Socialbridge
     * @see Phpfox_Parse_Format
     * @see Phpfox_Parse_Input
     * @return boolean
     */
    public function publish()
    {
        $aVals = $this->getAll();
        
        if (isset($aVals['val']['status']))
        {
            $aVals['val']['status'] = Phpfox::getLib('parse.input')->clean($aVals['val']['status'], 255);
        }
        
        $oService = Phpfox::getService('socialbridge');
		
        $aUserSelectedSetting['no_ask'] = isset($aVals['val']['no_ask']) ? $aVals['val']['no_ask'] : 0;
		
        $sType = isset($aVals['val']['type']) ? $aVals['val']['type'] : "";
		
        if ($sType == "music_song" || $sType == "music_album")
        {
            $sType = "music";
            $aVals['val']['content'] = '';
        }
		
		if($sType == "user_status"){
            $sType = "status";
        }

        $aSupportedModule = Phpfox::getService('socialpublishers.modules')->getModule($sType);
		
        if (count($aSupportedModule) > 0 && $aSupportedModule['is_active'] == 0)
        {
            return false;
        }
        if ($aUserSelectedSetting['no_ask'] == 1)
        {
            $aUserSelectedSetting['facebook'] = isset($aVals['val']['provider']['facebook']) ? 1 : 0;
            $aUserSelectedSetting['twitter'] = isset($aVals['val']['provider']['twitter']) ? 1 : 0;
            $aUserSelectedSetting['linkedin'] = isset($aVals['val']['provider']['linkedin']) ? 1 : 0;
            
            $aExistSettings = Phpfox::getService('socialpublishers.modules')->getUserModuleSettings(Phpfox::getUserId(), $sType);
            
            if (count($aExistSettings) <= 0)
            {
                Phpfox::getService('socialpublishers.modules')->insertUserSetting(Phpfox::getUserId(), $sType, $aUserSelectedSetting);
            }
            else
            {
                Phpfox::getService('socialpublishers.modules')->updateUserSetting(Phpfox::getUserId(), $sType, $aUserSelectedSetting);
            }
        }
        
        $aResponses = array();
        if (isset($aVals['val']['provider']) && count($aVals['val']['provider']) >= 0)
        {
            foreach ($aVals['val']['provider'] as $sKey => $sProvider)
            {
                if (isset($aSupportedModule[$sProvider]) && $aSupportedModule[$sProvider] == 1)
                {
                    $mResult = array();
                    try
                    {
                        $mResult = $oService->post($sProvider, $aVals['val']);
                    }
                    catch (Exception $e)
                    {
                        // Do nothing.
                    }
                    
                    if ($sProvider == 'facebook' && $mResult === true)
                    {
                        Phpfox::getService('socialpublishers.statisticdate.process')->updateTotalPostByDate($sProvider);
                        Phpfox::getService('socialpublishers.statisticuser.process')->updateTotalPostByUser($sProvider);
                    }
                    
                    if ($sProvider == 'twitter' && isset($mResult['id']) && !Phpfox::getLib('parse.format')->isEmpty($mResult['id']))
                    {
                        Phpfox::getService('socialpublishers.statisticdate.process')->updateTotalPostByDate($sProvider);
                        Phpfox::getService('socialpublishers.statisticuser.process')->updateTotalPostByUser($sProvider);
                    }
                    
                    if ($sProvider == 'linkedin' && isset($mResult['success']) && $mResult['success'] == 1)
                    {
                        Phpfox::getService('socialpublishers.statisticdate.process')->updateTotalPostByDate($sProvider);
                        Phpfox::getService('socialpublishers.statisticuser.process')->updateTotalPostByUser($sProvider);
                    }
                    
                    if (is_array($mResult))
                    {
                        $mResult['apipublisher'] = $sProvider;
                    }
                    
                    $aResponses[] = $mResult;
                }
            }
        }
        
        $aError = array();
        if (count($aResponses) > 0)
        {
            foreach ($aResponses as $iKey => $aResponse)
            {
                if (isset($aResponse['error']))
                {
                    $this->call("tb_remove();");
                    $aError[] = Phpfox::getPhrase('socialpublishers.' . $aResponse['apipublisher']) . ': ' . $aResponse['error'];
                    Phpfox_Error::set(Phpfox::getPhrase('socialpublishers.' . $aResponse['apipublisher']) . ': ' . $aResponse['error']);
                }
            }
        }

        $this->call("\$Behavior.showSocialPublishersPopup = function(){};");
        if (Phpfox::isModule('socialintegration'))
        {
            $this->call("$(this).ajaxCall('socialintegration.showAfterPublisher');");
        }
        if (Phpfox_Error::isPassed())
        {
            $this->call("tb_remove();");
        }
    }

}

?>