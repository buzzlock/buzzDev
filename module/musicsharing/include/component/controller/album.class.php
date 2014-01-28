<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class musicsharing_Component_Controller_Album extends Phpfox_Component {

    /**
     * @see Phpfox_Image_Helper
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
        $oMusic = phpFox::getService('musicsharing.music');
        $settings = $oMusic->getUserSettings(phpFox::getUserId(), false);
        if (isset($settings['is_public_permission']) && $settings['is_public_permission'] == 0)
        {
            phpFox::isUser(true);
        }
        $aParentModule = $this->getParam('aParentModule');

        $this->template()->setHeader(array(
            'musicsharing_style.css' => 'module_musicsharing',
            'music.css' => 'module_musicsharing',
        ));
        //@jh: settings ref
        $_settings = $oMusic->getUserSettings(phpFox::getUserId(), true);

        //can_view_album
        if ($_settings["can_view_album"] == "0")
        {
            $this->template()->assign(array('suppress' => "suppress"));
            return false;
        }

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
        $where = " search = 1 ";
        $filter = "Albums";
        $prefix = phpFox::getParam(array('db', 'prefix'));
        if ($this->request()->get('user'))
        {
            $user_id = $this->request()->get('user');
            $where = " " . $prefix . "m2bmusic_album.user_id = $user_id";
            $user = '<a href="' . $this->url()->makeUrl($this->request()->get('name')) . '">' . $this->request()->get('name') . '</a>';
            $filter = "Artist &raquo;" . $user;
        }
        if ($this->request()->get('where'))
        {
            $str = $this->request()->get('where');
            $where .= " AND " . $prefix . "m2bmusic_album.title_url LIKE '%" . $str . "%'";
        }
        //Search
        $aSorts = array('1' => phpFox::getPhrase('musicsharing.most_recent'), '2' => phpFox::getPhrase('musicsharing.most_played'));
        $aFilters = array(
            'title' => array(
                'type' => 'input:text',
                'search' => "%[VALUE]%"
            ),
            'sort' => array(
                'type' => 'select',
                'options' => $aSorts,
                'default' => '2'
            ),
            'keyword' => array(
                'type' => 'input:text',
                'search' => "[VALUE]"
            ),
        );

        $bIsProfile = false;
        $settings = $oMusic->getUserSettings(phpFox::getUserId(), false);

        $iPageSize = $settings['number_album_per_page'];
        $value = $iPageSize;
        $show = array($value, $value * 2, $value * 3);

        $oSearch = phpFox::getLib('search')->set(array(
            'type' => 'musicsharing',
            'filters' => $aFilters,
            'cache' => true,
            'field' => 'm2bmusic_album.album_id',
            'search' => 'search',
            'search_tool' => array(
                'table_alias' => 'm2bmusic_album',
                'search' => array(
                    'action' => ($aParentModule ? $this->url()->makeUrl('pages.' . $aParentModule["item_id"] . '.musicsharing/album', array('view' => $this->request()->get('view'))) : $this->url()->makeUrl('musicsharing/album', array('view' => $this->request()->get('view')))),
                    'default_value' => phpFox::getPhrase('musicsharing.search_albums'), 'name' => 'keyword',
                    'field' => 'm2bmusic_album.album_id'
                ),
                'sort' => array(
                    '1' => array($prefix . 'm2bmusic_album.play_count', phpFox::getPhrase('musicsharing.most_recent')),
                    '2' => array($prefix . 'm2bmusic_album.play_count', phpFox::getPhrase('musicsharing.most_played')),
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
            else
            {
                if (count($arrSearch) != 0 && substr_count($arrSearch[1], ".time_stamp"))
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
        
        $strWhere = "";
        if (isset($arrSearch[1]))
        {
            $strWhere = $arrSearch[0];
            $strWhere = "title LIKE '%" . $strWhere . "%'";
        }

        $type = isset($arrSearch[1]) ? $arrSearch[1] : "";
        if ($strWhere != "")
        {
            $where .= " AND " . $prefix . "m2bmusic_album." . $strWhere;
        }
        if ($this->request()->get('when'))
        {
            switch ($this->request()->get('when')) {
                case "this-week":
                    $where .= " AND year(" . $prefix . "m2bmusic_album.modified_date)=year(CURRENT_DATE()) and week(" . $prefix . "m2bmusic_album.modified_date)=week(CURRENT_DATE()) and " . $where;
                    break;
                case "today";
                    $where .= " AND datediff(" . $prefix . "m2bmusic_album.modified_date,CURRENT_DATE())=0 and" . $where;
                    break;
                case "this-month";
                    $where .= " AND month(" . $prefix . "m2bmusic_album.modified_date)=month(CURRENT_DATE()) and year(" . $prefix . "m2bmusic_album.modified_date)=year(CURRENT_DATE()) and " . $where;
                    break;
                default:
                    break;
            }
            $where .= "";
        }
        $sort = "";
        switch ($type) {
            case 1:
                $sort = "";
                break;
            case 2:
                $sort = " " . $prefix . "m2bmusic_album.play_count DESC";
                break;
        }

        if (($view = $this->request()->get('view')))
        {
            switch ($view) {
                case "my":
                    $where .= " AND " . $prefix . "m2bmusic_album.user_id = " . phpFox::getUserId();
                    break;
            }
        }

        //end search
        $list_total = $oMusic->get_total_album($where);
        $iPage = $this->request()->get("page");
        if (!$iPage)
            $iPage = 1;
        $max_page = floor($list_total / $iPageSize) + 1;
        if ($iPage > $max_page)
            $iPage = $max_page;

        $list_info = $oMusic->getAlbums(($iPage - 1) * $iPageSize, $iPageSize, null, null, $where);

        $limit = 10;
        if (count($list_info) > 0)
        {
            foreach ($list_info as $iKey => $album_info)
            {
                $list_song_info = $oMusic->getSongsAlbumId($album_info['album_id'], $limit);
                $list_info[$iKey]['list_song'] = $list_song_info;

                $search = array("\n", "\r", "&#039;");
                $replace = array("<br/>", "", "\&#039;");

                $list_info[$iKey]['title_replace'] = str_replace($search, $replace, $album_info['title']);
            }
        }
        else
        {
            $list_info = null;
        }
        $oSearch = phpFox::getLib('search')->set(array(
            'type' => 'musicsharing',
            'filters' => $aFilters,
            'cache' => true,
            'field' => 'm2bmusic_album.album_id',
            'search' => 'search',
            'search_tool' => array(
                'table_alias' => 'm2bmusic_album',
                'search' => array(
                    'action' => ($aParentModule ? $this->url()->makeUrl('pages.' . $aParentModule["item_id"] . '.musicsharing/album', array('view' => $this->request()->get('view'))) : $this->url()->makeUrl('musicsharing/album', array('view' => $this->request()->get('view')))),
                    'default_value' => (isset($arrSearch[0]) ? $arrSearch[0] : phpFox::getPhrase('musicsharing.search_albums')),
                    'name' => 'keyword',
                    'field' => 'm2bmusic_album.album_id'
                ),
                'sort' => array(
                    '1' => array($prefix . 'm2bmusic_album.play_count', phpFox::getPhrase('musicsharing.most_recent')),
                    '2' => array($prefix . 'm2bmusic_album.play_count', phpFox::getPhrase('musicsharing.most_played')),
                ),
                'show' => $show
        )));
        phpFox::getLib('pager')->set(array('page' => $iPage, 'size' => $iPageSize, 'count' => $list_total));

        $this->template()
                ->assign(array('iPage' => $iPage, 'aRows' => $list_info, 'iCnt' => $list_total))
                ->setHeader('cache', array(
                    'pager.css' => 'style_css'));
        $this->setParam('onmusicsharingpage', 1);
        $urlControl = $this->url()->makeUrl('musicsharing.album', array());
        if (defined('PHPFOX_IS_PAGES_VIEW'))
        {
            $urlControl = $this->url()->makeUrl('pages.' . $aParentModule['item_id'] . '.musicsharing.album.');
        }
        $urlControl = $urlControl . "search-id_" . $this->request()->get("search-id");
        $cartist = false;
        if ($this->request()->get('user') != "")
        {
            $cartist = $oMusic->getUserById($this->request()->get('user'));
        }
        $this->template()->assign(array(
            'sDeleteBlock' => 'dashboard',
            'cartist' => $cartist,
            'list_info' => $list_info,
            'core_path' => phpFox::getParam('core.path'),
            'user_id' => phpFox::getUserId(),
            'filter' => $filter,
            'description' => phpFox::getPhrase('musicsharing.description'),
            'name' => phpFox::getPhrase('musicsharing.name_upper'),
            'listofsongs' => phpFox::getPhrase('musicsharing.list_of_songs'),
            'actionForm' => $urlControl,
            'sTextSearch' => (isset($arrSearch[0]) ? $arrSearch[0] : NULL),
            'sort_title' => phpFox::getPhrase('musicsharing.sort_by') . " :"
        ));
        $this->template()->setHeader(array(
            'm2bmusic_tabcontent.js' => 'module_musicsharing',
            'm2bmusic_class.js' => 'module_musicsharing',
            'music.css' => 'module_musicsharing',
            'musicsharing_style.css' => 'module_musicsharing',
            'tooltipalbum.js' => 'module_musicsharing',
            'mobile.css' => 'module_musicsharing'
        ));

        //modified section (v 300b1)
        //build filter menu
        $oMusic->getSectionMenu($aParentModule);
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
        (($sPlugin = Phpfox_Plugin::get('musicsharing.component_controller_album_clean')) ? eval($sPlugin) : false);
    }

}

?>
