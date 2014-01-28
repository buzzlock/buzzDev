<?php

if (Phpfox::isMobile()) {
	Phpfox::getLib('setting')->setParam('comment.load_delayed_comments_items',false);
}

?>