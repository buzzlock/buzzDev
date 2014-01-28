<?php

defined('PHPFOX') or exit('NO DICE!');

class MobileTemplate_Service_MobileTemplate extends Phpfox_Service
{
	public function __construct()
	{

	}

  public function getAllThemes() {
    return $this->database()->select('t.*')
        ->from(Phpfox::getT('theme'), 't')
        //->where('t.is_active = 1')
        ->where('1 = 1')
        ->execute('getRows');
  }

  public function getAllStyles() {
    return $this->database()->select('s.*')
        ->from(Phpfox::getT('theme_style'), 's')
        //->where('s.is_active = 1')
        ->where('1 = 1')
        ->execute('getRows');
  }
  
  public function getMTActiveThemeStyle(){
    return $this->database()->select('s.*')
        ->from(Phpfox::getT('mobiletemplate_active_theme_style'), 's')
        ->execute('getRow');
  }

    /**
     * Get default mobile theme
     * @return mixed
     */
    public function getActiveMobileStyle()
    {
    	$mtActiveStyle = $this->getMTActiveThemeStyle();
		if(isset($mtActiveStyle) && isset($mtActiveStyle['style_id'])){
			$aActiveStyle = $this->getStyle($mtActiveStyle['style_id']);
		} else {
			return NULL;
		}
		
        return $aActiveStyle;
    }

    /**
     * Get style by id
     * @param $iId
     * @return mixed
     */
    public function getStyle($iId)
    {
        $aTheme = $this->database()->select('s.style_id, s.theme_id, s.parent_id AS style_parent_id, s.folder AS style_folder_name, t.folder AS theme_folder_name, t.parent_id AS theme_parent_id, t.total_column, s.l_width, s.c_width, s.r_width')
            ->from(Phpfox::getT('theme_style'), 's')
            ->join(Phpfox::getT('theme'), 't', 't.theme_id = s.theme_id AND t.is_active')
            ->where('s.style_id = ' . $iId)
            ->execute('getRow');

        return $aTheme;
    }
	
	public function getParentStyleFolderByParentID($aStyle){
        return $this->database()->select('folder AS parent_style_folder')
            ->from(Phpfox::getT('theme_style'))
            ->where('style_id = ' . $aStyle['style_parent_id'])
            ->execute('getRow');		
	}
	
	/**
	 * Example: key is 'homelistviewitem_id_background' which get phrase by 'phrase_homelistviewitem_id_background'
	 */
    function getDefaultMobileCustomStyle()
    {
        return array(
			'mtcss_background_main' => array('type' => 'color', 'value' => '#fff'),
			'mtcss_header_background' => array('type' => 'color', 'value' => '#7A7A7A'),
			'mtcss_background_button_right' => array('type' => 'color', 'value' => '#535353'),
			'mtcss_menu_left_background' => array('type' => 'color', 'value' => '#404040'),
			'mtcss_menu_text_s_color' => array('type' => 'color', 'value' => '#fff'),
			'mtcss_username_s_color' => array('type' => 'color', 'value' => '#fff'),
			'mtcss_content_s_background' => array('type' => 'color', 'value' => '#fff'),
			'mtcss_title_s_color' => array('type' => 'color', 'value' => '#000'),
			'mtcss_feed_s_background' => array('type' => 'color', 'value' => '#ccc'),
			'mtcss_item_background' => array('type' => 'color', 'value' => '#fff'),
			'mtcss_text_s_color' => array('type' => 'color', 'value' => '#000'),
			'mtcss_background_link' => array('type' => 'color', 'value' => '#fafafa'),
			'mtcss_text_link_s_color' => array('type' => 'color', 'value' => '#5e5e5e'),
			'mtcss_login_page_background' => array('type' => 'color', 'value' => '#4F4F4F'),
			'mtcss_login_button_color' => array('type' => 'color', 'value' => '#6977c5'),
			'mtcss_login_button_text_color' => array('type' => 'color', 'value' => '#fff'),
			'mtcss_signup_button_color' => array('type' => 'color', 'value' => '#ccc'),
        );
    }
	
  public function getAllMobileCustomStyles() {
    return $this->database()->select('t.*')
        ->from(Phpfox::getT('mobiletemplate_mobile_custom_style'), 't')
		->order('time_stamp DESC')
        ->execute('getRows');
  }
  
    public function getMobileCustomStyleForEdit($iStyleId)
    {
        return $this->database()
                ->select('*')
                ->from(Phpfox::getT('mobiletemplate_mobile_custom_style'))
                ->where('style_id = ' . (int) $iStyleId)
                ->execute('getRow');
        // $aStyles = array();
        // if ($aRow)
        // {
			// $aStyles = unserialize(base64_decode($aRow['data']));
        // }
		
        // return $aStyles;
    }
	
	public function loadActiveMobileCustomStyle(){
	    $activeStyle = $this->database()->select('t.*')
	        ->from(Phpfox::getT('mobiletemplate_mobile_custom_style'), 't')
	        ->where('t.is_active = 1')
	        ->execute('getRow');

		if(isset($activeStyle) && isset($activeStyle['name'])){
			$aDefaultStyles = $this->getDefaultMobileCustomStyle();
			$aStyles = unserialize(base64_decode($activeStyle['data']));
			foreach ($aDefaultStyles as $key => $value){
				if(isset($aStyles[$key])){
					$aDefaultStyles[$key]['value'] = $aStyles[$key];
				}
			}
			
			$org = $this->getStylesPatternFile();
			$reg = array();
	        foreach ($aDefaultStyles as $key => $value)
	        {
	            $key = '{' . $key . '}';
	            $reg[$key] = $value['value'];
	        }
			
			return strtr($org, $reg);
		} else {
			return '/* no custom style */';
		}
	}
  
    public function getStylesPatternFile()
    {
        $file = PHPFOX_DIR_MODULE . '/mobiletemplate/static/css/default/default/custom.css';
		if (file_exists($file)) {
			return file_get_contents($file);
		} else {
			return '';
		}
    }
	
	public function getUserInfoByUserID($userID = null, $aUser = null){
		//	int 
		if($userID === null){
			$userID = Phpfox::getUserId();
		}
		
		//	process
		//	get basic information
		if (!Phpfox::getService('user.privacy')->hasAccess($aUser['user_id'], 'profile.basic_info'))
		{
			return array();
		}
		if($aUser === null){
			$aUser = Phpfox::getService('user')->get($userID, true);
			if (empty($aUser['dob_setting']))
			{
				switch (Phpfox::getParam('user.default_privacy_brithdate'))
				{
					case 'month_day':
						$aUser['dob_setting'] =  '1';
						break;
					case 'show_age':
						$aUser['dob_setting'] =  '2';
						break;	
					case 'hide':
						$aUser['dob_setting'] =  '3';
						break;					
				}				
			}		
			$aUser['gender_name'] = Phpfox::getService('user')->gender($aUser['gender']);
			$aUser['birthday_time_stamp'] = $aUser['birthday'];	
			$aUser['birthday'] = Phpfox::getService('user')->age($aUser['birthday']);
			$aUser['location'] = Phpfox::getPhraseT(Phpfox::getService('core.country')->getCountry($aUser['country_iso']), 'country');
			if (isset($aUser['country_child_id']) && $aUser['country_child_id'] > 0)
			{
				$aUser['location_child'] = Phpfox::getService('core.country')->getChild($aUser['country_child_id']);
			}	
			$aUser['birthdate_display'] = Phpfox::getService('user')->getProfileBirthDate($aUser);
			$aUser['is_user_birthday'] = ((empty($aUser['birthday_time_stamp']) ? false : (int) floor(Phpfox::getLib('date')->daysToDate($aUser['birthday_time_stamp'], null, false)) === 0 ? true : false));
			if (empty($aUser['landing_page']))
			{
				$aUser['landing_page'] = Phpfox::getParam('profile.profile_default_landing_page');
			}
		}
		
		$aUser['bRelationshipHeader'] = true;
		$sRelationship = Phpfox::getService('custom')->getRelationshipPhrase($aUser);
		$aUserDetails = array();
		if (!empty($aUser['gender']))
		{
			$aUserDetails['gender'] = $aUser['gender_name'];
		}
		$aUserDetails['birth_date'] = $aUser['birthdate_display']['Birth Date'];
		
		$sExtraLocation = '';
		if (!empty($aUser['city_location']))
		{
			$sExtraLocation .= Phpfox::getLib('parse.output')->clean($aUser['city_location']) . ', ';
		}		
		
		if ($aUser['country_child_id'] > 0)
		{
			$sExtraLocation .= Phpfox::getService('core.country')->getChild($aUser['country_child_id']) . ' ';
		}		
		
		if (!empty($aUser['country_iso']))
		{
			$aUserDetails['location'] = $sExtraLocation . Phpfox::getPhraseT($aUser['location'], 'country');
		}
		if ((int) $aUser['last_login'] > 0 && ((!$aUser['is_invisible']) || (Phpfox::getUserParam('user.can_view_if_a_user_is_invisible') && $aUser['is_invisible'])))
		{
			$aUserDetails['last_login'] = Phpfox::getLib('date')->convertTime($aUser['last_login'], 'core.profile_time_stamps');
		}
		
		if ((int) $aUser['joined'] > 0)
		{
			$aUserDetails['member_since'] = Phpfox::getLib('date')->convertTime($aUser['joined'], 'core.profile_time_stamps');
		}
		
		if (Phpfox::getUserGroupParam($aUser['user_group_id'], 'profile.display_membership_info'))
		{
			$aUserDetails['membership'] = (empty($aUser['icon_ext']) ? '' : '<img src="' . Phpfox::getParam('core.url_icon') . $aUser['icon_ext'] . '" class="v_middle" alt="' . Phpfox::getLib('locale')->convert($aUser['title']) . '" title="' . Phpfox::getLib('locale')->convert($aUser['title']) . '" /> ') . $aUser['prefix'] . Phpfox::getLib('locale')->convert($aUser['title']) . $aUser['suffix'];
		}
		
		$aUserDetails['profile_views'] = $aUser['total_view'];
		
		if (Phpfox::isModule('rss') && Phpfox::getParam('rss.display_rss_count_on_profile') && Phpfox::getService('user.privacy')->hasAccess($aUser['user_id'], 'rss.display_on_profile'))
		{
			$aUserDetails['rss_subscribers'] = $aUser['rss_count'];
		}		
		 
		//	get custom information
		//	end 
		return array('sRelationship' => $sRelationship, 'aUserDetails' => $aUserDetails);
	}

	public function getLatestPublicPhotosByUserID($viewedUserID = null, $limit = 1){
		if($viewedUserID === null){
			return array();
		}
		
        return $this->database()->select('pa.name AS album_name, pa.profile_id AS album_profile_id
				, photo.*,  u.user_id, u.profile_page_id
				, u.server_id AS user_server_id, u.user_name, u.full_name, u.gender, u.user_image, u.is_invisible
				, u.user_group_id, u.language_id')
            ->from(Phpfox::getT('photo'), 'photo')
			->leftJoin(Phpfox::getT('photo_album'), 'pa', 'pa.album_id = photo.album_id')
			->join(Phpfox::getT('user'), 'u', 'u.user_id = photo.user_id')
            ->where('photo.view_id = 0 AND photo.group_id = 0 AND photo.type_id = 0 AND photo.privacy IN(0) AND photo.user_id = ' . (int)$viewedUserID)
			->group('photo.photo_id')
			->order('photo.photo_id DESC')
			->limit( $limit)
			->execute('getSlaveRows');
	}
	
	public function getPagesByLocation($fLat, $fLng, $name = null)
	{
		 
		// if($name != null && !empty($name)){
			// $where = ' 1=1 ';
			// $where .= ' AND title LIKE \'%' . $this->database()->escape($name) . '%\' ';
// 			
			// $aPages = $this->database()->select('page_id, title, image_server_id, image_path, location_latitude, location_longitude')
			// ->from(Phpfox::getT('pages'))
			// ->where($where) 
			// ->group('page_id')
			// ->limit(10)
			// ->execute('getSlaveRows');
		// } else {
			// $aPages = $this->database()->select('page_id, title, image_server_id, image_path, location_latitude, location_longitude, (3956 * 2 * ASIN(SQRT( POWER(SIN((' . $fLat . ' - location_latitude) *  pi()/180 / 2), 2) + COS(' . $fLat . ' * pi()/180) * COS(location_latitude * pi()/180) * POWER(SIN((' . $fLng . ' - location_longitude) * pi()/180 / 2), 2) ))) as distance')
			// ->from(Phpfox::getT('pages'))
			// ->having('distance < 1') // distance in kilometers
			// ->limit(10)
			// ->execute('getSlaveRows');
		// }
		
			$where = ' 1=1 ';
			if($name != null && !empty($name)){
				$where .= ' AND title LIKE \'%' . $this->database()->escape($name) . '%\' ';
			}
			
			$aPages = $this->database()->select('page_id, title, image_server_id, image_path, location_latitude, location_longitude, (3956 * 2 * ASIN(SQRT( POWER(SIN((' . $fLat . ' - location_latitude) *  pi()/180 / 2), 2) + COS(' . $fLat . ' * pi()/180) * COS(location_latitude * pi()/180) * POWER(SIN((' . $fLng . ' - location_longitude) * pi()/180 / 2), 2) ))) as distance')
			->from(Phpfox::getT('pages'))
			->where($where) 
			->group('page_id')
			->having('distance < 1') // distance in kilometers
			->limit(10)
			->execute('getSlaveRows');
		
		return $aPages;
	}

	public function getListOfShare($aMTLFeed, $type = 'feed'){
		$mtlType = $type;
		$mtlDisplay = 'menu';
		if($type == 'feed'){
			$mtlUrl = $aMTLFeed['feed_link'];
			$mtlTitle = $aMTLFeed['feed_title'];
			
			if($aMTLFeed['privacy'] == '0'){
				$mtlShareFeedID = $aMTLFeed['item_id'];
				$mtlShareModule = $aMTLFeed['type_id'];
				
				$mtlfeed_id = $mtlShareFeedID;
			} else {			
				$mtlShareFeedID = '';
				$mtlShareModule = '';
				$mtlfeed_id = '';
			}
		} else if ($type == 'pages'){
			$mtlUrl = $aMTLFeed['link'];
			$mtlTitle = $aMTLFeed['title'];
			
			$mtlShareFeedID = $aMTLFeed['page_id'];
			$mtlShareModule = 'pages';
			$mtlfeed_id = $mtlShareFeedID;
		}
		
		//	init base on: share.include.block.frame
		$mtlsBookmarkType = $mtlType;
		$mtlsBookmarkUrl = $mtlUrl;
		$mtlsBookmarkTitle = $mtlTitle;
		static $mtlaBookmarks = array();
		if (empty($mtlaBookmarks))
		{
			$mtlaBookmarks = Phpfox::getService('share')->getType();
		}
		if (!is_array($mtlaBookmarks))
		{
			$mtlaBookmarks = array();
		}		
		$mtlbShowSocialBookmarks = count($mtlaBookmarks) > 0;
		$mtliFeedId = ((Phpfox::hasCallback($mtlShareModule, 'canShareItemOnFeed')) ? $mtlfeed_id : 0);
		$mtlsShareModule = $mtlShareModule;
		
		//	check kind of share, base on share.template.block.frame
		$listOfShare = array();
		if (Phpfox::isUser() && $mtliFeedId > 0) 
		{
			$listOfShare['post'] = array('link' => '#', 'title' => Phpfox::getPhrase('share.post'));
		}
		if(Phpfox::getParam('share.enable_social_bookmarking') && $mtlbShowSocialBookmarks)
		{
			$listOfShare['bookmarks'] = array('link' => '#', 'title' => Phpfox::getPhrase('share.social_bookmarks'));
		}
		
		$info = array(
			'sBookmarkType' => $mtlType
			, 'sBookmarkUrl' => $mtlUrl
			, 'sBookmarkTitle' => $mtlTitle
			, 'sShareModule' => $mtlShareModule
			, 'feed_id' => $mtlfeed_id
		);
		
		return array('listOfShare' => $listOfShare, 'infoShare' => $info);
	}

	public function getAllMenuNavigation()
	{
	    return $this->database()->select('t.*')
	        ->from(Phpfox::getT('mobiletemplate_menu_navigation'), 't')
			->order('ordering ASC')
	        ->execute('getRows');		
	}

}
?>