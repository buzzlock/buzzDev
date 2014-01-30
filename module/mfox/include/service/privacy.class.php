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
class Mfox_Service_Privacy extends Phpfox_Service {

    /**
     * Input data: N/A
     * 
     * Output data:
     * + iListId: int.
     * + sName: string.
     * + bUsed: bool.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see privacy/getfriends
     * 
     * @param array $aData
     * @return array
     */
    public function getfriends($aData)
    {
        /**
         * @var array
         */
        $aList = Phpfox::getService('friend.list')->get();
        $aResult = array();
        foreach($aList as $aItem)
        {
            $aResult[] = array(
                'iListId' => $aItem['list_id'],
                'sName' => $aItem['name'],
                'bUsed' => $aItem['used']
            );
        }
        return $aResult;
    }
    
    /**
     * Input data:
     * + bPrivacyNoCustom: bool.
     * 
     * Output data:
     * + sPhrase: string.
     * + iValue: int.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see privacy/privacy
     * 
     * @param array $aData
     * @return array
     */
    public function privacy($aData)
    {
        /**
         * @var array
         */
        $aPrivacyControls = array();
        
        if (!Phpfox::getParam('core.friends_only_community'))
        {
            $aPrivacyControls[] = array(
                'sPhrase' => Phpfox::getPhrase('privacy.everyone'),
                'iValue' => '0'
            );
        }
        
        if (Phpfox::isModule('friend'))
        {
            $aPrivacyControls[] = array(
                'sPhrase' => Phpfox::getPhrase('privacy.friends'),
                'iValue' => '1'
            );
            $aPrivacyControls[] = array(
                'sPhrase' => Phpfox::getPhrase('privacy.friends_of_friends'),
                'iValue' => '2'
            );
        }

        $aPrivacyControls[] = array(
            'sPhrase' => Phpfox::getPhrase('privacy.only_me'),
            'iValue' => '3'
        );

        if (!isset($aData['bPrivacyNoCustom']))
        {
            $aData['bPrivacyNoCustom'] = true;
        }
        
        if (Phpfox::isModule('friend'))
        {
            if ((bool) $aData['bPrivacyNoCustom'])
            {
                $sCustomPhrase = preg_replace('/<span>(.*)<\/span>/i', '', Phpfox::getPhrase('privacy.custom_span_click_to_edit_span'));
            }
            else
            {
                $sCustomPhrase = strip_tags(Phpfox::getPhrase('privacy.custom_span_click_to_edit_span'));
            }
            $aPrivacyControls[] = array(
                'sPhrase' => $sCustomPhrase,
                'iValue' => '4'
            );
        }
        
        return $aPrivacyControls;
    }
    
    /**
     * Input data: N/A
     * 
     * Output data:
     * + sPhrase: string.
     * + iValue: int.
     * 
     * @see Mobile - API phpFox/Api V2.0
     * @see privacy/privacycomment
     * 
     * @param array $aData
     * @return array
     */
    public function privacycomment($aData)
    {
        $aData['bPrivacyNoCustom'] = true;
        
        return $this->privacy($aData);
    }

}
