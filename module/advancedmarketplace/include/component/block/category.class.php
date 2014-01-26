<?php


defined('PHPFOX') or exit('NO DICE!');


class AdvancedMarketplace_Component_Block_Category extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$sCategory = $this->getParam('sCategory');
		$bIsProfile = false;
		$bIsProfile = $this->getParam('bIsProfile');

		if($bIsProfile) {
			return false;
		}
		$this->template()->assign(array(
				'bIsProfile' => $bIsProfile,
			));
		// array_reverse
		$cats = PHPFOX::getLib("database")
			->select("*")
			->from(PHPFOX::getT("advancedmarketplace_category"))
			->order("ordering asc")
			->execute("getRows")
			;
			
		$aCategories = array();
		$all = array();
		$dangling = array();
		
		$iCurrentCategoryId = $this->request()->get('req4');
		$aListing = $this->getParam("aListing");
		if(!is_numeric($iCurrentCategoryId)) {
			$iListingId = $aListing["listing_id"];
			$iCurrentCategoryId = PHPFOX::getLib("database")
				->select("category_id")
				->from(PHPFOX::getT("advancedmarketplace_category_data"))
				->where(sprintf("listing_id = %d", $iListingId))
				->order("category_id DESC")
				->limit(1)
				->execute("getField");
		}

		// Initialize arrays
		foreach ($cats as $entry) {
			$entry['children'] = array();
			$id = $entry['category_id'];
            $entry['name'] = Phpfox::getLib("locale")->convert($entry['name']);
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
		
		$iTopParentId = (int)$iCurrentCategoryId;
		
		$iCurrentLevel = 0;
		while($iTopParentId != 0 && $all[$iTopParentId]['parent_id'] != 0){
			$iTopParentId = $all[$iTopParentId]['parent_id'];
			$iCurrentLevel++;
		}
		
		if (!is_array($aCategories))
		{
			return false;
		}
		
		if (!count($aCategories))
		{
			return false;
		}
		$this->updateCategoryLevels($aCategories, 0);
		// var_dump($aCategories);exit;
		$this->template()->assign(array(
				'sHeader' => Phpfox::getPhrase('advancedmarketplace.categories'),
				'aCategories' => $aCategories,
				'sCategory' => $sCategory,
				"iTopParentId" => $iTopParentId,
				"iCurrentLevel" => $iCurrentLevel,
				"iCurrentCategoryId" => $iCurrentCategoryId,
				// "iCurrentLevel" => $aCategories[$iCurrentCategoryId]["level"]
			)
		);
		
		return 'block';		
	}
	
	private function updateCategoryLevels($aCategories, $level = 0) {
		if(empty($aCategories))return false;

		foreach($aCategories as $key=>$aCategory) {
			$sLIClass = "";
			$aCategories[$key]["level"] = $level;
			if(!empty($aCategory["children"])) {
				$this->updateCategoryLevels($aCategory["children"], $level + 1);
			}
		}
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('advancedmarketplace.component_block_category_clean')) ? eval($sPlugin) : false);
	}
}

?>