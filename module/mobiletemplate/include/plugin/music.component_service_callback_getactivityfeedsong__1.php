<?php

$tmpVar = '';

if (Phpfox::isMobile()) 
{
	$aReturn['feed_image_onclick'] = 'window.location.href = this.href; return false;';
}

?>