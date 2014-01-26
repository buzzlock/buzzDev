<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');
/**
 * 
 * 
 * @copyright       [YOUNET_COPYRIGHT]
 * @author          YouNet Company
 * @package         YouNet_Wall
 */

class Wall_Service_User extends Phpfox_Service
{
    public function updateStatus($aVals)
    {
        if (Phpfox::getLib('parse.format')->isEmpty($aVals['user_status']))
        {
            return Phpfox_Error::set(Phpfox::getPhrase('user.add_some_text_to_share'));
        }                
        
        if (!Phpfox::getService('ban')->checkAutomaticBan($aVals['user_status']))
        {
            return false;
        }

        $sStatus = Phpfox::getService('wall.process')->compile($aVals['user_status'], $aVals['tagging']);//$this->preParse()->prepare($aVals['user_status']);
        
        $aUpdates = $this->database()->select('content')
            ->from(Phpfox::getT('user_status'))
            ->where('user_id = ' . (int) Phpfox::getUserId())
            ->limit(Phpfox::getParam('user.check_status_updates'))
            ->order('time_stamp DESC')
            ->execute('getSlaveRows');
            
        $iReplications = 0;
        foreach ($aUpdates as $aUpdate)
        {
            if ($aUpdate['content'] == $sStatus)
            {
                $iReplications++;
            }
        }
            
        if ($iReplications > 0)
        {
            return Phpfox_Error::set(Phpfox::getPhrase('user.you_have_already_added_this_recently_try_adding_something_else'));            
        }
        
        if (empty($aVals['privacy']))
        {
            $aVals['privacy'] = 0;
        }       

        if (empty($aVals['privacy_comment']))
        {
            $aVals['privacy_comment'] = 0;
        }
        
        $aInsert = array(
                'user_id' => (int) Phpfox::getUserId(),
                'privacy' => $aVals['privacy'],
                'privacy_comment' => $aVals['privacy_comment'],
                'content' => $sStatus,
                'time_stamp' => PHPFOX_TIME
            );
        if (isset($aVals['location']) && isset($aVals['location']['latlng']) && !empty($aVals['location']['latlng']))
        {
            $aMatch = explode(',',$aVals['location']['latlng']);
            $aMatch['latitude'] = floatval($aMatch[0]);
            $aMatch['longitude'] = floatval($aMatch[1]);
            $aInsert['location_latlng'] = json_encode(array('latitude' => $aMatch['latitude'], 'longitude' => $aMatch['longitude']));
        }
        if (isset($aInsert['location_latlng']) && !empty($aInsert['location_latlng']) && isset($aVals['location']) && isset($aVals['location']['name']) && !empty($aVals['location']['name']))
        {
            $aInsert['location_name'] = Phpfox::getLib('parse.input')->clean($aVals['location']['name']);
        }
        $iStatusId = $this->database()->insert(Phpfox::getT('user_status'), $aInsert);              
        
        if($iStatusId && !empty($aVals['tagging']))
        {
            $aTagging = json_decode($aVals['tagging'], true);
            $notified = array();
            foreach($aTagging as $iUserId => $aInfo)
            {
                if(in_array($iUserId, $notified))
                    continue;
                // Send notification
                Phpfox::getService('notification.process')->add('wall_status', $iStatusId, $iUserId);
                $notified[] = $iUserId;
            }
			/*
			foreach($aTagging as $iUserId => $aInfo)
            {
				Phpfox::getService('feed.process')->add('user_status', $iStatusId, $aVals['privacy'], $aVals['privacy_comment'], $iUserId, Phpfox::getUserId());
			}
			*/
        }

        if (Phpfox::isModule('tag') && Phpfox::getParam('tag.enable_hashtag_support'))
        {
            Phpfox::getService('tag.process')->add('user_status', $iStatusId, Phpfox::getUserId(), $sStatus, true);
        }

        (($sPlugin = Phpfox_Plugin::get('user.service_process_add_updatestatus')) ? eval($sPlugin) : false);        

        (($sPlugin = Phpfox_Plugin::get('user.service_process_add_updatestatus_end')) ? eval($sPlugin) : false);
        
        return Phpfox::getService('feed.process')->add('user_status', $iStatusId, $aVals['privacy'], $aVals['privacy_comment']);
    }
}