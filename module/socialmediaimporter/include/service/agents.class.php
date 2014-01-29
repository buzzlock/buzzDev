<?php
defined('PHPFOX') or exit('NO DICE!');
if (file_exists(PHPFOX_DIR . 'module' . PHPFOX_DS . 'socialmediaimporter' . PHPFOX_DS . 'include' . PHPFOX_DS . 'service' . PHPFOX_DS . 'libs' . PHPFOX_DS . 'facebook.php')) {
    require_once (PHPFOX_DIR . 'module' . PHPFOX_DS . 'socialmediaimporter' . PHPFOX_DS . 'include' . PHPFOX_DS . 'service' . PHPFOX_DS . 'libs' . PHPFOX_DS . 'facebook.php');
}
if (file_exists(PHPFOX_DIR . 'module' . PHPFOX_DS . 'socialmediaimporter' . PHPFOX_DS . 'include' . PHPFOX_DS . 'service' . PHPFOX_DS . 'libs' . PHPFOX_DS . 'twitter.php')) {
    require_once (PHPFOX_DIR . 'module' . PHPFOX_DS . 'socialmediaimporter' . PHPFOX_DS . 'include' . PHPFOX_DS . 'service' . PHPFOX_DS . 'libs' . PHPFOX_DS . 'twitter.php');
}
if (file_exists(PHPFOX_DIR . 'module' . PHPFOX_DS . 'socialmediaimporter' . PHPFOX_DS . 'include' . PHPFOX_DS . 'service' . PHPFOX_DS . 'libs' . PHPFOX_DS . 'phpFlickr.php')) {
    require_once (PHPFOX_DIR . 'module' . PHPFOX_DS . 'socialmediaimporter' . PHPFOX_DS . 'include' . PHPFOX_DS . 'service' . PHPFOX_DS . 'libs' . PHPFOX_DS . 'phpFlickr.php');
}
class SocialMediaImporter_Service_Agents extends Phpfox_Service 
{
	/**
	 * Class constructor
	 */	
	public function __construct()
	{	
		$this->_sTable = Phpfox::getT('socialmediaimporter_agents');
	}
	
	public function getAuthUrl($sService = 'facebook', $bIsRedirect = 1)	
	{
		$oService = Phpfox::getService('socialmediaimporter.services')->getObject($sService);
		$sUrl = $oService->getAuthUrl($bIsRedirect);
		return $sUrl;
	}	
	
	public function get($iUserId = 0, $sService = '')	
	{
		$aRows = $this->database()->select('A.*, B.name, B.title, B.params')
			->from($this->_sTable, 'A')
			->join(Phpfox::getT('socialmediaimporter_services'), 'B', 'A.service_id = B.service_id')
			->where('A.user_id = '. $iUserId)
			->execute('getRows');	
		foreach ($aRows as $i => $aRow)
		{
			$aToken = base64_decode($aRow['token']);
			if(Phpfox::getLib('parse.format')->isSerialized($aToken))
			{
				$aToken = unserialize($aToken);	
			}
			
			
			$aRows[$i]['ssid'] = isset($aToken['ssid']) ? $aToken['ssid'] : ''; 
		}		
		if ($sService)
		{
			$aRows = Phpfox::getService('socialmediaimporter.common')->arrayFilter($aRows, 'name', $sService);
			return (isset($aRows[0]) ? $aRows[0] : array());
		}
		return $aRows;		
	}
	
	public function delete($sService = 'facebook')
    {
        if (!$sService)
        {
            return false;
        }
		$aProvider = Phpfox::getService('socialmediaimporter.providers')->getProvider($sService);
        $this->database()->delete($this->_sTable, 'user_id = '. Phpfox::getUserId() . ' AND service_id = '. $aProvider['service_id']);
        return true;
    }	
	
	public function addToken($iUserId = null, $sService = 'facebook', $aParams, $aExtra)
    {
        if($iUserId == null || !$sService || count($aParams)<=0)
        {
            return false;
        }
        $aProvider = Phpfox::getService('socialmediaimporter.providers')->getProvider($sService);
        if (!isset($aExtra['full_name']))
        {
            $aExtra['full_name'] ="";
        }
        if (is_array($aParams))
        {
            $aParams = serialize($aParams);
        }
        $sNoImageUrl = Phpfox::getParam('core.path').'module/socialmediaimporter/static/image/no-image-'.$sService.'.png';
		$iDefaultPrivacy = 3;
		switch(Phpfox::getParam('socialmediaimporter.default_privacy'))
		{
			case "Everyone":
				$iDefaultPrivacy = 0;
				break;
			case "Friends":
				$iDefaultPrivacy = 1;
				break;
			case "Friends of Friends":
				$iDefaultPrivacy = 2;
				break;
			default:
				$iDefaultPrivacy = 3;				
		}
		
        $this->database()->insert($this->_sTable, array (
				'token'=>base64_encode($aParams),
				'full_name'=> (empty($aExtra['full_name'])?$aExtra['user_name']:$aExtra['full_name']),
				'user_name' => $aExtra['user_name'],
				'identity'=> isset($aExtra['identity']) ? $aExtra['identity'] : "",
				'service_id' => $aProvider['service_id'],
				'user_id' => $iUserId,
				'privacy' => $iDefaultPrivacy,
				'img_url' => isset($aExtra['img_url'])?$aExtra['img_url']:$sNoImageUrl,
			)
		);		
        return true;
    }
	
	public function deleteToken($iUserId = null, $sService = 'facebook')
    {
        if (!$iUserId || !$sService)
        {
            return false;
        }		
        $aProvider = Phpfox::getService('socialmediaimporter.providers')->getProvider($sService);
		$aAgent = $this->database()->select('*')
			->from($this->_sTable)
			->where('user_id = '.$iUserId. ' AND service_id= '.$aProvider['service_id'])
			->execute('getRow');
        $this->database()->delete($this->_sTable, 'user_id = '.Phpfox::getUserId() . ' AND service_id= '.$aProvider['service_id']);
        return true;
    }

	public function isLogged($iUserId = null)
	{
		if($iUserId == null)
			return false;
		
		return (int) $this->database()->select('count(*)')
										  ->from($this->_sTable)
										  ->where('user_id = '.$iUserId)
										  ->execute('getSlaveField');
	}
	
	public function getToken($iUserId = null,$sService ='facebook')
    {
        if(!$iUserId || !$sService)
        {
            return false;
        }
        $aProvider = Phpfox::getService('socialmediaimporter.providers')->getProvider($sService);
        $aAgent = $this->database()->select('*')
                    ->from($this->_sTable)
                    ->where('user_id = '.(int)$iUserId. ' AND service_id= '.$aProvider['service_id'])
                    ->execute('getRow');        
        return $aAgent;         
    }	
	
    /**
     * get profile of agent
     */
	public function getProfile($sService ="", $aParams = null)
    {
   
        $oService = Phpfox::getService('socialmediaimporter.services')->getProviderService($sService);
   
        if(!is_object($oService) or !method_exists($oService, 'getUserProfile')){
            throw new Exception("service $sService does not exists or has not implement getUserPrileMethod called in ". __FILE__);
        }
        return $oService->getUserProfile($aParams);
        
        // predecated.
        return Phpfox::getParam('core.path');
    }
	
	public function getUserConnected($iUserid = null, $iServiceId = null)
    {
        if($iUserid == null || $iServiceId == null)   
        {
            return false;
        }
        $aAgents = $this->database()->select('*')
                    ->from($this->_sTable)
                    ->where('service_id = '.(int)$iServiceId. ' AND user_id = '.$iUserid)
                    ->execute('getRow');
        if(count($aAgents) <=0)
        {
            return false;
        }
        if($aAgents['params'] && Phpfox::getLib('parse.format')->isSerialized($aAgents['params']))
        {
            $aAgents['params'] = unserialize($aAgents['params']);
        }
        else
        {
            $aProvider['params'] = null;
        }
        if($aAgents['token'] && !empty($aAgents['token']) )
        {
            $aAgents['token'] = base64_decode($aAgents['token']);
        }
        return $aAgents;
        
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
		if ($sPlugin = Phpfox_Plugin::get('socialmediaimporter.service_agents__call'))
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