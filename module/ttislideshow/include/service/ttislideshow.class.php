<?php

defined('PHPFOX') or exit('NO DICE!');

/**
 * author		Teamwurkz Technologies Inc.
 * package		tti_components
 */
 
class Ttislideshow_Service_Ttislideshow extends Phpfox_Service 
{

	public function __construct()
	{	
		$this->_sTable = Phpfox::getT('tti_slideshow');	
	}

	public function get()		
	{
		$aRows = $this->database()->select('*')
			->from($this->_sTable)
			->order('ordering ASC')
			->execute('getRows');

		return $aRows;
	}

	public function getdisplay()		
	{
		$aRows = $this->database()->select('*')
			->from($this->_sTable)
			->where('is_active=1')
			->order('ordering ASC')
			->execute('getRows');

		return $aRows;
	}
	
	public function getSlide($iId)		
	{
	
		$aRow = $this->database()->select('*')
			->from($this->_sTable)
			->where('slide_id = ' . (int) $iId)
			->execute('getRow');

		return $aRow;
	}
	
}

?>