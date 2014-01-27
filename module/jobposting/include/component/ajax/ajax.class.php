<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright       [PHPFOX_COPYRIGHT]
 * @author          VuDP, AnNT
 * @package         Phpfox_jobposting
 * @version         
 */

class JobPosting_Component_Ajax_Ajax extends Phpfox_Ajax
{
    public function popupPublishJob()
    {
        $id = $this->get('id');
        $this->setTitle(Phpfox::getPhrase('jobposting.publish_job'));
        Phpfox::getBlock('jobposting.job.publishJob', array('id' => $id));
    }
	
    public function publishJob()
    {
        $iId = $this->get('id');
        $package = $this->get('package');
        $paypal = $this->get('paypal');
        $feature = $this->get('feature');
        
        $aJob = Phpfox::getService('jobposting.job')->getGeneralInfo($iId);
        if (!$aJob)
        {
            return Phpfox_Error::set(Phpfox::getPhrase('jobposting.unable_to_find_the_job_you_want_to_publish'));
        }
        
        if (!$package)
        {
            return Phpfox_Error::set(Phpfox::getPhrase('jobposting.please_select_a_package_to_publish_this_job'));
        }
        
        $bSuccess = false;
        
        $featureJob = (isset($feature) && $feature) ? $iId : 0;
        $sUrl = Phpfox::getLib('url')->makeUrl('jobposting.company.add.jobs', array('id' => $aJob['company_id']));
        
        if ($paypal == 0) //select existing packages
        {
            $aPackage = Phpfox::getService('jobposting.package')->getByDataId($package, true);
            if (!$aPackage)
            {
                return Phpfox_Error::set('Invalid package.');
            }
            
            Phpfox::getService('jobposting.package.process')->updateRemainingPost($package);
            Phpfox::getService('jobposting.job.process')->publish($iId);
            if ($featureJob)
            {
                $sCheckoutUrl = Phpfox::getService('jobposting.job.process')->payForFeature($iId, $sUrl, true);
                if ($sCheckoutUrl === true)
                {
                    Phpfox::getService("jobposting.job.process")->featureJobs($iId, 1);
                    $bSuccess = true;
                }
                elseif (is_string($sCheckoutUrl))
                {
                    $this->call("$('#js_job_publish_loading').html($.ajaxProcess('Redirecting to Paypal')).show();");
                    $this->call("location.href = '".$sCheckoutUrl."';");
                    return;
                }
            }
            else
            {
                $bSuccess = true;
            }
        }
        elseif ($paypal == 1) //buy new
        {
            $aPackage = Phpfox::getService('jobposting.package')->getById($package);
            if (!$aPackage)
            {
                return Phpfox_Error::set('Invalid package.');
            }
            
            $iUserCompany = Phpfox::getService('jobposting.company')->getCompanyIdByUserId(Phpfox::getUserId());
            $sCheckoutUrl = Phpfox::getService('jobposting.package.process')->pay(array($package), $iUserCompany, $sUrl, true, $iId, $featureJob);
            if ($sCheckoutUrl === true)
            {
                if ($featureJob)
                {
                    Phpfox::getService("jobposting.job.process")->featureJobs($iId, 1);
                }
                $bSuccess = true;
            }
            elseif (is_string($sCheckoutUrl))
            {
                $this->call("$('#js_job_publish_loading').html($.ajaxProcess('".Phpfox::getPhrase('jobposting.redirecting_to_paypal')."')).show();");
                $this->call("location.href = '".$sCheckoutUrl."';");
                return;
            }
        }
        
        if ($bSuccess)
        {
            $sHtmlJobRow = Phpfox::getService('jobposting.job')->buildHtmlRow($iId);
            $this->html('#js_jp_job_'.$iId, $sHtmlJobRow);
            $this->call('tb_remove();');
        }
        else
        {
            $this->hide("#js_job_publish_loading");
            $this->call("$('.js_job_publish_btn').attr('disabled', false);");
        }
    }
	
    public function subscribe(){
        Phpfox::isUser(true);
        $this->setTitle('Subscribe Job');
        
        Phpfox::getComponent('jobposting.subscribe', array(), 'controller');
        
    }
	
	function view_more_jobs(){
		$company_id = $this->get('company_id');
		$iPage = $this->get('iPage')+1;
		$iLimit = 10;
		$ViewMore = 0;
		$aConds = 'job.company_id = '.$company_id;
		$aCompany = Phpfox::getService('jobposting.company')->getForEdit($company_id);	
		if(isset($aCompany['user_id']))
		{
			if($aCompany['user_id'] == PHpfox::getUserId() || PHpfox::isAdmin()){
				
			}
			else
			{
				$aConds.= " and job.post_status = 1 and job.time_expire>".PHPFOX_TIME;
			}
		}
		list($iCntSearch, $aJobsSearch) = Phpfox::getService("jobposting.job")->searchJobs($aConds, 'job.title ASC', $iPage, $iLimit);
		if(($iPage*$iLimit)<$iCntSearch)
		{
			$ViewMore = 1;
		}
		$hrefviewmore = "<a href='#' onclick=\"$.ajaxCall('jobposting.view_more_jobs','iPage={$iPage}&company_id={$company_id}');return false;\">".Phpfox::getPhrase('jobposting.view_more')."</a>";
	
		Phpfox::getLib('template')
			->assign(array(
				'aJobsSearch' => $aJobsSearch,
				'iCntSearch' => $iCntSearch,
				'ViewMoreJob' => $ViewMore,
				'iPage' => $iPage
			))
			->getTemplate('jobposting.block.job.mini_job_viewmore');
		
		if($ViewMore==0)
		{
			$hrefviewmore = "";
		}
		$this->append('#view_more_jobs', $this->getContent(false));
		$this->html('#href_view_more', $hrefviewmore);
		$this->call("\$Core.loadInit();");
	}
	
	function view_more_employee(){
		$company_id = $this->get('company_id');
		$iPage = $this->get('iPage')+1;
		$iLimit = 6;
		$ViewMore = 0;
		$sCond = "uf.company_id = ".$company_id;
		list($iCntEmployee, $aParticipant) = Phpfox::getService('jobposting.company')->searchEmployees($sCond, $iPage, $iLimit);
		if(($iPage*$iLimit+1)<$iCntEmployee)
		{
			$ViewMore = 1;
		}
		$hrefviewmore = "<a href='#' onclick=\"$.ajaxCall('jobposting.view_more_employee','iPage={$iPage}&company_id={$company_id}');return false;\">".Phpfox::getPhrase('jobposting.view_more')."</a>";
	
		Phpfox::getLib('template')
			->assign(array(
				'aParticipant' => $aParticipant,
				'iCntEmployee' => $iCntEmployee,
				'ViewMore' => $ViewMore,
				'iPage' => $iPage
			))
			->getTemplate('jobposting.block.company.mini_participant_company');
		
		if($ViewMore==0)
		{
			$hrefviewmore = "";
		}
		$this->append('#view_more_employee', $this->getContent(false));
		$this->html('#href_view_more_employee', $hrefviewmore);
	}
	
    
    public function activepackage(){
        $active = $this->get('active');
        $id = $this->get('id');
        
        if(Phpfox::getService('jobposting.package.process')->activepackage($id, $active))
        {
            if($active==1)
            {
                $this->call("$('#showpackage_{$id}').show();");
                $this->call("$('#hidepackage_{$id}').hide();");
            }    
            else {
                $this->call("$('#showpackage_{$id}').hide();");
                $this->call("$('#hidepackage_{$id}').show();");
            }
        }
    }
    
    public function deletepackage(){
        $id = $this->get('id');
        if(Phpfox::getService('jobposting.package.process')->delete($id))
        {
            $this->call('setTimeout(function() {window.location.href = window.location.href},100);');
        }
    }
    
    public function deleteImage()
    {
        $id = $this->get('id'); //image_id
        Phpfox::getService('jobposting.company.process')->deleteImage($id);
    }
    
    public function setDefaultImage()
    {
        $id = $this->get('id'); //image_id
        Phpfox::getService('jobposting.company.process')->setDefaultImage($id);
    }
    
    public function deleteLogo()
    {
        $id = $this->get('id'); //company_id
        Phpfox::getService('jobposting.company.process')->deleteLogo($id);
    }
    
    public function controllerAddField()
    {
        Phpfox::getComponent('jobposting.company.add-field', array(), 'controller');
    }
    
    public function addField()
    {
        $aVals = $this->get('val');
        list($iFieldId, $aOptions) = Phpfox::getService('jobposting.custom.process')->add($aVals);
        if(!empty($iFieldId))
        {
            $aFields = Phpfox::getService('jobposting.custom')->getByCompanyId($aVals['company_id']);
            $sHtml = Phpfox::getService('jobposting.custom')->buildHtmlForReview($aFields);
            $this->html('#js_custom_field_review_holder', $sHtml);
            $this->call('tb_remove();');
        }
        
        $this->call("$('#js_add_field_loading').hide();");
        $this->call("$('#js_add_field_button').attr('disabled', false);");
    }
    
    public function updateField()
    {
        $aVals = $this->get('val');
        if(Phpfox::getService('jobposting.custom.process')->update($aVals['id'], $aVals))
        {
            $aFields = Phpfox::getService('jobposting.custom')->getByCompanyId($aVals['company_id']);
            $sHtml = Phpfox::getService('jobposting.custom')->buildHtmlForReview($aFields);
            $this->html('#js_custom_field_review_holder', $sHtml);
            $this->call('tb_remove();');
        }
        
        $this->call("$('#js_add_field_loading').hide();");
        $this->call("$('#js_add_field_button').attr('disabled', false);");
    }
    
    public function deleteField()
    {
        $id = $this->get('id');
        if (Phpfox::getService('jobposting.custom.process')->delete($id))
        {
            $this->remove('#js_custom_field_'.$id);
        }
    }
    
    public function deleteOption()
    {
        $id = $this->get('id');
        $company_id = $this->get('company_id');
        if (Phpfox::getService('jobposting.custom.process')->deleteOption($id))
        {
            $aFields = Phpfox::getService('jobposting.custom')->getByCompanyId($company_id);
            $sHtml = Phpfox::getService('jobposting.custom')->buildHtmlForReview($aFields);
            $this->html('#js_custom_field_review_holder', $sHtml);
            $this->remove('#js_current_value_'.$id);
        }
        else
        {
            $this->alert(Phpfox::getPhrase('jobposting.could_not_delete'));
        }
    }
    
    public function sponsorCompany()
    {
    	if (!Phpfox::getUserParam('jobposting.can_sponsor_company'))
    	{
    		return Phpfox_Error::set(Phpfox::getPhrase('jobposting.you_do_not_have_permission_to_sponsor_company'));
    	}
		
        $id = $this->get('id');
        
        $aCompany = Phpfox::getService('jobposting.company')->getGeneralInfo($id);
        if (!$aCompany)
        {
            return Phpfox_Error::set(Phpfox::getPhrase('jobposting.unable_to_find_the_company_you_want_to_sponsor'));
        }
		
		if (!$aCompany['is_approved'])
		{
			return Phpfox_Error::set(Phpfox::getPhrase('jobposting.this_company_is_pending_for_approve'));
		}
        
        if (Phpfox::getService('jobposting.company')->isSponsor($id))
        {
            return Phpfox_Error::set(Phpfox::getPhrase('jobposting.this_company_has_been_sponsored'));
        }
        
		if(Phpfox::isAdmin())
		{
			Phpfox::getService('jobposting.company.process')->sponsor($id);
			$this->alert('Company successfully sponsored.',Phpfox::getPhrase('jobposting.sponsor_company'),300,100,true);
        	$this->call('setTimeout(function() { location.href = location.href;}, 1500);');
			return;
		}
		
        $sReturnUrl = urlencode(Phpfox::getLib('url')->permalink('jobposting.company', $id, $aCompany['name']));
        $sCheckoutUrl = Phpfox::getService('jobposting.company.process')->payForSponsor($id, $sReturnUrl, true);
        if ($sCheckoutUrl === true)
        {
            if (Phpfox::getService('jobposting.company.process')->sponsor($id))
            {
                $this->alert(Phpfox::getPhrase('jobposting.company_successfully_sponsored'));
            }
        }
        elseif (is_string($sCheckoutUrl))
        {
            $this->call("$('.js_jc_add_loading').html($.ajaxProcess('".Phpfox::getPhrase('jobposting.redirecting_to_paypal')."')).show();");
            $this->call("location.href = '".$sCheckoutUrl."';");
        }
        else
        {
            $this->hide(".js_jc_add_loading");
            $this->call("$('.js_jc_sponsor_btn').removeClass('button_off').attr('disabled', false);");
        }
    }
    
	public function unsponsorCompany()
    {
    	if (!Phpfox::isAdmin())
    	{
    		return Phpfox_Error::set(Phpfox::getPhrase('jobposting.you_do_not_have_permission_to_un_sponsor_company'));
    	}
		
        $id = $this->get('id');
        
        $aCompany = Phpfox::getService('jobposting.company')->getGeneralInfo($id);
        if (!$aCompany)
        {
            return Phpfox_Error::set(Phpfox::getPhrase('jobposting.unable_to_find_the_company_you_want_to_sponsor'));
        }
		
		if (!$aCompany['is_approved'])
		{
			return Phpfox_Error::set(Phpfox::getPhrase('jobposting.this_company_is_pending_for_approve'));
		}
        
        if (!Phpfox::getService('jobposting.company')->isSponsor($id))
        {
            return Phpfox_Error::set(Phpfox::getPhrase('jobposting.this_company_has_been_un_sponsored'));
        }
        
		if(Phpfox::isAdmin())
		{
			Phpfox::getService('jobposting.company.process')->sponsor($id,0);
			$this->alert(Phpfox::getPhrase('jobposting.company_successfully_un_sponsore'),Phpfox::getPhrase('jobposting.un_sponsor_company'),300,100,true);
        	$this->call('setTimeout(function() { location.href = location.href;}, 1500);');
			return;
		}
		
       
    }
    
    public function payPackages()
	{
		$iCompanyId = $this->get('id');
		$aVals = $this->get('val');
		
		$sUrl = Phpfox::getLib('url')->permalink('jobposting.company.add.packages', 'id_'.$iCompanyId);
        $sCheckoutUrl = Phpfox::getService('jobposting.package.process')->pay($aVals['packages'], $iCompanyId, $sUrl, true);
		if($sCheckoutUrl===true)
        {
        	$this->alert(Phpfox::getPhrase('jobposting.pay_packages_successfully'));

            $sHtmlBoughtPackages = Phpfox::getService('jobposting.package')->buildHtmlBoughtPackages($iCompanyId);
            $sHtmlToBuyPackages = Phpfox::getService('jobposting.package')->buildHtmlToBuyPackages($iCompanyId);
	        $this->hide(".js_jc_add_loading");
	        $this->call("$('.js_jc_pay_packages_btn').removeClass('button_off').attr('disabled', false);");
            $this->html('#js_jc_bought_packages', $sHtmlBoughtPackages);
            $this->html('#js_jc_tobuy_packages', $sHtmlToBuyPackages);
        }
		elseif(is_string($sCheckoutUrl))
        {
            $this->call("$('.js_jc_add_loading').html($.ajaxProcess('".Phpfox::getPhrase('jobposting.redirecting_to_paypal')."')).show();");
            $this->call("location.href = '".$sCheckoutUrl."';");
        }
		else
		{
	        $this->hide(".js_jc_add_loading");
	        $this->call("$('.js_jc_pay_packages_btn').removeClass('button_off').attr('disabled', false);");
		}
	}
    
    public function blockViewApplication()
    {
        $id = $this->get('id');
        Phpfox::getBlock('jobposting.application.view', array('id' => $id));
    }
    
    public function updateApplicationStatus()
    {
        $id = $this->get('id');
        $status = $this->get('status');
        if(Phpfox::getService('jobposting.application.process')->updateStatus($id, $status))
        {
            $sHtmlApplicationRow = Phpfox::getService('jobposting.application')->buildHtmlRow($id);
            $this->html('#js_ja_'.$id, $sHtmlApplicationRow);
        }
    }
    
    public function deleteApplication()
    {
        $id = $this->get('id');
        if(Phpfox::getService('jobposting.application.process')->delete($id))
        {
            $this->remove('#js_ja_'.$id);
            $this->alert(Phpfox::getPhrase('jobposting.application_successfully_deleted'));
        }
    }
    
    public function changeJobHide()
    {
        $id = $this->get('id');
        if(Phpfox::getService('jobposting.job.process')->changeHide($id))
        {
            $sHtmlJobRow = Phpfox::getService('jobposting.job')->buildHtmlRow($id);
            $this->html('#js_jp_job_'.$id, $sHtmlJobRow);
        }
    }
    
    public function deleteJob()
    {
        $id = $this->get('id');
        if(Phpfox::getService('jobposting.job.process')->delete($id))
        {
            $this->remove('#js_jp_job_'.$id);
            $this->alert(Phpfox::getPhrase('jobposting.job_successfully_deleted'));
        }
    }
    
    public function blockPromoteJob()
    {
        $id = $this->get('id');
        Phpfox::getBlock('jobposting.job.promote', array('id' => $id));
    }
    
    public function changePromoteCode()
    {
        $id = $this->get('id');
        $val = $this->get('val');
        $en_photo = !empty($val['en_photo']) ? 1 : 0;
        $en_description = !empty($val['en_description']) ? 1 : 0;
        
        $sPromoteCode = Phpfox::getService('jobposting.job')->getPromoteCode($id, $en_photo, $en_description);
        
        $this->html('#js_jp_promote_code_textarea', htmlentities($sPromoteCode));
        $this->html('#js_jp_promote_iframe', $sPromoteCode);
    }
    
    public function blockInvite()
    {
        $sType = $this->get('type');
        $iId = $this->get('id');
        
        Phpfox::getBlock('jobposting.invite', array('type' => $sType, 'id' => $iId));
        $this->call('<script>$Core.loadInit();</script>');
    }
    
    public function changeFavorite()
    {
        $sType = $this->get('type');
        $iId = $this->get('id');
        $iCurrent = $this->get('current');
        $iUserId = Phpfox::getUserId();
        
        $sHtmlFavorite = '<a href="#" onclick="$.ajaxCall(\'jobposting.changeFavorite\', \'type='.$sType.'&id='.$iId.'&current=0\'); return false;">'.Phpfox::getPhrase('jobposting.favorite').'</a>';
        $sHtmlUnFavorite = '<a href="#" onclick="$.ajaxCall(\'jobposting.changeFavorite\', \'type='.$sType.'&id='.$iId.'&current=1\'); return false;">'.Phpfox::getPhrase('jobposting.unfavorite').'</a>';
        
        if(!$iCurrent)
        {
            $iIsFavorited = (Phpfox::getService('jobposting')->isFavorited($sType, $iId, $iUserId) ? 1 : 0);
            
            if($iIsFavorited)
            {
                $this->html('#js_jp_favorite_link', $sHtmlUnFavorite);
                $this->alert(Phpfox::getPhrase('jobposting.you_have_favorited_this').' '.$sType.'.');
            }
            elseif(Phpfox::getService('jobposting.process')->favorite($sType, $iId, $iUserId))
            {
                $this->html('#js_jp_favorite_link', $sHtmlUnFavorite);
                $this->alert(Phpfox::getPhrase('jobposting.favorite_successfully'));
            }
        }
        else
        {
            if(Phpfox::getService('jobposting.process')->unfavorite($sType, $iId, $iUserId))
            {
                $this->html('#js_jp_favorite_link', $sHtmlFavorite);
                $this->alert(Phpfox::getPhrase('jobposting.unfavorite_successfully'));
            }
        }
    }
    
    public function changeFollow()
    {
        $sType = $this->get('type');
        $iId = $this->get('id');
        $iCurrent = $this->get('current');
        $iUserId = Phpfox::getUserId();
        
        $sHtmlFollow = '<a href="#" onclick="$.ajaxCall(\'jobposting.changeFollow\', \'type='.$sType.'&id='.$iId.'&current=0\'); return false;">'.Phpfox::getPhrase('jobposting.follow').'</a>';
        $sHtmlUnFollow = '<a href="#" onclick="$.ajaxCall(\'jobposting.changeFollow\', \'type='.$sType.'&id='.$iId.'&current=1\'); return false;">'.Phpfox::getPhrase('jobposting.unfollow').'</a>';
        
        if(!$iCurrent)
        {
            $iIsFollowed = (Phpfox::getService('jobposting')->isFollowed($sType, $iId, $iUserId) ? 1 : 0);
            
            if($iIsFollowed)
            {
                $this->html('#js_jp_follow_link', $sHtmlUnFollow);
                $this->alert(Phpfox::getPhrase('jobposting.you_have_followed_this').$sType.'.');
            }
            elseif(Phpfox::getService('jobposting.process')->follow($sType, $iId, $iUserId))
            {
                $this->html('#js_jp_follow_link', $sHtmlUnFollow);
                $this->alert(Phpfox::getPhrase('jobposting.follow_successfully'));
            }
        }
        else
        {
            if(Phpfox::getService('jobposting.process')->unfollow($sType, $iId, $iUserId))
            {
                $this->html('#js_jp_follow_link', $sHtmlFollow);
                $this->alert(Phpfox::getPhrase('jobposting.unfollow_successfully'));
            }
        }
    }
    
    public function updateFeatured()
    {
        // Get Params
     	$job_id 	 = (int) $this->get('job_id');
        $iIsFeatured = (int) $this->get('iIsFeatured');
        $iIsFeatured = (int)!$iIsFeatured;

        $oJobsProcess = Phpfox::getService('jobposting.job.process');
        if ($job_id)
        {
        	$oJobsProcess->feature($job_id, $iIsFeatured);
        }

        if($iIsFeatured)
        {
        	$sLabel = '<img src="'.Phpfox::getParam('core.path').'theme/adminpanel/default/style/default/image/misc/bullet_green.png" alt="">';
        }
        else
        {
        	$sLabel = '<img src="'.Phpfox::getParam('core.path').'theme/adminpanel/default/style/default/image/misc/bullet_red.png" alt="">';
        }

        $this->html('#item_update_featured_' . $job_id, '<a href="javascript:void(0);" onclick="$.ajaxCall(\'jobposting.updateFeatured\',\'job_id='.$job_id.'&iIsFeatured='.$iIsFeatured.'\');return false;"><div style="width:50px;">'.$sLabel.'</div></a>');
     }
	 
	 public function updateSponsor()
     {
        // Get Params
     	$company_id  = (int) $this->get('company_id');
        $iIsSponsor = (int) $this->get('iIsSponsor');
        $iIsSponsor = (int)!$iIsSponsor;

        $oCompaniesProcess = Phpfox::getService('jobposting.company.process');
        if ($company_id)
        {
        	$oCompaniesProcess->feature($company_id, $iIsSponsor);
        }

        if($iIsSponsor)
        {
        	$sLabel = '<img src="'.Phpfox::getParam('core.path').'theme/adminpanel/default/style/default/image/misc/bullet_green.png" alt="">';
        }
        else
        {
        	$sLabel = '<img src="'.Phpfox::getParam('core.path').'theme/adminpanel/default/style/default/image/misc/bullet_red.png" alt="">';
        }

        $this->html('#item_update_sponsor_' . $company_id, '<a href="javascript:void(0);" onclick="$.ajaxCall(\'jobposting.updateSponsor\',\'company_id='.$company_id.'&iIsSponsor='.$iIsSponsor.'\');return false;"><div style="width:50px;">'.$sLabel.'</div></a>');
     }
	 
	 public function applyjob(){
	 	$job_id = $this->get('job_id');
		 Phpfox::getBlock('jobposting.job.apply',
             array(                  
             	'job_id' => $job_id,
             )
         );
	 }
    
	public function addFeedComment()
	{
		Phpfox::isUser(true);
		
		$aVals = (array) $this->get('val');	
		
		if (Phpfox::getLib('parse.format')->isEmpty($aVals['user_status']))
		{
			$this->alert(Phpfox::getPhrase('user.add_some_text_to_share'));
			$this->call('$Core.activityFeedProcess(false);');
			return;			
		}		
		
		$aCompany = Phpfox::getService('jobposting.company')->getForEdit($aVals['callback_item_id'], true);
		
		if (!isset($aCompany['company_id']))
		{
			$this->alert(Phpfox::getPhrase('event.unable_to_find_the_event_you_are_trying_to_comment_on'));
			$this->call('$Core.activityFeedProcess(false);');
			return;
		}
		
		$sLink = Phpfox::permalink('jobposting.company', $aCompany['company_id'], $aCompany['name']);
		$aCallback = array(
			'module' => 'jobposting',
			'table_prefix' => 'jobposting_',
			'link' => $sLink,
			'email_user_id' => $aCompany['user_id'],
			'subject' => Phpfox::getPhrase('jobposting.full_name_wrote_a_comment_on_your_jobposting_name', array('full_name' => Phpfox::getUserBy('full_name'), 'title' => $aCompany['name'])),
			'message' => Phpfox::getPhrase('jobposting.full_name_wrote_a_comment_on_your_jobposting_message', array('full_name' => Phpfox::getUserBy('full_name'), 'link' => $sLink, 'title' => $aCompany['name'])),
			'notification' => 'jobposting_comment',
			'feed_id' => 'jobposting_comment',
			'item_id' => $aCompany['company_id']
		);
		
		$aVals['parent_user_id'] = $aVals['callback_item_id'];
		
		if (isset($aVals['user_status']) && ($iId = Phpfox::getService('feed.process')->callback($aCallback)->addComment($aVals)))
		{
			Phpfox::getLib('database')->updateCounter('jobposting_company', 'total_comment', 'company_id', $aCompany['company_id']);		
			
			Phpfox::getService('feed')->callback($aCallback)->processAjax($iId);
		}
		else 
		{
			$this->call('$Core.activityFeedProcess(false);');
		}		
	}	

	public function workingcompany(){
		$company_id = $this->get('company_id');
		
		$working = $this->get('working');
		$tmp = !$working;
		$phrase = Phpfox::getPhrase('jobposting.working_at_this_company');
		
		$alert = Phpfox::getPhrase('jobposting.you_have_left_to_the_compnay_successfully');
		if($working==1)
		{
			$phrase = Phpfox::getPhrase('jobposting.leave_this_company');
			$alert = Phpfox::getPhrase('jobposting.you_have_joined_to_the_compnay_successfully');
		}	
		$text = "<input type='button' class='button' onclick=\"$.ajaxCall('jobposting.workingcompany','company_id=$company_id&working=$tmp')\" value=\"$phrase\"/>";
		
		if($working==0)
		{
			$company_id = 0;
		}
		
		Phpfox::getLib("database")->update(Phpfox::getT('user_field'), array(
		    'company_id' => $company_id
		), 'user_id = ' . Phpfox::getUserId());
        
        #Notify
        if($working==1)
        {
            Phpfox::getService('jobposting.process')->addNotification('join', 'company', $company_id, Phpfox::getUserId(), true, true, false);
        }
        
		$this->html('#join_leave_company',$text);
		$this->alert($alert);
	}

	public function deleteCompany(){
		Phpfox::isUser(true);
		$iCompany = $this->get('company_id');
		if(!Phpfox::getService('jobposting.permission')->canDeleteCompany($iCompany, Phpfox::getUserId()))
    	{
    		$this->alert(Phpfox::getPhrase('jobposting.you_can_not_perform_this_action'),"Delete Company",300,100,true);
    		exit;
    	}
		if (Phpfox::getService('jobposting.company.process')->delete($iCompany))
    	{
    		$this->alert(Phpfox::getPhrase('jobposting.company_successfully_deleted'),"Delete Company",300,100,true);

    		$sUrl = Phpfox::getLib('url')->makeUrl('jobposting.company'); 
    		Phpfox::addMessage(Phpfox::getPhrase('jobposting.company_successfully_deleted'));
            
            $this->call('setTimeout(function() { location.href = \'' . $sUrl . '\';}, 1500);');
    		
    	}
    	else
    	{
    		$this->alert(Phpfox::getPhrase('jobposting.you_can_not_perform_this_action'),Phpfox::getPhrase('jobposting.delete_company'),300,100,true);
    	}
	}
	
	public function deleteJob_View(){
		Phpfox::isUser(true);
		$job_id = $this->get('job_id');
                $page_view = $this->get('page_view');
		if(!Phpfox::getService('jobposting.permission')->canDeleteJob($job_id, Phpfox::getUserId()))
    	{
    	
    		$this->alert(Phpfox::getPhrase('jobposting.you_can_not_perform_this_action'),Phpfox::getPhrase('jobposting.delete_job'),300,100,true);
    		exit;
    	}
		
		if (Phpfox::getService('jobposting.job.process')->delete($job_id))
    	{
    		$this->alert(Phpfox::getPhrase('jobposting.job_successfully_deleted'),Phpfox::getPhrase('jobposting.delete_job'),300,100,true);

    		$sUrl = Phpfox::getLib('url')->makeUrl('jobposting'); 
    		Phpfox::addMessage(Phpfox::getPhrase('jobposting.job_successfully_deleted'));
            if($page_view==2)
            {
                $company_id = $this->get('company_id');
                $sUrl = Phpfox::getLib('url')->makeUrl('jobposting.company').$company_id."/";
            }
            $this->call('setTimeout(function() { location.href = \'' . $sUrl . '\';}, 1500);');
    		
    	}
    	else
    	{
    		$this->alert(Phpfox::getPhrase('jobposting.you_can_not_perform_this_action'),Phpfox::getPhrase('jobposting.delete_job'),300,100,true);
    	}
	}

	public function featureJob()
    {
		Phpfox::isUser(true);
        
		$job_id = $this->get('job_id');
        
		$aJob = Phpfox::getService('jobposting.job')->getJobByJobId($job_id);
        
        if ($aJob['is_featured'])
        {
            $this->alert(Phpfox::getPhrase('jobposting.this_job_has_been_featured'), Phpfox::getPhrase('jobposting.feature_job'));
            return;
        }
		
		if(Phpfox::isAdmin())
		{
			Phpfox::getService("jobposting.job.process")->featureJobs($job_id, 1);
			$this->alert(Phpfox::getPhrase('jobposting.job_successfully_featured'),Phpfox::getPhrase('jobposting.feature_job'),300,100,true);
        	$this->call('setTimeout(function() { location.href = location.href;}, 1500);');
			return;
		}
			
		$sUrl = Phpfox::getLib('url')->permalink('jobposting', $job_id, $aJob['title']);
        
		$sCheckoutUrl = Phpfox::getService('jobposting.job.process')->payForFeature($job_id, $sUrl, true);
		
        if ($sCheckoutUrl === true)
        {
            Phpfox::getService("jobposting.job.process")->featureJobs($job_id, 1);
            $this->alert(Phpfox::getPhrase('jobposting.job_successfully_featured'), Phpfox::getPhrase('jobposting.feature_job'));
        }
        elseif (is_string($sCheckoutUrl))
        {
            $this->call("location.href = '".$sCheckoutUrl."';");
        }
	}

	public function unfeatureJob()
    {
		Phpfox::isUser(true);
        
		$job_id = $this->get('job_id');
        
		$aJob = Phpfox::getService('jobposting.job')->getJobByJobId($job_id);
        
        if ($aJob['is_featured']==0)
        {
            $this->alert(Phpfox::getPhrase('jobposting.this_job_has_been_un_featured'), Phpfox::getPhrase('jobposting.un_feature_job'));
            return;
        }
		
		if(Phpfox::isAdmin())
		{
			Phpfox::getService("jobposting.job.process")->featureJobs($job_id, 0, 0);
			$this->alert(Phpfox::getPhrase('jobposting.job_successfully_un_feature'),Phpfox::getPhrase('jobposting.un_feature_job'),300,100,true);
        	$this->call('setTimeout(function() { location.href = location.href;}, 1500);');
			return;
		}
		
	}
	
	public function approveCompany()
    {
		Phpfox::isUser(true);
		$iCompany = $this->get('id');
        
		if(!Phpfox::getService('jobposting.permission')->canApproveCompany($iCompany, Phpfox::getUserId()))
    	{
    		return $this->alert(Phpfox::getPhrase('jobposting.you_can_not_perform_this_action'),Phpfox::getPhrase('jobposting.approve_company'),300,100,true);
    	}
		if (Phpfox::getService('jobposting.company.process')->approveCompany($iCompany))
    	{
    		$this->alert(Phpfox::getPhrase('jobposting.company_successfully_approved'),Phpfox::getPhrase('jobposting.approve_company'),300,100,true);
            $this->call('setTimeout(function() { location.href = location.href;}, 1500);');
    	}
    	else
    	{
    		$this->alert(Phpfox::getPhrase('jobposting.you_can_not_perform_this_action'),Phpfox::getPhrase('jobposting.approve_company'),300,100,true);
    	}
	}
	
	public function approveJob()
    {
		Phpfox::isUser(true);
		$job_id = $this->get('job_id');
        
		if(!Phpfox::getService('jobposting.permission')->canApproveJob($job_id, Phpfox::getUserId()))
    	{
    		return $this->alert(Phpfox::getPhrase('jobposting.you_can_not_perform_this_action'),Phpfox::getPhrase('jobposting.approve_job'),300,100,true);
    	}
		if (Phpfox::getService('jobposting.job.process')->approveJob($job_id))
    	{
    		$this->alert(Phpfox::getPhrase('jobposting.job_successfully_approved'),Phpfox::getPhrase('jobposting.approve_job'),300,100,true);
            $this->call('setTimeout(function() { location.href = location.href;}, 1500);');
    	}
    	else
    	{
    		$this->alert(Phpfox::getPhrase('jobposting.you_can_not_perform_this_action'),Phpfox::getPhrase('jobposting.approve_job'),300,100,true);
    	}
	}
    
    public function moderationJob()
	{
		Phpfox::isUser(true);
		
		switch ($this->get('action'))
		{
			case 'approve':
				Phpfox::getUserParam('jobposting.can_approve_job', true);
				foreach ((array) $this->get('item_moderate') as $iId)
				{
					Phpfox::getService('jobposting.job.process')->approveJob($iId);
					$this->remove('#js_jp_job_entry_' . $iId);
				}
				$this->updateCount();
				$sMessage = Phpfox::getPhrase('jobposting.job_s_successfully_approved');
				break;			
			case 'delete':
				Phpfox::getUserParam('jobposting.can_delete_job_other_user', true);
				foreach ((array) $this->get('item_moderate') as $iId)
				{
					Phpfox::getService('jobposting.job.process')->delete($iId);
					$this->slideUp('#js_jp_job_entry_' . $iId);
				}
				$sMessage = Phpfox::getPhrase('jobposting.job_s_successfully_deleted');
				break;
		}
		
		$this->alert($sMessage, 'Moderation', 300, 150, true);
		$this->hide('.moderation_process');			
	}
    
    public function moderationCompany()
	{
		Phpfox::isUser(true);
		
		switch ($this->get('action'))
		{
			case 'approve':
				Phpfox::getUserParam('jobposting.can_approve_company', true);
				foreach ((array) $this->get('item_moderate') as $iId)
				{
					Phpfox::getService('jobposting.company.process')->approveCompany($iId);
					$this->remove('#js_jp_company_entry_' . $iId);
				}
				$this->updateCount();
				$sMessage = Phpfox::getPhrase('jobposting.company_s_successfully_approved');
				break;			
			case 'delete':
				Phpfox::getUserParam('jobposting.can_delete_company_other_user', true);
				foreach ((array) $this->get('item_moderate') as $iId)
				{
					Phpfox::getService('jobposting.company.process')->delete($iId);
					$this->slideUp('#js_jp_company_entry_' . $iId);
				}
				$sMessage = Phpfox::getPhrase('jobposting.company_s_successfully_deleted');
				break;
		}
		
		$this->alert($sMessage, Phpfox::getPhrase('jobposting.moderation'), 300, 150, true);
		$this->hide('.moderation_process');			
	}
    
    public function unfavorite()
    {
        Phpfox::isUser(true);
        
        $sType = $this->get('type');
        $iId = $this->get('id');
        $iUserId = Phpfox::getUserId();
        
        if(Phpfox::getService('jobposting.process')->unfavorite($sType, $iId, $iUserId))
        {
            $this->slideUp('#js_jp_'.$sType.'_entry_' . $iId);
            $this->alert(Phpfox::getPhrase('jobposting.unfavorite_successfully'), Phpfox::getPhrase('jobposting.unfavorite'), 300, 150, true);
        }
    }
    
    public function unfollow()
    {
        Phpfox::isUser(true);
        
        $sType = $this->get('type');
        $iId = $this->get('id');
        $iUserId = Phpfox::getUserId();
        
        if(Phpfox::getService('jobposting.process')->unfollow($sType, $iId, $iUserId))
        {
            $this->slideUp('#js_jp_'.$sType.'_entry_' . $iId);
            $this->alert(Phpfox::getPhrase('jobposting.unfollow_successfully'), Phpfox::getPhrase('jobposting.unfollow'), 300, 150, true);
        }
    }
    
}

?>
