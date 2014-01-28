<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');
?>
<?php

class musicsharing_Component_Controller_Index extends Phpfox_Component {

    public function process()
    {
        $this->template()->setPhrase(array(
            'musicsharing.you_do_not_have_permission_to_download_songs',
            'musicsharing.there_are_no_new_results_to_view_at_this_time',
            'musicsharing.add_song_to_playlist'));

        $settings = phpFox::getService('musicsharing.music')->getUserSettings(phpFox::getUserId(), false);
        if (isset($settings['is_public_permission']) && $settings['is_public_permission'] == 0)
        {
            phpFox::isUser(true);
        }

        if (defined('PHPFOX_IS_AJAX_CONTROLLER'))
        {
            $bIsProfile = true;
            $aUser = phpFox::getService('user')->get($this->request()->get('profile_id'));
            $this->setParam('aUser', $aUser);
        }
        else
        {
            $bIsProfile = $this->getParam('bIsProfile');
            if ($bIsProfile === true)
            {
                $aUser = $this->getParam('aUser');
            }
        }
        
        // Display in profile.
        if ($bIsProfile)
        {
            $this->setParam('bIsProfile', true);
            return Phpfox::getLib('module')->setController('musicsharing.profile');
        }
        
        $aParentModule = $this->getParam('aParentModule');
        if ($aParentModule)
        {
            $aParentModule['msf']['home'] = $this->url()->makeUrl('pages.' . $aParentModule['item_id'] . '.musicsharing.');
            $aParentModule['msf']['song'] = $this->url()->makeUrl('pages.' . $aParentModule['item_id'] . '.musicsharing.song.');
            $aParentModule['msf']['album'] = $this->url()->makeUrl('pages.' . $aParentModule['item_id'] . '.musicsharing.album.');
            $aParentModule['msf']['artist'] = $this->url()->makeUrl('pages.' . $aParentModule['item_id'] . '.musicsharing.artist.');
            $aParentModule['msf']['playlist'] = $this->url()->makeUrl('pages.' . $aParentModule['item_id'] . '.musicsharing.playlist');
            $this->template()->assign(array(
                'aParentModule' => $aParentModule
            ));
            
            phpFox::getLib('session')->set('pages_msf', $aParentModule);
        }
        else
        {
            phpFox::getLib('session')->remove('pages_msf');
        }
        $this->setParam('onmusicsharingpage', 1);
        $req4 = $this->request()->get('req4');
        $controller = array('song', 'album', 'playlist', 'artist');
        $controller2 = array('createalbum', 'createplaylist', 'editalbum', 'editplaylist', 'myalbums', 'myplaylists', 'listen', 'albumsongs', 'upload', 'playlistsongs');
        if ($req4 != "" && (in_array($req4, $controller) || in_array($req4, $controller2)))
        {
            if (in_array($req4, $controller2))
            {
                $this->setParam('onmusicsharingpage', 0);
                phpFox::getComponent('musicsharing.' . $req4, array('bNoTemplate' => true), 'controller');
            }
            else
            {
                phpFox::getComponent('musicsharing.' . $req4, array('bNoTemplate' => true), 'controller');
            }
            return true;
        }
        $this->template()->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'), null);
        $_SESSION['downloadlist_downloadlist'] = phpFox::getUserId();
        $this->template()->setHeader(
                array(
                    'm2bmusic_tabcontent.js' => 'module_musicsharing',
                    'm2bmusic_class.js' => 'module_musicsharing',
                    'music.css' => 'module_musicsharing',
                    'tooltipalbum.js' => 'module_musicsharing',
                    'jquery.easing.js' => 'module_musicsharing',
                    'script.js' => 'module_musicsharing',
                    'musicsharing_style.css' => 'module_musicsharing',
                    'crfixhome.css' => 'module_musicsharing',
					
					'switch_legend.js' => 'static_script',
                    'switch_menu.js' => 'static_script',
					'suppress_menu.css' => 'module_musicsharing',
					
					'mobile.css' => 'module_musicsharing',
                // 'init_index.js' => 'module_musicsharing',
                )
        );

        $aTypes = array(
            '1' => phpFox::getPhrase('musicsharing.song_name'),
            '2' => phpFox::getPhrase('musicsharing.album_name')
        );
        $aFilters = array(
            'keyword' => array(
                'type' => 'input:text',
                'search' => "[VALUE]"
            ),
        );


        $typeSort = "musicsharing/song";
        $ssort = "";
        if ($this->request()->get('sort'))
        {
            $ssort = $this->request()->get('sort');
            if ($this->request()->get('sort') == 2)
                $typeSort = "musicsharing/album";
        }

        $prefix = phpFox::getParam(array('db', 'prefix'));
        $bIsProfile = false;
        $oSearch = phpFox::getService('musicsharing.search')->set(
                array(
                    'type' => 'musicsharing',
                    'filters' => $aFilters,
                    'cache' => true,
                    'field' => 'm2bmusic_album_song.album_id',
                    'search' => 'search',
                    'search_tool' => array(
                        "table_alias" => "m2bmusic_album_song",
                        'search' => array(
                            'action' => ($aParentModule ? $this->url()->makeUrl('pages.' . $aParentModule["item_id"] . '.musicsharing/song', array('view' => $this->request()->get('view'))) : $this->url()->makeUrl('musicsharing/song', array('view' => $this->request()->get('view')))),
                            'default_value' => (($ssort == "" || $ssort == 1) ? phpFox::getPhrase('musicsharing.search_song') : phpFox::getPhrase('musicsharing.search_album')),
                            'name' => 'keyword',
                            'field' => 'm2bmusic_album_song.album_id'
                        ),
                        'sort' => array(
                            '1' => array($prefix . 'm2bmusic_album_song.play_count', phpFox::getPhrase('musicsharing.song_name')),
                            '2' => array($prefix . 'm2bmusic_album_song.play_count', phpFox::getPhrase('musicsharing.album_name')),
                        ),
                        'show' => array(10)
                    )
                )
        );

        //modified section (v 300b1)
        //build filter menu
        Phpfox::getService('musicsharing.music')->getSectionMenu($aParentModule);
        ///modified section (v 300b1)
        
        $arrSearch = $oSearch->getConditions();

        if ($this->request()->get('search-id'))
        {
            if (count($arrSearch) > 2)
            {
                if ($arrSearch[2])
                {
                    $strWhere = $arrSearch[0];

                    $type = $arrSearch[1];
                    $_SESSION['search_k'] = $strWhere;
                    $strWhere = html_entity_decode($strWhere, ENT_QUOTES, 'utf-8');

                    $strWhere = base64_encode(mysql_escape_string($strWhere));

                    $strWhere = phpFox::getService('musicsharing.music')->convertURL(htmlspecialchars_decode($strWhere, ENT_QUOTES));

                    switch ($type) {
                        case "type_1":
                            $this->url()->send('musicsharing.song.where_' . $strWhere, null);
                            break;
                        case "type_2":
                            $this->url()->send('musicsharing.album.where_' . $strWhere, null);
                            break;
                    }
                }
            }
        }
        $this->template()->assign(array(
            'sDeleteBlock' => 'dashboard',
            'core_path' => phpFox::getParam('core.path'),
            'user_id' => phpFox::getUserId(),
            'type_title' => phpFox::getPhrase('musicsharing.type') . ':',
            'currency' => phpFox::getService('core.currency')->getDefault(),
        ));


        //modified section (v 300b1)
        //build filter menu
        if (!$aParentModule)
        {
            $this->template()->clearBreadCrumb();
            $this->template()
                    ->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'), $this->url()->makeUrl('musicsharing'), false);
        }
        else
        {
            $this->template()->clearBreadCrumb();
            $this->template()
                    ->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'), $this->url()->makeUrl('musicsharing'), false);
        }
        ///modified section (v 300b1)
    }

    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('musicsharing.component_controller_index_clean')) ? eval($sPlugin) : false);
    }

}

?>
