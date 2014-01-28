<?php

require_once("m2b_music_lib/AlbumController.php");
require_once("m2b_music_lib/PlaylistController.php");
require_once("m2b_music_lib/AlbumSongController.php");
require_once("m2b_music_lib/PlaylistSongController.php");
require_once("m2b_music_lib/CategoryController.php");
require_once("m2b_music_lib/SingerController.php");
require_once("m2b_music_lib/SingerTypeController.php");
require_once("m2b_music_lib/SettingsController.php");


/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

// include 'logging.php';
class Musicsharing_Service_Callback extends Phpfox_Service {

    public function globalUnionSearch($sSearch)
    {
        $this->database()
                ->select('item.song_id AS item_id, item.title AS item_title, UNIX_TIMESTAMP(music_sharing_album.creation_date) AS item_time_stamp, music_sharing_album.user_id AS item_user_id, \'musicsharing\' AS item_type_id, \'\' AS item_photo, \'\' AS item_photo_server')
                ->from(Phpfox::getT('m2bmusic_album_song'), 'item')
                ->leftJoin(Phpfox::getT('m2bmusic_album'), 'music_sharing_album', 'music_sharing_album.album_id = item.album_id')
                ->where('music_sharing_album.privacy = 0 AND ' . $this->database()->searchKeywords('item.title', $sSearch))
                ->union();
    }

    public function getSearchInfo($aRow)
	{
		return array(
            'item_link' => Phpfox::getLib('url')->makeUrl('musicsharing.listen', array('music' => $aRow['item_id'], $aRow['item_title'])),
            'item_name' => Phpfox::getPhrase('musicsharing.menu_musicsharing_musicsharing_532c28d5412dd75bf975fb951c740a30')
        );
	}
    
    public function getSearchTitleInfo()
	{
		return array(
			'name' => Phpfox::getPhrase('musicsharing.menu_musicsharing_musicsharing_532c28d5412dd75bf975fb951c740a30')
		);
	}

    public function canShareItemOnFeedSong()
    {
        
    }

    public function canShareItemOnFeedAlbum()
    {
        
    }

    public function canShareItemOnFeedPlaylist()
    {
        
    }

    public function canShareItemOnFeedPagesSong()
    {
        
    }

    public function canShareItemOnFeedPagesAlbum()
    {
        
    }

    public function canShareItemOnFeedPagesPlaylist()
    {
        
    }

    public function getTotalItemCountSong($iUserId)
	{
		return array(
			'field' => 'total_musicsharing_song',
			'total' => $this->database()
                ->select('COUNT(song.song_id)')
                ->from(Phpfox::getT('m2bmusic_album_song'), 'song')
                ->leftJoin(Phpfox::getT('m2bmusic_album'), 'album', 'album.album_id = song.album_id')
                ->where('album.user_id = ' . (int) $iUserId . ' AND album.item_id = 0')
                ->execute('getSlaveField')
		);	
	}
    public function getAjaxProfileController()
	{
		return 'musicsharing.index';
	}
    public function getProfileMenu($aUser)
    {
        if ($aUser['user_id'] == Phpfox::getUserId())
        {
            $aUser['total_musicsharing_song'] = $this->database()
                ->select('COUNT(song.song_id)')
                ->from(Phpfox::getT('m2bmusic_album_song'), 'song')
                ->leftJoin(Phpfox::getT('m2bmusic_album'), 'album', 'album.album_id = song.album_id')
                ->where('album.user_id = ' . (int) $aUser['user_id'] . ' AND album.item_id = 0')
                ->execute('getSlaveField');
        }
        else
        {
            switch ($view = Phpfox::getLib('request')->get('view'))
            {
                case 'my':
                    $sPrivacy = '0,1,2,3,4';
                    break;
                
                case 'friend':
                    $sPrivacy = '0,1,2';
                    break;

                default:
                    $sPrivacy = '0';
                    break;
            }
            
            $aUser['total_musicsharing_song'] = $this->database()
                ->select('COUNT(song.song_id)')
                ->from(Phpfox::getT('m2bmusic_album_song'), 'song')
                ->leftJoin(Phpfox::getT('m2bmusic_album'), 'album', 'album.album_id = song.album_id')
                ->where('album.privacy IN(' . (Phpfox::getParam('core.section_privacy_item_browsing') ? $sPrivacy : Phpfox::getService('core')->getForBrowse($aUser)) . ') AND album.user_id = ' . (int) $aUser['user_id'] . ' AND album.item_id = 0')
                ->execute('getSlaveField');
        }
        
        if (!Phpfox::getParam('profile.show_empty_tabs'))
		{		
			if (!isset($aUser['total_musicsharing_song']))
			{
				return false;
			}

			if (isset($aUser['total_musicsharing_song']) && (int) $aUser['total_musicsharing_song'] === 0)
			{
				return false;
			}	
		}

        $aMenus[] = array(
            'phrase' => Phpfox::getPhrase('musicsharing.menu_musicsharing_musicsharing_532c28d5412dd75bf975fb951c740a30'),
            'url' => 'profile.musicsharing',
            'total' => (int) (isset($aUser['total_musicsharing_song']) ? $aUser['total_musicsharing_song'] : 0),
            'sub_menu' => array(),
            'icon' => 'feed/musicsharing.png'
        );

        return $aMenus;
    }

    public function getProfileLink()
    {
        return 'profile.musicsharing';
    }

    //report
    public function getFeedRedirectAlbum($iId)
    {
        $aRow = $this->database()->select('m.album_id, m.title_url, u.user_name')
                ->from(phpFox::getT('m2bmusic_album'), 'm')
                ->join(phpFox::getT('user'), 'u', 'u.user_id = m.user_id')
                ->where('m.album_id = ' . (int) $iId)
                ->execute('getSlaveRow');

        if (!isset($aRow['album_id']))
        {
            return false;
        }

        return phpFox::getLib('url')->makeUrl('musicsharing.listen', array('album' => $aRow['album_id']));
    }

    public function getFeedRedirectPlaylist($iId)
    {
        $aRow = $this->database()->select('m.playlist_id, m.title_url, u.user_name')
                ->from(phpFox::getT('m2bmusic_playlist'), 'm')
                ->join(phpFox::getT('user'), 'u', 'u.user_id = m.user_id')
                ->where('m.playlist_id = ' . (int) $iId)
                ->execute('getSlaveRow');

        if (!isset($aRow['playlist_id']))
        {
            return false;
        }

        return phpFox::getLib('url')->makeUrl('musicsharing.listen', array('playlist' => $aRow['playlist_id']));
    }

    public function getReportRedirectAlbum($iId)
    {
        return $this->getFeedRedirectAlbum($iId);
    }

    public function getReportRedirectPlaylist($iId)
    {
        return $this->getFeedRedirectPlaylist($iId);
    }

    //end
    //notification
    public function getNotificationAlbum_Like($aNotification)
    {
        $album_info = Phpfox::getService('musicsharing.music')->getAlbumInfo((int) $aNotification['item_id']);

        $aRow = $this->database()->select('ms.album_id, ms.title as name, ms.user_id, u.gender, u.full_name')
                ->from(phpFox::getT('m2bmusic_album'), 'ms')
                ->join(phpFox::getT('user'), 'u', 'u.user_id = ms.user_id')
                ->where('ms.album_id = ' . (int) $aNotification['item_id'])
                ->execute('getSlaveRow');

        if (!isset($aRow['album_id']))
        {
            return false;
        }
        $sPhrase = '';
        if ($aNotification['user_id'] == $aRow['user_id'])
        {
            $sPhrase = phpFox::getService('notification')->getUsers($aNotification) . ' ' . Phpfox::getPhrase('musicsharing.liked') . ' ' . phpFox::getService('user')->gender($aRow['gender'], 1) . ' own album "' . phpFox::getLib('parse.output')->shorten($album_info['title'], phpFox::getParam('notification.total_notification_title_length'), '...') . '"';
        }
        elseif ($aRow['user_id'] == phpFox::getUserId())
        {
            $sPhrase = phpFox::getService('notification')->getUsers($aNotification) . ' ' . Phpfox::getPhrase('musicsharing.liked_your_album') . ' "' . phpFox::getLib('parse.output')->shorten($album_info['title'], phpFox::getParam('notification.total_notification_title_length'), '...') . '"';
        }
        else
        {
            $sPhrase = phpFox::getService('notification')->getUsers($aNotification) . ' ' . Phpfox::getPhrase('musicsharing.liked') . ' <span class="drop_data_user">' . $aRow['full_name'] . '\'s</span> album "' . phpFox::getLib('parse.output')->shorten($album_info['title'], phpFox::getParam('notification.total_notification_title_length'), '...') . '"';
        }

        return array(
            'link' => phpFox::getLib('url')->makeUrl('musicsharing.listen', array('album' => $aRow['album_id'])),
            'message' => $sPhrase,
            'icon' => phpFox::getLib('template')->getStyle('image', 'activity.png', 'blog')
        );
    }

    public function getCommentNotificationAlbum($aNotification)
    {
        $aRow = $this->database()->select('l.album_id, l.title as name, u.user_id, u.gender, u.user_name, u.full_name, l.`item_id`, l.`module_id`')
                ->from(phpFox::getT('m2bmusic_album'), 'l')
                ->join(phpFox::getT('user'), 'u', 'u.user_id = l.user_id')
                ->where('l.album_id = ' . (int) $aNotification['item_id'])
                ->execute('getSlaveRow');
        if (!$aRow)
        {
            return false;
        }
        //@jh: modified
        if (isset($aRow["module_id"]) || $aRow["module_id"] === "")
        {
            $link = phpFox::getLib('url')->permalink($aRow["module_id"] . '.' . $aRow['item_id'] . '.' . "musicsharing.listen.album_" . $aRow['album_id'], $aRow['name']);
        }
        else
        {
            $link = phpFox::getLib('url')->permalink("musicsharing.listen", "album_" . $aRow['album_id'], $aRow['name']);
        }

        $sPhrase = '';
        if ($aNotification['user_id'] == $aRow['user_id'] && !isset($aNotification['extra_users']))
        {
            $sPhrase = phpFox::getService('notification')->getUsers($aNotification) . ' ' . Phpfox::getPhrase('musicsharing.commented_on') . ' ' . phpFox::getService('user')->gender($aRow['gender'], 1) . ' album "' . phpFox::getLib('parse.output')->shorten($aRow['name'], phpFox::getParam('notification.total_notification_title_length'), '...') . '"';
        }
        elseif ($aRow['user_id'] == phpFox::getUserId())
        {
            $sPhrase = phpFox::getService('notification')->getUsers($aNotification) . ' ' . Phpfox::getPhrase('musicsharing.commented_on_your_album') . ' "' . phpFox::getLib('parse.output')->shorten($aRow['name'], phpFox::getParam('notification.total_notification_title_length'), '...') . '"';
        }
        else
        {
            $sPhrase = phpFox::getService('notification')->getUsers($aNotification) . ' ' . Phpfox::getPhrase('musicsharing.commented_on') . ' <span class="drop_data_user">' . $aRow['full_name'] . '\'s</span> album "' . phpFox::getLib('parse.output')->shorten($aRow['name'], phpFox::getParam('notification.total_notification_title_length'), '...') . '"';
        }

        return array(
            'link' => $link, //phpFox::getLib('url')->permalink('musicsharing.listen', "album_" . $aRow['album_id'], $aRow['name']),
            'message' => $sPhrase,
            'icon' => phpFox::getLib('template')->getStyle('image', 'activity.png', 'blog')
        );
    }

    //end
    //like
    public function deleteLikeAlbum($iItemId)
    {
        $this->database()->updateCount('like', 'type_id = \'musicsharing_album\' AND item_id = ' . (int) $iItemId . '', 'total_like', 'm2bmusic_album', 'album_id = ' . (int) $iItemId);
    }

    public function addLikeAlbum($iItemId, $bDoNotSendEmail = false)
    {

        $aRow = $this->database()->select('album_id, title, user_id')
                ->from(phpFox::getT('m2bmusic_album'))
                ->where('album_id = ' . (int) $iItemId)
                ->execute('getSlaveRow');

        if (!isset($aRow['album_id']))
        {
            return false;
        }

        $this->database()->updateCount('like', 'type_id = \'musicsharing_album\' AND item_id = ' . (int) $iItemId . '', 'total_like', 'm2bmusic_album', 'album_id = ' . (int) $iItemId);
        if (!$bDoNotSendEmail)
        {
            /* $sLink = phpFox::getLib('url')->makeUrl('newsfeed.detail.item.'.$item['item_alias']);

              phpFox::getLib('mail')->to($aRow['owner_id'])
              ->subject(phpFox::getUserBy('full_name') . " liked your blog \"" . $aRow['title'] . "\"")
              ->message(phpFox::getUserBy('full_name') . " liked your blog \"<a href=\"" . $sLink . "\">" . $aRow['title'] . "</a>\"\nTo view this blog follow the link below:\n<a href=\"" . $sLink . "\">" . $sLink . "</a>")
              ->send();
             */
            phpFox::getService('notification.process')->add('musicsharing_album_like', $aRow['album_id'], $aRow['user_id']);
        }
    }

    //end
    //comment playlist
    public function getAjaxCommentVarPlaylist()
    {
        return 'musicsharing.can_post_on_musicsharing';
    }

    public function getCommentItemPlaylist($iId)
    {
        $iItem = $this->database()->select('playlist_id AS comment_item_id, m.user_id AS comment_user_id')
                ->from(phpFox::getT('m2bmusic_playlist'), 'm')
                ->where('playlist_id = ' . (int) $iId)
                ->execute('getSlaveRow');
        $iItem['comment_view_id'] = 1;
        return $iItem;
    }

    public function addCommentPlaylist($aVals, $iUserId = null, $sUserName = null)
    {
        $aPlaylist = $this->database()->select('u.full_name, u.user_id, u.user_name, a.title , a.playlist_id, a.title_url')
                ->from(phpFox::getT('m2bmusic_playlist'), 'a')
                ->join(phpFox::getT('user'), 'u', 'u.user_id = a.user_id')
                ->where('a.playlist_id = ' . (int) $aVals['item_id'])
                ->execute('getSlaveRow');
        phpFox::getService('musicsharing.music')->updateCounter(phpFox::getT('m2bmusic_playlist'), 'playlist_id', $aVals['item_id'], 'total_comment');

        if (!$aPlaylist)
        {
            return false;
        }
        //@jh: modified...
        //add subpage url to notification...
        $pages_msf = phpFox::getLib('session')->get('pages_msf');

        if (isset($pages_msf['url']))
        {
            $sLink = $pages_msf['url'] . 'musicsharing/listen/playlist_' . $aVals['item_id'];
        }
        else
        {
            $sLink = phpFox::getLib('url')->makeUrl('musicsharing.listen', array('playlist' => $aVals['item_id']));
        }

        // Send the user an email
        phpFox::getLib('mail')
                ->to($aPlaylist['user_id'])
                ->subject(array('photo.user_name_left_you_a_comment_on_site_title', array('user_name' => $sUserName, 'site_title' => phpFox::getParam('core.site_title'))))
                ->message(array('photo.user_name_left_you_a_comment_on_site_title_message', array(
                        'user_name' => $sUserName,
                        'site_title' => phpFox::getParam('core.site_title'),
                        'link' => $sLink
                    )
                        )
                )
                ->notification('comment.add_new_comment')
                ->send();

        $aActualUser = phpFox::getService('user')->getUser($iUserId);
        phpFox::getService('notification.process')->add('comment_musicsharing_playlist', $aPlaylist['playlist_id'], $aPlaylist['user_id']/* , array(
                  'title' => $aPlaylist['title'],
                  'user_id' => $aActualUser['user_id'],
                  'image' => $aActualUser['user_image'],
                  'server_id' => $aActualUser['server_id']
                  )
                 */);
    }

    public function deleteLikePlaylist($iItemId)
    {
        $this->database()->updateCount('like', 'type_id = \'musicsharing_playlist\' AND item_id = ' . (int) $iItemId . '', 'total_like', 'm2bmusic_playlist', 'playlist_id = ' . (int) $iItemId);
    }

    public function addLikePlaylist($iItemId, $bDoNotSendEmail = false)
    {

        $aRow = $this->database()->select('playlist_id, title, user_id')
                ->from(phpFox::getT('m2bmusic_playlist'), "pl")
                ->where('playlist_id = ' . (int) $iItemId)
                ->execute('getSlaveRow');

        if (!isset($aRow['playlist_id']))
        {
            return false;
        }

        $this->database()->updateCount('like', 'type_id = \'musicsharing_playlist\' AND item_id = ' . (int) $iItemId . '', 'total_like', 'm2bmusic_playlist', 'playlist_id = ' . (int) $iItemId);
        if (!$bDoNotSendEmail)
        {
            /* $sLink = phpFox::getLib('url')->makeUrl('newsfeed.detail.item.'.$item['item_alias']);

              phpFox::getLib('mail')->to($aRow['owner_id'])
              ->subject(phpFox::getUserBy('full_name') . " liked your blog \"" . $aRow['title'] . "\"")
              ->message(phpFox::getUserBy('full_name') . " liked your blog \"<a href=\"" . $sLink . "\">" . $aRow['title'] . "</a>\"\nTo view this blog follow the link below:\n<a href=\"" . $sLink . "\">" . $sLink . "</a>")
              ->send();
             */
            phpFox::getService('notification.process')->add('musicsharing_playlist_like', $aRow['playlist_id'], $aRow['user_id']);
        }
    }

    //end
    //notification playlist
    public function getNotificationPlaylist_Like($aNotification)
    {
        // playlist_info
        $plc = new PlaylistController();
        $playlist_info = $plc->playlist_info((int) $aNotification['item_id']);
        // mysql_query("SET character_set_client=utf8", $connection);
        // mysql_query("SET character_set_connection=utf8", $connection);
        $this->database()->query("SET character_set_client=utf8");
        $this->database()->query("SET character_set_connection=utf8");
        $aRow = $this->database()->select('ms.playlist_id, ms.title as name, ms.user_id, u.gender, u.full_name')
                ->from(phpFox::getT('m2bmusic_playlist'), 'ms')
                ->join(phpFox::getT('user'), 'u', 'u.user_id = ms.user_id')
                ->where('ms.playlist_id = ' . (int) $aNotification['item_id'])
                ->execute('getSlaveRow');
        if (!isset($aRow['playlist_id']))
        {
            return false;
        }
        $sPhrase = '';
        if ($aNotification['user_id'] == $aRow['user_id'])
        {
            $sPhrase = phpFox::getService('notification')->getUsers($aNotification) . ' ' . Phpfox::getPhrase('musicsharing.liked') . ' ' . phpFox::getService('user')->gender($aRow['gender'], 1) . ' own playlist "' . phpFox::getLib('parse.output')->shorten($playlist_info['title'], phpFox::getParam('notification.total_notification_title_length'), '...') . '"';
        }
        elseif ($aRow['user_id'] == phpFox::getUserId())
        {
            $sPhrase = phpFox::getService('notification')->getUsers($aNotification) . ' ' . Phpfox::getPhrase('musicsharing.liked_your_playlist') . ' "' . phpFox::getLib('parse.output')->shorten($playlist_info['title'], phpFox::getParam('notification.total_notification_title_length'), '...') . '"';
        }
        else
        {
            $sPhrase = phpFox::getService('notification')->getUsers($aNotification) . ' ' . Phpfox::getPhrase('musicsharing.liked') . ' <span class="drop_data_user">' . $aRow['full_name'] . '\'s</span> playlist "' . phpFox::getLib('parse.output')->shorten($playlist_info['title'], phpFox::getParam('notification.total_notification_title_length'), '...') . '"';
        }

        return array(
            'link' => phpFox::getLib('url')->makeUrl('musicsharing.listen', array('playlist' => $aRow['playlist_id'])),
            'message' => ($sPhrase),
            'icon' => phpFox::getLib('template')->getStyle('image', 'activity.png', 'blog')
        );
    }

    public function getCommentNotificationPlaylist($aNotification)
    {
        $aRow = $this->database()->select('l.playlist_id, l.title as name, l.`module_id`, l.`item_id`, u.user_id, u.gender, u.user_name, u.full_name')
                ->from(phpFox::getT('m2bmusic_playlist'), 'l')
                ->join(phpFox::getT('user'), 'u', 'u.user_id = l.user_id')
                ->where('l.playlist_id = ' . (int) $aNotification['item_id'])
                ->execute('getSlaveRow');
        if (!$aRow)
        {
            return false;
        }

        $sPhrase = '';
        if ($aNotification['user_id'] == $aRow['user_id'] && !isset($aNotification['extra_users']))
        {
            $sPhrase = phpFox::getService('notification')->getUsers($aNotification) . ' ' . Phpfox::getPhrase('musicsharing.commented_on') . ' ' . phpFox::getService('user')->gender($aRow['gender'], 1) . ' playlist "' . phpFox::getLib('parse.output')->shorten($aRow['name'], phpFox::getParam('notification.total_notification_title_length'), '...') . '"';
        }
        elseif ($aRow['user_id'] == phpFox::getUserId())
        {
            $sPhrase = phpFox::getService('notification')->getUsers($aNotification) . ' ' . Phpfox::getPhrase('musicsharing.commented_on_your_playlist') . ' "' . phpFox::getLib('parse.output')->shorten($aRow['name'], phpFox::getParam('notification.total_notification_title_length'), '...') . '"';
        }
        else
        {
            $sPhrase = phpFox::getService('notification')->getUsers($aNotification) . ' ' . Phpfox::getPhrase('musicsharing.commented_on') . ' <span class="drop_data_user">' . $aRow['full_name'] . '\'s</span> playlist "' . phpFox::getLib('parse.output')->shorten($aRow['name'], phpFox::getParam('notification.total_notification_title_length'), '...') . '"';
        }

        //@jh: modified...
        if (isset($aRow["module_id"]) || $aRow["module_id"] === "")
        {
            $link = phpFox::getLib('url')->permalink($aRow["module_id"] . '.' . $aRow['item_id'] . '.' . "musicsharing.listen.playlist_" . $aRow['playlist_id'], $aRow['name']);
        }
        else
        {
            $link = phpFox::getLib('url')->permalink("musicsharing.listen", "playlist_" . $aRow['playlist_id'], $aRow['name']);
        }
        return array(
            'link' => $link,
            'message' => $sPhrase,
            'icon' => phpFox::getLib('template')->getStyle('image', 'activity.png', 'blog')
        );
    }

    //end
    //**commemnt new phpfox 3
    public function deleteCommentAlbum($iId)
    {
        $this->database()->update(phpFox::getT('m2bmusic_album'), array('total_comment' => array('= total_comment -', 1)), 'album_id = ' . (int) $iId);
    }

    public function deleteCommentPlayList($iId)
    {
        $this->database()->update(phpFox::getT('m2bmusic_playlist'), array('total_comment' => array('= total_comment -', 1)), 'playlist_id = ' . (int) $iId);
    }

    public function getAjaxCommentVarAlbum()
    {
        return 'musicsharing.can_post_on_musicsharing';
    }

    public function getCommentItemAlbum($iId)
    {
        $iItem = $this->database()->select('album_id AS comment_item_id, m.user_id AS comment_user_id')
                ->from(phpFox::getT('m2bmusic_album'), 'm')
                ->where('album_id = ' . (int) $iId)
                ->execute('getSlaveRow');
        $iItem['comment_view_id'] = 1;
        return $iItem;
    }

    public function addCommentAlbum($aVals, $iUserId = null, $sUserName = null)
    {
        $aAlbum = $this->database()->select('u.full_name, u.user_id, u.user_name, a.title , a.album_id, a.title_url AS album_url')
                ->from(phpFox::getT('m2bmusic_album'), 'a')
                ->join(phpFox::getT('user'), 'u', 'u.user_id = a.user_id')
                ->where('a.album_id = ' . (int) $aVals['item_id'])
                ->execute('getSlaveRow');
        phpFox::getService('musicsharing.music')->updateCounter(phpFox::getT('m2bmusic_album'), 'album_id', $aVals['item_id'], 'total_comment');

        //@jh: modified...
        //add subpage url to notification...
        $pages_msf = phpFox::getLib('session')->get('pages_msf');

        if (isset($pages_msf['url']))
        {
            $sLink = $pages_msf['url'] . 'musicsharing/listen/album_' . $aVals['item_id'];
        }
        else
        {
            $sLink = phpFox::getLib('url')->makeUrl('musicsharing.listen', array('album' => $aVals['item_id']));
        }
        //$sLink = phpFox::getLib('url')->makeUrl('musicsharing.listen', array('album' => $aVals['item_id']));
        // Send the user an email
        phpFox::getLib('mail')
                ->to($aAlbum['user_id'])
                ->subject(array('photo.user_name_left_you_a_comment_on_site_title', array('user_name' => $sUserName, 'site_title' => phpFox::getParam('core.site_title'))))
                ->message(array('photo.user_name_left_you_a_comment_on_site_title_message', array(
                        'user_name' => $sUserName,
                        'site_title' => phpFox::getParam('core.site_title'),
                        'link' => $sLink
                    )
                        )
                )
                ->notification('comment.add_new_comment')
                ->send();

        $aActualUser = phpFox::getService('user')->getUser($iUserId);
        phpFox::getService('notification.process')->add('comment_musicsharing_album', $aAlbum['album_id'], $aAlbum['user_id']/* , array(
                  'title' => $aAlbum['title'],
                  'user_id' => $aActualUser['user_id'],
                  'image' => $aActualUser['user_image'],
                  'server_id' => $aActualUser['server_id']
                  )
                 */);
    }

    /*     * end
      /**page module* */

    public function getPageMenu($aPage)
    {
        if (!phpFox::getService('pages')->hasPerm($aPage['page_id'], 'musicsharing.view_browse_music'))
        {
            return null;
        }
        $aMenus[] = array(
            'phrase' => Phpfox::getPhrase('musicsharing.publishers_musicsharing'),
            'url' => phpFox::getService('pages')->getUrl($aPage['page_id'], $aPage['title'], $aPage['vanity_url']) . 'musicsharing/',
            'icon' => 'feed/musicsharing.png',
            'landing' => 'musicsharing'
        );

        return $aMenus;
    }

    public function canViewPageSection($iPage)
    {
        if (!phpFox::getService('pages')->hasPerm($iPage, 'musicsharing.view_browse_music'))
        {
            return false;
        }
        return true;
    }

    public function getPageSubMenu($aPage)
    {
        if (!phpFox::getService('pages')->hasPerm($aPage['page_id'], 'musicsharing.share_music'))
        {
            return null;
        }
        $aSubMenus = array();
        if (phpFox::getService('pages')->hasPerm($aPage['page_id'], 'musicsharing.can_create_album'))
        {
            $aSubMenus[] = array(
                'phrase' => phpFox::getPhrase('musicsharing.create_album'),
                'url' => phpFox::getLib('url')->makeUrl('pages.' . $aPage['page_id'] . '.musicsharing.createalbum', array('module' => 'pages', 'item' => $aPage['page_id'])),
                'var_name' => 'create_album'
            );
        }
        if (phpFox::getService('pages')->hasPerm($aPage['page_id'], 'musicsharing.can_create_playlist'))
        {
            $aSubMenus[] = array(
                'phrase' => phpFox::getPhrase('musicsharing.create_new_playlist2'),
                'url' => phpFox::getLib('url')->makeUrl('pages.' . $aPage['page_id'] . '.musicsharing.createplaylist', array('module' => 'pages', 'item' => $aPage['page_id'])),
                'var_name' => 'create_new_playlist2'
            );
        }
        if (phpFox::getService('pages')->hasPerm($aPage['page_id'], 'musicsharing.can_manage_playlist'))
        {
            $aSubMenus['manager_msf'] = array(
                'phrase' => phpFox::getPhrase('musicsharing.manage'),
                'url' => phpFox::getLib('url')->makeUrl('pages.' . $aPage['page_id'] . '.musicsharing.myplaylists'),
                'var_name' => 'manage'
            );
        }
        if (phpFox::getService('pages')->hasPerm($aPage['page_id'], 'musicsharing.can_manage_album'))
        {
            $aSubMenus['manager_msf'] = array(
                'phrase' => phpFox::getPhrase('musicsharing.manage'),
                'url' => phpFox::getLib('url')->makeUrl('pages.' . $aPage['page_id'] . '.musicsharing.myalbums'),
                'var_name' => 'manage'
            );
        }

        return $aSubMenus;
    }

    public function getPagePerms()
    {
        $aPerms = array();

        $aPerms['musicsharing.share_music'] = phpFox::getPhrase('musicsharing.who_can_share_songs_with_music_sharing');
        $aPerms['musicsharing.view_browse_music'] = phpFox::getPhrase('musicsharing.who_can_view_browse_songs_with_music_sharing');
        $aPerms['musicsharing.can_create_album'] = phpFox::getPhrase('musicsharing.who_can_create_albums_with_music_sharing');
        $aPerms['musicsharing.can_create_playlist'] = phpFox::getPhrase('musicsharing.who_can_create_playlists_with_music_sharing');
        $aPerms['musicsharing.can_manage_album'] = phpFox::getPhrase('musicsharing.who_can_manage_albums_with_music_sharing');
        $aPerms['musicsharing.can_manage_playlist'] = phpFox::getPhrase('musicsharing.who_can_manage_playlists_songs_with_music_sharing');

        return $aPerms;
    }

    //**END**/
    public function getApiSupportedMethods()
    {
        $aMethods = array();

        $aMethods[] = array(
            'call' => 'getSongs',
            'requires' => array(
                'user_id' => 'user_id'
            ),
            'detail' => 'Get all uploaded songs based on the user ID# you pass. If you do not pass the #{USER_ID} we will return information about the user that is currently logged in.',
            'type' => 'GET',
            'response' => '{"api":{"total":1,"pages":0,"current_page":0},"output":[{"path_file":"http:\/\/[DOMAIN_REPLACE]\/file\/musicsharing\/2011\/09\/c81e728d9d4c2f636f067f89cc14862a1.mp3","album_url":"http:\/\/[DOMAIN_REPLACE]\/musicsharing/listen/album_1","listen":"4"}]}'
        );

        return array(
            'module' => 'musicsharing',
            'module_info' => 'Sharing your MP3 files',
            'methods' => $aMethods
        );
    }

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->_sTable = phpFox::getT('m2bmusic_album_song');
    }

    public function getAjaxCommentVar()
    {
        //phpFox::getUserGroupParam()  ;
        return 'musicsharing.can_post_on_musicsharing';
        //return true;
    }

    public function getCommentItem($iId)
    {
        $iItem = $this->database()->select('song_id AS comment_item_id, a.user_id AS comment_user_id')
                ->from($this->_sTable, 'm')
                ->leftJoin(phpFox::getT('m2bmusic_album'), 'a', 'a.album_id = m.album_id')
                ->where('song_id = ' . (int) $iId)
                ->execute('getSlaveRow');
        $iItem['comment_view_id'] = 1;
        return $iItem;
    }

    public function deleteComment($iId)
    {
        phpFox::getService('musicsharing.music')->updateCounterComment($iId, false);
    }

    public function addComment($aVals, $iUserId = null, $sUserName = null)
    {
        $aSong = $this->database()->select('u.full_name, u.user_id, u.user_name, s.title , s.title_url, s.song_id, s.album_id, a.title_url AS album_url')
                ->from($this->_sTable, 's')
                ->join(phpFox::getT('m2bmusic_album'), 'a', 'a.album_id = s.album_id')
                ->join(phpFox::getT('user'), 'u', 'u.user_id = a.user_id')
                ->where('s.song_id = ' . (int) $aVals['item_id'])
                ->execute('getSlaveRow');
        phpFox::getService('musicsharing.music')->updateCounterComment($aVals['item_id'], true);
        (phpFox::isModule('feed') ? phpFox::getService('feed.process')->add('comment_musicsharing', $aVals['item_id'], $aVals['text_parsed'], $iUserId, $aSong['user_id'], $aVals['comment_id']) : null);
        $sLink = phpFox::getLib('url')->makeUrl('musicsharing.listen', array('music' => $aVals['item_id']));
        // Send the user an email
        phpFox::getLib('mail')
                ->to($aSong['user_id'])
                ->subject(array('photo.user_name_left_you_a_comment_on_site_title', array('user_name' => $sUserName, 'site_title' => phpFox::getParam('core.site_title'))))
                ->message(array('photo.user_name_left_you_a_comment_on_site_title_message', array(
                        'user_name' => $sUserName,
                        'site_title' => phpFox::getParam('core.site_title'),
                        'link' => $sLink
                    )
                        )
                )
                ->notification('comment.add_new_comment')
                ->send();

        $aActualUser = phpFox::getService('user')->getUser($iUserId);
        phpFox::getService('notification.process')->add('comment_musicsharing', $aSong['song_id'], $aSong['user_id']/* , array(
                  'title' => $aSong['title'],
                  'user_id' => $aActualUser['user_id'],
                  'image' => $aActualUser['user_image'],
                  'server_id' => $aActualUser['server_id']
                  )
                 */);
    }

    public function updateCommentText($aVals, $sText)
    {

        $aSong = $this->database()->select('u.full_name, u.user_id, u.user_name, s.title, s.title_url, s.song_id,s.album_id, a.title_url AS album_url')
                ->from($this->_sTable, 's')
                ->leftJoin(phpFox::getT('m2bmusic_album'), 'a', 'a.album_id = s.album_id')
                ->join(phpFox::getT('user'), 'u', 'u.user_id = a.user_id')
                ->where('s.song_id = ' . (int) $aVals['item_id'])
                ->execute('getSlaveRow');
        (phpFox::isModule('feed') ? phpFox::getService('feed.process')->update('comment_album_song', $aVals['item_id'], $sText, $aVals['comment_id']) : null);
    }

    public function getCommentNotificationFeed($aRow)
    {

        return array(
            'message' => phpFox::getPhrase('musicsharing.a_href_user_link_full_name_a_wrote_a_comment_on_your_song_a_href_song_link_title', array(
                'user_link' => phpFox::getLib('url')->makeUrl($aRow['user_name']),
                'full_name' => $aRow['full_name'],
                'item_link' => phpFox::getLib('url')->makeUrl('musicsharing.listen', array('music' => $aRow['item_id'])),
                'title' => phpFox::getLib('parse.output')->shorten($aRow['item_title'], 20, '...')
                    )
            ),
            'link' => phpFox::getLib('url')->makeUrl('musicsharing.listen', array('music' => $aRow['item_id'])),
            'path' => 'core.url_user',
            'suffix' => '_50'
        );
    }

    public function getItemName($iId, $sName)
    {

        return '<a href="' . phpFox::getLib('url')->makeUrl('comment.view', array('id' => $iId)) . '">' . phpFox::getPhrase('photo.on_name_s_photo', array('name' => $sName)) . '</a>';
    }

    public function getRedirectComment($iId)
    {
        return $this->getFeedRedirect($iId);
    }

    public function getReportRedirect($iId)
    {
        return $this->getFeedRedirect($iId);
    }

    /* public function getReportRedirectAlbum($iId)
      {
      return $this->getFeedRedirectAlbum($iId);
      } */

    public function getCommentItemName()
    {
        return Phpfox::getPhrase('musicsharing.musicsharing');
    }

    public function processCommentModeration($sAction, $iId)
    {
        // Is this comment approved?
        if ($sAction == 'approve')
        {
            // Update the blog comment count
            phpFox::getService('musicsharing.music')->updateCounterComment($aVals['item_id'], true);

            // Get the blogs details so we can add it to our news feed
            $aSong = $this->database()->select('u.full_name, u.user_id, u.user_name, s.title_url, ct.text_parsed, s.album_id, a.title_url AS album_url, c.comment_id, c.user_id AS comment_user_id')
                    ->from($this->_sTable, 's')
                    ->join(phpFox::getT('comment'), 'c', 'c.type_id = \'photo\' AND c.item_id = p.photo_id')
                    ->join(phpFox::getT('user'), 'u', 'u.user_id = c.user_id')
                    ->join(phpFox::getT('comment_text'), 'ct', 'ct.comment_id = c.comment_id')
                    ->leftJoin(phpFox::getT('m2bmusic_album'), 'a', 'a.album_id = s.album_id')
                    ->where('s.song_id = ' . (int) $iId)
                    ->execute('getSlaveRow');

            // Add to news feed
            (phpFox::isModule('feed') ? phpFox::getService('feed.process')->add('comment_musicsharing', $iId, $aSong['text_parsed'], phpFox::getUserBy('user_name'), $aSong['user_id'], $aSong['full_name'], $aSong['comment_id']) : null);

            // Send the user an email
            $sLink = phpFox::getLib('url')->makeUrl('musicsharing.listen', array('music' => $aVals['item_id']));
            phpFox::getLib('mail')->to($aSong['comment_user_id'])
                    ->subject(array('photo.full_name_approved_your_comment_on_site_title', array('full_name' => phpFox::getUserBy('full_name'), 'site_title' => phpFox::getParam('core.site_title'))))
                    ->message(array('photo.full_name_approved_your_comment_on_site_title_message', array(
                            'full_name' => phpFox::getUserBy('full_name'),
                            'site_title' => phpFox::getParam('core.site_title'),
                            'link' => $sLink
                        )
                            )
                    )
                    ->notification('comment.approve_new_comment')
                    ->send();
        }
    }

    public function getDashboardLinks()
    {

        return array(
            'submit' => array(
                'phrase' => 'Create an Album',
                'link' => 'musicsharing.createalbum',
                'image' => 'misc/note.png'
            ),
            'edit' => array(
                'phrase' => 'Manage Albums',
                'link' => 'musicsharing.myalbums',
                'image' => 'misc/note.png'
            )
        );
    }

    public function getCommentNewsFeed($aRow)
    {

        $aRow['link'] = phpFox::getLib('url')->makeUrl('musicsharing.listen', array('music' => $aRow['item_id']));
        $oUrl = phpFox::getLib('url');
        $oParseOutput = phpFox::getLib('parse.output');

        $aRow['image_path'] = $aPart['destination'];

        if ($aRow['owner_user_id'] == $aRow['viewer_user_id'])
        {
            $aRow['text'] = phpFox::getPhrase('musicsharing.a_href_user_link_full_name_a_added_a_new_comment_on_their_own_a_href_title_link_song', array(
                        'user_link' => $oUrl->makeUrl('feed.user', array('id' => $aRow['owner_user_id'])),
                        'full_name' => $this->preParse()->clean($aRow['owner_full_name']),
                        'title_link' => $aRow['link']
                            )
            );
        }
        else
        {
            if ($aRow['item_user_id'] == phpFox::getUserBy('user_id'))
            {
                $aRow['text'] = phpFox::getPhrase('musicsharing.a_href_user_link_full_name_a_added_a_new_comment_on_your_a_href_title_link_song_a', array(
                            'user_link' => $oUrl->makeUrl('feed.user', array('id' => $aRow['owner_user_id'])),
                            'full_name' => $this->preParse()->clean($aRow['owner_full_name']),
                            'title_link' => $aRow['link']
                                )
                );
            }
            else
            {
                $aRow['text'] = phpFox::getPhrase('musicsharing.a_href_user_link_full_name_a_added_a_new_comment_on', array(
                            'user_link' => $oUrl->makeUrl('feed.user', array('id' => $aRow['owner_user_id'])),
                            'full_name' => $this->preParse()->clean($aRow['owner_full_name']),
                            'title_link' => $aRow['link'],
                            'item_user_name' => $this->preParse()->clean($aRow['viewer_full_name']),
                            'item_user_link' => $oUrl->makeUrl('feed.user', array('id' => $aRow['viewer_user_id']))
                                )
                );
            }
        }
        $sImage = '';
        if (!empty($aRow['image_path']))
        {
            $sImage = phpFox::getLib('image.helper')->display(array(
                'server_id' => $aPart['server_id'],
                'path' => 'photo.url_photo',
                'file' => $aRow['image_path'],
                'suffix' => '_75',
                'max_width' => 75,
                'max_height' => 75,
                'style' => 'vertical-align:top; padding-right:5px;'
                    )
            );

            $sImage = '<a href="' . $aRow['link'] . '">' . $sImage . '</a>';
        }

        $aRow['text'] .= '<div class="p_4"><div class="go_left">' . $sImage . '</div><div style="margin-left:0px;">' . phpFox::getService('feed')->quote($aRow['content']) . '</div><div class="clear"></div></div>';

        return $aRow;
    }

    public function getActivityFeedPagesAlbum($aRow, $bIsCallback = false, $bIsChildItem = false)
    {
        $info = phpFox::getService('musicsharing.music')->getAlbumInfo($aRow['item_id']);
        if (!isset($info['album_id']))
        {
            return false;
        }

        if ($bIsChildItem)
        {
            $aRow = array_merge($info, $aRow);
        }

        $info['owner_full_name'] = '';
        $info['owner_user_name'] = '';

        if (!isset($info["play_count"]))
        {
            $info["play_count"] = 0;
        }

        $aReturn = array(
            'feed_title' => phpFox::getService('feed')->shortenTitle($info['title']),
            'feed_info' => phpFox::getPhrase('musicsharing.full_name_created_a_new_album', array(
                'full_name' => $this->preParse()->clean($info['owner_full_name']),
                'profile_link' => phpFox::getLib('url')->makeUrl($info['owner_user_name']),
                'album_title' => phpFox::getService('feed')->shortenTitle($info['title']),
                'album_link' => phpFox::getLib('url')->makeUrl(($info['module_id'] ? sprintf("%s.%s.", $info['module_id'], $info['item_id']) : "") . 'musicsharing.listen', array('album' => $aRow['item_id'])),
                'link' => ''
            )),
            'feed_link' => phpFox::getLib('url')->makeUrl(($info['module_id'] ? sprintf("%s.%s.", $info['module_id'], $info['item_id']) : "") . 'musicsharing.listen', array('album' => $aRow['item_id'])),
            'feed_content' => (((int) $info['play_count'] > 1) ? $info['play_count'] . ' ' . Phpfox::getPhrase('musicsharing.plays') : Phpfox::getPhrase('musicsharing.1_play')),
            'total_comment' => isset($info['total_comment']) ? $info['total_comment'] : 0,
            'feed_total_like' => isset($info['total_like']) ? $info['total_like'] : 0,
            'feed_is_liked' => isset($info['is_liked']) ? $info['is_liked'] : false,
            'feed_icon' => phpFox::getLib('image.helper')->display(array('theme' => 'module/musicsharing.png', 'return_url' => true)),
            'time_stamp' => isset($aRow['time_stamp']) ? $aRow['time_stamp'] : null,
            'enable_like' => true,
            'comment_type_id' => 'musicsharing_album',
            'like_type_id' => 'musicsharing_album',
            'feed_custom_width' => '38px',
        );

        $aReturn['feed_image'] = phpFox::getLib('image.helper')->display(array(
            'theme' => 'misc/play_button.png',
                // 'return_url' => true
                )
        );

        $aReturn['feed_image_onclick'] = 'window.location.href = \'' . phpFox::getLib('url')->makeUrl('musicsharing.listen', array('album' => $aRow['item_id'])) . '\'';
        $aReturn['feed_image_onclick_no_image'] = true;
        $aReturn['no_target_blank'] = true;

        return array_merge($aReturn, $aRow);
    }

    public function getActivityFeedPagesPlaylist($aRow, $bIsCallback = false, $bIsChildItem = false)
    {
        $info = phpFox::getService('musicsharing.music')->getPlaylistInfo($aRow['item_id']);
        if (!isset($info['playlist_id']))
        {
            return false;
        }

        if ($bIsChildItem)
        {
            $aRow = array_merge($info, $aRow);
        }

        $info['owner_full_name'] = '';
        $info['owner_user_name'] = '';
        if (!isset($info["play_count"]))
        {
            $info["play_count"] = 0;
        }
        $aReturn = array(
            'feed_title' => phpFox::getService('feed')->shortenTitle($info['title']),
            'feed_info' => phpFox::getPhrase('musicsharing.full_name_created_a_new_playlist', array(
                'full_name' => $this->preParse()->clean($info['owner_full_name']),
                'profile_link' => phpFox::getLib('url')->makeUrl($info['owner_user_name']),
                'playlist_title' => phpFox::getService('feed')->shortenTitle($info['title']),
                'playlist_link' => phpFox::getLib('url')->makeUrl('musicsharing.listen', array('playlist' => $aRow['item_id'])),
                'link' => ''
            )),
            'feed_link' => phpFox::getLib('url')->makeUrl('musicsharing.listen', array('playlist' => $aRow['item_id'])),
            'feed_content' => ($info['play_count'] > 1 ? $info['play_count'] . ' ' . Phpfox::getPhrase('musicsharing.plays') : Phpfox::getPhrase('musicsharing.1_play')),
            'total_comment' => isset($info['total_comment']) ? $info['total_comment'] : 0,
            'feed_total_like' => isset($info['total_like']) ? $info['total_like'] : 0,
            'feed_is_liked' => isset($info['is_liked']) ? $info['is_liked'] : false,
            'feed_icon' => phpFox::getLib('image.helper')->display(array('theme' => 'module/musicsharing.png', 'return_url' => true)),
            'time_stamp' => isset($aRow['time_stamp']) ? $aRow['time_stamp'] : null,
            'enable_like' => true,
            'comment_type_id' => 'musicsharing_playlist',
            'like_type_id' => 'musicsharing_playlist',
            'feed_custom_width' => '38px',
        );

        $aReturn['feed_image'] = phpFox::getLib('image.helper')->display(array(
            'theme' => 'misc/play_button.png',
                // 'return_url' => true
                )
        );

        $aReturn['feed_image_onclick'] = 'window.location.href = \'' . phpFox::getLib('url')->makeUrl('musicsharing.listen', array('playlist' => $aRow['item_id'])) . '\'';
        $aReturn['feed_image_onclick_no_image'] = true;
        $aReturn['no_target_blank'] = true;

        return array_merge($aReturn, $aRow);
    }

    public function getActivityFeedAlbum($aRow, $bIsCallback = false, $bIsChildItem = false)
    {
        $info = phpFox::getService('musicsharing.music')->getAlbumInfo($aRow['item_id']);

        if (!isset($info['album_id']))
        {
            return false;
        }

        if ($bIsChildItem)
        {
            $aRow = array_merge($info, $aRow);
        }

        $info['owner_full_name'] = '';
        $info['owner_user_name'] = '';

        if (!isset($info["play_count"]))
        {
            $info["play_count"] = 0;
        }

        $aReturn = array(
            'feed_title' => phpFox::getService('feed')->shortenTitle($info['title']),
            'feed_info' => phpFox::getPhrase('musicsharing.full_name_created_a_new_album', array(
                'full_name' => $this->preParse()->clean($info['owner_full_name']),
                'profile_link' => phpFox::getLib('url')->makeUrl($info['owner_user_name']),
                'album_title' => phpFox::getService('feed')->shortenTitle($info['title']),
                'album_link' => phpFox::getLib('url')->makeUrl(($info['module_id'] ? sprintf("%s.%s.", $info['module_id'], $info['item_id']) : "") . 'musicsharing.listen', array('album' => $aRow['item_id'])),
                'link' => ''
            )),
            'feed_link' => phpFox::getLib('url')->makeUrl(($info['module_id'] ? sprintf("%s.%s.", $info['module_id'], $info['item_id']) : "") . 'musicsharing.listen', array('album' => $aRow['item_id'])),
            'feed_content' => (((int) $info['play_count'] > 1) ? $info['play_count'] . ' ' . Phpfox::getPhrase('musicsharing.plays') : Phpfox::getPhrase('musicsharing.1_play')),
            'total_comment' => isset($info['total_comment']) ? $info['total_comment'] : 0,
            'feed_total_like' => isset($info['total_like']) ? $info['total_like'] : 0,
            'feed_is_liked' => isset($info['is_liked']) ? $info['is_liked'] : false,
            'feed_icon' => phpFox::getLib('image.helper')->display(array('theme' => 'module/musicsharing.png', 'return_url' => true)),
            'time_stamp' => isset($aRow['time_stamp']) ? $aRow['time_stamp'] : null,
            'enable_like' => true,
            'comment_type_id' => 'musicsharing_album',
            'like_type_id' => 'musicsharing_album',
            'feed_custom_width' => '38px',
        );

        $aReturn['feed_image'] = phpFox::getLib('image.helper')->display(array(
            'theme' => 'misc/play_button.png',
                )
        );

        $aReturn['feed_image_onclick_no_image'] = true;
        $aReturn['no_target_blank'] = true;

        return array_merge($aReturn, $aRow);
    }

    public function getActivityFeedPlaylist($aRow, $bIsCallback = false, $bIsChildItem = false)
    {
        $info = phpFox::getService('musicsharing.music')->getPlaylistInfo($aRow['item_id']);
        if (!isset($info['playlist_id']))
        {
            return false;
        }

        if ($bIsChildItem)
        {
            $aRow = array_merge($info, $aRow);
        }

        $info['owner_full_name'] = '';
        $info['owner_user_name'] = '';
        if (!isset($info["play_count"]))
        {
            $info["play_count"] = 0;
        }
        $aReturn = array(
            'feed_title' => phpFox::getService('feed')->shortenTitle($info['title']),
            'feed_info' => phpFox::getPhrase('musicsharing.full_name_created_a_new_playlist', array(
                'full_name' => $this->preParse()->clean($info['owner_full_name']),
                'profile_link' => phpFox::getLib('url')->makeUrl($info['owner_user_name']),
                'playlist_title' => phpFox::getService('feed')->shortenTitle($info['title']),
                'playlist_link' => phpFox::getLib('url')->makeUrl('musicsharing.listen', array('playlist' => $aRow['item_id'])),
                'link' => ''
            )),
            'feed_link' => phpFox::getLib('url')->makeUrl('musicsharing.listen', array('playlist' => $aRow['item_id'])),
            'feed_content' => ($info['play_count'] > 1 ? $info['play_count'] . ' ' . Phpfox::getPhrase('musicsharing.plays') : Phpfox::getPhrase('musicsharing.1_play')),
            'total_comment' => isset($info['total_comment']) ? $info['total_comment'] : 0,
            'feed_total_like' => isset($info['total_like']) ? $info['total_like'] : 0,
            'feed_is_liked' => isset($info['is_liked']) ? $info['is_liked'] : false,
            'feed_icon' => phpFox::getLib('image.helper')->display(array('theme' => 'module/musicsharing.png', 'return_url' => true)),
            'time_stamp' => isset($aRow['time_stamp']) ? $aRow['time_stamp'] : null,
            'enable_like' => true,
            'comment_type_id' => 'musicsharing_playlist',
            'like_type_id' => 'musicsharing_playlist',
            'feed_custom_width' => '38px',
        );

        $aReturn['feed_image'] = phpFox::getLib('image.helper')->display(array('theme' => 'misc/play_button.png'));

        $aReturn['feed_image_onclick'] = 'window.location.href = \'' . phpFox::getLib('url')->makeUrl('musicsharing.listen', array('playlist' => $aRow['item_id'])) . '\'';
        $aReturn['feed_image_onclick_no_image'] = true;
        $aReturn['no_target_blank'] = true;

        return array_merge($aReturn, $aRow);
    }

    public function getRedirectCommentSong($iId)
    {
        return $this->getFeedRedirectSong($iId);
    }

    public function getRedirectCommentAlbum($iId)
    {
        return $this->getFeedRedirectAlbum($iId);
    }

    public function getRedirectCommentPlaylist($iId)
    {
        return $this->getFeedRedirectAlbum($iId);
    }

    public function getRedirectLikeSong($iId)
    {
        return $this->getFeedRedirectSong($iId);
    }

    public function getRedirectLikeAlbum($iId)
    {
        return $this->getFeedRedirectAlbum($iId);
    }

    public function getRedirectLikePlaylist($iId)
    {
        return $this->getFeedRedirectAlbum($iId);
    }

    public function __call($sMethod, $aArguments)
    {
        /**
         * Check if such a plug-in exists and if it does call it.
         */
        if ($sPlugin = Phpfox_Plugin::get('musicsharing.service_callback__call'))
        {
            return eval($sPlugin);
        }

        /**
         * No method or plug-in found we must throw a error.
         */
        Phpfox_Error::trigger('Call to undefined method ' . __CLASS__ . '::' . $sMethod . '()', E_USER_ERROR);
    }

    public function getCommentNotificationAlbumtag($aNotification)
    {
        $aRow = phpFox::getLib("database")->select('m2bmusic.*, u.gender, u.full_name')
                ->from(phpFox::getT('comment'), 'c')
                ->join(phpFox::getT('m2bmusic_album'), 'm2bmusic', 'm2bmusic.album_id = c.item_id')
                ->join(phpFox::getT('user'), 'u', 'u.user_id = c.user_id')
                ->where('c.comment_id = ' . (int) $aNotification['item_id'])
                ->execute('getSlaveRow');

        if (empty($aRow))
        {
            return false;
        }

        $sPhrase = phpFox::getPhrase('musicsharing.user_name_tagged_you_in_a_comment_in_a_album', array('user_name' => $aRow['full_name']));

        return array(
            'link' => phpFox::getLib('url')->makeUrl('musicsharing.listen', array('album' => $aRow['album_id'])),
            'message' => $sPhrase,
            'icon' => phpFox::getLib('template')->getStyle('image', 'activity.png', 'blog')
        );
    }

    public function getCommentNotificationPlaylisttag($aNotification)
    {
        $aRow = phpFox::getLib("database")->select('m2bmusic.*, u.gender, u.full_name')
                ->from(phpFox::getT('comment'), 'c')
                ->join(phpFox::getT('m2bmusic_playlist'), 'm2bmusic', 'm2bmusic.playlist_id = c.item_id')
                ->join(phpFox::getT('user'), 'u', 'u.user_id = c.user_id')
                ->where('c.comment_id = ' . (int) $aNotification['item_id'])
                ->execute('getSlaveRow');
        if (empty($aRow))
        {
            return false;
        }
        $sPhrase = phpFox::getPhrase('musicsharing.user_name_tagged_you_in_a_comment_in_a_playlist', array('user_name' => $aRow['full_name']));

        return array(
            'link' => phpFox::getLib('url')->makeUrl('musicsharing.listen', array('playlist' => $aRow['playlist_id'])),
            'message' => $sPhrase,
            'icon' => phpFox::getLib('template')->getStyle('image', 'activity.png', 'blog')
        );
    }

}

?>