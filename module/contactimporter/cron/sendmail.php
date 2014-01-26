<?php
/**
 * This cron job should be confiugure to run every hour
 */

ob_start();

define('PHPFOX', true);

define('PHPFOX_NO_SESSION', true);

define('PHPFOX_NO_USER_SESSION', true);

define('PHPFOX_DS', DIRECTORY_SEPARATOR);

define('PHPFOX_DIR', dirname(dirname(dirname(dirname(__FILE__)))) . PHPFOX_DS);

include PHPFOX_DIR . PHPFOX_DS . 'include' . PHPFOX_DS . 'init.inc.php';

set_time_limit(15 * 60 * 60);

if (Phpfox::isModule('contactimporter'))
{
	Phpfox::getService('contactimporter.process') -> cronSendMail();
}