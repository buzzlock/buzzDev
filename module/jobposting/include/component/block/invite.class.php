<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright		[YOUNET_COPPYRIGHT]
 * @author  		AnNT
 * @package  		Module_jobposting
 */

class Jobposting_Component_Block_Invite extends Phpfox_Component 
{
    /**
	 * Class process method wnich is used to execute this component.
	 */
    public function process()
    {
        $sType = $this->request()->get('type');
        $iId = $this->request()->get('id');
        $sTitle = '';
        $sLink = '';
        
        if ($sType == 'job')
        {
            $aItem = Phpfox::getService('jobposting.job')->getGeneralInfo($iId);
            $sTitle = $aItem['title'];
            $sLink = Phpfox::getLib('url')->permalink('jobposting', $aItem['job_id'], $aItem['title']);
        }
        elseif ($sType == 'company')
        {
            $aItem = Phpfox::getService('jobposting.company')->getGeneralInfo($iId);
            $sTitle = $aItem['name'];
            $sLink = Phpfox::getLib('url')->permalink('jobposting.company', $aItem['company_id'], $aItem['name']);
        }
        else
        {
            return false;
        }
        
        $sSubject = Phpfox::getPhrase('jobposting.full_name_invited_you_to_the_type_title', array(
			'full_name' => Phpfox::getUserBy('full_name'),
            'type' => $sType,
			'title' => $sTitle,
		));
        
        $sMessage = Phpfox::getPhrase('jobposting.full_name_invited_you_to_the_type_title_link', array(
            'full_name' => Phpfox::getUserBy('full_name'),
            'type' => $sType,
            'title' => $sTitle,
            'link' => $sLink
		));
        
        $this->template()->assign(array(
            'sType' => $sType,
            'aItem' => $aItem,
            'sSubject' => $sSubject,
            'sMessage' => $sMessage
        ));
    }
}
