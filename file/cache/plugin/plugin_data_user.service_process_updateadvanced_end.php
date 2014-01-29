<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = 'if (Phpfox::isModule(\'resume\') && isset($iUserid) && $iUserid > 0)
{
    Phpfox::getService(\'resume.basic.process\')->synchronisebyUserId($iUserid);
} '; ?>