<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php $aContent = '$tmpVar = \'\';

if (Phpfox::isMobile()) 
{
	$aReturn[\'feed_image_onclick\'] = \'window.location.href = this.href; return false;\';
} '; ?>