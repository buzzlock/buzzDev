<?php

defined('PHPFOX') or exit('NO DICE!');

if ((Phpfox::getLib('module')->getFullControllerName() != 'pages.view' )) 
{
	if (
		( Phpfox::getLib('module')->getFullControllerName() == 'core.index-member' 
			|| Phpfox::getLib('module')->getFullControllerName() == 'feed.index' 
			|| Phpfox::getLib('module')->getFullControllerName() == 'profile.index'
		)
		|| (Phpfox::getParam('core.wysiwyg') == 'default')
	) {
		if (Phpfox::isModule('wall'))
		{
				echo " <script type=\"text/javascript\"> 
				var addImg = '" . Phpfox::getLib('image.helper')->display(array('theme' => 'ajax/add.gif', 'class' => 'v_middle')) . "';
				var isDoFilterAdvWall = false; 
				var isReloadActivityFeedAdvWall = false;
				 </script> ";
					
				PhpFox::getLib('template')->setHeader(array(
					'jquery.caret.1.02.js' => 'module_wall',
					'wall.js' => 'module_wall',
					'extra.js' => 'module_wall',
					'wall.css' => 'module_wall'
						)
				);
		}
	}
}

?>



