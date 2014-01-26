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

class Wall_Service_Link extends Phpfox_Service
{    
    public function add($aVals, $bIsCustom = false, $aCallback = null)
    {
        if (!defined('PHPFOX_FORCE_IFRAME'))
        {
            define('PHPFOX_FORCE_IFRAME', true);
        }
        
        if (empty($aVals['privacy_comment']))
        {
            $aVals['privacy_comment'] = 0;
        }    

        if (empty($aVals['privacy']))
        {
            $aVals['privacy'] = 0;
        }            
        Phpfox_Error::skip(true);
        
        $iId = $this->database()->insert(Phpfox::getT('link'), array(
                'user_id' => Phpfox::getUserId(),
                'is_custom' => ($bIsCustom ? '1' : '0'),
                'module_id' => ($aCallback === null ? null : $aCallback['module']),
                'item_id' => ($aCallback === null ? 0 : $aCallback['item_id']),
                'parent_user_id' => (isset($aVals['parent_user_id']) ? (int) $aVals['parent_user_id'] : 0),
                'link' => $this->preParse()->clean($aVals['link']['url'], 255),
                'image' => (isset($aVals['link']['image_hide']) && $aVals['link']['image_hide'] == '1' ? null : !empty($aVals['link']['image']) ? $this->preParse()->clean($aVals['link']['image'], 255) : null),
                'title' => $this->preParse()->clean($aVals['link']['title'], 255),
                'description' => !empty($aVals['link']['description']) ? $this->preParse()->clean($aVals['link']['description'], 200) : '',
                'status_info' => (empty($aVals['status_info']) ? null : Phpfox::getService('wall.process')->compile($aVals['status_info'], $aVals['tagging'])),
                'privacy' => (int) $aVals['privacy'],
                'privacy_comment' => (int) $aVals['privacy_comment'],
                'time_stamp' => PHPFOX_TIME,
                'has_embed' => (empty($aVals['link']['embed_code']) ? '0' : '1')
            )
        );
        Phpfox_Error::skip(false);
        if($iId && !empty($aVals['tagging']))
        {
            $aTagging = json_decode($aVals['tagging'], true);
            $notified = array();
            foreach($aTagging as $iUserId => $aInfo)
            {
                if(in_array($iUserId, $notified))
                    continue;
                // Send notification
                Phpfox::getService('notification.process')->add('wall_walllink', $iId, $iUserId);
                $notified[] = $iUserId;
            }
        }

        if (!empty($aVals['link']['embed_code']))
        {
            $this->database()->insert(Phpfox::getT('link_embed'), array(
                    'link_id' => $iId,
                    'embed_code' => $this->preParse()->prepare($aVals['link']['embed_code'])
                )
            );
        }
        
        $this->_iLinkId = $iId;
        
        return ($bIsCustom ? $iId : Phpfox::getService('feed.process')->callback($aCallback)->add('link', $iId, $aVals['privacy'], $aVals['privacy_comment'], (isset($aVals['parent_user_id']) ? (int) $aVals['parent_user_id'] : 0)));
    }
}
