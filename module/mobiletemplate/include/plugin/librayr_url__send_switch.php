<?php
if (Phpfox::isMobile()) {
	if (strpos($sUrl,'/music/album/track/setup/') !== false) {
		$sUrl .= 'method_simple';
	}
}
?>