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
class Advancedphoto_Component_Block_AlbumCommentviewEntry extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{	
		$aPhoto = $this->getParam('aPhoto');
		$aUser = $this->getParam('aUser');
		$aPhoto = Phpfox::getService('advancedphoto')->getPhoto($aPhoto['photo_id'], $aUser['user_id']);
		$aPhoto['link'] = Phpfox::permalink('advancedphoto', $aPhoto['photo_id'], $aPhoto['title']);
		$this->setParam('aFeed', array(				
				'comment_type_id' => 'advancedphoto',
				'privacy' => $aPhoto['privacy'],
				'comment_privacy' => $aPhoto['privacy_comment'],
				'like_type_id' => 'advancedphoto',
				'feed_is_liked' => $aPhoto['is_liked'],
				'feed_is_friend' => $aPhoto['is_friend'],
				'item_id' => $aPhoto['photo_id'],
				'user_id' => $aPhoto['user_id'],
				'total_comment' => $aPhoto['total_comment'],
				'total_like' => $aPhoto['total_like'],
				'feed_link' => $this->url()->permalink('advancedphoto', $aPhoto['photo_id'], $aPhoto['title']),
				'feed_title' => $aPhoto['title'],
				'feed_display' => 'view',
				'feed_total_like' => $aPhoto['total_like'],
				'report_module' => 'advancedphoto',
				'report_phrase' => Phpfox::getPhrase('advancedphoto.report_this_photo')
			)
		);	

		$this->template()->assign(array(
			'aPhoto' => $aPhoto,
			'aForms' => $aPhoto,
			'sJsPhotoTagContent' => Phpfox::getService('advancedphoto.tag')->getJsPhotoCommentViewTagContent($aPhoto['photo_id'])
		));
	}
	
}

?>