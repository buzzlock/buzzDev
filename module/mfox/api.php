<?php

ob_start();

/**
 * Key to include phpFox
 *
 */
define('PHPFOX', true);

/**
 * Directory Seperator
 *
 */
define('PHPFOX_DS', DIRECTORY_SEPARATOR);

define('MAX_SIZE_OF_USER_IMAGE', '_50_square');
define('MAX_SIZE_OF_USER_IMAGE_EVENT', '_50');
define('MAX_SIZE_OF_USER_IMAGE_PHOTO', '_150');

/**
 * phpFox Root Directory
 *
 */
define('PHPFOX_DIR', dirname(dirname(dirname(__FILE__))) . PHPFOX_DS);
// Require phpFox Init

/**
 * skip check post token
 * @see ./include/library/phpfox/phpfox.class.php
 */
define('PHPFOX_NO_CSRF', TRUE);

/**
 * @var bool
 */
define('PHPFOX_IS_AJAX', TRUE);

/**
 * skip save page
 * @see ./include/library/phpfox/phpfox.class.php
 */
define('PHPFOX_DONT_SAVE_PAGE', TRUE);

/**
 * @see ./include/init.inc.php: PHPFOX_NO_PLUGINS
 * skip plugins
 */
define('PHPFOX_NO_PLUGINS', TRUE);

/**
 * @see ./include/init.inc.php: PHPFOX_NO_SESSION
 * skip session init
 */
define('PHPFOX_NO_SESSION', TRUE);

/**
 * @see ./include/init.inc.php: PHPFOX_NO_USER_SESSION
 *
 */
define('PHPFOX_NO_USER_SESSION', TRUE);

defined('PHPFOX_MOBILE_MODE') or define('PHPFOX_MOBILE_MODE',TRUE);

/**
 * start init process.
 */
include PHPFOX_DIR . '/include/init.inc.php';

include PHPFOX_DIR . '/module/mfox/include/library/ynlog.php';

// nothing for some issue.
if (function_exists('ini_set'))
{
	ini_set('display_startup_errors', 1);
	ini_set('display_errors', 1);
	error_reporting(E_ERROR);
}

/**
 * set error handler
 */
set_error_handler(array(
	'Ynlog',
	'handleError'
));

/**
 * set exception handler
 */
set_exception_handler(array(
	'Ynlog',
	'handleException'
));
/**
 * Register the shutdown PHP script function.
 * If there is a fatal error, this function will clear all buffer and return the error json.
 */
register_shutdown_function(array(
	'Ynlog',
	'handeShutdown'
));

/**
 * @var string
 */
define('MFOX_TOKEN_KEY','token');

define('MFOX_TOKEN_KEY_HTTP','HTTP_TOKEN');

/**
 * set shutdown function
 */
// register_shutdown_function(array('Ynlog','handleShutdown'));

$sUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';

$sUri = trim($sUri, '/');

if ($pos = strpos($sUri, '.php'))
{
	$sUri = substr($sUri, $pos + 5);
}

$requestMethod = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
$actionType = 1;
$sMethod =  'get';

/**
 * generate data
 */
$aData = $_GET + $_POST;
$iId = NULL;

if (preg_match("#^(\w+)\/(\d+)#", $sUri, $matches))
{
	$actionType =  1;
	$sService = $matches[1];
	$sMethod = strtolower($requestMethod) . 'ByIdAction';
	$iId = $matches[2];
}
else
if (preg_match("#^(\w+)\/(\w+)#", $sUri, $matches))
{
	$actionType =  2;
	$sService = $matches[1];
	$sMethod = $matches[2];
	$iId = NULL;
}
else if (preg_match("#^(\w+)#", $sUri, $matches))
{
	$actionType = 3;
	$sService = $matches[1];
	$sMethod = strtolower($requestMethod) . 'Action';
	$iId = NULL;
}

$sService = str_replace('/', '.', 'mfox/' . $sService);

$isResful = FALSE;

if (!Phpfox::isModule('mfox'))
{
    echo json_encode(array(
		'error_code' => 1,
		'error_message' => "Module Mfox is not available!"
	));
    die;
}

$mFox = Phpfox::getService('mfox');

$oService = NULL;

Phpfox::getService('user.auth') -> setUserId(0);

if (!$mFox -> hasService($sService))
{
	echo json_encode(array(
		'error_code' => 1,
		'error_message' => "Invalid service [{$sService}] request URI [{$sUri}]"
	));
    die;
}
else
{
	// Call the service.
	$oService = Phpfox::getService($sService);
}

global $token;

if (isset($aData[MFOX_TOKEN_KEY]))
{
	$token = $aData[MFOX_TOKEN_KEY];
}
else
if (isset($_SERVER[MFOX_TOKEN_KEY_HTTP]))
{
	$token = $_SERVER[MFOX_TOKEN_KEY_HTTP];
}
else
if (function_exists('apache_request_headers'))
{
	$headers = apache_request_headers();

	if (isset($headers[MFOX_TOKEN_KEY]))
	{
		$token  =  $headers[MFOX_TOKEN_KEY];
	}

	$key = strtolower(MFOX_TOKEN_KEY);

	if (isset($headers[MFOX_TOKEN_KEY]))
	{
		$token  =  $headers[$key];
	}
}

$mFox -> log($aData);

/**
 * check if token is exsits.
 */
if (($sService != 'token') && ( $sService != 'user' && $sMethod != 'login' &&  $sMethod != 'register' && $sMethod != 'forgot'))
{
	extract($aData, EXTR_SKIP);

	$aResult = Phpfox::getService('mfox.token') -> isValid($token);

	// Is not valid.
	if (count($aResult) > 0)
	{
		echo json_encode($aResult);

		ob_end_flush();
		die ;
	}
}

// verify token at first.
if ($token)
{
	$aToken = Phpfox::getService('mfox.token') -> getToken($token);
	
		
	if ($aToken && isset($aToken['user_id']))
	{
		$iViewerId  =  (int)$aToken['user_id'];
		
		$oAuth = Phpfox::getService('user.auth') ; 
		$oAuth -> setUserId($iViewerId);
		$aUser = Phpfox::getService('user')->get($iViewerId);
		$oAuth->setUser($aUser);
	}
	
	$mFox->log(array('iViewerId'=>Phpfox::isUser()));
}

$aResult = $oService -> {$sMethod}($aData, $iId);


ob_start();
$content = json_encode($aResult);

$mFox -> log($content);
ob_get_clean();

echo $content;
exit(0);
