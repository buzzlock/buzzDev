<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class Fundraising_Component_Block_Campaign_Side_Campaign_Owner extends Phpfox_Component
{
    /**
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
		$aCampaign  = $this->getParam('aFrCampaign');
		if(!$aCampaign)
		{
			return false;
		}

		$aUserInfo = array(
			'title' => $aCampaign['full_name'],
			'path' => 'core.url_user',
			'file' => $aCampaign['user_image'],
			'suffix' => '_200',
			'max_width' => 175,
			'max_height' => 300,
			'no_default' => (Phpfox::getUserId() == $aCampaign['user_id'] ? false : true),
			'thickbox' => true,
        	'class' => 'profile_user_image',
			'no_link' => true
		);		

		$sImage = Phpfox::getLib('image.helper')->display(array_merge(array('user' => Phpfox::getService('user')->getUserFields(true, $aCampaign)), $aUserInfo));	

		
		$aCampaignOwner = Phpfox::getService('fundraising.user')->getCampaignOwnerProfile($aCampaign['user_id']);
		$this->setParam('aRatingCallback', array(
			'type' => 'fundraising_owner',
			'total_rating' => Phpfox::getPhrase('fundraising.total_rating_ratings', array('total_rating' => $aCampaignOwner['total_rating'])),
			'default_rating' => $aCampaignOwner['avg_rating'],
			'item_id' => $aCampaign['user_id'],
			'stars' => array(
				'2' => Phpfox::getPhrase('fundraising.poor'),
				'4' => Phpfox::getPhrase('fundraising.nothing_special'),
				'6' => Phpfox::getPhrase('fundraising.worth_donating'),
				'8' => Phpfox::getPhrase('fundraising.pretty_cool'),
				'10' => Phpfox::getPhrase('fundraising.awesome')
			)
				)
		);
		
		$this->template()->assign(array(
				'sHeader' => Phpfox::getPhrase('fundraising.campaign_owner'),
				'aCampaign' => $aCampaign,
				'sLink' => Phpfox::getParam('core.path'),
				'sCampaignOwnerImage' => $sImage
			)
		);
		return 'block';
    }
    
}

?>