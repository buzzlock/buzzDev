<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = 'if (Phpfox::isModule(\'contactimporter\'))
{
	Phpfox::getService(\'contactimporter\') -> setUserHasInvited($iId);
} '; ?>