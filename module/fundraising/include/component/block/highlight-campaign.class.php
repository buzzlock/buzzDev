<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class Fundraising_Component_Block_Highlight_Campaign extends Phpfox_Component {

	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process() {

        if(Phpfox::isMobile())
            return false;

        //$Campaign_id = (Phpfox::getParam('campaign_id'))?Phpfox::getParam('campaign_id'):0;

		$sHeader = '';
		/**
		 *this param is used when using iframe, to know the condition of badge code 
		 */
		$iStatus = 0;
		$bIsBadge = $this->getParam('bIsBadge', false);
		if($bIsBadge)
		{
			$iCampaignId = $this->getParam('iCampaignId');
			$iStatus = $this->getParam('iStatus');
			if($iCampaignId)
			{
				$aCampaign = Phpfox::getService('fundraising.campaign')->getCampaignById($iCampaignId);
			}
		}
		else
		{
			$sHeader = Phpfox::getPhrase('fundraising.highlight_campaign');
			$aCampaign = Phpfox::getService('fundraising.campaign')->getHightlightCampaign();
		}

        if(!$aCampaign || defined('PHPFOX_IS_USER_PROFILE') || defined('PHPFOX_IS_PAGES_VIEW'))
		{
            return false;
		}

        $aCampaign['can_donate'] = 1;

      	$aCampaign = Phpfox::getService('fundraising.campaign')->retrieveMoreInfoFromCampaign($aCampaign); 

		$aDonors = Phpfox::getService('fundraising.user')->getDonorsOfCampaign($aCampaign['campaign_id'], $iPageSize = Phpfox::getParam('fundraising.number_of_donors_in_highlight_campaign_block'));

		$this->template()->assign(array(
				'sHeader' => $sHeader,
                'core_path' => Phpfox::getParam('core.path'),
                'aCampaign' => $aCampaign,
				'aDonors' => $aDonors,
				'iStatus' => $iStatus,
				'aStatus' => Phpfox::getService('fundraising')->getAllBadgeStatus()
			
			)
		);
		return 'block';
	}

}

?>
