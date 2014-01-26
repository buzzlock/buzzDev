<?php

include "cli.php";

@ini_set('display_startup_errors',1);
@ini_set('display_errors',1);
@ini_set('error_reporting',-1);

Phpfox::getService('birthdayreminder.birthdayreminder')->createBirthdayEvent();
Phpfox::getService('birthdayreminder.birthdayreminder')->sendMail();
