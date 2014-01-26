<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

Phpfox::getService('younetpaymentgateways.classhelper')->getAPIClass('younetpaymentgateways.include.service.gatewayinterface');
class Younetpaymentgateways_Service_Younetpaymentgateways extends Phpfox_Service
{
    private $_aObject = array();
    private $_aAPIs = array();
    /**
	 * Loads a specific payment gateway API class
	 *
	 * @param string $sGateway Gateway API ID
	 * @param array $aSettings ARRAY of custom settings to pass along to the gateway class
	 * @return object Returns the object of the API gateway class
	 */
	public function load($sGateway, $aSettings = null)
	{
		if (!isset($this->_aObject[$sGateway]))
		{
			$sFilePath = PHPFOX_DIR_MODULE . 'younetpaymentgateways' . PHPFOX_DS . 'include' . PHPFOX_DS . 'service' .PHPFOX_DS . 'api' . PHPFOX_DS . $sGateway . '.class.php';
                        $aGatewaySetting = Phpfox::getService('younetpaymentgateways.gateway')->getActiveGatewaySetting($sGateway);
                        if($aGatewaySetting)
                        {
                            $this->_aObject[$sGateway] = (file_exists($sFilePath) ? Phpfox::getService('younetpaymentgateways.classhelper')->getAPI('younetpaymentgateways.include.service.api.' . $sGateway) : false);

                            if ($aSettings !== null && $this->_aObject[$sGateway] !== false)
                            {
                                    $this->_aObject[$sGateway]->set(array_merge($aSettings, $aGatewaySetting));
                            }
                        }
                        else
                        {
                            return false;
                        }
		}		
		
		return $this->_aObject[$sGateway];
	}
        
        /**
	 * Creates the API callback URL for a specific gateway.
	 *
	 * @param string $sGateway Gateway ID
	 * @return string Full path to the callback location for this specific gateway
	 */
	public function url($sGateway)
	{ 
            return Phpfox::getLib('phpfox.url')->makeUrl('younetpaymentgateways.gateway.callback', array($sGateway));
	}
        

        
}

?>
