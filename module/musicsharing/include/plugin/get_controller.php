<?php
if (Phpfox::isModule('musicsharing') && Phpfox::getLib('module')->getModuleName() == 'musicsharing')
{
	$sCorePath =  Phpfox::getParam('core.path');
	$jsMediaScript = '
	<link rel="stylesheet" type="text/css" href="'.$sCorePath.'module/musicsharing/static/css/default/default/mediaelementplayer.min.css" media="screen" /> 
	<link rel="stylesheet" type="text/css" href="'.$sCorePath.'module/musicsharing/static/css/default/default/mejs-audio-skins.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="'.$sCorePath.'module/musicsharing/static/css/default/default/jquery.rating.css" media="screen" />	
	<script type="text/javascript" src="'.$sCorePath.'module/musicsharing/static/jscript/mediaelement-and-player.min.js"></script>
	<script type="text/javascript" src="'.$sCorePath.'module/musicsharing/static/jscript/controller_player.js"></script>
	<script type="text/javascript" src="'.$sCorePath.'module/musicsharing/static/jscript/jquery.scrollTo-1.4.3.1-min.js"></script>
	<script type="text/javascript" src="'.$sCorePath.'module/musicsharing/static/jscript/slimScroll.min.js"></script>
	<script type="text/javascript" src="'.$sCorePath.'module/musicsharing/static/jscript/rate.js"></script>
	<script type="text/javascript" src="'.$sCorePath.'module/musicsharing/static/jscript/jquery.rating.js"></script>
	';
	$oTpl->setHeader($jsMediaScript);
	
	PhpFox::getLib('template')->setHeader(array(
		'jquery.rating.css' => 'style_css', 
			)
	);
	
}
?>
