<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class SocialBridge_Service_Providers extends Phpfox_Service
{

	public function __construct()
	{
		$this -> _sTable = phpfox::getT('socialbridge_services');
	}

	public function updateProviderSetting($sService = null, $iUserId, $sModuleId, $iIsActive)
	{
		if (!$iUserId || $sService == null)
		{
			return array();
		}

		$aRow = $this -> database() -> select('*') -> from(Phpfox::getT('socialbridge_services_setting'), 'sbss') -> join(Phpfox::getT('socialbridge_services'), 'sbs', 'sbs.service_id = sbss.service_id') -> where('sbs.name LIKE "' . $sService . '" AND sbss.user_id = ' . (int)$iUserId . ' AND sbss.module_id = "' . $sModuleId . '"') -> execute('getRow');

		if (empty($aRow))
		{
			$iServiceId = (int)$this -> database() -> select('service_id') -> from(Phpfox::getT('socialbridge_services')) -> where("name = '" . $sService . "'") -> execute('getField');

			$aInsert = array(
				'user_id' => (int)$iUserId,
				'service_id' => $iServiceId,
				'module_id' => $sModuleId,
				'is_active' => (int)$iIsActive
			);

			if ($this -> database() -> insert(Phpfox::getT('socialbridge_services_setting'), $aInsert))
			{
				return true;
			}
		} else
		{
			$iServiceId = (int)$aRow['service_id'];
			if ($this -> database() -> update(Phpfox::getT('socialbridge_services_setting'), array('is_active' => $iIsActive), 'user_id = ' . (int)$iUserId . ' AND service_id = ' . $iServiceId . ' AND module_id = "' . $sModuleId . '"'))
			{
				return true;
			}
		}

		return false;
	}

	public function getProvider($sService = "", $bActive = false, $iUserId = null)
	{
		(($sPlugin = Phpfox_Plugin::get('socialbridge.service_libs_getprovider_start')) ? eval($sPlugin) : false);
		if ($sService == "")
		{
			return false;
		}
		$aProvider = array();
		if ($bActive == true && $iUserId != null)
		{
			$aProvider = $this -> database() -> select('sbs.*') -> from($this -> _sTable, 'sbs') -> join(Phpfox::getT('socialbridge_services_setting'), 'sbss', 'sbs.service_id = sbss.service_id') -> where("sbss.user_id = " . (int)$iUserId . " AND sbss.is_active = 1 AND sbs.name = '" . $this -> database() -> escape($sService) . "'") -> execute('getRow');
		} elseif ($bActive == true)
		{
			$aProvider = $this -> database() -> select('sbs.*') -> from($this -> _sTable, 'sbs') -> where("sbs.is_active = 1 AND sbs.name = '" . $this -> database() -> escape($sService) . "'") -> execute('getRow');
		} else
		{
			$aProvider = $this -> database() -> select('sbs.*') -> from($this -> _sTable, 'sbs') -> where("sbs.name = '" . $this -> database() -> escape($sService) . "'") -> execute('getRow');
		}

		if (isset($aProvider['params']) && phpfox::getLib('parse.format') -> isSerialized($aProvider['params']))
		{
			$aProvider['params'] = unserialize($aProvider['params']);
			$aProvider['params'] = $this -> getFromDefaultPhpFox($aProvider['params'], $sService);
		} else
		{
			$aProvider['params'] = $this -> getFromDefaultPhpFox(null, $sService);
		}
		(($sPlugin = Phpfox_Plugin::get('socialbridge.service_libs_getprovider_end')) ? eval($sPlugin) : false);
		return $aProvider;
	}

	//Get providers setting
	public function getProviders($bDisplay = true, $bPopup = false)
	{
		$sQuery = "";
		if ($bDisplay == true)
		{
			$sQuery = "sb.is_active = 1";
		}

		(($sPlugin = Phpfox_Plugin::get('socialbridge.service_libs_getprovider_start')) ? eval($sPlugin) : false);

		$aProviders = $this -> database() -> select('*') -> from(Phpfox::getT('socialbridge_services'), 'sb') -> where($sQuery) -> order('ordering ASC') -> execute('getRows');

		foreach ($aProviders as $iKey => $aProvider)
		{
			$aProviders[$iKey]['is_active'] = $aProviders[$iKey]['is_active'];
			$aParams = $this -> getFromDefaultPhpFox($aProviders[$iKey]['params'], $aProvider['name']);
			if ($aProviders[$iKey]['params'] != "" && phpfox::getLib('parse.format') -> isSerialized($aProviders[$iKey]['params']))
			{
				$aParams = unserialize($aProviders[$iKey]['params']);
			} else
			{
				$aProviders[$iKey]['params'] = null;
			}

			$aProviders[$iKey]['params'] = $aParams;
			if ($bDisplay == true)
			{
				$aAgent = phpfox::getService('socialbridge.agents') -> getUserConnected(Phpfox::getUserId(), $aProvider['service_id']);
				if ($aAgent)
				{
					$aProviders[$iKey]['Agent'] = $aAgent;
				}
				if ($bPopup == false)
				{
					//$sUrlAuth = phpfox::getService('ss.services')->getUrlAuth($aProvider['name']);
				}
			}
		}

		(($sPlugin = Phpfox_Plugin::get('socialbridge.service_libs_getprovider_end')) ? eval($sPlugin) : false);
		return $aProviders;
	}

	public function getFromDefaultPhpFox($aDefaultParams = array(), $sProvider = 'facebook')
	{
		switch ($sProvider)
		{
			case 'twitter' :
				if (isset($aDefaultParams['consumer_key']) && isset($aDefaultParams['consumer_secret']) && !empty($aDefaultParams['consumer_secret']) && !empty($aDefaultParams['consumer_key']))
				{
					return $aDefaultParams;
				}
				$sTwitterkAppID = Phpfox::isModule('share') ? phpfox::getParam('share.twitter_consumer_key') : 0;
				$sTwitterSecret = Phpfox::isModule('share') ? phpfox::getParam('share.twitter_consumer_secret') : 0;
				$aParams = array(
					'consumer_key' => $sTwitterkAppID,
					'consumer_secret' => $sTwitterSecret
				);
				return $aParams;
			case 'facebook' :
				if (isset($aDefaultParams['app_id']) && isset($aDefaultParams['secret']) && !empty($aDefaultParams['secret']) && !empty($aDefaultParams['app_id']))
				{
					return $aDefaultParams;
				}
				$sFacebookAppID = Phpfox::isModule('facebook') ? phpfox::getParam('facebook.facebook_app_id') : 0;
				$sFacebookSecret = Phpfox::isModule('facebook') ? phpfox::getParam('facebook.facebook_secret') : 0;
				$aParams = array(
					'app_id' => $sFacebookAppID,
					'secret' => $sFacebookSecret
				);
				return $aParams;
			default :
				return $aDefaultParams;
		}
	}

	public function addSetting($sService = "", $sParams = "", $iStatus = 0)
	{
		if ($sService == "")
		{
			return false;
		}

		(($sPlugin = Phpfox_Plugin::get('socialbridge.service_libs_addsetting_start')) ? eval($sPlugin) : false);
		$this -> database() -> update($this -> _sTable, array(
			'params' => $sParams,
			'is_active' => $iStatus
		), 'name ="' . $sService . '"');
	}

}
?>
