<?php
if (Phpfox::isMobile()) {
	$varThree = Phpfox::getLib('request')->get('req3');
	$fullControllerName = Phpfox::getLib('module')->getFullControllerName();
	
	if($fullControllerName != 'pages.view' && isset($varThree) && $varThree == 'add-comment'){
		Phpfox::getLib('template' )->assign( array('isHideProfileHeader' => 'yes'));	
	} else if($fullControllerName == 'pages.view'){
		$commentID = Phpfox::getLib('request')->get('comment-id');
		$varFour = Phpfox::getLib('request')->get('req4');
		if(isset($commentID) && isset($varFour) && $varFour == 'add-comment'){
			Phpfox::getLib('template' )->assign( array('isHideProfileHeader' => 'yes'));
		}
	} else if($fullControllerName == 'event.view'){
		$commentID = Phpfox::getLib('request')->get('comment-id');
		$varFive = Phpfox::getLib('request')->get('req5');
		if(isset($commentID) && isset($varFive) && $varFive == 'add-comment'){
			Phpfox::getLib('template' )->assign( array('isHideProfileHeader' => 'yes'));
		}
	}
	
	
}
?>