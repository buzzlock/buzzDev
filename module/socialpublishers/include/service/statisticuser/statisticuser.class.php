<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class SocialPublishers_Service_Statisticuser_Statisticuser extends Phpfox_Service 
{
    public function adminGet($aConds, $sSort = 'su.id ASC', $iPage = '', $iLimit = '')
    {
        $iCount = $this->database()
                ->select('COUNT(su.id)')
                ->from(Phpfox::getT('socialpublishers_statistic_user'), 'su')
                ->leftJoin(Phpfox::getT('user'), 'u', 'u.user_id = su.user_id')
                ->where($aConds)
                ->order($sSort)
                ->execute('getSlaveField');
        
        $aItems = array();

        if ($iCount > 0)
        {
            $aItems = $this->database()
                    ->select('su.*, u.full_name, u.user_image')
                    ->from(Phpfox::getT('socialpublishers_statistic_user'), 'su')
                    ->leftJoin(Phpfox::getT('user'), 'u', 'u.user_id = su.user_id')
                    ->where($aConds)
                    ->order($sSort)
                    ->limit($iPage, $iLimit, $iCount)
                    ->execute('getSlaveRows');
        }

        return array($iCount, $aItems);
    }
}

?>