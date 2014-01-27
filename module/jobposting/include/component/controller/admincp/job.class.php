<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class JobPosting_Component_Controller_Admincp_Job extends Phpfox_Component
{
	public function implement($aJobs){
		foreach($aJobs as $key=>$Jobs){
			
			if($Jobs['time_expire']<=PHPFOX_TIME){
				$Jobs['status_jobs'] = Phpfox::getPhrase('jobposting.expired');
			}
			else {
				if($Jobs['post_status']==1 && $Jobs['is_approved']==0)
				{
					$Jobs['status_jobs'] = Phpfox::getPhrase('jobposting.pending');
				}
				else{
					if($Jobs['post_status']==1)
						$Jobs['status_jobs'] = Phpfox::getPhrase('jobposting.published');
					else if ($Jobs['post_status']==0){
						$Jobs['status_jobs'] = Phpfox::getPhrase('jobposting.draft');
					}	
				}		
			}
			$aJobs[$key] = $Jobs;
		}
		
		return $aJobs;
	}
	public function process()
	{				
		
		$iPage = $this->request()->getInt('page');
		
		$aPages = array(5, 10, 15, 20);
		$aDisplays = array();
		foreach ($aPages as $iPageCnt)
		{
			$aDisplays[$iPageCnt] = Phpfox::getPhrase('core.per_page', array('total' => $iPageCnt));
		}		
	
		
		$Feature = array('%' => 'Any',
			'1' => Phpfox::getPhrase('jobposting.feature'),
			'2' => Phpfox::getPhrase('jobposting.not_feature'),
			
		);
		
		$Status = array('%' => 'Any',
			'1' => Phpfox::getPhrase('jobposting.published'),
			'2' => Phpfox::getPhrase('jobposting.draft'),
			'3' => Phpfox::getPhrase('jobposting.expired'),
			'4' => Phpfox::getPhrase('jobposting.pending'),
		);

		$aFilters = array(
			'search' => array(
				'type' => 'input:text',
				'search' => "AND job.title LIKE '%[VALUE]%'"
			),	
			'searchcompany' => array(
				'type' => 'input:text',
				'search' => "AND ca.name LIKE '%[VALUE]%'"
			),	
			'user' => array(
				'type' => 'input:text',
				'search' => "AND u.full_name LIKE '%[VALUE]%'"
			),
			'feature' => array(
				'type' => 'select',
				'options' => $Feature,				
				
			),
			'status' => array(
				'type' => 'select',
				'options' => $Status,				
				
			),
		);		
		
		$oSearch = Phpfox::getLib('search')->set(array(
				'type' => 'jobposting',
				'filters' => $aFilters,
				'search' => 'search'
			)
		);
		
		$iLimit = $oSearch->getDisplay();
		
		$Conds = $oSearch->getConditions();
		
		if($this->search()->get('feature'))
		{
			switch($this->search()->get('feature'))
			{
				case '1':
					$Conds[] = "AND is_featured = 1 and job.post_status = 1";
					break;
				case '2':
					$Conds[] = "AND is_featured = 0 and job.post_status = 1";
					break;
			}
		}

		if($this->search()->get('status'))
		{
			switch($this->search()->get('status'))
			{
				case '1':
					$Conds[] = "AND job.is_approved = 1  AND job.post_status = 1 AND job.time_expire>".PHPFOX_TIME;
					break;
				case '2':
					$Conds[] = "AND job.post_status = 0 AND job.time_expire>".PHPFOX_TIME;
					break;
				case '3':
					$Conds[] = "AND job.time_expire<=".PHPFOX_TIME;
					break;
				case '4':
					$Conds[] = "AND job.is_approved = 0  and job.post_status = 1 and job.time_expire>".PHPFOX_TIME;
					break;
			}
		}
		
		//get code search block at block 3
		$aCategory = $this->search()->get('category');
		
		$aVals['categories'] = "";
		if(isset($aCategory['search_0']))
		{
			$aValue = str_replace("search_", "", $aCategory['search_0']);
		
			$aVals['categories'].= $aValue;
			if(isset($aCategory["search_".$aValue]))
			{
				$aValue = str_replace("search_", "", $aCategory["search_".$aValue]);
				$aVals['categories'].= ",".$aValue;
			}
		}
		
		if(!empty($aVals['categories'])){
			
			$listcategory = trim($aVals['categories'],",");
			$aList = explode(",", $listcategory);
			if(count($aList)>=2){
				if($aList[1]>0)
					$listcategory = $aList[1];
				else {
					$listcategory = $aList[0];
				}
			}
			$Conds[] = " AND 0<(select(count(*)) from ".Phpfox::getT('jobposting_category_data')." data where data.company_id = job.company_id and data.category_id in (".$listcategory."))";
			$this->template()->setHeader(array(
				'<script type="text/javascript">$Behavior.eventEditIndustry = function(){  var aCategories = explode(\',\', \'' . $aVals['categories'] . '\'); for (i in aCategories) { $(\'#js_mp_holder_\' + aCategories[i]).show(); $(\'#js_mp_category_item_\' + aCategories[i]).attr(\'selected\', true); } }</script>'
			));
		}
	
		//end
		list($iCnt, $aJobs) = Phpfox::getService('jobposting.job')->searchJobs($Conds, 'job.time_stamp DESC' , $oSearch->getPage(), $iLimit);
		
		Phpfox::getLib('pager')->set(array('page' => $iPage, 'size' => $iLimit, 'count' => $oSearch->getSearchTotal($iCnt)));
		$aIndustryBlock = PHpfox::getService('jobposting.category')->get(2);
		$this->template()->setTitle("Job Posting")
			->setBreadcrumb("Manage Companies", $this->url()->makeUrl('admincp.jobposting'))
			->assign(array(
					'aJobs' 	=> $this->implement($aJobs),
					'aIndustryBlock' => $aIndustryBlock,
					'iTotalResults' => $iCnt,
				)
			)
			->setHeader('cache', array(
				'quick_edit.js' => 'static_script',
				 'industry.js' => 'module_jobposting'
			)			
		);

	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('fundraising.component_controller_admincp_index_clean')) ? eval($sPlugin) : false);
	}
}

?>
