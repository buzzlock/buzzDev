<?php

defined('PHPFOX') or exit('NO DICE!');

class Contest_Component_Block_Entry_View_Content extends Phpfox_component{

	public function process ()
	{
		$iItemId = $this->getParam('iItemId');
		$iItemType = $this->getParam('iItemType');
		$iContestId = $this->getParam('iContestId');

		$aYnContestEntryItem = Phpfox::getService('contest.entry')->getEntryItem($i);

		$this->template()->assign(array(
			
			));
	}
}