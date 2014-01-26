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
class Advancedphoto_Component_Block_Viewcommentshare extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$iNumberOfPhotos = Phpfox::getParam('advancedphoto.number_of_photos_displayed_on_top_blocks_on_homepage');
		list($iTotalMostViewed, $aMostViewedPhotos) = Phpfox::getService('advancedphoto')->getMostViewedPhotos($iNumberOfPhotos);
		list($iTotalMostCommented, $aMostCommentedPhotos) = Phpfox::getService('advancedphoto')->getMostCommentedPhotos($iNumberOfPhotos);
		list($iTotalMostLiked, $aMostLikedPhotos) = Phpfox::getService('advancedphoto')->getMostLikedPhotos($iNumberOfPhotos);

		$sViewAllMostViewedLink = Phpfox::getService('advancedphoto.helper')->getLinkViewAll('most-viewed');
		$sViewAllMostCommentedLink = Phpfox::getService('advancedphoto.helper')->getLinkViewAll('most-commented');
		$sViewAllMostLikedLink = Phpfox::getService('advancedphoto.helper')->getLinkViewAll('most-liked');
		$this->template()->assign(array(
					'sHeader' => "",
					'corepath' => phpfox::getParam('core.path'),
					'sViewAllMostViewedLink' => $sViewAllMostViewedLink,
					'sViewAllMostCommentedLink' => $sViewAllMostCommentedLink, 
					'sViewAllMostLikedLink' => $sViewAllMostLikedLink,
					'aMostViewedPhotos' => $aMostViewedPhotos,
					'aMostCommentedPhotos' => $aMostCommentedPhotos,
					'aMostLikedPhotos' => $aMostLikedPhotos,
					'ynadvphoto_thickbox.js'=> 'module_advancedphoto',
				)
			);	
		$this->template()->setHeader(array(
		));
		return 'block';
	}
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('advancedphoto.component_block_viewcommentshare_clean')) ? eval($sPlugin) : false);
	}
}
?>