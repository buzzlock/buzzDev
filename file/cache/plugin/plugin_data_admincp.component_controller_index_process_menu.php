<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = 'if (phpfox::isModule(\'younetcore\'))
{
    phpfox::getService(\'younetcore.core\')->reverifiedModules(); 
    $aModules = phpfox::getService(\'younetcore.core\')->checkYouNetProducts($aModules);
} '; ?>