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
class Resume_Component_Controller_whoviewedme extends Phpfox_Component
{
	public function process()
	{
		Phpfox::isUser(TRUE);
		
		$iViewerId = Phpfox::getUserId();
		
		// Setup breadcrumb
		$this->template()->setBreadcrumb(Phpfox::getPhrase('resume.resume'),$this->url()->makeUrl('resume'));
		
		// Build filter section menu on left side
		$aFilterMenu = array();
		if (!defined('PHPFOX_IS_USER_PROFILE')) 
		{
			$aFilterMenu = array(
				Phpfox::getPhrase('resume.all_resumes') => '',
				TRUE,
				Phpfox::getPhrase('resume.my_resumes') 		 => 'my',
				Phpfox::getPhrase('resume.my_noted_resumes') => 'noted',
				Phpfox::getPhrase('resume.my_favorite_resumes') => 'favorite',
				TRUE,
				Phpfox::getPhrase('resume.who_viewed_me') 	 => 'resume.whoviewedme'
			);
		}
		$this -> template() -> buildSectionMenu('resume', $aFilterMenu);
		
		// Set action url for searching
		$sActionUrl = $this->url()->makeUrl('resume.whoviewedme');
		
		// Check "Who Viewed Me" Service Registration
		$bWhoViewRegistration = Phpfox::getService('resume.account')->checkWhoViewRegistration($iViewerId);
		$iCnt = 0;
		if($bWhoViewRegistration)
		{
			// Set up variables and search fields
			$sSearchNumber = Phpfox::getParam('resume.total_resume_display');
			
			if($sSearchNumber)
			{
				$aSearchNumber = explode(',',str_replace(" ", "", Phpfox::getParam('resume.total_resume_display')));
			}
			else 
			{
				$aSearchNumber = array(5,10,15,20,25);
			}
			
			$this->search()->set(
				array(
					'type' => 'resume',
					'field'=> 'rb.resume_id',
					'search' =>	'search',
					'search_tool' => array(
						'table_alias'  => 'rv',
						'search'=> array(
							'action' 	   => $sActionUrl,
							'default_value'=> Phpfox::getPhrase('resume.search_members'),
							'name'		   => 'search',
							'field'		   => 'u1.full_name'
						),
						'sort'	=> array(
							'latest' 		 => array('rv.time_stamp', Phpfox::getPhrase('resume.latest')),
							'most-viewed' 	 => array('rv.total_view', Phpfox::getPhrase('resume.most_viewed')),				
						),
						'show' => $aSearchNumber
					)
				)
			);
	
			// Setup search conditions
			
			$aCurrentPublishedResume = Phpfox::getService('resume')->getPublishedResumeByUserId($iViewerId);
			
			$this->search()->setCondition("and rv.owner_id = {$iViewerId}");
	
			if($aCurrentPublishedResume)
			{
				$this->search()->setCondition("and rv.resume_id = {$aCurrentPublishedResume['resume_id']}");
			}
			else
			{
				$this->search()->setCondition("and rv.resume_id = 0");
			}
			
			// Setup search params
			$aBrowseParams = array(
				'module_id' => 'resume',
				'alias' => 'rv',
				'field' => 'resume_id',
				'table' => Phpfox::getT('resume_viewme'),
				'hide_view' => array('my')
			);
			
			$this->search()->browse()->params($aBrowseParams)->execute();
			
			// Resume item list
			$aResumes = $this->search()->browse()->getRows();
			
			
			// Setup pager
			
			Phpfox::getLib('pager')->set(
				array(
					'page'  => $this->search()->getPage(), 
					'size'  => $this->search()->getDisplay(), 
					'count' => $this->search()->browse()->getCount()
				)
			);
		}
		else
		{
			$iLimit = Phpfox::getUserParam('resume.resume_viewer_numbers');
			list($iCnt,$aResumes) = Phpfox::getService('resume.viewme')->getWhoViewed($iViewerId, $iLimit);
		}
		
		// Assign variables and set header
		
		$this -> template()	
			  -> assign(array(
						'sCorePath'  	  		=> phpfox::getParam('core.path'),
						'aResumes'   	  		=> $aResumes,
						'iCnt'					=> $bWhoViewRegistration?$this->search()->browse()->getCount():$iCnt,
						'bWhoViewRegistration'	=> $bWhoViewRegistration
				 ))
			  -> setHeader(array(
			  			'resume.css' => 'module_resume',
			  			'resume.js'  => 'module_resume',
						'atooltip.css' => 'module_resume',
						'jquery.atooltip.min.js' => 'module_resume',
						'country.js' => 'module_core'	
			  	 ))
			  -> setPhrase(array(
			  			'resume.publish_resume'
			  	 ));
			
	}

	public function clean()
	{

		(($sPlugin = Phpfox_Plugin::get('resume.component_controller_index_clean')) ? eval($sPlugin) : false);
		(($sPlugin = Phpfox_Plugin::get('resume.component_controller_whoviewedme_clean')) ? eval($sPlugin) : false);
	}
}