<?php
defined('PHPFOX') or exit('NO DICE!');
class SocialMediaImporter_Service_Services extends Phpfox_Service
{
	public function __construct()
	{
		$this->_sTable = Phpfox::getT('socialmediaimporter_services');		
	}
	
	public function get($iUserId = 0)	
	{
		$aRows = $this->database()->select('*')			
			->from(Phpfox::getT('socialmediaimporter_services'))
			->execute('getRows');
		
		$aAgents = $this->database()->select('*')
			->from(Phpfox::getT('socialmediaimporter_agents'))
			->where('user_id = '. $iUserId)
			->execute('getRows');
		
		foreach ($aAgents as $i => $aAgent)
		{
			$aToken = base64_decode($aAgent['token']);
			if (phpfox::getLib('parse.format')->isSerialized($aToken))
			{
				$aToken = unserialize($aToken);
			}
			$aAgents[$i]['ssid'] = isset($aToken['ssid']) ? $aToken['ssid'] : ''; 
		}
		 
		$aNewRows = array();
		foreach($aRows as $i => $aRow) 			
		{
			if ($aRow['name'] == 'facebook' && !Phpfox::getUserParam('socialmediaimporter.enable_facebook'))
			{
				continue;
			}	
			if ($aRow['name'] == 'facebook' && !$aRow['params'])
			{
				continue;
			}
			if ($aRow['name'] == 'flickr' && !Phpfox::getUserParam('socialmediaimporter.enable_flickr'))
			{
				continue;
			}
			if ($aRow['name'] == 'flickr' && !$aRow['params'])
			{
				continue;
			}
			if ($aRow['name'] == 'instagram' && !Phpfox::getUserParam('socialmediaimporter.enable_instagram'))
			{
				continue;
			}
			if ($aRow['name'] == 'picasa' && !Phpfox::getUserParam('socialmediaimporter.enable_picasa'))
			{
				continue;
			}
			
			$aAgent = Phpfox::getService('socialmediaimporter.common')->arrayFilter($aAgents, 'service_id', $aRow['service_id']);
			$aRow['agent'] = $aAgent ? $aAgent[0] : array();
			if ($aRow['agent'])
			{
				$aRow['link_disconnect'] = $this->getLinkDisconnect($aRow['name']);
				$aRow['link_import'] = $this->getLinkImport($aRow['name']);
			}
			else
			{
				$aRow['link_connect'] = Phpfox::getService('socialmediaimporter.agents')->getAuthUrl($aRow['name'], 1);
				$aRow['link_import'] = $aRow['link_connect'];
				$aRow['link_import'] = "javascript:void(openauthsocialmediaimporter('" . $aRow['link_import'] . "'));";
			}			
			$aNewRows[] = $aRow;
		} 
		return $aNewRows;
	}
	
	public function getObject($sService = 'facebook') 
	{
		return Phpfox::getService('socialmediaimporter.' . $sService);
	}
	
	public function getLinkImport($sService = 'facebook') 
    {
		return Phpfox::getLib('url')->makeUrl("socialmediaimporter." . $sService);		
	}
	
	public function getLinkDisconnect($sService = 'facebook') 
    {
		return Phpfox::getLib('url')->makeUrl("socialmediaimporter." . $sService . ".disconnect");		
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
		if ($sPlugin = Phpfox_Plugin::get('socialmediaimporter.service_services__call'))
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