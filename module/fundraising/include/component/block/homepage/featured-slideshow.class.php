<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class Fundraising_Component_Block_Homepage_Featured_Slideshow extends Phpfox_Component {

	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process() {
		$iLimit = Phpfox::getParam('fundraising.number_of_campaigns_on_featured_slideshow');
//		$iLimit = 14;
		$aFeaturedCampaigns = Phpfox::getService('fundraising.campaign')->getCampaigns($sType = 'featured', $iLimit );

		if(count($aFeaturedCampaigns) == 0)
		{
			return false;
		}

		foreach($aFeaturedCampaigns as &$aCampaign)
		{
			$aCampaign = Phpfox::getService('fundraising.campaign')->retrieveMoreInfoFromCampaign($aCampaign);
			$aCampaign['donor_list'] = Phpfox::getService('fundraising.user')->getDonorsOfCampaign($aCampaign['campaign_id'],  $iPageSize = 14);
		}

		$this->template()->assign(array(
				'aFeaturedCampaigns' => $aFeaturedCampaigns,
				'sNoimageUrl' => Phpfox::getLib('template')->getStyle('image', 'noimage/' . 'profile_50.png'),
				'sCorePath' => Phpfox::getParam('core.path'),
			)
		);
		return 'block';
	}

}

?>
