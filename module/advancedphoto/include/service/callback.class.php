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
 * @package  		Module_Photo
 * @version 		$Id: callback.class.php 4545 2012-07-20 10:40:35Z Raymond_Benc $
 */
class Advancedphoto_Service_Callback extends Phpfox_Service 
{
	/**
	 * Class constructor
	 */	
	public function __construct()
	{	
		$this->_sTable = Phpfox::getT('photo');
		// if the notification module is disabled we cannot get the length to shorten, so we fallback to _iFallbackLength.
		$this->_iFallbackLength = 50;
	}

	public function getNotificationAlbum_Tag($aNotification)
	{
		$aRow = $this->database()->select('b.album_id, b.name, b.user_id, u.gender, u.full_name')	
			->from(Phpfox::getT('photo_album'), 'b')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = b.user_id')
			->where('b.album_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
		
		if (!isset($aRow['user_id']) || empty($aRow['user_id']))
		{
			return false;
		}
		$sPhrase = '';
		if ($aRow['user_id'] == Phpfox::getUserId())		
		{
			$sPhrase = Phpfox::getPhrase('advancedphoto.you_tagged_yourself_in_your_album_title', array('title' => Phpfox::getLib('parse.output')->shorten($aRow['name'], (Phpfox::isModule('notification') ? Phpfox::getParam('notification.total_notification_title_length') : $this->_iFallbackLength ), '...')));				
		}
		else 
		{
			$sPhrase = Phpfox::getPhrase('advancedphoto.user_name_tagged_you_in_span_class_drop_data_user_full_name_s_span_album_title',array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'full_name' => $aRow['full_name'], 'title' => Phpfox::getLib('parse.output')->shorten($aRow['name'], (Phpfox::isModule('notification') ? Phpfox::getParam('notification.total_notification_title_length') : $this->_iFallbackLength ), '...')));
		}
			
		return array(
			'link' => Phpfox::getLib('url')->permalink('advancedphoto.album', $aRow['album_id'], $aRow['name']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);		
		
	}
	
	public function getSiteStatsForAdmin($iStartTime, $iEndTime)
	{
		$aCond = array();
		$aCond[] = 'view_id = 0';
		if ($iStartTime > 0)
		{
			$aCond[] = 'AND time_stamp >= \'' . $this->database()->escape($iStartTime) . '\'';
		}	
		if ($iEndTime > 0)
		{
			$aCond[] = 'AND time_stamp <= \'' . $this->database()->escape($iEndTime) . '\'';
		}			
		
		$iCnt = (int) $this->database()->select('COUNT(*)')
			->from($this->_sTable)
			->where($aCond)
			->execute('getSlaveField');
		
		return array(
			'phrase' => Phpfox::getPhrase('advancedphoto.advanced_photos'),
			'total' => $iCnt
		);
	}	
	
	public function getApiSupportedMethods()
	{
		$aMethods = array();
		
		$aMethods[] = array(
			'call' => 'addPhoto',
			'requires' => array(
				'photo' => '@FILE_PATH;type=MIME_TYPE',
				'photo_name' => 'BASE_FILE_NAME'
			),
			'detail' => 'Uploads a photo on behalf of the user.',
			'type' => 'POST',			
			'response' => '{"api":{"total":0,"pages":0,"current_page":0},"output":{"original":"http:\/\/[DOMAIN_REPLACE]\/file\/pic\/photo\/2011\/08\/39740c5e356fdd6f66cfc973d4aebf6d.jpg","75px":"http:\/\/[DOMAIN_REPLACE]\/file\/pic\/photo\/2011\/08\/39740c5e356fdd6f66cfc973d4aebf6d_75.jpg","100px":"http:\/\/[DOMAIN_REPLACE]\/file\/pic\/photo\/2011\/08\/39740c5e356fdd6f66cfc973d4aebf6d_100.jpg","150px":"http:\/\/[DOMAIN_REPLACE]\/file\/pic\/photo\/2011\/08\/39740c5e356fdd6f66cfc973d4aebf6d_150.jpg","240px":"http:\/\/[DOMAIN_REPLACE]\/file\/pic\/photo\/2011\/08\/39740c5e356fdd6f66cfc973d4aebf6d_240.jpg","500px":"http:\/\/[DOMAIN_REPLACE]\/file\/pic\/photo\/2011\/08\/39740c5e356fdd6f66cfc973d4aebf6d_500.jpg","1024px":"http:\/\/[DOMAIN_REPLACE]\/file\/pic\/photo\/2011\/08\/39740c5e356fdd6f66cfc973d4aebf6d_1024.jpg"}}'			 
		);		
		
		$aMethods[] = array(
			'call' => 'getPhotos',
			'requires' => array(
				'user_id' => 'user_id'
			),
			'detail' => Phpfox::getPhrase('advancedphoto.get_all_the_photos_for_a_user_if_you_do_not_pass_the_user_id_we_will_return_information_about_the_user_that_is_currently_logged_in'),
			'type' => 'GET',			
			'response' => '{"api":{"total":1,"pages":0,"current_page":0},"output":[{"photo_100px":"http:\/\/[DOMAIN_REPLACE]\/file\/pic\/photo\/c81e728d9d4c2f636f067f89cc14862c_100.jpg","photo_240px":"http:\/\/[DOMAIN_REPLACE]\/file\/pic\/photo\/c81e728d9d4c2f636f067f89cc14862c_240.jpg","photo_original":"http:\/\/[DOMAIN_REPLACE]\/file\/pic\/photo\/c81e728d9d4c2f636f067f89cc14862c.jpg"}]}'			 
		);		
		
		return array(
			'module' => 'advancedphoto', 
			'module_info' => '', 
			'methods' => $aMethods
		);
	}
	
	public function mobileMenu()
	{
		return array(
			'phrase' => Phpfox::getPhrase('advancedphoto.photos'),
			'link' => Phpfox::getLib('url')->makeUrl('advancedphoto'),
			'icon' => Phpfox::getLib('image.helper')->display(array('theme' => 'mobile/small_photos.png'))
		);
	}	

	public function enableSponsor($aParams)
	{
	    return Phpfox::getService('advancedphoto.process')->sponsor($aParams['item_id'], 1);
	}

	public function getLink($aParams)
	{
	    $aPhoto = $this->database()->select('p.title, p.photo_id')
		    ->from(Phpfox::getT('photo'),'p')
		    ->join(Phpfox::getT('user'), 'u', 'u.user_id = p.user_id')
		    ->where('p.photo_id = ' . (int)$aParams['item_id'])
		    ->execute('getSlaveRow');
	    if (empty($aPhoto))
	    {
			return false;
	    }
	    //return Phpfox::getLib('url')->makeUrl($aPhoto['user_name'].'.photo.view.' . $aPhoto['title_url'] );
		return Phpfox::permalink('advancedphoto', $aPhoto['photo_id'], $aPhoto['title']);
	}
	
	public function getProfileLink()
	{
		return 'profile.advancedphoto';
	}

	public function getAjaxCommentVar()
	{
		return 'advancedphoto.can_post_on_photos';
	}		
	
	public function getTagLink()
	{
		return Phpfox::getLib('url')->makeUrl('advancedphoto.tag');
	}	
	
	public function getTagType()
	{
		return 'advancedphoto';
	}

	public function getCommentItem($iId)
	{
		$aRow = $this->database()->select('photo_id AS comment_item_id, privacy_comment, user_id AS comment_user_id')
			->from(Phpfox::getT('photo'))
			->where('photo_id = ' . (int) $iId)
			->execute('getSlaveRow');

		$aRow['comment_view_id'] = '0';
		
		if (!Phpfox::getService('comment')->canPostComment($aRow['comment_user_id'], $aRow['privacy_comment']))
		{
			Phpfox_Error::set(Phpfox::getPhrase('advancedphoto.unable_to_post_a_comment_on_this_item_due_to_privacy_settings'));
			
			unset($aRow['comment_item_id']);
		}
			
		return $aRow;
	}	
	
	public function deleteComment($iId)
	{
		Phpfox::getService('advancedphoto.process')->updateCounter($iId, 'total_comment', true);	
	}	
	
	public function getFeedRedirectAlbum($iId)
	{
		$aAlbum = $this->database()->select('a.album_id, a.name, u.user_name')
			->from(Phpfox::getT('photo_album'), 'a')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = a.user_id')
			->where('a.album_id = ' . (int) $iId)
			->execute('getRow');
		
		if (!isset($aAlbum['album_id']))
		{
			return false;
		}
			
		return Phpfox::getLib('url')->permalink('advancedphoto.album', $aAlbum['album_id'], $aAlbum['name']);		
	}
	
	public function getFeedRedirectAlbum_FeedLike($iId, $iChild = 0)
	{
		return $this->getFeedRedirectAlbum($iChild);	
	}	
	
	public function getFeedRedirectFeedLike($iId, $iChild = 0)
	{
		return $this->getFeedRedirect($iChild);	
	}
	
	public function getFeedRedirect($iId, $iChild = 0)
	{
		$aPhoto = $this->database()->select('p.photo_id, p.title')
			->from($this->_sTable, 'p')
			->where('p.photo_id = ' . (int) $iId)
			->execute('getSlaveRow');
		
		if (!isset($aPhoto['photo_id']))
		{
			return false;
		}		
		
		return Phpfox::permalink('advancedphoto', $aPhoto['photo_id'], $aPhoto['title']);
	}	
	
	public function getNewsFeedAlbum($aRow)
	{
		if ($sPlugin = Phpfox_Plugin::get('advancedphoto.service_callback_getnewsfeedalbum_start')){eval($sPlugin);}
		$aPart = unserialize($aRow['content']);
		$aRow['album_name'] = $aPart['name'];
		$aRow['photo_images'] = $aPart['images'];

		
		$aRow['text'] = Phpfox::getPhrase('advancedphoto.feed_user_add_photos', array(
				'owner_full_name' => $aRow['owner_full_name'],
				'owner_link' => Phpfox::getLib('url')->makeUrl('feed.user', array('id' => $aRow['owner_user_id'])),
				'total' => count($aRow['photo_images']),
				'album_name' => Phpfox::getService('feed')->shortenTitle($aRow['album_name']),
				'photo_images' => $aRow['photo_images'],
				'link' => $aRow['link'],
				'gender' => Phpfox::getService('user')->gender($aRow['owner_gender'], 1)
			)
		);
		
		$sImages = '';
		foreach ($aRow['photo_images'] as $aImage)
		{
			$sImage = Phpfox::getLib('image.helper')->display(array(
					'server_id' => $aImage['server_id'],
					'path' => 'photo.url_photo',
					'file' => $aImage['destination'],
					'suffix' => '_100',
					'max_width' => 100,
					'max_height' => 100,
					'style' => 'vertical-align:top; padding-right:5px;'
				)
			);
			
			if (preg_match('/misc\/noimage/', $sImage))
			{
				continue;
			}			
			
			if (isset($aImage['link']))
			{				
				$aRow['link'] = Phpfox::getLib('url')->makeUrl($aImage['link'][0], array($aImage['link'][1], $aImage['link'][2], $aImage['link'][3]));
			}			
			if (isset($aImage['mature']))
			{
				if (($aImage['mature'] == 0 || (($aImage['mature'] == 1 || $aImage['mature'] == 2) && Phpfox::getUserId() && Phpfox::getUserParam('advancedphoto.photo_mature_age_limit') <= Phpfox::getUserBy('age'))) || $aRow['user_id'] == Phpfox::getUserId())
				{
					$sImages .= '<a href="' . $aRow['link'] . '">' . $sImage . '</a>';
				}
				else
				{
					$sImages .= '<a href="' . $aRow['link'] . '"' . ($aImage['mature'] == 1 ? 'onclick="tb_show(\'' . Phpfox::getPhrase('advancedphoto.warning') . '\', $.ajaxBox(\'advancedphoto.warning\', \'height=300&amp;width=350&amp;link=' . $aRow['link'] . '\')); return false;"' : '') . '><img src="' . Phpfox::getLib('template')->getStyle('image', 'misc/no_access.png') . '" alt="" width="100px" /></a>';
				}
			}
					//$sImages .= '<a href="' . $aRow['link'] . '">' . $sImage . '</a>';
		}
		$aRow['text'] .= '<div class="p_4">' . $sImages . '</div>';		
		
		$aRow['icon'] = 'module/photo.png';
		$aRow['enable_like'] = true;
		
		return $aRow;
	}
	
	public function getNewsFeed($aRow)
	{
		if ($sPlugin = Phpfox_Plugin::get('advancedphoto.service_callback_getnewsfeed_start')){eval($sPlugin);}
		$aImages = unserialize($aRow['content']);
		
		if (count($aImages) == 1)
		{
			$aRow['text'] = Phpfox::getPhrase('advancedphoto.a_href_profile_link_owner_full_name_a_uploaded_a_new_photo', array(
					'profile_link' => Phpfox::getLib('url')->makeUrl('feed.user', array('id' => $aRow['owner_user_id'])),
					'owner_full_name' => $this->preParse()->clean($aRow['owner_full_name'])
				)
			);
		}
		else 
		{
			$aRow['text'] = Phpfox::getPhrase('advancedphoto.a_href_profile_link_owner_full_name_a_uploaded_new_photos', array(
					'profile_link' => Phpfox::getLib('url')->makeUrl('feed.user', array('id' => $aRow['owner_user_id'])),
					'owner_full_name' => $this->preParse()->clean($aRow['owner_full_name'])			
				)
			);
		}
		
		$sImages = '';
		foreach ($aImages as $aImage)
		{
			$sImage = Phpfox::getLib('image.helper')->display(array(
					'server_id' => $aImage['server_id'],
					'path' => 'photo.url_photo',
					'file' => $aImage['destination'],
					'suffix' => '_100',
					'max_width' => 100,
					'max_height' => 100,
					'style' => 'vertical-align:top; padding-right:5px;'
				)
			);
			
			if (preg_match('/misc\/noimage/', $sImage))
			{
				continue;
			}			
			
			if (isset($aImage['link']))
			{				
				$aRow['link'] = Phpfox::getLib('url')->makeUrl(implode('.', $aImage['link']));
			}			
			
			if (isset($aImage['mature']))
			{
				if (($aImage['mature'] == 0 || (($aImage['mature'] == 1 || $aImage['mature'] == 2) && Phpfox::getUserId() && Phpfox::getUserParam('advancedphoto.photo_mature_age_limit') <= Phpfox::getUserBy('age'))) || $aRow['user_id'] == Phpfox::getUserId())
				{
					$sImages .= '<a href="' . $aRow['link'] . '">' . $sImage . '</a>';
				}
				else 
				{
					$sImages .= '<a href="' . $aRow['link'] . '"' . ($aImage['mature'] == 1 ? 'onclick="tb_show(\'' . Phpfox::getPhrase('advancedphoto.warning') . '\', $.ajaxBox(\'advancedphoto.warning\', \'height=300&amp;width=350&amp;link=' . $aRow['link'] . '\')); return false;"' : '') . '><img src="' . Phpfox::getLib('template')->getStyle('image', 'misc/no_access.png') . '" alt="" width="100px" /></a>';
				}
			}
			else 
			{
				$sImages .= '<a href="' . $aRow['link'] . '">' . $sImage . '</a>';
			}
		}
		$aRow['text'] .= '<div class="p_4">' . $sImages . '</div>';		
		
		$aRow['icon'] = 'module/photo.png';
		$aRow['enable_like'] = true;
		
		return $aRow;
	}	

	/**
	  * @param int $iId video_id
	  * @return array in the format:
	     * array(
	     *	'title' => 'item title',		    <-- required
	     *  'link'  => 'makeUrl()'ed link',		    <-- required
	     *  'paypal_msg' => 'message for paypal'	    <-- required
	     *  'item_id' => int			    <-- required
	     *	'error' => 'phrase if item doesnt exit'	    <-- optional
	     *	'extra' => 'description'		    <-- optional
	     *	'image' => 'path to an image',		    <-- optional
	     *  'image_dir' => 'photo.url_photo|...	    <-- optional (required if image)
	     *	'server_id' => database value		    <-- optional (required if image)
	     * )
	    */
	public function getToSponsorInfo($iId)
	{
	    $aImage = $this->database()->select('p.user_id, p.title, p.photo_id as item_id, p.server_id, p.destination as image, u.user_name')
		    ->from($this->_sTable, 'p')
		    ->join(Phpfox::getT('user'), 'u', 'u.user_id = p.user_id')
		    ->where('p.photo_id = ' . (int) $iId)
		    ->execute('getSlaveRow');

	    if (empty($aImage))
	    {
			return array('error' => Phpfox::getPhrase('advancedphoto.sorry_the_photo_you_are_looking_for_no_longer_exists',array('link'=> Phpfox::getLib('url')->makeUrl('profile.photo'))));
	    }
	    
	    $aImage['link'] = Phpfox::permalink('advancedphoto',$aImage['item_id'], $aImage['title']);;//Phpfox::getLib('url')->makeUrl('advancedphoto.'.$aImage['photo_id'].'.'.$aImage['title']);
	    $aImage['paypal_msg'] = Phpfox::getPhrase('advancedphoto.sponsor_paypal_message', array('sPhotoTitle' => $aImage['title']));
	    $aImage['image_dir'] = 'photo.url_photo';
	    $aImage['title'] = Phpfox::getPhrase('advancedphoto.sponsor_title', array('sPhotoTitle' => $aImage['title']));
	    
	    return $aImage;
	}
	
	public function getCommentNewsFeed($aRow)
	{	
		$oUrl = Phpfox::getLib('url');
		$oParseOutput = Phpfox::getLib('parse.output');		
		
		$aPart = unserialize($aRow['content']);
		$aRow['content'] = $aPart['content'];
		$aRow['image_path'] = $aPart['destination'];
		
		if ($aRow['owner_user_id'] == $aRow['viewer_user_id'])
		{
			$aRow['text'] = Phpfox::getPhrase('advancedphoto.a_href_user_link_full_name_a_added_a_new_comment_on_their_own_a_href_title_link_photo', array(
					'user_link' => $oUrl->makeUrl('feed.user', array('id' => $aRow['owner_user_id'])),
					'full_name' => $this->preParse()->clean($aRow['owner_full_name']),
					'title_link' => $aRow['link']
				)
			);
		}
		else 
		{
			if ($aRow['item_user_id'] == Phpfox::getUserBy('user_id'))
			{
				$aRow['text'] = Phpfox::getPhrase('advancedphoto.a_href_user_link_full_name_a_added_a_new_comment_on_your_a_href_title_link_photo_a', array(
						'user_link' => $oUrl->makeUrl('feed.user', array('id' => $aRow['owner_user_id'])),
						'full_name' => $this->preParse()->clean($aRow['owner_full_name']),
						'title_link' => $aRow['link']						
					)
				);
			}
			else 
			{
				$aRow['text'] = Phpfox::getPhrase('advancedphoto.a_href_user_link_full_name_a_added_a_new_comment_on', array(
						'user_link' => $oUrl->makeUrl('feed.user', array('id' => $aRow['owner_user_id'])),
						'full_name' => $this->preParse()->clean($aRow['owner_full_name']),
						'title_link' => $aRow['link'],
						'item_user_name' => $this->preParse()->clean($aRow['viewer_full_name']),
						'item_user_link' => $oUrl->makeUrl('feed.user', array('id' => $aRow['viewer_user_id']))
					)
				);
			}
		}		
	
			
		$sImage = '';
		if (!empty($aRow['image_path']))
		{
			$sImage = Phpfox::getLib('image.helper')->display(array(
					'server_id' => $aPart['server_id'],
					'path' => 'photo.url_photo',
					'file' => $aRow['image_path'],
					'suffix' => '_75',
					'max_width' => 75,
					'max_height' => 75,
					'style' => 'vertical-align:top; padding-right:5px;'					
				)
			);
			if (isset($aPart['mature']))
			{
				if (($aPart['mature'] == 0 || // its public
						(
							($aPart['mature'] == 1 || $aPart['mature'] == 2) && // its restricted
							Phpfox::getUserId() && // is user
							(Phpfox::getUserParam('advancedphoto.photo_mature_age_limit') <= Phpfox::getUserBy('age')))) || // user is older
							$aRow['user_id'] == Phpfox::getUserId() // owner and viewer are the same
						)
				{
					$sImage = '<a href="' . $aRow['link'] . '">' . $sImage . '</a>';
				}
				elseif ($aPart['mature'] == 1)
				{
					$sImage = '<a href="' . $aRow['link'] . '" onclick="tb_show(\'' . Phpfox::getPhrase('advancedphoto.warning') . '\', $.ajaxBox(\'advancedphoto.warning\', \'height=300&amp;width=350&amp;link=' . $aRow['link'] . '\')); return false;"><img src="' . Phpfox::getLib('template')->getStyle('image', 'misc/no_access.png') . '" alt="" width="100px" /></a>';
				}
				else
				{
					$sImage = '<a href="' . $aRow['link'] . '"' . '><img src="' . Phpfox::getLib('template')->getStyle('image', 'misc/no_access.png') . '" alt="" width="100px" /></a>';
				}
			}
			else
			{
				$sImage = '<a href="' . $aRow['link'] . '">' . $sImage . '</a>';
			}
		}		
					
		$aRow['text'] .= '<div class="p_4"><div class="go_left">' . $sImage . '</div><div style="margin-left:75px;">' . Phpfox::getService('feed')->quote($aRow['content']) . '</div><div class="clear"></div></div>';
		
		return $aRow;
	}
	
	public function getActivityFeedComment($aRow)
	{
		if (Phpfox::isUser())
		{
			$this->database()->select('l.like_id AS is_liked, ')->leftJoin(Phpfox::getT('like'), 'l', 'l.type_id = \'feed_mini\' AND l.item_id = c.comment_id AND l.user_id = ' . Phpfox::getUserId());
		}		
		
		$aItem = $this->database()->select('b.photo_id, b.album_id, b.server_id, b.destination, b.title, b.time_stamp, b.privacy, b.total_comment, b.total_like, c.total_like, ct.text_parsed AS text, f.friend_id AS is_friend, b.mature, ' . Phpfox::getUserField())
			->from(Phpfox::getT('comment'), 'c')
			->join(Phpfox::getT('comment_text'), 'ct', 'ct.comment_id = c.comment_id')
			->join(Phpfox::getT('photo'), 'b', '(c.type_id = \'photo\' OR c.type_id = \'advancedphoto\') AND c.item_id = b.photo_id AND c.view_id = 0')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = b.user_id')
			->leftJoin(Phpfox::getT('friend'), 'f', "f.user_id = b.user_id AND f.friend_user_id = " . Phpfox::getUserId())
			->where('c.comment_id = ' . (int) $aRow['item_id'])
			->execute('getSlaveRow');
		
		if (!isset($aItem['photo_id']))
		{
			return false;
		}
		
		$sLink = Phpfox::permalink('advancedphoto', $aItem['photo_id'], $aItem['title']);
		if ($aItem['album_id'])
		{
			$sLink .= 'albumid_' . $aItem['album_id'] . '/';
		}
		$sUser = '<a href="' . Phpfox::getLib('url')->makeUrl($aItem['user_name']) . '">' . $aItem['full_name'] . '</a>';
		$sGender = Phpfox::getService('user')->gender($aItem['gender'], 1);
		
		if ($aRow['user_id'] == $aItem['user_id'])
		{
			$sMessage = Phpfox::getPhrase('advancedphoto.posted_a_comment_on_gender_photo',array('gender' => $sGender));
		}
		else
		{			
			$sMessage = Phpfox::getPhrase('advancedphoto.posted_a_comment_on_user_name_s_photo',array('user_name' => $sUser));
		}
		
		$aFeed = array(
			'no_share' => true,
			'feed_info' => $sMessage,
			'feed_link' => $sLink,
			'feed_status' => $aItem['text'],
			'feed_total_like' => $aItem['total_like'],
			'feed_is_liked' => isset($aItem['is_liked']) ? $aItem['is_liked'] : false,
			'feed_icon' => Phpfox::getLib('image.helper')->display(array('theme' => 'feed/advphoto.png', 'return_url' => true)),
			'time_stamp' => isset($aRow['time_stamp']) ? $aRow['time_stamp'] : time(),
			'like_type_id' => 'feed_mini',
			'custom_rel' => $aItem['photo_id'],
			'custom_css' => 'ynadvphoto_thickbox photo_holder_image ' . (Phpfox::getParam('advancedphoto.view_photos_in_theater_mode') ? 'no_ajax_link' : '')
		);

		$bCanViewItem = true;
		if ($aItem['privacy'] > 0)
		{
			$bCanViewItem = Phpfox::getService('privacy')->check('advancedphoto', $aItem['photo_id'], $aItem['user_id'], $aItem['privacy'], $aItem['is_friend'], true);
		}		
		
		if ($bCanViewItem)
		{
			$aFeed['feed_image'] = Phpfox::getLib('image.helper')->display(array(
					'server_id' => $aItem['server_id'],
					'path' => 'photo.url_photo',
					'file' => Phpfox::getService('advancedphoto')->getPhotoUrl($aItem),
					'suffix' => '_100',
					'max_width' => 100,
					'max_height' => 100,
					'class' => 'photo_holder'
				)
			);		
			
			if (isset($aItem['mature']))
			{
				if (($aItem['mature'] == 0 || // its public
						(
							($aItem['mature'] == 1 || $aItem['mature'] == 2) && // its restricted
							Phpfox::getUserId() && // is user
							(Phpfox::getUserParam('advancedphoto.photo_mature_age_limit') <= Phpfox::getUserBy('age')))) || // user is older
							$aRow['user_id'] == Phpfox::getUserId() // owner and viewer are the same
						)
				{
					
				}
				elseif ($aItem['mature'] == 1)
				{
					$aFeed['custom_css'] = 'no_ajax_link';
					$aFeed['custom_js'] = 'onclick="tb_show(\'' . Phpfox::getPhrase('advancedphoto.warning') . '\', $.ajaxBox(\'advancedphoto.warning\', \'height=300&amp;width=350&amp;link=' . $aFeed['feed_link'] . '\')); return false;"';
					$aFeed['feed_image'] = '<img src="' . Phpfox::getLib('template')->getStyle('image', 'misc/no_access.png') . '" alt="" width="100px" />';
				}
				else
				{
					$aFeed['feed_image'] = '<img src="' . Phpfox::getLib('template')->getStyle('image', 'misc/no_access.png') . '" alt="" width="100px" />';
				}
			}			
		}
		
		return $aFeed;		
	}		
	
	public function addComment($aVals, $iUserId = null, $sUserName = null)
	{		
		$aRow = $this->database()->select('u.full_name, u.gender, u.user_id, u.user_name, p.photo_id, p.title, p.group_id')
			->from($this->_sTable, 'p')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = p.user_id')
			->where('p.photo_id = ' . (int) $aVals['item_id'])
			->execute('getSlaveRow');
	
		if (empty($aRow['group_id']))
		{
			(Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->add($aVals['type'] . '_comment', $aVals['comment_id']) : null);
		}
		
		if (empty($aVals['parent_id']))
		{
			$this->database()->updateCounter('photo', 'total_comment', 'photo_id', $aRow['photo_id']);		
		}
		
		// Send the user an email
		$sLink = Phpfox::getLib('url')->permalink('advancedphoto', $aRow['photo_id'], $aRow['title']);
		
		Phpfox::getService('comment.process')->notify(array(
				'user_id' => $aRow['user_id'],
				'item_id' => $aRow['photo_id'],
				'owner_subject' => Phpfox::getPhrase('advancedphoto.full_name_commented_on_your_photo_title', array('full_name' => Phpfox::getUserBy('full_name'), 'title' => $this->preParse()->clean($aRow['title'], 100))),
				'owner_message' => 
		    Phpfox::getPhrase('advancedphoto.full_name_commented_on_your_photo_a_href_link_title_a_to_see_the_comment_thread_follow_the_link_below_a_href_link_link_a',array('full_name' => Phpfox::getUserBy('full_name'), 'title' => $aRow['title'], 'link' => $sLink)),		    
				'owner_notification' => 'comment.add_new_comment',
				'notify_id' => 'comment_advancedphoto',
				'mass_id' => 'advancedphoto',
				'mass_subject' => 
			    
			    (Phpfox::getUserId() == $aRow['user_id'] ? 
			    
			    Phpfox::getPhrase('advancedphoto.full_name_commented_on_gender_photo',array('full_name' => Phpfox::getUserBy('full_name'), 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1)))
			    			    
			    : 
			    Phpfox::getPhrase('advancedphoto.full_name_commented_on_other_full_name_s_photo', array('full_name' => Phpfox::getUserBy('full_name'), 'other_full_name' => $aRow['full_name']))),
				'mass_message' => (Phpfox::getUserId() == $aRow['user_id'] ? 
			    Phpfox::getPhrase('advancedphoto.full_name_commented_on_gender_photo_a_href_link_title_a_to_see_the_comment_thread_follow_the_link_below_a_href_link_link_a',array('full_name' => Phpfox::getUserBy('full_name'), 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'link' => $sLink, 'title' => $aRow['title']))
			    : 
			Phpfox::getPhrase('advancedphoto.full_name_commented_on_other_full_name_s_photo_a_href_link_title_a_to_see_the_comment_thread_follow_the_link_below_a_href_link_link_a',array('full_name' => Phpfox::getUserBy('full_name'), 'other_full_name' => $aRow['full_name'], 'link' => $sLink, 'title' => $aRow['title'])))
			)
		);	
	}
	
	public function addLike($iItemId, $bDoNotSendEmail = false)
	{
		$aRow = $this->database()->select('photo_id, title, user_id')
			->from(Phpfox::getT('photo'))
			->where('photo_id = ' . (int) $iItemId)
			->execute('getSlaveRow');
			
		if (!isset($aRow['photo_id']))
		{
			return false;
		}
		
		$this->database()->updateCount('like', '(type_id = \'photo\' OR type_id = \'advancedphoto\' ) AND item_id = ' . (int) $iItemId . '', 'total_like', 'photo', 'photo_id = ' . (int) $iItemId);	
		
		if (!$bDoNotSendEmail)
		{
			$sLink = Phpfox::permalink('advancedphoto', $aRow['photo_id'], $aRow['title']);
			
			Phpfox::getLib('mail')->to($aRow['user_id'])
				->subject(array('custom.full_name_liked_your_photo_title', array('full_name' => Phpfox::getUserBy('full_name'), 'title' => $aRow['title'])))
				->message(array('custom.full_name_liked_your_photo_message', array('full_name' => Phpfox::getUserBy('full_name'), 'link' => $sLink, 'title' => $aRow['title'])))
				->notification('like.new_like')
				->send();
					
			if (Phpfox::isModule('notification'))
			{
				Phpfox::getService('notification.process')->add('advancedphoto_like', $aRow['photo_id'], $aRow['user_id']);				
			}
		}
	}
	
	public function getNotificationLike($aNotification)
	{
		$aRow = $this->database()->select('b.photo_id, b.title, b.user_id, u.gender, u.full_name')	
			->from(Phpfox::getT('photo'), 'b')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = b.user_id')
			->where('b.photo_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
		
		if (!isset($aNotification['user_id']) || !isset($aRow['user_id']))
		{
			return false;
		}		
		$aRow['title'] = Phpfox::getLib('parse.output')->split($aRow['title'], 20);
		
		$sPhrase = '';
		if ($aNotification['user_id'] == $aRow['user_id'])
		{
			$sPhrase = Phpfox::getPhrase('advancedphoto.user_name_liked_gender_own_photo_title',array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], (Phpfox::isModule('notification') ? Phpfox::getParam('notification.total_notification_title_length') : $this->_iFallbackLength ), '...')));
			
		}
		elseif ($aRow['user_id'] == Phpfox::getUserId())		
		{
			$sPhrase = Phpfox::getPhrase('advancedphoto.user_name_liked_your_photo_title',array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], (Phpfox::isModule('notification') ? Phpfox::getParam('notification.total_notification_title_length') : $this->_iFallbackLength ), '...')));
		}
		else 
		{
			$sPhrase = Phpfox::getPhrase('advancedphoto.user_name_liked_span_class_drop_data_user_full_name_s_span_photo_title',array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'full_name' => $aRow['full_name'], 'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], (Phpfox::isModule('notification') ? Phpfox::getParam('notification.total_notification_title_length') : $this->_iFallbackLength ), '...')));			
		}
			
		return array(
			'link' => Phpfox::getLib('url')->permalink('advancedphoto', $aRow['photo_id'], $aRow['title']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);	
	}	
	
	public function deleteLike($iItemId)
	{
		$this->database()->updateCount('like', '(type_id = \'photo\' OR type_id = \'advancedphoto\' ) AND item_id = ' . (int) $iItemId . '', 'total_like', 'photo', 'photo_id = ' . (int) $iItemId);	
	}		
	
	public function updateCommentText($aVals, $sText)
	{
		
	}

	public function getCommentNotification($aNotification)
	{		
		$aRow = $this->database()->select('p.photo_id, p.title, u.user_id, u.gender, u.user_name, u.full_name')	
			->from(Phpfox::getT('photo'), 'p')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = p.user_id')
			->where('p.photo_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');

		if(!isset($aRow['photo_id']))
		{
			return false;
		}
			
		$sPhrase = '';
		if ($aNotification['user_id'] == $aRow['user_id'] && !isset($aNotification['extra_users']))
		{
			$sPhrase = Phpfox::getPhrase('advancedphoto.user_name_commented_on_gender_photo_title',array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], (Phpfox::isModule('notification') ? Phpfox::getParam('notification.total_notification_title_length') : $this->_iFallbackLength ), '...')));
		}
		elseif ($aRow['user_id'] == Phpfox::getUserId())		
		{
			$sPhrase = Phpfox::getPhrase('advancedphoto.user_name_commented_on_your_photo_title',array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], (Phpfox::isModule('notification') ? Phpfox::getParam('notification.total_notification_title_length') : $this->_iFallbackLength ), '...')));
		}
		else 
		{
			$sPhrase = Phpfox::getPhrase('advancedphoto.user_name_commented_on_span_class_drop_data_user_full_name_s_span_photo_title',array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'full_name' => $aRow['full_name'], 'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], (Phpfox::isModule('notification') ? Phpfox::getParam('notification.total_notification_title_length') : $this->_iFallbackLength ), '...')));
		}

		return array(
			'link' => Phpfox::getLib('url')->permalink('advancedphoto', $aRow['photo_id'], $aRow['title']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);
	}		
	
	public function getCommentNotificationTag($aNotification)
	{
		$aRow = $this->database()->select('p.photo_id, p.title, u.user_name, u.full_name')
					->from(Phpfox::getT('comment'), 'c')
					->join(Phpfox::getT('photo'), 'p', 'p.photo_id = c.item_id')
					->join(Phpfox::getT('user'), 'u', 'u.user_id = c.user_id')
					->where('c.comment_id = ' . (int)$aNotification['item_id'])
					->execute('getSlaveRow');
		
		if(!isset($aRow['photo_id']))
		{
			return false;
		}
		
		$sPhrase = Phpfox::getPhrase('advancedphoto.user_name_tagged_you_in_a_comment_in_a_photo', array('user_name' => $aRow['full_name']));
		
		return array(
			'link' => Phpfox::getLib('url')->permalink('advancedphoto', $aRow['photo_id'], $aRow['title'])  .'comment_' . $aNotification['item_id'],
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);
	}
	public function getCommentNotificationAlbumTag($aNotification)
	{
		$aRow = $this->database()->select('pa.album_id, pa.name, u.user_name, u.full_name')
					->from(Phpfox::getT('comment'), 'c')
					->join(Phpfox::getT('photo_album'), 'pa', 'pa.album_id = c.item_id')
					->join(Phpfox::getT('user'), 'u', 'u.user_id = c.user_id')
					->where('c.comment_id = ' . (int)$aNotification['item_id'])
					->execute('getSlaveRow');

		if(!isset($aRow['album_id']))
		{
			return false;
		}

		$sPhrase = Phpfox::getPhrase('advancedphoto.user_name_tagged_you_in_a_comment_in_a_photo_album', array('user_name' => $aRow['full_name']));
		
		return array(
			'link' => Phpfox::getLib('url')->permalink('advancedphoto.album', $aRow['album_id'], $aRow['name'])  .'comment_' . $aNotification['item_id'],
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);
	}
	public function getItemName($iId, $sName)
	{
		return '<a href="' . Phpfox::getLib('url')->makeUrl('comment.view', array('id' => $iId)) . '">' . Phpfox::getPhrase('advancedphoto.on_name_s_photo', array('name' => $sName)) . '</a>';
	}	

	public function getRedirectComment($iId)
	{
		return $this->getFeedRedirect($iId);
	}	
	
	public function getReportRedirect($iId)
	{
		return $this->getFeedRedirect($iId);
	}	
	
	public function getReportRedirectAlbum($iId)
	{
		return $this->getFeedRedirectAlbum($iId);
	}
	
	public function getCommentItemName()
	{
		return 'advancedphoto';
	}	
	
	public function processCommentModeration($sAction, $iId)
	{
		// Is this comment approved?
		if ($sAction == 'approve')
		{
			// Update the blog comment count
			Phpfox::getService('advancedphoto.process')->updateCounter($iId, 'total_comment');
			
			// Get the blogs details so we can add it to our news feed
			$aPhoto = $this->database()->select('u.full_name, u.user_id, u.user_name, p.title_url, p.destination, ct.text_parsed, p.album_id, pa.name_url AS album_url, c.comment_id, c.user_id AS comment_user_id')			
				->from($this->_sTable, 'p')								
				->join(Phpfox::getT('comment'), 'c', '(c.type_id = \'photo\' OR c.type_id = \'advancedphoto\') AND c.item_id = p.photo_id')
				->join(Phpfox::getT('user'), 'u', 'u.user_id = c.user_id')
				->join(Phpfox::getT('comment_text'), 'ct', 'ct.comment_id = c.comment_id')
				->leftJoin(Phpfox::getT('photo_album'), 'pa', 'pa.album_id = p.album_id')
				->where('p.photo_id = ' . (int) $iId)
				->execute('getSlaveRow');
				
			// Add to news feed
			(Phpfox::isModule('feed') ? Phpfox::getService('feed.process')->add('comment_photo', $iId, $aPhoto['text_parsed'], Phpfox::getUserBy('user_name'), $aPhoto['user_id'], $aPhoto['full_name'], $aPhoto['destination'], $aPhoto['comment_id']) : null);
			
			// Send the user an email			
			$sLink = ($aPhoto['album_id'] ? Phpfox::getLib('url')->makeUrl(Phpfox::getUserBy('user_name'), array('advancedphoto', $aPhoto['album_url'], $aPhoto['title_url'])) : Phpfox::getLib('url')->makeUrl(Phpfox::getUserBy('user_name'), array('advancedphoto', 'view', $aPhoto['title_url'])));					
			
			Phpfox::getLib('mail')->to($aPhoto['comment_user_id'])
				->subject(array('advancedphoto.full_name_approved_your_comment_on_site_title', array('full_name' => Phpfox::getUserBy('full_name'), 'site_title' => Phpfox::getParam('core.site_title'))))
				->message(array('advancedphoto.full_name_approved_your_comment_on_site_title_message', array(
							'full_name' => Phpfox::getUserBy('full_name'),
							'site_title' => Phpfox::getParam('core.site_title'),
							'link' => $sLink
						)
					)
				)
				->notification('comment.approve_new_comment')
				->send();
		}
	}	
	
	public function getWhatsNew()
	{
		return array(
			'advancedphoto.photos' => array(
				'ajax' => '#photo.getNew?id=js_new_item_holder',
				'id' => 'advancedphoto',
				'block' => 'advancedphoto.new'
			)
		);
	}
	
	public function getTagCloud()
	{
		return array(
			'link' => 'advancedphoto',
			'category' => 'photo'
		);
	}

	public function globalSearch($sQuery, $bIsTagSearch = false)
	{
		$sCondition = 'p.view_id = 0 AND p.group_id = 0 AND p.privacy = 0';
		if ($bIsTagSearch === false)
		{
			$sCondition .= ' AND (p.title LIKE \'%' . $this->database()->escape($sQuery) . '%\' OR pi.description LIKE \'%' . $this->database()->escape($sQuery) . '%\')';
		}
		
		if ($bIsTagSearch == true)
		{
			$this->database()->innerJoin(Phpfox::getT('tag'), 'tag', 'tag.item_id = p.photo_id AND (tag.category_id = \'photo\' OR tag.category_id = \'advancedphoto\') AND tag.tag_url = \'' . $this->database()->escape($sQuery) . '\'');
		}		
		
		$iCnt = $this->database()->select('COUNT(*)')
			->from($this->_sTable, 'p')
			->join(Phpfox::getT('photo_info'), 'pi', 'pi.photo_id = p.photo_id')
			->where($sCondition)
			->execute('getSlaveField');		
			
		if ($bIsTagSearch == true)
		{
			$this->database()->innerJoin(Phpfox::getT('tag'), 'tag', 'tag.item_id = p.photo_id AND (tag.category_id = \'photo\' OR tag.category_id = \'advancedphoto\') AND tag.tag_url = \'' . $this->database()->escape($sQuery) . '\'')->group('p.photo_id');
		}			
		
		$aRows = $this->database()->select('p.title, p.title_url, p.time_stamp, p.destination, p.server_id, p.album_id, pa.name_url AS album_url, ' . Phpfox::getUserField())
			->from($this->_sTable, 'p')			
			->join(Phpfox::getT('photo_info'), 'pi', 'pi.photo_id = p.photo_id')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = p.user_id')			
			->leftJoin(Phpfox::getT('photo_album'), 'pa', 'pa.album_id = p.album_id')
			->where($sCondition)
			->limit(10)
			->order('p.time_stamp DESC')
			->execute('getSlaveRows');
			
		if (count($aRows))
		{
			$aResults = array();
			$aResults['total'] = $iCnt;
			$aResults['menu'] = Phpfox::getPhrase('advancedphoto.photos');
			
			if ($bIsTagSearch == true)
			{
				$aResults['form'] = '<div><input type="button" value="' . Phpfox::getPhrase('advancedphoto.view_more_photos') . '" class="search_button" onclick="window.location.href = \'' . Phpfox::getLib('url')->makeUrl('advancedphoto', array('tag', $sQuery)) . '\';" /></div>';
			}
			else 
			{			
				$aResults['form'] = '<form method="post" action="' . Phpfox::getLib('url')->makeUrl('advancedphoto') . '"><div><input type="hidden" name="' . Phpfox::getTokenName() . '[security_token]" value="' . Phpfox::getService('log.session')->getToken() . '" /></div><div><input name="search[search]" value="' . Phpfox::getLib('parse.output')->clean($sQuery) . '" size="20" type="hidden" /></div><div><input type="submit" name="submit" value="' . Phpfox::getPhrase('advancedphoto.view_more_photos') . '" class="search_button" /></div></form>';
			}
			
			foreach ($aRows as $iKey => $aRow)
			{
				$aResults['results'][$iKey] = array(			
					'title' => $aRow['title'],	
					'link' => Phpfox::getLib('url')->makeUrl($aRow['user_name'], array('advancedphoto', ($aRow['album_id'] ? $aRow['album_url'] : 'view'), $aRow['title_url'])),
					'image' => Phpfox::getLib('image.helper')->display(array(
							'server_id' => $aRow['server_id'],
							'title' => $aRow['title'],
							'path' => 'photo.url_photo',
							'file' => $aRow['destination'],
							'suffix' => '_75',
							'max_width' => 75,
							'max_height' => 75
						)
					),
					'extra_info' => Phpfox::getPhrase('advancedphoto.a_href_photo_section_link_photo_a_uploaded_on_time_stamp_by_a_href_user_link', array(
							'photo_section_link' => Phpfox::getLib('url')->makeUrl('advancedphoto'),
							'time_stamp' => Phpfox::getTime(Phpfox::getParam('core.global_update_time'), $aRow['time_stamp']),
							'user_link' => Phpfox::getLib('url')->makeUrl($aRow['user_name']),
							'full_name' => $this->preParse()->clean($aRow['full_name'])
						)
					)
				);
			}
			
			return $aResults;
		}
	}
	
	public function verifyFavorite($iItemId)
	{
		$aItem = $this->database()->select('i.photo_id')
			->from(Phpfox::getT('photo'), 'i')
			->where('i.photo_id = ' . (int) $iItemId . ' AND i.view_id = 0 AND i.privacy IN(0,1)')
			->execute('getSlaveRow');
			
		if (!isset($aItem['photo_id']))
		{
			return false;
		}

		return true;
	}		

	public function verifyFavoriteAlbum($iItemId)
	{
		$aItem = $this->database()->select('i.album_id')
			->from(Phpfox::getT('photo_album'), 'i')
			->where('i.album_id = ' . (int) $iItemId . ' AND i.view_id = 0 AND i.privacy IN(0,1)')
			->execute('getSlaveRow');
			
		if (!isset($aItem['album_id']))
		{
			return false;
		}

		return true;
	}	
	
	public function getFavoriteAlbum($aFavorites)
	{
		$aItems = $this->database()->select('i.name AS title, i.album_id, i.time_stamp, i.name_url AS album_url, p.title_url, p.destination, p.server_id AS photo_server_id, ' . Phpfox::getUserField())
			->from(Phpfox::getT('photo_album'), 'i')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = i.user_id')
			->leftJoin(Phpfox::getT('photo'), 'p', 'p.album_id = i.album_id AND i.view_id = 0 AND p.is_cover = 1')
			->where('i.album_id IN(' . implode(',', $aFavorites) . ') AND i.view_id = 0 AND i.privacy IN(0,1)')
			->execute('getSlaveRows');
			
		foreach ($aItems as $iKey => $aItem)
		{
			$aItems[$iKey]['image'] = Phpfox::getLib('image.helper')->display(array(
					'server_id' => $aItem['photo_server_id'],
					'path' => 'photo.url_photo',
					'file' => $aItem['destination'],
					'suffix' => '_75',
					'max_width' => 75,
					'max_height' => 75
				)
			);			
			
			$aItems[$iKey]['link'] = Phpfox::getLib('url')->makeUrl($aItem['user_name'], array('advancedphoto', $aItem['album_url']));
		}		
		
		return array(
			'title' => Phpfox::getPhrase('advancedphoto.photo_albums'),
			'items' => $aItems
		);
	}
	
	public function getFavorite($aFavorites)
	{
		$aItems = $this->database()->select('i.title, i.album_id, i.time_stamp, p.name_url AS album_url, i.title_url, i.destination, i.server_id AS photo_server_id, ' . Phpfox::getUserField())
			->from(Phpfox::getT('photo'), 'i')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = i.user_id')
			->leftJoin(Phpfox::getT('photo_album'), 'p', 'p.album_id = i.album_id')
			->where('i.photo_id IN(' . implode(',', $aFavorites) . ') AND i.view_id = 0 AND i.privacy IN(0,1)')
			->execute('getSlaveRows');
			
		foreach ($aItems as $iKey => $aItem)
		{
			$aItems[$iKey]['image'] = Phpfox::getLib('image.helper')->display(array(
					'server_id' => $aItem['photo_server_id'],
					'path' => 'photo.url_photo',
					'file' => $aItem['destination'],
					'suffix' => '_75',
					'max_width' => 75,
					'max_height' => 75
				)
			);			
			
			$aItems[$iKey]['link'] = ($aItem['album_id'] ? Phpfox::getLib('url')->makeUrl($aItem['user_name'], array('photo', $aItem['album_url'], $aItem['title_url'])) : Phpfox::getLib('url')->makeUrl($aItem['user_name'], array('advancedphoto', 'view', $aItem['title_url'])));			
		}		
		
		return array(
			'title' => Phpfox::getPhrase('advancedphoto.photos'),
			'items' => $aItems
		);
	}	
	
	public function getDashboardLinks()
	{
		return array(
			'submit' => array(
				'phrase' => Phpfox::getPhrase('advancedphoto.upload_a_photo'),
				'link' => 'advancedphoto.upload',
				'image' => 'misc/photo_feature.png'
			),
			'edit' => array(
				'phrase' => Phpfox::getPhrase('advancedphoto.manage_photos'),
				'link' => 'profile.photo',
				'image' => 'misc/photos.png'
			)			
		);
	}	
	
	public function getDashboardActivity()
	{
		$aUser = Phpfox::getService('user')->get(Phpfox::getUserId(), true);
		
		return array(
			Phpfox::getPhrase('advancedphoto.advanced_photos') => $aUser['activity_advancedphoto']
		);
	}	

	/**
	 * Action to take when user cancelled their account
	 * @param int $iUser
	 */
	public function onDeleteUser($iUser)
	{
		// get the ids for the photos of this user
		$aPhotos = $this->database()
			->select('p.photo_id, pa.album_id, pb.battle_id, pt.tag_id')
			->from($this->_sTable, 'p')
			->leftjoin(Phpfox::getT('photo_album'), 'pa', 'pa.user_id = ' . (int)$iUser)
			->leftjoin(Phpfox::getT('photo_battle'), 'pb', 'pb.user_id = ' . (int)$iUser)
			->leftjoin(Phpfox::getT('photo_tag'), 'pt', 'pt.user_id = ' . (int)$iUser)
			->where('p.user_id = ' . (int)$iUser)
			->execute('getSlaveRows');

		foreach ($aPhotos as $aPhoto)
		{
			if (isset($aPhoto['photo_id']))
			{
				Phpfox::getService('advancedphoto.process')->delete($aPhoto['photo_id']);
			}
			if (isset($aPhoto['album_id']))
			{
				Phpfox::getService('advancedphoto.album.process')->delete($aPhoto['album_id']);
			}
			if (isset($aPhoto['tag_id']))
			{
				// delete tags added by this user
				Phpfox::getService('advancedphoto.tag.process')->delete($aPhoto['tag_id']);
			}
		}

		Phpfox::getService('advancedphoto.battle.process')->deleteByUser($iUser);
		Phpfox::getService('advancedphoto.rate.process')->deleteByUser($iUser);
		
	}
	
	public function groupMenu($sGroupUrl, $iGroupId)
	{
		if (!Phpfox::getService('group')->hasAccess($iGroupId, 'can_use_photo'))
		{
			return false;
		}		
		
		return array(
				Phpfox::getPhrase('advancedphoto.photos') => array(
					'active' => 'photo',
					'url' => Phpfox::getLib('url')->makeUrl('group', array($sGroupUrl, 'advancedphoto')
				)
			)
		);
	}	

	public function getItemView()
	{
		if (Phpfox::getLib('request')->get('req3') == 'view')
		{
			return true;
		}
	}
	
	public function getGroupPosting()
	{
		return array(
			Phpfox::getPhrase('advancedphoto.upload_photos') => 'can_upload_photo'
		);
	}
	
	public function getGroupAccess()
	{
		return array(
			Phpfox::getPhrase('advancedphoto.view_photos') => 'can_use_photo'
		);
	}	
	
	public function getProfileSettings()
	{
		return array(
			'advancedphoto.display_on_profile' => array(
				'phrase' => Phpfox::getPhrase('user.view_photos_within_your_profile'),
				'default' => '0'				
			)
		);
	}
	
	public function hideBlockProfile($sType)
	{
		return array(
			'table' => ($sType == 'profile' ? 'user_design_order' : '')
		);		
	}	
	
	public function getBlockDetailsProfile()
	{
		return array(
			'title' => Phpfox::getPhrase('advancedphoto.photos')
		);
	}		

	public function legacyRedirect($aRequest)
	{
		if (isset($aRequest['req2']))
		{
			switch ($aRequest['req2'])
			{
				case 'view':
					if (isset($aRequest['id']))
					{
						$aItem = Phpfox::getService('core')->getLegacyUrl(array(
								'url_field' => 'title_url',
								'table' => 'photo',
								'field' => 'upgrade_item_id',
								'id' => $aRequest['id']
							)
						);
						
						if ($aItem !== false)
						{
							return array($aItem['user_name'], array('advancedphoto', 'view', $aItem['title_url']));
						}
					}
					break;
				case 'rate':
					return 'advancedphoto.rate';
					break;
				case 'battle':
					return 'advancedphoto.battle';
					break;					
				default:
					return 'advancedphoto';
					break;
			}
		}
		
		return false;
	}
	
	public function addTrack($iId, $iUserId = null)
	{
		$this->database()->insert(Phpfox::getT('photo_track'), array(
				'item_id' => (int) $iId,
				'user_id' => Phpfox::getUserBy('user_id'),
				'time_stamp' => PHPFOX_TIME
			)
		);
	}	
	
	public function getSiteStatsForAdmins()
	{
		$iToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
		
		return false;	
		return array(
			'phrase' => Phpfox::getPhrase('advancedphoto.advanced_photos'),
			'value' => $this->database()->select('COUNT(*)')
				->from(Phpfox::getT('photo'))
				->where('view_id = 0 AND time_stamp >= ' . $iToday)
				->execute('getSlaveField')
		);
	}

	public function updateCounterList()
	{
		$aList = array();		
		
		$aList[] = array(
			'name' => Phpfox::getPhrase('advancedphoto.photo_count_for_photo_albums'),
			'id' => 'photo-album'			
		);		
		
		$aList[] = array(
			'name' => Phpfox::getPhrase('advancedphoto.update_tags_photo'),
			'id' => 'photo-tag-update'			
		);

		$aList[] = array(
			'name' => Phpfox::getPhrase('advancedphoto.update_photo_thumbnails'),
			'id' => 'photo-thumbnail'			
		);	
		
		$aList[] = array(
			'name' => Phpfox::getPhrase('advancedphoto.update_user_photo_count'),
			'id' => 'photo-count'			
		);		
		
		$aList[] = array(
			'name' => Phpfox::getPhrase('advancedphoto.update_profile_photos'),
			'id' => 'photo-profile'			
		);		
		
		return $aList;
	}		
	
	public function updateCounter($iId, $iPage, $iPageLimit)
	{		
		if ($iId == 'photo-profile')
		{
			$iCnt = $this->database()->select('COUNT(*)')
				->from(Phpfox::getT('photo'), 'p')
				->join(Phpfox::getT('photo_album'), 'pa', 'pa.album_id = p.album_id AND pa.profile_id > 0')
				->execute('getSlaveField');
				
			$aRows = $this->database()->select('p.photo_id')
				->from(Phpfox::getT('photo'), 'p')
				->join(Phpfox::getT('photo_album'), 'pa', 'pa.album_id = p.album_id AND pa.profile_id > 0')
				->limit($iPage, $iPageLimit, $iCnt)
				->execute('getSlaveRows');				
			
			foreach ($aRows as $aRow)
			{
				$this->database()->update(Phpfox::getT('photo'), array('is_profile_photo' => '1'), 'photo_id = ' . $aRow['photo_id']);
			}
			
			return $iCnt;			
		}
		elseif ($iId == 'photo-tag-update')
		{
			$iCnt = $this->database()->select('COUNT(*)')
				->from(Phpfox::getT('tag'))
				->where('(category_id = \'photo\' OR category_id = \'advancedphoto\')')
				->execute('getSlaveField');			
				
			$aRows = $this->database()->select('m.tag_id, oc.photo_id AS tag_item_id')
				->from(Phpfox::getT('tag'), 'm')
				->where('m.category_id = \'photo\' OR m.category_id = \'advancedphoto\'')
				->leftJoin(Phpfox::getT('photo'), 'oc', 'oc.photo_id = m.item_id')
				->limit($iPage, $iPageLimit, $iCnt)
				->execute('getSlaveRows');			
			
			foreach ($aRows as $aRow)
			{
				if (empty($aRow['tag_item_id']))
				{
					$this->database()->delete(Phpfox::getT('tag'), 'tag_id = ' . $aRow['tag_id']);
				}
			}
			
			return $iCnt;
		}		
		elseif ($iId == 'photo-thumbnail')
		{
			@ini_set('memory_limit', '100M');
			
			$iCnt = $this->database()->select('COUNT(*)')
				->from(Phpfox::getT('photo'))
				->where($this->database()->isNotNull('destination'))
				->execute('getSlaveField');			
				
			$aRows = $this->database()->select('photo_id, destination')
				->from(Phpfox::getT('photo'))
				->where($this->database()->isNotNull('destination'))
				->limit($iPage, $iPageLimit, $iCnt)
				->execute('getSlaveRows');		
		
			$oImage = Phpfox::getLib('image');
			foreach ($aRows as $aRow)
			{				
				if (preg_match("/\{file\/pic\/(.*)\/(.*)\.jpg\}/i", $aRow['destination'], $aMatches))
				{
					$sPath = PHPFOX_DIR;
					$sFileName = str_replace(array('{', '.jpg}'), array('', '%s.jpg'), $aRow['destination']);			
				}
				else 
				{
					$sPath = Phpfox::getParam('photo.dir_photo');
					$sFileName = $aRow['destination'];
				}							
				
				if (file_exists($sPath . sprintf($sFileName, '')))
				{					
					foreach(Phpfox::getParam('advancedphoto.photo_pic_sizes') as $iSize)
					{					
						if ($oImage->createThumbnail($sPath . sprintf($sFileName, ''), $sPath . sprintf($sFileName, '_' . $iSize), $iSize, $iSize) === false)
						{							
							continue;
						}						
						
						if (Phpfox::getParam('advancedphoto.enabled_watermark_on_photos'))
						{
						 	$oImage->addMark($sPath . sprintf($sFileName, '_' . $iSize));
						}					
					}
					
					if (Phpfox::getParam('advancedphoto.enabled_watermark_on_photos'))					
					{						
					 	$oImage->addMark($sPath . sprintf($sFileName, ''));
					}
				}				
			}
			
			return $iCnt;
		}	
		elseif ($iId == 'photo-count')
		{
			$iCnt = $this->database()->select('COUNT(*)')
				->from(Phpfox::getT('user'))
				->execute('getSlaveField');					

			$aRows = $this->database()->select('u.user_id')
				->from(Phpfox::getT('user'), 'u')
				->limit($iPage, $iPageLimit, $iCnt)
				->group('u.user_id')
				->execute('getSlaveRows');

			foreach ($aRows as $aRow)
			{
				$iTotalPhotos = $this->database()->select('COUNT(f.photo_id)')
					->from(Phpfox::getT('photo'), 'f')
					->where('f.view_id = 0 AND f.group_id = 0 AND f.type_id < 2 AND f.user_id = ' . $aRow['user_id'])
					->execute('getSlaveField');		

				$this->database()->update(Phpfox::getT('user_field'), array('total_photo' => $iTotalPhotos), 'user_id = ' . $aRow['user_id']);			
			}			
		}
		
		$iCnt = $this->database()->select('COUNT(*)')
			->from(Phpfox::getT('photo_album'))
			->execute('getSlaveField');
			
		$aRows = $this->database()->select('g.album_id, COUNT(gi.photo_id) AS total_items')
			->from(Phpfox::getT('photo_album'), 'g')
			->leftJoin(Phpfox::getT('photo'), 'gi', 'gi.album_id = g.album_id')
			->group('g.album_id')
			->limit($iPage, $iPageLimit, $iCnt)
			->execute('getSlaveRows');
			
		foreach ($aRows as $aRow)
		{
			$this->database()->update(Phpfox::getT('photo_album'), array('total_photo' => $aRow['total_items']), 'album_id = ' . (int) $aRow['album_id']);
		}
			
		return $iCnt;
	}

	public function deleteGroup($iId)
	{
		$aRows = $this->database()->select('*')
			->from($this->_sTable)
			->where('group_id = ' . (int) $iId)
			->execute('getRows');
			
		foreach ($aRows as $aRow)
		{
			Phpfox::getService('advancedphoto.process')->delete($aRow['photo_id'], true);
		}
		
		return true;
	}

	public function getNewsFeedAlbum_FeedLike($aRow)
	{
		if ($aRow['owner_user_id'] == $aRow['viewer_user_id'])
		{
			$aRow['text'] = Phpfox::getPhrase('advancedphoto.a_href_user_link_full_name_a_liked_their_own_a_href_link_photo_album_a', array(
					'full_name' => Phpfox::getLib('parse.output')->clean($aRow['owner_full_name']),
					'user_link' => Phpfox::getLib('url')->makeUrl($aRow['owner_user_name']),
					'link' => $aRow['link']
				)
			);
		}
		else 
		{
			$aRow['text'] = Phpfox::getPhrase('advancedphoto.a_href_user_link_full_name_a_liked_a_href_view_user_link_view_full_name_a_s_a_href_link_photo_album_a', array(
					'full_name' => Phpfox::getLib('parse.output')->clean($aRow['owner_full_name']),
					'user_link' => Phpfox::getLib('url')->makeUrl($aRow['owner_user_name']),
					'view_full_name' => Phpfox::getLib('parse.output')->clean($aRow['viewer_full_name']),
					'view_user_link' => Phpfox::getLib('url')->makeUrl($aRow['viewer_user_name']),
					'link' => $aRow['link']				
				)
			);
		}
		
		$aRow['icon'] = 'misc/thumb_up.png';

		return $aRow;		
	}
	
	public function getNewsFeedFeedLike($aRow)
	{
		if ($aRow['owner_user_id'] == $aRow['viewer_user_id'])
		{
			$aRow['text'] = Phpfox::getPhrase('advancedphoto.a_href_user_link_full_name_a_liked_their_own_a_href_link_photo_a', array(
					'full_name' => Phpfox::getLib('parse.output')->clean($aRow['owner_full_name']),
					'user_link' => Phpfox::getLib('url')->makeUrl($aRow['owner_user_name']),
					'link' => $aRow['link']
				)
			);
		}
		else 
		{
			$aRow['text'] = Phpfox::getPhrase('advancedphoto.a_href_user_link_full_name_a_liked_a_href_view_user_link_view_full_name_a_s_a_href_link_photo_a', array(
					'full_name' => Phpfox::getLib('parse.output')->clean($aRow['owner_full_name']),
					'user_link' => Phpfox::getLib('url')->makeUrl($aRow['owner_user_name']),
					'view_full_name' => Phpfox::getLib('parse.output')->clean($aRow['viewer_full_name']),
					'view_user_link' => Phpfox::getLib('url')->makeUrl($aRow['viewer_user_name']),
					'link' => $aRow['link']			
				)
			);
		}
		
		$aRow['icon'] = 'misc/thumb_up.png';

		return $aRow;				
	}
	
	public function getNotificationFeedAlbum_NotifyLike($aRow)
	{
		return array(
			'message' => Phpfox::getPhrase('advancedphoto.a_href_user_link_full_name_a_liked_your_a_href_link_photo_album_a', array(
					'full_name' => Phpfox::getLib('parse.output')->clean($aRow['full_name']),
					'user_link' => Phpfox::getLib('url')->makeUrl($aRow['user_name']),
					'link' => Phpfox::getLib('url')->makeUrl('advancedphoto', array('aredirect' => $aRow['item_id']))
				)
			),
			'link' => Phpfox::getLib('url')->makeUrl('advancedphoto', array('aredirect' => $aRow['item_id']))			
		);			
	}
	
	public function getNotificationFeedNotifyLike($aRow)
	{
		return array(
			'message' => Phpfox::getPhrase('advancedphoto.a_href_user_link_full_name_a_liked_your_a_href_link_photo_a', array(
					'full_name' => Phpfox::getLib('parse.output')->clean($aRow['full_name']),
					'user_link' => Phpfox::getLib('url')->makeUrl($aRow['user_name']),
					'link' => Phpfox::getLib('url')->makeUrl('advancedphoto', array('redirect' => $aRow['item_id']))
				)
			),
			'link' => Phpfox::getLib('url')->makeUrl('advancedphoto', array('redirect' => $aRow['item_id']))			
		);			
	}
	
	public function sendLikeEmailAlbum($iItemId)
	{
		return Phpfox::getPhrase('advancedphoto.a_href_user_link_full_name_a_liked_your_a_href_link_photo_album_a', array(
					'full_name' => Phpfox::getLib('parse.output')->clean(Phpfox::getUserBy('full_name')),
					'user_link' => Phpfox::getLib('url')->makeUrl(Phpfox::getUserBy('user_name')),
					'link' => Phpfox::getLib('url')->makeUrl('advancedphoto', array('aredirect' => $iItemId))
				)
			);		
	}
	
	public function sendLikeEmail($iItemId)
	{
		return Phpfox::getPhrase('advancedphoto.a_href_user_link_full_name_a_liked_your_a_href_link_photo_a', array(
					'full_name' => Phpfox::getLib('parse.output')->clean(Phpfox::getUserBy('full_name')),
					'user_link' => Phpfox::getLib('url')->makeUrl(Phpfox::getUserBy('user_name')),
					'link' => Phpfox::getLib('url')->makeUrl('advancedphoto', array('redirect' => $iItemId))
				)
			);			
	}
	
	public function getActivityPointField()
	{
		return array(
			Phpfox::getPhrase('advancedphoto.advancedphoto') => 'activity_advancedphoto'
		);
	}	
	
	public function pendingApproval()
	{
		return array(
			'phrase' => Phpfox::getPhrase('advancedphoto.advanced_photos'),
			'value' => $this->database()->select('COUNT(*)')->from(Phpfox::getT('photo'))->where('view_id = 1')->execute('getSlaveField'),
			'link' => Phpfox::getLib('url')->makeUrl('advancedphoto', array('view' => 'approval'))
		);
	}

	public function getSqlTitleField()
	{
		return array(
			'table' => 'photo',
			'field' => 'title',
			'has_index' => 'title'
		);
	}		
	
	public function canShareItemOnFeed(){}
	
	public function getActivityFeed($aItem, $aCallback = null, $bIsChildItem = false)
	{		

		$sThickbox = '';
		if (Phpfox::getUserParam('advancedphoto.can_view_photos'))
		{
			$sThickbox = 'ynadvphoto_thickbox ' . (Phpfox::getParam('advancedphoto.view_photos_in_theater_mode') ? 'no_ajax_link' : '');
		}		
		
		if ($aCallback === null)
		{
			$this->database()->select(Phpfox::getUserField('u', 'parent_') . ', ')->leftJoin(Phpfox::getT('user'), 'u', 'u.user_id = photo.parent_user_id');
		}
		
		if ($bIsChildItem)
		{
			$this->database()->select(Phpfox::getUserField('u2') . ', ')->join(Phpfox::getT('user'), 'u2', 'u2.user_id = photo.user_id');
		}

		$aRow = $this->database()->select('photo.*, l.like_id AS is_liked, pi.description, pfeed.photo_id AS extra_photo_id, pa.album_id, pa.name, pfeed.feed_id')
			->from($this->_sTable, 'photo')			
			->join(Phpfox::getT('photo_info'), 'pi', 'pi.photo_id = photo.photo_id')
			->leftJoin(Phpfox::getT('like'), 'l', '(l.type_id = \'photo\' OR l.type_id = \'advancedphoto\') AND l.item_id = photo.photo_id AND l.user_id = ' . Phpfox::getUserId())
			->leftJoin(Phpfox::getT('photo_feed'), 'pfeed', 'pfeed.feed_id = ' . (int) $aItem['feed_id'])
			->leftJoin(Phpfox::getT('photo_album'), 'pa', 'pa.album_id = photo.album_id')
			->where('photo.photo_id = ' . (int) $aItem['item_id'])
			->execute('getSlaveRow');
		
		if ($bIsChildItem)
		{
			$aItem = $aRow;
		}
	
		if (!isset($aRow['photo_id']))
		{
			return array('user_name' => '', 'full_name' => '');
		}
		
		if ((defined('PHPFOX_IS_PAGES_VIEW') && !Phpfox::getService('pages')->hasPerm(null, 'advancedphoto.view_browse_photos'))
			|| (!defined('PHPFOX_IS_PAGES_VIEW') && $aRow['module_id'] == 'pages' && !Phpfox::getService('pages')->hasPerm($aRow['group_id'], 'advancedphoto.view_browse_photos'))	
			)
		{
			return false;
		}		
		
		$bIsPhotoAlbum = false;
		if ($aRow['album_id'])
		{
			$bIsPhotoAlbum = true;
		}
		
		$sLink = Phpfox::permalink('advancedphoto', $aRow['photo_id'], $aRow['title']) . ($bIsPhotoAlbum ? 'albumid_' . $aRow['album_id'] : 'userid_' . $aRow['user_id']) . '/';			
		$sCustomCss = '';
		$sFeedImageOnClick = '';
		
		if (($aRow['mature'] == 0 || (($aRow['mature'] == 1 || $aRow['mature'] == 2) && Phpfox::getUserId() && Phpfox::getUserParam('advancedphoto.photo_mature_age_limit') <= Phpfox::getUserBy('age'))) || $aRow['user_id'] == Phpfox::getUserId())
		{
			$iImageMax = (Phpfox::getService('profile')->timeline() ? '300' : '450');
			$iImageMaxSuffix = (Phpfox::getService('profile')->timeline() ? '500' : '500');
			$sCustomCss = '' . $sThickbox . ' photo_holder_image';
			$sImage = Phpfox::getLib('image.helper')->display(array(
					'server_id' => $aRow['server_id'],
					'path' => 'photo.url_photo',
					'file' => Phpfox::getService('advancedphoto')->getPhotoUrl(array_merge($aRow, array('full_name' => $aItem['full_name']))),
					'suffix' => '_' . $iImageMaxSuffix,					
					'max_width' => $iImageMax,
					'max_height' => $iImageMax,
					'class' => 'photo_holder'
				)
			);			
		}
		else
		{
			$sImage = Phpfox::getLib('image.helper')->display(array(
					'theme' => 'misc/no_access.png'
				)
			);
			
			$sFeedImageOnClick = ' onclick="tb_show(\'' . Phpfox::getPhrase('advancedphoto.warning') . '\', $.ajaxBox(\'advancedphoto.warning\', \'height=300&width=350&link=' . $sLink . '\')); return false;" ';
			$sCustomCss = 'no_ajax_link';
		}
		
		$aListPhotos = array();	
		if ($aRow['extra_photo_id'] > 0)
		{
			$aPhotos = $this->database()->select('p.photo_id, p.album_id, p.user_id, p.title, p.server_id, p.destination, p.mature')	
				->from(Phpfox::getT('photo_feed'), 'pfeed')
				->join(Phpfox::getT('photo'), 'p', 'p.photo_id = pfeed.photo_id')
				->where('pfeed.feed_id = ' . (int) $aItem['feed_id'])
				->limit(3)
				->order('p.time_stamp DESC')
				->execute('getSlaveRows');
			
			foreach ($aPhotos as $aPhoto)
			{		
				if (($aPhoto['mature'] == 0 || (($aPhoto['mature'] == 1 || $aPhoto['mature'] == 2) && Phpfox::getUserId() && Phpfox::getUserParam('advancedphoto.photo_mature_age_limit') <= Phpfox::getUserBy('age'))) || $aPhoto['user_id'] == Phpfox::getUserId())
				{
					$aListPhotos[] = '<a href="' . Phpfox::permalink('advancedphoto', $aPhoto['photo_id'], $aPhoto['title']) . ($bIsPhotoAlbum ? 'albumid_' . $aRow['album_id'] : 'userid_' . $aRow['user_id']) . '/" class="' . $sThickbox . ' photo_holder_image" rel="' . $aPhoto['photo_id'] . '">' . Phpfox::getLib('image.helper')->display(array(
							'server_id' => $aPhoto['server_id'],
							'path' => 'photo.url_photo',
							'file' => Phpfox::getService('advancedphoto')->getPhotoUrl(array_merge($aPhoto, array('full_name' => $aItem['full_name']))),
							'suffix' => '_100',
							'max_width' => 100,
							'max_height' => 100,
							'class' => 'photo_holder',
							'userid' => isset($aItem['user_id']) ? $aItem['user_id'] : ''
						)
					) . '</a>';
				}
				else
				{
					
					
					$aListPhotos[] = '<a href="#" class="no_ajax_link" onclick="tb_show(\'' . Phpfox::getPhrase('advancedphoto.warning') . '\', $.ajaxBox(\'advancedphoto.warning\', \'height=300&width=350&link=' . Phpfox::permalink('advancedphoto', $aPhoto['photo_id'], $aPhoto['title']) . ($bIsPhotoAlbum ? 'albumid_' . $aRow['album_id'] : 'userid_' . $aRow['user_id']) . '/\')); return false;">' . $sImage . '</a>';
				}
			}	
			
			$aListPhotos = array_merge(array('<a href="' . Phpfox::permalink('advancedphoto', $aRow['photo_id'], $aRow['title']) . ($bIsPhotoAlbum ? 'albumid_' . $aRow['album_id'] : 'userid_' . $aRow['user_id']) . '/" ' . (empty($sFeedImageOnClick) ? ' class="' . $sThickbox . ' photo_holder_image" rel="' . $aRow['photo_id'] . '" ' : $sFeedImageOnClick . ' class="no_ajax_link"') . '>' . $sImage . '</a><br />'), $aListPhotos);
		}
			
		$aReturn = array(
			'feed_title' => '',
			'feed_image' => (count($aListPhotos) ? $aListPhotos : $sImage),
			'feed_status' => $aRow['description'],
			'feed_link' => $sLink,
			'total_comment' => $aRow['total_comment'],
			'feed_total_like' => $aRow['total_like'],
			'feed_is_liked' => $aRow['is_liked'],
			'feed_icon' => Phpfox::getLib('image.helper')->display(array('theme' => 'feed/advphoto.png', 'return_url' => true)),
			'time_stamp' => $aRow['time_stamp'],			
			'enable_like' => true,			
			'comment_type_id' => 'advancedphoto',
			'like_type_id' => 'advancedphoto',
			'custom_css' => $sCustomCss,
			'custom_rel' => $aRow['photo_id'],
			'custom_js' => $sFeedImageOnClick,
			'no_target_blank' => true
		);	
		
		if ($aRow['module_id'] == 'pages')
		{
			$aRow['parent_user_id'] = '';
			$aRow['parent_user_name'] = '';
		}
		
		if (empty($aRow['parent_user_id']))
		{
			if ($aRow['album_id'])
			{
				$aReturn['feed_status'] = '';
				$aReturn['feed_info'] = Phpfox::getPhrase('feed.added_new_photos_to_gender_album_a_href_link_name_a', array('gender' => Phpfox::getService('user')->gender($aItem['gender'], 1), 'link' => Phpfox::permalink('advancedphoto.album', $aRow['album_id'], $aRow['name']), 'name' => Phpfox::getLib('parse.output')->shorten($aRow['name'], (Phpfox::isModule('notification') ? Phpfox::getParam('notification.total_notification_title_length') : $this->_iFallbackLength ), '...')));
			}
			else
			{
				$aReturn['feed_info'] = (count($aListPhotos) ? Phpfox::getPhrase('feed.shared_a_few_photos') : Phpfox::getPhrase('feed.shared_a_photo'));			
			}
		}
		
		if ($aCallback === null)
		{			
			if (!empty($aRow['parent_user_name']) && !defined('PHPFOX_IS_USER_PROFILE') && empty($_POST))
			{
				$aReturn['parent_user'] = Phpfox::getService('user')->getUserFields(true, $aRow, 'parent_');
			}
			
			if (!PHPFOX_IS_AJAX && defined('PHPFOX_IS_USER_PROFILE') && !empty($aRow['parent_user_name']) && $aRow['parent_user_id'] != Phpfox::getService('profile')->getProfileUserId())
			{
				$aReturn['feed_mini'] = true;
				$aReturn['feed_mini_content'] = Phpfox::getPhrase('advancedphoto.full_name_posted_a_href_link_photo_a_photo_a_on_a_href_link_user_parent_full_name_a_s_a_href_link_wall_wall_a',array('full_name' => Phpfox::getService('user')->getFirstName($aItem['full_name']), 'link_photo' => Phpfox::permalink('advancedphoto', $aRow['photo_id'], $aRow['title']), 'link_user' => Phpfox::getLib('url')->makeUrl($aRow['parent_user_name']), 'parent_full_name' => $aRow['parent_full_name'], 'link_wall' => Phpfox::getLib('url')->makeUrl($aRow['parent_user_name'])));
				
				unset($aReturn['feed_status'], $aReturn['feed_image'], $aReturn['feed_content']);
			}		
		}
		
		if ($bIsChildItem)
		{
			$aReturn = array_merge($aReturn, $aItem);
		}
		
		(($sPlugin = Phpfox_Plugin::get('advancedphoto.component_service_callback_getactivityfeed__1')) ? eval($sPlugin) : false);
		
		return $aReturn;
	}	

	public function tabHasItems($iUser)
	{
		$iCount = $this->database()->select('COUNT(user_id)')
				->from(Phpfox::getT('photo'))
				->where('user_id = ' . (int)$iUser)
				->execute('getSlaveField');
		return $iCount > 0;
	}
	
	public function addLikeAlbum($iItemId, $bDoNotSendEmail = false)
	{
		$aRow = $this->database()->select('album_id, name, user_id')
			->from(Phpfox::getT('photo_album'))
			->where('album_id = ' . (int) $iItemId)
			->execute('getSlaveRow');
			
		if (!isset($aRow['album_id']))
		{
			return false;
		}
		
		$this->database()->updateCount('like', 'type_id = \'photo_album\' AND item_id = ' . (int) $iItemId . '', 'total_like', 'photo_album', 'album_id = ' . (int) $iItemId);	
		
		if (!$bDoNotSendEmail)
		{
			$sLink = Phpfox::permalink('advancedphoto.album', $aRow['album_id'], $aRow['name']);
			
			Phpfox::getLib('mail')->to($aRow['user_id'])
				->subject(array('custom.full_name_liked_your_photo_album_name', array('full_name' => Phpfox::getUserBy('full_name'), 'name' => $aRow['name'])))
				->message(array('custom.full_name_liked_your_photo_album_message', array('full_name' => Phpfox::getUserBy('full_name'), 'link' => $sLink, 'name' => $aRow['name'])))
				->notification('like.new_like')
				->send();
					
			Phpfox::getService('notification.process')->add('advancedphoto_album_like', $aRow['album_id'], $aRow['user_id']);				
		}
	}
	
	public function getNotificationAlbum_Like($aNotification)
	{
		$aRow = $this->database()->select('b.album_id, b.name, b.user_id, u.gender, u.full_name')	
			->from(Phpfox::getT('photo_album'), 'b')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = b.user_id')
			->where('b.album_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');

		if(!isset($aRow['album_id']))
		{
			return false;
		}
			
		$sPhrase = '';
		if ($aNotification['user_id'] == $aRow['user_id'])
		{
			$sPhrase = Phpfox::getPhrase('advancedphoto.user_name_liked_gender_own_photo_album_title',array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'title' => Phpfox::getLib('parse.output')->shorten($aRow['name'], (Phpfox::isModule('notification') ? Phpfox::getParam('notification.total_notification_title_length') : $this->_iFallbackLength ), '...')));
		}
		elseif ($aRow['user_id'] == Phpfox::getUserId())		
		{
			$sPhrase = Phpfox::getPhrase('advancedphoto.user_name_liked_your_photo_album_title',array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'title' =>Phpfox::getLib('parse.output')->shorten($aRow['name'], (Phpfox::isModule('notification') ? Phpfox::getParam('notification.total_notification_title_length') : $this->_iFallbackLength ), '...')));
		}
		else 
		{
			$sPhrase = Phpfox::getPhrase('advancedphoto.user_name_liked_span_class_drop_data_user_full_name_s_span_photo_album_title',array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'full_name' => $aRow['full_name'], 'title' => Phpfox::getLib('parse.output')->shorten($aRow['name'], (Phpfox::isModule('notification') ? Phpfox::getParam('notification.total_notification_title_length') : $this->_iFallbackLength ), '...')));
		}
			
		return array(
			'link' => Phpfox::getLib('url')->permalink('advancedphoto.album', $aRow['album_id'], $aRow['name']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);	
	}	
	
	public function deleteLikeAlbum($iItemId)
	{
		$this->database()->updateCount('like', 'type_id = \'photo_album\' AND item_id = ' . (int) $iItemId . '', 'total_like', 'photo_album', 'album_id = ' . (int) $iItemId);	
	}	
	
	public function addCommentAlbum($aVals, $iUserId = null, $sUserName = null)
	{	
		$aAlbum = $this->database()->select('u.full_name, u.user_id, u.gender, u.user_name, b.name, b.album_id, b.privacy')
			->from(Phpfox::getT('photo_album'), 'b')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = b.user_id')
			->where('b.album_id = ' . (int) $aVals['item_id'])
			->execute('getSlaveRow');
			
		// Update the post counter if its not a comment put under moderation or if the person posting the comment is the owner of the item.
		if (empty($aVals['parent_id']))
		{
			$this->database()->updateCounter('photo_album', 'total_comment', 'album_id', $aVals['item_id']);		
		}
		
		// Send the user an email
		$sLink = Phpfox::permalink('advancedphoto.album', $aAlbum['album_id'], $aAlbum['name']);
		
		Phpfox::getService('comment.process')->notify(array(
				'user_id' => $aAlbum['user_id'],
				'item_id' => $aAlbum['album_id'],
				'owner_subject' => Phpfox::getPhrase('advancedphoto.full_name_commented_on_your_photo_album_name', array('full_name' => Phpfox::getUserBy('full_name'), 'name' => $aAlbum['name'])),
				'owner_message' => 
		    Phpfox::getPhrase('advancedphoto.full_name_commented_on_your_photo_album_a_href_link_title_a_to_see_the_comment_thread_follow_the_link_below_a_href_link_link_a',array('full_name' => Phpfox::getUserBy('full_name'), 'link' => $sLink, 'title' => $aAlbum['name'])),
				'owner_notification' => 'comment.add_new_comment',
				'notify_id' => 'comment_advancedphoto_album',
				'mass_id' => 'advancedphoto_album',
				'mass_subject' => (Phpfox::getUserId() == $aAlbum['user_id'] ? 
			    Phpfox::getPhrase('advancedphoto.full_name_commented_on_gender_photo_album',array('full_name' => Phpfox::getUserBy('full_name'), 'gender' => Phpfox::getService('user')->gender($aAlbum['gender'], 1)))			    
			    : 
			    Phpfox::getPhrase('advancedphoto.full_name_commented_on_other_full_name_s_photo_album',array('full_name' => Phpfox::getUserBy('full_name'), 'other_full_name' => $aAlbum['full_name'])))
			    ,
				'mass_message' => (Phpfox::getUserId() == $aAlbum['user_id'] ? 
			    Phpfox::getPhrase('advancedphoto.full_name_commented_on_gender_photo_album_a_href_link_title_a_to_see_the_comment_thread_follow_the_link_below_a_href_link_link_a', array('full_name' => Phpfox::getUserBy('full_name'), 'gender' =>  Phpfox::getService('user')->gender($aAlbum['gender'], 1), 'link' => $sLink, 'title' => $aAlbum['name']))
			    
			    : 
			    Phpfox::getPhrase('advancedphoto.full_name_commented_on_other_full_name_s_photo_album_a_href_link_title_a_to_see_the_comment_thread_follow_the_link_below_a_href_link_link_a', array('full_name' => Phpfox::getUserBy('full_name'), 'other_full_name' => $aAlbum['full_name'], 'link' => $sLink, 'title' => $aAlbum['name'])))
			)
		);
		
		(($sPlugin = Phpfox_Plugin::get('blog.component_service_callback_addcomment__end')) ? eval($sPlugin) : false);
	}	
	
	public function getAjaxCommentVarAlbum()
	{
		return null;
	}	
	
	public function getCommentItemAlbum($iId)
	{
		$aRow = $this->database()->select('album_id AS comment_item_id, privacy_comment, user_id AS comment_user_id')
			->from(Phpfox::getT('photo_album'))
			->where('album_id = ' . (int) $iId)
			->execute('getSlaveRow');		

		if (!isset($aRow['comment_item_id']))
		{
			return false;
		}
			
		$aRow['comment_view_id'] = '0';
		
		if (!Phpfox::getService('comment')->canPostComment($aRow['comment_user_id'], $aRow['privacy_comment']))
		{
			Phpfox_Error::set(Phpfox::getPhrase('advancedphoto.unable_to_post_a_comment_on_this_item_due_to_privacy_settings'));
			
			unset($aRow['comment_item_id']);
		}
			
		return $aRow;
	}	
	
	public function getCommentNotificationAlbum($aNotification)
	{
		$aRow = $this->database()->select('b.album_id, b.name, b.user_id, u.gender, u.full_name')	
			->from(Phpfox::getT('photo_album'), 'b')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = b.user_id')
			->where('b.album_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');

		if (!isset($aRow['album_id']))
		{
			return false;
		}
		
		$sPhrase = '';
		if ($aNotification['user_id'] == $aRow['user_id'] && !isset($aNotification['extra_users']))
		{
			$sPhrase = Phpfox::getPhrase('advancedphoto.user_name_commented_on_gender_photo_album_title',array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'gender' => Phpfox::getService('user')->gender($aRow['gender'], 1), 'title' => Phpfox::getLib('parse.output')->shorten($aRow['name'], (Phpfox::isModule('notification') ? Phpfox::getParam('notification.total_notification_title_length') : $this->_iFallbackLength ), '...')));
		}
		elseif ($aRow['user_id'] == Phpfox::getUserId())		
		{
			$sPhrase = Phpfox::getPhrase('advancedphoto.user_name_commented_on_your_photo_album_title',array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'title' => Phpfox::getLib('parse.output')->shorten($aRow['name'], (Phpfox::isModule('notification') ? Phpfox::getParam('notification.total_notification_title_length') : $this->_iFallbackLength ), '...')));
		}
		else 
		{
			$sPhrase = Phpfox::getPhrase('advancedphoto.user_name_commented_on_span_class_drop_data_user_full_name_s_span_photo_album_title',array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'full_name' => $aRow['full_name'], 'title' => Phpfox::getLib('parse.output')->shorten($aRow['name'], (Phpfox::isModule('notification') ? Phpfox::getParam('notification.total_notification_title_length') : $this->_iFallbackLength ), '...')));
		}
			
		return array(
			'link' => Phpfox::getLib('url')->permalink('advancedphoto.album', $aRow['album_id'], $aRow['name']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);
	}	
	
	public function getActivityFeedAlbum($aItem, $aCallback = null)
	{	
		$aRow = $this->database()->select('pa.*, l.like_id AS is_liked, pai.description')
			->from(Phpfox::getT('photo_album'), 'pa')			
			->join(Phpfox::getT('photo_album_info'), 'pai', 'pai.album_id = pa.album_id')
			->leftJoin(Phpfox::getT('like'), 'l', '(l.type_id = \'photo_album\' OR l.type_id = \'advancedphoto_album\') AND l.item_id = pa.album_id AND l.user_id = ' . Phpfox::getUserId())
			->where('pa.album_id = ' . (int) $aItem['item_id'])
			->execute('getSlaveRow');
			
		if (!isset($aRow['album_id']))
		{
			return false;
		}
			
		$aPhotos = $this->database()->select('p.photo_id, p.title, p.server_id, p.destination')	
			->from(Phpfox::getT('photo'), 'p')
			->where('p.album_id = ' . $aRow['album_id'] . ' AND p.view_id = 0')
			->limit(3)
			->order('p.time_stamp DESC')
			->execute('getSlaveRows');
		
		$aListPhotos = array();	
		foreach ($aPhotos as $aPhoto)
		{			
			$aListPhotos[] = '<a href="' . Phpfox::permalink('advancedphoto', $aPhoto['photo_id'], $aPhoto['title']) . '" class="ynadvphoto_thickbox photo_holder_image ' . (Phpfox::getParam('advancedphoto.view_photos_in_theater_mode') ? 'no_ajax_link' : '') . '" rel="' . $aPhoto['photo_id'] . '">' . Phpfox::getLib('image.helper')->display(array(
					'server_id' => $aPhoto['server_id'],
					'path' => 'photo.url_photo',
					'file' => Phpfox::getService('advancedphoto')->getPhotoUrl(array_merge($aPhoto, array('full_name' => $aItem['full_name']))),
					'suffix' => '_100',
					'max_width' => 100,
					'max_height' => 100,
					'class' => 'photo_holder'			
				)
			) . '</a>';
		}	
			
		$aReturn = array(
			'feed_title' => '',
			'feed_info' => Phpfox::getPhrase('feed.added_new_photos_to_gender_album_a_href_link_name_a', array('gender' => Phpfox::getService('user')->gender($aItem['gender'], 1), 'link' => Phpfox::permalink('advancedphoto.album', $aRow['album_id'], $aRow['name']), 'name' => Phpfox::getLib('parse.output')->shorten($aRow['name'], (Phpfox::isModule('notification') ? Phpfox::getParam('notification.total_notification_title_length') : $this->_iFallbackLength ), '...'))),
			'feed_image' => $aListPhotos,
			'feed_status' => $aRow['description'],
			'feed_link' => Phpfox::permalink('advancedphoto.album', $aRow['album_id'], $aRow['name']),
			'total_comment' => $aRow['total_comment'],
			'feed_total_like' => $aRow['total_like'],
			'feed_is_liked' => $aRow['is_liked'],
			'feed_icon' => Phpfox::getLib('image.helper')->display(array('theme' => 'module/photo.png', 'return_url' => true)),
			'time_stamp' => $aRow['time_stamp'],			
			'enable_like' => true,			
			'comment_type_id' => 'advancedphoto_album',
			'like_type_id' => 'advancedphoto_album',
			'feed_custom_width' => '400px'
		);	
		
		return $aReturn;
	}	
	
	public function getActivityFeedTag($aItem, $aCallback = null)
	{
		$aRow = $this->database()->select('p.photo_id, p.title, p.time_stamp, p.server_id, p.destination, p.user_id AS photo_user_id, p.privacy, f.friend_id AS is_friend, pi.description, ' . Phpfox::getUserField() . ', ' . Phpfox::getUserField('u2', 'photo_'))
			->from(Phpfox::getT('photo_tag'), 'pt')
			->join(Phpfox::getT('photo'), 'p', 'p.photo_id = pt.photo_id')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = ' . (int) $aItem['parent_user_id'])
			->join(Phpfox::getT('user'), 'u2', 'u2.user_id = p.user_id')
			->leftJoin(Phpfox::getT('friend'), 'f', "f.user_id = p.user_id AND f.friend_user_id = " . Phpfox::getUserId())
			->leftJoin(Phpfox::getT('photo_info'), 'pi', 'pi.photo_id = p.photo_id')
			->where('pt.tag_id = ' . (int) $aItem['item_id'])
			->execute('getSlaveRow');

			
		if (!isset($aRow['photo_id']))
		{
			return false;
		}

		$bCanViewItem = true;
		if ($aRow['privacy'] > 0)
		{
			$bCanViewItem = Phpfox::getService('privacy')->check('advancedphoto', $aRow['photo_id'], $aRow['photo_user_id'], $aRow['privacy'], $aRow['is_friend'], true);
		}
		
		$sLink = Phpfox::permalink('advancedphoto', $aRow['photo_id'], $aRow['title']);
		
		if ($aItem['user_id'] == $aRow['photo_user_id'])
		{
			$sPhrase = Phpfox::getPhrase('feed.tagged_a_href_row_link_full_name_a_on_gender_a_href_link_photo_a', array('row_link' => Phpfox::getLib('url')->makeUrl($aRow['user_name']), 'full_name' => $aRow['full_name'], 'gender' => Phpfox::getService('user')->gender($aItem['gender'], 1), 'link' => $sLink));
		}
		elseif ($aItem['user_id'] == $aRow['user_id'])
		{
			$sPhrase = Phpfox::getPhrase('advancedphoto.tagged_gender_on_a_href_link_photo_full_name_s_photo', array('gender' => Phpfox::getService('user')->gender($aItem['gender'], 2), 'link' => Phpfox::getLib('url')->makeUrl($aRow['photo_user_name']), 'photo_full_name' => $aRow['photo_full_name']));
		}
		else 
		{
			$sPhrase = Phpfox::getPhrase('advancedphoto.tagged_a_href_row_link_full_name_a_on_a_href_photo_user_name_photo_full_name_a_a_href_link_photo_a', array('row_link' => Phpfox::getLib('url')->makeUrl($aRow['user_name']), 'full_name' => $aRow['full_name'], 'photo_user_name' => Phpfox::getLib('url')->makeUrl($aRow['photo_user_name']), 'photo_full_name' => $aRow['photo_full_name'], 'link' => $sLink));
		}

		$aReturn = array(
			'feed_info' => $sPhrase,
			'feed_link' => Phpfox::permalink('advancedphoto', $aRow['photo_id'], $aRow['title']),
			'feed_icon' => Phpfox::getLib('image.helper')->display(array('theme' => 'misc/group.png', 'return_url' => true)),
			'time_stamp' => $aRow['time_stamp'],			
			'enable_like' => false,
		);	
			
		if ($bCanViewItem)
		{
			$aReturn['feed_image'] = Phpfox::getLib('image.helper')->display(array(
					'server_id' => $aRow['server_id'],
					'path' => 'photo.url_photo',
					'file' => Phpfox::getService('advancedphoto')->getPhotoUrl($aRow),
					'suffix' => '_100',
					'max_width' => 100,
					'max_height' => 100,
					'class' => 'photo_holder'
				)
			);			

			$aReturn['feed_title'] = $aRow['title'];
			$aReturn['feed_content'] = $aRow['description'];
		}
		else
		{
			$aReturn['feed_title'] = '';
		}
		
		return $aReturn;	
	}


	public function getActivityFeedAlbum_Tag($aItem, $aCallback = null)
	{
		$aRow = $this->database()->select('pa.album_id, pa.name, pa.time_stamp, p.server_id, p.destination, pa.user_id AS album_user_id, pa.privacy, f.friend_id AS is_friend, pai.description, ' . Phpfox::getUserField() . ', ' . Phpfox::getUserField('u2', 'album_'))
			->from(Phpfox::getT('advancedphoto_album_tag'), 'pat')
			->join(Phpfox::getT('photo_album'), 'pa', 'pa.album_id = pat.album_id')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = ' . (int) $aItem['parent_user_id'])
			->join(Phpfox::getT('user'), 'u2', 'u2.user_id = pa.user_id')
			->leftJoin(Phpfox::getT('photo'), 'p', 'p.album_id = pa.album_id AND pa.view_id = 0 AND p.is_cover = 1')
			->leftJoin(Phpfox::getT('friend'), 'f', "f.user_id = pa.user_id AND f.friend_user_id = " . Phpfox::getUserId())
			->leftJoin(Phpfox::getT('photo_album_info'), 'pai', 'pai.album_id = pa.album_id')
			->where('pat.tag_id = ' . (int) $aItem['item_id'])
			->execute('getSlaveRow');
			
		if (!isset($aRow['album_id']))
		{
			return false;
		}

		$bCanViewItem = true;
		if ($aRow['privacy'] > 0)
		{
			$bCanViewItem = Phpfox::getService('privacy')->check('advancedphoto_album', $aRow['album_id'], $aRow['album_user_id'], $aRow['privacy'], $aRow['is_friend'], true);
		}
		
		$sLink = Phpfox::permalink('advancedphoto.album', $aRow['album_id'], $aRow['name']);
		
		if ($aItem['user_id'] == $aRow['album_user_id'])
		{
			$sPhrase = Phpfox::getPhrase('advancedphoto.tagged_a_href_row_link_full_name_a_on_gender_a_href_link_album_a', array('row_link' => Phpfox::getLib('url')->makeUrl($aRow['user_name']), 'full_name' => $aRow['full_name'], 'gender' => Phpfox::getService('user')->gender($aItem['gender'], 1), 'link' => $sLink));
		}
		elseif ($aItem['user_id'] == $aRow['user_id'])
		{
			$sPhrase = Phpfox::getPhrase('advancedphoto.tagged_gender_on_a_href_link_album_full_name_s_album', array('gender' => Phpfox::getService('user')->gender($aItem['gender'], 2), 'link' => Phpfox::getLib('url')->makeUrl($aRow['album_user_name']), 'album_full_name' => $aRow['album_full_name']));
		}
		else 
		{
			$sPhrase = Phpfox::getPhrase('advancedphoto.tagged_a_href_row_link_full_name_a_on_a_href_album_user_name_album_full_name_a_a_href_link_album_a', array('row_link' => Phpfox::getLib('url')->makeUrl($aRow['user_name']), 'full_name' => $aRow['full_name'], 'album_user_name' => Phpfox::getLib('url')->makeUrl($aRow['album_user_name']), 'album_full_name' => $aRow['album_full_name'], 'link' => $sLink));
		}

		$aReturn = array(
			'feed_info' => $sPhrase,
			'feed_link' => Phpfox::permalink('advancedphoto.album', $aRow['album_id'], $aRow['name']),
			'feed_icon' => Phpfox::getLib('image.helper')->display(array('theme' => 'misc/group.png', 'return_url' => true)),
			'time_stamp' => $aRow['time_stamp'],			
			'enable_like' => false,
		);	
			
		if ($bCanViewItem)
		{
			$aReturn['feed_image'] = Phpfox::getLib('image.helper')->display(array(
					'server_id' => $aRow['server_id'],
					'path' => 'photo.url_photo',
					'file' => Phpfox::getService('advancedphoto')->getPhotoUrl($aRow),
					'suffix' => '_100',
					'max_width' => 100,
					'max_height' => 100,
					'class' => 'photo_holder'
				)
			);			

			$aReturn['feed_title'] = $aRow['name'];
			$aReturn['feed_content'] = $aRow['description'];
		}
		else
		{
			$aReturn['feed_title'] = '';
		}
		
		return $aReturn;	
	}
	
	public function getNotificationTag($aNotification)
	{
		$aRow = $this->database()->select('b.photo_id, b.title, b.user_id, u.gender, u.full_name')	
			->from(Phpfox::getT('photo'), 'b')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = b.user_id')
			->where('b.photo_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');
		
		if (!isset($aRow['user_id']) || empty($aRow['user_id']))
		{
			return false;
		}
		$sPhrase = '';
		if ($aRow['user_id'] == Phpfox::getUserId())		
		{
			$sPhrase = Phpfox::getPhrase('advancedphoto.you_tagged_yourself_in_your_photo_title', array('title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], (Phpfox::isModule('notification') ? Phpfox::getParam('notification.total_notification_title_length') : $this->_iFallbackLength ), '...')));				
		}
		else 
		{
			$sPhrase = Phpfox::getPhrase('advancedphoto.user_name_tagged_you_in_span_class_drop_data_user_full_name_s_span_photo_title',array('user_name' => Phpfox::getService('notification')->getUsers($aNotification), 'full_name' => $aRow['full_name'], 'title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], (Phpfox::isModule('notification') ? Phpfox::getParam('notification.total_notification_title_length') : $this->_iFallbackLength ), '...')));
		}
			
		return array(
			'link' => Phpfox::getLib('url')->permalink('advancedphoto', $aRow['photo_id'], $aRow['title']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog')
		);			
	}
	
	public function getAjaxProfileController()
	{
		return 'advancedphoto.index';
	}
	
	public function getProfileMenu($aUser)
	{
		//in case we just installed advanced photo and it haven't been updated data
		//in case upload photo in page make total_photo larger than total_advancedphoto		
		$iTotal = $this->database()->select('COUNT(*)')->from(Phpfox::getT('photo'))->where('view_id = 0 AND group_id = 0 AND type_id < 2 AND user_id = ' . (int) $aUser['user_id'] )->execute('getSlaveField'); 
		
		if (!Phpfox::getParam('profile.show_empty_tabs'))
		{		
			if ($iTotal == 0)
			{
				return false;
			}
		}
		
		$aSubMenu = array();
		if ($this->request()->get('req2') == 'advancedphoto')
		{
			$aSubMenu[] = array(
				'phrase' => Phpfox::getPhrase('profile.albums'),
				'url' => Phpfox::getLib('url')->makeUrl($aUser['user_name'].'.advancedphoto.albums'),
				'total' => Phpfox::getService('advancedphoto.album')->getAlbumCount($aUser['user_id'])
			);		
		}

		$aMenus[] = array(
			'phrase' => Phpfox::getPhrase('advancedphoto.advanced_photos'),
			'url' => 'profile.advancedphoto',
			'total' => $iTotal,
			'icon' => 'feed/advphoto.png',
			'sub_menu' => $aSubMenu
		);		
		
		return $aMenus;
	}
	
	public function getTotalItemCount($iUserId)
	{
		return array(
			'field' => 'total_advancedphoto',
//			'total' => $this->database()->select('COUNT(*)')->from(Phpfox::getT('photo'))->where('view_id = 0 AND group_id = 0 AND type_id < 2 AND user_id = ' . (int) $iUserId . ($this->database()->isField(Phpfox::getT('photo'), 'yn_is_advphoto') ? ' AND yn_is_advphoto = 1' : ''))->execute('getSlaveField')
		);	
	}		

	public function globalUnionSearch($sSearch)
	{
		$this->database()->select('item.photo_id AS item_id, item.title AS item_title, item.time_stamp AS item_time_stamp, item.user_id AS item_user_id, \'photo\' AS item_type_id, item.destination AS item_photo, item.server_id AS item_photo_server')
			->from(Phpfox::getT('photo'), 'item')
			->where('item.view_id = 0 AND item.privacy = 0 AND ' . $this->database()->searchKeywords('item.title', $sSearch) . '')
			->union();
	}	
	
	public function getSearchInfo($aRow)
	{
		$aInfo = array();
		$aInfo['item_link'] = Phpfox::getLib('url')->permalink('advancedphoto', $aRow['item_id'], $aRow['item_title']);
		$aInfo['item_name'] = Phpfox::getPhrase('search.photo');

		$aInfo['item_display_photo'] = Phpfox::getLib('image.helper')->display(array(
				'server_id' => $aRow['item_photo_server'],
				'file' => $aRow['item_photo'],
				'path' => 'photo.url_photo',
				'suffix' => '_150',
				'max_width' => '150',
				'max_height' => '150'				
			)
		);
		
		return $aInfo;
	}	
	
	public function getSearchTitleInfo()
	{
		return array(
			'name' => Phpfox::getPhrase('search.photos')
		);
	}	
	
	public function getGlobalPrivacySettings()
	{
		return array(
			'advancedphoto.default_privacy_setting' => array(
				'phrase' => Phpfox::getPhrase('advancedphoto.advanced_photos')								
			)
		);
	}	
	
	public function getPageSubMenu($aPage)
	{
		if (!Phpfox::getService('pages')->hasPerm($aPage['page_id'], 'advancedphoto.share_photos'))
		{
			return null;
		}		
		
		return array(
			array(
				'phrase' => Phpfox::getPhrase('advancedphoto.upload_a_new_image'),
				'url' => Phpfox::getLib('url')->makeUrl('advancedphoto.add', array('module' => 'pages', 'item' => $aPage['page_id']))
			)
		);
	}	
	
	public function getPageMenu($aPage)
	{
		return false;
		if (!Phpfox::getService('pages')->hasPerm($aPage['page_id'], 'advancedphoto.view_browse_photos'))
		{
			return null;
		}			
		
		$aSubMenu = array();
		
		if ($this->request()->get('req3') == 'advancedphoto' || $this->request()->get('req2') == 'advancedphoto')
		{
			$aSubMenu[] = array(
				'phrase' => Phpfox::getPhrase('advancedphoto.all_albums'),
				'url' => 'albums/'				
			);		
			$aSubMenu[] = array(
				'phrase' => Phpfox::getPhrase('advancedphoto.my_albums'),
				'url' => 'albums/view_myalbums/'				
			);		
		}
		
		$aMenus[] = array(
			'phrase' => Phpfox::getPhrase('pages.photos'),
			'url' => Phpfox::getService('pages')->getUrl($aPage['page_id'], $aPage['title'], $aPage['vanity_url']) . 'photo/',
			'icon' => 'feed/photo.png',
			'landing' => 'advancedphoto',
			'sub_menu' => $aSubMenu
		);
		
		return $aMenus;
	}		
	
	public function getPagePerms()
	{
		$aPerms = array();
		
		$aPerms['advancedphoto.share_photos'] = Phpfox::getPhrase('advancedphoto.who_can_share_a_photo');
		$aPerms['advancedphoto.view_browse_photos'] = Phpfox::getPhrase('advancedphoto.who_can_view_browse_photos');
		
		return $aPerms;
	}
	
	public function canViewPageSection($iPage)
	{
		if (!Phpfox::getService('pages')->hasPerm($iPage, 'advancedphoto.view_browse_photos'))
		{
			return false;
		}
		
		return true;
	}
	
	public function checkFeedShareLink()
	{
		if (Phpfox::isMobile())
		{
			//return false;
		}
		
		if (defined('PHPFOX_IS_PAGES_VIEW') && !Phpfox::getService('pages')->hasPerm(null, 'advancedphoto.share_photos'))
		{
			return false;
		}
		
		if (!Phpfox::getUserParam('advancedphoto.can_upload_photos'))
		{
			return false;
		}
		
		if (defined('PHPFOX_IS_USER_PROFILE') || (Phpfox::getLib('request')->get('req1') == 'feed'))
		{
		
		}
		else 
		{
			if (Phpfox::isMobile())
			{
				return false;
			}
		}
	}	
	
	public function getNotificationApproved($aNotification)
	{
		$aRow = $this->database()->select('b.photo_id, b.title, b.user_id, u.gender, u.full_name')	
			->from(Phpfox::getT('photo'), 'b')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = b.user_id')
			->where('b.photo_id = ' . (int) $aNotification['item_id'])
			->execute('getSlaveRow');	

		if (!isset($aRow['photo_id']))
		{
			return false;
		}
		
		$sPhrase = Phpfox::getPhrase('advancedphoto.your_photo_title_has_been_approved', array('title' => Phpfox::getLib('parse.output')->shorten($aRow['title'], Phpfox::getParam('notification.total_notification_title_length'), '...')));
			
		return array(
			'link' => Phpfox::getLib('url')->permalink('advancedphoto', $aRow['photo_id'], $aRow['title']),
			'message' => $sPhrase,
			'icon' => Phpfox::getLib('template')->getStyle('image', 'activity.png', 'blog'),
			'no_profile_image' => true
		);			
	}	
	
	/**
	 * If a call is made to an unknown method attempt to connect
	 * it to a specific plug-in with the same name thus allowing 
	 * plug-in developers the ability to extend classes.
	 *
	 * @param string $sMethod is the name of the method
	 * @param array $aArguments is the array of arguments of being passed
	 */
	public function __call($sMethod, $aArguments)
	{
		/**
		 * Check if such a plug-in exists and if it does call it.
		 */
		if ($sPlugin = Phpfox_Plugin::get('advancedphoto.service_callback__call'))
		{
			return eval($sPlugin);
		}
			
		/**
		 * No method or plug-in found we must throw a error.
		 */
		Phpfox_Error::trigger('Call to undefined method ' . __CLASS__ . '::' . $sMethod . '()', E_USER_ERROR);
	}	
}

?>