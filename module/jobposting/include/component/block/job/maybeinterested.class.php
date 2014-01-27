<?php

defined('PHPFOX') or exit('NO DICE!');

class jobposting_component_block_job_MaybeInterested extends Phpfox_component{

	public function process ()
	{
		$iLimit = Phpfox::getParam('jobposting.number_of_items_block_recent_youmay');
		$oJobs = PHpfox::getService("jobposting.job");
		$order = 'job.time_stamp desc';
		$Conds = null;
		$aSubscribe = $oJobs->getSubscribe();
		if(isset($aSubscribe['subscribe_id']))
		{
			$Conds = $oJobs->implementConditions($aSubscribe);
		}	 
		$aBlockJobs = $oJobs->getBlockJob($Conds, $order, $iLimit);
		if(count($aBlockJobs)==0)
		{
			$aBlockJobs = Phpfox::getService('jobposting.job')->getBlockJob(null, $order, $iLimit);
		}
		
		$this->template()->assign(array(
				'sHeader' => Phpfox::getPhrase('jobposting.jobs_you_may_be_interested_in'),
				'aBlockJobs' => $aBlockJobs
			)
		);
		
		return 'block';
	}
}