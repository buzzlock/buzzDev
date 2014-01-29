<?php
defined('PHPFOX') or exit('NO DICE!');
require_once (PHPFOX_DIR . 'module' . PHPFOX_DS . 'socialmediaimporter' . PHPFOX_DS . 'include' . PHPFOX_DS . 'service' . PHPFOX_DS . 'libs.class.php');
class Socialmediaimporter_Service_Abstract extends Younet_Service
{    
    protected $_sService = '';  
    protected $_host = '';
    protected $_hasGetAlbums = true;    
    protected $_hasGetPhotos = true;

    /**
	 * define list of url call for service
	 * @param array
	 */
    protected $_urls = array (
        'logout' => '',
        'login' => '',
        'getAbums' => '',
        'getPhotos' => '',
    );	
	
    /**
	* @param array $params
	* @return array
	*
	*/
    public function getUserProfile($params = array())
    { 
		
    }    
	
	public function __construct()
	{		
		
	}	

    /**
     * set token
     * @param array $aSession
     * @param array $aExtra
     * @return Socialmediaimporter_Service_Abstract
     */
    public function setToken($aSession, $aExtra)
    {
    	if(is_array($aSession) && $this->_sService != 'facebook')
		{
			$aSession["session_id"] = session_id();
		}
        Phpfox::getService('socialmediaimporter.agents')->addToken(Phpfox::getUserId(), $this->_sService, $aSession, $aExtra);
        return $this;
    }

    /**
	 * get tocken of current viewer & service
	 * @param null
	 * @return array
	 */
    public function getToken()
    {
        $aToken = array();
        $aAgent = Phpfox::getService('socialmediaimporter.agents')->getToken(Phpfox::getUserId(), $this->_sService);

        if (!isset($aAgent['token']) || empty($aAgent['token']))
        {
            return array();
        }
        else
        {
            $sToken = base64_decode($aAgent['token']);
            if (Phpfox::getLib('parse.format')->isSerialized($sToken))
            {
                $aToken = unserialize($sToken);
            }
            else
            {
                $aToken = $sToken;
            }
        }
        return $aToken;
    }

    /**
	 * set cache object
	 * @param Cache
	 * @return Socialmediaimporter_Service_Abstract
	 */
    public function setCache($cache)
    {
        $this->_cache = $cache;
        return $this;
    }

    public function getRpcUrl($name)
    {
        return $this->_urls[$name];
    }

    public function getAlbums($params = array())
    {        
		return $this->rpcCall($this->getRpcUrl('getAbums'), $params);
    }

    public function getPhotos($params = array())
    {
        return $this->rpcCall($this->getRpcUrl('getPhotos'), $params);
    }

    /**
	 * @param string $url
	 * @param array $params
	 * @param $format default = 'json'
	 * @return string if is json return object
	 */
    public function rpcCall($url, $params = array(), $format = 'json')
    {
        Phpfox_Error::skip(true);
		try
		{
			$params = (array)$params;      
			if (isset($_SESSION['CENTRALIZE_SSID']) && !empty($_SESSION['CENTRALIZE_SSID'])){
				$params['ssid'] = $_SESSION['CENTRALIZE_SSID'];
			}
			if (!isset($params['ssid']))
			{
				$aAgent = Phpfox::getService('socialmediaimporter.agents')->get(Phpfox::getUserId(), $this->_sService);
				$params['ssid'] = isset($aAgent['ssid']) ? $aAgent['ssid'] : '';				
			}
			if (isset($params['url']) && $params['url'])
			{
				$url = $params['url'];
			}
			$result = file_get_contents($url . '?' . http_build_query((array)$params));
			if ($format == 'json')
			{     
				$as_assoc = true;
				return json_decode($result, $as_assoc);
			}	
			return $result;	
		}
		catch (exception $e)
		{				
			return array();
		}
		Phpfox_Error::skip(false);	       	
    }
    
    /**
	 * the service has get photo methods
	 * if true, we can use syncphotos.{service}.
	 * @return bool
	 */
    public function hasGetPhotos()
	{
        return $this->_hasGetPhotos;
    }
    
    /**
	 * detecte service has get albms memthos,
	 * if this is true , we can use syncalbum.{service}
	 * @return bool
	 */
    public function hasGetAlbums()
	{
        return $this->_hasGetAlbums;
    }
	
	/**
	 *  process logout at centralize server.
	 */ 
	public function doLogout($url = null)
	{
		if(NULL === $url){
			$url = $this->_urls['logout'];
		}
		$this->rpcCall($url,array(),'html');
		return 1;
	}
	
	public function isImported($aTracking, $sId)
	{
		if (!$aTracking) return 0;
		if (Phpfox::getService('socialmediaimporter.common')->inArray($sId, $aTracking))
		{
			return 1;
		}
		return 0;
	}
	
	public function setServiceProvider($sProviderName) {
		$this->_sService = $sProviderName;
	}
	
	public function getTracking($iUserId, $sType = 'photo')
	{				
		$sService = $this->_sService;
		$aConds[] = "AND type_id = '$sType'";
		$aConds[] = "AND user_id = '$iUserId'";
		$aConds[] = "AND service_name = '$sService' OR service_name IS NULL";
// var_dump(PHPFOX::getLib("database")->select('fid')
			// ->from(Phpfox::getT('socialmediaimporter_tracking'))			
			// ->where($aConds)->execute());exit;
		$aRows = $this->database()->select('fid')
			->from(Phpfox::getT('socialmediaimporter_tracking'))			
			->where($aConds)
			->execute('getRows');
		if (!$aRows) return array();
		$aReturnIds = Phpfox::getService('socialmediaimporter.common')->arrayField($aRows, 'fid');
		return $aReturnIds;
	}
}
?>