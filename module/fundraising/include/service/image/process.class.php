<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class Fundraising_Service_Image_Process extends Phpfox_Service {

	private $_aSizes = array(50, 120, 200, 240, 500);
	/**
	 * Class constructor
	 */
	public function __construct() {
		$this->_sTable = Phpfox::getT('fundraising_image');
		$this->_sDirFundraising = Phpfox::getService('fundraising.image')->getFundraisingImageDir(); 
		if(!is_dir($this->_sDirFundraising))
		{
			mkdir($this->_sDirFundraising);
		}
	}

	public function delete($iImageId) {
		$aImage = Phpfox::getService('fundraising.image')->getImageById($iImageId);
		if (!isset($aImage['campaign_id'])) {
			return Phpfox_Error::set(Phpfox::getPhrase('fundraising.unable_to_find_the_image'));
		}

		$iFileSizes = 0;
		$aSizes = $this->_aSizes;
		//delete original image
		$sImage = Phpfox::getParam('core.dir_pic') . sprintf($aImage['image_path'], '');
		if (file_exists($sImage)) {
			$iFileSizes += filesize($sImage);

			@unlink($sImage);
		}
		
		foreach ($aSizes as $iSize) {
			$sImage = Phpfox::getParam('core.dir_pic') . sprintf($aImage['image_path'], (empty($iSize) ? '' : '_' ) . $iSize);
			if (file_exists($sImage)) {
				$iFileSizes += filesize($sImage);

				@unlink($sImage);
			}
		}

		$this->database()->delete($this->_sTable, 'image_id = ' . $aImage['image_id']);
		$iNewImageId = 0;

		$aCampaign = Phpfox::getService('fundraising.campaign')->getCampaignById($aImage['campaign_id']);
		//in case the deleted image is default image
		if ($aImage['image_path'] == $aCampaign['image_path']) {
			$aCampaignImages = Phpfox::getService('fundraising.image')->getImagesOfCampaign($aImage['campaign_id']);

			//if still having images, get the first one
			if (!empty($aCampaignImages)) {
				$iNewImageId = $aCampaignImages[0]['image_id'];
			}
			$this->database()->update(Phpfox::getT('fundraising_campaign'), array('image_path' => (empty($aCampaignImages) ? null : $aCampaignImages[0]['image_path'] ), 'server_id' => (empty($aCampaignImages) ? null : $aCampaignImages[0]['server_id'])), 'campaign_id = ' . $aImage['campaign_id']);
		}

		if ($iFileSizes > 0) {
			//@todo: update this table later
//			Phpfox::getService('user.space')->update($aImage['user_id'], 'campaign', $iFileSizes, '-');
		}

		return $iNewImageId;
	}

	public function uploadImages($iCampaignId) {
		// Multi-upload
		if (isset($_FILES['image'])) {
			$oImage = Phpfox::getLib('image');
			$oFile = Phpfox::getLib('file');
			$sInvalid = '';
			$iFileSizes = 0;
			$iUploaded = 0;

			$iMaxUpload = Phpfox::getUserParam('fundraising.total_photo_upload_limit');
			$aImages = Phpfox::getService('fundraising.image')->getImagesOfCampaign($iCampaignId);

			if (count($aImages) > 0) {
				$iMaxUpload = $iMaxUpload - count($aImages);
			}

			foreach ($_FILES['image']['error'] as $iKey => $sError) {
				if ($iUploaded == $iMaxUpload) {
					break;
				}
				if ($sError == UPLOAD_ERR_OK) {
					if ($aImage = $oFile->load('image[' . $iKey . ']', array(
						'jpg',
						'gif',
						'png'
							), (Phpfox::getUserParam('fundraising.max_upload_size_fundraising') === 0 ? null : (Phpfox::getUserParam('fundraising.max_upload_size_fundraising') / 1024))
							)
					) {
						$iUploaded++;
						$sFileName = Phpfox::getLib('file')->upload('image[' . $iKey . ']', $this->_sDirFundraising, $iCampaignId);

						$iFileSizes += filesize($this->_sDirFundraising . sprintf($sFileName, ''));

						$this->database()->insert(Phpfox::getT('fundraising_image'), array('campaign_id' => $iCampaignId, 'image_path' => 'fundraising/' . $sFileName, 'server_id' => Phpfox::getLib('request')->getServer('PHPFOX_SERVER_ID')));

						Phpfox::getService('fundraising.campaign')->notifyToAllFollowers($iCampaignId, 'image');

						$aSizes = $this->_aSizes;
						foreach ($aSizes as $iSize) {
							$oImage->createThumbnail($this->_sDirFundraising . sprintf($sFileName, ''), $this->_sDirFundraising . sprintf($sFileName, '_' . $iSize), $iSize, $iSize);
							$iFileSizes += filesize($this->_sDirFundraising . sprintf($sFileName, '_' . $iSize));
						}
					} else {
						if ($sInvalid != '')
							$sInvalid .= '<li>' . $_FILES['image']['name'][$iKey] . '</li>';
						else
							$sInvalid = '<li>' . $_FILES['image']['name'][$iKey] . '</li>';
					}
				}
			}

			if(isset($sInvalid) && $sInvalid != '')
			{
//			   Phpfox_Error::set(Phpfox::getPhrase('fundraising.invalid_files'). '<br/><ul style="margin-left: 20px">'. $sInvalid.'</ul>');
			}    

			if ($iFileSizes != 0 && $sInvalid == '') {
				// Update user space usage
				// @todo: add this later
//				Phpfox::getService('user.space')->update(Phpfox::getUserId(), 'fundraising', $iFileSizes);
				$aUpdate['image_path'] = 'fundraising/' . $sFileName;
				$aUpdate['server_id'] = Phpfox::getLib('request')->getServer('PHPFOX_SERVER_ID');
			}
		}
	}

}

?>
