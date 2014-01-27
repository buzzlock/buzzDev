<?php

defined('PHPFOX') or exit('NO DICE!');

class jobposting_component_block_job_recent extends Phpfox_component{

	public function process ()
	{
		$iLimit = Phpfox::getParam('jobposting.number_of_items_block_recent_youmay');
		$order = 'job.time_stamp desc';

		$aBlockJobs = Phpfox::getService('jobposting.job')->getBlockJob(null, $order, $iLimit);
		
		$this->template()->assign(array(
				'sHeader' => Phpfox::getPhrase('jobposting.recent_job_posting'),
				'aBlockJobs' => $aBlockJobs,
			)
		);
		
		return 'block';
	}
}