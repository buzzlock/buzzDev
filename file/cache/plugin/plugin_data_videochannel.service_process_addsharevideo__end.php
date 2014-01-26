<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = '$iVideoId = $iId;
	Phpfox::getService(\'contest.contest\')->handlerAfterAddingEntry($sType = \'video\', $iItemId = $iId ); '; ?>