<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 *
 *
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		younet
 * @package 		Phpfox_Component
 * @version 		3.01
 */
 
class Resume_Component_Controller_Import extends Phpfox_Component
{
	public function process()
	{

		$iId = 0;

		$fields = '~:(picture-url,id,first-name,summary,email-address,last-name,industry,headline,siteStandardProfileRequest,associations,honors,interests,publications,patents,languages,skills,certifications,educations,courses,volunteer,three-current-positions,three-past-positions,num-recommenders,recommendations-received,mfeed-rss-url,job-bookmarks,suggestions,date-of-birth,member-url-resources,phone-numbers,im-accounts,main-address,twitter-accounts,primary-twitter-account)';

		$apiLinkedIn = Phpfox::getService('socialbridge') -> getProvider('linkedin') -> getApi();

		$apiLinkedIn -> setResponseFormat('JSON');

		$response = $apiLinkedIn -> profile($fields);

		$linkedin_data = @json_decode($response['linkedin'], $as_assoc = FALSE);
	
		if ($linkedin_data)
		{
			
			$aVals = Phpfox::getService("resume.process") -> import($linkedin_data);
                       
			$aVals['linkedin'] = 1;
			$aVals['resume_id'] = $iId = Phpfox::getService("resume.basic.process") -> add($aVals);
		
			Phpfox::getService("resume.summary.process") -> update($aVals);
			
			Phpfox::getService("resume.basic.process") -> updatePositionSection($iId, 9);
			
			Phpfox::getService("resume.process") -> importEducation($linkedin_data, $iId);
			
			Phpfox::getService("resume.process") -> importskills($linkedin_data, $iId);
			
			Phpfox::getService("resume.process") -> importExperience($linkedin_data, $iId);
			
			Phpfox::getService("resume.process") -> importpublications($linkedin_data, $iId);
			
			Phpfox::getService("resume.process") -> importlanguages($linkedin_data, $iId);
			
			Phpfox::getService("resume.process") -> importcertifications($linkedin_data, $iId);
			
			Phpfox::getService("resume.process") -> ImportAddition($linkedin_data, $iId);
		}
		
		if($iId)
		{
			Phpfox::getLib('url') -> send('resume.add.id_'.$iId,null, Phpfox::getPhrase('resume.import_resume_successfully'));
			exit;
		}
		
		// if failed.
		$this->template()->assign(array('iErrorCode' => 2));
		Phpfox_Error::set(Phpfox::getPhrase('resume.cannot_import_resume_from_linkedIn'));
	}
	
	
	public function clean()
	{

	}

}