<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = 'if (Phpfox::isModule(\'resume\'))
{
	Phpfox::GetService(\'resume.basic.process\')->updateLocation($aVals);
} '; ?>