<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

class Fundraising_Component_Block_Campaign_Gallery extends Phpfox_Component
{
    /**
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
//		$iLimit = Phpfox::getParam('fundraising.maximum_number_of_photos_on_campaign_gallery');
		$iCampaignId = $this->getParam('iCampaignId');
		$aGalleryVideo = Phpfox::getService('fundraising.video')->getVideoOfCampaign($iCampaignId);
		//It will fix the slide issue
		$aGalleryImages = Phpfox::getService('fundraising.image')->getImagesOfCampaign($iCampaignId, 7);

		if(!$aGalleryVideo && !$aGalleryImages)
		{
			return false;
		}
		$this->template()->assign(array(
			'aGalleryVideo' => $aGalleryVideo,
			'aGalleryImages' => $aGalleryImages,
			'sCorePath' => Phpfox::getParam('core.path'),
		));
    }
    
}

?>