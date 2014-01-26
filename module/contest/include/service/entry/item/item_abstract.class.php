<?php

defined('PHPFOX') or exit('NO DICE!');

interface Contest_Service_Entry_Item_Item_Abstract {
	public function getAddNewItemLink($iContestId);

	public function getItemsOfCurrentUser($iLimit = 5, $iPage = 0);

	public function getItemFromFox($iItemId);

	public function getTemplateViewPath();

	public function getDataToInsertIntoEntry($iItemId);

	public function getDataFromFoxAdaptedWithContestEntryData($iItemId);

}