<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

require_once 'provider/abstract.class.php';

class Socialbridge_Service_Socialbridge extends Phpfox_Service
{

	protected $_aSupporteds = array();

	/**
	 * @param array
	 */
	protected $_aViewerTokenData = NULL;

	/**
	 * @param array
	 */
	protected $_aSettings = array();

	/**
	 * @param bool
	 */
	protected $_bInitSetting = FALSE;

	/**
	 * @return void
	 */
	public function __construct()
	{
		$this -> _sTable = phpfox::getT('socialbridge_services');
		$this -> initSetting();
	}

	public function getAuthUrl($sService, $callbackUrl, $bRedirect = 1)
	{
		return $this -> getProvider($sService) -> getAuthUrl($callbackUrl, $bRedirect);
	}

	/**
	 * init setting once only.
	 * @TODO should apply cache to improve performance.
	 * @return void
	 */
	protected function initSetting()
	{
		if ($this -> _bInitSetting)
		{
			return;
		}

		$aRows = $this -> database() -> select('*') -> from($this -> _sTable) -> execute('getRows');

		foreach ($aRows as $aRow)
		{
			if ($aRow['params'])
			{
				$this -> _aSettings[$aRow['name']] = (array)@unserialize($aRow['params']);
				$this -> _aSupporteds[$aRow['name']] = $aRow['is_active'];
			} else
			{
				$this -> _aSupporteds[$aRow['name']] = FALSE;
			}
		}

		$this -> _bInitSetting = TRUE;
	}

	/**
	 * set token for particular service
	 * @param string $sService service name
	 * @param array $token   token must contain offset "identity"
	 * @param array $profile
	 * @param int $iUserId
	 * @return void
	 */
	public function setTokenData($sService, $aToken, $sProfile, $iUserId = NULL)
	{
		// add identity when we want to map multiple social account with one phpfox-user.
		$found = TRUE;
		$aRow = NULL;
		$iViewerId = Phpfox::getUserId();

		if (NULL == $iUserId)
		{
			$iUserId = $iViewerId;
		}

		if ($iUserId == $iViewerId)
		{
			$this -> _aViewerTokenData[$sService] = array(
				$aToken,
				$sProfile
			);
		}

		$sSessionId = @session_id();

		$sToken = serialize($aToken);
		$sProfile = serialize($sProfile);

		$sTable = Phpfox::getT('socialbridge_token');

		$sWhere = "service='{$sService}'";

		if ($iUserId)
		{
			$sWhere .= " AND (user_id='{$iUserId}' AND session_id='{$sSessionId}')";
		} else if ($sSessionId)
		{
			$sWhere .= " AND session_id='{$sSessionId}'";
		} else
		{
			$found = FALSE;
		}

		if ($found)
		{
			$this -> database() -> delete($sTable, $sWhere);
		}

		$this -> database() -> insert($sTable, array(
			'user_id' => $iUserId,
			'session_id' => $sSessionId,
			'service' => $sService,
			'token' => $sToken,
			'profile' => $sProfile,
			'timestamp' => time(),
		));

		return TRUE;
	}

	/**
	 * general used
	 * @param int $iUserId
	 * @param string $sService
	 * @return array
	 */
	protected function getUserTokenData($sService, $iUserId)
	{

		$token = TRUE;

		$sWhere = 'service = "' . $sService . '" AND user_id = ' . $iUserId;
		$aRow = $this -> database() -> select('*') -> from(Phpfox::getT('socialbridge_token')) -> where($sWhere) -> execute('getSlaveRow');

		if ($aRow)
		{
			return array(
				@unserialize($aRow['token']),
				@unserialize($aRow['profile'])
			);
		}

		return array(
			NULL,
			NULL
		);
	}

	/**
	 * remove user token data
	 * @param int $iUserId
	 * @param string $sServiceId
	 * @return TRUE
	 */
	protected function removeUserTokenData($sService = NULL, $iUserId = NULL)
	{
		if ($sService)
		{
			$sWhere = "service = '{$sService}' AND user_id='{$iUserId}'";
		} else
		{
			$sWhere = "user_id='{$iUserId}'";
		}

		$this -> database() -> delete(Phpfox::getT('socialbridge_token'), $sWhere);
		return TRUE;
	}

	/**
	 * @param string $sServiceName
	 * @param string $sId Optional
	 * @return string/array
	 */
	public function getTokenData($sService, $iUserId = NULL)
	{

		// load once with static cache
		$iViewerId = Phpfox::getUserId();

		if (NULL == $iUserId)
		{
			$iUserId = $iViewerId;
		}
		if ($iUserId != $iViewerId)
		{
			return $this -> getUserTokenData($sService, $iUserId);
		}

		if (NULL == $this -> _aViewerTokenData)
		{
			$aRows = NULL;
			$found = TRUE;

			$iUserId = Phpfox::getUserId();

			$sSessionId = @session_id();

			$sTable = Phpfox::getT('socialbridge_token');

			$sWhere = "1";

			if (NULL != $iUserId)
			{
				$sWhere .= " AND user_id='{$iUserId}'";
			} else if (NULL != $sSessionId)
			{
				$sWhere .= " AND session_id='{$sSessionId}'";
			} else
			{
				$found = FALSE;
			}

			if ($found)
			{
				$aRows = $this -> database() -> select('*') -> from($sTable) -> where($sWhere) -> execute('getSlaveRows');
			}

			// prevent reload within a request

			$this -> _aViewerTokenData = array();

			if ($aRows)
			{
				foreach ($aRows as $aRow)
				{
					$this -> _aViewerTokenData[$aRow['service']] = array(
						@unserialize($aRow['token']),
						@unserialize($aRow['profile'])
					);
				}
			}
		}

		return isset($this -> _aViewerTokenData[$sService]) ? $this -> _aViewerTokenData[$sService] : array(
			NULL,
			NULL
		);
	}

	/**
	 * @param string $sServiceName NULL, delete all connected to current service.
	 * @param string $sId Optional
	 * @return string/array
	 */
	public function removeTokenData($sService = NULL, $iUserId = NULL)
	{
		$aRow = NULL;
		$iViewerId = Phpfox::getUserId();

		if (NULL == $iUserId)
		{
			$iUserId = $iViewerId;
		}

		if ($iUserId != $iViewerId)
		{
			return $this -> removeUserTokenData($sService, $iUserId);
		}

		$sSessionId = @session_id();

		$sTable = Phpfox::getT('socialbridge_token');

		if ($sService)
		{
			$sWhere = "service='{$sService}'";
		} else
		{
			$sWhere = " 1 ";
		}

		if (NULL != $iUserId)
		{
			$sWhere .= " AND (user_id='{$iUserId}')";
		} else if (NULL != $sSessionId)
		{
			$sWhere .= " AND (session_id='{$sSessionId}')";
		}

		$this -> database() -> delete($sTable, $sWhere);

		if ($iViewerId == $iUserId)
		{

			if ($sService)
			{
				// reset viewer token data registers
				$this -> _aViewerTokenData[$sService] = array(
					NULL,
					NULL
				);
			} else
			{
				$this -> _aViewerTokenData = array();
			}
		}
		return TRUE;
	}

	/**
	 * get provider wrapper object by name
	 * @param string $sService available facebook,twitter,linkedin
	 * @return array
	 */
	public function getSetting($sService)
	{
		return isset($this -> _aSettings[$sService]) ? $this -> _aSettings[$sService] : array();
	}

	/**
	 * if provider supported $sName
	 * @param string $sService
	 * @return true|false
	 */
	public function hasProvider($sService)
	{
		return isset($this -> _aSupporteds[$sService]) ? $this -> _aSupporteds[$sService] : FALSE;
	}

	/**
	 * load provider object
	 * @param string $sName
	 * @return SocialBridge_Service_Provider_Abstract
	 */
	public function getProvider($sService)
	{
		static $providers = array();

		$sService = strtolower($sService);

		if (!isset($providers[$sService]))
		{
			if (!$this -> hasProvider($sService))
			{
				throw new Exception('system does not support provider ' . $sService);
			}
			$providers[$sService] = Phpfox::getService('socialbridge.provider.' . $sService);
		}
		return $providers[$sService];
	}

	/**
	 * get active providers data
	 * @param int $iUserId
	 * @return array
	 */
	public function getAllProviderData($iUserId = NULL)
	{
		static $aResult = NULL;

		if (NULL == $aResult)
		{
			if ($iUserId == NULL)
			{
				$iUserId = Phpfox::getUserId();
			}

			$aResult = array();

			foreach ($this->_aSupporteds as $sService => $isActive)
			{
				if ($isActive)
				{
					list($token, $aProfile) = $this -> getTokenData($sService, $iUserId);

					$aResult[$sService] = array(
						'service' => $sService,
						// backward compatible with ealier version
						'name' => $sService,
						'connected' => (bool)$aProfile,
						'profile' => $aProfile,
						'token' => $token,
					);
				}
			}

		}

		return $aResult;
	}

	//Get profile info
	public function getPostedProfile($sService = "", $aUserProfileId = null)
	{
		switch ($sService)
		{
			case 'facebook' :
				return Phpfox::getService('socialbridge.provider.facebook') -> getPostedProfile($aUserProfileId);
			case 'twitter' :
				return Phpfox::getService('socialbridge.provider.twitter') -> getPostedProfile($aUserProfileId);
			default :
				return phpfox::getParam('core.path');
		}
	}

	/**
	 * Check is in timeline mode
	 * @return bool
	 */

	public function timeline()
	{
		if (version_compare(Phpfox::getVersion(), '3.3', '<'))
		{
			return false;
		}
		return Phpfox::getService('profile') -> timeline();
	}

	/**
	 * post message to current conntected id
	 * @param string $sService
	 * @param array $aVal
	 * @return TRUE // always.
	 */
	public function post($sService, $aVal)
	{
		return $this -> getProvider($sService) -> post($aVal);
	}
}
