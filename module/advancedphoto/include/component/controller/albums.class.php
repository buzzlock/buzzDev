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
 * @version 		$Id: albums.class.php 4562 2012-07-23 14:16:07Z Miguel_Espinoza $
 */
class Advancedphoto_Component_Controller_Albums extends Phpfox_Component
{
	private function _checkIsInHomePageAlbums()
	{
		$bIsInHomePageAlbums = false;
		$aParentModule = $this->getParam('aParentModule');	
		$sTempView = $this->request()->get('view', false);
		 if ($sTempView == "" && !isset($aParentModule['module_id']) && !$this->request()->get('search-id')
                && !$this->request()->get('sort')
                && $this->request()->get('req3') == '') {
            if (!defined('PHPFOX_IS_USER_PROFILE')) {
				$bIsInHomePageAlbums = true;
            }
        }

		return $bIsInHomePageAlbums;

	}
	
	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process()
	{
		Phpfox::getUserParam('advancedphoto.can_view_photos', true);
		
		$aParentModule = $this->getParam('aParentModule');	
		
		if ($iDeleteId = $this->request()->getInt('delete'))
		{
			if (Phpfox::getService('advancedphoto.album.process')->delete($iDeleteId))
			{
				$this->url()->send('advancedphoto.albums', null, Phpfox::getPhrase('advancedphoto.photo_album_successfully_deleted'));
			}
		}		

		$bIsInHomePageAlbums = $this->_checkIsInHomePageAlbums();
		
		$bIsUserProfile = false;
		if (defined('PHPFOX_IS_AJAX_CONTROLLER'))
		{
			$bIsUserProfile = true;
			$aUser = Phpfox::getService('user')->get($this->request()->get('profile_id'));
			$this->setParam('aUser', $aUser);
		}		
		
		if (defined('PHPFOX_IS_USER_PROFILE'))
		{
			$bIsUserProfile = true;
			$aUser = $this->getParam('aUser');
		}
		
		$sPhotoUrl = ($bIsUserProfile ? $this->url()->makeUrl($aUser['user_name']. '.photo.albums') : ($aParentModule === null ? $this->url()->makeUrl('advancedphoto.albums') : $aParentModule['url'] . 'albums/photo/'));
		
		$aBrowseParams = array(
			'module_id' => 'advancedphoto.album',
			'alias' => 'pa',
			'field' => 'album_id',
			'table' => Phpfox::getT('photo_album'),
			'hide_view' => array('pending', 'myalbums')
		);		
		
		$this->search()->set(array(
				'type' => 'advancedphoto.album',
				'field' => 'pa.album_id',				
				'search_tool' => array(
					'table_alias' => 'pa',
					'search' => array(
						'action' => $sPhotoUrl,
						'default_value' => Phpfox::getPhrase('advancedphoto.search_photo_albums'),
						'name' => 'search',
						'field' => 'pa.name'
					),
					'sort' => array(
						'latest' => array('pa.time_stamp', Phpfox::getPhrase('advancedphoto.latest')),
						'most-talked' => array('pa.total_comment', Phpfox::getPhrase('advancedphoto.most_discussed')),
						'yn_ordering' =>  array('pa.yn_ordering DESC, pa.time_stamp', NULL)
					),
					'show' => array(12, 24, 36)
				)
			)
		);			

		$bIsInMyAlbum = false;
		$bIsInFriendAlbum = false;
		$bIsInMyAlbumEditMode = false;
		if ($bIsUserProfile)
		{
			$this->search()->setCondition('AND pa.view_id ' . ($aUser['user_id'] == Phpfox::getUserId() ? 'IN(0,2)' : '= 0') . ' AND pa.privacy IN(' . (Phpfox::getParam('core.section_privacy_item_browsing') ? '%PRIVACY%' : Phpfox::getService('core')->getForBrowse($aUser)) . ') AND pa.user_id = ' . (int) $aUser['user_id']);

			if($aUser['user_id'] == Phpfox::getUserId())
			{
				$bIsInMyAlbum = true;
			}

			if(!Phpfox::getService('advancedphoto')->checkIsInSearchMode())
			{
				Phpfox::getLib('request')->set(array(
					'sort' => 'yn_ordering',
				));
			}

			if($this->request()->get('mode') == 'edit')
			{
				$bIsInMyAlbumEditMode = true;
				// we want to show all albums of user
				Phpfox::getLib('request')->set(array(
					'sort' => 'yn_ordering',
					'show' => 10000,
					'page' => 0 
				));
			}
		}
		else
		{	
			if ($this->request()->get('view') == 'myalbums' )
			{
				Phpfox::isUser(true);
				$bIsInMyAlbum = true;

				if($this->request()->get('mode') == 'edit')
				{
					$bIsInMyAlbumEditMode = true;
					// we want to show all albums of user
					Phpfox::getLib('request')->set(array(
						'sort' => 'yn_ordering',
						'show' => 10000,
						'page' => 0 
					));
				}
				else
				{
					if(!Phpfox::getService('advancedphoto')->checkIsInSearchMode())
					{
						Phpfox::getLib('request')->set(array(
							'sort' => 'yn_ordering',
						));
					}
				}
				$this->search()->setCondition('AND pa.user_id = ' . Phpfox::getUserId());
			}
			else if ($this->request()->get('view') == 'friend')
			{
				Phpfox::isUser(true);
				$bIsInFriendAlbum = true;
			}
			else
			{
				$this->search()->setCondition('AND pa.view_id = 0 AND pa.privacy IN(%PRIVACY%) AND pa.total_photo > 0');
			}
		}	
		
		if ($aParentModule !== null && !empty($aParentModule['item_id']))
		{
			$this->search()->setCondition('AND pa.module_id = \'' . $aParentModule['module_id']. '\' AND pa.group_id = ' . (int) $aParentModule['item_id']);
		}
		
		$this->search()->browse()->params($aBrowseParams)->execute();
		
		$aAlbums = $this->search()->browse()->getRows();
		$iCnt = $this->search()->browse()->getCount();		
//		var_dump($this->search()->getSort());
		
		if (defined('PHPFOX_IS_USER_PROFILE'))
		{
			$bIsUserProfile = true;
			$aUser = $this->getParam('aUser');
			if (!Phpfox::getService('user.privacy')->hasAccess($aUser['user_id'], 'advancedphoto.display_on_profile'))
			{
				$aAlbums = array();
				$iCnt = 0;
			}
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
		
		$this->template()//->setTitle(Phpfox::getPhrase('advancedphoto.photo_albums'))
			->setHeader(array(
					'pager.css' => 'style_css',
					'advphoto.css' => 'module_advancedphoto',
					'mobile.css' => 'module_advancedphoto',
					'jquery.dragsort-0.5.1.js' => 'module_advancedphoto',
					'ynphoto.js' => 'module_advancedphoto',
                    'galleria-1.2.8.js' => 'module_advancedphoto',
                    'slide.js' => 'module_advancedphoto',
				)
			)
			->setBreadcrumb(Phpfox::getPhrase('advancedphoto.photos'), $this->url()->makeUrl('advancedphoto'))
			->assign(array(
				'aAlbums' => $aAlbums,
				'bIsInHomePageAlbums' => $bIsInHomePageAlbums,
				'bIsInMyAlbumEditMode' => $bIsInMyAlbumEditMode,
				'bIsInMyAlbum' => $bIsInMyAlbum,
				'bIsInFriendAlbum' => $bIsInFriendAlbum,
				'bIsUserProfile' => $bIsUserProfile
			)
		);	
		$this->setParam('bIsInHomePageAlbums', $bIsInHomePageAlbums);

		// for an unnoticed reason, these below files shouldn't be cached
		$this->template()->setHeader(array(
			'advphoto.css' => 'module_advancedphoto',
		));
		
		if ($aParentModule === null)
		{
			Phpfox::getService('advancedphoto')->buildMenu();
		}			
		//work around, to make all albums title diappear
		if($this->request()->get('search-id'))
		{
			$this->template()->setBreadcrumb(Phpfox::getPhrase('advancedphoto.search_album_results'), '', true);
		}
		else if($bIsInHomePageAlbums)
		{
			$this->template()->setBreadcrumb('', '', true);
		}
	}
	
	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean()
	{
		(($sPlugin = Phpfox_Plugin::get('advancedphoto.component_controller_albums_clean')) ? eval($sPlugin) : false);
	}
}

?>