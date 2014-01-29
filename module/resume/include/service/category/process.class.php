<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 *
 * @copyright      YouNet Company
 * @author         VuDP, TienNPL
 * @package        Module_Resume
 * @version        3.01
 * 
 */
class Resume_Service_Category_Process extends Phpfox_Service
{
	/**
	 * Class constructor
	 */	
	public function __construct()
	{	
		$this->_sTable = Phpfox::getT('resume_category');
	}
	
	/**
	 * Add new category into database
	 * @param array $aVals - array of category input information 
	 * @return integer $iId - the id of the  inserted category 
	 */
	public function add($aVals)
	{
		if (empty($aVals['name']))
		{
			return Phpfox_Error::set(Phpfox::getPhrase('event.provide_a_category_name'));
		}
		
		$oParseInput = Phpfox::getLib('parse.input');
		
		$iId = $this->database()->insert($this->_sTable, array(
				'parent_id'  => (!empty($aVals['parent_id']) ? (int) $aVals['parent_id'] : 0),
				'is_active'  => 1,
				'name' 		 => $oParseInput->clean($aVals['name'], 255),
				'name_url' 	 => $oParseInput->cleanTitle($aVals['name']),
				'time_stamp' => PHPFOX_TIME
			)
		);
		
		$this->cache()->remove('resume', 'substr');
		
		return $iId;
	}
	/**
	 * Update order of category list
	 * @param array $aVals - array of category order
	 * @return true
	 */
	public function updateOrder($aVals)
	{
		foreach ($aVals as $iId => $iOrder)
		{
			$this->database()->update($this->_sTable, array('ordering' => $iOrder), 'category_id = ' . (int) $iId);
		}
		
		$this->cache()->remove('resume', 'substr');
		
		return true;
	}
	/** 
	 * Update category data
	 */
	public function update($iId, $aVals)
	{
		$oParseInput = Phpfox::getLib('parse.input');
		$this->database()->update(
				$this->_sTable, 
				array(
					'name' 		=> $oParseInput->clean($aVals['name'], 255), 
					'name_url' 	=> $oParseInput->cleanTitle($aVals['name']),
					'parent_id' => (int) $aVals['parent_id']
				), 
				'category_id = ' . (int) $iId);
		
		$this->cache()->remove('resume', 'substr');
		
		return true;
	}
	/**
	 * Delete a category
	 * @param integer $iId - the id of the category needed to be deleted
	 * @return true
	 */
	public function delete($iId)
	{
		$this->database()->update($this->_sTable, array('parent_id' => 0), 'parent_id = ' . (int) $iId);
		
		/**
		 * @todo Remove the deleted category id from all related resume.
		 */
		
		$this->database()->delete($this->_sTable, 'category_id = ' . (int) $iId);
		
		$this->cache()->remove('resume', 'substr');
		
		return true;
	}

	/**
	 * Add data for category 
	 * @param $resum_id: id of resume which you are editing or creating
	 * @param $category_id: id of category is added.
	 */
	 public function addCategorydata($resume_id,$category_id)
	 {
	 	$this->database()->insert(Phpfox::getT('resume_category_data'),array(
	 		'resume_id' => $resume_id,
	 		'category_id' => $category_id
		));
	 }
	 
	 /**
	  * Delete all Category data if have resume id in params
	  */
	  public function deleteAllCategorydata($resume_id)
	  {
	  		return $this->database()->delete(Phpfox::getT('resume_category_data'),'resume_id='.$resume_id);
	  }
	  
	  /**
	   * increase or except one unit on category table
	   */
	   public function updateUsedCategory($cagegory_id,$unit)
	   {
	   		$sSql = 'update '.Phpfox::getT('resume_category').' set used=used+'.$unit.' where category_id='.$cagegory_id;
	   		Phpfox::getLib("database")->query($sSql);
	   }
	  
}


?>