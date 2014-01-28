<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class musicsharing_Component_Controller_Song extends Phpfox_Component {

    /**
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
        // Profile case.
        $bIsProfile = $this->getParam('bIsProfile');	
        if ($bIsProfile === true)
        {
            $aUser = $this->getParam('aUser');
        }
        
        $this->template()->setPhrase(array('musicsharing.you_do_not_have_permission_to_download_songs', 'musicsharing.are_you_sure'));
        $settings = phpFox::getService('musicsharing.music')->getUserSettings(phpFox::getUserId(), false);
        if (isset($settings['is_public_permission']) && $settings['is_public_permission'] == 0)
        {
            phpFox::isUser(true);
        }

        $aParentModule = $this->getParam('aParentModule');
        if ($aParentModule)
        {
            $aParentModule['msf']['home'] = $this->url()->makeUrl('pages.' . $aParentModule['item_id'] . '.musicsharing.');
            $aParentModule['msf']['song'] = $this->url()->makeUrl('pages.' . $aParentModule['item_id'] . '.musicsharing.song.');
            $aParentModule['msf']['album'] = $this->url()->makeUrl('pages.' . $aParentModule['item_id'] . '.musicsharing.album.');
            $aParentModule['msf']['artist'] = $this->url()->makeUrl('pages.' . $aParentModule['item_id'] . '.musicsharing.artist.');
            $aParentModule['msf']['playlist'] = $this->url()->makeUrl('pages.' . $aParentModule['item_id'] . '.musicsharing.playlist');
            $this->template()->assign(array('aParentModule' => $aParentModule));
        }
        else
        {
            phpFox::getLib('session')->remove('pages_msf');
        }
        $this->setParam('onmusicsharingpage', 1);
        $this->template()->assign(array('aParentModule' => $aParentModule));
        $this->template()->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'), null);
        $settings = phpFox::getService('musicsharing.music')->getUserSettings(phpFox::getUserId());
        $prefix = phpFox::getParam(array('db', 'prefix'));
        $this->template()->assign(array('settings' => $settings));
        $where = " search = 1";
        $filter = "Songs";
        if ($this->request()->get('album'))
        {
            $album_id = $this->request()->get('album');
            $where = "  " . $prefix . "m2bmusic_album_song.album_id = $album_id";
            $filter = "Album >> " . $this->request()->get('title');
        }
        if ($this->request()->get('cat') != "")
        {
            $cat_id = $this->request()->get('cat');
            $cat_id = $cat_id == "others" ? "0" : $cat_id;
            $where = "  " . $prefix . "m2bmusic_album_song.cat_id = $cat_id";
            $filter = "Category >> " . $this->request()->get('catitle');
        }
        if ($this->request()->get('singer') != "")
        {
            $singer_id = $this->request()->get('singer');
            $singer_id = $singer_id == "others" ? "0" : $singer_id;
            $where = "  " . $prefix . "m2bmusic_album_song.singer_id = $singer_id";
            $filter = "Singer >> " . $this->request()->get('sititle');
        }
        if ($this->request()->getInt('user') != 0)
        {
            $where .= " AND " . $prefix . "user.user_id = " . $this->request()->getInt('user');
            $filter = 'Name &raquo; <a href="' . $this->url()->makeUrl($this->request()->get('name')) . '">' . $this->request()->get('name') . '</a>';
        }
        if ($this->request()->get('where'))
        {
            $str = $this->request()->get('where');

            $where = "  " . $prefix . "m2bmusic_album_song.title_url LIKE '%" . $str . "%'";
        }
        if ($this->request()->get('wheresinger'))
        {
            $str = $this->request()->get('wheresinger');
            $str = base64_decode($str);
            $str = phpFox::getService('musicsharing.music')->convertURL(htmlspecialchars_decode($str, ENT_QUOTES));
            $where = "  " . $prefix . "m2bmusic_album_song.other_singer_title_url LIKE '" . $str . "'";
        }

        $aFilters = array(
            'keyword' => array(
                'type' => 'input:text',
                'search' => "[VALUE]"
            ),
        );
        $settings = phpFox::getService('musicsharing.music')->getUserSettings(phpFox::getUserId(), false);

        $iPageSize = $settings['number_song_per_page'];

        $oSearch = phpFox::getLib('search')->set(
                array(
                    'type' => 'musicsharing',
                    'filters' => $aFilters,
                    'cache' => true,
                    'field' => 'm2bmusic_album.album_id',
                    'search' => 'search',
                    'search_tool' => array(
                        'table_alias' => 'm2bmusic_album',
                        'search' => array(
                            'action' => ($aParentModule ? $this->url()->makeUrl('pages.' . $aParentModule["item_id"] . '.musicsharing/song', array('view' => $this->request()->get('view'))) : $this->url()->makeUrl(($bIsProfile ? $aUser['user_name'] . '.musicsharing.profile.song' : 'musicsharing/song'), array('view' => $this->request()->get('view')))),
                            'default_value' => phpFox::getPhrase('musicsharing.search_songs'),
                            'name' => 'keyword',
                            'field' => 'm2bmusic_album.album_id'
                        ),
                        'sort' => array(
                            'name' => array($prefix . 'm2bmusic_album_song.play_count', phpFox::getPhrase('musicsharing.name')),
                            'played' => array($prefix . 'm2bmusic_album_song.play_count', phpFox::getPhrase('musicsharing.most_played')),
                        ),
                        'show' => array($iPageSize * 1, $iPageSize * 2, $iPageSize * 3)
                    )
                )
        );
        $sort = "";

        $arrSearch = $oSearch->getConditions();

        if ($this->request()->get('when'))
        {
            if (count($arrSearch) == 1)
            {
                if (substr_count($arrSearch[0], ".time_stamp"))
                    unset($arrSearch[0]);
            }
            else
            {
                if (count($arrSearch) != 0 && substr_count($arrSearch[1], ".time_stamp"))
                    unset($arrSearch[1]);
            }
        }

        if ($this->request()->get('when'))
        {
            switch ($this->request()->get('when')) {
                case "this-week":
                    $where .= " AND year(" . $prefix . "m2bmusic_album.creation_date)=year(CURRENT_DATE()) and week(" . $prefix . "m2bmusic_album.creation_date)=week(CURRENT_DATE()) ";
                    break;
                case "today";
                    $where .= " AND datediff(" . $prefix . "m2bmusic_album.creation_date,CURRENT_DATE())=0 ";
                    break;
                case "this-month";
                    $where .= " AND month(" . $prefix . "m2bmusic_album.creation_date)=month(CURRENT_DATE()) and year(" . $prefix . "m2bmusic_album.creation_date)=year(CURRENT_DATE()) ";
                    break;
                default:
                    break;
            }
        }

        $strWhere = "";

        if (isset($arrSearch[0]) && is_string($arrSearch[0]))
        {
            $strWhere = $arrSearch[0];
            $where .= " AND " . $prefix . "m2bmusic_album_song.title LIKE '%" . $strWhere . "%' ";
        }

        $sort = "";

        if (($type = $this->request()->get('sort')))
        {
            switch ($type) {
                case "name":
                    $sort = $prefix . "m2bmusic_album_song.title";
                    break;
                case "played":
                    $sort = $prefix . "m2bmusic_album_song.play_count DESC";
                    break;
            }
        }

        if ($bIsProfile)
        {
            if ($aUser['user_id'] == Phpfox::getUserId())
            {
                $this->request()->set('view', 'my');
            }
            else
            {
                $where .= (" AND " . Phpfox::getT('m2bmusic_album') . ".privacy IN(" . (Phpfox::getParam('core.section_privacy_item_browsing') ? '%PRIVACY%' : Phpfox::getService('core')->getForBrowse($aUser)) . ") AND " . Phpfox::getT('m2bmusic_album') . ".user_id = " . $aUser['user_id'] . "");
            }
        }
        
        switch ($view = $this->request()->get('view')) {
            case 'my':
                phpFox::isUser(true);
                $where.=" AND " . Phpfox::getT('m2bmusic_album') . ".user_id = " . phpFox::getUserId();
                
                $where = str_replace('%PRIVACY%', '0,1,2,3,4', $where);
                break;
            case 'friend':
                phpFox::isUser(true);
                $where .=" AND ( 0 < (SELECT COUNT(*) FROM " . $prefix . "friend AS friends WHERE friends.user_id = " . $prefix . "m2bmusic_album.user_id AND friends.friend_user_id = " . phpFox::getUserId() . ")) ";
                
                $where = str_replace('%PRIVACY%', '0,1,2', $where);
                break;

            default:
                $where = str_replace('%PRIVACY%', '0', $where);
                break;
        }
        
        //end search
        //jeep
        $oSearch = phpFox::getLib('search')->set(
                array(
                    'type' => 'musicsharing',
                    'filters' => $aFilters,
                    'cache' => true,
                    'field' => 'm2bmusic_album.album_id',
                    'search' => 'search',
                    'search_tool' => array(
                        'table_alias' => 'm2bmusic_album',
                        'search' => array(
                            'action' => ($aParentModule ? $this->url()->makeUrl('pages.' . $aParentModule["item_id"] . '.musicsharing.song', array('view' => $this->request()->get('view'))) : $this->url()->makeUrl(($bIsProfile ? $aUser['user_name'] . '.musicsharing.profile.song' : 'musicsharing/song'), array('view' => $this->request()->get('view')))),
                            'default_value' => (isset($arrSearch[0]) ? $arrSearch[0] : phpFox::getPhrase('musicsharing.search_songs')),
                            'name' => 'keyword',
                            'field' => 'm2bmusic_album.album_id'
                        ),
                        'sort' => array(
                            'name' => array($prefix . 'm2bmusic_album_song.play_count', phpFox::getPhrase('musicsharing.name')),
                            'played' => array($prefix . 'm2bmusic_album_song.play_count', phpFox::getPhrase('musicsharing.most_played')),
                        ),
                        'show' => array($iPageSize * 1, $iPageSize * 2, $iPageSize * 3)
                    )
                )
        );
        $list_total = phpFox::getService('musicsharing.music')->get_total_song($where . " AND search = 1");

        $_settings = phpFox::getService('musicsharing.music')->getSettings(0);
        $iPageSize = $_settings['number_song_per_page'];
        if ($this->request()->get('show'))
        {
            $iPageSize = $this->request()->get('show');
        }
        $iPage = $this->request()->get("page");
        if (!$iPage)
            $iPage = 1;
        $max_page = floor($list_total / $iPageSize) + 1;
        if ($iPage > $max_page)
            $iPage = $max_page;
        $list_info = phpFox::getService('musicsharing.music')->getSongs(($iPage - 1) * $iPageSize, $iPageSize, $sort, null, $where . " AND search = 1");

        phpFox::getLib('pager')->set(array('page' => $iPage, 'size' => $iPageSize, 'count' => $list_total));

        $this->template()
                ->assign(array('iPage' => $iPage, 'aRows' => $list_info, 'iCnt' => $list_total))
                ->setHeader('cache', array('pager.css' => 'style_css'));

        $urlControl = $this->url()->makeUrl('musicsharing.song', array());
        
        if (defined('PHPFOX_IS_PAGES_VIEW'))
        {
            $urlControl = phpFox::getLib("url")->makeUrl('pages.' . $aParentModule['item_id'] . '.musicsharing.song.');
        }
        // Check profile.
        if ($bIsProfile)
        {
            $urlControl = $this->url()->makeUrl($aUser['user_name'] . '.musicsharing.profile');
        }
        $urlControl = $urlControl . "search-id_" . $this->request()->get("search-id");

        $csinger = false;
        if ($this->request()->get('singer') != "" && $this->request()->get('singer') != "others")
        {
            $csinger = phpFox::getService('musicsharing.music')->getSingerInfo($this->request()->get('singer'));
        }
        else if ($this->request()->get('singer') == "others")
        {
            $csinger = 'musicsharing.this_is_all_song_by_other_singer';
        }
        $ccat = false;
        if ($this->request()->get('cat') != "")
        {
            $ccat = phpFox::getService('musicsharing.music')->getCategoryById($this->request()->get('cat'));

            if (isset($ccat['title']))
            {
                $ccat['title'] = Phpfox::getLib("locale")->convert($ccat['title']);
            }
        }
        $aArtist = false;
        if ($this->request()->getInt('user') > 0)
        {
            $aArtist = phpFox::getService('musicsharing.music')->getUserById($this->request()->getInt('user'));
        }
        $this->template()->assign(
                array(
                    'cartist' => $aArtist,
                    'sDeleteBlock' => 'dashboard',
                    'list_info' => $list_info,
                    'core_path' => phpFox::getParam('core.path'),
                    'user_id' => phpFox::getUserId(),
                    'filter' => $filter,
                    'actionForm' => $urlControl,
                    'type_title' => phpFox::getPhrase('musicsharing.type') . " :",
                    'sort_title' => phpFox::getPhrase('musicsharing.sort_by') . " :",
                    'csinger' => $csinger,
                    'ccat' => $ccat,
                    'sTextSearch' => (isset($arrSearch[0]) ? $arrSearch[0] : NULL),
                    'inMySong' => ($view == "my" ? "true" : ""),
                )
        );
        $this->template()->setHeader(
                array(
                    'm2bmusic_tabcontent.js' => 'module_musicsharing',
                    'm2bmusic_class.js' => 'module_musicsharing',
                    'music.css' => 'module_musicsharing',
                    'musicsharing_style.css' => 'module_musicsharing',
                    'mobile.css' => 'module_musicsharing'
                )
        );

        //modified section (v 300b1)
        //build filter menu
        phpFox::getService('musicsharing.music')->getSectionMenu($aParentModule);
        $aBreadCrumbs = $this->template()->getBreadCrumb();
        if (!$aParentModule)
        {
            if ($bIsProfile)
            {
                
            }
            else
            {
                $sTitle = '';
                if (isset($aBreadCrumbs[1]))
                {
                    if (isset($aBreadCrumbs[1][0]))
                    {
                        $sTitle = $aBreadCrumbs[1][0];
                    }
                    else
                    {
                        foreach ($aBreadCrumbs[1] as $sBreadCrumb)
                        {
                            $sTitle = $sBreadCrumb;
                        }
                    }
                }
                $this->template()->clearBreadCrumb();
                $this->template()
                        ->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'), $this->url()->makeUrl('musicsharing'))
                        ->setBreadCrumb($sTitle, null)
                        ->setBreadCrumb($sTitle, null, true);
            }
        }
        else
        {
            $this->template()->clearBreadCrumb();
            $this->template()->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'), $this->url()->makeUrl('musicsharing'), false);
        }
        ///modified section (v 300b1)
    }

    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('musicsharing.component_controller_song_clean')) ? eval($sPlugin) : false);
    }

}

?>