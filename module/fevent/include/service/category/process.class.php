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
 * @package         YouNet_Event
 */
class Fevent_Service_Category_Process extends Phpfox_Service 
{
	/**
	 * Class constructor
	 */	
	public function __construct()
	{	
		$this->_sTable = Phpfox::getT('fevent_category');
	}
	
	public function add($aVals)
	{
		if (empty($aVals['name']))
		{
			return Phpfox_Error::set(Phpfox::getPhrase('fevent.provide_a_category_name'));
		}
		
		$oParseInput = Phpfox::getLib('parse.input');
		
		$iId = $this->database()->insert($this->_sTable, array(
				'parent_id' => (!empty($aVals['parent_id']) ? (int) $aVals['parent_id'] : 0),
				'is_active' => 1,
				'name' => $oParseInput->clean($aVals['name'], 255),
				'name_url' => $oParseInput->cleanTitle($aVals['name']),
				'time_stamp' => PHPFOX_TIME
			)
		);
		
		$this->cache()->remove('fevent', 'substr');
        $this->cache()->remove('event_category_display_admincp');
        $this->cache()->remove('event_category_display_option');
		
		return $iId;
	}
	
	public function update($iId, $aVals)
	{
        $aDatas = $this->database()->select('event_id')->from(Phpfox::getT('fevent_category_data'))->where('category_id = '.(int)$iId)->execute('getRows');
        foreach($aDatas as $k=>$aData)
        {
            $aDatas[$k]['category_id'] = $this->getDirectCategoryIdOfEvent($aData['event_id']);
        }

		$this->database()->update($this->_sTable, array('name' => Phpfox::getLib('parse.input')->clean($aVals['name'], 255), 'parent_id' => (int) $aVals['parent_id']), 'category_id = ' . (int) $iId);
        
        foreach($aDatas as $aData)
        {
            $this->database()->delete(Phpfox::getT('fevent_category_data'), 'event_id = '.(int)$aData['event_id']);
            
            if($aData['category_id'])
            {
                $this->database()->insert(Phpfox::getT('fevent_category_data'), array('event_id' => (int)$aData['event_id'], 'category_id' => (int)$aData['category_id']));
                
                $aParentId = $this->getParentIds($aData['category_id']);
                
                if(count($aParentId))
                {
                    foreach($aParentId as $iCategoryId)
                    {
                        $this->database()->insert(Phpfox::getT('fevent_category_data'), array('event_id' => (int)$aData['event_id'], 'category_id' => (int)$iCategoryId));
                    }
                }
            }
        }
        
		$this->cache()->remove('fevent', 'substr');
		
		return true;
	}
    
    public function getParentIds($iId)
    {
        $aIds = array();
        $aCategories = $this->database()->select('*')->from($this->_sTable)->execute('getSlaveRows');
        $aCurr = $this->database()->select('*')->from($this->_sTable)->where('category_id = '.(int)$iId)->execute('getSlaveRow');
        
        if(count($aCategories) && is_array($aCurr))
        {
            while($aCurr['parent_id'] != 0)
            {
                foreach($aCategories as $aCategory)
                {
                    if($aCategory['category_id'] == $aCurr['parent_id'])
                    {
                        $aIds[] = $aCategory['category_id'];
                        $aCurr = $aCategory;
                        continue;
                    }
                }
            }
        }
        
        return $aIds;
    }
    
    public function getDirectCategoryIdOfEvent($iEventId)
    {
        $aDatas = $this->database()->select('category_id')->from(Phpfox::getT('fevent_category_data'))->where('event_id = '.(int)$iEventId)->execute('getRows');
        
        if($cnt = count($aDatas))
        {
            foreach($aDatas as $aData)
            {
                if(!$this->_haveChild($aData, $aDatas))
                {
                    return $aData['category_id'];
                }
            }
        }
        
        return 0;
    }
    
    protected function _haveChild($aCheck, $aDatas)
    {
        foreach($aDatas as $aData)
        {
            if($this->database()->select('*')->from($this->_sTable)->where('category_id='.(int)$aData['category_id'].' AND parent_id='.(int)$aCheck['category_id'])->execute('getRow'))
            {
                return true;
            }
        }
        return false;
    }
	
	public function delete($iId)
	{
		$this->database()->update($this->_sTable, array('parent_id' => 0), 'parent_id = ' . (int) $iId);
		
		$aEvents = $this->database()->select('m.event_id, m.user_id, m.image_path')
			->from(Phpfox::getT('fevent_category_data'), 'mcd')
			->join(Phpfox::getT('fevent'), 'm', 'm.event_id = mcd.event_id')
			->where('mcd.category_id = ' . (int) $iId)
			->execute('getRows');		
			
		foreach ($aEvents as $aEvent)
		{
			Phpfox::getService('fevent.process')->delete($aEvent['event_id'], $aEvent);
		}
		
		$this->database()->delete($this->_sTable, 'category_id = ' . (int) $iId);
		
		$this->cache()->remove('fevent', 'substr');
		
		return true;
	}
	
	public function updateOrder($aVals)
	{
		foreach ($aVals as $iId => $iOrder)
		{
			$this->database()->update($this->_sTable, array('ordering' => $iOrder), 'category_id = ' . (int) $iId);
		}
		
		$this->cache()->remove('fevent', 'substr');
		
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
		if ($sPlugin = Phpfox_Plugin::get('fevent.service_category_process__call'))
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