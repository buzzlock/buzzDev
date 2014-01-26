<?php


defined('PHPFOX') or exit('NO DICE!');

class AdvancedMarketplace_Service_Category_Category extends Phpfox_Service 
{
	private $_sOutput = '';
	
	private $_iCnt = 0;
	
	private $_sDisplay = 'select';
	
	/**
	 * Class constructor
	 */	
	public function __construct()
	{	
		$this->_sTable = Phpfox::getT('advancedmarketplace_category');
	}
	
	public function getForEdit($iId)
	{
		return $this->database()->select('*')
			->from($this->_sTable)
			->where('category_id = ' . (int) $iId)
			->execute('getRow');
	}
	
	public function getForBrowse($iCategoryId = null)
	{
		(($sPlugin = Phpfox_Plugin::get('advancedmarketplace.service_category_getforbrowse')) ? eval($sPlugin) : false);
		
		$sCacheId = $this->cache()->set('advancedmarketplace_category_browse' . ($iCategoryId === null ? '' : '_' . md5($iCategoryId)));		
	 	if (!($aCategories = $this->cache()->get($sCacheId)))
		{					
			$aCategories = $this->database()->select('mc.category_id, mc.name')
				->from($this->_sTable, 'mc')
				->where('mc.parent_id = ' . ($iCategoryId === null ? '0' : (int) $iCategoryId) . ' AND mc.is_active = 1')
				->order('mc.ordering ASC')
				->execute('getRows');
			
			foreach ($aCategories as $iKey => $aCategory)
			{
				$aCategories[$iKey]['url'] = Phpfox::permalink('advancedmarketplace.search.category', $aCategory['category_id'], $aCategory['name']);
				
				//if ($sCategory === null)
				{
					$aCategories[$iKey]['sub'] = $this->database()->select('mc.category_id, mc.name')
						->from($this->_sTable, 'mc')
						->where('mc.parent_id = ' . $aCategory['category_id'] . ' AND mc.is_active = 1')
						->order('mc.ordering ASC')
						->execute('getRows');			
						
					foreach ($aCategories[$iKey]['sub'] as $iSubKey => $aSubCategory)
					{
						$aCategories[$iKey]['sub'][$iSubKey]['url'] = Phpfox::permalink('advancedmarketplace.search.category', $aSubCategory['category_id'], $aSubCategory['name']);
					}
				}
			}
			
			$this->cache()->save($sCacheId, $aCategories);
		}		
		
		return $aCategories;
	}
	
	public function display($sDisplay)
	{
		$this->_sDisplay = $sDisplay;
		
		return $this;
	}
	
	public function get()
	{
		$sCacheId = $this->cache()->set('advancedmarketplace_category_display_' . $this->_sDisplay);
		
		if ($this->_sDisplay == 'admincp')
		{
			if (!($sOutput = $this->cache()->get($sCacheId)))
			{				
				$sOutput = $this->_get(0, 1);
				
				//$this->cache()->save($sCacheId, $sOutput);
			}
			
			return $sOutput;
		}
		else 
		{
			if (!($this->_sOutput = $this->cache()->get($sCacheId)))
			{				
				$iEditId = $this->request()->getInt('id') ? $this->request()->getInt('id') : null;
                
                $this->_get(0, 1, $iEditId);
				
				//$this->cache()->save($sCacheId, $this->_sOutput);
			}
			
			return $this->_sOutput;
		}		
	}
	
	public function getParentBreadcrumb($sCategory)
	{	
		$sCacheId = $this->cache()->set('advancedmarketplace_parent_breadcrumb_' . md5($sCategory));
		if (!($aBreadcrumb = $this->cache()->get($sCacheId)))
		{		
			$sCategories = $this->getParentCategories($sCategory);

			$aCategories = $this->database()->select('*')
				->from($this->_sTable)
				->where('category_id IN(' . $sCategories . ')')
				->execute('getRows');
			$aBreadcrumb = $this->getCategoriesById(null, $aCategories);
			
			$this->cache()->save($sCacheId, $aBreadcrumb);
		}		
		
		return $aBreadcrumb;
	}
	
	public function getCategoriesById($iId = null, &$aCategories = null)
	{
		$oUrl = Phpfox::getLib('url');
		if ($aCategories === null)
		{
			$aCategories = $this->database()->select('pc.parent_id, pc.category_id, pc.name')
				->from(Phpfox::getT('advancedmarketplace_category_data'), 'pcd')
				->join($this->_sTable, 'pc', 'pc.category_id = pcd.category_id')
				->where('pcd.listing_id = ' . (int) $iId)
				->order('pc.parent_id ASC, pc.ordering ASC')
				->execute('getSlaveRows');
			
		}

		if (!count($aCategories))
		{
			return null;
		}
		
		$aBreadcrumb = array();		
		if (count($aCategories) > 1)
		{			
			foreach ($aCategories as $aCategory)
			{				
				$aBreadcrumb[] = array(Phpfox::getLib('locale')->convert($aCategory['name']), Phpfox::permalink('advancedmarketplace.search.category', $aCategory['category_id'], $aCategory['name']));
			}
		}		
		else 
		{			
			$aBreadcrumb[] = array(Phpfox::getLib('locale')->convert($aCategories[0]['name']), Phpfox::permalink('advancedmarketplace.search.category', $aCategories[0]['category_id'], $aCategories[0]['name']));
		}				

		return $aBreadcrumb;
	}	
	
	public function getCategoryIds($iId)
	{
		$aCategories = $this->database()->select('category_id')
			->from(Phpfox::getT('advancedmarketplace_category_data'))
			->where('listing_id = ' . (int) $iId)
			->execute('getSlaveRows');
			
		$aCache = array();
		foreach ($aCategories as $aCategory)
		{
			$aCache[] = $aCategory['category_id'];
		}
		
		return implode(',', $aCache);
	}
	
	// nhanlt
	public function getCategoryId($iId)
	{
		$aCategories = $this->database()->select('category_id')
			->from(Phpfox::getT('advancedmarketplace_category_data'))
			->where('listing_id = ' . (int) $iId)
			->execute('getSlaveRows');
		
		return $aCategories[count($aCategories) - 1];
	}
	
	public function getAllCategories($sCategory)
	{
		$sCacheId = $this->cache()->set('advancedmarketplace_category_children_' . $sCategory);
		
		if (!($sCategories = $this->cache()->get($sCacheId)))
		{
			$iCategory = $this->_getCorrectId($sCategory);
			$sCategories = $this->_getChildIds($iCategory);
			$sCategories = rtrim($iCategory . ',' . ltrim($sCategories, $iCategory . ','), ',');			
			
			$this->cache()->save($sCacheId, $sCategories);
		}		

		return $sCategories;	
	}	
	
	public function getChildIds($iId)
	{
		return rtrim($this->_getChildIds($iId), ',');
	}
	
	public function getParentIds($iId)
	{
		return rtrim($this->_getParentIds($iId), ',');
	}
	
	public function getParentCategories($sCategory)
	{
		$sCacheId = $this->cache()->set('advancedmarketplace_category_parent_' . $sCategory);

		if (!($sCategories = $this->cache()->get($sCacheId)))
		{
			$iCategory = $this->database()->select('category_id')
				->from($this->_sTable)
				->where('category_id = \'' . (int) $sCategory . '\'')
				->execute('getField');
		
			$sCategories = $this->_getParentIds($iCategory);

			$sCategories = rtrim($sCategories, ',');
			
			$this->cache()->save($sCacheId, $sCategories);
		}

		return $sCategories;	
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
		if ($sPlugin = Phpfox_Plugin::get('advancedmarketplace.service_category_category__call'))
		{
			return eval($sPlugin);
		}
			
		/**
		 * No method or plug-in found we must throw a error.
		 */
		Phpfox_Error::trigger('Call to undefined method ' . __CLASS__ . '::' . $sMethod . '()', E_USER_ERROR);
	}	
	
	private function _getChildIds($iParentId, $bUseId = true)
	{
		$aCategories = $this->database()->select('pc.name, pc.category_id')
			->from($this->_sTable, 'pc')
			->where(($bUseId ? 'pc.parent_id = ' . (int) $iParentId . '' : 'pc.name_url = \'' . $this->database()->escape($iParentId) . '\''))
			->execute('getRows');
			
		$sCategories = '';
		foreach ($aCategories as $aCategory)
		{
			$sCategories .= $aCategory['category_id'] . ',' . $this->_getChildIds($aCategory['category_id']) . '';
		}
		
		return $sCategories;		
	}		
	
	private function _getParentIds($iId)
	{		
		$aCategories = $this->database()->select('pc.category_id, pc.parent_id')
			->from($this->_sTable, 'pc')
			->where('pc.category_id = ' . (int) $iId)
			->execute('getRows');
		
		$sCategories = '';
		foreach ($aCategories as $aCategory)
		{
			$sCategories .= $aCategory['category_id'] . ',' . $this->_getParentIds($aCategory['parent_id']) . '';
		}
		
		return $sCategories;		
	}	
	
	private function _get($iParentId, $iActive = null, $iEditId = null)
	{
		$aCategories = $this->database()->select('*')
			->from($this->_sTable)
			->where('parent_id = ' . (int) $iParentId . ' AND is_active = ' . (int) $iActive . '')
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
				$this->_sOutput .= '<select name="val[category][]" class="js_mp_category_list" id="js_mp_id_' . $iParentId . '">' . "\n";
				$this->_sOutput .= '<option value="">' . ($iParentId === 0 ? Phpfox::getPhrase('advancedmarketplace.select') : Phpfox::getPhrase('advancedmarketplace.select_a_sub_category')) . ':</option>' . "\n";
			}
			
			foreach ($aCategories as $iKey => $aCategory)
			{
				if($aCategory['category_id'] == $iEditId)
                {
                    continue;
                }
                
                $aCache[] = $aCategory['category_id'];
				
				if ($this->_sDisplay == 'option')
				{
					$this->_sOutput .= '<option value="' . $aCategory['category_id'] . '" id="js_mp_category_item_' . $aCategory['category_id'] . '">' . ($this->_iCnt > 0 ? str_repeat('&nbsp;', ($this->_iCnt * 2)) . ' ' : '') . Phpfox::getLib('locale')->convert($aCategory['name']) . '</option>' . "\n";
					$this->_sOutput .= $this->_get($aCategory['category_id'], $iActive, $iEditId);					
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
	
	private function _getParentsUrl($iParentId, $bPassName = false)
	{
		// Cache the round we are going to increment
		static $iCnt = 0;
		
		// Add to the cached round
		$iCnt++;
		
		// Check if this is the first round
		if ($iCnt === 1)
		{
			// Cache the cache ID
			static $sCacheId = null;
			
			// Check if we have this data already cached
			$sCacheId = $this->cache()->set('advancedmarketplace_category_url' . ($bPassName ? '_name' : '') . '_' . $iParentId);
			if ($sParents = $this->cache()->get($sCacheId))
			{
				return $sParents;
			}
		}
		
		// Get the menus based on the category ID
		$aParents = $this->database()->select('category_id, name, name_url, parent_id')
			->from($this->_sTable)
			->where('category_id = ' . (int) $iParentId)
			->execute('getRows');
			
		// Loop thur all the sub menus
		$sParents = '';
		foreach ($aParents as $aParent)
		{
			$sParents .= $aParent['name_url'] . ($bPassName ? '|' . $aParent['name'] . '|' . $aParent['category_id'] : '') . '/' . $this->_getParentsUrl($aParent['parent_id'], $bPassName);
		}		
	
		// Save the cached based on the static cache ID
		if (isset($sCacheId))
		{
			$this->cache()->save($sCacheId, $sParents);
		}
		
		// Return the loop
		return $sParents;		
	}	
	
	private function _getCorrectId($sCategory)
	{				
		if (preg_match('/\./i', $sCategory))
		{			
			$aParts = explode('.', $sCategory);		
			$iCategoryId = 0;			
			for ($i = 0; $i < count($aParts); $i++)
			{					
				$iCategoryId = $this->database()->select('category_id')
					->from($this->_sTable)
					->where(($iCategoryId > 0 ? 'parent_id = ' . (int) $iCategoryId . ' AND ' : ' parent_id = 0 AND ') . 'name_url = \'' . $this->database()->escape($aParts[$i]) . '\'')
					->execute('getField');						
			}							
		}
		else 
		{
			$iCategoryId = $this->database()->select('category_id')
				->from($this->_sTable)
				->where('parent_id = 0 AND name_url = \'' . $this->database()->escape($sCategory) . '\'')
				->execute('getField');
		}
		
		return $iCategoryId;
	}
	
	public function getChildIdsOfCats($aCategories)
	{
		$iCategoryId = '';
		$iCategoryId = $aCategories[0];
		foreach($aCategories as $iCatId)
		{
			$iChildId = $this->_getChildIds($iCatId['category_id']);
			if($iChildId == '')
			{
				$iCategoryId = $iCatId;
				return $iCategoryId; 
			}
		}
		return $iCategoryId;
	}
	
	// nhanlt
	public function getCategorieStructure ($returnAllStructure = NULL) {
		$cats = PHPFOX::getLib("database")
			->select("*")
			->from(PHPFOX::getT("advancedmarketplace_category"))
			->execute("getRows")
		;
			
		$aCategories = array();
		$all = array();
		$dangling = array();

		// Initialize arrays
		foreach ($cats as $entry) {
			$entry['children'] = array();
			$id = $entry['category_id'];
			$entry['url'] = Phpfox::permalink('advancedmarketplace.search.category', $entry['category_id'], $entry['name']);

			// If this is a top-level node, add it to the output immediately
			if ($entry['parent_id'] == 0) {
				$all[$id] = $entry;
				$aCategories[] =& $all[$id];

			// If this isn't a top-level node, we have to process it later
			} else {
				$dangling[$id] = $entry; 
			}
		}
		
		while (count($dangling) > 0) {
			foreach($dangling as $entry) {
				$id = $entry['category_id'];
				$pid = $entry['parent_id'];

				// If the parent has already been added to the output, it's
				// safe to add this node too
				if (isset($all[$pid])) {
					$all[$id] = $entry;
					$all[$pid]['children'][] =& $all[$id]; 
					$all[$id]['parent_id'] = $pid;
					unset($dangling[$entry['category_id']]);
				}
			}
		}
		if($returnAllStructure){
			return array($all, $aCategories);
		} else {
			return $aCategories;
		}
	}
}

?>