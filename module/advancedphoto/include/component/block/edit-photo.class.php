<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package 		Phpfox_Component
 * @version 		$Id: edit-photo.class.php 2610 2011-05-19 18:43:08Z Raymond_Benc $
 */
class Advancedphoto_Component_Block_Edit_Photo extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		if (($iPhotoId = $this->getParam('ajax_photo_id')))
		{
			$aPhoto = Phpfox::getService('advancedphoto')->getForEdit($this->request()->get('photo_id'));
			list($iAlbumCnt, $aAlbums) = Phpfox::getService('advancedphoto.album')->get('pa.user_id = ' . Phpfox::getUserId());
			
			$this->template()->assign(array(
					'aForms' => $aPhoto,
					'aAlbums' => $aAlbums,
					'bSingleMode' => true,
					'bIsInline' => $this->request()->get('inline', false)
				)
			);
		}
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('advancedphoto.component_block_edit_photo_clean')) ? eval($sPlugin) : false);
	}
}

?>