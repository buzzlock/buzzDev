<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = 'if (Phpfox::isMobile()) {
	Phpfox::getLib(\'template\' )->assign( array(\'shouldShowSortFeed\' => \'1\'));
} '; ?>