	<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class Contest_Service_Category_Category extends Phpfox_Service 
{

	private $_sDisplay = 'select';

	private $_iCnt = 0;


    /**
     * Class constructor
     */
   public function __construct()
	{	
		$this->_sTable = Phpfox::getT('contest_category');
	}


	public function getCategoryIds($iId)
	{
		$aCategories = $this->database()->select('category_id')
			->from(Phpfox::getT('contest_category_data'))
			->where('contest_id = ' . (int) $iId)
			->execute('getSlaveRows');
			
		$aCache = array();
		foreach ($aCategories as $aCategory)
		{
			$aCache[] = $aCategory['category_id'];
		}
		
		return implode(',', $aCache);
	}


	public function getForEdit($iId)
	{
		return $this->database()->select('*')
			->from($this->_sTable)
			->where('category_id = ' . (int) $iId)
			->execute('getRow');
	}

	public function display($sDisplay)
	{
		$this->_sDisplay = $sDisplay;
		
		return $this;
	}

	public function get()
	{
		$sCacheId = $this->cache()->set('contest_category_display_' . $this->_sDisplay . '_' . Phpfox::getLib('locale')->getLangId());
		
		if ($this->_sDisplay == 'admincp')
		{
			if (!($sOutput = $this->cache()->get($sCacheId)))
			{				
				$sOutput = $this->_get(0, 1);
				
				$this->cache()->save($sCacheId, $sOutput);
			}
			
			return $sOutput;
		}
		else 
		{
			if (!($this->_sOutput = $this->cache()->get($sCacheId)))
			{				
				$this->_get(0, 1);
				
				$this->cache()->save($sCacheId, $this->_sOutput);
			}
			
			return $this->_sOutput;
		}		
	}

	private function _get($iParentId, $iActive = null)
	{
	  
		$aCategories = $this->database()->select('*')
			->from($this->_sTable)
			->where('parent_id = 0' . (int) $iParentId . ' AND is_active = ' . (int) $iActive . '')
			->order('ordering ASC')
			->execute('getRows');
			
		if (count($aCategories))
		{
			$aCache = array();
			
			if ($iParentId != 0)
			{
				$this->_iCnt++;	
			}
			
			if ($this->_sDisplay == 'option')
			{
				
			}
			elseif ($this->_sDisplay == 'admincp')
			{
				$sOutput = '<ul>';
			}
			else 
			{
				$this->_sOutput .= '<div class="js_mp_parent_holder" id="js_mp_holder_' . $iParentId . '" ' . ($iParentId > 0 ? ' style="display:none; padding:5px 0px 0px 0px;"' : '') . '>';
				$this->_sOutput .= '<select name="val[category][' . $iParentId . ']" class="js_mp_category_list contest_add required" id="js_mp_id_' . $iParentId . '">' . "\n";
				$this->_sOutput .= '<option value="">' . ($iParentId === 0 ? Phpfox::getPhrase('contest.select') : Phpfox::getPhrase('contest.select_a_sub_category')) . ':</option>' . "\n";
			}
			
			foreach ($aCategories as $iKey => $aCategory)
			{
				$aCache[] = $aCategory['category_id'];
				
				if ($this->_sDisplay == 'option')
				{
					$this->_sOutput .= '<option value="' . $aCategory['category_id'] . '" id="js_mp_category_item_' . $aCategory['category_id'] . '">' . ($this->_iCnt > 0 ? str_repeat('&nbsp;', ($this->_iCnt * 2)) . ' ' : '') . Phpfox::getLib('locale')->convert($aCategory['name']) . '</option>' . "\n";
					//$this->_sOutput .= $this->_get($aCategory['category_id'], $iActive);					
				}
				elseif ($this->_sDisplay == 'admincp')
				{
					$sOutput .= '<li><img src="' . Phpfox::getLib('template')->getStyle('image', 'misc/draggable.png') . '" alt="" /> <input type="hidden" name="order[' . $aCategory['category_id'] . ']" value="' . $aCategory['ordering'] . '" class="js_mp_order" /><a href="#?id=' . $aCategory['category_id'] . '" class="js_drop_down">' . Phpfox::getLib('locale')->convert($aCategory['name']) . '</a>' . $this->_get($aCategory['category_id'], $iActive) . '</li>' . "\n";
				}
				else 
				{				
					$this->_sOutput .= '<option value="' . $aCategory['category_id'] . '" id="js_mp_category_item_' . $aCategory['category_id'] . '">' . Phpfox::getLib('locale')->convert($aCategory['name']) . '</option>' . "\n";
				}
			}
			
			if ($this->_sDisplay == 'option')
			{
				
			}
			elseif ($this->_sDisplay == 'admincp')
			{
				$sOutput .= '</ul>';
				
				return $sOutput;
			}
			else 
			{			
				$this->_sOutput .= '</select>' . "\n";
				$this->_sOutput .= '</div>';
				
				foreach ($aCache as $iCateoryId)
				{
					$this->_get($iCateoryId, $iActive);
				}
			}
			
			$this->_iCnt = 0;
		}		
	}	
	
	
	/**
	 * get categories by Id or list of Ids seperated by comma
	 * @by minhta
	 * @param string $sCampaignIds purpose
	 * @return
	 */
	public function getCategoriesByCampaignId($iCampaignId) {
		
		$aCategories = $this->database()->select('pc.parent_id, pc.category_id, pc.name')
			->from(Phpfox::getT('contest_category_data'), 'pcd')
			->join($this->_sTable, 'pc', 'pc.category_id = pcd.category_id')
			->where('pcd.contest_id = ' . (int) $iCampaignId)
			->order('pc.parent_id ASC, pc.ordering ASC')
			->execute('getSlaveRows');

		if (!count($aCategories))
		{
			return null;
		}
		
		$aBreadcrumb = array();		
		if (count($aCategories) > 1)
		{			
			foreach ($aCategories as $aCategory)
			{				
				$aBreadcrumb[] = array(Phpfox::getLib('locale')->convert($aCategory['name']), Phpfox::permalink('contest.category', $aCategory['category_id'], $aCategory['name']));
			}
		}		
		else 
		{			
			$aBreadcrumb[] = array(Phpfox::getLib('locale')->convert($aCategories[0]['name']), Phpfox::permalink('contest.category', $aCategories[0]['category_id'], $aCategories[0]['name']));
		}
		
		return $aBreadcrumb;
		
	}
    /**
     * @TODO: LIST OF CATEGORY TO SELECT
     * <pre>
     * PhpFox::getService('fundraising.category')->getCategories($aConds  = array() , $sSort = string);
     * </pre>
     * @by datlv
     * @param stringarray $aConds condition for query
     * @param string $sSort condition for sort in query
     * @return $aItems list of all categories
     */

    public function getCategories($aConds = 'c.parent_id = 0', $sSort = 'c.name ASC')
    {
        $aItems = $this->database()->select('c.category_id, c.name')
            ->from(Phpfox::getT('contest_category'), 'c')
            ->where($aConds)
            ->group('c.category_id')
            ->order($sSort)
            ->execute('getSlaveRows');

        return $aItems;
    }

	public function getForBrowse($iCategoryId = null)
	{
		$sCacheId = $this->cache()->set('contest_category_browse' . ($iCategoryId === null ? '' : '_' . $iCategoryId));
	 	if (!($aCategories = $this->cache()->get($sCacheId)))
		{					
			$aCategories = $this->database()->select('mc.category_id, mc.name')
				->from($this->_sTable, 'mc')
				->where('mc.parent_id = 0' . ($iCategoryId === null ? '0' : (int) $iCategoryId) . ' AND mc.is_active = 1')
				->order('mc.ordering ASC')
				->execute('getRows');
			
			foreach ($aCategories as $iKey => $aCategory)
			{
				$aCategories[$iKey]['url'] = Phpfox::permalink('contest.category', $aCategory['category_id'], $aCategory['name']);
				
				//if ($sCategory === null)
				{
					$aCategories[$iKey]['sub'] = $this->database()->select('mc.category_id, mc.name')
						->from($this->_sTable, 'mc')
						->where('mc.parent_id = ' . $aCategory['category_id'] . ' AND mc.is_active = 1')
						->order('mc.ordering ASC')
						->execute('getRows');			
						
					foreach ($aCategories[$iKey]['sub'] as $iSubKey => $aSubCategory)
					{
						$aCategories[$iKey]['sub'][$iSubKey]['url'] = Phpfox::permalink('contest.category', $aSubCategory['category_id'], $aSubCategory['name']);
					}
				}
			}
			
			$this->cache()->save($sCacheId, $aCategories);
		}
		
		return $aCategories;
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
		if ($sPlugin = Phpfox_Plugin::get('contest.service_category_category__call'))
		{
			return eval($sPlugin);
		}
			
		/**
		 * No method or plug-in found we must throw a error.
		 */
		Phpfox_Error::trigger('Call to undefined method ' . __CLASS__ . '::' . $sMethod . '()', E_USER_ERROR);
	}
}

?>
