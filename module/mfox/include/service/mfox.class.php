<?php

/**
 * @package mfox
 * @version 3.01
 */
defined('PHPFOX') or exit('NO DICE!');
/**
 * @author ductc@younetco.com
 * @package mfox
 * @subpackage mfox.service
 * @version 3.01
 * @since June 5, 2013
 * @link Mfox Api v1.0
 */
class Mfox_Service_Mfox extends Phpfox_Service {

    /**
     * @var array
     */
    protected $_aServiceNames = array();

    /**
     * @var string
     */
    static private $_sCode = NULL;
    protected $_debug = TRUE;

    /**
     * constructor
     * @return void
     */
    function __construct()
    {
        
    }

    /**
     * get all supported service api
     * @return array {name=>1}
     */
    function getAvaliableServices()
    {
        return array(
            'mfox.user' => 1,
            'mfox.message' => 1,
            'mfox.profile' => 1,
            'mfox.feed' => 1,
            'mfox.friend' => 1,
            'mfox.comment' => 1,
            'mfox.like' => 1,
            'mfox.token' => 1,
            'mfox.notification' => 1,
            'mfox.event' => 1,
            'mfox.core' => 1,
            'mfox.photo' => 1,
            'mfox.privacy' => 1,
            'mfox.report' => 1,
            'mfox.song' => 1,
            'mfox.link' => 1,
            'mfox.album' => 1,
            'mfox.video' => 1,
            'mfox.device' => 1,
            'mfox.leftnavi' => 1,
            'mfox.push' => 1,
        );
    }

    /**
     * check service name is supported
     * @param string $name
     * @return TRUE|FALSE
     */
    function hasService($name)
    {
        static $aServices;

        if (NULL == $aServices)
        {
            $aServices = $this->getAvaliableServices();
        }

        return isset($aServices[$name]) ? $aServices[$name] : 0;
    }

    /**
     * write log to file when debug mode
     */
    function log($message, $level = 'DEBUG')
    {
        // skip log debug message
        if (!$this->_debug)
        {
            return;
        }
        if (class_exists('Ynlog', FALSE))
        {
            Ynlog::write($message, $level);
            return;
        }
        /**
         * @var string
         */
        $filename = PHPFOX_DIR_FILE . '/log/mobile-phpfox-' . date('Y-m-d') . '.log';

        if (!is_string($message))
        {
            $message = var_export($message, 1);
        }

        if ($fp = fopen($filename, 'a+'))
        {
            fwrite($fp, date('Y-m-d H:i:s') . ':' . self::getCode() . PHP_EOL . $message . PHP_EOL);
            fclose($fp);
        }
    }
    /**
     * Using to get code.
     * @return string
     */
    static public function getCode()
    {
        if (NULL == self::$_sCode)
        {
            self::$_sCode = $this->getRandomString(6);
        }
        return self::$_sCode;
    }

    /**
     * @param int $len OPTIONAL default = 8
     * @return string
     */
    static public function getRandomString($len = 8)
    {
        $seek = '0123456789AWETYUIOPASDFGHJKLZXCVBNMqwertyuioppasdfghjklzxcvbnm';
        $max = strlen($seek) - 1;
        $str = '';
        for ($i = 0; $i < $len; ++$i)
        {
            $str .= substr($seek, mt_rand(0, $max), 1);
        }
        return $str;
    }

}
