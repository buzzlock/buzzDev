<?php

include "cli.php";

@ini_set('display_startup_errors',1);
@ini_set('display_errors',1);
@ini_set('error_reporting',-1);

Phpfox::getService('advancedmarketplace.process')->sendExpireNotifications();
