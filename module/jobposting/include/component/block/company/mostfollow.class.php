<?php

defined('PHPFOX') or exit('NO DICE!');

class jobposting_component_block_Company_mostfollow extends Phpfox_component{

	public function process ()
	{
		$iLimit = Phpfox::getParam('jobposting.number_of_items_block_top_mostfollowed_employers');
		$order = 'ca.total_follow desc';
		$aBlockCompanies = Phpfox::getService('jobposting.company')->getBlockCompany(null, $order, $iLimit);
		
		
		$this->template()->assign(array(
				'sHeader' => Phpfox::getPhrase('jobposting.most_followed_employers'),
				'aBlockCompanies' => $aBlockCompanies,
				'type_id' => 2, 
			)
		);
		
		return 'block';
	}
}