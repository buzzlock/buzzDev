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
class Resume_Component_Controller_Publish extends Phpfox_Component
{
	public function process()
	{	
		// Check user login requirement	
		Phpfox::isUser(true);
		$iUserId = Phpfox::getUserBy('user_id');
		
		//Get related resume
		$iId = $this->request()->getInt('id');
		
		//Quick get resume
		$aResume = Phpfox::getService('resume.basic')->getQuick($iId);
		
		// Process if the user is the owner of the resume
		if ($aResume && $aResume['user_id'] == $iUserId)
		{
			if (Phpfox::getService('resume.process')->setPublish($aResume))
            {
                $this->url()->send("resume.view_my", array(), Phpfox::getPhrase("resume.your_resume_had_been_published_successfully"));
            }
			else
            {
                $this->url()->send("resume.view_my", array(), Phpfox::getPhrase("resume.public_resumes_have_been_reached_limit"));
            }
		}
		else
		{
			return Phpfox_Error::set(Phpfox::getPhrase("resume.cannot_found_the_related_resume"));
		}
	}
}

?>