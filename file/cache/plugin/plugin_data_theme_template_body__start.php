<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = ' ?>
<script type="text/javascript">
    oCore[\'core.disable_hash_bang_support\'] = 1;
</script>
<?php ?>
<?php
if (Phpfox::isModule(\'advancedmarketplace\'))
{
    Phpfox::getLib(\'setting\')->setParam(\'advancedmarketplace.dir_pic\', Phpfox::getParam(\'core.dir_pic\') . "advancedmarketplace/");
    Phpfox::getLib(\'setting\')->setParam(\'advancedmarketplace.url_pic\', Phpfox::getParam(\'core.url_pic\') . "advancedmarketplace/");
} echo \'<script type="text/javascript" src="\'. Phpfox::getParam(\'core.path\') . \'module/advancedphoto/static/jscript/ynadvphoto_thickbox.js"></script>\'; echo \'<div id="fb-root"></div>\'; '; ?>