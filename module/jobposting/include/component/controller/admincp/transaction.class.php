<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class JobPosting_Component_Controller_Admincp_Transaction extends Phpfox_Component
{
	
	private function _convertToTimeStamp($sDate, $bToDate = false)
	{
		// Generate date information array 
		$aDate = explode('/', $sDate);
		if(count($aDate)!=3 || strlen(trim($sDate))>10)
		{
			return '';
		}	
		
		// Convert to timestamp
		if(!$bToDate)
		{
			$sTimeStamp = phpfox::getLib('date')->mktime(0,0,0,$aDate[0], $aDate[1], $aDate[2]);	
		}
		else 
		{		
			$sTimeStamp = phpfox::getLib('date')->mktime(23,59,59,$aDate[0], $aDate[1], $aDate[2]);	
		}
		
		// Return timestamp result
		if(isset($sTimeStamp) && $sTimeStamp != '')
		{
			return $sTimeStamp;
		}
		return '';
	}

	
	private function implementFields($aRows){
		
		$aType = array(
            1 => Phpfox::getPhrase("jobposting.sponsor"),
            2 => Phpfox::getPhrase('jobposting.package'),
            3 => Phpfox::getPhrase('jobposting.package'),
            4 => Phpfox::getPhrase('jobposting.feature_package'),
            5 => Phpfox::getPhrase('jobposting.feature')
    	);
	
		$format_time = 'F j, Y g:i a';			
		foreach($aRows as $key=>$aRow){
			
			$aRow['type_text'] = $aType[$aRow['payment_type']];
			$aRow['invoice'] = unserialize($aRow['invoice']);
			$aRow['invoice_text'] = '';
			$aRow['job_text'] = Phpfox::getPhrase('jobposting.n_a'); 
			$aRow['is_job_text'] = 0;
			$aRow['job_id'] = 0;
			$aRow['title'] = "";
			
            if(isset($aRow['invoice']['package_data']) && count($aRow['invoice']['package_data'])>0)
			{
				//get info Package
				foreach($aRow['invoice']['package_data'] as $invoice){
					
					$aPackage = Phpfox::getService('jobposting.package')->getPackageByDataId($invoice);
					if($aPackage){
					
						$aRow['invoice_text'].= $aPackage['name'].", ";
					}
				}
			}
			else {
				$aRow['invoice_text'] = Phpfox::getPhrase('jobposting.n_a');
			}
            
            //get info Job
			if(isset($aRow['invoice']['feature'])){	
				$job_id = $aRow['invoice']['feature'];
				
				if($job_id>0)
				{
					$aJob = PHpfox::getService("jobposting.job")->getJobByJobId($job_id);
					if($aJob){
						$aRow['job_text'] = $aJob['title'];
						$aRow['is_job_text'] = 1;
						$aRow['job_id'] = $job_id;
						$aRow['title'] = $aJob['title'];
					}	
				}	
			}
            
            $aRow['time_stamp_text'] = Phpfox::getTime($format_time, $aRow['time_stamp'], false);
			$aRow['invoice_text'] = trim($aRow['invoice_text']);
			$aRow['invoice_text'] = trim($aRow['invoice_text'],",");
			$aRow['amount_text'] = PHpfox::getService('jobposting.helper')->getTextParseCurrency($aRow['amount']);
			$aRows[$key] = $aRow;
		}

		return $aRows;
	}

	private function TotalMoney($aRows){
		$total = array();
		
		foreach($aRows as $key=>$aRow){
		
			if(!isset($total[$aRow['currency']]))
				$total[$aRow['currency']] = 0;
				
			$total[$aRow['currency']] += $aRow['amount'];	
		}
		
		return $total;
	}
	
	public function process()
	{	
		// Get global setting
	
		$iPage 		= $this->request()->getInt('page');
		$iPageSize 	= 20;
		
		
		$aConds 	= array();
		
		$oSearch 	= Phpfox::getLib('search')->set(array(
						'type' => 'request',
						'search' => 'search',
					  ));
		$sFromDate 	= $oSearch->get('fromdate');
		$sToDate 	= $oSearch->get('todate');
		$sType      = $oSearch->get('type');
		$company    = $oSearch->get('company');
		$status_pay = $oSearch->get('status_pay');
		$aForms = array();
		$aForms['company'] = $company;
		$aForms['type'] = $sType;
		$aForms['status_pay'] = $status_pay;
		$this->template()->assign(array(
			'aForms' => $aForms,
		));
		$oSearch->setCondition(' 1=1 ');
		if($company)
		{
			$oSearch->setCondition(" and ca.name like '%".$company."%'");	
		}
		if($sFromDate)
		{
			$iFromDateTimeStamp = $this->_convertToTimeStamp($sFromDate,false);
			
			if(!$iFromDateTimeStamp)
			{
				$oSearch->setCondition(' and 1!=1 ');
			}
			else {
				$oSearch->setCondition(" and tr.time_stamp >= ".$iFromDateTimeStamp);	
			}	
		}
		
		if($sToDate)
		{
			$iToDateTimeStamp = $this->_convertToTimeStamp($sToDate,true);
			
			if(!$iToDateTimeStamp){
				$oSearch->setCondition(' and 1!=1 ');
			}
			else {
				$oSearch->setCondition(" and tr.time_stamp <= ".$iToDateTimeStamp);	
			}
		}
		
		if($sType)
		{
            switch($sType)
            {
                case 1: //sponsor
                    $oSearch->setCondition(' AND tr.payment_type = 1');
                    break;
                case 2: //package
                    $oSearch->setCondition(' AND tr.payment_type IN(2,3,4)');
                    break;
                case 4: //feature
                    $oSearch->setCondition(' AND tr.payment_type IN(4,5)');
                    break;
                default:
            }
		}
		
		if($status_pay)
		{
			if($status_pay==2){
				$oSearch->setCondition(' and tr.status != 3');
			}
			else if($status_pay==3){
				$oSearch->setCondition(' and tr.status = 3');
			}
		}
		
		//Set pager
		$iCount = Phpfox::getService('jobposting.transaction')->getItemCount($oSearch->getConditions());
		
				
		// Set header 
		$this->template()->setHeader(array(
			'jobposting_backend.css' => 'module_jobposting',
		));
		
		phpFox::getLib('pager')->set(array(
				'page'  => $iPage, 
				'size'  => $iPageSize, 
				'count' => $iCount
		));
		
		// Get category list
		//$aCategories = phpFox::getService('karaoke.category')->getAllCategories();
		
		// Get resume list
		
		$aTransactions = Phpfox::getService('jobposting.transaction')->getTransaction($oSearch->getConditions(), 'tr.transaction_id desc,ca.time_stamp desc', $iPage, $iPageSize, $iCount);
		
		// Set page header
		$this -> template() -> setHeader(array(
			'manage_request.js' => 'module_jobposting', 
		));
		
		// Set breadcrumb
		$this->template()->setBreadCrumb(Phpfox::getPhrase('jobposting.manage_transactions'), $this->url()->makeUrl('admincp.jobposting.transaction'));
		
		// Assign variable for layout
		$this -> template() -> assign(array(
			'aTransactions'   => $this->implementFields($aTransactions),
			'total_money' => $this->TotalMoney($aTransactions),
			'sFromDate'	=> $sFromDate,
			'sToDate'   => $sToDate,
			'sType'  => $sType,			
		));
		
		// Set page phrase for jscript call
		$this->template()->setPhrase(array(
			
		));
		
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('jobposting.component_controller_admincp_transaction_clean')) ? eval($sPlugin) : false);
	}
}

?>
