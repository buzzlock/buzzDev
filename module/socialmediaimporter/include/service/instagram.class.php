<?php
require_once (PHPFOX_DIR . 'module' . PHPFOX_DS . 'socialmediaimporter' . PHPFOX_DS . 'include' . PHPFOX_DS . 'service' . PHPFOX_DS . 'abstract.php');	
class Socialmediaimporter_Service_Instagram extends Socialmediaimporter_Service_Abstract
{
    /**
	 * service name of this service
	 * @var string
	 */
    protected $_sService = 'instagram';

    /**
		* @var array
	 */

    protected $_urls = array(
        'host' => 'http://openid.younetid.com/v2/instagram/',   
		'login' => 'http://openid.younetid.com/v2/instagram/login',
		'logout' => 'http://openid.younetid.com/v2/instagram/logout',
        'getPhotos' => 'http://openid.younetid.com/v2/instagram/photos',
    );

	 protected $__urls = array(
        'host' => 'http://younetid.dev/media/instagram/', 
		'login' => 'http://younetid.dev/media/instagram/login',
		'logout' => 'http://younetid.dev/media/instagram/logout',		
        'getPhotos' => 'http://younetid.dev/media/instagram/photos',
    );
	
	protected $_cache_id = '';
	protected $_cache_time = array();
	
    public function __construct()
    {
		$this->_hasGetAlbums = false;
		$this->_cache_id = 'socialmediaimporter_instagram_' . Phpfox::getUserId();
		$this->_cache_time = array (
			'album' => 30,
			'photo' => 30,
			'photo_tag' => 30
		);
    } 
	
	public function getUserId()
    {
		$aAgent = Phpfox::getService('socialmediaimporter.agents')->getToken(Phpfox::getUserId(), $this->_sService);
		return isset($aAgent['identity']) ? $aAgent['identity'] : 0;
	}	
	
	public function setCacheId($sIdentity)
	{
		$sCacheId = $this->cache()->set(array($this->_cache_id, $sIdentity));
		return $sCacheId;		
	}	
	
	public function removeCache()
    {
		$this->cache()->remove(array($this->_cache_id, ''), 'substr');
	}		

    /**
	 * call from /static/php/static.php
	 * @param Request $oRequest
	 * @param array  $aParams
	 * @return array
	 */
    public function processAuthCallback($oRequest, $aParams)
    {
        $aUserData = (array)json_decode($aParams['user_data']);        
        if ($aUserData)
        {
            if (!isset($aUserData["username"]))
            {
                $aUserData["username"] = $aUserData["full_name"];
            }            
            list($aSession, $aExtra) = $this->processAgentProfile($aUserData);
			$_SESSION['CENTRALIZE_SSID'] = $aSession['ssid'] = $aParams['ssid'];
			$this->setToken($aSession, $aExtra);
            return $aExtra;
        }
        return array();
    }

    /**
	 * get user profile from callback
	 * @param array $aParams
	 */
    public function processAgentProfile($aParams = array())
    {        
        $aUserProfile['service'] = $this->_sService;
		$aUserProfile['user_name'] = $aParams["username"];
        $aUserProfile['full_name'] = $aParams["full_name"];      
        $aUserProfile['identity'] = $aParams["id"];        
        $aUserProfile['img_url'] = $aParams["profile_picture"];
		if (isset($aParams["ssid"]) && $aParams["ssid"])
		{
			$aUserProfile['ssid'] = $aParams["ssid"];
		}
        return array ($aParams, $aUserProfile);
    }

    /**
	 * get auth urls.
	 * @param bool $bRedirect
	 * @param array $params
	 * @return string
	 */
    public function getAuthUrl($bRedirect = 0, $params = array())
    {
        $sReturnUrl = Phpfox::getParam('core.path') . 'module/socialmediaimporter/static/php/static.php';
        $sNextUrl = $sReturnUrl . '?' . http_build_query(array(            
            'service' => $this->_sService,
            'redirect' => $bRedirect,
			'prevent' => time(),
        ));
        $params['callback'] = $sNextUrl;
        $params['platform'] = 'phpfox';		
        return $this->_urls['host'] . '?' . http_build_query($params);
    }

    private function _preparePhotos($aRows = array(), &$sNextUrl = '')
    {		
		Phpfox_Error::skip(false);
		$sNextUrl = isset($aRows['pagination']['next_url']) ? $aRows['pagination']['next_url'] : '';
		$aRows = isset($aRows['data']) ? $aRows['data'] : array();
		$aNewRows = array();
		foreach ($aRows as $i => $aRow)
        {            
			$aRow['photo_id'] = $aRow["id"];
			$aRow['title'] = $aRow["caption"];							
			$aRow['is_cover'] = 0;			
			if (isset($aRow['images']['thumbnail']['url']))
			{
				$aRow['photo_thumb'] = $aRow['images']['thumbnail']['url'];	
			}
			else
			{
				$aRow['photo_thumb'] = $aRow['images']['standard_resolution']['url'];	
			}
			if (isset($aRow['images']['standard_resolution']['url']))
			{
				$aRow['photo_large'] = $aRow['images']['standard_resolution']['url'];	
			}
			else
			{
				$aRow['photo_large'] = $aRow['photo_thumb'];
			}
			unset($aRow["id"]);			
			unset($aRow["caption"]);
			unset($aRow["images"]);
			unset($aRow["attribution"]);
			unset($aRow["tags"]);
			unset($aRow["type"]);
			unset($aRow["location"]);
			unset($aRow["comments"]);
			unset($aRow["filter"]);
			unset($aRow["created_time"]);
			unset($aRow["link"]);
			unset($aRow["likes"]);
			unset($aRow["user_has_liked"]);
			unset($aRow["user"]);
			$aNewRows[] = $aRow;			
        }
		Phpfox_Error::skip(true);
        return $aNewRows;
    }	

	public function getPhotos($aParams = array())
    {		
		$iServiceUserId = $this->getUserId();
		$iLimit = isset($aParams['limit']) ? $aParams['limit'] : 12;
        $iOffset = isset($aParams['offset']) ? $aParams['offset'] : 0;
        $iPage = isset($aParams['page']) ? $aParams['page'] : 1;
		$iPrivacy = isset($aParams['privacy']) ? $aParams['privacy'] : 0;
        $iPrivacyComment = isset($aParams['privacy_comment']) ? $aParams['privacy_comment'] : 0;
       	$sPhotoIds = isset($aParams['photo_id']) ? $aParams['photo_id'] : '';        	
		$iFoxAlbumId = 0;
		$sError = '';
		try
        {		
			list ($iCnt, $aRows) = $this->getPhotoTags($aParams);
			$iQueueId = 0;			
			$aFoxAlbums = array();
			if ($iCnt && $sPhotoIds)
			{
				$aPhotoIds = explode('","', $sPhotoIds);
				$aFoxAlbums[] = $iFoxAlbumId = isset($aParams['album_id']) && $aParams['album_id'] ? $aParams['album_id'] : 0;
				$aSelected = array();			
				for ($i = 0; $i < count($aRows); $i++)
				{
					$sPhotoId = $aRows[$i]['photo_id'];
					if (Phpfox::getService('socialmediaimporter.common')->inArray($sPhotoId, $aPhotoIds))
					{
						$aRows[$i]['is_cover'] = 0; 
						$aRows[$i]['album_id'] = $iFoxAlbumId;
						$aSelected[] = $aRows[$i];				
					}
				}
				$aParams = array();
				$aParams['privacy'] = $iPrivacy;
				$aParams['privacy_comment'] = $iPrivacyComment;
				$aParams['service'] = $this->_sService;
				$aParams['media_type'] = 'photo';
				$aParams['media'] = $aRows = $aSelected;
				$iCnt = count($aRows);			
				$iQueueId = Phpfox::getService('socialmediaimporter.process')->addQueue($aParams, $aFoxAlbums);
			}
			return array($iCnt, $aRows, $iQueueId, $sError);
		}
		catch (exception $e)
        {		
			return false;
        }
	}
	
	public function getPhotoTags($aParams)
	{		
		$iServiceUserId = $this->getUserId();
		if (!$iServiceUserId) return false;
		$iLimit = isset($aParams['limit']) ? $aParams['limit'] : 8;
        $iOffset = isset($aParams['offset']) ? $aParams['offset'] : 0;
		$iPage = isset($aParams['page']) ? $aParams['page'] : 1;
		$sCacheId = $this->setCacheId('photo_tag_' . $iServiceUserId);
		list ($aRows, $sNextUrl) = $this->cache()->get($sCacheId, $this->_cache_time['photo_tag']);
		if (empty($aRows) || !is_array($aRows))
		{
			$aRows = array();
		}
		$iCnt = count($aRows);
		$iPageCached = ceil($iCnt/$iLimit);
		if (($iPageCached < $iPage) && ($sNextUrl != '' || $iCnt == 0))
		{
			$aNewParams['offset'] = $iOffset;
			$aNewParams['limit'] = $iLimit;			
			$aNewParams['url'] = $sNextUrl;
			$aNewRows = parent::getPhotos($aNewParams); 
			if ($aNewRows)
			{
				$aNewRows = $this->_preparePhotos($aNewRows, $sNextUrl);
				$aRows = array_merge($aRows, $aNewRows);
				$sCacheId = $this->setCacheId('photo_tag_' . $iServiceUserId);
				$this->cache()->save($sCacheId, array($aRows, $sNextUrl));
			}
		}
		$iCnt = count($aRows);
		$aRows = Phpfox::getService('socialmediaimporter.common')->arraySlice($aRows, $iOffset, $iLimit);
		return array($iCnt, $aRows);
		
		/*
		$sCacheId = $this->setCacheId('photo_tag_' . $iServiceUserId);
		if (!($aRows = $this->cache()->get($sCacheId, $this->_cache_time['photo_tag'])))		
		{
			try
			{
				$aNewParams['limit'] = 5;
				$aNewParams['offset'] = 0;
				$aRows = parent::getPhotos($aNewParams); 
				if (!$aRows)
				{
					return array(0, array());
				}
				$aRows = $this->_preparePhotos($aRows);				
				$this->cache()->save($sCacheId, $aRows);
			}
			catch (exception $e)
			{				
				return false;
			}			
		}
		$iCnt = count($aRows);
		$aRows = Phpfox::getService('socialmediaimporter.common')->arraySlice($aRows, $iOffset, $iLimit);
		return array($iCnt, $aRows);
		*/
	}	

    public function disconnect()
    {             
        $this->removeCache();
        $this->rpcCall($this->_urls['logout']);
    }
}
?>