<?php

defined('PHPFOX') or exit('NO DICE!');

class Contest_Component_Block_Who_Vote_Entry extends Phpfox_component {

    public function process() {

        $aEntry = $this->getParam('aEntry');
        $aVotes = Phpfox::getService("contest.entry")->getListVotesByEntryId($aEntry['entry_id']);
		if(count($aVotes)>0)
		{
			$this->template()->assign(array(
				'sHeader' => Phpfox::getPhrase('contest.who_voted_this_entry'),
			));
		}
        $this->template()->assign(array(
            'aVotes' => $aVotes,
                )
        );

        return 'block';
    }

}