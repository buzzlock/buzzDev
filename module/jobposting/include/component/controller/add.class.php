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


class Jobposting_Component_Controller_Add extends Phpfox_Component 
{
 	private function _getValidationParams($aVals = array()) {

        $aParam = array(
            'title' => array(
                'def' => 'required',
                'title' => Phpfox::getPhrase('jobposting.fill_in_a_title_for_your_job'),
            ),
            'description' => array(
                'def' => 'required',
                'title' => Phpfox::getPhrase('jobposting.add_some_content_to_your_description'),
            ),
            'skills' => array(
                'def' => 'required',
                'title' => Phpfox::getPhrase('jobposting.add_some_content_to_your_skills'),
            ),
        );

        return $aParam;
    }
	
	private function payPackage($aVals, $iId)
    {
        if (isset($aVals['publish']) && $aVals['publish'])
        {
            if (isset($aVals['packages']) && $aVals['packages'])
            {
                $iPackage = $aVals['packages'];
                $featureJob = (isset($aVals['feature']) && $aVals['feature']) ? $iId : 0;
                $sUrl = Phpfox::getLib('url')->permalink('jobposting', $iId, $aVals['title']);
                
                if ($aVals['paypal'] == 0) //select existing packages
                {
                    $aPackage = Phpfox::getService('jobposting.package')->getByDataId($iPackage, true);
                    if (!$aPackage)
                    {
                        return Phpfox_Error::set('Invalid package.');
                    }
                    
                    Phpfox::getService('jobposting.package.process')->updateRemainingPost($iPackage);
                    Phpfox::getService('jobposting.job.process')->publish($iId);
                    if ($featureJob)
                    {
                        Phpfox::getService('jobposting.job.process')->payForFeature($iId, $sUrl);
                    }
                }
                elseif ($aVals['paypal'] == 1) //buy new
                {
                    $aPackage = Phpfox::getService('jobposting.package')->getById($iPackage);
                    if (!$aPackage)
                    {
                        return Phpfox_Error::set('Invalid package.');
                    }
                    
                    Phpfox::getService('jobposting.package.process')->pay(array($iPackage), $aVals['company_id'], $sUrl, false, $iId, $featureJob);
                }
            }
        }
	}
 
	public function process(){
		Phpfox::getUserParam('jobposting.can_add_job', true);
		$this->template()->setBreadcrumb(Phpfox::getPhrase('jobposting.job_posting'), $this->url()->makeUrl('jobposting'));
		
		$aValidationParam = $this->_getValidationParams();
		
        $oValid = Phpfox::getLib('validator')->set(array(
                'sFormName' => 'ync_edit_jobposting_form',
                'aParams' => $aValidationParam
            )
        );
		$bIsEdit = false;
		$iEditId = 0;
		if ($iEditId = $this->request()->get('req3')){
			
			if (($aJob = Phpfox::getService('jobposting.job')->getJobByJobId($iEditId))){
				
				if($aJob['time_expire']<=PHPFOX_TIME){
					PHpfox::getLib("url")->send('subscribe');
				}
				
				$bIsEdit = true;
				$this->setParam('aJob', $aJob);
				
				$this->template()->assign(array(
					'aForms' => $aJob
				));
			}
		}
		
		$aCompany = Phpfox::getService('jobposting.company')->getCompany(Phpfox::getUserId());	
		if(($aCompany && $aCompany['post_status']==1 && $aCompany['is_approved'] && $aCompany['is_deleted']==0) || ($bIsEdit && Phpfox::isAdmin()))
        {
			
		}
		else
        {
			$str = Phpfox::getPhrase('jobposting.you_can_not_create_a_job_your_company_has_not_been_published');
			Phpfox_Error::display(Phpfox_Error::set($str));
		}
		if ($aVals = $this->request()->getArray('val'))
		{
			$aVals['company_id'] = Phpfox::getService('jobposting.company')->getCompanyIdByUserId(Phpfox::getUserId());
            
			$valuefeatue = PHpfox::getParam("jobposting.fee_feature_job");
			$payment_featuer = 0;
			if($valuefeatue>0 && $aVals['feature']==1)
			{
				$payment_featuer = 1;
			}
			if ($oValid->isValid($aVals)){
				if (Phpfox_Error::isPassed())
				{
					if($bIsEdit){
						$aVals['job_id'] = $iEditId;
						$aVals['company_id'] = $aJob['company_id'];
						Phpfox::getService('jobposting.job.process')->update($aVals);
						
						$this->payPackage($aVals, $iEditId);
						$this->url()->permalink('jobposting', $iEditId, $aVals['title'], true, Phpfox::getPhrase('jobposting.job_successfully_updated'));
					}
					else
					{
						if ($iId = Phpfox::getService('jobposting.job.process')->add($aVals))
						{
							$this->payPackage($aVals, $iId);
							$this->url()->permalink('jobposting', $iId, $aVals['title'], true, Phpfox::getPhrase('jobposting.job_successfully_added'));
						}
					}
				}
			}
		}
		
		$this->template()->assign(array(
			'sCreateJs' => $oValid->createJS(),
			'bIsEdit' => $bIsEdit,
			'job_id' => $iEditId,
		))->setEditor(array('wysiwyg' => 1))->setHeader('cache', array(
				'jquery/plugin/jquery.highlightFade.js' => 'static_script',
				'switch_legend.js' => 'static_script',
				'switch_menu.js' => 'static_script',
				'quick_edit.js' => 'static_script',
				'pager.css' => 'style_css', 
				'share.js' => 'module_attachment', 
				'addjob.js' => 'module_jobposting',
                                'ynjobposting' => 'module_jobposting'
			));
		
		
		 if (Phpfox::isModule('attachment')) {
            $this->template()->assign(array('aAttachmentShare' => array(
                    'type' => 'jobposting',
                    'id' => 'ync_edit_jobposting_form',
                    'edit_id' => ($bIsEdit ? $iEditId : 0),
                    'inline' => false
                )
                )
            );
        }
		  
	}

	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		
		(($sPlugin = Phpfox_Plugin::get('jobposting.Jobposting_Component_Controller_Add_clean')) ? eval($sPlugin) : false);
	}

}

