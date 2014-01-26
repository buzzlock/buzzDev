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
class Advancedphoto_Component_Block_Otheralbum extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		
		$aAlbum = $this->getParam('aAlbum', false);
		if(!$aAlbum)
		{
			return false;
		}
		$iNumberOfAlbums = Phpfox::getParam('advancedphoto.number_of_albums_in_other_album_block') 	;
		list($iTotalImages, $aAlbums) = Phpfox::getService('advancedphoto.album')->getOtherAlbumsOfSameUploader($aAlbum['user_id'], $iNumberOfAlbums);
		$this->template()->assign(array(
					'sHeader' => Phpfox::getPhrase('advancedphoto.other_albums'),
					'corepath' => phpfox::getParam('core.path'),
					'aAlbums' => $aAlbums
				)
			);	
		return 'block';
	}
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('advancedphoto.component_block_viewcommentshare_clean')) ? eval($sPlugin) : false);
	}
}
?>