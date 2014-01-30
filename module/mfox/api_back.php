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
define('PHPFOX_IS_AJAX', FALSE);

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

$sService = $sMethod = NULL;

if ($pos = strrpos($sUri, '/'))
{
	$sService = substr($sUri, 0, $pos);
	$sMethod = substr($sUri, $pos + 1);
}
elseif ($pos = strrpos($sUri, '?'))
{
    $sService = substr($sUri, 0, $pos);
}
else
{
	$sService = $sUri;
}

$sService = str_replace('/', '.', 'mfox/' . $sService);

$isResful = FALSE;

$requestMethod = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';

$resfulMethod = strtolower($requestMethod) . 'Action';

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

	if ($sMethod == NULL OR !method_exists($oService, $sMethod))
	{
		$isResful = TRUE;
	}
}

/**
 * generate data
 */
$aData = $_GET + $_POST;

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

/**
 * check if token is exsits.
 */
if ($sService != 'user' && $sMethod != 'login')
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

$mFox -> log($aData);

// verify token at first.
if ($token)
{
	$aToken = Phpfox::getService('mfox.token') -> getToken($token);

	if ($aToken && isset($aToken['user_id']))
	{
		Phpfox::getService('user.auth') -> setUserId((int)$aToken['user_id']);
	}
}

ob_start();

if ($isResful)
{
	$aResult = $oService -> {$resfulMethod}($aData);
}
else
{
	$aResult = $oService -> {$sMethod}($aData);
}

$content = json_encode($aResult);

$mFox -> log($content);

echo $content;

exit(0);
