<?php

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

define('PHPFOX_DIR', dirname(dirname(dirname(__FILE__))) . PHPFOX_DS);
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

/**
 * start init process.
 */
include PHPFOX_DIR . '/include/init.inc.php';
header("Content-type: text/css", true);
echo Phpfox::getService('mfox.style') -> getCustomCss();
