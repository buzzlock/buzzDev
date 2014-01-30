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
class Mfox_Service_Profile extends Phpfox_Service
{
    /**
     * Profile info.
     * 
     * Input data:
     * + iUserId: int, required.
     * 
     * @param array $aData
     * @return array
     */
    public function info($aData)
    {
        extract($aData, EXTR_SKIP);
        /**
         * @var int
         */
        $iUserId = isset($iUserId) ? (int) $iUserId : Phpfox::getUserId();
        /**
         * @var int
         */
        $iPhpfoxUserId = Phpfox::getUserId();
        /**
         * @var int
         */
        $iUserGroupId = Phpfox::getUserBy('user_group_id');
        if (!Phpfox::getService('user.privacy')->hasAccess($iUserId, 'profile.basic_info'))
		{
			return array(
                'error_code' => 1,
                'error_message' => " You don't have permission to view this profile! "
            );
		}
        if (($aUser = Phpfox::getService('user')->getUser($iUserId, 'u.user_id, u.user_name, u.full_name')) && isset($aUser['user_id']))
        {
            $iPhpfoxUserId = $aUser['user_id'];
            $iUserGroupId = $aUser['user_group_id'];
        }
        else
        {
            return array(
                'error_code' => 1,
                'error_message' => " Profile is not valid! "
            );
        }
        /**
         * @var array
         */
        $aRelations = Phpfox::getService('custom.relation')->getAll();
        /**
         * @var array
         */
        $aCustomGroups = Phpfox::getService('custom.group')->getGroups('user_profile', $iUserGroupId);
        /**
         * @var array
         */
        $aCustomFields = Phpfox::getService('custom')->getForEdit(array('user_main', 'user_panel', 'profile_panel'), $iPhpfoxUserId, $iUserGroupId, false, $iPhpfoxUserId);
        /**
         * @var array
         */
        $aGroupCache = array();
        foreach ($aCustomFields as $aFields)
        {
            $aGroupCache[$aFields['group_id']] = true;
        }
        foreach ($aCustomGroups as $iKey => $aCustomGroup)
        {
            if (!isset($aGroupCache[$aCustomGroup['group_id']]))
            {
                unset($aCustomGroups[$iKey]);
            }
        }
        /**
         * @var array
         */
        $aRebuildKeys = $aCustomGroups;
        /**
         * @var array
         */
        $aCustomGroups = array();
        $iCnt = 0;
        foreach ($aRebuildKeys as $aCustomGroup)
        {
            $aCustomGroups[$iCnt] = $aCustomGroup;
            $iCnt++;
        }
        /**
         * @var array
         */
        $aTimeZones = Phpfox::getService('core')->getTimeZones();
        /**
         * @var array
         */
        $aFullProfileInfo = Phpfox::getService('user')->get($iPhpfoxUserId, true);

        /* we could put this part inside get but I fear its being wrongly used */
        $aRelation = Phpfox::getService('custom.relation')->getLatestForUser($iPhpfoxUserId, null, true);
        $sRelation = '';
        if (isset($aRelation['status_id']))
        {
            $aFullProfileInfo = array_merge($aFullProfileInfo, $aRelation);
            $sRelation = Phpfox::getPhrase($aRelation['phrase_var_name']);
        }
        $aFullProfileInfo['month'] = substr($aFullProfileInfo['birthday'], 0, 2);
        $aFullProfileInfo['day'] = substr($aFullProfileInfo['birthday'], 2, 2);
        $aFullProfileInfo['year'] = substr($aFullProfileInfo['birthday'], 4);
        /**
         * @var array
         */
        $aProfileInfo = array();
        if (is_file(Phpfox::getParam('core.dir_pic') . 'user' . PHPFOX_DS . sprintf($aFullProfileInfo['user_image'], MAX_SIZE_OF_USER_IMAGE)))
        {
            $sUserImage = Phpfox::getParam('core.url_user') . sprintf($aFullProfileInfo['user_image'], '');
        }
        else
        {
            $sUserImage = Phpfox::getParam('core.path') . "theme/frontend/default/style/default/image/noimage/profile.png";
        }
        $aProfileInfo['BasicInfo'] = array(
            'Location' => $aFullProfileInfo['country_iso'],
            'City' => $aFullProfileInfo['city_location'],
            'Zip_Postal_Code' => $aFullProfileInfo['postal_code'],
            'Date_Of_Birth' => date('M j, Y', (int) $aFullProfileInfo['birthday_search']),
            'Gender' => ($aFullProfileInfo['gender'] == 1 ? 'Male' : 'Female'),
            'Relationship_Status' => $sRelation,
            'Forum_Signature' => $aFullProfileInfo['signature_clean'],
            'Profile_Image' => $sUserImage,
            'Display_Name' => $aFullProfileInfo['full_name']
        );
        foreach($aCustomGroups as $aGroup)
        {
            $aTemp = array();
            foreach($aCustomFields as $aField)
            {
                if ($aField['group_id'] == $aGroup['group_id'])
                {
                    if ($aField['var_type'] == 'select')
                    {
                        $aTemp[$this->changeTextForField($aField['field_name'])] = $aField['options'][$aField['customValue']]['value'];
                    }
                    else
                    {
                        $aTemp[$this->changeTextForField($aField['field_name'])] = $aField['value'];
                    }
                }
            }
            $aProfileInfo[$this->changeTextForGroup(Phpfox::getPhrase($aGroup['phrase_var_name']))] = $aTemp;
        }
        return $aProfileInfo;
    }

    /**
     * Change the text for group.
     * @param string $sText
     * @return string
     */
    public function changeTextForGroup($sText)
    {
        /**
         * @var array
         */
        $aWord = explode(' ', $sText);
        $aText = array();
        foreach($aWord as $sWord)
        {
            $aText[] = ucfirst($sWord);
        }
        return implode('_', $aText);
    }
    /**
     * Change the text for field.
     * @param string $sText
     * @return string
     */
    public function changeTextForField($sText)
    {
        /**
         * @var array
         */
        $aWord = explode('_', $sText);
        $aText = array();
        foreach($aWord as $sWord)
        {
            $aText[] = ucfirst($sWord);
        }
        return implode('_', $aText);
    }

    /**
     * 
     */
    public function getmenu()
    {

    }

}
