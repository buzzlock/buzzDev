<?php

defined('PHPFOX') or exit('NO DICE!');

class jobposting_component_block_Company_TopEmployer extends Phpfox_component{

	public function process ()
	{
		$iLimit = Phpfox::getParam('jobposting.number_of_items_block_top_mostfollowed_employers');
		$order = 'ca.total_job desc';
		$aBlockCompanies = Phpfox::getService('jobposting.company')->getBlockCompany(null, $order, $iLimit);
		
		$this->template()->assign(array(
				'sHeader' => Phpfox::getPhrase('jobposting.top_employers'),
				'aBlockCompanies' => $aBlockCompanies,
				'type_id' => 1, 
			)
		);
		
		return 'block';
	}
}