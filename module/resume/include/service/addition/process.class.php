<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 *
 * @copyright      YouNet Company
 * @author         VuDP, TienNPL
 * @package        Module_Resume
 * @version        3.01
 * 
 */
class Resume_Service_Addition_Process extends Phpfox_Service
{
	
	public function __construct()
	{
		$this->_sTable = Phpfox::getT('resume_addition');
	}
	public function add($aVals)
	{
		$oFilter = Phpfox::getLib('parse.input');
		$aSql = array(
			'sport' => $oFilter->clean($aVals['sport']),
			'resume_id' => $aVals['resume_id'],
			'movies' => $oFilter->clean($aVals['movies']),
			'interests' => $oFilter->clean($aVals['interests']),
			'music' => $oFilter->clean($aVals['music']),
		);
		
		if(count($aVals['emailaddress'])>0)
		{
			foreach($aVals['emailaddress'] as $key=>$email)
			{
				if(empty($email))
				{
					unset($aVals['emailaddress'][$key]);
				}
			}
			$aSql['website'] = serialize($aVals['emailaddress']);
		}
		                                                                                                                                                                                                                                                       
		$iId = $this->database()->insert($this->_sTable ,$aSql);		
		return $iId;
	}
	
	public function update($aVals)
	{
		$oFilter = Phpfox::getLib('parse.input');
		$aSql = array(
			'sport' => $oFilter->clean($aVals['sport']),
			'movies' => $oFilter->clean($aVals['movies']),
			'interests' => $oFilter->clean($aVals['interests']),
			'music' => $oFilter->clean($aVals['music']),
		);
		
		if(count($aVals['emailaddress'])>0)
		{
			foreach($aVals['emailaddress'] as $key=>$email)
			{
				if(empty($email))
				{
					unset($aVals['emailaddress'][$key]);
				}
			}
			
			$aSql['website'] = serialize($aVals['emailaddress']);
		}
		else
		{
			$aSql['website'] ="";
		}
		                                                                                                                                                                                                                                                       
		$iId = $this->database()->update($this->_sTable ,$aSql,'resume_id='.$aVals['resume_id']);		
		return $iId;
	}
	
	/**
	 * Delete addition related to resume
	 * @param int $iId - the id of the resume need to be deleted
	 * @return true 
	 */
	public function delete($iId)
	{
		$this->database()->delete($this->_sTable, 'resume_id = ' . (int) $iId);
		return true;
	}
}

?>