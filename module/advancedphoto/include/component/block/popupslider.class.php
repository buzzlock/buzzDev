<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond_Benc
 * @package 		Phpfox_Component
 * @version 		$Id: attachment.class.php 2627 2011-05-24 19:04:14Z Raymond_Benc $
 */
class Advancedphoto_Component_Block_popupslider extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		$aAlbum = $this->getParam("aAlbum");
		$aConditions = array();
		$aConditions[] = 'p.album_id = ' . $aAlbum['album_id'] . '';
		list($iCnt, $aPhotos) = Phpfox::getService('advancedphoto')->get($aConditions, 'p.yn_ordering DESC', 0, 9999);
		$aAlbum = Phpfox::getService('advancedphoto.album')->getForView($aAlbum['album_id']);
		foreach ($aPhotos as $aPhoto)
		{
			Phpfox::getService("advancedphoto.process")->cropCenterlize($aPhoto["destination"], $aPhoto["width"], $aPhoto["height"]);
		}

		$this->template()->assign(array(
			'aPhotos' => $aPhotos,
			'aForms' => $aAlbum,
		));
	}
}

?>