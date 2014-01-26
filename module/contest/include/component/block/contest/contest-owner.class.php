<?php

defined('PHPFOX') or exit('NO DICE!');

class Contest_Component_Block_Contest_Contest_Owner extends Phpfox_component{

	public function process ()
	{
		$aContest = $this->getParam('aContest');
		
		$this->template()->assign(array(
				'sHeader' => Phpfox::getPhrase('contest.contest_owner'),
				'corepath' => phpfox::getParam('core.path'),
				'aItem' => $aContest
			)
		);	
		return 'block';	
	}
}