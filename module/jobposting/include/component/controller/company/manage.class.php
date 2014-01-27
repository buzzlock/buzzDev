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

class Jobposting_Component_Controller_Company_Manage extends Phpfox_Component 
{
    /**
	 * Class process method wnich is used to execute this component.
	 */
    public function process()
    {
        $iJobId = $this->request()->get('job');
        $iPage = $this->request()->get('page');
        $iShowLimit = 10;
        
        $aJob = Phpfox::getService('jobposting.job')->getGeneralInfo($iJobId);
        
        list($iCnt, $aApplications) = Phpfox::getService('jobposting.application')->getByJobId($iJobId, $iPage, $iShowLimit);        
        
        Phpfox::getLib('pager')->set(array('page' => $this->request()->get('page'), 'size' => $iShowLimit, 'count' => $iCnt));
        
        $this->template()->setTitle(Phpfox::getPhrase('jobposting.manage_job_posted'))
            ->setBreadcrumb(Phpfox::getPhrase('jobposting.job_posting'), $this->url()->makeUrl('jobposting'))
            ->setBreadcrumb(Phpfox::getPhrase('jobposting.managing_company'), $this->url()->makeUrl('jobposting.company.add', array('id' => $aJob['company_id'])))
            ->setBreadcrumb(Phpfox::getPhrase('jobposting.manage_job_posted'), $this->url()->makeUrl('jobposting.company.add.jobs', array('id' => $aJob['company_id'])))
            ->setBreadcrumb(Phpfox::getPhrase('jobposting.view_applications'), $this->url()->makeUrl('jobposting.company.manage', array('job' => $iJobId)), true)
            ->setFullSite()
            ->setHeader('cache', array(
                'table.css' => 'style_css',
                'global.css' => 'module_jobposting',
                'ynjobposting.css' => 'module_jobposting',
                'jobposting.js' => 'module_jobposting',
            ))
            ->assign(array(
                'aApplications' => $aApplications,
                'aJob' => $aJob,
                'urlModule' => Phpfox::getParam('core.url_module')
            ));
    }
    
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('jobposting.jobposting_component_controller_company_manage_clean')) ? eval($sPlugin) : false);
	}
}