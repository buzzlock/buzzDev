<?php


defined('PHPFOX') or exit('NO DICE!');

/**
 *
 *
 */
class AdvancedMarketplace_Service_CustomField_AdvancedMarketplace extends Phpfox_Service {

    /**
     * Class constructor
     */
    public function __construct() {
        
    }
	
	public function loadAllCustomFieldGroup($iCatId = NULL){
		$oQuery = PHPFOX::getLib("database")
			->select("*, REPLACE(phrase_var_name, \"advancedmarketplace.\", \"\") AS var_name")
			->from(PHPFOX::getT("advancedmarketplace_custom_group"));
		if($iCatId !== NULL){
			$oQuery->where(sprintf("category_id = %d", $iCatId));
		}
		
		$aRows = $oQuery->execute("getRows");
		foreach($aRows as $index=>$aRow) {
			$aRows[$index]["text"] = PHPFOX::getPhrase($aRow["phrase_var_name"]);
			// var_dump($aRows[$index]["text"]);exit;
		}
		return $aRows;
	}
	
	public function loadCustomFields($iCatId = NULL){
		$oQuery = PHPFOX::getLib("database")
			->select("cf.*, cg.phrase_var_name as group_phrase_var_name, cg.is_active as group_is_active, cg.category_id, REPLACE(cg.phrase_var_name, \"advancedmarketplace.\", \"\") AS var_name")
			->from(PHPFOX::getT("advancedmarketplace_custom_group"), "cg")
			->join(PHPFOX::getT("advancedmarketplace_custom_field"), "cf", "cf.group_id = cg.group_id")
			->order("ordering ASC")
			->where(sprintf("cg.phrase_var_name = \"advancedmarketplace.%s\"", $iCatId));
		
		$aRows = $oQuery->execute("getRows");
		foreach($aRows as $index=>$aRow) {
			$aRows[$index]["group_text_name"] = PHPFOX::getPhrase($aRow["phrase_var_name"]);
			$tmp = explode("|", $aRow["field_info"]);
			$aRows[$index]["options"] = empty($aRow["field_info"])?NULL:array_slice($tmp, 0, count($tmp) - 1);
			// var_dump($aRows[$index]["text"]);exit;
		}
		return $aRows;
	}
	
	public function frontend_loadCustomFields($iCatId, $iListingId = NULL) {
		$aRet = array();
		// var_dump($iCatId);
		$oQuery = PHPFOX::getLib("database")
			->select("cf.*, cg.ordering as group_order, cf.ordering as field_order, cg.phrase_var_name as group_phrase_var_name, cg.is_active as group_is_active, cg.category_id, REPLACE(cg.phrase_var_name, \"advancedmarketplace.\", \"\") AS var_name")
			->from(PHPFOX::getT("advancedmarketplace_custom_group"), "cg")
			->join(PHPFOX::getT("advancedmarketplace_custom_field"), "cf", "cf.group_id = cg.group_id")
			->order("group_order ASC, field_order ASC")
			->where(sprintf("cg.category_id = %d AND cg.is_active = 1 AND cf.is_active = 1", $iCatId));
		if($iListingId !== NULL) {
			$oQuery->leftJoin(PHPFOX::getT("advancedmarketplace_custom_field_data"), "cd", sprintf("cd.field_id = cf.field_id AND cd.listing_id = %d", $iListingId));
			$oQuery->select(", cd.data");
		}
		$aRows = $oQuery->execute("getRows");
		// var_dump($aRows);exit;
		foreach($aRows as $aRow) {
			$tmp = explode("|", $aRow["field_info"]);
			$aRet[$aRow["group_phrase_var_name"]][$aRow["field_id"]] = $aRow;
			$aRet[$aRow["group_phrase_var_name"]][$aRow["field_id"]]["options"] = empty($aRow["field_info"])?NULL:array_slice($tmp, 0, count($tmp) - 1);
			$aRet[$aRow["group_phrase_var_name"]][$aRow["field_id"]]["view_id"] = str_replace(array("advancedmarketplace.","_") , array("",""), $aRow["var_type"]);
		}
		return $aRet;
	}
}