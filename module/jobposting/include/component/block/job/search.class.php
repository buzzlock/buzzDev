<?php

defined('PHPFOX') or exit('NO DICE!');

class jobposting_component_block_job_search extends Phpfox_component{

	public function process ()
	{
		$aIndustryBlock = PHpfox::getService('jobposting.category')->get(2);
		
		$this->template()->assign(array(
				'sHeader' => Phpfox::getPhrase('jobposting.search'),
				'aIndustryBlock' => $aIndustryBlock,
			)
		);
		
		return 'block';
	}
}