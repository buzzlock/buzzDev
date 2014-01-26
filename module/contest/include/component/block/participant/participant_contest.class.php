<?php

defined('PHPFOX') or exit('NO DICE!');

class Contest_Component_Block_Participant_Participant_Contest extends Phpfox_component {

    public function process() {
        
        $aContest = $this->getParam('aContest');
        $iLimit = 20;
        $iPage = $this->request()->get('page', 0);
        list($iCnt,$aParticipant) = Phpfox::getService('contest.participant')->get($aContest['contest_id'],$iPage,$iLimit);
		
        Phpfox::getLib('pager')->set(array('page' => $iPage, 'size' => $iLimit, 'count' => $iCnt));
        $this->template()->assign(array(
                'aParticipant' => $aParticipant,
            )
        );
    }

}