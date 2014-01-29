<?php

defined('PHPFOX') or exit('NO DICE!');

class SocialMediaImporter_Service_Providers extends Phpfox_Service 
{
	/**
	 * Class constructor
	 */	
	public function __construct()
	{	
        $this->_sTable = Phpfox::getT('socialmediaimporter_services');
	}

	public function getProviderName($iServiceId = 0)
	{
		if($iServiceId <= 0)
		{
			return false;
		}
		$sProviderName = $this->database()->select('name')
                      ->from($this->_sTable)
					  ->where("service_id = ".(int)$iServiceId)
                      ->execute('getSlaveField');
		return $sProviderName;		
	}
	
	//Get a provider setting
	public function getProvider($sService ="")
	{
		if($sService == "")
		{
			return false;
		}
		$aProvider = $this->database()->select('*')
					  ->from($this->_sTable)
					  ->where("name = '".$this->database()->escape($sService)."'")
					  ->execute('getRow');
        
		if ($aProvider['params'] && Phpfox::getLib('parse.format')->isSerialized($aProvider['params']))
        {
            $aProvider['params'] = unserialize($aProvider['params']);
            $aProvider['params'] = $this->getFromDefaultPhpFox($aProvider['params'], $sService);
        }
        else
        {
            $aProvider['params'] = $this->getFromDefaultPhpFox($aProvider['params'], $sService);
        }
        return $aProvider;
	}
	
	
	//Get a provider setting
	public function getProviderById($iServiceId = 0)
	{
		if($iServiceId <= 0)
		{
			return false;
		}
		$aProvider = $this->database()->select('*')
                      ->from($this->_sTable)
					  ->where("service_id = ".(int)$iServiceId)
                      ->execute('getRow');
		if(count($aProvider) == 0)
			return false;
		
        if($aProvider['params'] && Phpfox::getLib('parse.format')->isSerialized($aProvider['params']))
        {
            $aProvider['params'] = unserialize($aProvider['params']);
            $aProvider['params'] = $this->getFromDefaultPhpFox($aProvider['params'],$aProvider['name']);
        }
        else
        {
            $aProvider['params'] = $this->getFromDefaultPhpFox($aProvider['params'],$aProvider['name']);
        }
        return $aProvider;
	}
	
	//Get providers setting
	public function getProviders($bDisplay = true,$bPopup = false)
	{
		$sQuery = "";
		if($bDisplay == true)
		{
			$sQuery ="ss.is_active = 1";
		}
		$aProviders = $this->database()->select('sb.*,ss.is_active as is_active')
                      ->from($this->_sTable,'sb')
                      ->leftjoin(Phpfox::getT('socialmediaimporter_services'),'ss','ss.name = sb.name')
					  ->where($sQuery)
                      ->order('ordering ASC')
                      ->execute('getRows');
					  
        foreach($aProviders as $iKey=>$aProvider)
        {
        	$aProviders[$iKey]['is_active'] = $aProviders[$iKey]['is_active'];
            $aParams = $this->getFromDefaultPhpFox($aProviders[$iKey]['params'],$aProvider['name']);
            if($aProviders[$iKey]['params'] != "" && Phpfox::getLib('parse.format')->isSerialized($aProviders[$iKey]['params']))
            {
                $aParams = unserialize($aProviders[$iKey]['params']);
            }
            else
            {
               $aProviders[$iKey]['params'] = null;              
            }
			
            $aProviders[$iKey]['params'] = $aParams;
            if($bDisplay == true)
            {
				$aAgent = Phpfox::getService('socialmediaimporter.agents')->getUserConnected(Phpfox::getUserId(),$aProvider['service_id']);
                if($aAgent)
                {                     
					if ($aAgent['img_url'] == '') 
					{
						$aAgent['img_url'] = Phpfox::getParam('core.path') . 'theme/frontend/default/style/default/image/noimage/profile_50.png';
					}
					$aProviders[$iKey]['Agent'] = $aAgent;                     
                }
                if($bPopup == false)
                {
                    //$sUrlAuth = Phpfox::getService('ss.services')->getUrlAuth($aProvider['name']);    
                }
                
            }
        }
        return $aProviders;
	}
	
	//Get provider setting from default phpfox
    public function getFromDefaultPhpFox($aDefaultParams = array(),$sProvider = 'facebook')
    {
        switch($sProvider)
        {
            case 'twitter':
               if(isset($aDefaultParams['consumer_key']) && isset($aDefaultParams['consumer_secret']) && !empty($aDefaultParams['consumer_secret'])&&!empty($aDefaultParams['consumer_key'])) 
               {
                   return $aDefaultParams;
               }

			   if(!Phpfox::isModule('share'))
			   {
					return array('consumer_key' =>0,'consumer_secret' =>0);
			   }
			   
               $sTwitterkAppID = Phpfox::getParam('share.twitter_consumer_key');
               $sTwitterSecret = Phpfox::getParam('share.twitter_consumer_secret'); 
               $aParams = array(
                    'consumer_key' =>$sTwitterkAppID,
                    'consumer_secret' =>$sTwitterSecret,
               );
               return $aParams;  
            case 'facebook':
               if(isset($aDefaultParams['app_id']) && isset($aDefaultParams['secret']) && !empty($aDefaultParams['secret'])&&!empty($aDefaultParams['app_id'])) 
               {
                   return $aDefaultParams;
               }
			   
			   if(!Phpfox::isModule('facebook'))
			   {
					return array('app_id' =>0,'secret' =>0);
			   }
			   
               $sFacebookAppID = Phpfox::getParam('facebook.facebook_app_id');
               $sFacebookSecret = Phpfox::getParam('facebook.facebook_secret'); 
               $aParams = array(
                    'app_id' =>$sFacebookAppID,
                    'secret' =>$sFacebookSecret,
               );
               return $aParams;
            default:
                return $aDefaultParams;
        }
    }

	public function addSetting($sService ="",$sParams = "",$iStatus = 0)
    {		
        if($sService == "")
        {
            return false;
        }
        $this->database()->update(Phpfox::getT('socialmediaimporter_services'),array('params'=>$sParams,'is_active'=>$iStatus),'name ="'.$sService.'"');
		$aParams = unserialize($sParams);
		
		if(count($aParams) && isset($aParams['time_type']) && isset($aParams['time_to_get']))
		{
			$aCronIds = $this->database()->select('sa.cron_id')
							   ->from(Phpfox::getT('socialmediaimporter_agents'),'sa')
							   ->join(Phpfox::getT('socialmediaimporter_services'),'ss','ss.service_id = sa.service_id')
							   ->where('ss.name = "'.$sService.'"')
							   ->execute('getRows');
			if(count($aCronIds))
			{
				$sIds = '0';
				foreach($aCronIds as $aCronId)
				{
					$sIds .= ','.$aCronId['cron_id'];
				}
				
				$this->database()->update(Phpfox::getT('cron'), array('type_id' => $aParams['time_type'],'every'=>$aParams['time_to_get']), "cron_id IN (".$sIds.")");
				Phpfox::getLib('cache')->remove(Phpfox::getParam('core.dir_cache').'cron.php', 'path');	
			}											
		}		
    }	
	
	/**
	 * If a call is made to an unknown method attempt to connect
	 * it to a specific plug-in with the same name thus allowing 
	 * plug-in developers the ability to extend classes.
	 *
	 * @param string $sMethod is the name of the method
	 * @param array $aArguments is the array of arguments of being passed
	 */
	public function __call($sMethod, $aArguments)
	{
		/**
		 * Check if such a plug-in exists and if it does call it.
		 */
		if ($sPlugin = Phpfox_Plugin::get('socialmediaimporter.service_providers__call'))
		{
			return eval($sPlugin);
		}
			
		/**
		 * No method or plug-in found we must throw a error.
		 */
		Phpfox_Error::trigger('Call to undefined method ' . __CLASS__ . '::' . $sMethod . '()', E_USER_ERROR);
	}	
}

?>