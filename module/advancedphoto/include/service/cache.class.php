<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class Advancedphoto_Service_Cache extends Phpfox_Service {

	private $_sPrefix = '';
	private $_sDir = 'advancedphoto';
	private $_bIsDebug = false;

	public function __construct() {
		$this->_sPrefix = 'advancedphoto_';
		if(defined('PHPFOX_DEBUG') && PHPFOX_DEBUG)
		{
			$this->_bIsDebug = false;
		}
	}

	public function set($sKey, $aValue) {
		if($this->_bIsDebug)
		{
			Phpfox_error::log('set to cache key: ' . $sKey , '', 18);
		}
		$sKey = $this->_sPrefix . $sKey;
		$sName = array($this->_sDir, $sKey);
		$sCacheId = $this->cache()->set($sName);
		$iId = $this->cache()->save($sCacheId, $aValue);
		return $iId;
	}


	/*
	 * @params 
	 * 
	 * note: iExpiredTime is in minute
	 */
	public function get($sKey, $sType = 'default') {
		if($this->_bIsDebug)
		{
			Phpfox_error::log('get from cache key: ' . $sKey , '', 18);
		}

		$sKey = $this->_sPrefix . $sKey;
		$sName = array($this->_sDir, $sKey);
		$sCacheId = $this->cache()->set($sName);
		switch($sType)
		{
			case 'timelineyears';
					$iExpiredTime = 1440;
					return $this->cache()->get($sCacheId, $iExpiredTime);	
				break;
			case 'featured_photos':
					$iExpiredTime = 1;
					return $this->cache()->get($sCacheId, $iExpiredTime);	
				break;
			case 'mostviewed_photos':
			case 'mostliked_photos':
			case 'mostcommented_photos':
			case 'newest_photos':
					$iExpiredTime = 1;
					return $this->cache()->get($sCacheId, $iExpiredTime);	
				break;
			case 'todaytop_photos':
			case 'thisweektop_photos':
			case 'top_uploadmembers':
			case 'total_albums':
			case 'total_photos':
			case 'thismonthtop_photos':
					$iExpiredTime = 1;
					return $this->cache()->get($sCacheId, $iExpiredTime);	
				break;
			default :
					$iExpiredTime = 1;
					return $this->cache()->get($sCacheId, $iExpiredTime);
				break;
		}

		return false;
	}

	public function getConst($sKey) {
		switch ($sKey) {
			case 'user_total_song_thres':
				return 100;
				break;
			case 'is_smart_cache':
				return true;
				break;
			case 'top_play_thres':
				return 10;
				break;
		}

		return 100;
	}

}

?>