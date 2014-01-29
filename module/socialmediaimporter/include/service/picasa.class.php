<?php
require_once (PHPFOX_DIR . 'module' . PHPFOX_DS . 'socialmediaimporter' . PHPFOX_DS . 'include' . PHPFOX_DS . 'service' . PHPFOX_DS . 'abstract.php');
class Socialmediaimporter_Service_Picasa extends Socialmediaimporter_Service_Abstract
{
    /**
     * service name of this service
     * @var string
     */
    protected $_sService = 'picasa';

    /**
     * @var array
	 */

    protected $_urls = array (
        'host' => 'http://openid.younetid.com/v2/picasa',
        'login' => 'http://openid.younetid.com/v2/picasa',
        'logout' => 'http://openid.younetid.com/v2/picasa/logout',
        'getAbums' => 'http://openid.younetid.com/v2/picasa/albums',
        'getPhotos' => 'http://openid.younetid.com/v2/picasa/photos',
    );

	protected $__urls = array (
        'host' => 'http://younetid.dev/v2/picasa',
        'login' => 'http://younetid.dev/v2/picasa',
        'logout' => 'http://younetid.dev/v2/picasa/logout',
        'getAbums' => 'http://younetid.dev/v2/picasa/albums',
        'getPhotos' => 'http://younetid.dev/v2/picasa/photos',
    );

	protected $_cache_id = '';
	protected $_cache_time = array();

    public function __construct()
    {
		$this->_hasGetPhotos = false;
		$this->_cache_id = 'socialmediaimporter_picasa_' . Phpfox::getUserId();
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
        $aUserProfile['identity'] = $aParams["user_id"];
        $aUserProfile['img_url'] = $aParams["avatar"];
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

	/**
	 * @param array $aRawData raw data get from getAlbums
	 * @return array
	 */
    private function _prepareAlbums($aRows = array())
    {
        if (!$aRows) return array();
		$aNewRows = array();
		foreach ($aRows as $aRow)
        {
            if ($aRow["photo_count"] <= 0)
            {
                continue;
            }
			$aRow["name"] = $aRow["title"];
			$aRow["size"] = $aRow["photo_count"];
            $aRow["photo_thumb"] = $aRow["thumb"];
            $aRow["photo_cover"] = $aRow["large"];
			unset($aRow["user_id"]);
			unset($aRow["album_link"]);
			unset($aRow["photo_count"]);
			unset($aRow["title"]);
			unset($aRow["thumb"]);
			unset($aRow["large"]);
			$aNewRows[] = $aRow;
        }
        return $aNewRows;
    }

    private function _preparePhotos($aRows = array(), $sAlbumId = '')
    {
        $aNewRows = array();
		foreach ($aRows as $i => $aRow)
        {
            $aRow['album_id'] = $sAlbumId;
			$aRow['photo_id'] = $aRow["photo_id"];
			$aRow['title'] = $aRow["title"];
			$aRow['is_cover'] = 0;
			$aRow['photo_thumb'] = $aRow["thumb"];
			$aRow['photo_large'] = $aRow["large"];
			unset($aRow["thumb"]);
			unset($aRow["large"]);
			$aNewRows[] = $aRow;
        }
        return $aNewRows;
    }

	public function getCoversAlbums()
	{
		$aParams['offset'] = 0;
		$aParams['limit'] = 1000;
		list ($iCnt, $aAlbums) = $this->getAlbums($aParams);
		$aAlbumCovers = array();
		foreach ($aAlbums as $aAlbum)
		{
			$aAlbumCovers[$aAlbum['album_id']] = $aAlbum['photo_cover'];
		}
		return $aAlbumCovers;
	}

	public function getAlbumsFromCache()
    {
		$iServiceUserId = $this->getUserId();
		$sCacheId = $this->setCacheId('album_' . $iServiceUserId);
		$aAlbums = $this->cache()->get($sCacheId, $this->_cache_time['album']);
		return $aAlbums;
	}
	
    public function getAlbums($aParams = array())
    {
		$iServiceUserId = $this->getUserId();
		if (!$iServiceUserId) return false;
		$iLimit = isset($aParams['limit']) ? $aParams['limit'] : 8;
        $iOffset = isset($aParams['offset']) ? $aParams['offset'] : 0;
        $iPage = isset($aParams['page']) ? $aParams['page'] : 1;
		$aNoAlbums = array (0, array());
		$sCacheId = $this->setCacheId('album_' . $iServiceUserId);
		$aAlbums = $this->cache()->get($sCacheId, $this->_cache_time['album']);
		if (empty($aAlbums) || !is_array($aAlbums))
		{
			$aAlbums = array();
		}
		$iCnt = count($aAlbums);
		$iPageCached = ceil($iCnt/$iLimit);
		if ($iPageCached < $iPage)
		{
			$aNewParams['limit'] = $iLimit;
			$aNewParams['offset'] = $iOffset;
			$aNewAlbums = parent::getAlbums($aNewParams);
			if ($aNewAlbums)
			{	
				$aNewAlbums = $this->_prepareAlbums($aNewAlbums);
				$aAlbums = array_merge($aAlbums, $aNewAlbums);
				$sCacheId = $this->setCacheId('album_' . $iServiceUserId);
				$this->cache()->save($sCacheId, $aAlbums);
			}
		}
		$iCnt = count($aAlbums);
		$aAlbums = Phpfox::getService('socialmediaimporter.common')->arraySlice($aAlbums, $iOffset, $iLimit);
		return array($iCnt, $aAlbums);
	}

	public function getPhotos($aParams = array())
    {
		$iServiceUserId = $this->getUserId();
		$iLimit = isset($aParams['limit']) ? $aParams['limit'] : 10;
        $iOffset = isset($aParams['offset']) ? $aParams['offset'] : 0;
        $iPrivacy = isset($aParams['privacy']) ? $aParams['privacy'] : 0;
        $iPrivacyComment = isset($aParams['privacy_comment']) ? $aParams['privacy_comment'] : 0;
        $sAlbumIds = isset($aParams['album_id']) ? $aParams['album_id'] : '';
        $sServiceAlbumId = isset($aParams['service_album_id']) ? $aParams['service_album_id'] : '';
		$sAlbumNames = isset($aParams['album_name']) ? $aParams['album_name'] : '';
		$sPhotoIds = isset($aParams['photo_id']) ? $aParams['photo_id'] : '';
        $bIsImportAlbum = isset($aParams['is_import_album']) ? $aParams['is_import_album'] : 0;
        $bIsImportPhoto = isset($aParams['is_import_photo']) ? $aParams['is_import_photo'] : 0;
        $sType = isset($aParams['type']) ? $aParams['type'] : '';
		if (!$iServiceUserId) return false;
        $aAlbums = array();
		$aNoPhotos = array (0, array(), 0, '');
		$iFoxAlbumId = 0;
		$sError = '';
		try
        {
			if ($sType == 'load_album' || $sType == 'load_photo' || $sType == 'import_album' || $sType == 'import_photo')
			{
				if ($sType == 'import_photo')
				{
					$sAlbumIds = $sServiceAlbumId;
				}
				$aAlbumIds = explode('","', $sAlbumIds);
				$aGetAlbumIds = $aRows = array();
				for ($i = 0; $i < count($aAlbumIds); $i++)
				{
					$sAlbumId = $aAlbumIds[$i];
					$sCacheId = $this->setCacheId('photo_' . $sAlbumId);
					if (($aPhotosInAlbum = $this->cache()->get($sCacheId, $this->_cache_time['photo'])))
					{
						$aRows = $aRows ? array_merge($aRows, $aPhotosInAlbum) : $aPhotosInAlbum;
					}
					else
					{
						$aGetAlbumIds[] = $aAlbumIds[$i];
					}
				}
				if ($aGetAlbumIds)
				{					
					$aAlbums = $this->getAlbumsFromCache();					
					$aAlbums = Phpfox::getService('socialmediaimporter.common')->arrayKeyValue($aAlbums, 'album_id', 'size');
					for ($i = 0; $i < count($aGetAlbumIds); $i++)
					{					
						$sAlbumId = $aGetAlbumIds[$i];
						$iSize = isset($aAlbums[$sAlbumId]) ? $aAlbums[$sAlbumId] : 0;
						if ($iSize <= 0) continue;
						
						$aNewParams['album'] = $sAlbumId;
						$aNewParams['limit'] = 100;
						$aNewParams['offset'] = 0;
						$aPhotosInAlbum = array();
						do 
						{							
							$aNewPhotosInAlbum = parent::getPhotos($aNewParams);
							$aPhotosInAlbum = array_merge($aPhotosInAlbum, $aNewPhotosInAlbum);
							$aNewParams['offset'] = $aNewParams['offset'] + $aNewParams['limit'];
						}
						while ((count($aPhotosInAlbum) < $aAlbums[$sAlbumId]) && (count($aNewPhotosInAlbum) == $aNewParams['limit']));
						
						if ($aPhotosInAlbum)
						{
							$aPhotosInAlbum = $this->_preparePhotos($aPhotosInAlbum, $sAlbumId);
							$aRows = array_merge($aRows, $aPhotosInAlbum);
							$sCacheId = $this->setCacheId('photo_' . $sAlbumId);
							$this->cache()->save($sCacheId, $aPhotosInAlbum);
						}
					}
				}
			}

			if ($sType == 'load_photo_tag' || $sType == 'import_photo_tag')
			{
				//$aPhotoTags = $this->getPhotoTags($aParams);
				//$aRows = isset($aPhotoTags[1]) ? $aPhotoTags[1] : array();
			}
        }
        catch (exception $e)
        {
			return false;
        }
		if (!$aRows)
		{
			return $aNoPhotos;
		}
		$iCnt = count($aRows);
		$aRows = Phpfox::getService('socialmediaimporter.common')->arraySlice($aRows, $iOffset, $iLimit);
		$iQueueId = 0;
		$aFoxAlbums = $aTempAlbums = array();
		if ($iCnt && $sAlbumNames && $sType == 'import_album')
		{
			$aAlbums = array();
			$aAlbumNames = explode('","', $sAlbumNames);
			for ($i = 0; $i < count($aAlbumIds); $i++)
			{
				$aAlbums[$aAlbumIds[$i]] = $aAlbumNames[$i];
			}			
			//$aAlbumCovers = $this->getCoversAlbums();
			$aParams = array();
			$aParams['privacy'] = $iPrivacy;
			$aParams['privacy_comment'] = $iPrivacyComment;
			$aParams['service'] = $this->_sService;
			$aParams['media_type'] = 'photo';
			foreach ($aAlbums as $sAlbumId => $sAlbumName)
			{
				$aAlbum['name'] = $sAlbumName;
				$aAlbum['description'] = $sAlbumName;
				$aAlbum['privacy'] = $iPrivacy;
				$aAlbum['privacy_comment'] = $iPrivacyComment;				
				$aTempAlbums[] = $iTempAlbumId = Phpfox::getService('socialmediaimporter.process')->addTempAlbum($aAlbum);
				for ($i = 0; $i < count($aRows); $i++)
				{
					if ($aRows[$i]['album_id'] == $sAlbumId)
					{
						Phpfox::getService('socialmediaimporter.process')->addTracking(Phpfox::getUserId(), $sAlbumId, 0, 'album',$this->_sService);
						$aRows[$i]['album_id'] = $iTempAlbumId;
					}
				}				
			}
			$aParams['media'] = $aRows;
			$iQueueId = Phpfox::getService('socialmediaimporter.process')->addQueue($aParams, array(), $aTempAlbums);
		}

		if ($iCnt && $sPhotoIds && ($sType == 'import_photo' || $sType == 'import_photo_tag'))
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


    public function disconnect()
    {
        $this->removeCache();
        $this->rpcCall($this->_urls['logout']);
    }
}
?>