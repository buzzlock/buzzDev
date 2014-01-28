<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class musicsharing_Component_Controller_Artist extends Phpfox_Component {

    /**
     * @see Musicsharing_Service_Music
     * Class process method wnich is used to execute this component.
     */
    public function process()
    {
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

        $this->template()->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'), null);

        $bIsProfile = false;
        $settings = phpFox::getService('musicsharing.music')->getUserSettings(phpFox::getUserId(), false);
        $iPageSize = $settings['number_artist_per_page'];

        $this->search()->setCondition('user.user_id IN (SELECT DISTINCT filter_album.user_id 
FROM ' . Phpfox::getT('m2bmusic_album') . ' AS filter_album
WHERE (
	filter_album.user_id = ' . Phpfox::getUserId() . ' OR filter_album.privacy IN (0)  
	OR (
		filter_album.privacy = 4 
		AND ' . Phpfox::getUserId() . ' IN (
			SELECT ' . Phpfox::getT('friend_list_data') . '.friend_user_id 
			FROM  ' . Phpfox::getT('friend_list') . '
			INNER JOIN ' . Phpfox::getT('privacy') . ' ON ' . Phpfox::getT('privacy') . '.friend_list_id = ' . Phpfox::getT('friend_list') . '.list_id 
			INNER JOIN ' . Phpfox::getT('user') . ' ON ' . Phpfox::getT('user') . '.user_id = ' . Phpfox::getT('privacy') . '.user_id 
			INNER JOIN ' . Phpfox::getT('friend_list_data') . ' ON ' . Phpfox::getT('friend_list') . '.list_id = ' . Phpfox::getT('friend_list_data') . '.list_id 
			WHERE ' . Phpfox::getT('privacy') . '.module_id = "musicsharing_album" AND ' . Phpfox::getT('privacy') . '.item_id = filter_album.album_id
		)
	)  
	OR (
		filter_album.privacy = 3 
		AND filter_album.user_id = ' . Phpfox::getUserId() . '
	)  
	OR (
		filter_album.privacy IN (1) 
		AND filter_album.user_id IN (
			SELECT fr.user_id 
			FROM ' . Phpfox::getT('friend') . ' as fr 
			WHERE fr.friend_user_id = ' . Phpfox::getUserId() . '
		)
	) 
	OR (
		filter_album.privacy IN (2) 
		AND (
			filter_album.user_id IN (
				SELECT f.user_id 
				FROM ' . Phpfox::getT('friend') . ' AS f 
				INNER JOIN (
					SELECT ffxf.friend_user_id 
					FROM ' . Phpfox::getT('friend') . ' AS ffxf 
					WHERE ffxf.is_page = 0 
					AND ffxf.user_id = ' . Phpfox::getUserId() . '
				) AS sf ON sf.friend_user_id = f.friend_user_id 
				JOIN ' . Phpfox::getT('user') . ' AS u ON u.user_id = f.friend_user_id
			) 
			OR filter_album.user_id IN (
				SELECT fr.user_id 
				FROM ' . Phpfox::getT('friend') . ' AS fr 
				WHERE fr.friend_user_id = ' . Phpfox::getUserId() . '
			)
		)
	)
) ' . ($aParentModule ? 'AND (filter_album.module_id = "' . $aParentModule['module_id'] . '" AND filter_album.item_id = ' . $aParentModule['item_id'] . ')' : 'AND (filter_album.module_id IS NULL OR filter_album.module_id = "")') . ')');
        
        if ($this->request()->get('when'))
        {
            switch ($this->request()->get('when')) {
                case "this-week":
                    $this->search()->setCondition("AND year(FROM_UNIXTIME(user.joined))=year(CURRENT_DATE()) and week(FROM_UNIXTIME(user.joined))=week(CURRENT_DATE())");
                    break;
                case "today";
                    $this->search()->setCondition("AND datediff(FROM_UNIXTIME(user.joined),CURRENT_DATE())=0");
                    break;
                case "this-month";
                    $this->search()->setCondition("AND month(FROM_UNIXTIME(user.joined))=month(CURRENT_DATE()) and year(FROM_UNIXTIME(user.joined))=year(CURRENT_DATE())");
                    break;
                default:
                    break;
            }
        }

        $aView = array();
        if ($sView = $this->request()->get('view'))
        {
            $aView = array('view' => $sView);
        }
        $sAction = ($aParentModule ? $this->url()->makeUrl('pages.' . $aParentModule["item_id"] . '.musicsharing/artist', $aView) : $this->url()->makeUrl('musicsharing/artist', $aView));
        $aFilters = array(
            'keyword' => array(
                'type' => 'input:text',
                'search' => "[VALUE]"
            ),
        );
        $this->search()->set(
            array(
                'type' => 'musicsharing_artist',
                'filters' => $aFilters,
                'field' => 'user.user_id',
                'search_tool' => array(
                    'table_alias' => 'user',
                    'search' => array(
                        'action' => $sAction,
                        'default_value' => phpFox::getPhrase('musicsharing.search_artists'),
                        'name' => 'search',
                        'field' => 'user.full_name'
                    ),
                    'sort' => array(
                        'all' => array('user.user_id', phpFox::getPhrase('musicsharing.all')),
                        'name' => array('user.full_name', phpFox::getPhrase('musicsharing.name')),
                        'albums' => array('total_album', phpFox::getPhrase('musicsharing.most_albums')),
                    ),
                    'show' => array($iPageSize * 1, $iPageSize * 2, $iPageSize * 3),
                    'when_field' => 'joined'
                )
            )
        );
        
        $aBrowseParams = array(
            'module_id' => 'musicsharing.artist',
            'alias' => 'user',
            'field' => 'user_id',
            'table' => Phpfox::getT('user'),
            'hide_view' => array()
        );

        $this->search()->browse()->params($aBrowseParams)->execute();
        
        phpFox::getLib('pager')->set(
            array(
                'page' => $this->search()->getPage(),
                'size' => $this->search()->getDisplay(),
                'count' => $this->search()->browse()->getCount()
            )
        );
        
        //end search
        $list_total = $this->search()->browse()->getCount();
        
        $iPageSize = $this->request()->get('show', $iPageSize);
        $iPage = $this->request()->getInt('page', 1);

        $max_page = floor($list_total / $iPageSize) + 1;
        if ($iPage > $max_page)
        {
            $iPage = $max_page;
        }

        $list_info = $this->search()->browse()->getRows();

        foreach ($list_info as $iKey => $aItem)
        {
            $list_info[$iKey]['total_song'] = Phpfox::getService('musicsharing.music')->get_total_song(Phpfox::getT('user') . '.user_id = ' . (int) $aItem['user_id']);
        }
        $this->template()
                ->assign(array('iPage' => $iPage, 'aRows' => $list_info, 'iCnt' => $list_total))
                ->setHeader('cache', array('pager.css' => 'style_css'));

        $this->template()->assign(
                array(
                    'sDeleteBlock' => 'dashboard',
                    'list_info' => $list_info
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
        )->clearBreadCrumb();

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
            $this->template()->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'), $this->url()->makeUrl('musicsharing'), false);
        }
    }

    /**
     * Garbage collector. Is executed after this class has completed
     * its job and the template has also been displayed.
     */
    public function clean()
    {
        (($sPlugin = Phpfox_Plugin::get('musicsharing.component_controller_artist_clean')) ? eval($sPlugin) : false);
    }

}

?>
