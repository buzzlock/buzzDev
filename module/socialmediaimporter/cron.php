<?php
ob_start();
define('PHPFOX', true);
define('PHPFOX_NO_SESSION',true);
define('PHPFOX_NO_USER_SESSION',true);
define('PHPFOX_DS', DIRECTORY_SEPARATOR);
define('PHPFOX_DIR', dirname(dirname(dirname(__FILE__))) . PHPFOX_DS);
include PHPFOX_DIR .PHPFOX_DS.'include'.PHPFOX_DS.'init.inc.php';

set_time_limit(15*60*60);
$sService = isset($_GET['service']) ? $_GET['service'] : '';
$iUserId = isset($_GET['user_id']) ? $_GET['user_id'] : 0;
$iLimit = isset($_GET['limit']) ? $_GET['limit'] : 0;
if (Phpfox::isModule('socialmediaimporter')) 
{
	Phpfox::getService('socialmediaimporter.process')->cronImportPhoto($sService, $iUserId, $iLimit);
	echo '<br/>Success';
}
?>