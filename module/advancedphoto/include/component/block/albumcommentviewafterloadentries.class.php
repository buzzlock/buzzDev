<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * Controller to view images on a users profile.
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Photo
 * @version 		$Id: view.class.php 4532 2012-07-19 10:03:18Z Miguel_Espinoza $
 */
class Advancedphoto_Component_Block_Albumcommentviewafterloadentries extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{	
		$aAlbum = $this->getParam('aAlbum');
		$aUser = $this->getParam('aUser');

		$this->template()->assign(array(
			'aForms' => $aAlbum,
		));

		$this->setParam('aFeed', array(
				'comment_type_id' => 'advancedphoto_album',
				'privacy' => $aAlbum['privacy'],
				'comment_privacy' => $aAlbum['privacy_comment'],
				'like_type_id' => 'advancedphoto_album',
				'feed_is_liked' => $aAlbum['is_liked'],
				'feed_is_friend' => $aAlbum['is_friend'],
				'item_id' => $aAlbum['album_id'],
				'user_id' => $aAlbum['user_id'],
				'total_comment' => $aAlbum['total_comment'],
				'total_like' => $aAlbum['total_like'],
				'feed_link' => $this->url()->permalink('advancedphoto.album', $aAlbum['album_id'], $aAlbum['name']),
				'feed_title' => $aAlbum['name'],
				'feed_display' => 'view',
				'feed_total_like' => $aAlbum['total_like'],
				'report_module' => 'advancedphoto_album',
				'report_phrase' => Phpfox::getPhrase('advancedphoto.report_this_photo_album')
			)
		);
	}
	
}

?>