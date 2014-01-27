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

class JobPosting_Component_Controller_Company_View extends Phpfox_Component 
{
	public function process(){
	   
        Phpfox::getUserParam('jobposting.can_view_company', true);
      
		$sTempView = $this->request()->get('view', false);
		$aCompany = "";
		
        if($sTempView=="mycompany"){
			$aCompany = Phpfox::getService('jobposting.company')->getCompany(Phpfox::getUserId());	
			
			if(!$aCompany)
				Phpfox::getLib("url")->send("jobposting.company.add");
		}
		else {
			$id = $this->request()->get('req3');
                       
			$aCompany = Phpfox::getService('jobposting.company')->getForEdit($id);
                         
		}
                if(!$aCompany){
                    return Phpfox_Error::display(Phpfox::getPhrase('jobposting.the_company_you_are_looking_for_cannot_be_found'));
                }
	    Phpfox::getService('jobposting.company.process')->updateTotalJob($aCompany['company_id']);  
        
            $iCompanyId = $aCompany['company_id'];
        
        #Permission and privacy
        if (!Phpfox::getService('jobposting.permission')->canViewCompany($iCompanyId, Phpfox::getUserId()))
        {
            return Phpfox_Error::display(Phpfox::getPhrase('jobposting.the_company_you_are_looking_for_cannot_be_found'));
        }
        
        if (Phpfox::isModule('privacy'))
		{
			Phpfox::getService('privacy')->check('jobposting', $aCompany['company_id'], $aCompany['user_id'], $aCompany['privacy'], $aCompany['is_friend']);
		}
        
        #Send invitations
		$aVals = $this->request()->getArray('val');
		if(!empty($aVals['submit_invite']))
		{
			Phpfox::getService('jobposting.process')->sendInvitations('company', $iCompanyId, $aVals);
		}
        
		$iPage = 1;
		$aConds = 'job.company_id = '.$iCompanyId;
		$iLimit = 10;
		$ViewMore = 0;
		
		if($aCompany['user_id'] == PHpfox::getUserId() || PHpfox::isAdmin()){
			
		}
		else
		{
			$aConds.= " and job.post_status = 1 and job.time_expire>".PHPFOX_TIME;
		}
		
		list($iCntSearch, $aJobsSearch) = Phpfox::getService("jobposting.job")->searchJobs($aConds, 'job.title ASC', $iPage, $iLimit);
		if(($iPage*$iLimit)<$iCntSearch)
		{
			$ViewMore = 1;
		}
        
        #Favorite
        $iIsFavorited = (Phpfox::getService('jobposting')->isFavorited('company', $iCompanyId, Phpfox::getUserId()) ? 1 : 0);
        
		#Follow
        $iIsFollowed = (Phpfox::getService('jobposting')->isFollowed('company', $iCompanyId, Phpfox::getUserId()) ? 1 : 0);
        
		$this->setParam('aCompany',$aCompany);
		
		//Activity Feed
		$bCanPostComment = true;
		$this->setParam('aFeedCallback', array(
				'module' => 'jobposting',
				'table_prefix' => 'jobposting_',
				'ajax_request' => 'jobposting.addFeedComment',
				'item_id' => $iCompanyId,
				'disable_share' => ($bCanPostComment ? false : true)
			)
		);
		//end Activity Feed
		
        $this->template()->setTitle($aCompany['name'])
        ->setBreadCrumb(Phpfox::getPhrase('jobposting.job_posting'), $this->url()->makeUrl('jobposting'))
        ->setBreadCrumb(Phpfox::getPhrase('jobposting.company'), $this->url()->makeUrl('jobposting.company'),true)
        ->setBreadCrumb('', '', true);
        
		$this -> template() -> setHeader(array(
			'global.css' => 'module_jobposting',
			'ynjobposting.css' => 'module_jobposting',
			'feed.js' => 'module_feed',
			'jquery/plugin/jquery.highlightFade.js' => 'static_script',	
			'jquery/plugin/jquery.scrollTo.js' => 'static_script',
			'comment.css' => 'style_css',
			'jobposting.js' => 'module_jobposting',
		))->assign(array(
			'aCompany' => Phpfox::getService("jobposting.permission")->allPermissionForCompany($aCompany, Phpfox::getUserId()),
			'aJobsSearch' => $aJobsSearch,
			'iCntSearch' => $iCntSearch,
			'ViewMoreJob' => $ViewMore,
			'iPage' => $iPage,
            'iIsFavorited' => $iIsFavorited,
            'iIsFollowed' => $iIsFollowed
		));	
	}
}

?>