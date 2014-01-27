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

class JobPosting_Service_Application_Process extends Phpfox_Service
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->_sTable = Phpfox::getT('jobposting_application');
    }
    
    public function delete($iId)
    {
        $aApplication = $this->database()->select('*')->from($this->_sTable)->where('application_id = '.(int)$iId)->execute('getSlaveRow');
        if(!$aApplication)
        {
            return Phpfox_Error::set(Phpfox::getPhrase('jobposting.unable_to_find_the_application_you_want_to_delete'));
        }
        
        $this->database()->update(Phpfox::getT('jobposting_job'), array('total_application' => 'total_application - 1'), 'job_id = '.$aApplication['job_id'], false);
        
        $this->database()->delete($this->_sTable, 'application_id = '.$iId);
        
        return true;
    }
    	
    public function updateStatus($iId, $sStatus)
    {
        return $this->database()->update($this->_sTable, array('status' => Phpfox::getService('jobposting.application')->getStatusKeyByName($sStatus)), 'application_id = '.$iId);
    }
    
}