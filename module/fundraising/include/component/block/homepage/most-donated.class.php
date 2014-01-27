<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class Fundraising_Component_Block_Homepage_Most_Donated extends Phpfox_Component {

	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process() {
		$iLimit = 9;
		$aMostDonatedCampaigns = Phpfox::getService('fundraising.campaign')->getCampaigns($sType = 'most-donated', $iLimit );
		if(!$aMostDonatedCampaigns)
		{
			return false;
		}
		$this->template()->assign(array(
				'aMostDonatedCampaigns' => $aMostDonatedCampaigns,
				'sHeader' => ''
			)
		);
		return 'block';
	}

}

?>
