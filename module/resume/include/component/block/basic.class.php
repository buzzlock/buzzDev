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
class Resume_Component_Block_Basic extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		if($this->request()->get('req2') != 'view')
		{
			return false;
		}
		
		// Get related resume
		$aOptions = array();
		$iResumeId = $this->request()->getInt('req3');
		$aResume = Phpfox::getService("resume.basic")->getBasicInfo($iResumeId);
		$aOptions['no_resume'] = FALSE;
		
		if(!$aResume) 
		{
			$this->template()->assign(array(
				'aOptions' => $aOptions
			));			
			return;
		}
		if(!$aResume['headline'])
		{
			$aResume['headline'] = Phpfox::getPhrase('resume.untitled_resume');
		}
		
		// Setup permissions
		$aOptions['can_edit']   	  = FALSE;
		$aOptions['can_delete']	 	  = FALSE;
		$aOptions['can_note'] 		  = FALSE;
		$aOptions['can_send_message'] = FALSE;
		$aOptions['can_favorite'] 	  = Phpfox::getUserParam('resume.can_favorite_resumes');
		$aOptions['can_export_resume']    = TRUE;
		if($aResume['user_id'] == Phpfox::getUserId())
		{
			$aOptions['can_edit']     = TRUE;
			$aOptions['can_favorite'] = FALSE;
			$aOptions['can_delete']   = Phpfox::getUserParam('resume.can_delete_own_resumes');
		}
		else
		{
			$aOptions['can_send_message'] = TRUE;
			$aOptions['can_note']   	  = TRUE;
			$aOptions['can_edit']   	  = Phpfox::getUserParam('resume.can_edit_other_resume');
			$aOptions['can_delete'] 	  = Phpfox::getUserParam('resume.can_delete_other_resumes');
		}
		
		if($aResume['status'] == 'approving')
		{
			$aOptions['can_edit']   = FALSE;
			$aOptions['can_delete'] = FALSE;
		}
		
		// Check Favorite
		$aOptions['favorited'] = phpfox::getService('resume')->isFavorite($aResume['resume_id']);
		
		// Check Note
		$aOptions['noted'] = Phpfox::getService('resume')->isNote($aResume['resume_id']);
		
		//Get Category List
		$aCats = Phpfox::getService('resume.category')->getCatNameList($iResumeId);
		
		// Get Gender
		$aResume['gender_parsed'] = Phpfox::getService('user')->gender($aResume['gender']);
		
		// Get Birthday
		if($aResume['birthday'])
		{
			$aBirthDay = Phpfox::getService('user')->getAgeArray($aResume['birthday']);
			$aResume['birthday_parsed']=Phpfox::getTime(Phpfox::getParam('user.user_dob_month_day_year'), mktime(0, 0, 0, $aBirthDay['month'], $aBirthDay['day'], $aBirthDay['year']), false);
		}
		else
		{
			$aResume['birthday_parsed'] = "";
		}
		// Get Level
		$aResume['level_name'] = Phpfox::getService('resume.level')->getLevelById($aResume['level_id']);
		$aResume['authorized_position'] = Phpfox::getService('resume.level')->getLevelById($aResume['authorized_level_id']);
		
		// Parse Skills 
		if(Phpfox::getLib('parse.format')->isSerialized($aResume['skills']))
		{
			$aSkills = unserialize($aResume['skills']);
			$aResume['skills'] = explode(',', $aSkills);
		}
		
		// Get Current Working Place
		$aCurrentWork = Phpfox::getService('resume.experience')->getCurrentWork($iResumeId);
		$linkdownloadpdf = PHpfox::getParam('core.path').'module/resume/static/php/downloadzip.php?id='.$aResume['resume_id'];
		// Get latest education 
		$aLatestEducation = Phpfox::getService('resume.education')->getLatestEducation($iResumeId);
		$aAccount = Phpfox::getService("resume.account")->getAccount();
		$this->template()->assign(array(
				'aResume' 			=> $aResume,
				'aCurrentWork' 		=> $aCurrentWork,
				'aLatestEducation'	=> $aLatestEducation,
				'aCats'				=> $aCats,
				'aOptions'			=> $aOptions,
				'aAccount'          => $aAccount,
                                'linkdownloadpdf' => $linkdownloadpdf,
		));
		
	}
}