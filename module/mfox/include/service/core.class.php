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
 * @since May 27, 2013
 * @link Mfox Api v1.0
 */
class Mfox_Service_Core extends Phpfox_Service {
    /**
     * Input data: N/A
     * 
     * Output data:
     * + sISO: string.
     * + sCountry: string.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see core/country
     * 
     * @param array $aData Array of data
     * @return array
     */
    public function country($aData)
    {
        /**
         * @var array
         */
        $aCountries = Phpfox::getService('core.country')->get();
        /**
         * @var array
         */
        $aResult = array();
        
        foreach($aCountries as $sISO => $sCountry)
        {
            $aResult[] = array(
                'sISO' => $sISO,
                'sCountry' => $sCountry
            );
        }
        
        return $aResult;
    }
    
}
