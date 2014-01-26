<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = 'defined(\'PHPFOX\') or exit(\'NO DICE!\');

if (!Phpfox::isMobile() && Phpfox::isUser() && !Phpfox::isAdminPanel() && Phpfox::isModule(\'notification\'))
{
        PhpFox::getLib(\'template\')->setHeader(array(
            \'fanot.js\' => \'module_fanot\', 
			\'fanot.css\' => \'module_fanot\'
                )
        );
} if (Phpfox::isModule(\'fevent\') && !Phpfox::isAdminPanel())
{
    $sJdpickerPhrases = Phpfox::getService(\'fevent\')->getJdpickerPhrases();
    Phpfox::getLib(\'template\')->setHeader(array(
        \'<script type="text/javascript">\'.$sJdpickerPhrases.\'</script>\',
        \'jquery.jdpicker.js\' => \'module_fevent\'
    ));
} '; ?>