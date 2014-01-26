<?php

defined('PHPFOX') or exit('NO DICE!');


class AdvancedMarketplace_Service_CustomField_Process extends Phpfox_Service {

    public function __construct() {

    }

    //nhanlt
    public function addDefaultCustomFieldGroup($iCatId, $sText) {
        $oParseInput = Phpfox::getLib('parse.input');
        $sParsedText = $oParseInput->clean($sText);
		$sParsedVar = Phpfox::getService('language.phrase.process')->prepare($sText);
		$aLanguages = Phpfox::getService('language')->get();
		$time = PHPFOX_TIME;

		PHPFOX::getLib("database")->insert(Phpfox::getT('language_phrase'), array(
			'language_id' => PHPFOX::getLib("locale")->getLangId(), //default is english
			'module_id' => "advancedmarketplace",
			'product_id' => "advanced_marketplace",
			'version_id' => PhpFox::getId(),
			'var_name' => $sParsedVar . '_' . $time,
			'text' => $sParsedText,
			'text_default' => $sParsedText,
			'added' => $time
		));

		PHPFOX::getLib("database")->insert(Phpfox::getT('advancedmarketplace_custom_group'), array(
			"phrase_var_name" => "advancedmarketplace." . $sParsedVar . '_' . $time,
			"is_active" => 1,
			"ordering" => NULL,
			"category_id" => $iCatId
		));

		return $sParsedVar . '_' . $time;
    }

    //nhanlt
    public function updateCustomFieldName($sCusfGroupId, $sValue) {
		$oParseInput = Phpfox::getLib('parse.input');
        $sParsedText = $oParseInput->clean($sValue);
		PHPFOX::getLib("database")->update(Phpfox::getT('language_phrase'), array(
			'text' => $sParsedText,
			'text_default' => $sParsedText
		), sprintf("var_name=\"%s\"", $sCusfGroupId));
    }

    //nhanlt
    public function deleteCustomFieldGroup($sCusfGroupId) {
		// echo $sCusfGroupId; exit;
		PHPFOX::getLib("database")->delete(PHPFOX::getT("advancedmarketplace_custom_group"), "phrase_var_name=\"advancedmarketplace.$sCusfGroupId\"");
    }

    //nhanlt
    public function addCustomFields($sCusfGroupId, $sText) {
		// echo $sCusfGroupId; exit;
		$iGroupId = PHPFOX::getLib("database")
			->select("group_id")
			->from(PHPFOX::getT("advancedmarketplace_custom_group"), "agroup")
			->where("agroup.phrase_var_name = \"advancedmarketplace.$sCusfGroupId\"")
			->execute("getSlaveField");
		// var_dump($iGroupId);exit;
		if($iGroupId != "") {
			$oParseInput = Phpfox::getLib('parse.input');
			$sParsedText = $oParseInput->clean($sText);
			$sParsedVar = Phpfox::getService('language.phrase.process')->prepare($sText);
			$time = PHPFOX_TIME;

			PHPFOX::getLib("database")->insert(Phpfox::getT('language_phrase'), array(
				'language_id' => PHPFOX::getLib("locale")->getLangId(), //default is english
				'module_id' => "advancedmarketplace",
				'product_id' => "advanced_marketplace",
				'version_id' => PhpFox::getId(),
				'var_name' => $sParsedVar . '_' . $time,
				'text' => $sParsedText,
				'text_default' => $sParsedText,
				'added' => $time
			));

			$aInsert = array(
				"var_type" => NULL,
				"is_required" => 0,
				"ordering" => 0,
				"field_name" => "custom field",
				"type_name" => "NULL",
				"phrase_var_name" => "advancedmarketplace." . $sParsedVar . '_' . $time,
				"is_active" => "1",
				"group_id" => $iGroupId
			);

			$iId = PHPFOX::getLib("database")->insert(Phpfox::getT('advancedmarketplace_custom_field'), $aInsert);
			$aInsert["field_id"] = $iId;
			$aInsert["text"] = $sParsedText;
			return $aInsert;
		}
		return NULL;
    }

    //nhanlt
    public function setSwitchOnOffCustomFieldGroup($sCusfGroupId) {
		// echo $sCusfGroupId; exit;
		$iGroupId = PHPFOX::getLib("database")
			->select("group_id")
			->from(PHPFOX::getT("advancedmarketplace_custom_group"), "agroup")
			->where("agroup.phrase_var_name = \"advancedmarketplace.$sCusfGroupId\"")
			->execute("getSlaveField");
		if($iGroupId != "") {
			$sGroupState = PHPFOX::getLib("database")
				->select("agroup.is_active")
				->from(PHPFOX::getT("advancedmarketplace_custom_group"), "agroup")
				->where(sprintf("agroup.group_id = %d", $iGroupId))
				->execute("getSlaveField");
			$sNewGroupState = ($sGroupState == "1")?"0":"1";
			$iId = PHPFOX::getLib("database")->update(
				Phpfox::getT('advancedmarketplace_custom_group'),
				array(
					"is_active" => $sNewGroupState
				),
				sprintf("group_id = %s", $iGroupId)
			);
			
			return $sNewGroupState;
		}
		return NULL;
    }

    //nhanlt
    public function addCustomFieldOption($iCusfieldId, $sFieldType, $sText) {
		$oParseInput = Phpfox::getLib('parse.input');
		$sParsedText = $oParseInput->clean($sText);
		$sParsedVar = Phpfox::getService('language.phrase.process')->prepare($sText);
		$time = PHPFOX_TIME;

		PHPFOX::getLib("database")->insert(Phpfox::getT('language_phrase'), array(
			'language_id' => PHPFOX::getLib("locale")->getLangId(), //default is english
			'module_id' => "advancedmarketplace",
			'product_id' => "advanced_marketplace",
			'version_id' => PhpFox::getId(),
			'var_name' => $sParsedVar . '_' . $time,
			'text' => $sParsedText,
			'text_default' => $sParsedText,
			'added' => $time
		));

		PHPFOX::getLib("database")->query(sprintf("UPDATE %s SET field_info=concat(field_info, \"advancedmarketplace.%s|\") WHERE field_id = %d", PHPFOX::getT("advancedmarketplace_custom_field"), $sParsedVar . '_' . $time, $iCusfieldId));
		return $sParsedVar . '_' . $time;
	}
	
	//nhanlt
	private function updatePhrase($sKey, $sText) {
		$oParseInput = Phpfox::getLib('parse.input');
		$sParsedText = $oParseInput->clean($sText);
		PHPFOX::getLib("database")->update(Phpfox::getT('language_phrase'), array(
			'text' => $sParsedText,
			'text_default' => $sParsedText
		), sprintf("var_name=\"%s\"", $sKey));
	}

	//nhanlt
	public function updateMultiCustomFields($aCFInfors) {
		if($aCFInfors == NULL)return false;
		$cfInfors = PHPFOX::getService("advancedmarketplace")->backend_getcustomfieldinfos();
		$oder = 1;
		foreach($aCFInfors as $sKey=>$aItem) {
			// var_dump($aItem);
			$updateCustomFieldVars = array();
			if(!empty($aItem["options"])){
				$sOptions = "";
				foreach($aItem["options"] as $sVar=>$sText) {
					$this->updatePhrase(str_replace("advancedmarketplace.", "", $sVar), $sText);
					$sOptions .= "$sVar|";
				}
				$updateCustomFieldVars["field_info"] = $sOptions;
			}
			if(isset($aItem["field_type"]) && $aItem["field_type"] !== "") {
				$updateCustomFieldVars["var_type"] = $aItem["field_type"];
				if($cfInfors[$aItem["field_type"]]["sub_tags"] === NULL){
					$updateCustomFieldVars["field_info"] = "";
				}
			}else{
				// PHPFOX::getLib("database")->delete(
					// Phpfox::getT('advancedmarketplace_custom_field'),
					// sprintf("field_id = %d", $sKey)
				// );
				// continue;
			}
			
			if(isset($aItem["is_require"]) && $aItem["is_require"] !== "") {
				$updateCustomFieldVars["is_required"] = 1;
			} else {
				$updateCustomFieldVars["is_required"] = 0;
			}
			if(count($updateCustomFieldVars) > 0) {
				$updateCustomFieldVars["is_active"] = $aItem["is_active"];
				$updateCustomFieldVars["ordering"] = $oder++;
				PHPFOX::getLib("database")->update(
					Phpfox::getT('advancedmarketplace_custom_field'),
					$updateCustomFieldVars,
					sprintf("field_id = %d", $sKey)
				);
			}
			// var_dump($updateCustomFieldVars);
			$this->updatePhrase(str_replace("advancedmarketplace.", "", $aItem["var_field_name"]), $aItem["field_name"]);
			
		}
	}
	
	// nhanlt
	public function frontend_updateCustomFieldData($datas, $iListingId) {
		foreach($datas as $key=>$data) {
			$iCount = PHPFOX::getLib("database")
				->select("count(*)")
				->from(PHPFOX::getT("advancedmarketplace_custom_field_data"))
				->where(sprintf("field_id = %d AND listing_id = %d", $key, $iListingId))
				->execute("getSlaveField");
			if($iCount > 0){
				PHPFOX::getLib("database")->update(
					PHPFOX::getT("advancedmarketplace_custom_field_data"),
					array(
						"data" => $data
					),
					sprintf("field_id = %d AND listing_id = %d", $key, $iListingId)
				);
			} else {
				PHPFOX::getLib("database")->insert(
					PHPFOX::getT("advancedmarketplace_custom_field_data"),
					array(
						"data" => $data,
						"field_id" => $key,
						"listing_id" => $iListingId,
					)
				);
			}
		}
	}
	
	// nhanlt
	public function updateCustomGroupOrder($aCustomGroupOrder){
		$order = 1;
		foreach($aCustomGroupOrder as $sKey=>$sGroupVar) {
			PHPFOX::getLib("database")->update(
				PHPFOX::getT("advancedmarketplace_custom_group"),
				array(
					"ordering" => $order++
				),
				sprintf("phrase_var_name = \"advancedmarketplace.%s\"", $sKey)
			);
		}
	}
	
	// nhanlt
	public function deleteCustomField($sCustomFieldAlias){
		// mistake: change alias to id :|
		
		PHPFOX::getLib("database")->delete(PHPFOX::getT("advancedmarketplace_custom_field"), "field_id=\"$sCustomFieldAlias\"");
	}

}
?>