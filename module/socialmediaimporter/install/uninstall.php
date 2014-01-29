<?php
defined('PHPFOX') or exit('NO DICE!');
Phpfox::getLib('phpfox.database')->delete(Phpfox::getT('language_phrase'), 'language_id = "en" AND product_id = "socialmediaimporter" AND module_id = "socialmediaimporter"');
Phpfox::getLib('phpfox.database')->delete(Phpfox::getT('setting'), 'product_id = "socialmediaimporter" AND module_id = "socialmediaimporter"');
Phpfox::getLib('phpfox.database')->delete(Phpfox::getT('user_group_setting'), 'product_id = "socialmediaimporter" AND module_id = "socialmediaimporter"');
Phpfox::getLib('phpfox.database')->delete(Phpfox::getT('component'), 'product_id = "socialmediaimporter" AND module_id = "socialmediaimporter"');
Phpfox::getLib('phpfox.database')->delete(Phpfox::getT('block'), 'product_id = "socialmediaimporter" AND module_id = "socialmediaimporter"');
Phpfox::getLib('phpfox.database')->delete(Phpfox::getT('menu'), 'product_id = "socialmediaimporter" AND module_id = "socialmediaimporter"');
?>