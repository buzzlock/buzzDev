<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class Fundraising_Component_Block_Homepage_Most_Liked extends Phpfox_Component {

	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process() {
		$iLimit = 9;
		$aMostLikedCampaigns = Phpfox::getService('fundraising.campaign')->getCampaigns($sType = 'most-liked', $iLimit );
		if(!$aMostLikedCampaigns)
		{
			return false;
		}
		$this->template()->assign(array(
				'aMostLikedCampaigns' => $aMostLikedCampaigns,
				'sHeader' => ''
			)
		);
		return 'block';
	}

}

?>
