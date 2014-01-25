<?php
defined('PHPFOX') or exit('NO DICE!');
Phpfox::getLib('phpfox.database')->delete(Phpfox::getT('block'), 'product_id = "younetcore" AND module_id = "younetcore"');
Phpfox::getLib('phpfox.database')->delete(Phpfox::getT('menu'), 'product_id = "younetcore" AND module_id = "younetcore"');
?>