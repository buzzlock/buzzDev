<?php

defined('PHPFOX') or exit('NO DICE!');

if (!Phpfox::isMobile() && Phpfox::isUser() && !Phpfox::isAdminPanel() && Phpfox::isModule('notification'))
{
        PhpFox::getLib('template')->setHeader(array(
            'fanot.js' => 'module_fanot', 
			'fanot.css' => 'module_fanot'
                )
        );
}

?>



