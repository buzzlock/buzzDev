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
 * @link Mfox Api v2.0
 */
class Mfox_Service_Report extends Phpfox_Service {

    /**
     * Input data:
     * + sType: string, required.
     * 
     * Output data:
     * + iReportId: int.
     * + sMessage: string.
     * 
     * @see Mobile - API phpFox/Api V3.0
     * @see report/reason
     * 
     * @param array $aData
     * @return array
     */
    public function reason($aData)
    {
        /**
         * @var string
         */
        $sType = isset($aData['sType']) ? $aData['sType'] : '';
        /**
         * @var array
         */
        $aReasons = Phpfox::getService('report')->getOptions($sType);
        /**
         * @var array
         */
        $aResult = array();
        foreach($aReasons as $i => $aReason)
        {
            $aMatches = null;
            preg_match('/\{phrase var\=&#039;(.*)&#039;\}/ise', $aReason['message'], $aMatches);
            
            $aResult[] = array(
                'iReportId' => $aReason['report_id'],
                'sMessage' => isset($aMatches[1]) ? Phpfox::getPhrase($aMatches[1]) : $aReasons[$i]['message']
            );
        }
        return $aResult;
    }
 
    /**
     * Input data:
     * + iItemId: int, required.
     * + sType: string, required. Ex: photo, photo_album, music_song, music_album, video, user_status.
     * + sFeedback: string, optional.
     * + iReport: int, required.
     * + sModule: string, optional.
     * + iItem: int, optional.
     * 
     * - 1: Nudity or Pornography
     * - 2: Drug Use
     * - 3: Violence
     * - 4: Attacks Individual or Group
     * - 5: Copyright Infringement
     * 
     * Output data:
     * + error_code: int.
     * + error_message: string.
     * + result: int.
     * + message: string.
     * 
     * @see Mobile - API phpFox/Api V3.0
     * @see report/add
     * 
     * @param array $aData
     * @return array
     */
    public function add($aData)
    {
        /**
         * @var int
         */
        $iItemId = isset($aData['iItemId']) ? (int) $aData['iItemId'] : 0;
        /**
         * @var string
         */
        $sType = isset($aData['sType']) ? $aData['sType'] : '';
        /**
         * @var string
         */
        $sFeedback = isset($aData['sFeedback']) ? $aData['sFeedback'] : '';
        /**
         * @var int
         */
        $iReport = isset($aData['iReport']) ? (int) $aData['iReport'] : 0;
        /**
         * @var string Using in page.
         */
        $sModule = isset($aData['sModule']) ? $aData['sModule'] : '';
        /**
         * @var int Using in page.
         */
        $iItem = isset($aData['iItem']) ? (int) $aData['iItem'] : 0;
        
        /**
         * @var object
         */
        $oReport = Phpfox::getService('report');
        /**
         * @var array
         */
        $aVals = array(
            'type' => $sType,
            'id' => $iItemId
        );

        if (!Phpfox::getLib('parse.format')->isEmpty($sFeedback))
        {
            $aVals['feedback'] = $sFeedback;
        }
        else
        {
            $aVals['feedback'] = '';
            /**
             * @var array
             */
            $aReasons = $oReport->getOptions($aVals['type']);
            $aReasonId = array();
            foreach ($aReasons as $aReason)
            {
                $aReasonId[$aReason['report_id']] = $aReason['report_id'];
            }

            if (!isset($aReasonId[$iReport]))
            {
                return array(
                    'error_code' => 1,
                    'error_message' => " Reason is not valid! "
                );
            }
        }
        $aVals['report'] = $iReport > 0 ? $iReport : '';
        
        // Check privacy.
        $sType = $this->changeType($sType);
        
        switch ($sType) {
            case 'photo':
                $aError = Phpfox::getService('mfox.photo')->checkPrivacyOnPhoto($iItemId);
                break;
            
            case 'photo_album':
                $aError = Phpfox::getService('mfox.photo')->checkPrivacyOnAlbum($iItemId);
                break;
            
            case 'music_song':
                $aError = Phpfox::getService('mfox.song')->checkPrivacyOnSong($iItemId);
                break;
            
            case 'music_album':
                $aError = Phpfox::getService('mfox.album')->checkPrivacyOnMusicAlbum($iItemId);
                break;
            
            case 'video':
                $aError = Phpfox::getService('mfox.video')->checkPrivacyOnVideo($iItemId, $sModule, $iItem);
                break;
            
            case 'user_status':
                $aError = Phpfox::getService('mfox.feed')->checkPrivacyOnUserStatusFeed($iItemId, $sType, $sModule, $iItem);
                break;
            default:
                
                break;
        }
        
        if (isset($aError))
        {
            return $aError;
        }
        
        if ($oReport->canReport($aVals['type'], $aVals['id']))
        {
            if ($bResult = Phpfox::getService('report.data.process')->add($aVals['report'], $aVals['type'], $aVals['id'], $aVals['feedback']))
            {
                return array(
                    'result' => $bResult,
                    'message' => "Report successfully!"
                );
            }
            else
            {
                return array(
                    'error_code' => 1,
                    'error_message' => Phpfox_Error::get()
                );
            }
        }
        else
        {
            return array(
                'error_code' => 1,
                'error_message' => Phpfox::getPhrase('report.you_have_already_reported_this_item')
            );
        }
    }
    
    /**
     * Change the type if needed.
     * @param string $sType
     * @return string
     */
    public function changeType($sType)
    {
        switch ($sType) {
            case 'feed_mini':
                break;

            default:
                break;
        }
        
        return $sType;
    }
}
