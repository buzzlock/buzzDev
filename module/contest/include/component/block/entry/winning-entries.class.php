<?php

defined('PHPFOX') or exit('NO DICE!');

class Contest_Component_Block_Entry_Winning_Entries extends Phpfox_component {

    public function process() {
        
        $aContest = $this->getParam('aContest');
		
		$sUrl = Phpfox::getLib('url')->permaLink('contest', $aContest['contest_id'],$aContest['contest_name']);
        $iLimit = 20;
        $iPage = $this->request()->get('page', 0);
        list($iCnt,$aEntries) = Phpfox::getService('contest.entry')->get($aContest['contest_id'],$iPage,$iLimit);
    	foreach($aEntries as $key=>$aEntry){
			$aEntry['delete'] = 1;
            $aEntry = Phpfox::getService('contest.entry')->retrieveEntryPermission($aEntry);
    		$aEntries[$key] = $aEntry;
    	}
		
		
		
        Phpfox::getLib('pager')->set(array('page' => $iPage, 'size' => $iLimit, 'count' => $iCnt));
        $this->template()->assign(array(
                'aEntries' => $aEntries,
                'sUrl' => $sUrl,
                'is_hidden_action' => Phpfox::getService('contest.permission')->canHideAction($aContest),
            )
        );
		
		
    }

}