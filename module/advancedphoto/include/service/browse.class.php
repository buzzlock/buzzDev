<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond_Benc
 * @package 		Phpfox_Service
 * @version 		$Id: browse.class.php 4538 2012-07-19 10:38:00Z Miguel_Espinoza $
 */
class Advancedphoto_Service_Browse extends Phpfox_Service 
{
	private $_sCategory = null;	
	
	/**
	 * Class constructor
	 */	
	public function __construct()
	{	
		
	}
	
	public function category($sCategory)
	{
		$this->_sCategory = $sCategory;
		
		return $this;
	}
	
	public function query()
	{
		$this->database()->select('pa.name AS album_name, pa.profile_id AS album_profile_id, ppc.name as category_name, ppc.category_id, ')
			->leftJoin(Phpfox::getT('photo_album'), 'pa', 'pa.album_id = photo.album_id')
			->leftJoin(Phpfox::getT('photo_category_data'), 'ppcd', 'ppcd.photo_id = photo.photo_id')
			->leftJoin(Phpfox::getT('photo_category'), 'ppc', 'ppc.category_id = ppcd.category_id')
			->group('photo.photo_id');
			
		
		if (Phpfox::getLib('request')->get('mode') == 'edit')
		{
			$this->database()->select('pi.description, ')->leftJoin(Phpfox::getT('photo_info'), 'pi', 'pi.photo_id = photo.photo_id');
		}
	}	
	
	public function processRows(&$aRows)
	{
		$oReq = Phpfox::getLib('request');
		foreach ($aRows as $iKey => $aRow)
		{				
			$aRows[$iKey]['link'] = Phpfox::permalink('advancedphoto', $aRow['photo_id'], $aRow['title']);
			if ((Phpfox::getUserId() && defined('PHPFOX_IS_USER_PROFILE'))
					|| $oReq->get('req1') == 'advancedphoto' && $oReq->get('view') == 'my'
				)
			{
				$aRows[$iKey]['link'] .= 'userid_' . $aRow['user_id'] . '/';
			}
			$aRows[$iKey]['destination'] = Phpfox::getService('advancedphoto')->getPhotoUrl($aRow);
			
			if (Phpfox::getLib('request')->get('mode') == 'edit')
			{
				$sCategoryList = '';
				$aCategories = (array) $this->database()->select('category_id')
					->from(Phpfox::getT('photo_category_data'))
					->where('photo_id = ' . (int) $aRow['photo_id'])
					->execute('getSlaveRows');
					
				foreach ($aCategories as $aCategory)
				{
					$sCategoryList .= $aCategory['category_id'] . ',';
				}
				
				$aRows[$iKey]['category_list'] = rtrim($sCategoryList, ',');			
			}
		}
	}	
	
	public function getQueryJoins($bIsCount = false, $bNoQueryFriend = false)
	{
		if (Phpfox::isModule('friend') && Phpfox::getService('friend')->queryJoin($bNoQueryFriend))
		{
			$this->database()->join(Phpfox::getT('friend'), 'friends', 'friends.user_id = photo.user_id AND friends.friend_user_id = ' . Phpfox::getUserId());	
		}
		
		if ($this->request()->get('req2') == 'tag')
		{
			$this->database()->innerJoin(Phpfox::getT('tag'), 'tag', 'tag.item_id = photo.photo_id AND (tag.category_id = \'photo\' OR tag.category_id = \'advancedphoto\')');
		}		

		if ($this->_sCategory !== null || (isset($_SESSION['photo_category']) && $_SESSION['photo_category'] != ''))
		{		
			$this->database()->innerJoin(Phpfox::getT('photo_category_data'), 'pcd', 'pcd.photo_id = photo.photo_id');
			if (!$bIsCount)
			{
				$this->database()->group('photo.photo_id');
			}
		}	
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
		if ($sPlugin = Phpfox_Plugin::get('advancedphoto.service_browse__call'))
		{
			eval($sPlugin);
			return;
		}
			
		/**
		 * No method or plug-in found we must throw a error.
		 */
		Phpfox_Error::trigger('Call to undefined method ' . __CLASS__ . '::' . $sMethod . '()', E_USER_ERROR);
	}	
}

?>