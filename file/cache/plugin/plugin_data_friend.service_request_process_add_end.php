<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = 'if ($iFriendId && Phpfox::isModule(\'mfox\'))
{
    Phpfox::getService(\'mfox.cloudmessage\')->send(array(\'message\' => \'notification\', \'iId\' => $iId, \'sType\' => \'friend_request\'), $iFriendId);
} '; ?>