<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class Petition_Service_Category_Process extends Phpfox_Service 
{
	/**
	 * Class constructor
	 */	
	public function __construct()
	{	
		$this->_sTable = Phpfox::getT('petition_category');
	}
	
	public function update($iId, $sCategory, $iUserId)
	{
		$sNew = Phpfox::getLib('parse.input')->clean($sCategory, 255);
		
		$iId = $this->database()->update($this->_sTable, array(
			'name' => $sNew			
		), 'category_id = ' . (int) $iId);		
		
		return $sNew;
	}
	
	public function add($sCategory, $iUserId = null)
	{
		$iId = $this->database()->insert(Phpfox::getT('petition_category'), array(
				'name' => Phpfox::getLib('parse.input')->clean($sCategory, 255),
				'user_id' => ($iUserId === null ? Phpfox::getUserId() : $iUserId),
				'added' => PHPFOX_TIME
			)
		);
		
		return $iId;
	}	
	
	public function deleteMultiple($aIds)
	{
		foreach ($aIds as $iId)
		{
			$this->database()->delete($this->_sTable, 'category_id = ' . (int) $iId);
			$this->deletePetitionBelongToCategory($iId);
			$this->database()->delete(Phpfox::getT('petition_category_data'), 'category_id = ' . (int) $iId);
		}
		return true;
	}	
	
	public function deletePetitionBelongToCategory($sId)
	{
		$aItems = $this->database()->select('d.petition_id')
				->from(Phpfox::getT('petition_category_data'), 'd')
				->where("d.category_id IN(" . $sId . ")")			
				->execute('getSlaveRows');
		if(!empty($aItems))
		{
			foreach($aItems as $aItem)
			{
				Phpfox::getService('petition.process')->delete($aItem['petition_id']);
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
		if ($sPlugin = Phpfox_Plugin::get('petition.service_category_process__call'))
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