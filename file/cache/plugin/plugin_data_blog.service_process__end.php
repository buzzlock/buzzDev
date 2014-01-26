<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = 'if($aVals[\'post_status\'] == 1)
	{
		Phpfox::getService(\'contest.contest\')->handlerAfterAddingEntry($sType = \'blog\', $iItemId = $iId );
	} '; ?>