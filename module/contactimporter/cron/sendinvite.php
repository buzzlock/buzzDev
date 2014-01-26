<?php
/**
 * This cron job should be confiugure to run every hour
 */

ob_start();

define('PHPFOX', TRUE);
define('PHPFOX_NO_SESSION', TRUE);
define('PHPFOX_NO_USER_SESSION', TRUE);
define('PHPFOX_DS', DIRECTORY_SEPARATOR);

define('PHPFOX_DIR', dirname(dirname(dirname(dirname(__FILE__)))) . PHPFOX_DS);

include PHPFOX_DIR . PHPFOX_DS . 'include' . PHPFOX_DS . 'init.inc.php';

set_time_limit(15 * 60 * 60);

if (Phpfox::isModule('contactimporter'))
{
//	Phpfox::getService('contactimporter.process') -> cronSendInvite();
	Phpfox::getService('contactimporter.process') -> sendInviteInQueue();
}
?>