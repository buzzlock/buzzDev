<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 */
class Advancedphoto_Component_Block_Yntimelinelistphoto extends Phpfox_Component {

	/**
	 * Class process method wnich is used to execute this component.
	 */
	public function process() {
		$iYear = (int) $this->getParam('iYear', false);
		if(!$iYear)
		{
			$iYear = date('Y', PHPFOX_TIME);
		}

		$iPage = (int) $this->getParam('iPage', false) ? $this->getParam('iPage') : 1;
		$iMaxPhotosPerLoad = Phpfox::getService('advancedphoto')->getMaxPhotosPerLoad();
		$iLimit = (int) $this->getParam('iLimit', false) ? $this->getParam('iLimit') : $iMaxPhotosPerLoad;

		Phpfox::getLib('request')->set(array(
			'show' => $iLimit,
			'page' => $iPage
		));
		$bIsAlreadeySetSearch = ($this->getParam('bIsAlreadySetSearch')) ? $this->getParam('bIsAlreadySetSearch') : false;

		//this initialization is written by copy from index.class.php
		//just to make sure everything work exactly the same
		//for the evil of duplication, this should be refined laterly


		$aParentModule = $this->getParam('aParentModule');
		$bIsUserProfile = false;
		

		// Used to control privacy 
		$bNoAccess = false;
		if (defined('PHPFOX_IS_USER_PROFILE')) {
			$bIsUserProfile = true;
			$aUser = $this->getParam('aUser');
			if(!$aUser)
			{
				$aUser = Phpfox::getService('user')->get(Phpfox::getService('profile')->getProfileUserId());
			}

			if (!Phpfox::getService('user.privacy')->hasAccess($aUser['user_id'], 'advancedphoto.display_on_profile')) {
				$bNoAccess = true;
			}
		}

		$sPhotoUrl = ($bIsUserProfile ? $this->url()->makeUrl($aUser['user_name'], 'advancedphoto') : ($aParentModule === null ? $this->url()->makeUrl('advancedphoto') : $aParentModule['url'] . 'advancedphoto/'));

		$aSort = array(
			'latest' => array('photo.time_stamp', Phpfox::getPhrase('advancedphoto.latest')),
			'most-viewed' => array('photo.total_view', Phpfox::getPhrase('advancedphoto.most_viewed')),
			'most-talked' => array('photo.total_comment', Phpfox::getPhrase('advancedphoto.most_discussed'))
		);

		if (Phpfox::getParam('advancedphoto.can_rate_on_photos')) {
			$aSort['top-rating'] = array('photo.total_rating', Phpfox::getPhrase('advancedphoto.top_rated'));
		}

		if (Phpfox::getParam('advancedphoto.enable_photo_battle')) {
			$aSort['top-battle'] = array('photo.total_battle', Phpfox::getPhrase('advancedphoto.top_battle'));
		}
		if (!$bIsAlreadeySetSearch) {

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
		}

		$aBrowseParams = array(
			'module_id' => 'advancedphoto',
			'alias' => 'photo',
			'field' => 'photo_id',
			'table' => Phpfox::getT('photo'),
			'hide_view' => array('pending', 'my')
		);

		$iStartYear = mktime(0, 0, 0, 1, 1, $iYear);
		$iEndYear = mktime(23, 59, 59, 12, 31, $iYear);
		$this->search()->setCondition(' AND photo.time_stamp > \'' . $iStartYear . '\' AND photo.time_stamp <= \'' . $iEndYear . '\'');
		
		// var_dump($this->search()->getConditions());

		//finish copying
		//currently we only work on my photo page so the condition is pretty simple
		//later on, we might need to add more conditions here
		
		if ($bIsUserProfile)
        {
            $this->search()->setCondition('AND photo.view_id ' . ($aUser['user_id'] == Phpfox::getUserId() ? 'IN(0,2)' : '= 0') . ' AND photo.group_id = 0 AND photo.type_id < 2 AND photo.privacy IN(' . (Phpfox::getParam('core.section_privacy_item_browsing') ? '%PRIVACY%' : Phpfox::getService('core')->getForBrowse($aUser)) . ') AND photo.user_id = ' . (int) $aUser['user_id']);
		}
		else
		{
			$this->search()->setCondition('AND photo.user_id = ' . Phpfox::getUserId());
		}

		$this->search()->browse()->params($aBrowseParams)->execute();
		$iCnt = $this->search()->browse()->getCount();

		$aPhotos = $this->search()->browse()->getRows();
		$bIsLoadMore = true;
		$iTotalLoadedPhotos = count($aPhotos);

		// the page number also includes current loaded photo, to get real number we must subtract 1
		$aTotalCurrentPhotos = ($iPage - 1) * $iMaxPhotosPerLoad + $iTotalLoadedPhotos;
		if($iTotalLoadedPhotos < $iMaxPhotosPerLoad || ($iCnt === $aTotalCurrentPhotos))
		{
			$bIsLoadMore = false;
		}
		foreach ($aPhotos as $aPhoto)
		{
			$this->template()->setMeta('keywords', $this->template()->getKeywords($aPhoto['title']));
		}		
//		$iCnt = $this->search()->browse()->getCount();

		$this->template()->assign(array(
				'aPhotos' => $aPhotos,
				'bIsUseTimelineInterface' => true,
				'iYear' => $iYear,
				'iMaxPhotosPerLoad' => $iMaxPhotosPerLoad,
				'bIsLoadMore' => $bIsLoadMore,
				'iNextPage' => $iPage + 1,
				'sView' => 'my'
			)
		);
	}

	/**
	 * Garbage collector. Is executed after this class has completed
	 * its job and the template has also been displayed.
	 */
	public function clean() {
		(($sPlugin = Phpfox_Plugin::get('advancedphoto.component_block_yntimelinelistphoto_clean')) ? eval($sPlugin) : false);
	}

}

?>