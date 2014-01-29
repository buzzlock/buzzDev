<?php

/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

if (file_exists(PHPFOX_DIR.'module'.PHPFOX_DS.'opensocialconnect'.PHPFOX_DS.'include'.PHPFOX_DS.'service'.PHPFOX_DS.'libs'.PHPFOX_DS.'facebook.php'))
{
    require_once (PHPFOX_DIR.'module'.PHPFOX_DS.'opensocialconnect'.PHPFOX_DS.'include'.PHPFOX_DS.'service'.PHPFOX_DS.'libs'.PHPFOX_DS.'facebook.php');
}
if (file_exists(PHPFOX_DIR.'module'.PHPFOX_DS.'opensocialconnect'.PHPFOX_DS.'include'.PHPFOX_DS.'service'.PHPFOX_DS.'libs'.PHPFOX_DS.'twitter.php'))
{
    require_once (PHPFOX_DIR.'module'.PHPFOX_DS.'opensocialconnect'.PHPFOX_DS.'include'.PHPFOX_DS.'service'.PHPFOX_DS.'libs'.PHPFOX_DS.'twitter.php');
}
if (file_exists(PHPFOX_DIR.'module'.PHPFOX_DS.'opensocialconnect'.PHPFOX_DS.'include'.PHPFOX_DS.'service'.PHPFOX_DS.'libs'.PHPFOX_DS.'linkedin.php'))
{
    require_once (PHPFOX_DIR.'module'.PHPFOX_DS.'opensocialconnect'.PHPFOX_DS.'include'.PHPFOX_DS.'service'.PHPFOX_DS.'libs'.PHPFOX_DS.'linkedin.php');
}

class OpenSocialConnect_Service_Services extends Phpfox_Service
{

    const CENTRAL_SERVICE_URL = 'http://openid.younetid.com/auth/phpfox.php';

    /**
     * constants to define list of API PROVIDERS
     */
    static private $_serviceTypes = array(
        'facebook' => 'api',
        'twitter' => 'api',
        'linkedin' => 'api');

    public function __construct()
    {
        $this->_sTable = Phpfox::getT('socialconnect_services');
    }

    /**
     * get salt code string
     * @return string
     */
    private function _getSalt($iTotal = 3)
    {
        $sSalt = '';
        for ($i = 0; $i < $iTotal; $i++)
        {
            $sSalt .= chr(rand(33, 91));
        }

        return $sSalt;
    }

    /**
     *
     * get type of service
     * @example Phpfox::getService('opensocialconnect.services')->getServiceType('facebook'); // return 'api';
     * @param string $service name
     * @return string available result: api, null
     */
    function getServiceType($service)
    {
        $service = strtolower($service);
        return isset(self::$_serviceTypes[$service]) ? self::$_serviceTypes[$service] : null;
    }

    /**
     * get agent by identity and service
     * @param string $sIdentity
     * @param string $sService
     * @param string $iServiceId OPTIONAL 
     * @return array|NULL
     */
    public function getAgentByIdentityAndService($sIdentity, $sService, $iServiceId = null)
    {
        if ($sService == 'facebook')
        {
            $aAgent = $this->database()->select('sag.*')->from(Phpfox::getT('fbconnect'), 'sag')->where("fb_user_id='{$sIdentity}'")->execute('getRow');

            if ($aAgent)
            {
                return $aAgent;
            }
        }

        if (null == $iServiceId)
        {
            $aService = Phpfox::getService('opensocialconnect.providers')->getProvider($sService);
            $iServiceId = intval($aService['service_id']);
        }

        $aAgent = $this->database()->select('*')->from(Phpfox::getT('socialconnect_agents'), 'sag')->where("identity = '{$sIdentity}' AND service_id ='{$iServiceId}'")->execute('getRow');

        // data is dirty, for some reason, we  should clear to make it work faster for futher request
        if ($aAgent && !$aAgent['user_id'])
        {
            $this->database()->delete(Phpfox::getT('socialconnect_agents'), "identity='{$sIdentity}' and service_id='{$iServiceId}'");
            return false;
        }

        return $aAgent;
    }

    /**
     * add setting
     * @param string $sService
     * @param string $sParams
     * @return void
     */
    public function addSettings($sService = "", $sParams = "")
    {
        if ($sService == "")
        {
            return false;
        }
        $this->database()->update($this->_sTable, array('params' => $sParams), 'name ="'.$sService.'"');
    }

}

?>
