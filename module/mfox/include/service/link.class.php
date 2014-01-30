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
 * @link Mfox Api v2.0
 */

class Mfox_Service_Link extends Phpfox_Service {
    /**
     * Input data:
     * + sLink: string, required.
     * 
     * Output data:
     * + sLink: string.
     * + sTitle: string.
     * + sDescription: string.
     * + sDefaultImage: string.
     * + sEmbedCode: string.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see link/preview
     * 
     * @param array $aData
     * @return array
     */
    public function preview($aData)
    {
        /**
         * @var string
         */
        $sLink = isset($aData['sLink']) ? $aData['sLink'] : '';
        if (empty($sLink))
        {
            return array(
                'error_message' => ' Parameter is not valid! ',
                'error_code' => 1
            );
        }
        /**
         * @var array
         */
        $aLink = Phpfox::getService('link')->getLink($aData['sLink']);
        if ($aLink)
        {
            return array(
                'sLink' => $aLink['link'],
                'sTitle' => $aLink['title'],
                'sDescription' => $aLink['description'],
                'sDefaultImage' => $aLink['default_image'],
                'sEmbedCode' => $aLink['embed_code']
            );
        }
        else
        {
            return array();
        }
    }
    
}
