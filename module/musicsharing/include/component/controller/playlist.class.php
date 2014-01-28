<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class musicsharing_Component_Controller_Playlist extends Phpfox_Component {

    /**
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
        Phpfox::getLib('setting')->setParam('musicsharing.url_image', Phpfox::getParam('core.url_pic') . 'musicsharing' . PHPFOX_DS);
        
        $settings = phpFox::getService('musicsharing.music')->getUserSettings(phpFox::getUserId(), false);
        if (isset($settings['is_public_permission']) && $settings['is_public_permission'] == 0)
        {
            phpFox::isUser(true);
        }
        $aParentModule = $this->getParam('aParentModule');
        if ($aParentModule)
        {
            $aParentModule['msf']['home'] = phpFox::getLib("url")->makeUrl('pages.' . $aParentModule['item_id'] . '.musicsharing.');
            $aParentModule['msf']['song'] = phpFox::getLib("url")->makeUrl('pages.' . $aParentModule['item_id'] . '.musicsharing.song.');
            $aParentModule['msf']['album'] = phpFox::getLib("url")->makeUrl('pages.' . $aParentModule['item_id'] . '.musicsharing.album.');
            $aParentModule['msf']['artist'] = phpFox::getLib("url")->makeUrl('pages.' . $aParentModule['item_id'] . '.musicsharing.artist.');
            $aParentModule['msf']['playlist'] = phpFox::getLib("url")->makeUrl('pages.' . $aParentModule['item_id'] . '.musicsharing.playlist');
            $this->template()->assign(array(
                'aParentModule' => $aParentModule,
            ));
        }
        else
        {
            phpFox::getLib('session')->remove('pages_msf');
        }
        $settings = phpFox::getService('musicsharing.music')->getUserSettings(phpFox::getUserId());
        $this->template()->assign(array('settings' => $settings));
        $prefix = phpFox::getParam(array('db', 'prefix'));

        //Search
        $filter = "Playlists";
        $aSorts = array('playlist_id' => phpFox::getPhrase('musicsharing.most_recent'), 'play_count' => phpFox::getPhrase('musicsharing.most_played'));
        $aFilters = array(
            'keyword' => array(
                'type' => 'input:text',
                'search' => "[VALUE]"
            ),
        );
        $bIsProfile = false;
        $settings = phpFox::getService('musicsharing.music')->getUserSettings(phpFox::getUserId(), false);

        $iPageSize = $settings['number_playlist_per_page'];
        $value = $iPageSize;
        $show = array($value, $value * 2, $value * 3);

        $bIsProfile = false;
        $value = $iPageSize;
        $show = array($value, $value * 2, $value * 3);

        $oSearch = phpFox::getLib('search')->set(array(
            'type' => 'musicsharing',
            'filters' => $aFilters,
            'cache' => true,
            'field' => 'm2bmusic_playlist.playlist_id',
            'search' => 'search',
            'search_tool' => array(
                'table_alias' => 'm2bmusic_playlist',
                'search' => array(
                    'action' => ($bIsProfile === true ? $this->url()->makeUrl($aUser['user_name'], array('musicsharing/playlist', 'view' => $this->request()->get('musicsharing'))) : $this->url()->makeUrl('musicsharing/playlist', array('view' => $this->request()->get('view')))),
                    'default_value' => 'Search Playlists...',
                    'name' => 'keyword',
                    'field' => 'm2bmusic_playlist.playlist_id'
                ),
                'sort' => array(
                    '1' => array($prefix . 'm2bmusic_playlist.play_count', phpFox::getPhrase('musicsharing.most_recent')),
                    '2' => array($prefix . 'm2bmusic_playlist.play_count', phpFox::getPhrase('musicsharing.most_played')),
                ),
                'show' => $show
        )));


        $arrSearch = $oSearch->getConditions();

        if ($this->request()->get('when'))
        {

            if (count($arrSearch) == 1)
            {
                if (substr_count($arrSearch[0], ".time_stamp"))
                    unset($arrSearch[0]);
            }
            else if (count($arrSearch) == 2)
            {
                if (substr_count($arrSearch[1], ".time_stamp"))
                    unset($arrSearch[1]);
            }
        }

        if ($this->request()->get('sort'))
        {
            if (count($arrSearch) > 0)
                $arrSearch[1] = $this->request()->get('sort');
        }
        else
        {
            if (count($arrSearch) > 0)
                $arrSearch[1] = 1;
        }
        if ($this->request()->get('show'))
        {
            $iPageSize = $this->request()->get('show');
        }


        // print_r($arrSearch);die();
        $aFilterMenu = array();
        if (!defined('PHPFOX_IS_USER_PROFILE'))
        {
            $aFilterMenu[phpFox::getPhrase('musicsharing.browse_all')] = 'musicsharing.view_all';
            $aFilterMenu[] = true;

            $aFilterMenu[phpFox::getPhrase('musicsharing.all_songs')] = 'musicsharing.song';
            $aFilterMenu[phpFox::getPhrase('musicsharing.my_songs')] = 'musicsharing.song.view_my';

            if (phpFox::isModule('friend') && !phpFox::getParam('core.friends_only_community'))
            {
                $aFilterMenu[phpFox::getPhrase('musicsharing.friends_songs')] = 'friend';
            }

            $aFilterMenu[] = true;

            $aFilterMenu[phpFox::getPhrase('musicsharing.all_albums')] = 'musicsharing.album';
            $aFilterMenu[phpFox::getPhrase('musicsharing.my_albums')] = 'musicsharing.myalbums';

            $aFilterMenu[] = true;

            $aFilterMenu[phpFox::getPhrase('musicsharing.all_playlists')] = 'musicsharing.playlist';
            $aFilterMenu[phpFox::getPhrase('musicsharing.my_playlists')] = 'musicsharing.myplaylists';

            $aFilterMenu[] = true;
            $aFilterMenu[phpFox::getPhrase('musicsharing.uploaders')] = 'musicsharing.artist';
        }
        $this->template()->buildSectionMenu('musicsharing/playlist', $aFilterMenu);

        $sort = "";
        $where = " ";
        $search = 0;
        if ($this->request()->get('search-rid'))
        {
            if (count($arrSearch) == 2)
            {
                $strWhere = $arrSearch[0];
                $sort = isset($arrSearch[2]) ? $arrSearch[2] : "";
            }
            else
            {
                $strWhere = "";
                $sort = isset($arrSearch[2]) ? $arrSearch[2] : "";
            }
            $strWhere = @mysql_escape_string($strWhere);
            $where = " WHERE  (" . $prefix . "m2bmusic_playlist.title LIKE '%" . $strWhere . "%' OR " . $prefix . "m2bmusic_playlist.description LIKE '%" . $strWhere . "%')";
            $search = 1;
        }
        else
        {
            $sort = isset($arrSearch[2]) ? $arrSearch[2] : "";
        }

        if ($this->request()->get('when'))
        {
            switch ($this->request()->get('when')) {
                case "this-week":
                    $where .= " AND year(" . $prefix . "m2bmusic_playlist.creation_date)=year(CURRENT_DATE()) and week(" . $prefix . "m2bmusic_playlist.creation_date)=week(CURRENT_DATE()) ";
                    break;
                case "today";
                    $where .= " AND datediff(" . $prefix . "m2bmusic_playlist.creation_date,CURRENT_DATE())=0 ";
                    break;
                case "this-month";
                    $where .= " AND month(" . $prefix . "m2bmusic_playlist.creation_date)=month(CURRENT_DATE()) and year(" . $prefix . "m2bmusic_playlist.creation_date)=year(CURRENT_DATE()) ";
                    break;
                default:
                    break;
            }
        }
        //end search     
        $oSearch = phpFox::getLib('search')->set(array(
            'type' => 'musicsharing',
            'filters' => $aFilters,
            'cache' => true,
            'field' => 'm2bmusic_playlist.playlist_id',
            'search' => 'search',
            'search_tool' => array(
                'table_alias' => 'm2bmusic_playlist',
                'search' => array(
                    'action' => ($aParentModule ? $this->url()->makeUrl('pages.' . $aParentModule["item_id"] . '.musicsharing/playlist', array('view' => $this->request()->get('view'))) : $this->url()->makeUrl('musicsharing/playlist', array('view' => $this->request()->get('view')))),
                    'default_value' => (isset($arrSearch[0]) ? $arrSearch[0] : "Search Playlists..."),
                    'name' => 'keyword',
                    'field' => 'm2bmusic_playlist.playlist_id'
                ),
                'sort' => array(
                    '1' => array($prefix . 'm2bmusic_playlist.play_count', phpFox::getPhrase('musicsharing.most_recent')),
                    '2' => array($prefix . 'm2bmusic_playlist.play_count', phpFox::getPhrase('musicsharing.most_played')),
                ),
                'show' => $show
        )));
        if ($search == 0)
        {
            $list_total = phpFox::getService('musicsharing.music')->get_total_playlist($where . " where search = 1");
        }
        else
        {
            $list_total = phpFox::getService('musicsharing.music')->get_total_playlist($where . " AND search = 1");
        }

        $iPage = $this->request()->get("page");
        if (!$iPage)
            $iPage = 1;
        $max_page = floor($list_total / $iPageSize) + 1;
        if ($iPage > $max_page)
            $iPage = $max_page;
        if ($search == 0)
            $list_info = phpFox::getService('musicsharing.music')->getPlaylists(($iPage - 1) * $iPageSize, $iPageSize, $sort, null, $where . " where search = 1");
        else
            $list_info = phpFox::getService('musicsharing.music')->getPlaylists(($iPage - 1) * $iPageSize, $iPageSize, $sort, null, $where . " and search = 1");
        
        phpFox::getLib('pager')->set(array('page' => $iPage, 'size' => $iPageSize, 'count' => $list_total));
        $this->setParam('onmusicsharingpage', 1);
        $urlControl = $this->url()->makeUrl('musicsharing.playlist', array());
        if (defined('PHPFOX_IS_PAGES_VIEW'))
        {
            $urlControl = phpFox::getLib("url")->makeUrl('pages.' . $aParentModule['item_id'] . '.musicsharing.playlist.');
        }
        $urlControl = $urlControl . "search-id_" . $this->request()->get("search-id");
        $this->template()->assign(array('iPage' => $iPage, 'aRows' => $list_info, 'iCnt' => $list_total))
                ->setHeader('cache', array(
                    'pager.css' => 'style_css'));
        $this->template()->assign(array(
            'list_info' => $list_info,
            'core_path' => phpFox::getParam('core.path'),
            'user_id' => phpFox::getUserId(),
            'filter' => $filter,
            'actionForm' => $urlControl,
            'sDeleteBlock' => 'dashboard',
            'aParentModule' => $aParentModule,
            'sTextSearch' => (isset($arrSearch[0]) ? $arrSearch[0] : NULL),
            'sort_title' => "Sort By :"
        ));
        $this->template()->setHeader(array(
            'm2bmusic_tabcontent.js' => 'module_musicsharing',
            'm2bmusic_class.js' => 'module_musicsharing',
            'music.css' => 'module_musicsharing',
            'musicsharing_style.css' => 'module_musicsharing',
            'mobile.css' => 'module_musicsharing'
        ));

        //modified section (v 300b1)
        //build filter menu
        phpFox::getService('musicsharing.music')->getSectionMenu($aParentModule);
        $catitle = $this->template()->getBreadCrumb();

        if (!$aParentModule)
        {
            $satitle = isset($catitle[1][0]) ? $catitle[1][0] : $catitle[0][0];
            $this->template()->clearBreadCrumb();
            $this->template()
                    ->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'), $this->url()->makeUrl('musicsharing'))
                    ->setBreadCrumb($satitle, null)
                    ->setBreadCrumb($satitle, null, true);
        }
        else
        {
            $this->template()->clearBreadCrumb();
            $this->template()
                    ->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'), $this->url()->makeUrl('musicsharing'), false);
        }
        ///modified section (v 300b1)
    }

    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('musicsharing.component_controller_playlist_clean')) ? eval($sPlugin) : false);
    }

}

?>