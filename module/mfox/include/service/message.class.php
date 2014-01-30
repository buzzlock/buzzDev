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
class Mfox_Service_Message extends Phpfox_Service {
    /**
     * Input data:
     * + iItemId: int, required.
     * 
     * Output data:
     * + result: int.
     * + error_code: int.
     * + error_message: string.
     * 
     * @see Mobile - API phpFox/Api V1.0
     * @see message/markread
     * 
     * @param array $aData
     * @return array
     */
    public function markread($aData)
    {
        /**
         * @var int
         */
        $iMessageId = isset($aData['iItemId']) ? (int) $aData['iItemId'] : 0;
        /**
         * @var array
         */
        $aMail = Phpfox::getService('mail')->getMail($iMessageId);
        if (!$aMail)
        {
            return array(
                'error_code' => 1,
                'error_message' => "Message is not valid!"
            );
        }
        if (($aMail['viewer_user_id'] != Phpfox::getUserId()) && ($aMail['owner_user_id'] != Phpfox::getUserId()))
        {
            return array(
                'error_code' => 1,
                'error_message' => Phpfox::getPhrase('mail.invalid_message')
            );
        }
        if ($aMail['viewer_user_id'] == Phpfox::getUserId())
		{
			return array('result' => Phpfox::getService('mail.process')->toggleView($aMail['mail_id'], false));
		}
        else
        {
            return array('result' => false, 'error_code' => 1, 'error_message' => 'You can not mark this message!');
        }
    }
    /**
     * Input data:
     * + iItemId: int, required.
     * 
     * Output data:
     * + result: bool.
     * + error_code: int.
     * + error_message: string.
     * 
     * @see Mobile - API phpFox/Api V1.0
     * @see message/reply
     * 
     * @param array $aData
     * @return array
     */
    public function reply($aData)
    {
        /**
         * @var int
         */
        $iItemId = isset($aData['iItemId']) ? $aData['iItemId'] : 0;
        if ($iItemId < 1)
        {
            return array(
                'error_code' => 1,
                'error_message' => " Parameter(s) is not valid! "
            );
        }
        /**
         * @var array
         */
        $aVals = array();
        /**
         * @var array
         */
        $aMail = Phpfox::getService('mail')->getMail($iItemId);
        if (($aMail['viewer_user_id'] != Phpfox::getUserId()) && ($aMail['owner_user_id'] != Phpfox::getUserId()))
        {
            return array(
                'error_code' => 1,
                'error_message' => Phpfox::getPhrase('mail.invalid_message')
            );
        }
        if (isset($aData['sText']))
        {
            $aVals['message'] = $aData['sText'];
        }
        $aVals['parent_id'] = $iItemId;
        $aVals['to'] = $aMail['owner_user_id'];
        /**
         * @var int
         */
        $iNewId = Phpfox::getService('mail.process')->add($aVals);
        if ($iNewId)
        {
            return array(
                'result' => TRUE,
                'iItemId' => $iNewId
            );
        }
        else
        {
            return array('result' => FALSE, 'message' => Phpfox_Error::get());
        }
    }
    /**
     * Input data:
     * + sUserId: string, required. Ex: "5,4,6"
     * + sSubject: string, required.
     * + sText: string, required.
     * 
     * Output data:
     * + result: bool.
     * + iItemId: int.
     * + message: string.
     * 
     * @see Mobile - API phpFox/Api V1.0
     * @see message/compose
     * 
     * @see Mail_Service_Process
     * @param array $aData
     * @return array
     */
    public function compose($aData)
    {
        $api = Phpfox::getService('mail.process');
        /**
         * @var array
         */
        $aVals = array();
        if ($aData['sUserId'])
        {
            $aVals['to'] = explode(',', $aData['sUserId']);
        }
        if (isset($aData['sSubject']))
        {
            $aVals['subject'] = $aData['sSubject'];
        }
        if (isset($aData['sText']))
        {
            $aVals['message'] = $aData['sText'];
        }
        /**
         * @var int
         */
        $iId = $api->add($aVals);
        if ($iId)
        {
            return array(
                'result' => TRUE,
                'iItemId' => $iId
            );
        }
        else
        {
            return array('result' => FALSE, 'message' => Phpfox_Error::get());
        }
    }
    /**
     * Delete message from data.
     * 
     * Input data:
     * + sType: string, required. Ex: "sentbox", "trash" or empty string.
     * + iItemId: int, required.
     * 
     * Output data:
     * + result: int.
     * + message: string.
     * 
     * @see Mobile - API phpFox/Api V1.0
     * @see message/delete
     * 
     * @param array $aData
     * @return array
     */
    public function delete($aData)
    {
        /**
         * @var string
         */
        $sType = isset($aData['sType']) ? $aData['sType'] : '';
        /**
         * @var int
         */
		$iEmailId = isset($aData['iItemId']) ? (int) $aData['iItemId'] : 0;
        /**
         * @var array
         */
        $aMail = Phpfox::getService('mail')->getMail($iEmailId);
        if (($aMail['viewer_user_id'] != Phpfox::getUserId()) && ($aMail['owner_user_id'] != Phpfox::getUserId()))
        {
            return array(
                'error_code' => 1,
                'error_message' => Phpfox::getPhrase('mail.invalid_message')
            );
        }
		if (Phpfox::getParam('mail.threaded_mail_conversation'))
		{
			Phpfox::getService('mail.process')->archiveThread($iEmailId);
		}
		else
		{
			if (($sType == 'trash' ? Phpfox::getService('mail.process')->deleteTrash($iEmailId) : Phpfox::getService('mail.process')->delete($iEmailId, ($sType == 'sentbox' ? true : false))))
			{
			}
		}
        if (Phpfox_Error::isPassed())
        {
            return array('result' => TRUE);
        }
        else
        {
            return array('result' => FALSE, 'message' => Phpfox_Error::get());
        }
    }
    /**
     * Input data:
     * + iLastItemId: int, optional.
     * + iLimit: int, optional.
     * + sAction: string, optional.
     * 
     * Output data:
     * + mail_id: int.
	 * + parent_id: int.
	 * + mass_id: int.
	 * + subject: string.
	 * + preview: string.
	 * + owner_user_id: int.
	 * + owner_folder_id: int.
	 * + owner_type_id: int.
	 * + viewer_user_id: int.
	 * + viewer_folder_id: int.
	 * + viewer_type_id: int.
	 * + viewer_is_new: int.
	 * + time_stamp: int.
	 * + time_updated: int.
	 * + total_attachment: int.
	 * + text_reply: string.
	 * + text: string.
	 * + owner_profile_page_id: int.
	 * + user_owner_server_id: int.
	 * + owner_user_name: string.
	 * + owner_full_name: string.
	 * + owner_gender: int.
	 * + owner_user_image: string.
	 * + owner_is_invisible: bool.
	 * + owner_user_group_id: int.
	 * + owner_language_id: int.
	 * + viewer_profile_page_id: int.
	 * + viewer_server_id: int.
	 * + viewer_user_name: int.
	 * + viewer_full_name: string.
	 * + viewer_gender: int.
	 * + viewer_user_image: string.
	 * + viewer_is_invisible: bool.
	 * + viewer_user_group_id: int.
	 * + viewer_language_id: int.
     * + owner_user_image: string.
     * + viewer_user_image: string.
     * + Time: string.
     * 
     * @see Mobile - API phpFox/Api V1.0
     * @see message/inbox
     * 
     * @param array $aData
     * @return array
     */
    public function inbox($aData)
    {
        /**
         * @var int
         */
        $iLastItemId = isset($aData['iLastItemId']) ? (int) $aData['iLastItemId'] : 0;
        /**
         * @var int
         */
        $iLimit = isset($aData['iLimit']) ? (int) $aData['iLimit'] : 10;
        /**
         * @var string
         */
        $sAction = isset($aData['sAction']) ? $aData['sAction'] : '';
        $this->database()
                ->select('m.*, mreply.text AS text_reply,mt.text AS text, ' . Phpfox::getUserField('u', 'owner_') . ', ' . Phpfox::getUserField('u2', 'viewer_'))
                ->from(Phpfox::getT('mail'), 'm')
                ->join(Phpfox::getT('mail_text'), 'mt', 'mt.mail_id = m.mail_id')
                ->leftjoin(Phpfox::getT('user'), 'u', 'u.user_id = m.owner_user_id')
                ->join(Phpfox::getT('user'), 'u2', 'u2.user_id = m.viewer_user_id')
                ->leftJoin(Phpfox::getT('mail_text'), 'mreply', 'mreply.mail_id = m.parent_id');
        switch ($sAction) {
            case 'sent':
                $this->database()->where('m.owner_user_id = ' . Phpfox::getUserId() . ' AND m.owner_type_id = 0' . ($iLastItemId ? ' AND m.mail_id < ' . (int) $iLastItemId : ''));
                break;
            case 'trash':
                $this->database()->where('((m.viewer_user_id = ' . Phpfox::getUserId() . ' AND m.viewer_type_id = 1) OR (m.owner_user_id = ' . Phpfox::getUserId() . ' AND m.owner_type_id = 1))' . ($iLastItemId ? ' AND m.mail_id < ' . (int) $iLastItemId : ''));
                break;
            case 'inbox':
            default:
                $this->database()->where(('m.viewer_user_id = ' . Phpfox::getUserId() . ' AND m.viewer_type_id =0 ' . ($iLastItemId ? 'AND m.mail_id < ' . (int) $iLastItemId : '') . ''));
                break;
        }
        /**
         * @var array
         */
        $aRows = $this->database()->order('m.mail_id DESC')
                ->limit((int) $iLimit)
                ->execute('getSlaveRows');
        foreach ($aRows as $index => $aRow)
        {
            if ($aRow['owner_user_image'])
            {
                $aRows[$index]['owner_user_image'] = Phpfox::getParam('core.url_user') . sprintf($aRow['owner_user_image'], '_50_square');
            }
            else
            {
                $aRows[$index]['owner_user_image'] = Phpfox::getParam('core.path') . "theme/frontend/default/style/default/image/noimage/profile_50.png";
            }
            if ($aRow['viewer_user_image'])
            {
                $aRows[$index]['viewer_user_image'] = Phpfox::getParam('core.url_user') . sprintf($aRow['viewer_user_image'], '_50_square');
            }
            else
            {
                $aRows[$index]['viewer_user_image'] = Phpfox::getParam('core.path') . "theme/frontend/default/style/default/image/noimage/profile_50.png";
            }
            $aRows[$index]['Time'] = Phpfox::getLib('date')->convertTime((int) $aRow['time_stamp']);
        }
        return $aRows;
    }
    /**
     * Input data:
     * + iItemId: int, required.
     * 
     * Output data:
	 * + parent_id: int.
	 * + mass_id: int.
	 * + subject: string.
	 * + preview: string.
	 * + owner_user_id: int.
	 * + owner_folder_id: int.
	 * + owner_type_id: int.
	 * + viewer_user_id: int.
	 * + viewer_folder_id: int.
	 * + viewer_type_id: int.
	 * + viewer_is_new: int.
	 * + time_stamp: int.
	 * + time_updated: int.
	 * + total_attachment: int.
	 * + text_reply: string.
	 * + text: string.
	 * + owner_profile_page_id: int.
	 * + user_owner_server_id: int.
	 * + owner_user_name: string.
	 * + owner_full_name: string.
	 * + owner_gender: int.
	 * + owner_user_image: string.
	 * + owner_is_invisible: bool.
	 * + owner_user_group_id: int.
	 * + owner_language_id: int.
	 * + viewer_profile_page_id: int.
	 * + viewer_server_id: int.
	 * + viewer_user_name: int.
	 * + viewer_full_name: string.
	 * + viewer_gender: int.
	 * + viewer_user_image: string.
	 * + viewer_is_invisible: bool.
	 * + viewer_user_group_id: int.
	 * + viewer_language_id: int.
     * 
     * @see Mobile - API phpFox/Api V1.0
     * @see message/detail
     * 
     * @param array $aData
     * @return array
     */
    public function detail($aData)
    {
        /**
         * @var int
         */
        $iItemId = isset($aData['iItemId']) ? (int) $aData['iItemId'] : 0;
        /**
         * @var array
         */
        $aRow = Phpfox::getService('mail')->getMail($iItemId, TRUE);
        if (($aRow['viewer_user_id'] != Phpfox::getUserId()) && ($aRow['owner_user_id'] != Phpfox::getUserId()))
        {
            return array(
                'error_code' => 1,
                'error_message' => Phpfox::getPhrase('mail.invalid_message')
            );
        }
        if ($aRow['owner_user_image'])
        {
            $aRows[$index]['owner_user_image'] = Phpfox::getParam('core.url_user') . sprintf($aRow['owner_user_image'], MAX_SIZE_OF_USER_IMAGE);
        }
        else
        {
            $aRows[$index]['owner_user_image'] = Phpfox::getParam('core.path') . "theme/frontend/default/style/default/image/noimage/profile_50.png";
        }
        if ($aRow['viewer_user_image'])
        {
            $aRows[$index]['viewer_user_image'] = Phpfox::getParam('core.url_user') . sprintf($aRow['viewer_user_image'], MAX_SIZE_OF_USER_IMAGE);
        }
        else
        {
            $aRows[$index]['viewer_user_image'] = Phpfox::getParam('core.path') . "theme/frontend/default/style/default/image/noimage/profile_50.png";
        }
        $aRow['Time'] = Phpfox::getLib('date')->convertTime((int) $aRow['time_stamp']);
        return $aRow;
    }

    /**
     * Input data:
     * + iLastItemId: int, optional.
     * + iLimit: int, optional.
     * 
     * Output data:
     * + mail_id: int.
	 * + parent_id: int.
	 * + mass_id: int.
	 * + subject: string.
	 * + preview: string.
	 * + owner_user_id: int.
	 * + owner_folder_id: int.
	 * + owner_type_id: int.
	 * + viewer_user_id: int.
	 * + viewer_folder_id: int.
	 * + viewer_type_id: int.
	 * + viewer_is_new: int.
	 * + time_stamp: int.
	 * + time_updated: int.
	 * + total_attachment: int.
	 * + text_reply: string.
	 * + text: string.
	 * + owner_profile_page_id: int.
	 * + user_owner_server_id: int.
	 * + owner_user_name: string.
	 * + owner_full_name: string.
	 * + owner_gender: int.
	 * + owner_user_image: string.
	 * + owner_is_invisible: bool.
	 * + owner_user_group_id: int.
	 * + owner_language_id: int.
	 * + viewer_profile_page_id: int.
	 * + viewer_server_id: int.
	 * + viewer_user_name: int.
	 * + viewer_full_name: string.
	 * + viewer_gender: int.
	 * + viewer_user_image: string.
	 * + viewer_is_invisible: bool.
	 * + viewer_user_group_id: int.
	 * + viewer_language_id: int.
     * + owner_user_image: string.
     * + viewer_user_image: string.
     * + Time: string.
     * 
     * @see Mobile - API phpFox/Api V1.0
     * @see message/sent
     * 
     * @param array $aData
     * @return array
     */
    public function sent($aData)
    {
        $aData['sAction'] = 'sent';
        return $this->inbox($aData);
    }
    
    /**
     * Input data:
     * + iLastItemId: int, optional.
     * + iLimit: int, optional.
     * 
     * Output data:
     * + mail_id: int.
	 * + parent_id: int.
	 * + mass_id: int.
	 * + subject: string.
	 * + preview: string.
	 * + owner_user_id: int.
	 * + owner_folder_id: int.
	 * + owner_type_id: int.
	 * + viewer_user_id: int.
	 * + viewer_folder_id: int.
	 * + viewer_type_id: int.
	 * + viewer_is_new: int.
	 * + time_stamp: int.
	 * + time_updated: int.
	 * + total_attachment: int.
	 * + text_reply: string.
	 * + text: string.
	 * + owner_profile_page_id: int.
	 * + user_owner_server_id: int.
	 * + owner_user_name: string.
	 * + owner_full_name: string.
	 * + owner_gender: int.
	 * + owner_user_image: string.
	 * + owner_is_invisible: bool.
	 * + owner_user_group_id: int.
	 * + owner_language_id: int.
	 * + viewer_profile_page_id: int.
	 * + viewer_server_id: int.
	 * + viewer_user_name: int.
	 * + viewer_full_name: string.
	 * + viewer_gender: int.
	 * + viewer_user_image: string.
	 * + viewer_is_invisible: bool.
	 * + viewer_user_group_id: int.
	 * + viewer_language_id: int.
     * + owner_user_image: string.
     * + viewer_user_image: string.
     * + Time: string.
     * 
     * @see Mobile - API phpFox/Api V1.0
     * @see message/trash
     * 
     * @param array $aData
     * @return array
     */
    public function trash($aData)
    {
        $aData['sAction'] = 'trash';
        return $this->inbox($aData);
    }

}
