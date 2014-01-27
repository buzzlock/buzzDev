<?php

defined('PHPFOX') or exit('NO DICE!');

class JobPosting_Component_Block_Company_participant_company extends Phpfox_Component{

    public function process() {
        
    	$aParticipant = array();
		$iPage = 1;
		$aCompany = $this->getParam('aCompany');
		
		$sCond = "uf.company_id = ".$aCompany['company_id'];
		$iLimit = 6;
		list($iCntEmployee, $aParticipant) = Phpfox::getService('jobposting.company')->searchEmployees($sCond, $iPage, $iLimit);
		$ViewMore = 0;
		if(($iPage*$iLimit+1)<$iCntEmployee)
		{
			$ViewMore = 1;
		}
        
        $this->template()->assign(array(
                'aParticipant' => $aParticipant,
                'aCompany' => $aCompany,
                'ViewMore' => $ViewMore,
				'iPage' => $iPage
            )
        );
    }

}