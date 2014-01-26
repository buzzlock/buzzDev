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
class Advancedphoto_Component_Controller_View extends Phpfox_Component
{
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{	
		(($sPlugin = Phpfox_Plugin::get('advancedphoto.component_controller_view__1')) ? eval($sPlugin) : false);
		Phpfox::getUserParam('advancedphoto.can_view_photos', true);
		define('PHPFOX_SHOW_TAGS', true); 
		(($sPlugin = Phpfox_Plugin::get('advancedphoto.component_controller_view__2')) ? eval($sPlugin) : false);
		
		$aCallback = $this->getParam('aCallback', null);
		$sId = $this->request()->get('req2');
		$sAction = $this->request()->get('req4');		
		$aUser = $this->getParam('aUser');
		$this->setParam('sTagType','photo'); // fixes http://forums.phpfox.com/project.php?issueid=5274
		(($sPlugin = Phpfox_Plugin::get('advancedphoto.component_controller_view_process_start')) ? eval($sPlugin) : false);
		
		if (Phpfox::isUser() && Phpfox::isModule('notification'))
		{
			Phpfox::getService('notification.process')->delete('comment_advancedphoto', $this->request()->getInt('req2'), Phpfox::getUserId());
			Phpfox::getService('notification.process')->delete('advancedphoto_like', $this->request()->getInt('req2'), Phpfox::getUserId());
			Phpfox::getService('notification.process')->delete('advancedphoto_tag', $this->request()->getInt('req2'), Phpfox::getUserId());
		}		
		
		// Get the photo
		$aPhoto = Phpfox::getService('advancedphoto')->getPhoto($sId, $aUser['user_id']);
		
		if (!empty($aPhoto['module_id']) && $aPhoto['module_id'] != 'photo')
		{			
			if ($aCallback = Phpfox::callback($aPhoto['module_id'] . '.getPhotoDetails', $aPhoto))
			{
				$this->template()->setBreadcrumb($aCallback['breadcrumb_title'], $aCallback['breadcrumb_home']);
				$this->template()->setBreadcrumb($aCallback['title'], $aCallback['url_home']);
				if ($aPhoto['module_id'] == 'pages' && !Phpfox::getService('pages')->hasPerm($aCallback['item_id'], 'advancedphoto.view_browse_photos'))
				{
					return Phpfox_Error::display('Unable to view this item due to privacy settings.');
				}					
			}
		}
		
		// No photo founds lets get out of here
		if (!isset($aPhoto['photo_id']))
		{
			return Phpfox_Error::display(Phpfox::getPhrase('advancedphoto.sorry_the_photo_you_are_looking_for_no_longer_exists', array('link' => $this->url()->makeUrl('advancedphoto'))));
		}
		
		if ($aPhoto['user_id'] == Phpfox::getUserId() && $this->request()->get('refresh'))
		{
			header("Cache-Control: no-cache, must-revalidate");
			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		}
		
		Phpfox::getService('core.redirect')->check($aPhoto['title']);
		if (Phpfox::isModule('privacy'))
		{
			Phpfox::getService('privacy')->check('photo', $aPhoto['photo_id'], $aPhoto['user_id'], $aPhoto['privacy'], $aPhoto['is_friend']);
		}
		
		if ($aPhoto['mature'] != 0)
		{
			if (Phpfox::getUserId())
			{	
				if ($aPhoto['user_id'] != Phpfox::getUserId())
				{
					if ($aPhoto['mature'] == 1 && Phpfox::getUserParam(array(
							'advancedphoto.photo_mature_age_limit' => array(
									'>', 
									(int) Phpfox::getUserBy('age')
								)
							)
						)
					)
					{
						// warning check cookie
					}
					elseif ($aPhoto['mature'] == 2 && Phpfox::getUserParam(array(
							'advancedphoto.photo_mature_age_limit' => array(
									'>', 
									(int) Phpfox::getUserBy('age')
								)
							)
						)				
					)
					{
						return Phpfox_Error::display(Phpfox::getPhrase('advancedphoto.sorry_this_photo_can_only_be_viewed_by_those_older_then_the_age_of_limit', array('limit' => Phpfox::getUserParam('advancedphoto.photo_mature_age_limit'))));	
					}
				}
			}
			else 
			{
				Phpfox::isUser(true);
			}
		}
		
		$this->setParam('bIsValidImage', true);
		
		/* 
			Don't like that this is here, but if added in the service class it would require an extra JOIN to the user table and its such a waste of a query when we could
			just get the users details vis the cached user array.		
		*/
		$aPhoto['bookmark_url'] = $this->url()->permalink('advancedphoto', $aPhoto['photo_id'], $aPhoto['title']);
		
		// Increment the total view
		$aPhoto['total_view'] = ((int) $aPhoto['total_view'] + 1);
		
		(($sPlugin = Phpfox_Plugin::get('advancedphoto.component_controller_view_process_photo')) ? eval($sPlugin) : false);
		
		// Assign the photo array so other blocks can use this information
		$this->setParam('aPhoto', $aPhoto);	
		define('TAG_ITEM_ID', $aPhoto['photo_id']); // to be used with the cloud block
		
		// Check if we should set another controller
		if (!empty($sAction))
		{
			switch ($sAction)
			{
				case 'all':
					return Phpfox::getLib('module')->setController('advancedphoto.size');
					break;
				case 'download':
					return Phpfox::getLib('module')->setController('advancedphoto.download');
					break;
				default:
					(($sPlugin = Phpfox_Plugin::get('advancedphoto.component_controller_view_process_controller')) ? eval($sPlugin) : false);
					break;
			}			
		}
		
		// Increment the view counter
		if (Phpfox::isModule('track') && Phpfox::isUser() && Phpfox::getUserId() != $aPhoto['user_id'] && !$aPhoto['is_viewed'])
		{
			Phpfox::getService('track.process')->add('photo', $aPhoto['photo_id']);
			Phpfox::getService('advancedphoto.process')->updateCounter($aPhoto['photo_id'], 'total_view');
		}
		
		// Add photo tags to meta keywords
		if (!empty($aPhoto['tag_list']) && $aPhoto['tag_list'] && Phpfox::isModule('tag'))
		{
			$this->template()->setMeta('keywords', Phpfox::getService('tag')->getKeywords($aPhoto['tag_list']));
		}		

		$this->template()->setTitle($aPhoto['title']);
		/*
		if (Phpfox::getParam('advancedphoto.how_many_categories_to_show_in_title') > 0)
		{
		    $aCategories = explode('<br />',$aPhoto['categories']);
		    $sCategories = '';
		    foreach ($aCategories as $iCount => $sCategory)
		    {
				if ($iCount >= Phpfox::getParam('advancedphoto.how_many_categories_to_show_in_title'))
				{
				    break;
				} // clean the categories
				
				$sCategories .= strip_tags($sCategory) . ' - ';
		    }
		    
		    $this->template()->setTitle(rtrim($sCategories, ' - '));
		}
		*/
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
				
		$iUserId = $this->request()->get('userid') ? $this->request()->get('userid') : 0;
		if ($iUserId > 0)
		{
			$this->template()->assign(array('feedUserId' => $iUserId));
		}
		
		$iCategory = ($this->request()->getInt('category') ? $this->request()->getInt('category') : null);		
		
		$this->template()
				->setBreadcrumb(Phpfox::getPhrase('advancedphoto.photos'), ($aCallback === null ? $this->url()->makeUrl('advancedphoto') : $this->url()->makeUrl($aCallback['url_home_photo'])))
				->setBreadcrumb($aPhoto['title'], $this->url()->permalink('advancedphoto', $aPhoto['photo_id'], $aPhoto['title']), true)
				->setMeta('description',  Phpfox::getPhrase('advancedphoto.full_name_s_photo_from_time_stamp', array('full_name' => $aPhoto['full_name'], 'time_stamp' => Phpfox::getTime(Phpfox::getParam('core.description_time_stamp'), $aPhoto['time_stamp']))) . ': ' . (empty($aPhoto['description']) ? $aPhoto['title'] : $aPhoto['title'] . '.' . $aPhoto['description']))
				->setMeta('keywords', $this->template()->getKeywords($aPhoto['title']))
				->setMeta('keywords', Phpfox::getParam('advancedphoto.photo_meta_keywords'))
				->setMeta('description', Phpfox::getParam('advancedphoto.photo_meta_description'))		
				->setMeta('og:image', Phpfox::getLib('image.helper')->display(array(
							'server_id' => $aPhoto['server_id'],
							'path' => 'photo.url_photo',
							'file' => $aPhoto['destination'],
							'suffix' => '_150',
							'return_url' => true
						)
					)
				)
				//right here we keep this photo phrase becaues in file jquery.tag.js, the phrase is used hardcodedly
				->setPhrase(array(
						'photo.none_of_your_files_were_uploaded_please_make_sure_you_upload_either_a_jpg_gif_or_png_file',
						'photo.updating_photo',
						'photo.save',
						'photo.cancel',
						'photo.click_here_to_tag_as_yourself'
					)
				)				
				->setHeader('cache', array(
						'jquery/plugin/jquery.highlightFade.js' => 'static_script',	
						'jquery/plugin/jquery.scrollTo.js' => 'static_script',
						'quick_edit.js' => 'static_script',
						'comment.css' => 'style_css',
						'pager.css' => 'style_css',
						'view.js' => 'module_advancedphoto',
						'jquery.advancedphototag.js' => 'module_advancedphoto',
						'advancedphoto.js' => 'module_advancedphoto',
						'switch_legend.js' => 'static_script',
						'switch_menu.js' => 'static_script',
						'view.css' => 'module_advancedphoto',
						'feed.js' => 'module_feed',
						'edit.css' => 'module_advancedphoto',
						'ynphoto.js' => 'module_advancedphoto'
					)
				)
				->setEditor(array(
						'load' => 'simple'					
					)
				)->assign(array(
					'aForms' => $aPhoto,
					'aCallback' => $aCallback,
					'aPhotoStream' => Phpfox::getService('advancedphoto')->getPhotoStream($aPhoto['photo_id'], ($this->request()->getInt('albumid') ? $this->request()->getInt('albumid') : '0'), $aCallback, $iUserId, $iCategory),
					'bIsTheater' => ($this->request()->get('theater') ? true : false),
					'sPhotoJsContent' => Phpfox::getService('advancedphoto.tag')->getJs($aPhoto['photo_id']),
					'iForceAlbumId' => ($this->request()->getInt('albumid') > 0 ? $this->request()->getInt('albumid') : 0)
				)
			);		
		if ( ($iCategory = $this->request()->getInt('category')) && isset($aPhoto['categories']) && !empty($aPhoto['categories']))
		{
			foreach ($aPhoto['categories'] as $aCategory)
			{
				if (isset($aCategory['category_id']) && $aCategory['category_id'] == $iCategory)
				{
					$this->template()->setBreadcrumb($aCategory[0], $aCategory[1]);
				}
			}
		}

		
		if (!empty($aPhoto['album_title']))
		{
			$this->template()->setTitle($aPhoto['album_title']);
			$this->template()->setMeta('description', '' . Phpfox::getPhrase('advancedphoto.part_of_the_photo_album') . ': ' . $aPhoto['album_title']);
		}
			
		(($sPlugin = Phpfox_Plugin::get('advancedphoto.component_controller_view_process_end')) ? eval($sPlugin) : false);
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('advancedphoto.component_controller_view_clean')) ? eval($sPlugin) : false);	
	}
}

?>