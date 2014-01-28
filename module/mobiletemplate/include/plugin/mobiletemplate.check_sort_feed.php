<?php
if (Phpfox::isMobile()) {
	Phpfox::getLib('template' )->assign( array('shouldShowSortFeed' => '1'));
}
?>