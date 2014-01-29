<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 *
 *
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		YouNet Company
 * @package 		Phpfox_SocialMediaImporter 
 */
require_once (PHPFOX_DIR . 'module' . PHPFOX_DS . 'socialmediaimporter' . PHPFOX_DS . 'include' . PHPFOX_DS . 'service' . PHPFOX_DS . 'libs' . PHPFOX_DS . 'facebook.php');
require_once (PHPFOX_DIR . 'module' . PHPFOX_DS . 'socialmediaimporter' . PHPFOX_DS . 'include' . PHPFOX_DS . 'service' . PHPFOX_DS . 'abstract.php');
class Socialmediaimporter_Service_Facebook extends Socialmediaimporter_Service_Abstract
{   
    protected $_oFacebook = null;
	protected $_sService = 'facebook';   
	protected $_sAlbumFields = 'aid, name, size, cover_pid, cover_object_id';
	protected $_sPhotoFields = 'pid, aid, src_small, src_big, src, images';
	protected $_cache_id = '';
	protected $_cache_time = array();
	
	public function __construct()
    {
        $aConfig = array ('cookie' => false);
        $aProvider = Phpfox::getService('socialmediaimporter.providers')->getProvider($this->_sService);
        $aConfig = $aProvider['params'];
        $this->_oFacebook = new FacebookYNSSI($aConfig);
        $aToken = $this->getToken();
        if (!empty($aToken))
		{
            $this->_oFacebook->setAccessToken($aToken);
        }		
		$this->_cache_id = 'socialmediaimporter_facebook_' . Phpfox::getUserId();
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
		$oFacebook = $this->_oFacebook;
		if($oFacebook)
		{
			$oFacebook->destroySession();
		}
	}

    /**
	 * @param Request $oRequest
	 * @param array $aParams
	 * @return bool
	 */
    public function processAuthCallback($oRequest, $aParams)
    {
        //$aParams['session'] = stripslashes($aParams['session']);
        //$aSession = (array)(json_decode($aParams['session'], true));
        list($aSession, $aExtra) = $this->processAgentProfile($aParams);		
        if ($aExtra['identity'])
		{
			$this->setToken($aSession, $aExtra);
        }
		return $aExtra;
    }

    /**
	 * @param array $params
	 * @return array
	 *
	 */
    public function processAgentProfile($aParams = array())
    {
        $oFacebook = $this->_oFacebook;
		$aParams= $oFacebook->getAccessToken();
        //$oFacebook->setAccessToken($aParams);
        $iUserProfileId = $oFacebook->getUser();				
        if (isset($iUserProfileId) && $iUserProfileId)
        {
            $me = $oFacebook->api('/me');
			$aUserProfile = array();
			$aUserProfile['user_name'] = isset($me['username']) ? $me['username'] : "";
			$aUserProfile['full_name'] = isset($me['name']) ? $me['name'] : "";
			$aUserProfile['identity'] = $iUserProfileId;
			$aUserProfile['service'] = $this->_sService;
			$aUserProfile['img_url'] = $imgLink = sprintf("http://graph.facebook.com/%s/picture", $aUserProfile['identity']);
			return array ($aParams, $aUserProfile);
        }
		return array ($aParams, array());
    }

    /**
	 * get auth urls.
	 * @param bool redirect =  0 PREDECATED
	 * @param array $params PREDECATED
	 * @return string
	 */
    public function getAuthUrl($bRedirect = 0, $params = array())
    {
        $sReturnUrl = Phpfox::getParam('core.path') . 'module/socialmediaimporter/static/php/static.php';
		
		$aPermission['scope'] = "user_photos,friends_photos,offline_access";
		$aPermission['service'] = $this->_sService;
		$aPermission['redirect'] = $bRedirect;
		$aPermission['prevent'] = time();
		$aPermission['redirect_uri'] = $sReturnUrl;
		
		$sUrlAuth = $this->_oFacebook->getLoginUrl($aPermission);

        return $sUrlAuth;
    }		

	public function getCoversAlbums($aParams = array())
    {		
		$iServiceUserId = $this->_oFacebook->getUser();
		$sCacheId = $this->setCacheId('album_' . $iServiceUserId);
		if (!($aAlbums = $this->cache()->get($sCacheId, $this->_cache_time['album'])))			
		{
			return $aAlbums['covers'];
		}
		return array();
	}
	public function renewFacebookToken()
	{
	   $aProvider = phpfox::getService('socialmediaimporter.providers')->getProvider('facebook');

	   $aConfig = $aProvider['params'];
	   $aAgent = Phpfox::getService('socialmediaimporter.agents')->getToken(Phpfox::getUserId(), $this->_sService);
	   // $aToken = $this->getToken();
	   
	   $aToken = "";
	   if (!isset($aAgent['token']) || empty($aAgent['token']))
	   {
		  return false;
	   }
	   else
	   {
		  $sToken = base64_decode($aAgent['token']);
		  if (phpfox::getLib('parse.format')->isSerialized($sToken))
		  {
			 $aToken = unserialize($sToken);
		  }
		  else
		  {
			 $aToken = $sToken;
		  }
	   }		
	   $url = "https://graph.facebook.com/oauth/access_token?client_id=" . $aConfig['app_id'] . "&client_secret=" . $aConfig['secret'] . "&grant_type=fb_exchange_token&fb_exchange_token=" . $aToken;
	   // var_dump($url, $aToken, $aAgent);
	   // exit;

	   $ch = curl_init();

	   curl_setopt($ch, CURLOPT_URL, $url);
	   curl_setopt($ch, CURLOPT_HEADER, 0);
	   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	   $response = curl_exec($ch);
	   curl_close($ch);	
	   if ($response)
	   {
		  $token_data = null;
		  parse_str($response, $token_data);		  
		  $access_token = base64_encode($token_data['access_token']);
		  $this->database()->update(Phpfox::getT('socialmediaimporter_agents'), array('token' => $access_token), 'user_id=' . Phpfox::getUserId() . ' AND service_id=' . $aProvider['service_id']);
		  return $access_token;
	   }
	   return false;
	}
	public function getAlbums($aParams = array())
    {		
		$this->renewFacebookToken();
		$oFacebook = $this->_oFacebook;
		$iServiceUserId = $oFacebook->getUser();
		if (!$iServiceUserId) return array(-1, array());
		$iLimit = isset($aParams['limit']) ? $aParams['limit'] : 10;
        $iOffset = isset($aParams['offset']) ? $aParams['offset'] : 0;		
		$sCacheId = $this->setCacheId('album_' . $iServiceUserId);
		if (!($aAlbums = $this->cache()->get($sCacheId, $this->_cache_time['album'])))		
		{
			try
			{
				$aAlbums = $oFacebook->api(array(
					'method' => 'fql.query',
					'query' => "SELECT " . $this->_sAlbumFields . " FROM album WHERE owner = me() AND size > 0"
				));
				
				if (!$aAlbums) return array();
				$aCoverPhotoIds = array();
				foreach ($aAlbums as $aAlbum)
				{
					$aCoverPhotoIds[] = sprintf("\"%s\"", $aAlbum["cover_pid"]);
				}
				$sCoverPhotoIds = implode(",", $aCoverPhotoIds);
				$aAlbumCovers = $oFacebook->api(array(
					'method' => 'fql.query',
					'query' => "SELECT " . $this->_sPhotoFields . " FROM photo WHERE pid IN ($sCoverPhotoIds)"
				));
				
				$aAlbumCoverTemp = array();
				foreach ($aAlbumCovers as $aAlbumCover)
				{
					if (isset($aAlbumCover["images"]) && isset($aAlbumCover["images"][5]) && $aAlbumCover["images"][5]["width"] == "180")
					{
						$aAlbumCoverTemp[$aAlbumCover['pid']] = $aAlbumCover["images"][5]["source"];
					}
					else
					{
						$aAlbumCoverTemp[$aAlbumCover['pid']] = $aAlbumCover['src'];
					}
				}				
				$aAlbumCovers = $aAlbumCoverTemp;				
				$aAlbumTemp = array();
				foreach ($aAlbums as $i => $aAlbum)				
				{
					$iCoverId = $aAlbum['cover_pid'];
					if (isset($aAlbumCovers[$iCoverId]))
					{
						$aAlbums[$i]['album_id'] = $aAlbum['aid'];
						$aAlbums[$i]['name'] = $aAlbum['name'];
						$aAlbums[$i]['size'] = $aAlbum['size'];
						$aAlbums[$i]['photo_cover'] = $aAlbumCovers[$iCoverId];
						$aAlbumTemp[] = $aAlbums[$i];
					}
				}				
				$aAlbums = array ('albums' => $aAlbumTemp, 'covers' => $aAlbumCovers);
				$this->cache()->save($sCacheId, $aAlbums);
			}
			catch (exception $e)
			{				
				return null;
			}
		}
		$iCnt = count($aAlbums['albums']);
		$aAlbums = Phpfox::getService('socialmediaimporter.common')->arraySlice($aAlbums['albums'], $iOffset, $iLimit);
		return array($iCnt, $aAlbums);
	}

	public function getPhotos($aParams = array())
    {		
		$oFacebook = $this->_oFacebook;
		$iServiceUserId = $oFacebook->getUser();
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
		if (!$iServiceUserId) return array(-1, array(), 0);
        $aAlbums = array();
		$iFoxAlbumId = 0;
		$sError = '';
					
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
			if ($aGetAlbumIds)
			{					
				for ($i = 0; $i < count($aGetAlbumIds); $i++)
				{
					$sAlbumId = $aGetAlbumIds[$i];
					$aNewRows = $oFacebook->api(array(
						'method' => 'fql.query',
						'query' => 'SELECT ' . $this->_sPhotoFields . ' FROM photo WHERE aid ="' . $sAlbumId . '"'
					));
					$aNewRows = $this->processRows($aNewRows);
					$aRows = array_merge($aRows, $aNewRows);
					$sCacheId = $this->setCacheId('photo_' . $sAlbumId);
					$this->cache()->save($sCacheId, $aNewRows);
				}
			}
		}
		
		if ($sType == 'load_photo_tag' || $sType == 'import_photo_tag')
		{
			$aPhotoTags = $this->getPhotoTags($aParams);
			$aRows = isset($aPhotoTags[1]) ? $aPhotoTags[1] : array();				
		}
        
		$iCnt = count($aRows);
		$aRows = Phpfox::getService('socialmediaimporter.common')->arraySlice($aRows, $iOffset, $iLimit);
		$iQueueId = 0;
		$aFoxAlbums = array();
		if ($iCnt && $sAlbumNames && $sType == 'import_album')
		{		
			$aAlbumNames = explode('","', $sAlbumNames);
			for ($i = 0; $i < count($aAlbumIds); $i++)
			{
				$aAlbums[$aAlbumIds[$i]] = $aAlbumNames[$i];
			}			
			$aAlbumCovers = $this->getCoversAlbums();
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
					$aRows[$i]['is_cover'] = isset($aAlbumCovers[$aRows[$i]['photo_id']]) ? 1 : 0;
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

	public function getPhotoTags($aParams = array())
    {		
		$oFacebook = $this->_oFacebook;
		$iServiceUserId = $oFacebook->getUser();
		$iLimit = isset($aParams['limit']) ? $aParams['limit'] : 8;
        $iOffset = isset($aParams['offset']) ? $aParams['offset'] : 0;
		if (!$iServiceUserId) return array(-1, array());
		try
        {					
			$sCacheId = $this->setCacheId('photo_tag_' . $iServiceUserId);			
			if (!($aRows = $this->cache()->get($sCacheId, $this->_cache_time['photo_tag'])))
			{
				$aRows = $oFacebook->api(array(
					'method' => 'fql.query',
					'query' => "SELECT " . $this->_sPhotoFields . " FROM photo WHERE pid IN (SELECT pid FROM photo_tag WHERE subject = me())"
				));
				if ($aRows)
				{
					$aRows = $this->processRows($aRows);
					$this->cache()->save($sCacheId, $aRows);
				}
			}
        }
        catch (exception $ex)
        {
			return array(-1, array());
        }
		$iCnt = count($aRows);
		$aRows = Phpfox::getService('socialmediaimporter.common')->arraySlice($aRows, $iOffset, $iLimit);
		return array($iCnt, $aRows);
	}
	
	public function processRows($aRows, $isOneRow = 0)
	{
		if (!$aRows) return array();
		if ($isOneRow == 1)
		{
			$aRows = array($aRows);
		}
		for ($i = 0; $i < count($aRows); $i++)
		{			
			$aRows[$i]['photo_id'] = $aRows[$i]['pid'];
			$aRows[$i]['album_id'] = $aRows[$i]['aid'];	
			if (isset($aRows[$i]["images"]) && isset($aRows[$i]["images"][5]) && $aRows[$i]["images"][5]["width"] == "180")
			{
				$aRows[$i]['photo_thumb'] = $aRows[$i]["images"][5]["source"];
				unset($aRows[$i]["images"]);
			}
			else
			{
				$aRows[$i]['photo_thumb'] = $aRows[$i]['src'];
			}						
			$aRows[$i]['photo_large'] = $aRows[$i]['src_big'];
			unset($aRows[$i]["pid"]);
			unset($aRows[$i]["aid"]);
			unset($aRows[$i]["src_small"]);
			unset($aRows[$i]["src_big"]);
			unset($aRows[$i]["src"]);			
		}		
		if ($isOneRow == 1)
		{
			$aRows = $aRows[0];
		}
		return $aRows;
	}
	
	public function disconnect()
    {
		$this->removeCache();
	}
}
?>