<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class Fundraising_Component_Block_Homepage_Latest extends Phpfox_Component {

	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process() {

		$iLimit = 9;
		$aLatestCampaigns = Phpfox::getService('fundraising.campaign')->getCampaigns($sType = 'latest', $iLimit );
		if(!$aLatestCampaigns)
		{
			return false;
		}
		$this->template()->assign(array(
				'aLatestCampaigns' => $aLatestCampaigns,
				'sHeader' => ''
			)
		);
		return 'block';
	}

}

?>
