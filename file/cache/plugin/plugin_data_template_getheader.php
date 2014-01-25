<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = 'if (Phpfox::isModule(\'fevent\') && !Phpfox::isAdminPanel())
{
    $sJdpickerPhrases = Phpfox::getService(\'fevent\')->getJdpickerPhrases();
    Phpfox::getLib(\'template\')->setHeader(array(
        \'<script type="text/javascript">\'.$sJdpickerPhrases.\'</script>\',
        \'jquery.jdpicker.js\' => \'module_fevent\'
    ));
} '; ?>