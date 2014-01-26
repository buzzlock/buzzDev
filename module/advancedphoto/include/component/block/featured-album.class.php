<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * Used to display a featured image and is setup to refresh X number of milliseconds.
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Photo
 * @version 		$Id: featured.class.php 3469 2011-11-07 16:51:48Z Raymond_Benc $
 */
class Advancedphoto_Component_Block_Featured_Album extends Phpfox_Component
{
	private function _checkIsInAlbumHomePage()
	{
		$bIsInHomePage = false;
		$aParentModule = $this->getParam('aParentModule');	
		$sTempView = $this->request()->get('view', false);
		 if ($sTempView == "" && !isset($aParentModule['module_id']) && !$this->request()->get('search-id')
                && !$this->request()->get('sort')
                && $this->request()->get('req3') === '') {
            if (!defined('PHPFOX_IS_USER_PROFILE')) {
				$bIsInHomePage = true;
            }
        }

		return $bIsInHomePage;
	}
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		
		if (defined('PHPFOX_IS_GROUP_VIEW') || defined('PHPFOX_IS_PAGES_VIEW') || defined('PHPFOX_IS_USER_PROFILE') || !$this->_checkIsInAlbumHomePage())
		{
			return false;
		}
		
		// Get the featured random image
		$bNoStaticCache = true;
		$iNumOfAlbums = Phpfox::getParam('advancedphoto.number_of_photos_display_on_featured_slide');
		list($iTotalImages, $aFeatured) = Phpfox::getService('advancedphoto.album')->getFeatured($iNumOfAlbums, $bNoStaticCache);
				
		// If not images were featured lets get out of here
		if (!count($aFeatured))
		{
			return false;
		}
		
		$aFeaturedImage = $aFeatured[rand(0, (count($iTotalImages) - 1))];
		
		$sCorePath = Phpfox::getParam('core.path');
		$sPhotoPath = Phpfox::getParam('photo.url_photo');
		foreach($aFeatured as &$aAlbum)
		{
			$sImagePath = PHPFOX_DIR_FILE . 'pic' . PHPFOX_DS . 'photo' .  PHPFOX_DS . sprintf($aAlbum['destination'], '_500');
			if(is_dir($sImagePath) || !file_exists($sImagePath))
			{

				$aAlbum['slideshow_big_image_url'] =  str_replace('\\', '/', $sCorePath . 'module' . PHPFOX_DS . 'advancedphoto' . PHPFOX_DS . 'static' . PHPFOX_DS . 'image' . PHPFOX_DS . 'item-noimage.png');

			}
			else
			{
				$aAlbum['slideshow_big_image_url'] =  $sPhotoPath . sprintf($aAlbum['destination'], '_500');
			}
		}
		// If this is not AJAX lets display the block header, footer etc...
		if (!PHPFOX_IS_AJAX)
		{
			$this->template()->assign(array(
					'sHeader' => "",
					'sBlockJsId' => 'featured_photo',
					'corepath' => Phpfox::getParam('core.path'),
					'aFeatureds' => $aFeatured
				)
			);	
		}
		
		// Assign template vars
		$this->template()->assign(array(				
				'aFeaturedImage' => $aFeaturedImage,
				'iRefreshTime' => Phpfox::getService('advancedphoto')->getFeaturedRefreshTime()
			)
		);	
		
		if (Phpfox::getUserParam('advancedphoto.can_feature_photo'))
		{
			$this->template()->assign(array(
					//'aFooter' => array(Phpfox::getPhrase('advancedphoto.unfeature') => $this->url()->makeUrl('photo', array('unfeature' => $aFeaturedImage['photo_id'])))
				)
			);
		}
		
		return 'block';
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('advancedphoto.component_block_featured_clean')) ? eval($sPlugin) : false);
	}
}

?>