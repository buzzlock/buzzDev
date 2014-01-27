<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright		[YOUNET_COPPYRIGHT]
 * @author  		VuDP, AnNT
 * @package  		Module_jobposting
 */

class JobPosting_Service_Jobposting extends Phpfox_service
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        
    }
    
    /**
     * Check favorite status
     * @param $sType: item_type
     * @param $iId: item_id
     * @param $iUserId
     * @return bool
     */
    public function isFavorited($sType, $iId, $iUserId)
    {
        $iId = $this->database()->select('favorite_id')
            ->from(Phpfox::getT('jobposting_favorite'))
            ->where('item_type = "'.$sType.'" AND item_id = '.(int)$iId.' AND user_id = '.(int)$iUserId)
            ->execute('getSlaveField');
        
		if(!$iId)
		{
			return false;
		}
        
		return true;
    }
    
    /**
     * Check follow status
     * @param $sType: item_type
     * @param $iId: item_id
     * @param $iUserId
     * @return bool
     */
    public function isFollowed($sType, $iId, $iUserId)
    {
        $iId = $this->database()->select('follow_id')
            ->from(Phpfox::getT('jobposting_follow'))
            ->where('item_type = "'.$sType.'" AND item_id = '.(int)$iId.' AND user_id = '.(int)$iUserId)
            ->execute('getSlaveField');
        
        if(!$iId)
		{
			return false;
		}
        
		return true;
    }
    
    public function getOwner($sType, $iId)
    {
        $iOwner = $this->database()->select('user_id')
            ->from(Phpfox::getT('jobposting_'.$sType))
            ->where($sType.'_id = '.(int)$iId)
            ->execute('getSlaveField');
        
        return $iOwner;
    }
    
    public function getFollowers($sType, $iId)
    {
        $aFollower = array();

        $aRows = $this->database()->select('DISTINCT user_id')
            ->from(Phpfox::getT('jobposting_follow'))
            ->where('item_type = "'.$sType.'" AND item_id = '.(int)$iId)
            ->execute('getSlaveRows');
        
        if (count($aRows))
        {
            foreach ($aRows as $aRow)
			{
            	$aFollower[] = $aRow['user_id'];
			}
        }
        
        return $aFollower;
    }
    
    public function getApplicants($sType, $iId)
    {
        $aApplicant = array();
        $aRows = array();
        
        if ($sType == 'job')
        {
            $aRows = $this->database()->select('DISTINCT user_id')
                ->from(Phpfox::getT('jobposting_application'))
                ->where('job_id = '.(int)$iId)
                ->execute('getSlaveRows');
        }
        
        if ($sType == 'company')
        {
            $aRows = $this->database()->select('DISTINCT a.user_id')
                ->from(Phpfox::getT('jobposting_application'), 'a')
                ->join(Phpfox::getT('jobposting_job'), 'j', 'j.job_id = a.job_id')
                ->where('j.company_id = '.(int)$iId)
                ->execute('getSlaveRows');
        }
        
        if (count($aRows))
        {
            foreach ($aRows as $aRow)
			{
            	$aApplicant[] = $aRow['user_id'];
			}
        }
        
        return $aApplicant;
    }
    
    public function getItemTitle($sItemType, $iItemId)
    {
        $sTitle = $this->database()->select(($sItemType == 'company') ? 'name' : 'title')
			->from(Phpfox::getT('jobposting_'.$sItemType))
			->where($sItemType.'_id = '.(int)$iItemId)
			->execute('getSlaveField');
        
        return $sTitle;
    }
}