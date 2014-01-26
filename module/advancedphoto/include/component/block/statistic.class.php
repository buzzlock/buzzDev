<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * Display the stats of the image we are voting on
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package 		Phpfox_Component
 * @version 		$Id: stat.class.php 2632 2011-05-26 19:28:02Z Raymond_Benc $
 */
class Advancedphoto_Component_Block_Statistic extends Phpfox_Component
{
	public function process()
	{
		$sView = $this->request()->get('view');
		if($sView == 'myalbums' || $sView == 'friend' || defined('PHPFOX_IS_USER_PROFILE'))
		{
			return false;
		}
		$iTotalPhotos = Phpfox::getService('advancedphoto')->getTotalPhotos();
		$iTotalAlbums = Phpfox::getService('advancedphoto')->getTotalAlbums();

		$this->template()->assign(array(
			'sHeader' => Phpfox::getPhrase('advancedphoto.statistic'),
			'iTotalPhotos' => $iTotalPhotos,
			'iTotalAlbums' => $iTotalAlbums
		));
		return 'block';
	}
}