<?php

defined('PHPFOX') or exit('NO DICE!');


class SocialStream_Service_Agents extends Phpfox_Service
{

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->_sTable = phpfox::getT('socialbridge_agents');
    }

    public function updatePrivacy($iUserId = null, $sService = 'facebook', $iPrivacy = 0)
    {
        if (!$iUserId || !$sService)
        {
            return false;
        }
        
        $aAgent = Phpfox::getService('socialbridge.agents')->getToken($iUserId, $sService);
        $aProvider = phpfox::getService('socialbridge.providers')->getProvider($sService);
        $iOldPrivacy = $aAgent['privacy'];

        $this->database()->update($this->_sTable, array('privacy' => (int)$iPrivacy), 'user_id = ' . $iUserId . ' AND service_id= ' . $aProvider['service_id']);

        $sSocialAgentId = $aAgent['identity'];
        
        if ($sService == 'twitter')
        {
            $sSocialAgentId = $aAgent['user_name'];
        }

        $aFeeds = $this->database()->select('feed_id')
            ->from(Phpfox::getT('socialstream_feeds'))
            ->where('privacy_comment = 0 AND user_id = ' . $iUserId . ' AND service_id= ' . $aProvider['service_id'])
            ->execute('getRows');

        $sIds = '0';
        foreach ($aFeeds as $aFeed)
        {
            $sIds .= ', ' . $aFeed['feed_id'];
        }

        $this->database()->update(Phpfox::getT('socialstream_feeds'), array('privacy' => (int)$iPrivacy), 'feed_id IN (' . $sIds . ')');
        $this->database()->update(Phpfox::getT('feed'), array('privacy' => (int)$iPrivacy), 'item_id IN (' . $sIds . ') AND user_id = ' . $iUserId . ' AND type_id = "socialstream_' . $sService . '"');
        return true;
    }

    /**
     * If a call is made to an unknown method attempt to connect
     * it to a specific plug-in with the same name thus allowing 
     * plug-in developers the ability to extend classes.
     *
     * @param string $sMethod is the name of the method
     * @param array $aArguments is the array of arguments of being passed
     */
    public function __call($sMethod, $aArguments)
    {
        /**
         * Check if such a plug-in exists and if it does call it.
         */
        if ($sPlugin = Phpfox_Plugin::get('socialstream.service_agents__call'))
        {
            return eval($sPlugin);
        }

        /**
         * No method or plug-in found we must throw a error.
         */
        Phpfox_Error::trigger('Call to undefined method ' . __class__ . '::' . $sMethod . '()', E_USER_ERROR);
    }

}

?>
