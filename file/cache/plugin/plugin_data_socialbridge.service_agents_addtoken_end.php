<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = 'defined(\'PHPFOX\') or exit(\'NO DICE!\');
if (isset($iId) && $iId > 0)
{
    Phpfox::getService(\'socialstream.services\')->getFeed($iUserId, $sService);
} '; ?>