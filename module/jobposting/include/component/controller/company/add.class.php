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

class Jobposting_Component_Controller_Company_Add extends Phpfox_Component 
{
    /**
	 * Class process method wnich is used to execute this component.
	 */
    public function process()
    {
        Phpfox::isUser(true);
        Phpfox::getUserParam('jobposting.can_add_company', true);
        
        $bIsEdit = false;
        $iJobsLimit = 10;
		$sModule = $this->request()->get('module', false);
		$iItem =  $this->request()->getInt('item', false);
        
        $aAction = array('photos', 'packages', 'form', 'jobs', 'admins');
        $sAction = $this->request()->get('req4');
        if(!in_array($sAction, $aAction))
        {
            $sAction = null;
        }
        
        $aForms = array(
            'category' => null
        );
        
        if ($iEditId = $this->request()->get('id'))
		{
			if (($aCompany = Phpfox::getService('jobposting.company')->getForEdit($iEditId)))
			{
				
				if (Phpfox::getUserId() == $aCompany['user_id'] || Phpfox::getService('jobposting.company')->isAdmin($iEditId, Phpfox::getUserId()))
				{
					Phpfox::getUserParam('jobposting.can_edit_own_company', true);
				}
				else
				{
					Phpfox::getUserParam('jobposting.can_edit_user_company', true);
				}
				
				$bIsEdit = true;
                $aForms = $aCompany;
				$this->setParam('aCompany', $aCompany);
				$this->setParam(array(
					'country_child_value' => $aCompany['country_iso'],
					'country_child_id' => $aCompany['country_child_id']
				));
                
				if ($aCompany['module_id'] != 'jobposting')
				{
					$sModule = $aCompany['module_id'];
					$iItem = $aCompany['item_id'];
				}
                
                #Manage Job Posted
               
                list($iJobsCnt, $aJobs, $aSearchForms) = $this->_getJobsForManage($iEditId, $iJobsLimit);
				
				foreach($aJobs as $key=>$Jobs){
			
					if($Jobs['time_expire']<=PHPFOX_TIME){
						$Jobs['status_jobs'] = Phpfox::getPhrase('jobposting.expired');
                                                $Jobs['is_expired'] = 1;
					}
					else {
						$Jobs['is_expired'] = 0;
						if($Jobs['post_status']==1)
							$Jobs['status_jobs'] = Phpfox::getPhrase('jobposting.published');
						else if ($Jobs['post_status']==0){
							$Jobs['status_jobs'] = Phpfox::getPhrase('jobposting.draft');
						}		
					}
					$aJobs[$key] = $Jobs;
				}
				
                Phpfox::getLib('pager')->set(array('page' => $this->request()->get('page'), 'size' => $iJobsLimit, 'count' => $iJobsCnt));
                $this->template()->assign('aJobs', $aJobs);
                $aForms = array_merge($aForms, $aSearchForms);
				
				$bCanSponsorPublishedCompany = Phpfox::getService('jobposting.permission')->canSponsorPublishedCompany($iEditId) ? true : false;
			}
		}
		else
		{
			Phpfox::getUserParam('jobposting.can_add_company', true);
			
			if (Phpfox::getService('jobposting.company')->hasCompany(Phpfox::getUserId()))
	        {
	            Phpfox_Error::display(Phpfox_Error::set(Phpfox::getPhrase('jobposting.you_can_not_create_more_than_one_company')));
	        }
			
			if (!Phpfox::getService('jobposting.category')->getTotalActive())
			{
				Phpfox_Error::display(Phpfox_Error::set(Phpfox::getPhrase('jobposting.unable_to_find_any_industry')));
			}
			
			$bCanSponsorPublishedCompany = Phpfox::getService('jobposting.permission')->canSponsorPublishedCompany() ? true : false;
		}
        
        $aValidation = $this->_getValidationVars($bIsEdit);
        $oValid = Phpfox::getLib('validator')->set(array(
            'sFormName' => 'core_js_jobposting_company_form', 
            'aParams' => $aValidation
        ));
        
        if ($aVals = $this->request()->getArray('val'))
        {
            if ($oValid->isValid($aVals))
            {
                if($bIsEdit)
                {
                	$sMessage = Phpfox::getPhrase('jobposting.company_information_successfully_updated');
                	if (isset($aVals['draft_publish']))
					{
						$aVals['post_status'] = 1;
						$sMessage = Phpfox::getPhrase('jobposting.company_successfully_published');
					}
                    $aVals['user_id'] = $aCompany['user_id'];
					$aVals['is_approved'] = $aCompany['is_approved'];
					$aVals['old_status'] = $aCompany['post_status'];
                    $aVals['action'] = !empty($sAction) ? $sAction : '';
					
                    if (Phpfox::getService('jobposting.company.process')->update($iEditId, $aVals, $images))
					{
						
                        switch ($sAction)
						{
							case 'photos':
								if($images=="")	
                                	$this->url()->send('jobposting.company.add.photos', array('id' => $iEditId), Phpfox::getPhrase('jobposting.upload_photos_process_has_finished'));
                                break;
                            case 'packages':
                                $this->url()->send('jobposting.company.add.packages', array('id' => $iEditId), Phpfox::getPhrase('jobposting.company_information_successfully_updated'));
                                break;
                            case 'jobs':
                                $this->url()->send('jobposting.company.add.jobs', array('id' => $iEditId), Phpfox::getPhrase('jobposting.company_information_successfully_updated'));
                                break;
                            case 'form':
                                $this->url()->send('jobposting.company.add.form', array('id' => $iEditId), Phpfox::getPhrase('jobposting.submission_form_successfully_updated'));
                                break;
                            case 'admins':
                                $this->url()->send('jobposting.company.add.admins', array('id' => $iEditId), Phpfox::getPhrase('jobposting.company_admins_successfully_updated'));
                                break;
                            default:
								$this->url()->send('jobposting.company.add', array('id' => $iEditId), $sMessage);
						}
					}
                }
                else
                {
                	$sMessage = Phpfox::getPhrase('jobposting.company_successfully_added');
                	if (isset($aVals['draft']))
					{
						$aVals['post_status'] = 2;
						$sMessage = Phpfox::getPhrase('jobposting.company_successfully_saved');
					}
					
                    if (Phpfox_Error::isPassed())
                    {
                    	$iId = Phpfox::getService('jobposting.company.process')->add($aVals);
                        if (isset($iId) && $iId)
						{
							if (Phpfox::getUserParam('jobposting.can_edit_own_company'))
							{
								$this->url()->send('jobposting.company.add.photos', array('id' => $iId), $sMessage);
							}
							else
							{
								Phpfox::permalink('jobposting.company', $iId, $aVals['name'], true, $sMessage);
							}
						}
                    }
                }
            }
            
            $aForms = $aVals;
        }
        
		if ($bIsEdit)
		{
			$aMenus = array(
				'info' => Phpfox::getPhrase('jobposting.company_information'),
				'photos' => Phpfox::getPhrase('jobposting.photos'),
				'packages' => Phpfox::getPhrase('jobposting.my_bought_packages'),
                'form' => Phpfox::getPhrase('jobposting.submission_form'),
                'jobs' => Phpfox::getPhrase('jobposting.manage_job_posted'),
                'admins' => Phpfox::getPhrase('jobposting.admins')
			);
			
			$this->template()->buildPageMenu('js_jobposting_company_block', $aMenus, array(
					'link' => $this->url()->permalink('jobposting.company', $aForms['company_id'], $aForms['name']),
					'phrase' => Phpfox::getPhrase('jobposting.view_company')
				)
			);
		}
        
        $this->template()->setTitle((!empty($iEditId) ? Phpfox::getPhrase('jobposting.managing_company') : Phpfox::getPhrase('jobposting.add_company')))
            ->setBreadcrumb(Phpfox::getPhrase('jobposting.job_posting'), $this->url()->makeUrl('jobposting'))
            ->setBreadcrumb((!empty($iEditId) ? Phpfox::getPhrase('jobposting.managing_company') : Phpfox::getPhrase('jobposting.add_company')), ($iEditId > 0 ? $this->url()->makeUrl('jobposting.company.add', array('id' => $iEditId)) : $this->url()->makeUrl('jobposting.company.add')), true)
            ->setFullSite()	
            ->setEditor()
            ->setHeader('cache', array(
                'table.css' => 'style_css',
                'pager.css' => 'style_css',
                'company-add.js' => 'module_jobposting',
                'jquery/plugin/jquery.highlightFade.js' => 'static_script',
                'switch_legend.js' => 'static_script',
                'switch_menu.js' => 'static_script',
                'quick_edit.js' => 'static_script',
                'progress.js' => 'static_script',					
				'country.js' => 'module_core',
                'map.js' => 'module_jobposting',
                'jobposting.js' => 'module_jobposting',
				'global.css' => 'module_jobposting'
			))
            ->setHeader(array(
                '<script type="text/javascript">$Behavior.jobpostingProgressBarSettings = function(){ if ($Core.exists(\'#js_jobposting_company_block_photos_holder\')) { oProgressBar = {holder: \'#js_jobposting_company_block_photos_holder\', progress_id: \'#js_progress_bar\', uploader: \'#js_progress_uploader\', add_more: false, max_upload: 6, total: 1, frame_id: \'js_upload_frame\', file_id: \'image[]\'}; $Core.progressBarInit(); } }</script>'
            ))
            ->setPhrase(array(
                'jobposting.do_you_want_to_sponsor_your_company_width',
                'jobposting.pay_fee_to_sponsor_this_company',
                'jobposting.save_and_pay_fee_for_selected_package',
                'jobposting.pay_fee_for_selected_packages',
                //'jobposting.are_you_sure_you_want_to_delete_this_custom_option',
                'jobposting.processing',
                'jobposting.updating',
                'jobposting.adding'
            ))
            ->assign(array(
                'sCreateJs' => $oValid->createJS(),
                'sGetJsForm' => $oValid->getJsForm(),
                'bIsEdit' => $bIsEdit,
                'aForms' => $aForms,
                'aCompany' => $aForms,
                'currency' => PHpfox::getService('jobposting.helper')->getCurrency(),
                'sCustomField' => !empty($aForms['custom_field']) ? Phpfox::getService('jobposting.custom')->buildHtmlForReview($aCompany['custom_field']) : '',
                'iSponsorFee' => (Phpfox::getParam('jobposting.jobposting_fee_to_sponsor_company') > 0) ? Phpfox::getService('jobposting.helper')->getTextJsCurrency(Phpfox::getParam('jobposting.jobposting_fee_to_sponsor_company')) : 0,
                'bCanSponsorPublishedCompany' => $bCanSponsorPublishedCompany,
                'sNewReq' => !empty($sAction) ? $sAction : 'info',
                'sModule' => !empty($sModule) ? $sModule : null,
				'iItem' => !empty($iItem) ? $iItem : null,
                'sIndustries' => Phpfox::getService('jobposting.category')->getForAdd(3, $aForms['category']),
                'iMaxFileSize' => (Phpfox::getParam('jobposting.jobposting_maximum_upload_size_photo') === 0 ? null : Phpfox::getLib('phpfox.file')->filesize((Phpfox::getParam('jobposting.jobposting_maximum_upload_size_photo') / 1024) * 1048576)),
                'urlModule' => Phpfox::getParam('core.url_module'),
            ));
    }
    
    private function _getJobsForManage($iCompanyId, $iLimit)
    {
        $aForms = array();
        if($this->request()->get('search'))
        {
            $sCond = '';
            if($sTitle = $this->request()->get('title'))
            {
                $sCond .= ' AND title LIKE "%'.$sTitle.'%"';
                $aForms['search_title'] = $sTitle;
            }
            if ($sFrom = $this->request()->get('from'))
            {
                $aFrom = explode('-', $sFrom);
                $iStartTime = mktime(0, 0, 0, $aFrom[0], $aFrom[1], $aFrom[2]);
                $sCond .= ' AND time_stamp >= '.$iStartTime;
                $aForms['from_month'] = $aFrom[0];
                $aForms['from_day'] = $aFrom[1];
                $aForms['from_year'] = $aFrom[2];
            }
            if($sTo = $this->request()->get('to'))
            {
                $aTo = explode('-', $sTo);
                $iEndTime = mktime(23, 59, 59, $aTo[0], $aTo[1], $aTo[2]);
                $sCond .= ' AND time_stamp <= '.$iEndTime;
                $aForms['to_month'] = $aTo[0];
                $aForms['to_day'] = $aTo[1];
                $aForms['to_year'] = $aTo[2];
            }
            if($sStatus = $this->request()->get('status'))
            {
                if($sStatus == 'show')
                {
                    $sCond .= ' AND is_hide = 0';
                }
                if($sStatus == 'hide')
                {
                    $sCond .= ' AND is_hide = 1';
                }
                $aForms['search_status'] = $sStatus;
            }
            list($iCnt, $aJobs) = Phpfox::getService('jobposting.job')->searchForEditCompany($iCompanyId, $sCond, $this->request()->get('page'), $iLimit);
        }
        else
        {
            list($iCnt, $aJobs) = Phpfox::getService('jobposting.job')->searchForEditCompany($iCompanyId, null, $this->request()->get('page'), $iLimit);
        }
        
        return array($iCnt, $aJobs, $aForms);
    }
    
    private function _getValidationVars($bIsEdit = false)
    {
        $aVars = array(
            'name' => array(
                'def' => 'required',
                'title' => Phpfox::getPhrase('jobposting.company_name_is_required')
            ),
            'description' => array(
                'def' => 'required',
                'title' => Phpfox::getPhrase('jobposting.description_is_required')
            ),
            'location' => array(
                'def' => 'required',
                'title' => Phpfox::getPhrase('jobposting.headquaters_location_is_required')
            ),
            'size_from' => array(
                'def' => 'required',
                'title' => Phpfox::getPhrase('jobposting.from_field_of_company_size_is_required')
            ),
            'size_to' => array(
                'def' => 'required',
                'title' => Phpfox::getPhrase('jobposting.to_field_of_company_size_is_required')
            ),
            'category' => array(
                'def' => 'required',
                'title' => Phpfox::getPhrase('jobposting.industry_is_required')
            ),
            'contact_name' => array(
                'def' => 'required',
                'title' => Phpfox::getPhrase('jobposting.contact_name_is_required')
            ),
            'contact_phone' => array(
                'def' => 'required',
                'title' => Phpfox::getPhrase('jobposting.contact_phone_is_required')
            ),
            'contact_email' => array(
                'def' => 'required',
                'title' => Phpfox::getPhrase('jobposting.contact_email_is_required')
            ),
        );
        
        if($bIsEdit)
        {
            $aVars['form_title'] = array(
                'def' => 'required',
                'title' => Phpfox::getPhrase('jobposting.form_title_is_required')
            );
        }
        
        return $aVars;
    }

	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('jobposting.Jobposting_Component_Controller_Company_Add_clean')) ? eval($sPlugin) : false);
	}

}

