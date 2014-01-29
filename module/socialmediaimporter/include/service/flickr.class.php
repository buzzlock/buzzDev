<?php
require_once (PHPFOX_DIR . 'module' . PHPFOX_DS . 'socialmediaimporter' . PHPFOX_DS . 'include' . PHPFOX_DS . 'service' . PHPFOX_DS . 'libs' . PHPFOX_DS . 'phpFlickr.php');
require_once (PHPFOX_DIR . 'module' . PHPFOX_DS . 'socialmediaimporter' . PHPFOX_DS . 'include' . PHPFOX_DS . 'service' . PHPFOX_DS . 'abstract.php');
class Socialmediaimporter_Service_Flickr extends Socialmediaimporter_Service_Abstract
{    
	protected $_oFlickr = NULL;
	protected $_sService = 'flickr';
    protected $_urls = array('host' => '');    		
	protected $_cache_id = '';
	protected $_cache_time = array();

    /**
	 * constructor
	 * @return void
	 */
    public function __construct()
    {
        $aConfig = array();
        $aProvider = Phpfox::getService('socialmediaimporter.providers')->getProvider($this->_sService);
        $aConfig = $aProvider['params'];
        if (!$aConfig)
		{
			return false;
		}
		$this->_oFlickr = new phpFlickrYNSS($aConfig["app_id"], $aConfig["secret"]);
        $aToken = $this->getToken();
        if (!empty($aToken) && isset($aToken["token"]))
        {
			if (Phpfox::getLib('parse.format')->isSerialized(base64_decode($aToken["token"])))
			{
				ob_start();
				$aFOToken = @unserialize(base64_decode($aToken["token"]));
				ob_get_contents();
				$this->_oFlickr->setToken($aFOToken["token"]);
			}
        }
		$this->_cache_id = 'socialmediaimporter_flickr_' . Phpfox::getUserId();
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
	 * see /static/php/static.php
	 * @param Request $oRequest
	 * @param array $aParams
	 * @return array
	 */
    public function processAuthCallback($oRequest, $aParams)
    {
		$sFrob = $aParams['frob'];
        list($sToken, $aExtra) = $this->processAgentProfile(array("frob" => $sFrob));
        $this->setToken($sToken, $aExtra);
        return $aExtra;
    }

    public function processAgentProfile($aParams = array())
    {
        $oFlickr = $this->_oFlickr;
        $sFrob = $aParams["frob"];
        $aResTokenInfo = $oFlickr->auth_getToken($sFrob);
        $sToken = $aResTokenInfo["token"];
        $me = $aResTokenInfo["user"];
		if (isset($me['nsid']) && $me['nsid'])
		{
			$aUserInfo = $oFlickr->people_getInfo($me['nsid']);
			$aUserProfile['user_name'] = isset($me['username']) ? $me['username'] : "";
			$aUserProfile['full_name'] = isset($me['fullname']) ? $me['fullname'] : "";
			$aUserProfile['identity'] = $me['nsid'];
			$aUserProfile['service'] = 'flickr';
			if (isset($aUserInfo["iconfarm"]) && $aUserInfo["iconfarm"] > 0)
			{
				$aUserProfile['img_url'] = sprintf("http://farm%d.staticflickr.com/%d/buddyicons/%s.jpg", $aUserInfo["iconfarm"], $aUserInfo["iconserver"], $aUserInfo["nsid"]);
			}
			return array ($aResTokenInfo, $aUserProfile);
		}
		return array();
    }

    /**
	 * get connect/auth url
	 * @param bool $bRedirect PREDECATED
	 * @param array $params
	 * @return string
	 */
    public function getAuthUrl($bRedirect = 0, $params = array())
    {
        return $this->_oFlickr->getUrl("read");
	}

	public function getCoversAlbums()
	{
		$aParams['offset'] = 0;
		$aParams['limit'] = 1000;
		list ($iCnt, $aAlbums) = $this->getAlbums($aParams);
		$aAlbumCovers = array();
		foreach ($aAlbums as $aAlbum)
		{
			$aAlbumCovers['album_id'] = $aAlbum['photo_cover'];
		}
		return $aAlbumCovers;
	}	

	public function getAlbums($aParams = array())
    {		
		$oFlickr = $this->_oFlickr;
		$iServiceUserId = $this->getUserId();
		if (!$iServiceUserId) return false;
		$iLimit = isset($aParams['limit']) ? $aParams['limit'] : 8;
        $iOffset = isset($aParams['offset']) ? $aParams['offset'] : 0;
		$aToken = $this->getToken();		
		$sCacheId = $this->setCacheId($sIdentity = 'album_' . $iServiceUserId);
		if (!($aAlbums = $this->cache()->get($sCacheId, $this->_cache_time['album'])))
		{
			try
			{
				$aAlbumSets = $oFlickr->photosets_getList($iServiceUserId);				
				$aAlbums = $aAlbumSets['photoset']; //print_r($aAlbums); exit;
				foreach ($aAlbums as $i => $aAlbum)
				{
					$aAlbums[$i]['album_id'] = $aAlbum["id"];
					$aAlbums[$i]['name'] = $aAlbum["title"];
					$aAlbums[$i]['size'] = $aAlbum["photos"];
					if ($aAlbum["photos"] > 0 && $aAlbum["primary"])
					{
						$aPrimaryPhoto = $oFlickr->photos_getSizes($aAlbum["primary"]);
						foreach ($aPrimaryPhoto as $aPhoto)
						{
							if ($aPhoto['label'] == 'Large Square')
							{
								$aAlbums[$i]['photo_cover'] = $aPhoto["source"];
							}
						}
					}
					Phpfox_Error::skip(true);
					unset($aAlbums[$i]['id']);
					unset($aAlbums[$i]['primary']);
					unset($aAlbums[$i]['secret']);
					unset($aAlbums[$i]['server']);
					unset($aAlbums[$i]['title']);
					unset($aAlbums[$i]['farm']);
					unset($aAlbums[$i]['photos']);
					unset($aAlbums[$i]['videos']);
					unset($aAlbums[$i]['needs_interstitial']);
					unset($aAlbums[$i]['visibility_can_see_set']);
					unset($aAlbums[$i]['count_views']);
					unset($aAlbums[$i]['count_comments']);
					unset($aAlbums[$i]['can_comment']);
					unset($aAlbums[$i]['date_create']);
					unset($aAlbums[$i]['date_update']);
					Phpfox_Error::skip(false);
				}
				$this->cache()->save($sCacheId, $aAlbums);
			}
			catch (exception $e)
			{			
				return false;
			}
		}
		$iCnt = count($aAlbums);
		$aAlbums = Phpfox::getService('socialmediaimporter.common')->arraySlice($aAlbums, $iOffset, $iLimit);
		return array($iCnt, $aAlbums);
	}	
	
	public function getPhotoById($iPhotoId = 0)
    {
		$oFlickr = $this->_oFlickr;
		$aPhotos = $oFlickr->photos_getSizes($iPhotoId);
		$sThumb = $sLarge = '';
		foreach ($aPhotos as $aPhoto)
		{
			if ($aPhoto['label'] == 'Large Square')
			{
				$sThumb = $aPhoto['source'];
			}
			if ($aPhoto['label'] == 'Large')
			{
				$sLarge = $aPhoto['source'];
			}
			if ($sLarge == '' && $aPhoto['label'] == 'Medium')
			{
				$sLarge = $aPhoto['source'];
			}
			if ($sLarge == '' && $aPhoto['label'] == 'Small 320')
			{
				$sLarge = $aPhoto['source'];
			}
			if ($sLarge == '' && $aPhoto['label'] == 'Small')
			{
				$sLarge = $aPhoto['source'];
			}								
		}
		return array ($sThumb, $sLarge);
	}
	
	public function getPhotos($aParams = array())
    {
		$iUserId = Phpfox::getUserId();
		$oFlickr = $this->_oFlickr;
		$iServiceUserId = $this->getUserId();
		if (!$iServiceUserId) return false;
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
        $aAlbums = $aTracking = array();
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
						$aRows = array_merge($aRows, $aPhotosInAlbum);
					}
					else
					{
						$aGetAlbumIds[] = $sAlbumId;
					}
				}
				
				Phpfox_Error::skip(true);
				if ($aGetAlbumIds)
				{					
					for ($i = 0; $i < count($aGetAlbumIds); $i++)
					{
						$sAlbumId = $aGetAlbumIds[$i]; 
						$aPhotosInAlbums = $oFlickr->photosets_getPhotos($sAlbumId);
						$aPhotosInAlbums = isset($aPhotosInAlbums['photoset']['photo']) ? $aPhotosInAlbums['photoset']['photo'] : array();
						foreach ($aPhotosInAlbums as $j => $aPhotosInAlbum)
						{
							$aPhotosInAlbums[$j]['album_id'] = $sAlbumId;
							$aPhotosInAlbums[$j]['photo_id'] = $aPhotosInAlbum["id"];
							$aPhotosInAlbums[$j]['title'] = $aPhotosInAlbum["title"];							
							$aPhotosInAlbums[$j]['is_cover'] = $aPhotosInAlbum["isprimary"] ? 1 : 0;						
							unset($aPhotosInAlbums[$j]['id']);
							unset($aPhotosInAlbums[$j]['secret']);
							unset($aPhotosInAlbums[$j]['server']);
							unset($aPhotosInAlbums[$j]['farm']);
							unset($aPhotosInAlbums[$j]['isprimary']);					
						}
						$aRows = array_merge($aRows, $aPhotosInAlbums);
						$sCacheId = $this->setCacheId('photo_' . $sAlbumId);
						$this->cache()->save($sCacheId, $aPhotosInAlbums);
					}					
				}
				Phpfox_Error::skip(false);
			}

			if ($sType == 'load_photo_tag' || $sType == 'import_photo_tag')
			{
				list ($iCnt, $aRows) = $aPhotoTags = $this->getPhotoTags($aParams);
			}
        }
        catch (exception $e)
        {
			return null;
        }
		
		$iCnt = count($aRows);
		$aChecks = array_slice($aRows, $iOffset, $iLimit);
		$aGetIds = Phpfox::getService('socialmediaimporter.common')->arrayField($aChecks, 'photo_id');		
		$aAlbumsChanged = array();
		foreach ($aRows as $i => $aRow)
		{
			if (!isset($aRow['photo_thumb']) || !isset($aRow['photo_large']))
			{
				if (Phpfox::getService('socialmediaimporter.common')->inArray($aRow['photo_id'], $aGetIds))
				{
					$aAlbumsChanged[] = $aRow['album_id'];
					list ($aRows[$i]['photo_thumb'], $aRows[$i]['photo_large']) = $this->getPhotoById($aRow['photo_id']);
				}
			}
		}

		if ($aAlbumsChanged)
		{
			foreach ($aAlbumsChanged as $sAlbumId)
			{
				$aPhotosInAlbums = Phpfox::getService('socialmediaimporter.common')->arrayFilter($aRows, 'album_id', $sAlbumId);
				$sCacheId = $this->setCacheId('photo_' . $sAlbumId);
				$this->cache()->save($sCacheId, $aPhotosInAlbums);
			}
		}

		$aRows = array_slice($aRows, $iOffset, $iLimit);

		$iQueueId = 0;
		$aFoxAlbums = array();
		if ($iCnt && $sAlbumNames && $sType == 'import_album')
		{
			$aAlbumNames = explode('","', $sAlbumNames);
			for ($i = 0; $i < count($aAlbumIds); $i++)
			{
				$aAlbums[$aAlbumIds[$i]] = $aAlbumNames[$i];
			}			
			$aParams = array();
			$aParams['privacy'] = $iPrivacy;
			$aParams['privacy_comment'] = $iPrivacyComment;
			$aParams['service'] = $this->_sService;
			$aParams['media_type'] = 'photo';
			$aTempAlbums = array();
			foreach ($aAlbums as $iAlbumId => $sAlbumName)
			{
				$aAlbum['name'] = $sAlbumName;
				$aAlbum['description'] = $sAlbumName;
				$aAlbum['privacy'] = $iPrivacy;
				$aAlbum['privacy_comment'] = $iPrivacyComment;
				$aTempAlbums[] = $iTempAlbumId = Phpfox::getService('socialmediaimporter.process')->addTempAlbum($aAlbum);
				for ($i = 0; $i < count($aRows); $i++)
				{					
					if ($aRows[$i]['album_id'] == $iAlbumId)
					{
						Phpfox::getService('socialmediaimporter.process')->addTracking(Phpfox::getUserId(), $iAlbumId, 0, 'album', $this->_sService);
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

	public function getPhotoTags($aParams = array())
    {
		$oFlickr = $this->_oFlickr;
		$iServiceUserId = $this->getUserId();
		if (!$iServiceUserId) return false;
		$iLimit = isset($aParams['limit']) ? $aParams['limit'] : 8;
        $iOffset = isset($aParams['offset']) ? $aParams['offset'] : 0;		
        $iPage = isset($aParams['page']) ? $aParams['page'] : 1;		
		try
        {
			$sCacheId = $this->setCacheId('photo_tag_' . $iServiceUserId);
			if (!($aReturns = $this->cache()->get($sCacheId, $this->_cache_time['photo_tag'])))
			{
				$aReturns = $oFlickr->photos_search(array("user_id" => $iServiceUserId));
				$iCnt = isset($aReturns['total']) ? $aReturns['total'] : 0;
				$aRows = isset($aReturns['photo']) ? $aReturns['photo'] : array();				
				foreach ($aRows as $j => $aRow)
				{
					$aRows[$j]['album_id'] = '';
					$aRows[$j]['photo_id'] = $aRow["id"];
					$aRows[$j]['title'] = $aRow["title"];							
					$aRows[$j]['is_cover'] = 0;							
					unset($aRows[$j]['id']);
					unset($aRows[$j]['owner']);
					unset($aRows[$j]['secret']);
					unset($aRows[$j]['server']);
					unset($aRows[$j]['isfamily']);											
					unset($aRows[$j]['ispublic']);											
					unset($aRows[$j]['isfriend']);											
					unset($aRows[$j]['farm']);											
				}
				$aReturns = array($iCnt, $aRows);
				$this->cache()->save($sCacheId, $aReturns);
			}      
			$iCnt = isset($aReturns[0]) ? $aReturns[0] : 0;
			if (isset($aReturns[1]) && is_array($aReturns[1]))
			{
				$aChecks = array_slice($aReturns[1], $iOffset, $iLimit);
				$aGetIds = Phpfox::getService('socialmediaimporter.common')->arrayField($aChecks, 'photo_id');
			}
			else
			{
				return array (0, array());
			}
			$aRows = $aReturns[1];
			$bIsGetNew = 0;
			foreach ($aRows as $i => $aRow)
			{
				if (!isset($aRow['photo_thumb']) || !isset($aRow['photo_large']))
				{
					if (Phpfox::getService('socialmediaimporter.common')->inArray($aRow["photo_id"], $aGetIds))
					{
						$bIsGetNew = 1;							
						list ($aRows[$i]['photo_thumb'], $aRows[$i]['photo_large']) = $this->getPhotoById($aRow['photo_id']);
					}
				}
			}
			if ($bIsGetNew)
			{		
				$sCacheId = $this->setCacheId('photo_tag_' . $iServiceUserId);
				$this->cache()->save($sCacheId, array($iCnt, $aRows));
			}
			$aRows = array_slice($aRows, $iOffset, $iLimit);
			return array($iCnt, $aRows);
		}
        catch (exception $ex)
        {
			return false;
        }
	}
	
	public function disconnect()
    {             
        $this->removeCache();        
    }
}
?>