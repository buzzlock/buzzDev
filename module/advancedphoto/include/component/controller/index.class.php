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
 * @version 		$Id: index.class.php 4562 2012-07-23 14:16:07Z Miguel_Espinoza $
 */
class Advancedphoto_Component_Controller_Index extends Phpfox_Component
{
	private function _checkIsInHomePage()
	{
		$bIsInHomePage = false;
		$aParentModule = $this->getParam('aParentModule');	
		$sTempView = $this->request()->get('view', false);
		 if ($sTempView == "" && !isset($aParentModule['module_id']) && !$this->request()->get('search-id')
                && !$this->request()->get('sort')
                && $this->request()->get('req2') == '') {
            if (!defined('PHPFOX_IS_USER_PROFILE')) {
				$bIsInHomePage = true;
            }
        }

		return $bIsInHomePage;

	}
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{		
		if($this->_checkIsInHomePage())
		{
			Phpfox::getComponent('advancedphoto.homepage', NULL, 'controller');
			return ;
        }

		if (defined('PHPFOX_IS_PAGES_VIEW') && $this->request()->get('req4') == 'albums')
		{
			Phpfox::getComponent('advancedphoto.albums', array('bNoTemplate' => true), 'controller');
			
			return;
		}
		
		if (defined('PHPFOX_IS_USER_PROFILE') && ($sLegacyTitle = $this->request()->get('req3')) && !empty($sLegacyTitle))
		{
			if (($sLegacyPhoto = $this->request()->get('req4')) && !empty($sLegacyPhoto))
			{
				$aLegacyItem = Phpfox::getService('core')->getLegacyItem(array(
						'field' => array('photo_id', 'title'),
						'table' => 'photo',		
						'redirect' => 'advancedphoto',
						'title' => $sLegacyPhoto
					)
				);	
			}
			else
			{
				$aLegacyItem = Phpfox::getService('core')->getLegacyItem(array(
						'field' => array('album_id', 'name'),
						'table' => 'photo_album',		
						'redirect' => 'advancedphoto.album',
						'title' => $sLegacyTitle,
						'search' => 'name_url'
					)
				);
			}
		}			
		
		Phpfox::getUserParam('advancedphoto.can_view_photos', true);
		if ($this->request()->get('req2') == 'category')
		{
			$_SESSION['photo_category'] = $this->request()->get('req3');
			$this->template()->setHeader(array('<script type="text/javascript"> var sPhotoCategory = "' . $this->request()->get('req3') . '"; </script>'))
				->assign(array('sPhotoCategory' => $this->request()->get('req3')));
		}
		else
		{
			$_SESSION['photo_category'] = '';
		}
		$aParentModule = $this->getParam('aParentModule');	
		
		if (($iRedirectId = $this->request()->getInt('redirect')) && ($aPhoto = Phpfox::getService('advancedphoto')->getForEdit($iRedirectId)))
		{
			if ($aPhoto['group_id'])
			{
				$aGroup = Phpfox::getService('group')->getGroup($aPhoto['group_id'], true);
				
				$this->url()->send('group', array($aGroup['title_url'], 'advancedphoto', 'view', $aPhoto['title_url']));
			}
			else 
			{
				$this->url()->send($aPhoto['user_name'], array('advancedphoto', ($aPhoto['album_id'] ? $aPhoto['album_url'] : 'view'), $aPhoto['title_url']));
			}
		}
		
		if (($iRedirectAlbumId = $this->request()->getInt('aredirect')) && ($aAlbum = Phpfox::getService('advancedphoto.album')->getForEdit($iRedirectAlbumId)))
		{
			$this->url()->send($aAlbum['user_name'], array('advancedphoto', $aAlbum['name_url']));	
		}
		
		if (($iUnFeature = $this->request()->getInt('unfeature')) && Phpfox::getUserParam('advancedphoto.can_feature_photo'))
		{
			if (Phpfox::getService('advancedphoto.process')->feature($iUnFeature, 0))
			{
				$this->url()->send('advancedphoto', null, Phpfox::getPhrase('advancedphoto.photo_successfully_unfeatured'));
			}
		}
		
		if ($aParentModule === null && $this->request()->getInt('req2') > 0)
		{
			return Phpfox::getLib('module')->setController('advancedphoto.view');			
		}		
		
		if (($sLegacyTitle = $this->request()->get('req2')) && !empty($sLegacyTitle) && !is_numeric($sLegacyTitle))
		{
			if ((defined('PHPFOX_IS_USER_PROFILE') || defined('PHPFOX_IS_PAGES_VIEW')) && $sLegacyTitle == 'photo')
			{
				
			}
			else
			{
				if ($this->request()->get('req3') != '')
				{
					$sLegacyTitle = $this->request()->get('req3');
				}

				$aLegacyItem = Phpfox::getService('core')->getLegacyItem(array(
						'field' => array('category_id', 'name'),
						'table' => 'photo_category',		
						'redirect' => 'advancedphoto.category',
						'title' => $sLegacyTitle,
						'search' => 'name_url'
					)
				);		
			}
		}			
		
		$bIsUserProfile = false;
		if (defined('PHPFOX_IS_AJAX_CONTROLLER'))
		{
			$bIsUserProfile = true;
			$aUser = Phpfox::getService('user')->get($this->request()->get('profile_id'));
			$this->setParam('aUser', $aUser);
		}		
		
		// Used to control privacy 
		$bNoAccess = false;
		if (defined('PHPFOX_IS_USER_PROFILE'))
		{
			$bIsUserProfile = true;
			$aUser = $this->getParam('aUser');
			if (!Phpfox::getService('user.privacy')->hasAccess($aUser['user_id'], 'advancedphoto.display_on_profile'))
			{
				$bNoAccess = true;
			}
		}		
		
		$aCallback = $this->getParam('aCallback', null);
		if (PHPFOX_IS_AJAX)
		{
			if ($this->request()->get('req1') == 'group')
			{
				$aGroup = Phpfox::getService('group')->getGroup($this->request()->get('req2'));
				if (isset($aGroup['group_id']))
				{
					$aCallback = array(
						'group_id' => $aGroup['group_id'],
						'url_home' => 'group.' . $aGroup['title_url'] . '.photo',
						'url_home_array' => array(
							'group',
							array(
								$aGroup['title_url']							
							)
						)						
					);
				}
			}
		}		
		
		$sCategory = null;	
		$aSearch = $this->request()->getArray('search');
		$bIsTagSearch = false;
		$sPhotoUrl = ($bIsUserProfile ? $this->url()->makeUrl($aUser['user_name'], 'advancedphoto') : ($aParentModule === null ? $this->url()->makeUrl('advancedphoto') : $aParentModule['url'] . 'photo/'));
		$this->setParam('sTagType', 'advancedphoto');
		$sView = $this->request()->get('view', false);

		$bIsUseTimelineInterface = false;
		$bIsMassEditUpload = false;
		$iCnt = 0;
		if(($sView === 'my' || $bIsUserProfile) && !$this->request()->get('show') && !$this->request()->get('mode') && !$this->request()->get('search-id') && !$this->request()->get('sort') && !$this->request()->get('when') )
		{
			$bIsUseTimelineInterface = true;	
			$bIsMassEditUpload = true;
		}
		
		if ($iDeleteId = $this->request()->get('delete'))
		{
			if (Phpfox::getService('advancedphoto.process')->delete($iDeleteId))
			{
				$this->url()->forward($sPhotoUrl, Phpfox::getPhrase('advancedphoto.photo_successfully_deleted'));
			}
		}			
		
		$aSort = array(
			'latest' => array('photo.time_stamp', Phpfox::getPhrase('advancedphoto.latest')),
			'most-viewed' => array('photo.total_view', Phpfox::getPhrase('advancedphoto.most_viewed')),
			'most-talked' => array('photo.total_comment', Phpfox::getPhrase('advancedphoto.most_discussed')),
			'most-liked' => array('photo.total_like', Phpfox::getPhrase('advancedphoto.most_liked')),
		);
		
		if (Phpfox::getParam('advancedphoto.can_rate_on_photos'))
		{
			$aSort['top-rating'] = array('photo.total_rating', Phpfox::getPhrase('advancedphoto.top_rated'));
		}
		
		if (Phpfox::getParam('advancedphoto.enable_photo_battle'))
		{
			$aSort['top-battle'] = array('photo.total_battle', Phpfox::getPhrase('advancedphoto.top_battle'));
		}
	
		$aPhotos = array();
		
		$this->search()->set(array(
				'type' => 'photo',
				'field' => 'photo.photo_id',				
				'search_tool' => array(
					'table_alias' => 'photo',
					'search' => array(
						'action' => $sPhotoUrl,
						'default_value' => Phpfox::getPhrase('advancedphoto.search_photos'),
						'name' => 'search',
						'field' => 'photo.title'
					),
					'sort' => $aSort,
					'show' => (array) Phpfox::getUserParam('advancedphoto.total_photos_displays')
				)
			)
		);		

		$aBrowseParams = array(
			'module_id' => 'advancedphoto',
			'alias' => 'photo',
			'field' => 'photo_id',
			'table' => Phpfox::getT('photo'),
			'hide_view' => array('pending', 'my')
		);	
		
		if(!$bIsUseTimelineInterface)
		{

			$bRunPlugin = false;
			if ( ($sPlugin = Phpfox_Plugin::get('advancedphoto.component_controller_index_brunplugin1')) && ( eval($sPlugin) === false))
			{
				return false;
			}
			
			switch ($sView)
			{
				case 'pending':
					Phpfox::getUserParam('advancedphoto.can_approve_photos', true);
					$this->search()->setCondition('AND photo.view_id = 1');
					$this->template()->assign('bIsInApproveMode', true);
					break;
				case 'my':
					Phpfox::isUser(true);
					$this->search()->setCondition('AND photo.user_id = ' . Phpfox::getUserId());		
					if ($this->request()->get('mode') == 'edit')
					{
						list($iAlbumCnt, $aAlbums) = Phpfox::getService('advancedphoto.album')->get('pa.user_id = ' . Phpfox::getUserId());
						$this->template()->assign('bIsEditMode', true);
						$this->template()->assign('aAlbums', $aAlbums);
						if (($sEditPhotos = $this->request()->get('photos')))
						{
							$sEditPhotos = base64_decode(urldecode($sEditPhotos));
							$aEditPhotos = explode(',', $sEditPhotos);
							$sPhotoList = '';
							foreach ($aEditPhotos as $iPhotoId)
							{
								if (empty($iPhotoId))
								{
									continue;
								}
								
								$sPhotoList .= (int) $iPhotoId . ',';
							}
							$sPhotoList = rtrim($sPhotoList, ',');
							if (!empty($sPhotoList))
							{
								$bIsMassEditUpload = true;
								$this->search()->setCondition('AND photo.photo_id IN(' . $sPhotoList . ')');
							}
						}
					}
					break;			
				default:
					if ($bRunPlugin)
					{
						(($sPlugin = Phpfox_Plugin::get('advancedphoto.component_controller_index_plugin1')) ? eval($sPlugin) : false);			
					}
					elseif ($bIsUserProfile)
					{
						if(!$this->request()->get('show') && !$this->request()->get('mode') && !$this->request()->get('search-id') && !$this->request()->get('sort') && !$this->request()->get('when'))
						{
							$bIsUseTimelineInterface = true;	
						}
						$this->search()->setCondition('AND photo.view_id ' . ($aUser['user_id'] == Phpfox::getUserId() ? 'IN(0,2)' : '= 0') . ' AND photo.group_id = 0 AND photo.type_id < 2 AND photo.privacy IN(' . (Phpfox::getParam('core.section_privacy_item_browsing') ? '%PRIVACY%' : Phpfox::getService('core')->getForBrowse($aUser)) . ') AND photo.user_id = ' . (int) $aUser['user_id']);
					}
					else
					{					
						if (defined('PHPFOX_IS_PAGES_VIEW'))
						{
							$this->search()->setCondition('AND photo.view_id = 0 AND photo.module_id = \'' . Phpfox::getLib('database')->escape($aParentModule['module_id']) . '\' AND photo.group_id = ' . (int) $aParentModule['item_id'] . ' AND photo.privacy IN(%PRIVACY%)');
						}
						else
						{					
							$this->search()->setCondition('AND photo.view_id = 0 AND photo.group_id = 0 AND photo.type_id < 2 AND photo.privacy IN(%PRIVACY%)');
						}
					}
					break;	
			}
			
			if ($this->request()->get('req2') == 'category')
			{
				$sCategory = $this->request()->getInt('req3');
				$this->search()->setCondition('AND pcd.category_id = ' . (int) $sCategory);
				$this->setParam('hasSubCategories', true);
			}		
			
			if ($this->request()->get('req2') == 'tag')
			{
				if (($aTag = Phpfox::getService('tag')->getTagInfo('advancedphoto', $this->request()->get('req3'))))
				{
					$this->template()->setBreadCrumb(Phpfox::getPhrase('tag.topic') . ': ' . $aTag['tag_text'] . '', $this->url()->makeUrl('current'), true);				
					
					$this->search()->setCondition('AND tag.tag_text = \'' . Phpfox::getLib('database')->escape($aTag['tag_text']) . '\'');	
				}
			}		
			
			if ($sView == 'featured')
			{ 
				$this->search()->setCondition('AND photo.is_featured = 1');
			}		
			
			Phpfox::getService('advancedphoto.browse')->category($sCategory);
			
			if (!Phpfox::getParam('advancedphoto.display_profile_photo_within_gallery'))
			{
				$this->search()->setCondition('AND photo.is_profile_photo = 0');
			}
			
			$this->search()->browse()->params($aBrowseParams)->execute();
			
			if ($bNoAccess == false)
			{
				$aPhotos = $this->search()->browse()->getRows();
				$iCnt = $this->search()->browse()->getCount();
			}
			else
			{
				$aPhotos = array();
				$iCnt = 0;
			}
			
			
			foreach ($aPhotos as $aPhoto)
			{
				$this->template()->setMeta('keywords', $this->template()->getKeywords($aPhoto['title']));
			}		
			
			$aPager = array(
					'page' => $this->search()->getPage(), 
					'size' => $this->search()->getDisplay(), 
					'count' => $this->search()->browse()->getCount()
				);
			
			if ($aPager['size'] > Phpfox::getUserParam('advancedphoto.max_photo_display_limit'))
			{
				$aPager['size'] = Phpfox::getUserParam('advancedphoto.max_photo_display_limit');
			}
			Phpfox::getLib('pager')->set($aPager);
		}
		//this is the end of condition for getting photos		
		$sLinkToWatchingProfile = '';
		$bIsTimeLineProfile = false;
		if($bIsUserProfile)	
		{
			$sLinkToWatchingProfile = Phpfox::getLib('url')->makeUrl($aUser['user_name'] . '.advancedphoto');
			$bIsTimeLineProfile = Phpfox::getService('advancedphoto.helper')->isTimeline($aUser['user_id']);
		}

		// this to check guess in my photo

		if($bIsUseTimelineInterface && !$bIsUserProfile)
		{
			Phpfox::isUser(true);
		}
		$this->template()->setTitle(($bIsUserProfile ? Phpfox::getPhrase('advancedphoto.full_name_s_photos', array('full_name' => $aUser['full_name'])) : Phpfox::getPhrase('advancedphoto.photos')))
			->setBreadcrumb(Phpfox::getPhrase('advancedphoto.photos'), $sPhotoUrl)
			->setMeta('keywords', Phpfox::getParam('advancedphoto.photo_meta_keywords'))
			->setMeta('description', Phpfox::getParam('advancedphoto.photo_meta_description'))
			->setMeta('description', Phpfox::getPhrase('advancedphoto.site_title_has_a_total_of_total_photo_s', array('site_title' => Phpfox::getParam('core.site_title'), 'total' => $iCnt)))	
			->setPhrase(array(
					'advancedphoto.loading'
				)
			)
			->setHeader('cache', array(
					'browse.js' => 'module_advancedphoto',					
					'jquery/plugin/jquery.highlightFade.js' => 'static_script',					
					'quick_edit.js' => 'static_script',
					'comment.css' => 'style_css',
					'pager.css' => 'style_css',
					'view.js' => 'module_advancedphoto',
					'advancedphoto.js' => 'module_advancedphoto',
					'switch_legend.js' => 'static_script',
					'switch_menu.js' => 'static_script',
					'view.css' => 'module_advancedphoto',
					'mobile.css' => 'module_advancedphoto',
					'feed.js' => 'module_feed',
					'browse.css' => 'module_advancedphoto',
					'edit.css' => 'module_advancedphoto',
					'yn_pinterest.css' => 'module_advancedphoto',
					'jquery.wookmark.js' => 'module_advancedphoto',
					'ynphoto.js'=> 'module_advancedphoto',
					'ynadvphoto_thickbox.js'=> 'module_advancedphoto',
//					'jquery.wookmark.js' => 'module_advancedphoto'
				)
			)
			->assign(array(
					'aPhotos' => $aPhotos,
					'bIsAjax' => PHPFOX_IS_AJAX,
					'sPhotoUrl' => $sPhotoUrl,				
					'sView' => $sView,
					'bIsMassEditUpload' => $bIsMassEditUpload,
					'bIsUseTimelineInterface' => $bIsUseTimelineInterface,
					'bIsUserProfile' => $bIsUserProfile,
					'sLinkToWatchingProfile' => $sLinkToWatchingProfile,
					'bYnIsTimeLineProfile' => $bIsTimeLineProfile
				)
			);	
		// when putting js and css in cache, a cache file will be generated in the static folder of PHpfox
		// this make relative paths in css and js don't work
		// so these files below will be no cached
		$this->template()->setHeader(array(
			'advphoto.css' => 'module_advancedphoto',
		));
		
		if ($this->request()->get('req2') == 'category' && isset($aPhoto) && isset($aPhoto['category_name']) && isset($aPhoto['category_id']))
		{
			$sCatUrl = str_replace(' ', '-', strtolower($aPhoto['category_name']));
			$this->template()->setBreadcrumb($aPhoto['category_name'], $this->url()->makeUrl('advancedphoto.category.' . $aPhoto['category_id'] . '.'). $sCatUrl .'/');
		}
		
		if ($aParentModule === null)
		{
			Phpfox::getService('advancedphoto')->buildMenu();
		}		
		
		if (!empty($sCategory))
		{
			$aCategories = Phpfox::getService('advancedphoto.category')->getParentBreadcrumb($sCategory);
			$iCnt = 0;
			foreach ($aCategories as $aCategory)
			{
				$iCnt++;
				
				$this->template()->setTitle($aCategory[0]);
				/*
				if ($aCallback !== null)
				{
					$sHomeUrl = '/' . Phpfox::getLib('url')->doRewrite($aCallback['url_home_array'][0]) . '/' . implode('/', $aCallback['url_home_array'][1]) . '/' . Phpfox::getLib('url')->doRewrite('photo') . '/';	
					$aCategory[1] = preg_replace('/^http:\/\/(.*?)\/' . Phpfox::getLib('url')->doRewrite('photo') . '\/(.*?)$/i', 'http://\\1' . $sHomeUrl . '\\2', $aCategory[1]);						
				}				
				*/
				$this->template()->setBreadcrumb($aCategory[0], $aCategory[1], ($iCnt === count($aCategories) ? true : false));
			}				
		}

		$this->setParam('sCurrentCategory', $sCategory);
		
		$this->setParam('global_moderation', array(
				'name' => 'advancedphoto',
				'ajax' => 'advancedphoto.moderation',
				'menu' => array(
					array(
						'phrase' => Phpfox::getPhrase('advancedphoto.delete'),
						'action' => 'delete'
					),
					array(
						'phrase' => Phpfox::getPhrase('advancedphoto.approve'),
						'action' => 'approve'
					)					
				)
			)
		);			
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('advancedphoto.component_controller_index_clean')) ? eval($sPlugin) : false);
	}
}

?>
