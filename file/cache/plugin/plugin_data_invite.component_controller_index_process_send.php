<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = 'if (Phpfox::isModule(\'contactimporter\') && isset($iInvite) && $iInvite > 0)
{
    Phpfox::getService(\'contactimporter\')->updateStatistic(Phpfox::getUserId(), 1, 0, \'manual\');
} '; ?>