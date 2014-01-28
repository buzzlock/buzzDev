<?php

/**
 * [PHPFOX_HEADER]
 */
if (!isset($_SESSION))
{
    session_start();
}
defined('PHPFOX') or exit('NO DICE!');
?>
<?php

class musicsharing_Component_Controller_Listen extends Phpfox_Component
{
    /**
     * Get the song path for the migration song.
     * @param string $sSong
     * @param int $iServerId
     * @return string
     */
    public function getSongPath($sSong, $iServerId = null)
	{
		if (preg_match("/\{file\/music_folder\/(.*)\.mp3\}/i", $sSong, $aMatches))
		{
			return Phpfox::getParam('core.path') . str_replace(array('{', '}'), '', $aMatches[0]);
		}
		$sSong = Phpfox::getParam('music.url') . sprintf($sSong, '');	
		
		if (Phpfox::getParam('core.allow_cdn') && !empty($iServerId))
		{
			//$sSong = Phpfox::getLib('cdn')->getUrl($sSong);
			$sTempSong = Phpfox::getLib('cdn')->getUrl($sSong, $iServerId);
			if (!empty($sTempSong))
			{
				$sSong = $sTempSong;
			}
		}
		
		return $sSong;
	}
    /**
     * Get the song path for music sharing song.
     * @param string $sSong
     * @param int $iServerId
     * @return string
     */
    private function getSongPathForCDN($sSong, $iServerId = null)
	{
		if (preg_match("/\{file\/musicsharing\/(.*)\.mp3\}/i", $sSong, $aMatches))
		{
			return Phpfox::getParam('core.path') . str_replace(array('{', '}'), '', $aMatches[0]);
		}
        
		$sSong = phpFox::getParam('core.path') . 'file/musicsharing/' . sprintf($sSong, '');
        
		if (Phpfox::getParam('core.allow_cdn') && !empty($iServerId))
		{
			$sTempSong = Phpfox::getLib('cdn')->getUrl($sSong, $iServerId);
			if (!empty($sTempSong))
			{
				$sSong = $sTempSong;
			}
		}
		
		return $sSong;
	}
    
    public function process()
    {   
        Phpfox::getLib('setting')->setParam('musicsharing.url_image', Phpfox::getParam('core.url_pic') . 'musicsharing' . PHPFOX_DS);
        
        $this->template()->setPhrase(array('musicsharing.add_song_to_playlist'));
        $oServiceMusic = Phpfox::getService('musicsharing.music');
        $aParentModule = $this->getParam('aParentModule');

        $this->template()->assign(array('aParentModule' => $aParentModule));

        $is_facebook = strpos($_SERVER["HTTP_USER_AGENT"], "facebook");
        $siteName = phpFox::getParam('core.site_title');
        $core_path = phpFox::getParam('core.path');

        $bIsProfile = false;

        $oServiceMusic->getSectionMenu($aParentModule);

        $this->template()->setBreadCrumb(phpFox::getPhrase('musicsharing.music_sharing'), $this->url()->makeUrl('musicsharing'), false);

        $_SESSION['downloadlist_downloadlist'] = phpFox::getUserId();
        
        if (phpFox::getUserId() == 0)
        {
            $settings = $oServiceMusic->getSettings(0);

            if ($settings["is_public_permission"] == 0)
                phpFox::isUser(true);
            else
                $settings = $oServiceMusic->getSettings(3);
        } 
        else
        {
            $settings = $oServiceMusic->getUserSettings(phpFox::getUserId());
        }
        
        $this->template()->assign(array('settings' => $settings));

        $music_id = $this->request()->get('music', '');
        $album_id = $this->request()->get('album', '');
        $playlist_id = $this->request()->get('playlist', '');
        
        $this->template()->setHeader(array(
            'm2bmusic_tabcontent.js' => 'module_musicsharing',
            'm2bmusic_class.js' => 'module_musicsharing',
            'jquery.scrollTo-1.4.3.1-min.js' => 'module_musicsharing',
            'music.css' => 'module_musicsharing',
            'jquery/plugin/jquery.highlightFade.js' => 'static_script',
            'jquery/plugin/jquery.scrollTo.js' => 'static_script',
            'jquery/plugin/imgnotes/jquery.tag.js' => 'static_script',
            'quick_edit.js' => 'static_script',
            'comment.css' => 'style_css',
            'pager.css' => 'style_css',
            'switch_legend.js' => 'static_script',
            'switch_menu.js' => 'static_script',
            'feed.js' => 'module_feed',
            'listen.css' => 'module_musicsharing',
            'listen.js' => 'module_musicsharing',
            'musicsharing_style.css' => 'module_musicsharing',
            'suppress_menu.css' => 'module_musicsharing',

            'mediaelement-and-player.min.js' => 'module_musicsharing',
            'controller_player.js' => 'module_musicsharing',
            'mediaelementplayer.min.css' => 'module_musicsharing',
            'mejs-audio-skins.css' => 'module_musicsharing',
            'jquery.scrollTo-1.4.3.1-min.js' => 'module_musicsharing',
            'slimScroll.min.js' => 'module_musicsharing',
            'config.js' => 'module_musicsharing',

            // For Rating.
            'jquery.rating.css' => 'style_css',
            'jquery/plugin/star/jquery.rating.js' => 'static_script',
            'rate.js' => 'module_musicsharing'
        ));

        // For HTML5 player.
        $arSongs = array();

        $album = true;
        $music_info = array();

        if ($music_id != "")
        {
            $music_info = $oServiceMusic->song_track_info($music_id);
            if (!isset($music_info['song_id']))
            {
                $this->url()->send('musicsharing');
            }
            
            // Count the total play time.
            $oServiceMusic->service_playcount($music_info['song_id']);
            
            if (isset($music_info['album_id']) && $music_info['album_id'] > 0)
            {
                $info = $oServiceMusic->getAlbumInfo($music_info['album_id']);
                
                $this->setParam('artistId', $info['user_id']);
                $this->setParam('aFeed', array(
                    'comment_type_id' => 'musicsharing_album',
                    'privacy' => $info['privacy'],
                    'comment_privacy' => $info['privacy_comment'],
                    'like_type_id' => 'musicsharing_album',
                    'feed_is_liked' => $info['is_liked'],
                    'feed_is_friend' => $info['is_friend'],
                    'item_id' => $info['album_id'],
                    'user_id' => $info['user_id'],
                    'total_comment' => $info['total_comment'],
                    'total_like' => $info['total_like'],
                    'feed_link' => $this->url()->makeUrl('musicsharing.listen', array('album' => $music_info['album_id'])),
                    'feed_title' => $info['title'],
                    'feed_display' => 'view',
                    'feed_total_like' => $info['total_like'],
                    'report_module' => 'musicsharing',
                    'report_phrase' => phpFox::getPhrase('musicsharing.report_this_album'),
                    'time_stamp' => strtotime($info['creation_date']),
                    'music_info' => $music_info
                ));

                $iPlheight = (79 + ((($info["num_track"] > 8) ? 8 : ($info["num_track"])) * 41.5));
                if ($iPlheight < 270)
                {
                    $iPlheight = 270;
                }
                $this->template()->assign(array('iPlheight' => $iPlheight + 20));
                
                $_SESSION['musicsharing_listen'] = array('item_id' => $music_info['album_id'], 'type' => 'musicsharing_album');
            }
            
            $mydescription = $music_info['lyric'];
            $mydescription .= "<br/>" . $this->url()->makeUrl('musicsharing.listen', array('music' => $music_id));
            
            $album_info = $oServiceMusic->getAlbumInfo($music_info['album_id']);
            $oServiceMusic->check('musicsharing_album', $album_info['album_id'], $album_info['user_id'], $album_info['privacy'], $album_info['is_friend'], false, "musicsharing_album");
            
            // For HTML5 player.
            $music_info['ordering'] = 1;
            // Support CDN.
            if (isset($music_info['phpfox_music_id']) && $music_info['phpfox_music_id'] > 0)
            {
                $music_info['url'] = $this->getSongPath($music_info['url'], $music_info['server_id']);
            }
            else
            {
                $music_info['url'] = $this->getSongPathForCDN($music_info['url'], $music_info['server_id']);
            }            
            $arSongs[] = $music_info;
            
            $this->template()->assign(array(
                'album_info' => $album_info,
                'urldata' => $this->url()->makeUrl('musicsharing.data.name_getalbum.idalbum_' . $album_info['album_id']),
                'default_music' => $music_id,
                "defaultSongId" => "yes"
            ));
            
            $this->template()->setMeta(array('description' => $mydescription, 'title'));
            $this->template()->setTitle($music_info['title']);

            if ($is_facebook !== false)
            {
                $imgs = "";
                if (isset($album_info["album_image"]) && $album_info["album_image"] != "")
                {
                    $imgs = "<img src=\"" . $core_path . "file/pic/musicsharing/" . $album_info["album_image"] . "\"/>";
                }
                else
                {
                    $imgs = "<img src=\"" . $core_path . "module/musicsharing/static/image/music.png\"/>";
                }

                $satitle = $album_info["title"] . " - " . $music_info['title'];
                $sdescript = phpFox::getLib('phpfox.parse.output')->shorten(phpFox::getLib('phpfox.parse.output')->clean($album_info["description"]), 120, "...", false);
                echo <<<EOF
	<html>
		<head>
			<title>$satitle</title>
			<meta name="description" content="$sdescript" />
		</head>
		<body>
			$imgs
		</body>
	</html>
EOF;
                exit;
            }
        }
        else if ($album_id != "")
        {
            $music_info = $oServiceMusic->get_firstSong($album_id);

            if (isset($music_info['album_id']) && $music_info['album_id'] > 0)
            {
                $info = $oServiceMusic->getAlbumInfo($album_id);

                $this->setParam('artistId', $info['user_id']);
                $aFeed = array(
                    'comment_type_id' => 'musicsharing_album',
                    'privacy' => $info['privacy'],
                    'comment_privacy' => $info['privacy_comment'],
                    'like_type_id' => 'musicsharing_album',
                    'feed_is_liked' => $info['is_liked'],
                    'feed_is_friend' => $info['is_friend'],
                    'item_id' => $info['album_id'],
                    'user_id' => $info['user_id'],
                    'total_comment' => $info['total_comment'],
                    'total_like' => $info['total_like'],
                    'feed_link' => phpFox::getLib('url')->makeUrl('musicsharing.listen', array('album' => $music_info['album_id'])),
                    'feed_title' => $info['title'],
                    'feed_display' => 'view',
                    'feed_total_like' => $info['total_like'],
                    'report_module' => 'musicsharing_album',
                    'report_phrase' => phpFox::getPhrase('musicsharing.report_this_album'),
                    'time_stamp' => strtotime($info['creation_date']),
                );
                
                $aPage = Phpfox::getLib('session')->get('pages_msf');
                if (isset($aPage['module_id']) && $info['privacy'] != 0)
                {
                    $aFeed['privacy'] = 0;
                    $aFeed['report_module'] = '';
                }

                $this->setParam('aFeed', $aFeed);

                $_SESSION['musicsharing_listen'] = array('item_id' => $music_info['album_id'], 'type' => 'musicsharing_album');

                $album_info = $oServiceMusic->getAlbumInfo($music_info['album_id']);
                
                // Check the privacy.
                $oServiceMusic->check('musicsharing_album', $album_info['album_id'], $album_info['user_id'], $album_info['privacy'], $album_info['is_friend'], "musicsharing_album");

                // Update playcount for album if it is available.
                if ($album_info)
                {
                    $oServiceMusic->updatePlayCountForAlbum($album_id);
                }

                // For HTML5 player.
                $arResults = $oServiceMusic->getSongsInAlbum($album_info['album_id'], phpFox::getUserId());                
                foreach($arResults as $i => $aItem)
                {
                    if (isset($aItem['phpfox_music_id']) && $aItem['phpfox_music_id'] > 0)
                    {
                        $aItem['url'] = $this->getSongPath($aItem['url'], $aItem['server_id']);
                    }
                    else
                    {
                        $aItem['url'] = $this->getSongPathForCDN($aItem['url'], $aItem['server_id']);
                    }
                    $aItem['ordering'] = $i + 1;
                    $arSongs[] = $aItem;
                }
                
                $this->template()->assign(array(
                    'album_info' => $album_info,
                    'urldata' => $this->url()->makeUrl('musicsharing.data.name_getalbum.idalbum_' . $album_info['album_id']),
                    'default_music' => $music_id
                ));

                $iPlheight = (79 + ((($info["num_track"] > 8) ? 8 : ($info["num_track"])) * 41.5));
                if ($iPlheight < 270)
                {
                    $iPlheight = 270;
                }
                $this->template()->assign(array('iPlheight' => $iPlheight + 20));
            }
            else
            {
                $album_info = $oServiceMusic->getAlbumInfo($album_id);
                if (!isset($album_info['album_id']))
                {
                    $this->url()->send('musicsharing');
                }
                $oServiceMusic->check('musicsharing_album', $album_info['album_id'], $album_info['user_id'], $album_info['privacy'], $album_info['is_friend'], "musicsharing_album");

                $this->template()->assign(
                        array(
                            'album_info2' => $album_info,
                            'default_music' => $music_id
                        )
                );
            }

            if ($is_facebook !== false)
            {
                $imgs = "";
                if (isset($album_info["album_image"]) && $album_info["album_image"] != "")
                {
                    $imgs = "<img src=\"" . $core_path . "file/pic/musicsharing/" . $album_info["album_image"] . "\"/>";
                }
                else
                {
                    $imgs = "<img src=\"" . $core_path . "module/musicsharing/static/image/music.png\"/>";
                }

                $satitle = $album_info["title"];
                $sdescript = phpFox::getLib('phpfox.parse.output')->shorten(phpFox::getLib('phpfox.parse.output')->clean($album_info["description"]), 120, "...", false);
                echo <<<EOF
	<html>
		<head>
			<title>$satitle</title>
			<meta name="description" content="$sdescript" />
		</head>
		<body>
			$imgs
		</body>
	</html>
EOF;
                exit;
            }
        }
        else if ($playlist_id != "")
        {
            $this->template()->assign(array("pid" => "pid"));
            $info = $oServiceMusic->getPlaylistInfo($playlist_id);
            $amusic = $oServiceMusic->getPlaylistSongs($playlist_id, "", 1);
            
            $aPlsCount = $oServiceMusic->get_total_playlistsong($playlist_id);
            
            $music_info = isset($amusic[0]) ? $amusic[0] : 0;
            
            if (count($music_info) > 0 && $aPlsCount > 0)
            {
                $oServiceMusic->playlist_updatePlaycount($playlist_id);

                if (!isset($info['playlist_id']))
                {
                    $this->url()->send('musicsharing');
                }

                $oServiceMusic->check('musicsharing_playlist', $info['playlist_id'], $info['user_id'], $info['privacy'], $info['is_friend'], false, "musicsharing_playlist");

                $this->setParam('artistId', $info['user_id']);

                $aFeed = array(
                    'comment_type_id' => 'musicsharing_playlist',
                    'privacy' => $info['privacy'],
                    'comment_privacy' => $info['privacy_comment'],
                    'like_type_id' => 'musicsharing_playlist',
                    'feed_is_liked' => $info['is_liked'],
                    'feed_is_friend' => $info['is_friend'],
                    'item_id' => $playlist_id,
                    'user_id' => $info['user_id'],
                    'total_comment' => $info['total_comment'],
                    'total_like' => $info['total_like'],
                    'feed_link' => $this->url()->makeUrl('musicsharing.listen', array('playlist' => $playlist_id)),
                    'feed_title' => $info['title'],
                    'feed_display' => 'view',
                    'feed_total_like' => $info['total_like'],
                    'report_module' => 'musicsharing_playlist',
                    'report_phrase' => Phpfox::getPhrase('musicsharing.report_this_playlist'),
                    'time_stamp' => strtotime($info['creation_date'])
                );

                $aPage = Phpfox::getLib('session')->get('pages_msf');
                if (isset($aPage['module_id']) && $info['privacy'] != 0)
                {
                    $aFeed['privacy'] = 0;
                    $aFeed['report_module'] = '';
                }

                $this->setParam('aFeed', $aFeed);

                $iPlheight = ((79 + ($info["num_track"] > 8 ? 8 : $info["num_track"]) * 41.5) . "");
                if ($iPlheight < 270)
                {
                    $iPlheight = 270;
                }
                
                $this->template()->assign(array('iPlheight' => $iPlheight + 20));
                
                $_SESSION['musicsharing_listen'] = array('item_id' => $music_info['album_id'], 'type' => 'musicsharing_playlist');
                
                // Cheat code.
                $info['album_image'] = $info['playlist_image'];

                // For HTML5 player.
                $arResults = $oServiceMusic->getSongsInPlaylist($playlist_id, phpFox::getUserId());
                
                // Support CDN.
                if (isset($music_info['phpfox_music_id']) && $music_info['phpfox_music_id'] > 0)
                {
                    $music_info['url'] = $this->getSongPath($music_info['url'], $music_info['server_id']);
                }
                else
                {
                    $music_info['url'] = $this->getSongPathForCDN($music_info['url'], $music_info['server_id']);
                }
                foreach($arResults as $i => $aItem)
                {
                    if (isset($aItem['phpfox_music_id']) && $aItem['phpfox_music_id'] > 0)
                    {
                        $aItem['url'] = $this->getSongPath($aItem['url'], $aItem['server_id']);
                    }
                    else
                    {
                        $aItem['url'] = $this->getSongPathForCDN($aItem['url'], $aItem['server_id']);
                    }
                    $aItem['ordering'] = $i + 1;
                    $arSongs[] = $aItem;
                    // Get ordering for default song.
                    if ($aItem['song_id'] == $music_info['song_id'])
                    {
                        $music_info['ordering'] = $i;
                    }
                }

                $this->template()->assign(array(
                    'album_info' => $info,
                    'urldata' => $this->url()->makeUrl('musicsharing.data.name_getplaylist.idplaylist_' . $playlist_id),
                    'default_music' => $music_id,
                ));
            }
            else
            {
                if (!isset($info['playlist_id']))
                {
                    $this->url()->send('musicsharing');
                }
                $oServiceMusic->check('musicsharing_playlist', $info['playlist_id'], $info['user_id'], $info['privacy'], $info['is_friend'], false, "musicsharing_playlist");
                $this->template()->assign(
                        array(
                            'album_info2' => $info,
                            'default_music' => $music_id,
                        )
                );
            }
            $album = false;

            if ($is_facebook !== false)
            {
                $imgs = "";
                if (isset($info["playlist_image"]) && $info["playlist_image"] != "")
                {
                    $imgs = "<img src=\"" . $core_path . "file/pic/musicsharing/" . $info["playlist_image"] . "\"/>";
                }
                else
                {
                    $imgs = "<img src=\"" . $core_path . "module/musicsharing/static/image/music.png\"/>";
                }
                
                $satitle = $info["title"];
                $sdescript = phpFox::getLib('phpfox.parse.output')->shorten(phpFox::getLib('phpfox.parse.output')->clean($info["description"]), 120, "...", false);
                echo <<<EOF
	<html>
		<head>
			<title>$satitle</title>
			<meta name="description" content="$sdescript" />
		</head>
		<body>
			$imgs
		</body>
	</html>
EOF;
                exit;
            }
        }

        //fix lyrics...
        // For HTML5 player.
        if (count($arSongs) > 0)
        {
            $music_info = $arSongs[0];
        }

        $strPathForMP3 = $core_path . 'file/musicsharing/';

        // For vote in HTML5.
        $this->template()->setPhrase(array('musicsharing.thanks_for_rating'));
        $arStars = array(
            '1' => Phpfox::getPhrase('musicsharing.poor'),
            '2' => Phpfox::getPhrase('musicsharing.nothing_special'),
            '3' => Phpfox::getPhrase('musicsharing.worth_listening_too'),
            '4' => Phpfox::getPhrase('musicsharing.pretty_cool'),
            '5' => Phpfox::getPhrase('musicsharing.awesome')
        );

        $arAlbum = array();
        $arRatings = array();
        // Get the default rating.
        $default_rating = 0;
        $bCheckVoted = false;

        // Get info of the song.
        if ($music_info)
        {
            $arAlbum = $oServiceMusic->getAlbumInfo($music_info['album_id']);

            $arRatings = $oServiceMusic->getVoteBySongId($music_info['song_id']);

            if (count($arRatings) > 0)
            {
                $iTotal = 0;
                foreach($arRatings as $arRating)
                {
                    $iTotal += $arRating['rating'];
                }

                $default_rating = $iTotal / count($arRatings);
            }

            $bCheckVoted = $oServiceMusic->checkVoted($music_info['song_id'], Phpfox::getUserId());
        }

        $this->template()->assign(array(
            'core_path' => phpFox::getParam('core.path'),
            'music_info' => $music_info,
            'boolAlbum' => $album,
            'user_id' => phpFox::getUserId(),
            'idplaylist' => $playlist_id,
            'idalbum' => $album_id,
            'time' => PHPFOX_TIME,
            'listofsongs' => phpFox::getPhrase('musicsharing.list_of_songs'),
            'description' => phpFox::getPhrase('musicsharing.description'),
            'name' => phpFox::getPhrase('musicsharing.name'),
            'mexpect' => "younet-empty-expect",
            'arSongs' => $arSongs,
            'strPathForMP3' => $strPathForMP3,
            'arStars' => $arStars,
            'default_rating' => $default_rating
        ));
        
        $this->template()->setHeader(
                        array(
                            //'jquery/plugin/jquery.highlightFade.js' => 'static_script',
                            'jquery/plugin/jquery.scrollTo.js' => 'static_script',
                            //'jquery/plugin/imgnotes/jquery.tag.js' => 'static_script',
                            'quick_edit.js' => 'static_script',
                            'comment.css' => 'style_css',
                            'pager.css' => 'style_css',
                            //'switch_legend.js' => 'static_script',
                            //'switch_menu.js' => 'static_script',
                            'feed.js' => 'module_feed',
                            'listen.css' => 'module_musicsharing',
                            //'listen.js' => 'module_musicsharing',
                            'musicsharing_style.css' => 'module_musicsharing',
                            'suppress_menu.css' => 'module_musicsharing',

                            'mediaelement-and-player.min.js' => 'module_musicsharing',
                            'controller_player.js' => 'module_musicsharing',
                            'mediaelementplayer.min.css' => 'module_musicsharing',
                            'mejs-audio-skins.css' => 'module_musicsharing',
                            'jquery.scrollTo-1.4.3.1-min.js' => 'module_musicsharing',
                            'slimScroll.min.js' => 'module_musicsharing',
                            'config.js' => 'module_musicsharing',
                            
                            // For Rating.
                            'jquery.rating.css' => 'style_css',
                            'jquery/plugin/star/jquery.rating.js' => 'static_script',
                            'rate.js' => 'module_musicsharing',
							'mobile.css' => 'module_musicsharing'
                        )
                )->setHeader(array(
                    '<script type="text/javascript">' .
                        '$Behavior.rateSong = function() { ' .
                            '$Core.rateForMusicSharing.init({ ' .
                                'module: \'musicsharing\', ' .
                                'display: ' . ($bCheckVoted ? 'false' : (isset($arAlbum['user_id']) && $arAlbum['user_id'] == Phpfox::getUserId() ? 'false' : 'true')) . ', ' .
                                'error_message: \'' . ($bCheckVoted ? Phpfox::getPhrase('musicsharing.you_have_already_voted', array('phpfox_squote' => true)) : Phpfox::getPhrase('musicsharing.you_cannot_rate_your_own_song', array('phpfox_squote' => true))) . '\''.
                            '}); ' .
                        '}' .
                    '</script>'))
                ->setEditor(array('load' => 'simple'));
    }
}

?>
