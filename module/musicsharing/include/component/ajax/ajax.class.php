<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');

class musicsharing_Component_Ajax_Ajax extends Phpfox_Ajax
{
    
    public function viewMoreNewPlaylistsMobile()
    {
        $iPage = $this->get('iPage', 0);
        $iLimitPerPage = Phpfox::getParam('musicsharing.mobile_view_amount_of_item_per_page_in_new_playlist');
        
        $aPlaylist = Phpfox::getService('musicsharing.music')->getPlaylists($iLimitPerPage * $iPage, $iLimitPerPage, Phpfox::getT('m2bmusic_playlist') . ".creation_date desc", "", "Where search = 1 AND (SELECT count(*) FROM " . Phpfox::getT('m2bmusic_playlist_song') . " WHERE " . Phpfox::getT('m2bmusic_playlist_song') . ".playlist_id = " . Phpfox::getT('m2bmusic_playlist') . ".playlist_id) > 0");
        
        Phpfox::getBlock('musicsharing.mobile.viewmorenewplaylistsmobile', array('aPlaylist' => $aPlaylist));
        
        if ($aPlaylist)
        {
            $this->append('.new-playlists-mobile', $this->getContent(false));
            $this->call("jQuery('.add-view-more-new-playlists-mobile').removeClass('disable');");
        }
        else
        {
            $this->call("jQuery('.add-view-more-new-playlists-mobile-text').html(oTranslations['musicsharing.there_are_no_new_results_to_view_at_this_time']);");
        }
        
        $this->call("jQuery('.add-view-more-new-playlists-mobile-text').show();");
        $this->call("jQuery('.add-view-more-new-playlists-mobile-loading').hide();");
    }
    
    
    public function viewMoreNewAlbumsMobile()
    {
        $iPage = $this->get('iPage', 0);
        $iLimitPerPage = Phpfox::getParam('musicsharing.mobile_view_amount_of_item_per_page_in_new_albums');
        
        $aNewAlbums = Phpfox::getService('musicsharing.music')->getAlbums($iLimitPerPage * $iPage, $iLimitPerPage, " " . Phpfox::getT('m2bmusic_album') . ".creation_date DESC", null, " " . Phpfox::getT('m2bmusic_album') . ".search = 1 AND (SELECT count(*) FROM " . Phpfox::getT('m2bmusic_album_song') . " WHERE " . Phpfox::getT("m2bmusic_album_song") . ".album_id = " . Phpfox::getT('m2bmusic_album') . ".album_id) > 0");
        
        Phpfox::getBlock('musicsharing.mobile.viewmorenewalbumsmobile', array('aNewAlbums' => $aNewAlbums));
        
        if ($aNewAlbums)
        {
            $this->append('.new-albums-mobile', $this->getContent(false));
            $this->call("jQuery('.add-view-more-new-albums-mobile').removeClass('disable');");
        }
        else
        {
            $this->call("jQuery('.add-view-more-new-albums-mobile-text').html(oTranslations['musicsharing.there_are_no_new_results_to_view_at_this_time']);");
        }
        
        $this->call("jQuery('.add-view-more-new-albums-mobile-text').show();");
        $this->call("jQuery('.add-view-more-new-albums-mobile-loading').hide();");
    }
    
    public function viewMoreNewSongsMobile()
    {
        $iPage = $this->get('iPage', 0);
        
        $aSetting = phpFox::getService('musicsharing.music')->getUserSettings(phpFox::getUserId(), false);
        $iLimitPerPage = isset($aSetting['number_song_per_page_widget']) ? $aSetting['number_song_per_page_widget'] : 10;
        $aSongs = phpFox::getService('musicsharing.music')->getSongs($iLimitPerPage * $iPage, $iLimitPerPage, "" . Phpfox::getT('m2bmusic_album_song') . ".song_id DESC", null, " search = 1");
        
        Phpfox::getBlock('musicsharing.mobile.viewmorenewsongsmobile', array('aSongs' => $aSongs));
        
        if ($aSongs)
        {
            $this->append('.new-songs-mobile', $this->getContent(false));
            $this->call("jQuery('.add-view-more-new-songs-mobile').removeClass('disable');");
        }
        else
        {
            $this->call("jQuery('.add-view-more-new-songs-mobile-text').html(oTranslations['musicsharing.there_are_no_new_results_to_view_at_this_time']);");
        }
        
        $this->call("jQuery('.add-view-more-new-songs-mobile-text').show();");
        $this->call("jQuery('.add-view-more-new-songs-mobile-loading').hide();");
    }
    
    public function viewMoreTopAlbumsMobile()
    {
        $iPage = $this->get('iPage', 0);
        $iLimitPerPage = Phpfox::getParam('musicsharing.mobile_view_amount_of_item_per_page_in_top_albums');
        
        $aNewAlbums = phpFox::getService('musicsharing.music')->getAlbums($iLimitPerPage * $iPage, $iLimitPerPage, " " . Phpfox::getT('m2bmusic_album') . ".play_count DESC", null, " " . Phpfox::getT('m2bmusic_album') . ".search = 1  AND (SELECT count(*) FROM ".Phpfox::getT('m2bmusic_album_song')." WHERE ".Phpfox::getT('m2bmusic_album_song').".album_id = ".Phpfox::getT('m2bmusic_album').".album_id) > 0");
        
        Phpfox::getBlock('musicsharing.mobile.viewmoretopalbumsmobile', array('aNewAlbums' => $aNewAlbums));
        
        if ($aNewAlbums)
        {
            $this->append('.top-albums-mobile', $this->getContent(false));
            $this->call("jQuery('.add-view-more-top-albums-mobile').removeClass('disable');");
        }
        else
        {
            $this->call("jQuery('.add-view-more-top-albums-mobile-text').html(oTranslations['musicsharing.there_are_no_new_results_to_view_at_this_time']);");
        }
        
        $this->call("jQuery('.add-view-more-top-albums-mobile-text').show();");
        $this->call("jQuery('.add-view-more-top-albums-mobile-loading').hide();");
    }
    
    public function viewMoreTopPlaylistsMobile()
    {
        $iPage = $this->get('iPage', 0);
        $iLimitPerPage = Phpfox::getParam('musicsharing.mobile_view_amount_of_item_per_page_in_top_playlists');
        
        $aPlaylist = phpFox::getService('musicsharing.music')->getPlaylists($iLimitPerPage * $iPage, $iLimitPerPage, Phpfox::getT('m2bmusic_playlist') . ".play_count desc", "", "Where search = 1 AND (SELECT count(*) FROM " . Phpfox::getT('m2bmusic_playlist_song') . " WHERE " . Phpfox::getT('m2bmusic_playlist_song') . ".playlist_id = " . Phpfox::getT('m2bmusic_playlist') . ".playlist_id) > 0");
        
        Phpfox::getBlock('musicsharing.mobile.viewmoretopplaylistsmobile', array('aPlaylist' => $aPlaylist));
        
        if ($aPlaylist)
        {
            $this->append('.top-playlists-mobile', $this->getContent(false));
            $this->call("jQuery('.add-view-more-top-playlists-mobile').removeClass('disable');");
        }
        else
        {
            $this->call("jQuery('.add-view-more-top-playlists-mobile-text').html(oTranslations['musicsharing.there_are_no_new_results_to_view_at_this_time']);");
        }
        
        $this->call("jQuery('.add-view-more-top-playlists-mobile-text').show();");
        $this->call("jQuery('.add-view-more-top-playlists-mobile-loading').hide();");
    }
    
    public function viewMoreTopSongsMobile()
    {
        $iPage = $this->get('iPage', 0);
        
        $aSetting = phpFox::getService('musicsharing.music')->getUserSettings(phpFox::getUserId(), false);
        $iLimitPerPage = isset($aSetting['number_song_per_page_widget']) ? $aSetting['number_song_per_page_widget'] : 10;
        
        $aSongs = phpFox::getService('musicsharing.music')->getSongs($iLimitPerPage * $iPage, $iLimitPerPage, "" . Phpfox::getT('m2bmusic_album_song') . ".play_count DESC", null, " search = 1");
        
        Phpfox::getBlock('musicsharing.mobile.viewmoretopsongsmobile', array('aSongs' => $aSongs));
        
        if ($aSongs)
        {
            $this->append('.top-songs-mobile', $this->getContent(false));
            $this->call("jQuery('.add-view-more-top-songs-mobile').removeClass('disable');");
        }
        else
        {
            $this->call("jQuery('.add-view-more-top-songs-mobile-text').html(oTranslations['musicsharing.there_are_no_new_results_to_view_at_this_time']);");
        }
        
        $this->call("jQuery('.add-view-more-top-songs-mobile-text').show();");
        $this->call("jQuery('.add-view-more-top-songs-mobile-loading').hide();");
    }
    
    public function uploadProcess()
    {
        $this->call('completeProgress();');
    }
    
    public function migrateData()
    {
        $oProcess = Phpfox::getService('musicsharing.process');
        $oMusic = phpFox::getService('musicsharing.music');
        $oDb = Phpfox::getLib('database');
        $is_migrate = false;
        $is_migrate_album = false;
        //create new column to know what records belong default music.
        $oProcess->createNewColumn(null, 'phpfox_music_id', 'int(6)');
        //create new column to know what records belong default music.
        $oProcess->createNewColumn(phpFox::getT('m2bmusic_album'), 'phpfox_album_id', 'int(6)');
        //get all albums from default music module
        $new_album_s = "";
        $aFoxAlbums = $oMusic->getAlbumsPHPFOX();

        //insert albumn to music sharing module
        list($new_album_s, $aNewAlbumList) = $oMusic->migrateAlbums($aFoxAlbums);
        $html = "";
        foreach ($aNewAlbumList as $aNewAlbumItem)
        {
            $is_migrate_album = true;
            $html .= "Migrated album " . $aNewAlbumItem['title'] . "<br/>";
        }
        $this->html('#info_process', $html);
        $this->html('#contener_pro', '<div id="contener_percent" style="background-color: fuchsia;height:16px;width:5%; padding-top: 4px;">
                   5%
                </div>');
        //insert song to music sharing module follow album
        $count_a_l = count($aNewAlbumList);
        if ($count_a_l == 0)
            $count_a_l = 1;
        $count_a_l = 90 / $count_a_l;
        $index = 1;
        foreach ($aNewAlbumList as $aNewAlbumItem)
        {
            $is_migrate_album = true;
            $per = 5 + $index * $count_a_l;
            $this->html('#info_process', "Importing songs of album " . $aNewAlbumItem['title'] . "<br/>");
            $this->html('#contener_pro', '<div id="contener_percent" style="background-color: fuchsia; height: 16px; padding-top: 4px; width:' . $per . '%">
                   ' . $per . '%
                </div>');
            //mirgrate song for albums
            list($new_album_song_s, $aNewAlbumSongList) = $oMusic->migrateAlbumSongs($aNewAlbumItem);
            //migrate comment and feed for songs
            foreach ($aNewAlbumSongList as $new_song)
            {
                $oMusic->migrateAlbumSongCommentAndFeeds($new_song);
            }
            $this->html('#info_process', Phpfox::getPhrase('musicsharing.imported_songs_of_album', array('title' => $aNewAlbumItem['title'])) . "<br/>");
        }
        //create new album for all music song not belongs to any older album album_id = 0;
        $musics = $oMusic->getMusicsPHPFOX();
        $owners = $oMusic->getOwnerMusicsPHPFOX();
        if (count($musics) > 0)
        {
            $is_migrate = true;
            foreach ($owners as $own)
            {
                $sCurrentDate = date("Y-m-d H:i:s");
                $album = array(
                    'title' => 'Unknow Album ' . $sCurrentDate,
                    'title_url' => 'Unknow Album ' . $sCurrentDate,
                    'user_id' => $own['user_id'],
                    'album_image' => '',
                    'description' => Phpfox::getPhrase('musicsharing.default_album_for_all_songs_not_belongs_to_any_older_albums'),
                    'search' => 1,
                    'is_download' => 1,
                    'creation_date' => $sCurrentDate,
                    'modified_date' => $sCurrentDate,
                    'play_count' => 0,
                    'download_count' => 0,
                    'is_featured' => 0,
                    'is_download' => 1,
                    'phpfox_album_id' => 0,
                    'order_id' => -time()
                );
                $last_insert_id_album = $oDb->insert(phpFox::getT('m2bmusic_album'), $album, array('user_id', 'title', 'title_url', 'description', 'album_image', 'search', 'creation_date', 'modified_date', 'play_count', 'download_count', 'is_featured', 'is_download', 'phpfox_album_id'));
                $title = $album['title'];
                $aAlbum = $oMusic->getAlbumMS(0);
                $this->html('#info_process', "Importing songs don't belong to any albums<br/>");
                list($new_album_song_s, $aNewAlbumSongList) = $oMusic->migrateNoAlbumSongs($aAlbum);
                if (count($aNewAlbumSongList))
                {
                    (phpFox::isModule('feed') ? phpFox::getService('feed.process')->add('musicsharing_album', $last_insert_id_album, serialize(array(
                                                'title' => phpFox::getLib('parse.input')->clean($title, 255),
                                                'album' => $album
                                                    )
                                            )
                                    ) : null);
                    foreach ($aNewAlbumSongList as $new_song)
                    {
                        $oMusic->migrateAlbumSongCommentAndFeeds($new_song);
                    }
                    $this->html('#info_process', Phpfox::getPhrase('musicsharing.imported_songs_of_album', array('title' => $aAlbum['title'])) . "<br/>");
                }
                else
                {
                    $oDb->delete(phpFox::getT('m2bmusic_album'), 'album_id = ' . $last_insert_id_album);
                    $this->html('#info_process', Phpfox::getPhrase('musicsharing.there_is_no_song_to_import_for_this_album'));
                }
            }
        }
        else
        {
            $is_migrate = false;
            $this->html('#info_process', Phpfox::getPhrase('musicsharing.there_is_no_song_to_import'));
        }
        if ($is_migrate == true || $is_migrate_album == true)
        {
            $this->html('#info_process', Phpfox::getPhrase('musicsharing.import_successfully'));
            $this->html('#contener_pro', '<div id="contener_percent" style="background-color: fuchsia;height:16px;width:100%; padding-top: 4px;">
					   100%
					</div>');
            $this->alert(Phpfox::getPhrase('musicsharing.import_successfully'));
        }
        else
        {
            if ($is_migrate == false && $is_migrate_album == false)
            {
                $this->html('#contener_pro', '<div id="contener_percent" style="background-color: fuchsia;height:16px;width:100%; padding-top: 4px;">
                       100%
                    </div>');
                $this->html('#info_process', Phpfox::getPhrase('musicsharing.there_is_no_song_to_import'));
                $this->alert(Phpfox::getPhrase('musicsharing.there_is_no_song_to_import'));
            }
        }
        $oMusic->createThumbnail();

        //finish view
    }

    public function loadSettings()
    {
        PhpFox::getBlock('musicsharing.settings', array('user_group_id' => $this->get('user_group_id')));
        $this->html('#div_settings', $this->getContent(false));
        $this->html('#loading', '');
    }

    public function addplaylist()
    {
        phpFox::isUser(true);
        $aParentModule = phpFox::getLib('session')->get('pages_msf');

        phpFox::getBlock('musicsharing.addplaylist', array(
            'iItemId' => $this->get('idsong'),
            'aParentModule' => $aParentModule,
        ));
    }

    public function editsong()
    {
        phpFox::isUser(true);

        phpFox::getBlock('musicsharing.editsong', array(
            'iItemId' => $this->get('idsong'),
            'page' => $this->get('page'),
            'album' => $this->get('album'),
            "inMySong" => $this->get('inMySong')
                )
        );
    }

    //All function Delete
    public function deletePlaylist()
    {
        phpFox::isUser(true);
        phpFox::getService('musicsharing.music')->deletePlaylist($this->get('idplaylist'));
        $this->call("window.location = window.location");
    }

    public function deleteAlbum()
    {
        phpFox::isUser(true);
        phpFox::getService('musicsharing.music')->deleteAlbum($this->get('idalbum'));
        $this->call("window.location = window.location");
    }

    public function deletePlaylistSong()
    {
        phpFox::isUser(true);
        phpFox::getService('musicsharing.music')->deletePlaylistSong($this->get('idSong'));
        $this->call("window.location = window.location");
    }

    //delete albums by string of id(s)
    public function deleteAlbums()
    {
        phpFox::isUser(true);
        phpFox::getService('musicsharing.music')->deleteAlbums($this->get('sIds'));
        $this->call("jsReload();");
    }

    public function deleteAlbumSong()
    {
        phpFox::isUser(true);

        Phpfox::getService('musicsharing.music')->deleteAlbumSong($this->get('idSong'));

        $this->call("location.reload();");
    }

    public function deleteSingerType()
    {
        phpFox::isUser(true);
        phpFox::getService('musicsharing.music')->deleteSingerType($this->get('idsingertype'));
    }

    public function deleteCategory()
    {
        phpFox::getUserParam('admincp.has_admin_access', true);
        phpFox::getService('musicsharing.music')->deleteCategory($this->get('idCategory'));
    }

    public function deleteSinger()
    {
        PhpFox::getUserParam('admincp.has_admin_access', true);
        Phpfox::getService('musicsharing.music')->deleteSinger($this->get('idsinger'));
    }

    //Other
    public function addtoplaylist()
    {
        phpFox::isUser(true);


        phpFox::getService('musicsharing.music')->addtoPlaylist($this->get('idPlaylist'), $this->get('idSong'));

        phpFox::getLib('phpfox.database')->insert(phpFox::getT('m2bmusic_song_playlist_order'), array(
            'user_id' => phpFox::getUserId(),
            'playlist_id' => $this->get('idPlaylist'),
            'song_id' => $this->get('idSong'),
            'value' => -time(),
            'album_id' => 0
        ));
    }

    public function ratingSongHTML5()
    {
        PhpFox::isUser(true);

        $songid = $this->get('song_id');

        $vote = 0;
        $arStar = $this->get('rating');
        if (count($arStar) > 0)
        {
            $vote = (int) $arStar['star'];
        }

        $result = phpFox::getService('musicsharing.music')->voteSong($songid, $vote);

        if ($result)
        {
            $this->alert(Phpfox::getPhrase('musicsharing.you_voted_successfully'));
        }
        else
        {
            $this->alert(Phpfox::getPhrase('musicsharing.you_voted_faild'));
        }

        $this->call('$Core.rateForMusicSharing.success();');
    }

    public function ratingSong()
    {
        phpFox::isUser(true);
        $songid = $this->get('item_id');
        $uid = $this->get('uid');
        $vote = $this->get('vote');
        $result = phpFox::getService('musicsharing.music')->voteSong($songid, $vote);
        if ($result)
        {
            $this->alert(Phpfox::getPhrase('musicsharing.you_voted_successfully'));
        }
        else
        {
            $this->alert(Phpfox::getPhrase('musicsharing.you_voted_faild'));
        }
    }
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
    public function downloadSong()
    {
        $song_id = $this->get('musicid');
        $music_info = phpFox::getService('musicsharing.music')->song_track_info($song_id);
        if (phpFox::getUserId() == 0)
        {
            $settings = phpFox::getService('musicsharing.music')->getSettings(0);

            if ($settings["is_public_permission"] == 0)
                phpFox::isUser(true);
            else
                $settings = phpFox::getService('musicsharing.music')->getSettings(3);
        }
        else
        {
            $settings = phpFox::getService('musicsharing.music')->getUserSettings(phpFox::getUserId());
        }
        
        $core_path = phpFox::getParam('core.path');
        if (($music_info['is_download'] != 0 || phpFox::getUserId() == $music_info['user_id']) && $settings['can_download_song'] == 1)
        {
            $sPath = Phpfox::getLib('url')->makeUrl('musicsharing.download', array('iSongId' => $music_info['song_id']));
            $sIFrame = "<iframe src='{$sPath}\'></iframe>";

            $sJavascript = "$('.iframe-download-html5-player').html(\"$sIFrame\");";

            Phpfox_error::log('Download song: ' . $sJavascript, '', 251);

            $this->call($sJavascript);
        }
        else
        {
            $this->alert(phpFox::getPhrase("musicsharing.you_do_not_have_permission_to_download_this_song"));
            $this->call('setTimeOut("tb_remove();",2000)');
        }
        return false;
    }

    public function changSongHTML5()
    {
        $song_id = $this->get('musicid');

        phpFox::getService('musicsharing.music')->service_playcount($song_id);
        $arSong = phpFox::getService('musicsharing.music')->song_track_info($song_id);

        $lyric = phpFox::getPhrase("musicsharing.no_lyric");

        $arAlbum = array();
        $arRatings = array();
        // Get the default rating.
        $default_rating = 0;
        $bCheckVoted = false;

        if ($arSong)
        {
            $arAlbum = PhpFox::getService('musicsharing.music')->getAlbumInfo($arSong['album_id']);

            if ($arSong['album_id'] > 0)
            {
                if ($arSong['lyric'] != "")
                {
                    $lyric = $arSong['lyric'];
                }
            }

            $arRatings = PhpFox::getService('musicsharing.music')->getVoteBySongId($arSong['song_id']);
            if (count($arRatings) > 0)
            {
                $iTotal = 0;
                foreach ($arRatings as $arRating)
                {
                    $iTotal += $arRating['rating'];
                }

                $default_rating = $iTotal / count($arRatings);
            }
            $bCheckVoted = PhpFox::getService('musicsharing.music')->checkVoted($song_id, Phpfox::getUserId());
        }

        $strPathForMP3 = PhpFox::getParam('core.path') . 'file/musicsharing/';
        $arStars = array(
            '1' => Phpfox::getPhrase('musicsharing.poor'),
            '2' => Phpfox::getPhrase('musicsharing.nothing_special'),
            '3' => Phpfox::getPhrase('musicsharing.worth_listening_too'),
            '4' => Phpfox::getPhrase('musicsharing.pretty_cool'),
            '5' => Phpfox::getPhrase('musicsharing.awesome')
        );

        // For share.
        header("Content-Type:text/html; charset=utf-8");
        phpFox::getLib('template')->assign(array('music_info' => $arSong))->getTemplate('musicsharing.block.sharethis', false);
        $share = $this->getContent(false);

        PhpFox::getLib('template')->assign(array(
            'strPathForMP3' => $strPathForMP3,
            'arStars' => $arStars,
            'default_rating' => $default_rating,
            'user_id' => Phpfox::getUserId(),
            'music_info' => $arSong
        ));

        $this->template()->getTemplate('musicsharing.block.rating', false);
        $strSongRating = $this->getContent(false);

        // For embed.
        $typ = ($this->get("typ") == 1) ? "idalbum" : "idplaylist";
        $nam = ($this->get("typ") == 1) ? "getalbum" : "getplaylist";

        $this->template()->assign(array(
            'music_info' => $arSong,
            "core_path" => phpFox::getParam("core.path"),
            'urldata' => phpFox::getLib('url')->makeUrl('musicsharing.data.name_' . $nam . '.' . $typ . '_' . $arSong['album_id'])
        ))->getTemplate('musicsharing.block.embed', false);

        $strEmbed = $this->getContent(false);

        $strStatus = ($bCheckVoted ? 'false' : ((isset($arAlbum['user_id']) && $arAlbum['user_id'] == Phpfox::getUserId()) ? 'false' : 'true'));

        $this->html("#share", $share);
        $this->html('#lyric_music_song .lyric-height-default', $lyric);
        $this->html('#embed_music_song', $strEmbed);
        $this->html('.change_song_rating', $strSongRating);

        $this->call(
                '$Core.rateForMusicSharing.init({ ' .
                'module: \'musicsharing\', ' .
                'display: ' . $strStatus . ', ' .
                'error_message: \'' . ($bCheckVoted ? Phpfox::getPhrase('musicsharing.you_have_already_voted', array('phpfox_squote' => true)) : Phpfox::getPhrase('musicsharing.you_cannot_rate_your_own_song', array('phpfox_squote' => true))) . '\'' .
                '}); '
        );
        $this->call('$Core.rateForMusicSharing.rebuild("' . $strStatus . '");');
        $this->call('$Behavior.init();');
    }

    public function changSongHTML5Small()
    {
        Phpfox::getService('musicsharing.music')->service_playcount($this->get('musicid'));
    }

    public function share()
    {
        $temp = ' <div class="addthis_toolbox addthis_default_style "
                 addthis:url="' . phpFox::getLib('url')->makeUrl('musicsharing.listen.music_' . $this->get('musicid')) . '"
                >
            <a class="addthis_button_preferred_1"></a>
            <a class="addthis_button_preferred_2"></a>
            <a class="addthis_button_preferred_3"></a>
            <a class="addthis_button_preferred_4"></a>
            <a class="addthis_button_compact"></a>
            </div>
            $Behavior.init = function()
                {
                     $.getScript(\'http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4e7be7602a83d379&domready=1\', function() {
                        addthis.init();
                          addthis.toolbox(".addthis_toolbox");
                      });
                }
';
        $this->html("#bookmark", $temp);
        phpFox::getBlock('musicsharing.share', array(
            'iItemId' => $this->get('musicid')
                )
        );
        $music_info = phpFox::getService('musicsharing.music')->song_track_info($this->get('musicid'));
        $lyric = phpFox::getPhrase("musicsharing.no_lyric");
        if (isset($music_info['album_id']) && $music_info['album_id'] > 0)
        {
            if ($music_info['lyric'] != "")
            {
                $lyric = "<pre>" . $music_info['lyric'] . "</pre>";
            }
        }
        $this->html("#share", $this->getContent(false) . '');
        $this->html('#lyric_music_song', $lyric);

        $this->call('$Core.init();');
    }

    //Update
    public function updateCategory()
    {
        // Check permission.
        phpFox::getUserParam('admincp.has_admin_access', true);

        // Validate data.
        if (PhpFox::getLib('parse.format')->isEmpty($this->get('quick_edit_input')))
        {
            $this->alert(Phpfox::getPhrase('musicsharing.please_enter_category_name'));

            return false;
        }

        // Update category.
        if (PhpFox::getService('musicsharing.music')->updateCategory($this->get('cat_id'), $this->get('quick_edit_input')))
        {
            $this->html('#' . $this->get('id'), '<a href="#?type=input&amp;id=js_blog_edit_title' . $this->get('cat_id') . '&amp;content=js_category' . $this->get('cat_id') . '&amp;call=musicsharing.updateCategory&amp;cat_id=' . $this->get('cat_id') . '" class="quickEdit" id="js_category' . $this->get('cat_id') . '">' . Phpfox::getLib('parse.input')->clean($this->get('quick_edit_input')) . '</a>')
                    ->call('$Core.loadInit();');
        }
    }

    public function updateSingerType()
    {
        phpFox::getUserParam('admincp.has_admin_access', true);

        if (phpFox::getLib('parse.format')->isEmpty($this->get('quick_edit_input')))
        {
            $this->alert(Phpfox::getPhrase('musicsharing.please_enter_singer_type_name'));
            return false;
        }

        if (Phpfox::getService('musicsharing.music')->updateSingerType($this->get('type_id'), $this->get('quick_edit_input')))
        {
            $this->html('#' . $this->get('id'), '<a href="#?type=input&amp;id=js_blog_edit_title' . $this->get('type_id') . '&amp;content=js_category' . $this->get('type_id') . '&amp;call=musicsharing.updateSingerType&amp;type_id=' . $this->get('type_id') . '" class="quickEdit" id="js_category' . $this->get('type_id') . '">' . phpFox::getLib('parse.input')->clean($this->get('quick_edit_input')) . '</a>')
                    ->call('$Core.loadInit();');
        }
    }

    public function updateSingerTitle()
    {
        PhpFox::getUserParam('admincp.has_admin_access', true);

        if (PhpFox::getLib('parse.format')->isEmpty($this->get('quick_edit_input')))
        {
            $this->alert(Phpfox::getPhrase('musicsharing.please_enter_singer_name'));
            return false;
        }
        if (Phpfox::getService('musicsharing.music')->updateSinger($this->get('singer_id'), $this->get('quick_edit_input')))
        {
            $this->html('#' . $this->get('id'), '<a href="#?type=input&amp;id=js_blog_edit_title' . $this->get('singer_id') . '&amp;content=js_category' . $this->get('singer_id') . '&amp;call=musicsharing.updateSingerTitle&amp;singer_id=' . $this->get('singer_id') . '" class="quickEdit" id="js_category' . $this->get('singer_id') . '">' . phpFox::getLib('parse.input')->clean($this->get('quick_edit_input')) . '</a>')
                    ->call('$Core.loadInit();');
        }
    }

    /**
     * This function is used to delete an image of a singer.
     */
    public function deleteSingerImage()
	{
		$iSingerId = (int)$this->get('iSingerId');
		if (Phpfox::getService('musicsharing.process')->deleteSingerImage($iSingerId, Phpfox::getUserId()))
		{
			$this->call('$("#singer_image").show();');
			$this->call('$(".musicsharing-singer-image").remove();');
		}
		else
		{
			$this->call('$(".musicsharing-singer-image").after("' . Phpfox::getPhrase('musicsharing.an_error_occured_and_your_image_could_not_be_deleted_please_try_again') . '");');
		}
	}

    public function requestmoney()
    {
        //$date=date('Y-m-d');
        //$timestamp=strtotime($date);
        $current_money = $this->get('currentmoney');
        if (!is_numeric($current_money))
            $current_money = -10;
        $warning = 0;

        if ((round($current_money, 2) - $current_money != 0) || $current_money <= 0)
        {
            $this->alert(Phpfox::getPhrase('musicsharing.invalid_request_number'));
            $warning = 1;
            return false;
        }

        $user_group_id = phpFox::getService('musicsharing.cart.account')->getUserGroupId(phpFox::getUserId());
        $info_sellingsettings = phpFox::getService("musicsharing.cart.account")->getSellingSettings($user_group_id);
        $info_account = phpFox::getService("musicsharing.cart.account")->getCurrentAccount(phpFox::getUserId());
        $TotalRequest = phpFox::getService("musicsharing.cart.account")->getTotalRequest(phpFox::getUserId());

        $min_payout = $info_sellingsettings['min_payout'];
        $max_payout = $info_sellingsettings['max_payout'];
        $allow_request = 0;

        if (round(($info_account['total_amount'] - $TotalRequest - $min_payout), 2) >= round($current_money, 2))
        {
            //$this->alert($TotalRequest);
            if ($current_money != -10 && $current_money > 0)
            {
                if ($max_payout == -1 || $max_payout >= $current_money)
                {
                    $allow_request = 1;
                }
            }
        }
        else
        {
            $warning = 1;
            $this->alert("You have requested " . round($TotalRequest, 2) . " " . phpFox::getService('core.currency')->getDefault() . " before,so you only can request maximum is " . round($info_account['total_amount'] - $TotalRequest - $min_payout, 2) . " " . phpFox::getService('core.currency')->getDefault() . ".", "Warning");
        }
        if ($allow_request == 1)
        {
            $vals = array();
            $vals['request_user_id'] = phpFox::getUserId();
            $vals['request_amount'] = round($current_money, 2);
            $vals['request_date'] = time();
            $vals['request_reason'] = $this->get('reason');
            $vals['request_status'] = 0;
            $vals['request_payment_acount_id'] = $info_account['payment_account_id'];
            $request_id = phpFox::getService("musicsharing.cart.account")->insertRequest($vals);
            //$update=phpFox::getService("musicsharing.cart.music")->updateTotalAmount($request_id,$info_account['total_amount']);
            $info_account = phpFox::getService("musicsharing.cart.account")->getCurrentAccount(phpFox::getUserId());
            //$this->html("#current_money", $info_account['total_amount']);
            echo "tb_remove();";
            $this->alert("Request successfully!", "Notice");
            $this->html('#current_request_money', round($TotalRequest + $current_money, 2));
            $this->html('#current_money_money', round($info_account['total_amount'] - $TotalRequest - $current_money, 2));
        }
        else if ($warning != 1)
        {
            $this->alert("Request false!", "Warning");
        }
    }

    //truc
    public function musictopsongs()
    {
        phpFox::getBlock('musicsharing.topsongs-front-end');
        $ajxContent = $this->getContent(false);
        $ajxContent = str_replace("\"", "\\\"", $ajxContent);
        $this->call("$(\"#song_list_frame\").html($(\"$ajxContent\").children());");
        // $this->html('#song_list_frame', $ajxContent);
        $this->call("_js_topsong_home = $('#song_list_frame').html();");
    }

    public function musicnewsongs()
    {
        phpFox::getBlock('musicsharing.newsongs-front-end');
        $ajxContent = $this->getContent(false);
        $ajxContent = str_replace("\"", "\\\"", $ajxContent);
        $this->call("$(\"#song_list_frame\").html($(\"$ajxContent\").children());");
        $this->call("_js_newsong_home = $('#song_list_frame').html();");
    }

    public function musictopplaylists()
    {
        phpFox::getBlock('musicsharing.topplaylists');
        $ajxContent = $this->getContent(false);
        $ajxContent = str_replace("\"", "\\\"", $ajxContent);
        $this->call("$(\"#js_newtopplaylist\").html($(\"$ajxContent\").children());");
        $this->call("_js_toppl_home = $('#js_newtopplaylist').html();");
    }

    public function musicnewplaylists()
    {
        phpFox::getBlock('musicsharing.newplaylists');
        $ajxContent = $this->getContent(false);
        $ajxContent = str_replace("\"", "\\\"", $ajxContent);
        $this->call("$(\"#js_newtopplaylist\").html($(\"$ajxContent\").children());");
        $this->call("_js_newpl_home = $('#js_newtopplaylist').html();");
    }

    public function editSong_proc()
    {
        $aSong = array();
        $aSong['title'] = $this->get('songTitle', Phpfox::getPhrase('musicsharing.not_updated'));
        $aSong['title_url'] = $this->get('songTitle', Phpfox::getPhrase('musicsharing.not_updated'));
        $aSong['singer_id'] = $this->get('check_other_singer') ? 0 : $this->get('songSinger');
        $aSong['other_singer'] = $this->get('check_other_singer') ? ($this->get('songSingerName') ? $this->get('songSingerName') : Phpfox::getPhrase('musicsharing.not_updated')) : '';
        $aSong['cat_id'] = $this->get('songCat');
        $aSong['song_id'] = $this->get('song_id');
        $aSong['lyric'] = $this->get('songLyric');
        $aSong['privacy'] = $this->get('privacy');

        Phpfox::getService('musicsharing.music')->updateAlbumSong($aSong);

        $this->call("window.location = window.location;");
    }

}

?>
