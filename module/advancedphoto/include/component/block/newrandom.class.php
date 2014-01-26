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
 * @version 		$Id: featured.class.php 346$iNumberOfPhotos 2011-11-07 16:51:48Z Raymond_Benc $
 */
class Advancedphoto_Component_Block_Newrandom extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		
		$iNumberOfPhotos = Phpfox::getParam('advancedphoto.number_of_photos_displayed_on_blocks_newrandom_on_homepage');

		list($iTotalImages, $aNewestPhotos) = Phpfox::getService('advancedphoto')->getNewestPhotos($iNumberOfPhotos);
		list($iTotalImages, $aRandomPhotos) = Phpfox::getService('advancedphoto')->getRandomPhotos($iNumberOfPhotos);
		$sViewAllRecentLink = Phpfox::getService('advancedphoto.helper')->getLinkViewAll('recent');
		$this->template()->assign(array(
					'sHeader' => "",
					'corepath' => phpfox::getParam('core.path'),
					'sViewAllRecentLink' => $sViewAllRecentLink,
					'aRandomPhotos' => $aRandomPhotos,
					'aNewestPhotos' => $aNewestPhotos
				)
			);	
		return 'block';
	}
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('advancedphoto.component_block_newrandom_clean')) ? eval($sPlugin) : false);
	}
}
?>