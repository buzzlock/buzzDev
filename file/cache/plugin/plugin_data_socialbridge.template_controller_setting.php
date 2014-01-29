<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = 'defined(\'PHPFOX\') or exit(\'NO DICE!\');

Phpfox::getBlock(\'socialpublishers.settings\'); defined(\'PHPFOX\') or exit(\'NO DICE!\');

Phpfox::getBlock(\'socialstream.settings\', array(
	\'privacy_name\' => \'privacy\',
	\'default_privacy\' => 2,
	\'privacy_type\' => \'mini\',
	\'privacy_no_custom\' => true
)); '; ?>