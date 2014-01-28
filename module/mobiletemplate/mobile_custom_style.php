<?php

include "cli.php";

@ini_set('display_startup_errors',1);
@ini_set('display_errors',1);
@ini_set('error_reporting',-1);

header("Content-type: text/css", true);

// $file = PHPFOX_DIR . '/static/css/default/default/custom.css';
// echo (file_get_contents($file));
echo Phpfox::getService('mobiletemplate') -> loadActiveMobileCustomStyle();

?> 