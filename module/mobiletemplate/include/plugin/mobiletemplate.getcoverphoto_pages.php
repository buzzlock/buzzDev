<?php
if (Phpfox::isMobile()) {
    $aPage = Phpfox::getLib('template')->getVar('aPage');
	if(isset($aPage) && isset($aPage['cover_photo_id'])){
		$mtaCoverPhoto = Phpfox::getService('photo')->getCoverPhoto($aPage['cover_photo_id']);
		Phpfox::getLib('template' )->assign( array('mtaCoverPhoto' => $mtaCoverPhoto));
	}
}
?>