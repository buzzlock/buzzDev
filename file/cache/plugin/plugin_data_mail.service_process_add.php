<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = 'if (isset($aVals[\'user_id\']) && $aVals[\'user_id\'] && Phpfox::isModule(\'mfox\'))
{
	Phpfox::getService(\'mfox.cloudmessage\') -> send(array(\'message\' => \'notification\', \'iId\' => $iId, \'sType\' => \'mail\'), $aVals[\'user_id\']);
} '; ?>