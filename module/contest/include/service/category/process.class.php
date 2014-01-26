<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class Contest_Service_Category_Process extends Phpfox_Service 
{
	/**
	 * Class constructor
	 */	
	public function __construct()
	{	
		$this->_sTable = Phpfox::getT('contest_category');
	}

	public function updateOrder($aVals)
	{
		foreach ($aVals as $iId => $iOrder)
		{
			$this->database()->update($this->_sTable, array('ordering' => $iOrder), 'category_id = ' . (int) $iId);
		}
		
		$this->cache()->remove('contest', 'substr');
		
		return true;
	}
	
	public function delete($iId)
	{
		$this->database()->update($this->_sTable, array('parent_id' => 0), 'parent_id = ' . (int) $iId);
		
        /* http://www.phpfox.com/tracker/view/6349/ 
         * To fix this we can create a setting letting the admin choose 
         * whether to delete the contests belonging to the category being
         * deleted or to simply remove the category from them
         */
        if ( false /* Phpfox::getParam('contest.keep_video_after_category_delete') */)
        {
            $this->database()->delete($this->_sTable, 'category_id = ' . (int) $iId);

			$this->database()->delete(Phpfox::getT('contest_category_data'), 'category_id = ' . (int)$iId);
            $this->cache()->remove('contest', 'substr');
            return true;
        }

		$aContestIds = $this->database()->select('d.contest_id')
				->from(Phpfox::getT('contest_category_data'), 'd')
				->where("d.category_id = " . $iId)			
				->execute('getSlaveRows');		
		foreach ($aContestIds as $aContestId)
		{
			Phpfox::getService('contest.contest.process')->deleteContest($aContestId['contest_id']);
		}
		

		$this->database()->delete($this->_sTable, 'category_id = ' . (int) $iId);

		$this->database()->delete(Phpfox::getT('contest_category_data'), 'category_id = ' . (int)$iId);
		$this->cache()->remove('contest', 'substr');
		return true;

	}

	public function deleteAllContestsBelongToCategory($sId)
	{
		$aItems = $this->database()->select('d.contest_id')
				->from(Phpfox::getT('contest_category_data'), 'd')
				->where("d.category_id IN(" . $sId . ")")			
				->execute('getSlaveRows');
		if(!empty($aItems))
		{
			foreach($aItems as $aItem)
			{
				Phpfox::getService('fundraising.process')->delete($aItem['campaign_id']);
			}
		}
	}
	
	public function add($aVals)
	{
		if (empty($aVals['name']))
		{
			return Phpfox_Error::set(Phpfox::getPhrase('admincp.provide_a_category_name'));			
		}
		Phpfox::getService('ban')->checkAutomaticBan($aVals['name']);
		$oParseInput = Phpfox::getLib('parse.input');
		
		$iId = $this->database()->insert($this->_sTable, array(
				'parent_id' => (!empty($aVals['parent_id']) ? (int) $aVals['parent_id'] : 0),
				'is_active' => 1,
				'name' => $oParseInput->clean($aVals['name'], 255),
				'time_stamp' => PHPFOX_TIME
			)
		);
		
		$this->cache()->remove('contest', 'substr');
		
		return $iId;
	}
	
	public function update($iId, $aVals)
	{
		if (empty($aVals['name']))
		{
			return Phpfox_Error::set(Phpfox::getPhrase('admincp.provide_a_category_name'));
		}

		if($iId == $aVals['parent_id'])
		{
			return Phpfox_Error::set(Phpfox::getPhrase('contest.parent_category_and_child_is_the_same'));
		}
		$this->database()->update($this->_sTable, array('name' => Phpfox::getLib('parse.input')->clean($aVals['name'], 255), 'parent_id' => (int) $aVals['parent_id']), 'category_id = ' . (int) $iId);
		
		$this->cache()->remove('contest', 'substr');
		
		return true;
	}	
	
	public function deleteMultiple($aIds)
	{
		foreach ($aIds as $iId)
		{
			$this->database()->delete($this->_sTable, 'category_id = ' . (int) $iId);
			$this->deletecontestBelongToCategory($iId);
			$this->database()->delete(Phpfox::getT('contest_category_data'), 'category_id = ' . (int) $iId);
		}
		return true;
	}	
	
	public function deletecontestBelongToCategory($sId)
	{
		$aItems = $this->database()->select('d.campaign_id')
				->from(Phpfox::getT('contest_category_data'), 'd')
				->where("d.category_id IN(" . $sId . ")")			
				->execute('getSlaveRows');
		if(!empty($aItems))
		{
			foreach($aItems as $aItem)
			{
				Phpfox::getService('contest.process')->delete($aItem['campaign_id']);
			}
		}
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
		if ($sPlugin = Phpfox_Plugin::get('contest.service_category_process__call'))
		{
			return eval($sPlugin);
		}
			
		/**
		 * No method or plug-in found we must throw a error.
		 */
		Phpfox_Error::trigger('Call to undefined method ' . __CLASS__ . '::' . $sMethod . '()', E_USER_ERROR);
	}	
}

?>