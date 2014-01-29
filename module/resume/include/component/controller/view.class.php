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
class Resume_Component_Controller_View extends Phpfox_Component
{
	public function process()
	{
		// Check view permission	
		
		$iViewerId = Phpfox::getUserId();
		
		// Build filter section menu on left side
		$this -> template() ->setBreadcrumb(Phpfox::getPhrase('resume.resume'),$this->url()->makeUrl('resume'));
		
		$aFilterMenu = array();
		if (!defined('PHPFOX_IS_USER_PROFILE')) 
		{
			$aFilterMenu = array(
				Phpfox::getPhrase('resume.all_resumes') => '',
				true,
				Phpfox::getPhrase('resume.my_resumes') 		 => 'my',
				Phpfox::getPhrase('resume.my_noted_resumes') => 'noted',
				Phpfox::getPhrase('resume.my_favorite_resumes') => 'favorite',
				true,
				Phpfox::getPhrase('resume.who_viewed_me') 	 => 'resume.whoviewedme'
			);
		}
		$this -> template() -> buildSectionMenu('resume', $aFilterMenu);
		
		// Get resume
		if($this->request()->get('req2') != 'view')
		{
			$this->url()->send("subscribe");			
		}
		
		$iResumeId = $this->request()->getInt('req3');
		
		$aResume = Phpfox::getService('resume.basic')->getQuick($iResumeId);
		
        $check_permission = Phpfox::getService('resume.permission')->canViewResume($aResume);
                
		if(!$check_permission)
		{
			Phpfox::getLib("url")->send("subscribe");
		}
		// Setup breadcrumbs
		if(Phpfox::getUserId() == $aResume['user_id'])
		{
			$this -> template() ->setBreadcrumb(Phpfox::getPhrase('resume.my_resumes'),$this->url()->makeUrl('resume.view_my'));
		}
		$this -> template() ->setBreadcrumb('','',true);
		
		// Check can view mode
		$bCanView = true;
		
		if(!$aResume)
		{
			$bCanView = false;
			$this->template()->assign(array(
				'bCanView'=> $bCanView
			));
			return Phpfox_Error::set(Phpfox::getPhrase('resume.resume_not_found'));
		}
		
		if(!Phpfox::isAdmin() && $aResume['user_id'] != Phpfox::getUserId() && (!$aResume['is_published'] || $aResume['status'] != 'approved' || !$aResume['is_completed']))
		{
			$bCanView = false;
			$this->template()->assign(array(
				'bCanView'=> $bCanView
			));
			Phpfox::getLib("url")->send("subscribe");
		}

		// Calculate completeness percent
		$percentfinish = 100;
        $aUncomplete = array();
        
        list($score, $aListUncomplete, $total_marks) = Phpfox::getService("resume.completeness")->calculate($iResumeId);
        $aUncomplete = Phpfox::getService("resume.completeness")->showUnComplete($aListUncomplete, $iResumeId);
        if ($total_marks !== 0)
        {
            $percentfinish = round($score * 100 / $total_marks);
        }
		
		// Update Status when admin change weight setting but the resume had not been updated
		if($aResume['is_published'] == 0 && $aResume['status'] != 'approved')
		{
			Phpfox::getService('resume')->updateStatus($aResume['resume_id']);
		}
		
		// Check Add Note
		$bIsAddNote = false;
		if($aNote = $this->request()->get('note'))
		{
			$bIsAddNote = true;
			Phpfox::getService('resume')->addNote($aNote);	
		}

		// Update view resume
		if($iViewerId != 0 && $iViewerId != $aResume['user_id'] && !$bIsAddNote)
		{
			
			Phpfox::getService('resume.viewme')->updateViewResume($aResume);
		}
		//end
		
		
		//support custom fields
		$aCustom = Phpfox::getService('resume.custom')->getFields($iResumeId);
		
		$turnonFields = false;
		 if (isset($aCustom[0])) {
            foreach ($aCustom as $iKey => $aField) {
            	
                $sValue = $aField['value'];
				
                if (preg_match("/^\[.*?\]$/", $sValue)) {
                    $aValues = explode(",", trim($sValue, '[]'));
                    $sValue = "";
                    foreach ($aValues as $sVal) {
                        $sVal = trim($sVal, '"');
                        $sValue .= "<li>$sVal</li>";
                    }
                    $sValue = '<ul>' . $sValue . '</ul>';
                }
                $aField['value'] = $sValue;
               
				if($sValue!="")
				{
					$turnonFields = true;
				}
                $aCustom[$iKey] = $aField;
            }
            $aCustomFields = $aCustom;
        } else {
            $aCustomFields = array();
        }
	
		
		$this->template()->assign(array(
			'sCorePath' => Phpfox::getParam('core.path'),
			'percentfinish' => $percentfinish,
			'aUncomplete' => $aUncomplete,
			'aViewCustomFields' => $aCustomFields,
			'bCanView'	=> $bCanView,
			'turnonFields' => $turnonFields,	
		))
		-> setHeader(array(
			'resume.css' => 'module_resume',
			'resume.js'	 => 'module_resume'
		));
	}
	
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('resume.component_controller_index_clean')) ? eval($sPlugin) : false);
		(($sPlugin = Phpfox_Plugin::get('resume.component_controller_view_clean')) ? eval($sPlugin) : false);
	}
}