<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * Gets photo details from the database.
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Photo
 * @version 		$Id: photo.class.php 4532 2012-07-19 10:03:18Z Miguel_Espinoza $
 */
class Advancedphoto_Service_Advancedphoto extends Phpfox_Service 
{
	private $_bIsTagSearch = false;
	
	/**
	 * Class constructor
	 */	
	public function __construct()
	{	
		$this->_sTable = Phpfox::getT('photo');	
		$this->_sTableAlias = 'p';
	}

	public function checkIsInSearchMode()
	{
		if(!$this->request()->get('search-id') && !$this->request()->get('sort') && !$this->request()->get('when')&& !$this->request()->get('show'))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	public function getTotalAlbums($aModule = array())
	{
		if(!$aModule || $aModule['sModuleName'] === '' || $aModule['sModuleName'] === 'advancedphoto')
		{
			$oCustomCache = Phpfox::getService('advancedphoto.cache');
			$sKey = 'total_albums_' ;
			$sType = 'total_albums';
			
			if (!($aResult = $oCustomCache->get($sKey, $sType)))
			{
				$aResult = $this->database()->select('count(album_id) as total_album')
						->from(Phpfox::getT('photo_album'))
						->where('module_id IS NULL')
						->execute('getRow');
			}

			return $aResult['total_album'];
		}

		return false;
		
	}
	

	public function getTotalPhotos($aModule = array())
	{
		if(!$aModule || $aModule['sModuleName'] === '' || $aModule['sModuleName'] === 'advancedphoto')
		{
			$oCustomCache = Phpfox::getService('advancedphoto.cache');
			$sKey = 'total_photos_' ;
			$sType = 'total_photos';
			
			if (!($aResult = $oCustomCache->get($sKey, $sType)))
			{
				$aResult = $this->database()->select('count(photo_id) as total_photo')
						->from($this->_sTable)
						->where('group_id = 0 AND type_id < 2 AND view_id = 0')
						->execute('getRow');

				
				$oCustomCache->set($sKey, $aResult);
			}

			return $aResult['total_photo'];
		}

		return false;

		
	}

	public function getTopUploadMembers($iLimit = 4)
	{
		$oCustomCache = Phpfox::getService('advancedphoto.cache');
		$sKey = 'top_uploadmembers_' . $iLimit;
		$sType = 'top_uploadmembers';
		
		if (!($aRows = $oCustomCache->get($sKey, $sType)))
		{
			$aRows = $this->database()->select('u.*, COUNT(*) as total_photos')
					->from(Phpfox::getT('photo'),'p')
					->join(Phpfox::getT('user'), 'u', 'u.user_id = p.user_id && u.profile_page_id = 0')
					->group('p.user_id')
					->order('total_photos DESC')
					->limit( $iLimit)
					->execute('getRows');	

			$oCustomCache->set($sKey, $aRows);
		}

		return $aRows;

		
	}

	public function getTodayTops($iLimit = 4)
	{
		$iEndDay = Phpfox::getLib('date')->mktime(23, 59, 59, Phpfox::getTime('m'), Phpfox::getTime('d'), Phpfox::getTime('Y'));				
		$iTimeDisplay = Phpfox::getLib('date')->mktime(0, 0, 0, Phpfox::getTime('m'), Phpfox::getTime('d'), Phpfox::getTime('Y'));
		
        $sCond = 'p.privacy = 0 AND p.group_id = 0 AND p.type_id < 2 AND p.view_id = 0';					
		$sCond .= ' AND (' . $this->_sTableAlias . '.time_stamp >= \'' . Phpfox::getLib('date')->convertToGmt($iTimeDisplay) . '\' AND ' . $this->_sTableAlias . '.time_stamp < \'' . Phpfox::getLib('date')->convertToGmt($iEndDay) . '\')';
		$sOrder = 'RAND()'; //'p.total_vote DESC';
		$iPage = 1;
		$iPageSize = $iLimit;
		
		$aRows = $this->get($sCond, $sOrder, $iPage, $iPageSize);

		return $aRows;
	}

	public function getThisWeekTops($iLimit = 4)
	{
		$iWeekStart = Phpfox::getLib('date')->mktime(0, 0, 0, Phpfox::getTime('m'), (date('d', Phpfox::getLib('date')->getWeekEnd()) - 6), Phpfox::getTime('Y'));
        
        $sCond = 'p.privacy = 0 AND p.group_id = 0 AND p.type_id < 2 AND p.view_id = 0';
		$sCond .= ' AND ' . $this->_sTableAlias . '.time_stamp >= \'' . Phpfox::getLib('date')->convertToGmt($iWeekStart) . '\'';
		$sOrder = 'RAND()';
		$iPage = 1;
		$iPageSize = $iLimit;
		
		$aRows = $this->get($sCond, $sOrder, $iPage, $iPageSize);

		return $aRows;
	}

	public function getThisMonthTops($iLimit = 4)
	{
		$sCond = 'p.privacy = 0 AND p.group_id = 0 AND p.type_id < 2 AND p.view_id = 0';
		$sCond .= ' AND ' . $this->_sTableAlias . '.time_stamp >= \'' . Phpfox::getLib('date')->convertToGmt(Phpfox::getLib('date')->getThisMonth()) . '\'';
		$sOrder = 'RAND()';
		$iPage = 1;
		$iPageSize = $iLimit;
		
		$aRows = $this->get($sCond, $sOrder, $iPage, $iPageSize);

		return $aRows;
	}
	
	public function getNewestPhotos($iLimit = 4)
	{
		$sCond = 'p.privacy = 0 AND p.group_id = 0 AND p.type_id < 2 AND p.view_id = 0';
		$sOrder = 'p.time_stamp DESC';
		$iPage = 1;
		$iPageSize = $iLimit;
		$oCustomCache = Phpfox::getService('advancedphoto.cache');
		$sKey = 'newest_photos_' . $iLimit;
		$sType = 'newest_photos';
		
		if (!($aRows = $oCustomCache->get($sKey, $sType)))
		{
			$aRows = $this->get($sCond, $sOrder, $iPage, $iPageSize);
			$oCustomCache->set($sKey, $aRows);
		}
		return $aRows;
	}

	public function getRandomPhotos($iLimit = 4)
	{
		$aPhotos = array();
        $sPhotoIds = '0';
        $iTotalPhotos = $this->database()->select('count(photo_id) as total_photo')
			->from($this->_sTable)
			->where('privacy = 0 AND group_id = 0 AND type_id < 2 AND view_id = 0')
			->execute('getSlaveField');
        $iLimit = min($iTotalPhotos, $iLimit);
        $iCnt = 0;
        
		while($iCnt < $iLimit)
		{
			$aPhoto = $this->database()->select(Phpfox::getUserField() . ', p1.*, pa.name AS album_url, pi.*')
				->from(Phpfox::getT('photo'), 'p1')
				->join('( SELECT ROUND(RAND() * (SELECT MAX(photo_id) FROM ' . Phpfox::getT('photo') . ')) AS photo_id)', 'p2', 'p1.photo_id >= p2.photo_id')
				->join(Phpfox::getT('user'), 'u', 'u.user_id = p1.user_id')
				->leftJoin(Phpfox::getT('photo_info'), 'pi', 'pi.photo_id = p1.photo_id')
				->leftJoin(Phpfox::getT('photo_album'), 'pa', 'pa.album_id = p1	.album_id')
				->where('p1.privacy = 0 AND p1.group_id = 0 AND p1.type_id < 2 AND p1.view_id = 0 AND p1.photo_id NOT IN ('.$sPhotoIds.')')
				->order('p1.photo_id ASC')
				->limit(1)
				->execute('getSlaveRow');
            
            if(!empty($aPhoto['photo_id']))
            {
                $aPhotos[] = $aPhoto;
                $sPhotoIds .= ', '.$aPhoto['photo_id'];
                $iCnt++;
            }
		}
        
		foreach ($aPhotos as $iKey => $aPhoto)
		{				
			if(!isset($aPhoto['photo_id']))
			{
				return array(0, array());
			}
			$sCategoryList = '';
			$aCategories = (array) $this->database()->select('category_id')
				->from(Phpfox::getT('photo_category_data'))
				->where('photo_id = ' . (int) $aPhoto['photo_id'])
				->execute('getSlaveRows');
				
			foreach ($aCategories as $aCategory)
			{
				$sCategoryList .= $aCategory['category_id'] . ',';
			}
			
			$aPhotos[$iKey]['link'] = Phpfox::permalink('advancedphoto', $aPhoto['photo_id'], $aPhoto['title']);
			$aPhotos[$iKey]['category_list'] = rtrim($sCategoryList, ',');
			$aPhotos[$iKey]['destination'] = $this->getPhotoUrl($aPhoto);
		}
		$iCnt = count($aPhotos);

		return array($iCnt,$aPhotos);

	}


	public function getMostViewedPhotos($iLimit = 4)
	{
		$sCond = 'p.privacy = 0 AND p.group_id = 0 AND p.type_id < 2 AND p.view_id = 0';
		$sOrder = 'p.total_view DESC';
		$iPage = 1;
		$iPageSize = $iLimit;
		$oCustomCache = Phpfox::getService('advancedphoto.cache');
		$sKey = 'mostviewed_photos_' . $iLimit;
		$sType = 'mostviewed_photos';
		
		if (!($aRows = $oCustomCache->get($sKey, $sType)))
		{
			$aRows = $this->get($sCond, $sOrder, $iPage, $iPageSize);
			$oCustomCache->set($sKey, $aRows);
		}
		return $aRows;
	}

	public function getMostCommentedPhotos($iLimit = 4)
	{
		$sCond = 'p.privacy = 0 AND p.group_id = 0 AND p.type_id < 2 AND p.view_id = 0';
		$sOrder = 'p.total_comment DESC';
		$iPage = 1;
		$iPageSize = $iLimit;
		$oCustomCache = Phpfox::getService('advancedphoto.cache');
		$sKey = 'mostcommneted_photos_' . $iLimit;
		$sType = 'mostcommented_photos';
		
		if (!($aRows = $oCustomCache->get($sKey, $sType)))
		{
			$aRows = $this->get($sCond, $sOrder, $iPage, $iPageSize);
			$oCustomCache->set($sKey, $aRows);
		}
		return $aRows;
	}

	public function getMostLikedPhotos($iLimit = 4)
	{
		$sCond = 'p.privacy = 0 AND p.group_id = 0 AND p.type_id < 2 AND p.view_id = 0';
		$sOrder = 'p.total_like DESC';
		$iPage = 1;
		$iPageSize = $iLimit;
		$oCustomCache = Phpfox::getService('advancedphoto.cache');
		$sKey = 'mostliked_photos_' . $iLimit;
		$sType = 'mostliked_photos';
		
		if (!($aRows = $oCustomCache->get($sKey, $sType)))
		{
			$aRows = $this->get($sCond, $sOrder, $iPage, $iPageSize);
			$oCustomCache->set($sKey, $aRows);
		}
		return $aRows;
	}
	public function getMaxPhotosPerLoad()
	{
		return Phpfox::getParam('advancedphoto.number_of_photos_each_click');
	}
	public function getUserById($iUserId)
	{
		$aUser = $this->database()->select('u.*, uf.*, ua.*')
			->from(Phpfox::getT('user'), 'u')
			->join(Phpfox::getT('user_field'), 'uf', 'uf.user_id = u.user_id')
			->join(Phpfox::getT('user_activity'), 'ua', 'ua.user_id = u.user_id')
			->where('u.user_id = ' . (int) $iUserId)
			->execute('getRow');
			
		if (!isset($aUser['user_id']))
		{
			return false;
		}
		
		return $aUser;
	}
	public function getTimeLineYears($iUserId, $iLastTimeStamp)
	{					
		$aNewYears = array();
		$sKey = 'timelineyears';
		$sType = 'timelineyears';
		$oCustomCache = Phpfox::getService('advancedphoto.cache');
		if (!($aNewYears = $oCustomCache->get($sKey, $sType)))
		{
			$aYears = range(date('Y', PHPFOX_TIME), date('Y', $iLastTimeStamp));		
			foreach ($aYears as $iYear)
			{
				$iStartYear = mktime(0, 0, 0, 1, 1, $iYear);
				$iEndYear = mktime(23, 59, 59, 12, 31, $iYear);

				$iCnt = $this->database()->select('COUNT(*)')
					->from($this->_sTable)
					->where('user_id = ' . (int) $iUserId .' AND time_stamp > \'' . $iStartYear . '\' AND time_stamp <= \'' . $iEndYear . '\'')
					->execute('getSlaveField');

				if ($iCnt)
				{
					$aNewYears[] = $iYear;
				}
			}
			
			//type to classify cache item to customize caching time
			//$oCustomCache->set($sKey, $aNewYears);
		}

		if (!is_array($aNewYears))
		{
			$aNewYears = array();
		}
		
		return $aNewYears;
	}
	
	public function getCoverPhoto($iPhotoId)
	{
		$aRow = $this->database()->select('*')
			->from(Phpfox::getT('photo'))
			->where('photo_id = ' . (int) $iPhotoId)
			->execute('getSlaveRow');
		
		return $aRow;
	}
	
	public function isTagSearch($bIsTagSearch = false)
	{
		$this->_bIsTagSearch = $bIsTagSearch;		
		
		return $this;
	}		

	/**
	 * Get all photos based on filters we passed via the params.
	 *
	 * @param array $mConditions SQL Conditions
	 * @param string $sOrder SQL Ordering
	 * @param int $iPage Current page we are on
	 * @param int $iPageSize Define how many photos we can display at one time
	 * 
	 * @return array Return an array of the total photo count and the photos
	 */
	public function get($mConditions = array(), $sOrder = 'p.time_stamp DESC', $iPage = '', $iPageSize = '', $aCallback = null)
	{		
		$aPhotos = array();
		// $sOrder = 'p.is_sponsor DESC' . (!empty($sOrder) ? ', ' .$sOrder : ''); no need since we're making a block
		if ($this->_bIsTagSearch !== false)
		{
			$this->database()->innerJoin(Phpfox::getT('tag'), 'tag', "tag.item_id = p.photo_id AND (tag.category_id = 'advancedphoto' OR tag.category_id = 'photo')");
		}		
		
		$iCnt = $this->database()->select('COUNT(*)')
			->from(Phpfox::getT('photo'), 'p')
			->where($mConditions)
			->execute('getSlaveField');
		
		if ($iCnt)
		{
			if ($this->_bIsTagSearch !== false)
			{
				$this->database()->innerJoin(Phpfox::getT('tag'), 'tag', "tag.item_id = p.photo_id AND (tag.category_id = 'advancedphoto' OR tag.category_id = 'photo')");
			}			
			
			$aPhotos = $this->database()->select(Phpfox::getUserField() . ', p.*, pa.name AS album_url, pi.*')
				->from(Phpfox::getT('photo'), 'p')
				->leftJoin(Phpfox::getT('photo_info'), 'pi', 'pi.photo_id = p.photo_id')
				->join(Phpfox::getT('user'), 'u', 'u.user_id = p.user_id')
				->leftJoin(Phpfox::getT('photo_album'), 'pa', 'pa.album_id = p.album_id')
				->where($mConditions)
				->order($sOrder)
				->limit($iPage, $iPageSize, $iCnt)
				->execute('getSlaveRows');
				
			$oUrl = Phpfox::getLib('url');
			foreach ($aPhotos as $iKey => $aPhoto)
			{				
				$sCategoryList = '';
				$aCategories = (array) $this->database()->select('category_id')
					->from(Phpfox::getT('photo_category_data'))
					->where('photo_id = ' . (int) $aPhoto['photo_id'])
					->execute('getSlaveRows');
					
				foreach ($aCategories as $aCategory)
				{
					$sCategoryList .= $aCategory['category_id'] . ',';
				}
				
				$aPhotos[$iKey]['link'] = Phpfox::permalink('advancedphoto', $aPhoto['photo_id'], $aPhoto['title']);
				$aPhotos[$iKey]['category_list'] = rtrim($sCategoryList, ',');
				$aPhotos[$iKey]['destination'] = $this->getPhotoUrl($aPhoto);
				//clone new image for slider...
			}
		}		
		
		return array($iCnt, $aPhotos);
	}
	
	public function getForEdit($iId)
	{
		$aPhoto = $this->database()->select('p.*, pi.*, pa.name AS album_url, pa.name AS album_title, ' . Phpfox::getUserField())
			->from($this->_sTable, 'p')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = p.user_id')
			->leftJoin(Phpfox::getT('photo_info'), 'pi', 'pi.photo_id = p.photo_id')
			->leftJoin(Phpfox::getT('photo_album'), 'pa', 'pa.album_id = p.album_id')			
			->where('p.photo_id = ' . (int) $iId)
			->execute('getSlaveRow');		
			
		$aPhoto['categories'] = Phpfox::getService('advancedphoto.category')->getCategoriesById($aPhoto['photo_id']);		
		// $aPhoto['category_list'] = Phpfox::getService('advancedphoto.category')->getCategoryIds($aPhoto['photo_id']);		
		
		$aPhoto['ynadvphoto_time'] = Phpfox::getLib('date')->convertFromGmt($aPhoto['time_stamp'], 0);
			
		$aPhoto['ynadvphoto_month'] = date('n', $aPhoto['ynadvphoto_time']);
		$aPhoto['ynadvphoto_day'] = date('j', $aPhoto['ynadvphoto_time']);
		$aPhoto['ynadvphoto_year'] = date('Y', $aPhoto['ynadvphoto_time']);
		$aPhoto['ynadvphoto_hour'] = date('H', $aPhoto['ynadvphoto_time']);
		$aPhoto['ynadvphoto_minute'] = date('i', $aPhoto['ynadvphoto_time']);

		if (Phpfox::isModule('tag'))
		{
			$aTags = Phpfox::getService('tag')->getTagsById('advancedphoto', $aPhoto['photo_id']);	
			$aTagsFromPhotoModule = Phpfox::getService('tag')->getTagsById('photo', $aPhoto['photo_id']);	
			if (isset($aTagsFromPhotoModule[$aPhoto['photo_id']]))
			{
				if (isset($aTags[$aPhoto['photo_id']]))
				{
					$aTags[$aPhoto['photo_id']] = array_merge($aTags[$aPhoto['photo_id']], $aTagsFromPhotoModule[$aPhoto['photo_id']]);
				}
				else
				{
					$aTags[$aPhoto['photo_id']] = $aTagsFromPhotoModule[$aPhoto['photo_id']]; 
				}
				
			}	
			if (isset($aTags[$aPhoto['photo_id']]))
			{
				$aPhoto['tag_list'] = '';					
				foreach ($aTags[$aPhoto['photo_id']] as $aTag)
				{
					$aPhoto['tag_list'] .= ' ' . $aTag['tag_text'] . ',';	
				}
				$aPhoto['tag_list'] = trim(trim($aPhoto['tag_list'], ','));
			}			
		}		
		
		$sCategoryList = '';
		$aCategories = (array) $this->database()->select('category_id')
			->from(Phpfox::getT('photo_category_data'))
			->where('photo_id = ' . (int) $aPhoto['photo_id'])
			->execute('getSlaveRows');
					
		foreach ($aCategories as $aCategory)
		{
			$sCategoryList .= $aCategory['category_id'] . ',';
		}
			
		$aPhoto['category_list'] = rtrim($sCategoryList, ',');			
			
		return $aPhoto;
	}
	
	public function getForProcess($iId)
	{
		return $this->database()->select('photo_id, server_id, title, album_id, group_id, destination, privacy, privacy_comment')
			->from($this->_sTable)
			->where('photo_id = ' . (int) $iId . ' AND user_id = ' . Phpfox::getUserId())
			->execute('getRow');
	}
	
	public function getApprovalPhotosCount()
	{
		return $this->database()->select('COUNT(*)')
			->from($this->_sTable)
			->where('view_id = 1')
			->execute('getSlaveField');
	}
	
	public function getPhotoByDestination($sName)
	{
		$aPhoto = $this->database()->select('p.*, pi.*, pa.name AS album_title, ' . Phpfox::getUserField()) 
			->from($this->_sTable, 'p')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = p.user_id')
			->leftJoin(Phpfox::getT('photo_info'), 'pi', 'pi.photo_id = p.photo_id')
			->leftJoin(Phpfox::getT('photo_album'), 'pa', 'pa.album_id = p.album_id')		
			->where('p.destination = \'' . $this->database()->escape($sName) . '\'')	
			->execute('getSlaveRow');		
			
		if (!isset($aPhoto['photo_id']))
		{
			return false;
		}
		
		return $aPhoto;
	}
	
	public function getPhoto($sId, $iUserId = 0, $aCallback = null)
	{
		/*
		if ($aCallback === null)
		{
			$sView = 'p.view_id = 0';
			if ($iUserId == Phpfox::getUserId() || Phpfox::getUserParam('advancedphoto.can_approve_photos'))
			{
				$sView = 'p.view_id IN(0,1)';
			}		
			$this->database()->where($sView . ' AND p.group_id = 0 AND p.title_url = \'' . $this->database()->escape($sId) . '\' AND p.user_id = ' . (int) $iUserId);
		}
		else 
		{
			$this->database()->where('p.view_id = 0 AND p.group_id = ' . $aCallback['group_id'] . ' AND p.title_url = \'' . $this->database()->escape($sId) . '\'');			
		}
		*/
		
		if (Phpfox::isModule('like'))
		{
			$this->database()->select('lik.like_id AS is_liked, ')->leftJoin(Phpfox::getT('like'), 'lik', '(lik.type_id = \'advancedphoto\' OR lik.type_id = \'photo\'  ) AND lik.item_id = p.photo_id AND lik.user_id = ' . Phpfox::getUserId());
		}	
		
		$this->database()->select('f.friend_id AS is_friend, ')->leftJoin(Phpfox::getT('friend'), 'f', "f.user_id = p.user_id AND f.friend_user_id = " . Phpfox::getUserId());		
		
		$this->database()->where('p.photo_id = ' . (int) $sId);
		
		$aPhoto = $this->database()->select('' . Phpfox::getUserField() . ', p.*, pi.*, pa.name AS album_url, pa.name AS album_title, pa.profile_id AS album_profile_id, pt.item_id AS is_viewed')
			->from($this->_sTable, 'p')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = p.user_id')
			->leftJoin(Phpfox::getT('photo_info'), 'pi', 'pi.photo_id = p.photo_id')
			->leftJoin(Phpfox::getT('photo_album'), 'pa', 'pa.album_id = p.album_id')
			->leftJoin(Phpfox::getT('photo_track'), 'pt', 'pt.item_id = p.photo_id AND pt.user_id = ' . Phpfox::getUserId())		
			->execute('getSlaveRow');
			
		if (!isset($aPhoto['photo_id']))
		{
			return false;
		}
			
		if (!Phpfox::isModule('like'))
		{
			$aPhoto['is_liked'] = false;
		}
		if (Phpfox::isModule('tag'))
		{
			$aTags = Phpfox::getService('tag')->getTagsById('advancedphoto', $aPhoto['photo_id']);	
			$aTagsFromPhotoModule = Phpfox::getService('tag')->getTagsById('photo', $aPhoto['photo_id']);	
			if (isset($aTagsFromPhotoModule[$aPhoto['photo_id']]))
			{
				if (isset($aTags[$aPhoto['photo_id']]))
				{
					$aTags[$aPhoto['photo_id']] = array_merge($aTags[$aPhoto['photo_id']], $aTagsFromPhotoModule[$aPhoto['photo_id']]);
				}
				else
				{
					$aTags[$aPhoto['photo_id']] = $aTagsFromPhotoModule[$aPhoto['photo_id']]; 
				}
				
			}	
			if (isset($aTags[$aPhoto['photo_id']]))
			{
				$aPhoto['tag_list'] = $aTags[$aPhoto['photo_id']];
			}
		}
		
		$aPhoto['categories'] = Phpfox::getService('advancedphoto.category')->getCategoriesById($aPhoto['photo_id']);
		$aPhoto['category_list'] = Phpfox::getService('advancedphoto.category')->getCategoryIds($aPhoto['photo_id']);
		
		if (empty($aPhoto['album_id']))
		{
			$aPhoto['album_url'] = 'view';
		}
		
		$aPhoto['original_destination'] = $aPhoto['destination'];
		$aPhoto['destination'] = $this->getPhotoUrl($aPhoto);
		
		if ($aPhoto['album_id'] > 0)
		{
			if ($aPhoto['album_profile_id'] > 0)
			{
				$aPhoto['album_title'] = Phpfox::getPhrase('advancedphoto.profile_pictures');
				$aPhoto['album_url'] = Phpfox::permalink('advancedphoto.album.profile', $aPhoto['user_id'], $aPhoto['user_name']);
			}
			else
			{
				$aPhoto['album_url'] = Phpfox::permalink('advancedphoto.album', $aPhoto['album_id'], $aPhoto['album_title']);
			}
		}

		return $aPhoto;
	}
 
	/**
	 * We get and return the latest images we uploaded. The reason we run
	 * this check is so we only return images that belong to the user that is loggeed in
	 * and not someone elses images.
	 *
	 * @param int $iUserId User ID of the user the images belong to.
	 * @param array $aIds Array of photo IDS
	 * 
	 * @return array Array of user images.
	 */
	public function getNewImages($iUserId, $aIds)
	{
		// We run an INT check just in case someone is trying to be funny.
		$sIds = '';
		foreach ($aIds as $iKey => $sId)
		{
			if (!is_numeric($sId))
			{
				continue;
			}
			$sIds .= $sId . ',';
		}
		$sIds = rtrim($sIds, ',');		
		
		// Lets the new images and return them.
		return $this->database()->select('p.photo_id, p.album_id, p.destination, p.server_id, p.view_id, pa.privacy, p.title')
					->from($this->_sTable, 'p')
					->leftJoin(Phpfox::getT('photo_album'), 'pa', 'pa.album_id = p.album_id')
					->where('p.photo_id IN(' . $sIds . ') AND p.user_id = ' . (int) $iUserId)
					->order('p.photo_id DESC')
					->execute('getRows');		
	}
	
	public function getRandomSponsored()
	{
	    $sCacheId = $this->cache()->set('photo_sponsored');
	    if (!($aPhotos = $this->cache()->get($sCacheId)))
		{
			$aPhotos = $this->database()->select('s.*, pi.width, pi.height, u.user_name, p.total_view, p.time_stamp, pi.file_size, p.photo_id, p.destination, p.server_id, p.title, p.album_id, p.total_view')
				->from($this->_sTable, 'p')
				->join(Phpfox::getT('user'), 'u', 'u.user_id = p.user_id')
				->join(Phpfox::getT('photo_info'), 'pi', 'pi.photo_id = p.photo_id')
				->join(Phpfox::getT('ad_sponsor'),'s','s.item_id = p.photo_id')
				->leftJoin(Phpfox::getT('photo_album'), 'pa', 'pa.album_id = p.album_id')
				->where('p.view_id = 0 AND p.group_id = 0 AND p.type_id < 2 AND p.privacy = 0 AND p.is_sponsor = 1 AND s.module_id = "photo"')
				->execute('getRows');
			
			$this->cache()->save($sCacheId, $aPhotos);
		}

		if (Phpfox::isModule('ad'))
		{
			$aPhotos = Phpfox::getService('ad')->filterSponsor($aPhotos);
		}
		
		if ($aPhotos === true || (is_array($aPhotos) && !count($aPhotos)))
		{
			return false;
		}

		// Randomize to get a photo		
		return $aPhotos[mt_rand(0, (count($aPhotos) - 1))];
	}
	
	public function getNew($iLimit = 3)
	{
		$aPhotos = $this->database()->select('p.destination, p.server_id, p.title, p.photo_id, p.mature, p.album_id, pa.name_url AS album_url, ' . Phpfox::getUserField())
			->from($this->_sTable, 'p')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = p.user_id')
			->leftJoin(Phpfox::getT('photo_album'), 'pa', 'pa.album_id = p.album_id')
			->where('p.view_id = 0 AND p.group_id = 0 AND p.type_id < 2 AND p.privacy = 0')
			->order('p.time_stamp DESC')	
			->limit($iLimit)
			->execute('getSlaveRows');
			
		$oUrl = Phpfox::getLib('url');
		foreach ($aPhotos as $iKey => $aPhoto)
		{				
			$aPhotos[$iKey]['link'] = Phpfox::permalink('advancedphoto', $aPhoto['photo_id'], $aPhoto['title']);
		}		
		
		return $aPhotos;	
	}
	
	/* This function is used in the converting controller to get the image that needs to have its thumbnails created.*/
	public function getForConverting($iUserId, $iLimit = 1)
	{
		$aPhoto = $this->database()->select('p.photo_id, p.destination, p.server_id, p.title, p.mature, p.album_id, ' . Phpfox::getUserField())
			->from($this->_sTable, 'p')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = p.user_id')
			->leftJoin(Phpfox::getT('photo_album'), 'pa', 'pa.album_id = p.album_id')
			->where('p.view_id = 0 AND p.user_id = ' . (int)$iUserId)
			->order('p.time_stamp DESC')	
			->limit($iLimit)
			->execute('getSlaveRows');
		return $aPhoto;
	}
	
	public function getForProfile($iUserId, $iLimit = 3)
	{
		$aPhotos = $this->database()->select(Phpfox::getUserField() . ', p.destination, p.server_id, p.title, p.mature, p.album_id, pa.name AS album_name, pa.name_url AS album_url')
			->from($this->_sTable, 'p')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = p.user_id')
			->leftJoin(Phpfox::getT('photo_album'), 'pa', 'pa.album_id = p.album_id')
			->where('p.view_id = 0 AND p.group_id = 0 AND p.type_id < 2 AND p.privacy = 0 AND p.user_id = ' . (int) $iUserId)
			->order('p.time_stamp DESC')	
			->limit($iLimit)
			->execute('getSlaveRows');
			
		$oUrl = Phpfox::getLib('url');
		foreach ($aPhotos as $iKey => $aPhoto)
		{				
			$aPhotos[$iKey]['link'] = Phpfox::permalink('advancedphoto', $aPhoto['photo_id'], $aPhoto['title']);
		}		
		
		return $aPhotos;	
	}	
	
	public function getForGroup($iGroupId, $sGroupUrl)
	{
		$aPhotos = $this->database()->select('p.destination, p.server_id, p.title, p.mature, p.album_id, pa.name_url AS album_url, ' . Phpfox::getUserField())
			->from($this->_sTable, 'p')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = p.user_id')
			->leftJoin(Phpfox::getT('photo_album'), 'pa', 'pa.album_id = p.album_id')
			->where('p.view_id = 0 AND p.group_id = ' . $iGroupId . ' AND p.privacy = 0')
			->order('p.time_stamp DESC')	
			->limit(3)
			->execute('getSlaveRows');
			
		$oUrl = Phpfox::getLib('url');
		foreach ($aPhotos as $iKey => $aPhoto)
		{				
			$aPhotos[$iKey]['link'] = Phpfox::permalink('advancedphoto', $aPhoto['photo_id'], $aPhoto['title']);
		}		
		
		return $aPhotos;	
	}	
	
	/**
	 * Return the featured time stamp in milliseconds
	 *
	 * @return int Time stamp in milliseconds
	 */
	public function getFeaturedRefreshTime()
	{
		// Get the refresh setting
		$sTime = Phpfox::getUserParam('advancedphoto.refresh_featured_photo');
		
		// Match the minutes or seconds
		preg_match("/(.*?)(min|sec)$/i", $sTime, $aMatches);
		
		// Make sure we have a match
		if (isset($aMatches[1]) && isset($aMatches[2]))
		{
			// Trim the matched time stamp
			$aMatches[2] = trim($aMatches[2]);
			
			// If we want to work with minutes
			if ($aMatches[2] == 'min')
			{
				// Convert to milliseconds
				return (int) ($aMatches[1] * 60000);
			}
			// If we want to work with seconds
			elseif ($aMatches[2] == 'sec')
			{
				// Convert to milliseconds
				return (int) ($aMatches[1] * 1000);	
			}
		}
		
		// Return the default value (60 seconds)
		return 60000;
	}
	
	/**
	 * Get the next photo based on the current photo and album we are viewing.
	 *
	 * @param int $iAlbumId ID of the album
	 * @param int $iPhotoId ID of the current photo we are viewing
	 * 
	 * @return array Array of the next photo
	 */
	public function getPreviousPhotos($iPhotoId, $sType = null, $iItemId = null, $aCallback = null, $iUserId = 0)
	{		
		(($sPlugin = Phpfox_Plugin::get('advancedphoto.service_album_album_getnextphoto')) ? eval($sPlugin) : false);
		
		$sView = 'p.view_id = 0';
		if ($iUserId == Phpfox::getUserId() || Phpfox::getUserParam('advancedphoto.can_approve_photos'))
		{
			$sView = 'p.view_id IN(0,1)';
		}		
		
		$aCond = array();
		if ($sType !== null)
		{
			if ($sType == 'album')
			{
				$aCond[] = 'p.photo_id > ' . (int) $iPhotoId . ' AND p.album_id = ' . (int) $iItemId . ' AND p.group_id = 0 AND ' . $sView;
			}
			elseif ($sType == 'group')
			{
				$aCond[] = 'p.photo_id > ' . (int) $iPhotoId . ' AND p.group_id = ' . (int) $iItemId . ' AND ' . $sView;
			}
		}	
		
		$aPhoto = $this->database()->select(Phpfox::getUserField() . ', p.photo_id,  p.destination, p.server_id, p.title, p.mature, p.album_id, pa.name_url AS album_url')
			->from($this->_sTable, 'p')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = p.user_id')
			->leftJoin(Phpfox::getT('photo_album'), 'pa', 'pa.album_id = p.album_id')
			->where($aCond)
			->order('p.photo_id ASC')
			->execute('getSlaveRow');	
			
		if (!isset($aPhoto['photo_id']))
		{
			return false;
		}
			
		$aPhoto['link'] = Phpfox::permalink('advancedphoto', $aPhoto['photo_id'], $aPhoto['title']);
			
		return $aPhoto;
	}
	
	/**
	 * Get the previous photo based on the current photo and album we are viewing.
	 *
	 * @param int $iAlbumId ID of the album
	 * @param int $iPhotoId ID of the current photo we are viewing
	 * 
	 * @return array Array of the previous photo
	 */	
	public function getNextPhotos($iPhotoId, $sType = null, $iItemId = null, $aCallback = null, $iUserId = 0)
	{		
		(($sPlugin = Phpfox_Plugin::get('advancedphoto.service_album_album_getpreviousphoto')) ? eval($sPlugin) : false);
		
		$sView = 'p.view_id = 0';
		if ($iUserId == Phpfox::getUserId() || Phpfox::getUserParam('advancedphoto.can_approve_photos'))
		{
			$sView = 'p.view_id IN(0,1)';
		}				
		
		$aCond = array();
		if ($sType !== null)
		{
			if ($sType == 'album')
			{
				$aCond[] = 'p.photo_id < ' . (int) $iPhotoId . ' AND p.album_id = ' . (int) $iItemId . ' AND p.group_id = 0 AND ' . $sView;
			}
			elseif ($sType == 'group')
			{
				$aCond[] = 'p.photo_id < ' . (int) $iPhotoId . ' AND p.group_id = ' . (int) $iItemId . ' AND ' . $sView;
			}			
		}
		
		$aPhoto = $this->database()->select(Phpfox::getUserField() . ', p.photo_id, p.destination, p.server_id, p.title, p.mature, p.album_id, pa.name_url AS album_url')
			->from($this->_sTable, 'p')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = p.user_id')
			->leftJoin(Phpfox::getT('photo_album'), 'pa', 'pa.album_id = p.album_id')
			->where($aCond)
			->order('p.photo_id DESC')
			->execute('getRow');	
			
		if (!isset($aPhoto['photo_id']))
		{
			return false;
		}			
			
		$aPhoto['link'] = Phpfox::permalink('advancedphoto', $aPhoto['photo_id'], $aPhoto['title']);
			
		return $aPhoto;
	}	
	
	public function getPhotoStream($iPhotoId, $iAlbumId = 0, $aCallback = null, $iUserId = 0, $iCategory = null)
	{		
		$sQuery = '';
		if (isset($aCallback['module_id']))
		{
			$sQuery = ' AND photo.module_id = \'' . $this->database()->escape($aCallback['module_id']) . '\' AND photo.group_id = ' . (int) $aCallback['item_id'];
		}
		else
		{
			$sQuery = ' AND photo.group_id = 0 AND photo.type_id < 2 ';
		}
		
		if ($iAlbumId > 0)
		{
			$sQuery = ' AND photo.album_id = ' . (int) $iAlbumId;	
		}
		if ($iUserId > 0)
		{
			$sQuery .= ' AND photo.user_id = ' . (int)$iUserId;
		}
		
		// Check permissions
		if (!Phpfox::isAdmin())
		{
			/*
				4 => Custom
				3 => Only Me
				2 => Friends of Friends
				1 => Friends	
				0 => Everyone
			*/
			$sQuery .= (empty($sQuery) ? '' : ' AND ') . '(';
			$sQuery .= '(photo.privacy = 0)';
			if (Phpfox::getParam('core.section_privacy_item_browsing'))
			{	
				$sQuery .= ' OR ';
				// 3 - "Only me" privacy
				$sQuery .= ' (photo.privacy = 3 AND photo.user_id = ' . Phpfox::getUserId() . ') '; 				
				
				list($iCnt, $aFriends) = Phpfox::getService('friend')->get(array(), '', '', false);
				if ($iCnt > 0)
				{
					// 1 - Friends
					$sFriendsIn = '(';
					foreach ($aFriends as $aFriend)
					{
						$sFriendsIn .= $aFriend['friend_user_id'] .',';
					}
					$sFriendsIn = rtrim($sFriendsIn, ',') .')';
					
					$sQuery .= ' OR (photo.privacy = 1 AND photo.user_id IN ' . $sFriendsIn .')';
					
					// 2 - Friends of Frends
					$aFriendsOfFriends = Phpfox::getService('friend')->getFriendsOfFriends($sFriendsIn);
					$sIn = implode(',', $aFriendsOfFriends);
					$sQuery .= ' OR (photo.privacy = 2 AND photo.user_id IN (' . $sIn . '))';
					
					$aInList = Phpfox::getService('friend.list')->getUsersInAnyList();					
					if (!empty($aInList))
					{
						$sIn = implode(',', $aInList);
						$sQuery .= ' OR (photo.privacy = 4 AND photo.user_id IN (' . $sIn . '))';
					}
					
				}				
			}
			else
			{
				$sQuery .= ' AND photo.privacy = 0';
			}
			$sQuery .= ')';			
		}		

		$aPhoto = $this->database()->select('*')
				->from($this->_sTable)
				->where('photo_id = ' . $iPhotoId)
				->execute('getRow');

		if($iAlbumId != 0)
		{
			if(isset($aPhoto['yn_ordering']) && $aPhoto['yn_ordering'] != 0 )
			{
				list($iPreviousCnt, $aPrevious) = $this->_getPhoto('AND photo.yn_ordering > ' . (int) $aPhoto['yn_ordering'] . $sQuery, 'photo.yn_ordering ASC', (empty($sQuery) ? false : true), $iCategory);
				list($iNextCount, $aNext) = $this->_getPhoto('AND photo.yn_ordering < ' . (int) $aPhoto['yn_ordering'] . $sQuery, 'photo.yn_ordering DESC', (empty($sQuery) ? false : true), $iCategory);		
			}
			else
			{
				list($iPreviousCnt, $aPrevious) = $this->_getPhoto('AND photo.photo_id < ' . (int) $iPhotoId . $sQuery, 'DESC', (empty($sQuery) ? false : true), $iCategory);
				list($iNextCount, $aNext) = $this->_getPhoto('AND photo.photo_id > ' . (int) $iPhotoId . $sQuery, 'ASC', (empty($sQuery) ? false : true), $iCategory);				

			}
		}
		else
		{
			if(isset($_SERVER['HTTP_REFERER']))
			{
				$sOriginUrl = $_SERVER['HTTP_REFERER'];
				if(strstr($sOriginUrl, 'view_featured'))
				{
					$sQuery .= 'AND photo.is_featured = 1';
				}
			}
			list($iPreviousCnt, $aPrevious) = $this->_getPhoto('AND photo.photo_id > ' . (int) $iPhotoId . $sQuery, 'ASC', (empty($sQuery) ? false : true), $iCategory);
			list($iNextCount, $aNext) = $this->_getPhoto('AND photo.photo_id < ' . (int) $iPhotoId . $sQuery, 'DESC', (empty($sQuery) ? false : true), $iCategory);				
		}

		return array(
			'total' => ($iNextCount + $iPreviousCnt + 1),
			'current' => ($iPreviousCnt + 1),			
			'previous' => $aPrevious,
			'next' => $aNext
		);
	}
		
	public function getPendingTotal()
	{
		return (int) $this->database()->select('COUNT(*)')
			->from($this->_sTable)
			->where('view_id = 1')
			->execute('getSlaveField');		
	}

	public function getPhotoUrl($aPhoto)
	{
		$sUrl = $aPhoto['destination'];
		if (Phpfox::getParam('advancedphoto.rename_uploaded_photo_names'))
		{			
			if (preg_match('/(.*)\/(.*)\%s\.(.*)/i', $aPhoto['destination'], $aMatches) && isset($aMatches[2]) && (int) strlen($aMatches[2]) == 32)
			{
				$sUrl = '[PHPFOX_CUSTOM_URL]' . $aMatches[1] . '/' . $aMatches[2] . '-' . Phpfox::getLib('parse.input')->cleanFileName($aPhoto['title']) . '%s.' . $aMatches[3];
			}
		}
		
		return $sUrl;
	}
	
	public function getFeatured($iLimit = 4, $bNoStaticCache = false)
	{
		static $aFeatured = null;
		static $iTotal = null;
		
		if ($aFeatured !== null )
		{
			if(!$bNoStaticCache)
			{
				return array($iTotal, $aFeatured);
			}
		}
		
		$oCustomCache = Phpfox::getService('advancedphoto.cache');
		$sKey = 'featured_photos_' . $iLimit;
		$sType = 'featured_photos';
		$aFeatured = array();
		if (!($aRows = $oCustomCache->get($sKey, $sType)))
		{
			$aRows = $this->database()->select('v.*, ' . Phpfox::getUserField())
				->from(Phpfox::getT('photo'), 'v')
				->join(Phpfox::getT('user'), 'u', 'u.user_id = v.user_id')
				->where('v.is_featured = 1 AND v.group_id = 0 AND v.type_id < 2 AND v.view_id = 0')
				->order('time_stamp DESC')
				->execute('getSlaveRows');
			
			$oCustomCache->set($sKey, $aRows);
		}
		
		$iTotal = 0;
		if (is_array($aRows) && count($aRows))
		{
			$iTotal = count($aRows);
			shuffle($aRows);
			foreach ($aRows as $iKey => $aRow)
			{
				$aRow['link'] = Phpfox::permalink('advancedphoto', $aRow['photo_id'], $aRow['title']);
				if ($iKey === $iLimit)
				{
					break;
				}
				
				$aFeatured[] = $aRow;
			}
		}
		
		return array($iTotal, $aFeatured);
	}	
	
	public function buildMenu()
	{
		$aFilterMenu = array();
		if (!defined('PHPFOX_IS_USER_PROFILE'))
		{
			if (Phpfox::getParam('core.friends_only_community') ||  !Phpfox::isModule('friend'))
			{				
				$aFilterMenu = array(
					Phpfox::getPhrase('advancedphoto.all_photos') => '',
					Phpfox::getPhrase('advancedphoto.my_photos') => 'my'							
				);			
			}
			else 
			{
				$aFilterMenu = array(
					Phpfox::getPhrase('advancedphoto.all_photos') => '',
					Phpfox::getPhrase('advancedphoto.my_photos') => 'my',
					Phpfox::getPhrase('advancedphoto.friends_photos') => 'friend'				
				);					
			}				
			
			list($iTotalFeatured, $aFeatured) = Phpfox::getService('advancedphoto')->getFeatured();
			if ($iTotalFeatured)
			{
				$aFilterMenu[Phpfox::getPhrase('advancedphoto.featured_photos') . '<span class="pending">' . $iTotalFeatured . '</span>'] = 'featured';
			}			
				
			if (Phpfox::getUserParam('advancedphoto.can_approve_photos'))
			{
				$iPendingTotal = Phpfox::getService('advancedphoto')->getPendingTotal();
				
				if ($iPendingTotal)
				{
					$aFilterMenu[Phpfox::getPhrase('advancedphoto.pending_photos') . (Phpfox::getUserParam('advancedphoto.can_approve_photos') ? '<span class="pending">' . $iPendingTotal . '</span>' : 0)] = 'pending';
				}
			}
			
			if (Phpfox::getUserParam('advancedphoto.can_view_photo_albums'))
			{
				$aFilterMenu[] = true;
				$aFilterMenu[Phpfox::getPhrase('advancedphoto.all_albums')] = 'advancedphoto.albums';
				$aFilterMenu[Phpfox::getPhrase('advancedphoto.my_albums')] = 'advancedphoto.albums.view_myalbums';
				if (!Phpfox::getParam('core.friends_only_community') ||  !Phpfox::isModule('friend'))
				{				
					$aFilterMenu[Phpfox::getPhrase('advancedphoto.friends_album')] = 'advancedphoto.albums.view_friend';
				}
			}
			
			if (Phpfox::getParam('advancedphoto.can_rate_on_photos') || Phpfox::getParam('advancedphoto.enable_photo_battle'))
			{
				$aFilterMenu[] = true;
			}
			
			if (Phpfox::getParam('advancedphoto.can_rate_on_photos'))
			{
				$aFilterMenu[Phpfox::getPhrase('advancedphoto.rate_photos')] = 'advancedphoto.rate';	
			}
			
			if (Phpfox::getParam('advancedphoto.enable_photo_battle'))
			{
				$aFilterMenu[Phpfox::getPhrase('advancedphoto.photo_battles')] = 'advancedphoto.battle';
			}
		}		
		
		Phpfox::getLib('template')->buildSectionMenu('advancedphoto', $aFilterMenu);			
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
		if ($sPlugin = Phpfox_Plugin::get('advancedphoto.service_photo__call'))
		{
			return eval($sPlugin);
		}
			
		/**
		 * No method or plug-in found we must throw a error.
		 */
		Phpfox_Error::trigger('Call to undefined method ' . __CLASS__ . '::' . $sMethod . '()', E_USER_ERROR);
	}	
	
	private function _getPhoto($sCondition, $sOrder, $bNoPrivacy = false, $iCategory = null)
	{		
		if ($bNoPrivacy === true)
		{
			$iCategoryChecked = null;
			if ($iCategory !== null)
			{
				$iCategoryChecked = (int)$iCategory;
			}
			else if (Phpfox::getCookie('photo_category'))
			{
				$iCategoryChecked = Phpfox::getCookie('photo_category');
			}
			else if ( (isset($_SESSION['photo_category']) && $_SESSION['photo_category'] != '') )
			{
				$iCategoryChecked = $_SESSION['photo_category'];
			}
			
			if ( $iCategoryChecked !== null )
			{
				$this->database()->join(Phpfox::getT('photo_category_data'), 'pcd', 'pcd.photo_id = photo.photo_id AND pcd.category_id = ' . ((int)$iCategoryChecked));
			}
			$iPreviousCnt = $this->database()->select('COUNT(*)')
				->from(Phpfox::getT('photo'), 'photo')
				->where(array($sCondition))
				->execute('getSlaveField');

			if ($iCategoryChecked !== null)
			{				
				$this->database()->select('pcd.category_id,')->join(Phpfox::getT('photo_category_data'), 'pcd', 'pcd.photo_id = photo.photo_id AND pcd.category_id = ' . (int)$iCategoryChecked);
			}
			if(strstr($sOrder, 'photo.yn_ordering'))
			{
				$aPrevious = (array) $this->database()->select('photo.*')
					->from(Phpfox::getT('photo'), 'photo')
					->where(array($sCondition))
					->order($sOrder)
					->execute('getSlaveRow');
			}
			else
			{
				$aPrevious = (array) $this->database()->select('photo.*')
					->from(Phpfox::getT('photo'), 'photo')
					->where(array($sCondition))
					->order('photo.photo_id ' . $sOrder)
					->execute('getSlaveRow');
			}

			if (!empty($aPrevious['photo_id']))
			{
				$aPrevious['link'] = Phpfox::getLib('url')->permalink('advancedphoto', $aPrevious['photo_id'], $aPrevious['title']) . ($iCategoryChecked !== null ? 'category_' . $iCategoryChecked : '');			
			}
							
			return array($iPreviousCnt, $aPrevious);	
		}
		
		$aBrowseParams = array(
			'module_id' => 'photo',
			'alias' => 'photo',
			'field' => 'photo_id',
			'table' => Phpfox::getT('photo'),
			'hide_view' => array('pending', 'my')
		);				
		
		$this->search()->set(array(
				'type' => 'photo',
				'filters' => array(
					'display' => array('type' => 'option', 'default' => '1'),
					'sort' => array('type' => 'option', 'default' => 'photo_id'),
					'sort_by' => array('type' => 'option', 'default' => $sOrder)
				)
			)
		);

		$this->search()->setCondition($sCondition);	
		$this->search()->setCondition('AND photo.view_id = 0 AND photo.group_id = 0 AND photo.type_id < 2 AND photo.privacy IN(%PRIVACY%)');

		$this->search()->browse()->params($aBrowseParams)->execute();
		$iPreviousCnt = $this->search()->browse()->getCount();
		$aPreviousRows = $this->search()->browse()->getRows();	
		
		$this->search()->browse()->reset();
		
		$aPrevious = array();
		if (isset($aPreviousRows[0]))
		{
			$aPrevious = $aPreviousRows[0];	
		}		
		
		return array($iPreviousCnt, $aPrevious);
	}
}

?>