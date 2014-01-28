<?php

require_once("m2b_music_lib/AlbumController.php");
require_once("m2b_music_lib/PlaylistController.php");
require_once("m2b_music_lib/AlbumSongController.php");
require_once("m2b_music_lib/PlaylistSongController.php");
require_once("m2b_music_lib/CategoryController.php");
require_once("m2b_music_lib/SingerController.php");
require_once("m2b_music_lib/SingerTypeController.php");
require_once("m2b_music_lib/SettingsController.php");

class Musicsharing_Service_Music extends Phpfox_Service {

    public function replaceTitle($str) {
        $str = preg_replace("/(&)/", "&amp;", $str);
        $str = preg_replace("/( )/", "&nbsp;", $str);
        $str = preg_replace("/(\")/", "&quot;", $str);
        $str = preg_replace("/(\')/", "&apos; ", $str);
        $str = preg_replace("/(<)/", "&lt;", $str);
        $str = preg_replace("/(>)/", "&gt;", $str);
        return $str;
    }
    
    public function prefix()
    {
        return Phpfox::getParam(array('db', 'prefix'));
    }
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
    
    public function embedServiceGetPlaylist($idplaylist, $user_log)
    {
        $sql = "SELECT ".$this->prefix()."m2bmusic_album.title as album_title,".$this->prefix()."user.user_id,".$this->prefix()."user.user_name,".$this->prefix()."user.full_name,".$this->prefix()."user.user_image,".$this->prefix()."m2bmusic_album_song.*,".$this->prefix()."m2bmusic_album.is_download 
            FROM ".$this->prefix()."m2bmusic_album_song 
            Left join ".$this->prefix()."m2bmusic_playlist_song on ".$this->prefix()."m2bmusic_album_song.song_id =  ".$this->prefix()."m2bmusic_playlist_song.album_song_id Left join ".$this->prefix()."m2bmusic_album on ".$this->prefix()."m2bmusic_album_song.album_id =  ".$this->prefix()."m2bmusic_album.album_id ";
        $sql.=" LEFT JOIN ".$this->prefix()."m2bmusic_song_playlist_order ON (".$this->prefix()."m2bmusic_album_song.song_id  = ".$this->prefix()."m2bmusic_song_playlist_order.song_id AND ".$this->prefix()."m2bmusic_song_playlist_order.user_id = ".phpfox::getUserId()." AND ".$this->prefix()."m2bmusic_song_playlist_order.playlist_id  = $idplaylist" .")"  ;
        $sql.=" LEFT JOIN ".$this->prefix()."user ON ".$this->prefix()."user.user_id = ".$this->prefix()."m2bmusic_album.user_id ";
        $sql.=" WHERE ".$this->prefix()."m2bmusic_playlist_song.playlist_id = $idplaylist";
        $sql.=" ORDER BY value ASC";
        $aSongs = $this->executeSql($sql);
        
        $sqlPlaylist = "SELECT ".$this->prefix()."m2bmusic_playlist.*,".$this->prefix()."user.* FROM ".$this->prefix()."m2bmusic_playlist JOIN ".$this->prefix()."user ON ".$this->prefix()."m2bmusic_playlist.user_id = ".$this->prefix()."user.user_id WHERE ".$this->prefix()."m2bmusic_playlist.playlist_id = $idplaylist";
        
        $aPlaylist = $this->executeSql($sqlPlaylist, true);
        
        if (!$aPlaylist)
        {
            $xmlstr = "<?xml version='1.0' encoding='utf-8'?>\n"."<data></data>";
            $xml = new SimpleXMLElement($xmlstr);
            $name = $xml->addChild("name", "PLAYLIST IS NOT EXIST");
            header("Content-type: text/xml; charset: utf-8");
            echo $xml->asXML();
            return;
        }
        
        $xmlstr = "<?xml version='1.0' encoding='utf-8'?>\n"."<data></data>";
        $xml = new SimpleXMLElement($xmlstr);
        
        $xml->addChild("userid", $aPlaylist["user_id"]);
        
        $user_id = phpfox::getUserId();
        $songs = $xml->addChild("songs");
       
        foreach ($aSongs as $aSong)
        {
            $song =  $songs->addChild("song");
            $song->addChild("id", $aSong["song_id"]);
            $song_id = $aSong["song_id"];
			$name = str_replace("&nbsp;" , " " , $aSong["title"]);
			$name = str_replace("&apos;" , " " , $name);
            $song->addChild("name", $name);
            
            $sqlrate = "SELECT ".$this->prefix()."m2bmusic_song_rating.* FROM ".$this->prefix()."m2bmusic_song_rating  where item_id = $song_id and user_id = $user_id";
            $aRating = $this->executeSql($sqlrate);
            
			$isVote = "true";
            if ($aRating)
            {
				$song->addChild("vote", number_format($aRating['rating']));  
				if(number_format($aRating['rating']) != 0){
					$isVote = "false";
				}
            }
            else 
            {   
                $sqlrates = "SELECT ".$this->prefix()."m2bmusic_song_rating.* FROM ".$this->prefix()."m2bmusic_song_rating  where item_id = $song_id";
                $aRatings = $this->executeSql($sqlrates);
                $avgRate = 0;
                foreach ($aRatings as $aRating)
                {
                   $avgRate += $aRating['rating']; 
                 
                }
                if(count($aRatings))
                {
                    $avgRate = floor($avgRate/count($aRatings));
                }            
                $song->addChild("vote", $avgRate);
            }
            $song->addChild("playcount", $aSong["play_count"]);        
            $song->addChild("download", $aSong["download_count"]);
            
            $song->addChild("isvote", $isVote);
            
            // Support CDN.
            if (isset($aSong['phpfox_music_id']) && $aSong['phpfox_music_id'] > 0)
            {
                $sSongPath = $this->getSongPath($aSong["url"], $aSong['server_id']);
            }
            else
            {
                $sSongPath = $this->getSongPathForCDN($aSong["url"], $aSong['server_id']);
            } 
            
            $song->addChild("path", $sSongPath);
                        
            $is_download = $aSong['is_download'];
            if ($aPlaylist['is_download'] == 0){
                $is_download = "false";
			}else{
				$is_download = "true";
			}
            $song->addChild("isdownload", $is_download);
            //if ($plrs)
            $song->addChild("artist", $this->replaceTitle($aSong["user_name"]));
            $song->addChild("albumid", $aSong['album_id']);
            if(strlen($aSong["album_title"])>40){$aSong["title"]=substr($aSong["album_title"],0,37)."..."; }
            
			$atitle = str_replace("&nbsp;" , " " , $aSong["album_title"]);
			$atitle = str_replace("&apos;" , "'" , $atitle);
			
            $song->addChild("albumname", $atitle);
            
			$song->addChild("isadd", "true");
            $song->addChild("iscart", "false");
        }
		ob_clean();
        header("Content-type: text/xml; charset: utf-8");
        
        $xmlData = $xml->asXML();
        echo substr($xmlData, strrpos($xmlData, "<?xml version=\"1.0\" encoding=\"utf-8\"?>"));
        return;
    }    
    
    public function embedServiceGetAlbum($idalbum, $user_log)
    {
        $sSongQuery = $this->database()->select("song.*, rating.rating")
                ->from(Phpfox::getT('m2bmusic_album_song'), 'song')
                ->leftJoin(Phpfox::getT('m2bmusic_song_playlist_order'), 'ordering', 'song.song_id = ordering.song_id AND ordering.user_id = '.$user_log . ' AND ordering.album_id  = ' . $idalbum)
                ->leftJoin(Phpfox::getT('m2bmusic_song_rating'), 'rating', 'rating.item_id = song.song_id and rating.user_id = ' . $user_log)
                ->where('song.album_id = ' . $idalbum)
                ->order('ordering.value ASC')
                ->execute();
        
        $aSongs = $this->executeSql($sSongQuery);
        
        $sAlbumQuery = $this->database()
                ->select('album.*, user.*')
                ->from(Phpfox::getT('m2bmusic_album'), 'album')
                ->join(Phpfox::getT('user'), 'user', 'album.user_id = user.user_id')
                ->where('album.album_id = ' . $idalbum)
                ->execute();
        
        $aAlbum = $this->executeSql($sAlbumQuery, true);
        if (!$aAlbum)
        {
            $xmlstr = "<?xml version='1.0' encoding='utf-8' ?>\n"."<data></data>";
            
            $xml = new SimpleXMLElement($xmlstr);
            $name = $xml->addChild("name", "ALBUM IS NOT EXIST");
            header("Content-type: text/xml; charset: utf-8");
            echo $xml->asXML();
            return;
        }
        $xmlstr = "<?xml version='1.0' encoding='utf-8' ?>\n"."<data></data>";
        $xml = new SimpleXMLElement($xmlstr);
        $albumuser = $xml->addChild("userid", $aAlbum["user_id"]);
        $songs = $xml->addChild("songs");
        
        foreach($aSongs as $aSong)
        {
            $song =  $songs->addChild("song");
            $song->addChild("id", $aSong["song_id"]);
            $song_id = $aSong["song_id"];
            $name = str_replace("&nbsp;" , " " , $aSong["title"]);
			$name = str_replace("&apos;" , " " , $name);
            $song->addChild("name", $name);
            $isVote = "true";
            if ($aSong['rating'] > 0) 
            {
                $song->addChild("vote", number_format($aSong['rating']));
                if(number_format($aSong['rating']) != 0)
                {
                    $isVote = "false";
                }
            }
            else
            {
                $sqlrates = "SELECT ".$this->prefix()."m2bmusic_song_rating.* 
                    FROM ".$this->prefix()."m2bmusic_song_rating  
                        where item_id = $song_id";
                $aRatings = $this->executeSql($sqlrates);
                $avgRate = 0;
                foreach($aRatings as $aRating)
                {
                   $avgRate += $aRating['rating'];
                }
                if (count($aRatings))
                {
                    $avgRate = floor($avgRate/count($aRatings));
                }
                $song->addChild("vote", $avgRate);
            }
            
            $song->addChild("playcount", $aSong["play_count"]);
            $song->addChild("download", $aSong["download_count"]);
			$song->addChild("isvote", $isVote);
            
            // Support CDN.
            if (isset($aSong['phpfox_music_id']) && $aSong['phpfox_music_id'] > 0)
            {
                $sSongPath = $this->getSongPath($aSong["url"], $aSong['server_id']);
            }
            else
            {
                $sSongPath = $this->getSongPathForCDN($aSong["url"], $aSong['server_id']);
            } 
            $song->addChild("path", $sSongPath);
			
            $is_download = $aAlbum['is_download'];
            if ($aAlbum['is_download'] == 0){
                $is_download = "false";
			}else{
				$is_download = "true";
			}

            $song->addChild("isdownload", $is_download);
            $song->addChild("artist", $this->replaceTitle($aAlbum["user_name"]));
            $song->addChild("albumid", $idalbum);
            if(strlen($aAlbum["title"])>40){$aAlbum["title"]=substr($aAlbum["title"],0,37)."..."; }

			$atitle = str_replace("&nbsp;" , " " , $aAlbum["title"]);
			$atitle = str_replace("&apos;" , " " , $atitle);
			
            $song->addChild("albumname", $atitle);
            $song->addChild("isadd", "true");
            $song->addChild("iscart", "false");
        }
        
		ob_clean();
        header("Content-type: text/xml; charset: utf-8");
        
        $xmlData = $xml->asXML();
        echo substr($xmlData, strrpos($xmlData, "<?xml version=\"1.0\" encoding=\"utf-8\"?>"));
        return;
    }
    
    public function updateDownloadCount($iSongId)
    {
        $oDb = $this->database();
        
        $sSql = "UPDATE " . Phpfox::getT('m2bmusic_album_song') . " SET download_count = download_count + 1 WHERE song_id = " . (int) $iSongId;
        $oDb->query($sSql);
        
        $sSqlAlbum = "UPDATE ". Phpfox::getT('m2bmusic_album') . " SET download_count = download_count + 1 WHERE album_id = (select album_id from " . Phpfox::getT('m2bmusic_album_song') . " where song_id = " . (int) $iSongId.")";
        $oDb->query($sSqlAlbum);
    }
    public function checkPerPage($item_id, $action)
    {
        $aPers = phpFox::getService('musicsharing.callback')->getPagePerms();
        if (count($aPers) <= 0)
        {
            return true;
        }
        foreach ($aPers as $k => $p)
        {
            if (phpFox::getService('pages')->hasPerm($item_id, 'musicsharing.can_manage_album'))
            {
                return false;
            }
        }
    }

    /*     * *****order songs in playlist****** */

    public function reOrderPlaylistSong($playlist_id, $user_id, $is_playlist = true)
    {
        if ($is_playlist == true)
        {
            //delete all order of this playlist
            phpFox::getLib('phpfox.database')->delete(phpFox::getT('m2bmusic_song_playlist_order'), 'user_id = ' . $user_id . ' AND playlist_id =' . $playlist_id);
            
            $pl_songs = $this->getPlaylistSongs($playlist_id);

            $insert_item = array();

            foreach ($pl_songs as $key => $item)
            {
                $insert_item[] = array($user_id, $playlist_id, 0, $item['song_id'], $key + 1
                );
            }
            phpFox::getLib('phpfox.database')->multiInsert(phpFox::getT('m2bmusic_song_playlist_order'), array('user_id', 'playlist_id', 'album_id', 'song_id', 'value'), $insert_item);
        }
        else
        {
            //delete all order of this playlist
            phpFox::getLib('phpfox.database')->delete(phpFox::getT('m2bmusic_song_playlist_order'), 'user_id = ' . $user_id . ' AND album_id =' . $playlist_id);
            $prefix = phpFox::getParam(array('db', 'prefix'));

            $where = " " . $prefix . "m2bmusic_album_song.album_id = " . $playlist_id;
            $pl_songs = $this->getSongs(null, null, null, null, $where);
            
            $insert_item = array();
            foreach ($pl_songs as $key => $item)
            {
                $insert_item[] = array($user_id, 0, $playlist_id, $item['song_id'], $key + 1
                );
            }

            phpFox::getLib('phpfox.database')->multiInsert(phpFox::getT('m2bmusic_song_playlist_order'), array('user_id', 'playlist_id', 'album_id', 'song_id', 'value'), $insert_item);
        }
    }

    public function switchOrderSongs($order_song_up, $song_current)
    {
        phpFox::getLib('phpfox.database')->update(phpFox::getT('m2bmusic_song_playlist_order'), array('song_id' => $order_song_up['song_id']), 'order_id = ' . $song_current['order_id']);
        phpFox::getLib('phpfox.database')->update(phpFox::getT('m2bmusic_song_playlist_order'), array('song_id' => $song_current['song_id']), 'order_id = ' . $order_song_up['order_id']);
    }

    public function getSongDown($song_id, $playlist_id, $user_id, $is_playlist = true)
    {
        if ($is_playlist == true)
        {
            $current_song = phpFox::getLib('phpfox.database')
                    ->select('*')
                    ->from(phpFox::getT('m2bmusic_song_playlist_order'))
                    ->where('song_id = ' . (int) $song_id . ' AND playlist_id = ' . (int) $playlist_id . " AND user_id = " . (int) $user_id)
                    ->execute('getRow');
            
            $playlist_songs = phpFox::getLib('phpfox.database')
                    ->select('*')
                    ->from(phpFox::getT('m2bmusic_song_playlist_order'))
                    ->where('playlist_id = ' . (int) $playlist_id . " AND user_id = " . (int) $user_id)
                    ->order('value ASC')
                    ->execute('getRows');
            
            if (count($playlist_songs) > 0)
            {
                $length = count($playlist_songs);
                for ($i = 0; $i < $length; $i++)
                {

                    if ($playlist_songs[$i]['song_id'] == $song_id)
                    {
                        if ($i == $length - 1)
                            return array($current_song, $current_song);
                        else
                            return array($playlist_songs[$i + 1], $current_song);
                    }
                }
            }
            else
            {
                return array(null, null);
            }
        }
        else
        {

            $current_song = phpFox::getLib('phpfox.database')->select('*')
                    ->from(phpFox::getT('m2bmusic_song_playlist_order'))
                    ->where('song_id = ' . $song_id . ' AND album_id = ' . $playlist_id . " AND user_id = " . $user_id)
                    ->execute('getRow');
            $playlist_songs = phpFox::getLib('phpfox.database')->select('*')
                    ->from(phpFox::getT('m2bmusic_song_playlist_order'))
                    ->where('album_id = ' . $playlist_id . " AND user_id = " . $user_id)
                    ->order('value ASC')
                    ->execute('getRows');

            if (count($playlist_songs) > 0)
            {
                $length = count($playlist_songs);
                for ($i = 0; $i < $length; $i++)
                {

                    if ($playlist_songs[$i]['song_id'] == $song_id)
                    {
                        if ($i == $length - 1)
                            return array($current_song, $current_song);
                        else
                            return array($playlist_songs[$i + 1], $current_song);
                    }
                }
            }
            else
            {
                return array(null, null);
            }
        }
    }

    public function getSongUp($song_id, $playlist_id, $user_id, $is_playlist = true)
    {
        if ($is_playlist == true)
        {
            $current_song = phpFox::getLib('phpfox.database')->select('*')
                    ->from(phpFox::getT('m2bmusic_song_playlist_order'))
                    ->where('song_id = ' . $song_id . ' AND playlist_id = ' . $playlist_id . " AND user_id = " . $user_id)
                    ->execute('getRow');
            $playlist_songs = phpFox::getLib('phpfox.database')->select('*')
                    ->from(phpFox::getT('m2bmusic_song_playlist_order'))
                    ->where('playlist_id = ' . $playlist_id . " AND user_id = " . $user_id)
                    ->order('value ASC')
                    ->execute('getRows');

            if (count($playlist_songs) > 0)
            {
                $length = count($playlist_songs);
                for ($i = 0; $i < $length; $i++)
                {

                    if ($playlist_songs[$i]['song_id'] == $song_id)
                    {
                        if ($i == 0)
                            return array($current_song, $current_song);
                        else
                            return array($playlist_songs[$i - 1], $current_song);
                    }
                }
            }
            else
                return array(null, null);
        }else
        {

            $current_song = phpFox::getLib('phpfox.database')->select('*')
                    ->from(phpFox::getT('m2bmusic_song_playlist_order'))
                    ->where('song_id = ' . $song_id . ' AND album_id = ' . $playlist_id . " AND user_id = " . $user_id)
                    ->execute('getRow');
            $playlist_songs = phpFox::getLib('phpfox.database')->select('*')
                    ->from(phpFox::getT('m2bmusic_song_playlist_order'))
                    ->where('album_id = ' . $playlist_id . " AND user_id = " . $user_id)
                    ->order('value ASC')
                    ->execute('getRows');

            if (count($playlist_songs) > 0)
            {
                $length = count($playlist_songs);
                for ($i = 0; $i < $length; $i++)
                {

                    if ($playlist_songs[$i]['song_id'] == $song_id)
                    {
                        if ($i == 0)
                            return array($current_song, $current_song);
                        else
                            return array($playlist_songs[$i - 1], $current_song);
                    }
                }
            }
            else
                return array(null, null);
        }
    }

    //end
    /*     * *Migrate Data *** */
    public function getAlbumMS($album_id = 0)
    {
        if ($album_id == null)
            $album_id = 0;
        $musics = $this->database()
                ->select('*')
                ->from(phpFox::getT('m2bmusic_album'))
                ->where('phpfox_album_id = ' . $album_id)
                ->order('album_id DESC')
                ->execute('getRows');

        return $musics[0];
    }

    public function getMusicSongCommentsPHPFOX($song_id = 0)
    {
        $comments = $this->database()
                ->select('*')
                ->from(phpFox::getT('comment'), 'c')
                ->leftJoin(phpFox::getT('comment_text'), 'ct', 'ct.comment_id = c.comment_id')
                ->where('c.item_id = ' . $song_id . ' AND type_id = "music_song" ')
                ->execute('getRows');
        return $comments;
    }

    public function getMusicSongFeedsPHPFOX($song_id = 0)
    {
        $comments = $this->database()
                ->select('*')
                ->from(phpFox::getT('feed'), 'f')
                ->where('f.item_id = ' . $song_id . ' AND type_id = "comment_music_song" ')
                ->execute('getRows');
        return $comments;
    }

    public function getMusicsPHPFOX($iAlbumId = 0)
    {
        $aM2bMusic = $this->database()
                ->select('*')
                ->from(phpFox::getT('m2bmusic_album_song'), 'ms')
                ->where('phpfox_music_id > 0')
                ->execute('getRows');
        
        $sNotInList = "(-1";
        foreach ($aM2bMusic as $aItem)
        {
            $sNotInList .= "," . $aItem['phpfox_music_id'];
        }
        $sNotInList .= ")";
        
        if ($iAlbumId == null)
            $iAlbumId = 0;
        
        return $this->database()
                ->select('*')
                ->from(phpFox::getT('music_song'), 'ms')
                ->where('ms.album_id = ' . $iAlbumId . ' AND song_id not IN ' . $sNotInList)
                ->execute('getRows');
    }

    public function getOwnerMusicsPHPFOX($album_id = 0)
    {
        if ($album_id == null)
            $album_id = 0;
        $musics = $this->database()
                ->select('user_id')
                ->from(phpFox::getT('music_song'))
                ->where('album_id = ' . $album_id)
                ->group('user_id')
                ->execute('getRows');

        return $musics;
    }

    public function getAlbumsPHPFOX()
    {
        return $this->database()
                ->select('*')
                ->from(phpFox::getT('music_album'), 'ma')
                ->leftjoin(phpFox::getT('music_album_text'), 'mat', 'mat.album_id  =  ma.album_id')
                ->execute('getRows');
    }

    public function checkExist($table = null, $resource_id, $column)
    {
        $result = $this->database()
                ->select('*')
                ->from($table)
                ->where($column . '= ' . $resource_id)
                ->execute('getRow');
        
        if ($result != null)
            return true;
        else
            return false;
    }
    /**
     * @param type $aNewAlbum
     * @return type
     */
    public function migrateNoAlbumSongs($aNewAlbum)
    {
        $aInsertItem = array();
        $sNewMusicAlbumList = "( ";
        $aFoxMusicList = $this->getMusicsPHPFOX($aNewAlbum['phpfox_album_id']);
        
        foreach ($aFoxMusicList as $aItem)
        {
            if ($this->checkExist(phpFox::getT('m2bmusic_album_song'), $aItem['song_id'], 'phpfox_music_id') == false && $aItem['user_id'] == $aNewAlbum['user_id'])
            {
                $sFullPath = phpFox::getParam('core.dir_file') . 'music' . PHPFOX_DS . sprintf($aItem['song_path'], '');
                $iFileSize = (int) @filesize($sFullPath);
                
                $aInsertItem[] = array(
                    $aItem['server_id'],
                    $aItem['title'], 
                    $aNewAlbum['album_id'], 
                    $iFileSize, 
                    str_replace('%s', '', $aItem['song_path']), 
                    'mp3', 
                    '', 
                    0, 
                    0, 
                    '', 
                    '', 
                    $aItem['total_comment'], 
                    $aItem['total_play'], 
                    0, 
                    $aItem['song_id']);
                
                $sNewMusicAlbumList .= $aItem['song_id'] . ",";
            }
        }
        $sNewMusicAlbumList .= "-1 )";
        
        $oDb = $this->database();
        if ($aInsertItem)
        {
            $oDb->multiInsert(
                    phpFox::getT('m2bmusic_album_song'), 
                    array(
                        'server_id',
                        'title', 
                        'album_id', 
                        'filesize', 
                        'url', 
                        'ext', 
                        'lyric', 
                        'cat_id', 
                        'singer_id', 
                        'other_singer', 
                        'other_singer_title_url', 
                        'comment_count', 
                        'play_count', 
                        'download_count', 
                        'phpfox_music_id'), 
                    $aInsertItem);
        }
        $results = $oDb->select('*')
                ->from(phpFox::getT('m2bmusic_album_song'))
                ->where('phpfox_music_id IN ' . $sNewMusicAlbumList)
                ->execute('getRows');
        
        return array($sNewMusicAlbumList, $results);
    }

    public function migrateAlbums($aFoxAlbums)
    {
        $aInsertItem = array();
        $sNewAlbumList = "( ";
        foreach ($aFoxAlbums as $aAlbum)
        {
            if ($this->checkExist(phpFox::getT('m2bmusic_album'), $aAlbum['album_id'], 'phpfox_album_id') == false)
            {
                if (!isset($aAlbum['name_url']))
                {
                    $aAlbum['name_url'] = "";
                }
                $aInsertItem[] = array(
                    $aAlbum['server_id'],
                    $aAlbum['user_id'], 
                    $aAlbum['name'], 
                    $aAlbum['name_url'], 
                    $aAlbum['text'], 
                    $aAlbum['image_path'], 
                    1, 
                    date("Y-m-d H:i:s"), 
                    date("Y-m-d H:i(worry)"),
                    $aAlbum['total_play'], 
                    0, 
                    $aAlbum['is_featured'], 
                    1, 
                    $aAlbum['album_id'], 
                    -time(), 
                    $aAlbum['privacy'], 
                    $aAlbum['privacy_comment']
                );
                $sNewAlbumList .= $aAlbum['album_id'] . ",";
            }
        }
        $sNewAlbumList .= "-1 )";
        if (count($aInsertItem) > 0)
        {
            $this->database()->multiInsert(
                    Phpfox::getT('m2bmusic_album'), 
                    array(
                        'server_id',
                        'user_id', 
                        'title', 
                        'title_url', 
                        'description', 
                        'album_image', 
                        'search', 
                        'creation_date', 
                        'modified_date', 
                        'play_count', 
                        'download_count', 
                        'is_featured', 
                        'is_download', 
                        'phpfox_album_id', 
                        'order_id', 
                        'privacy', 
                        'privacy_comment'), $aInsertItem);
        }
        $aResults = $this->database()
                ->select('*')
                ->from(phpFox::getT('m2bmusic_album'))
                ->where('phpfox_album_id IN ' . $sNewAlbumList)
                ->execute('getRows');
        return array($sNewAlbumList, $aResults);
    }

    public function migrateAlbumSongs($aNewAlbum)
    {
        $aInsertItem = array();
        $sNewMusicAlbumList = "( ";
        $aFoxMusicList = $this->getMusicsPHPFOX($aNewAlbum['phpfox_album_id']);
        foreach ($aFoxMusicList as $aFoxMusic)
        {
            if ($this->checkExist(phpFox::getT('m2bmusic_album_song'), $aFoxMusic['song_id'], 'phpfox_music_id') == false)
            {
                $sFullPath = phpFox::getParam('core.dir_file') . 'music' . PHPFOX_DS . sprintf($aFoxMusic['song_path'], '');
                $iFileSize = (int) @filesize($sFullPath);

                $aInsertItem[] = array(
                    $aFoxMusic['title'], 
                    $aNewAlbum['album_id'], 
                    $iFileSize, 
                    $aFoxMusic['song_path'], 
                    'mp3', 
                    '', 
                    0, 
                    0, 
                    '', 
                    '', 
                    $aFoxMusic['total_comment'], 
                    $aFoxMusic['total_play'], 
                    0,
                    $aFoxMusic['song_id']);
                
                $sNewMusicAlbumList .= $aFoxMusic['song_id'] . ",";
            }
        }
        $sNewMusicAlbumList .= "-1 )";
        if (count($aInsertItem) > 0)
        {
            $this->database()->multiInsert(
                    phpFox::getT('m2bmusic_album_song'), 
                    array(
                        'title', 
                        'album_id', 
                        'filesize', 
                        'url', 
                        'ext', 
                        'lyric', 
                        'cat_id', 
                        'singer_id', 
                        'other_singer', 
                        'other_singer_title_url', 
                        'comment_count', 
                        'play_count', 
                        'download_count', 
                        'phpfox_music_id'), 
                    $aInsertItem);
        }
        $results = $this->database()
                ->select('*')
                ->from(phpFox::getT('m2bmusic_album_song'))
                ->where('phpfox_music_id IN ' . $sNewMusicAlbumList)
                ->execute('getRows');
        return array($sNewMusicAlbumList, $results);
    }

    public function migrateAlbumSongCommentAndFeeds($new_song)
    {
        $insert_item = array();
        $comment_new_song_list = $this->getMusicSongCommentsPHPFOX($new_song['phpfox_music_id']);
        
        foreach ($comment_new_song_list as $c_n_s_l)
        {
            $insert_item_comment = array();
            $insert_item_comment['parent_id'] = $c_n_s_l['parent_id'];
            $insert_item_comment['type_id'] = 'musicsharing';
            $insert_item_comment['item_id'] = $new_song['song_id'];
            $insert_item_comment['user_id'] = $c_n_s_l['user_id'];
            $insert_item_comment['owner_user_id'] = $c_n_s_l['owner_user_id'];
            $insert_item_comment['time_stamp'] = $c_n_s_l['time_stamp'];
            $insert_item_comment['update_time'] = $c_n_s_l['update_time'];
            $insert_item_comment['update_user'] = $c_n_s_l['update_user'];
            $insert_item_comment['rating'] = $c_n_s_l['rating'];
            $insert_item_comment['ip_address'] = $c_n_s_l['ip_address'];
            $insert_item_comment['author'] = $c_n_s_l['author'];
            $insert_item_comment['author_email'] = $c_n_s_l['author_email'];
            $insert_item_comment['author_url'] = $c_n_s_l['author_url'];
            $insert_item_comment['view_id'] = $c_n_s_l['view_id'];
            $insert_item_comment['child_total'] = $c_n_s_l['child_total'];
            $last_insert_id = $this->database()->insert(phpFox::getT('comment'), $insert_item_comment);
            //inset text and text parse.
            $insert_item_comment_text['text'] = $c_n_s_l['text'];
            $insert_item_comment_text['comment_id'] = $last_insert_id;
            $insert_item_comment_text['text_parsed'] = $c_n_s_l['text_parsed'];
            $last_insert_id = $this->database()->insert(phpFox::getT('comment_text'), $insert_item_comment_text);
        }
        $feed_new_song_list = $this->getMusicSongFeedsPHPFOX($new_song['phpfox_music_id']);

        foreach ($feed_new_song_list as $f_n_s_l)
        {
            unset($f_n_s_l['feed_id']);
            $f_n_s_l['type_id'] = "comment_musicsharing";
            $f_n_s_l['item_id'] = $new_song['song_id'];
            $last_insert_id = $this->database()->insert(phpFox::getT('feed'), $f_n_s_l);
        }
    }

    /*     * **************** */

    public function setDefaultValue($settings = array())
    {
        if (!isset($settings['can_view_album']))
        {
            $settings['can_view_album'] = 0;
        }
        if (!isset($settings['can_post_on_profile']))
        {
            $settings['can_post_on_profile'] = 0;
        }
        if (!isset($settings['can_edit_album']))
        {
            $settings['can_edit_album'] = 0;
        }
        if (!isset($settings['can_edit_album']))
        {
            $settings['can_edit_album'] = 0;
        }
        if (!isset($settings['can_post_comment_on_song']))
        {
            $settings['can_post_comment_on_song'] = 0;
        }
        if (!isset($settings['max_songs']))
        {
            $settings['max_songs'] = 10;
        }
        if (!isset($settings['max_file_size_upload']))
        {
            $settings['max_file_size_upload'] = 30000;
        }
        if (!isset($settings['max_storage_size']))
        {
            //$aUser = $this->getVar('aUser');
            //$settings['max_storage_size'] =  phpFox::getLib('phpfox.file')->filesize(100000);
            $settings['max_storage_size'] = 100000;
        }
        if (!isset($settings['max_playlist_created']))
        {
            $settings['max_playlist_created'] = 5;
        }
        if (!isset($settings['max_album_created']))
        {
            $settings['max_album_created'] = 5;
        }
        if (!isset($settings['can_download_song']))
        {
            $settings['can_download_song'] = 0;
        }
        if (!isset($settings['can_upload_song']))
        {
            $settings['can_upload_song'] = 0;
        }
        if (!isset($settings['is_public_permission']))
        {
            $settings['is_public_permission'] = 0;
        }
        if (!isset($settings['max_songs']))
        {
            $settings['max_songs'] = 10;
        }
        return $settings;
    }

    public function getUsedSpace($user_id = null)
    {
        $this->database()
                ->select('SUM(filesize)')
                ->from(Phpfox::getT('m2bmusic_album_song'), 'album_song')
                ->leftJoin(Phpfox::getT('m2bmusic_album'), 'album', 'album_song.album_id = album.album_id');
        
        if ($user_id != null)
        {
            $this->database()->where('album.user_id = ' . (int) $user_id);
        }
        
        return $this->database()->execute('getfield');
    }

    public function getUserSettings($user_id = null, $is_public = true)
    {
        if ($is_public == false)
        {
            return $this->getSettings(0);
        }
        if ($user_id == null)
            return $this->getSettings(3); //guest.
        else
            return $this->getSettings(phpFox::getUserBy('user_group_id'));
    }

    public function getSettings($user_group_id = 1)
    {
        $aData = $this->database()
                ->select('*')
                ->from(Phpfox::getT('m2bmusic_settings'))
                ->where('user_group_id = ' . (int) $user_group_id)
                ->execute('getRows');
        
        $aSettings = array();
        foreach($aData as $aItem)
        {
            $aSettings[$aItem['name']] = $aItem['default_value'];
        }
        return $aSettings;
    }

    public function setSettings($params = array(), $user_group_id)
    {
        $settingController = new SettingsController();
        $settingController->delete_settings_user_group($user_group_id);
        $settingController->insert_settings_user_group($params, $user_group_id);
    }

    public function updatePlaylistInfo($iPlaylistId, $aVals = array())
    {
        return $this->database()->update(Phpfox::getT('m2bmusic_playlist'), $aVals, 'playlist_id = ' . (int) $iPlaylistId);
    }
    
    public function updateAlbumInfo($iAlbumId, $aVals = array())
    {
        return $this->database()->update(Phpfox::getT('m2bmusic_album'), $aVals, 'album_id = ' . (int) $iAlbumId);
    }
            
    public function createAlbum($album = array())
    {
        // Get title url.
        $album['title'] = $this->preParse()->clean($album['title']);
        $album['description'] = $this->preParse()->clean($album['description']);
        $album['title_url'] = $this->convertURL($album['title_url']);
        $album['play_count'] = 0;
        $album['download_count'] = 0;
        $album['is_featured'] = 0;

        // Get parent module.
        $page_msf = phpFox::getLib('session')->get('pages_msf');

        // Assign to album.
        if ($page_msf)
        {
            $album['module_id'] = $page_msf['module_id'];
            $album['item_id'] = $page_msf['item_id'];
        }
        else
        {
            $album['module_id'] = null;
            $album['item_id'] = 0;
        }

        if (!isset($album['privacy']))
        {
            $album['privacy'] = 0;
        }
        
        if (!isset($album['privacy_comment']))
        {
            $album['privacy_comment'] = 0;
        }
        
        // Insert to table.
        $new_albumid = $this->database()->insert(Phpfox::getT('m2bmusic_album'), $album);

        $title = $album['title'];

        if ($new_albumid > 0)
        {
            // Add feed.
            if (phpFox::isModule('feed') && $page_msf)
            {
                $iUserId = (int) $this->database()
                                ->select('user_id')
                                ->from(phpFox::getT('pages'), 'p')
                                ->where('page_id=' . $page_msf['item_id'])
                                ->execute('getSlaveField');

                $timestamp = time();
                $this->database()->insert(phpFox::getT('pages_feed'), array(
                    'type_id' => 'musicsharing_pagesalbum',
                    'user_id' => phpFox::getUserId(),
                    'parent_user_id' => $page_msf["item_id"],
                    'item_id' => $new_albumid,
                    'time_stamp' => $timestamp
                        )
                );
            }
            else if (phpFox::isModule('feed'))
            {
                (phpFox::isModule('feed') ? phpFox::getService('feed.process')->add('musicsharing_album', $new_albumid, $album['privacy'], (isset($album['privacy_comment']) ? (int) $album['privacy_comment'] : 0)) : null);
            }
        }

        return $new_albumid;
    }

    public function editAlbum($album = array())
    {
        $sTable = Phpfox::getParam(array('db', 'prefix')) . 'm2bmusic_album';

        // Get title url.
        $album['title'] = Phpfox::getLib('parse.input')->clean($album['title']);
        $album['description'] = Phpfox::getLib('parse.input')->clean($album['description']);
        $album['title_url'] = $this->convertURL($album['title_url']);
        $album['play_count'] = 0;
        $album['download_count'] = 0;
        $album['is_featured'] = 0;

        // Get parent module.
        $page_msf = phpFox::getLib('session')->get('pages_msf');

        // Assign to album.
        if ($page_msf)
        {
            $album['module_id'] = $page_msf['module_id'];
            $album['item_id'] = $page_msf['item_id'];
        }
        else
        {
            $album['module_id'] = null;
            $album['item_id'] = 0;
        }

        // Update album.
        $this->database()->update($sTable, $album, 'album_id = ' . (int) $album['album_id']);

        (phpFox::isModule('feed') ? phpFox::getService('feed.process')->update('musicsharing_album', $album['album_id'], $album['privacy'], $album['privacy_comment'], 0, phpFox::getUserId()) : null);

        return $album['album_id'];
    }

    public function createPlaylist($playlist = array())
    {
        // Get table with prefix.
        $sTable = Phpfox::getParam(array('db', 'prefix')) . 'm2bmusic_playlist';

        $playlist['title'] = Phpfox::getLib('parse.input')->clean($playlist['title']);
        $playlist['title_url'] = $this->convertURL($playlist['title_url']);
        $playlist['description'] = Phpfox::getLib('parse.input')->clean($playlist['description']);
        $playlist['play_count'] = 0;

        // Get parent module.
        $page_msf = phpFox::getLib('session')->get('pages_msf');

        // Assign to album.
        if ($page_msf)
        {
            $playlist['module_id'] = $page_msf['module_id'];
            $playlist['item_id'] = $page_msf['item_id'];
        }
        else
        {
            $playlist['module_id'] = null;
            $playlist['item_id'] = 0;
        }
        
        if (!isset($playlist['privacy']))
        {
            $playlist['privacy'] = 0;
        }

        if (!isset($playlist['privacy_comment']))
        {
            $playlist['privacy_comment'] = 0;
        }

        // Insert to table.
        $new_playlistid = $this->database()->insert($sTable, $playlist);

        if ($new_playlistid > 0)
        {
            $page_msf = phpFox::getLib('session')->get('pages_msf');
            if (phpFox::isModule('feed') && $page_msf)
            {
                $iUserId = (int) $this->database()->select('user_id')
                                ->from(phpFox::getT('pages'), 'p')
                                ->where('page_id=' . $page_msf['item_id'])
                                ->execute('getSlaveField');

                $timestamp = time();

                $this->database()->insert(phpFox::getT('pages_feed'), array(
                    'type_id' => 'musicsharing_pagesplaylist',
                    'user_id' => phpFox::getUserId(),
                    'parent_user_id' => $page_msf["item_id"],
                    'item_id' => $new_playlistid,
                    'time_stamp' => $timestamp
                        )
                );
            }
            else if (phpFox::isModule('feed'))
            {
                (phpFox::isModule('feed') ? phpFox::getService('feed.process')->add('musicsharing_playlist', $new_playlistid, $playlist['privacy'], (isset($playlist['privacy_comment']) ? (int) $playlist['privacy_comment'] : 0)) : null);
            }
        }

        return $new_playlistid;
    }

    public function editPlaylist($playlist = array())
    {
        // Get table with prefix.
        $sTable = Phpfox::getParam(array('db', 'prefix')) . 'm2bmusic_playlist';

        $playlist['title'] = Phpfox::getLib('parse.input')->clean($playlist['title']);
        $playlist['title_url'] = $this->convertURL($playlist['title_url']);
        $playlist['description'] = Phpfox::getLib('parse.input')->clean($playlist['description']);
        $playlist['play_count'] = 0;

        // Get parent module.
        $page_msf = phpFox::getLib('session')->get('pages_msf');

        // Assign to album.
        if ($page_msf)
        {
            $playlist['module_id'] = $page_msf['module_id'];
            $playlist['item_id'] = $page_msf['item_id'];
        }
        else
        {
            $playlist['module_id'] = null;
            $playlist['item_id'] = 0;
        }

        // Get the playlist id.
        $iPlaylistId = (int) $playlist['playlist_id'];

        // Insert to table.
        $this->database()->update($sTable, $playlist, 'playlist_id = ' . $iPlaylistId);

        (phpFox::isModule('feed') ? phpFox::getService('feed.process')->update('musicsharing_playlist', $iPlaylistId, $playlist['privacy'], $playlist['privacy_comment'], 0, phpFox::getUserId()) : null);

        return $iPlaylistId;
    }

    public function setplaylistprofile($playlist_id, $user_id)
    {
        $playlistControl = new PlaylistController();
        return $playlistControl->playlist_setprofile($playlist_id, $user_id);
    }

    public function uploadSong($song = array())
    {
        $oDb = $this->database();
        
        $album_song = new AlbumSongController();
        $new_song = new AlbumSongModel(0, $song['title'], $song['title_url'], $song['album_id'], $song['filesize'], $song['url'], $song['ext'], 0, '', $song['cat_id'], $song['singer_id'], "", 0, 0);
        $song_id = $album_song->album_song_create($new_song);
        
        $user_owners = $oDb->select('*')
                ->from(phpFox::getT('m2bmusic_album'))
                ->where('album_id = ' . $new_song->get_album_id())
                ->execute('getRow');
        
        $oDb->update(phpFox::getT('m2bmusic_album_song'), array('server_id' => Phpfox::getLib('request')->getServer('PHPFOX_SERVER_ID')), 'song_id = ' . (int) $song_id);
        
        $oDb->insert(phpFox::getT('m2bmusic_song_playlist_order'), array('user_id' => $user_owners['user_id'], 'album_id' => $song['album_id'], 'song_id' => $song_id, 'value' => -time() - $song_id, 'playlist_id' => 0));
        (($sPlugin = Phpfox_Plugin::get('musicsharing.service_music_uploadsong_end')) ? eval($sPlugin) : false);
        return $song_id;
    }

    public function getAlbums($start = null, $limit = null, $sort_by = null, $select = null, $where = null, $cross_module_id = false)
    {
        $album = new AlbumController();
        return $album->album_list($start, $limit, $sort_by, $select, $where, $cross_module_id);
    }

    public function getAlbums_Id($id_album, $user_id)
    {
        $album = new AlbumController();
        return $album->album_list_id($id_album, $user_id);
    }

    public function getAlbumsBefore_Id($id_album, $user_id)
    {
        $album = new AlbumController();
        return $album->album_list_before_id($id_album, $user_id);
    }

    public function getAlbumsAfter_Id($id_album, $user_id)
    {
        $album = new AlbumController();
        return $album->album_list_after_id($id_album, $user_id);
    }

    public function updateAlbum($id_album, $order_id)
    {
        $album = new AlbumController();
        return $album->album_update($id_album, $order_id);
    }

    public function updateOrderAlbum($user_id)
    {
        $album = new AlbumController();
        return $album->album_update_order($user_id);
    }

    public function getTotalAlbums()
    {
        $album = new AlbumController();
        return $album->album_getTotalAlbum();
    }

    public function getTotalSongs()
    {
        $albumSong = new AlbumSongController();
        return $albumSong->album_getTotalSong();
    }

    public function getTotalPlaylist()
    {
        $playlist = new PlaylistController();
        return $playlist->album_getTotalPlaylist();
    }

    public function getPlaylists($start = null, $limit = null, $sort_by = null, $select = null, $where = null)
    {
        $playlist = new PlaylistController();
        $pl = $playlist->playlist_list($start, $limit, $sort_by, $select, $where);
        return $pl;
    }

    public function getplaylists_Id($id_playlist, $user_id)
    {
        $playlist = new playlistController();
        return $playlist->playlist_list_id($id_playlist, $user_id);
    }

    public function getplaylistsBefore_Id($id_playlist, $user_id)
    {
        $playlist = new playlistController();
        return $playlist->playlist_list_before_id($id_playlist, $user_id);
    }

    public function getplaylistsAfter_Id($id_playlist, $user_id)
    {
        $playlist = new playlistController();
        return $playlist->playlist_list_after_id($id_playlist, $user_id);
    }

    public function updateplaylist($id_playlist, $order_id)
    {
        $playlist = new playlistController();
        return $playlist->playlist_update($id_playlist, $order_id);
    }

    public function updateOrderPlaylist($user_id)
    {
        $album = new playlistController();
        return $album->playlist_update_order($user_id);
    }

    public function addCategory($title)
    {
        // Get table name.
        $sTable = Phpfox::getParam(array('db', 'prefix')) . 'm2bmusic_category';

        Phpfox::getService('ban')->checkAutomaticBan($title);

        // Compose data.
        $aValues = array();
        $aValues['title'] = Phpfox::getLib('parse.input')->clean($title);
        $aValues['title_url'] = Phpfox::getLib('parse.input')->cleanTitle($title);

        Phpfox::getLib('cache')->remove('m2bmusic_category');

        // Add new category.
        return Phpfox::getLib('database')->insert($sTable, $aValues);
    }

    public function addSingerType($title)
    {
        // Get table name.
        $sTable = Phpfox::getParam(array('db', 'prefix')) . 'm2bmusic_singer_type';

        Phpfox::getService('ban')->checkAutomaticBan($title);

        // Compose data.
        $aValues = array();
        $aValues['title'] = Phpfox::getLib('parse.input')->clean($title);

        Phpfox::getLib('cache')->remove('m2bmusic_singer_type');

        // Add new category.
        return Phpfox::getLib('database')->insert($sTable, $aValues);
    }

    /**
     * Add a new singer.
     * @param string $sTitle
     * @param integer $iType
     * @param string $sSingerImagePath
     * @return integer Id
     */
    public function addSinger($sTitle, $iType, $sSingerImagePath)
    {
        // Get table name.
        Phpfox::getService('ban')->checkAutomaticBan($sTitle);

        // Compose data.
        $aValues = array();
        $aValues['title'] = Phpfox::getLib('parse.input')->clean($sTitle);
        $aValues['title_url'] = $this->convertURL($sTitle);
        $aValues['singer_type'] = (int) $iType;
        $aValues['singer_image'] = Phpfox::getLib('parse.input')->clean($sSingerImagePath);

        Phpfox::getLib('cache')->remove('m2bmusic_singer');

        // Add new category.
        return Phpfox::getLib('database')->insert(Phpfox::getT('m2bmusic_singer'), $aValues);
    }

    public function updateSingerImage($iSingerId, $aVals)
    {
        // Add new category.
        return $this->database()->update(Phpfox::getT('m2bmusic_singer'), $aVals, 'singer_id = ' . (int) $iSingerId);
    }
    
    public function getSingerTypes()
    {
        $singerType = new SingerTypeController();
        return $singerType->singertype_list();
    }

    public function getCategories()
    {
        $category = new CategoryController();
        return $category->category_list();
    }

    public function getCategoryById($id = null)
    {
        $category = new CategoryController();
        if ($id == null)
            return false;
        return $category->getCategoryById($id);
    }

    /**
     * Remove this function.
     * @return type
     */
    public function getSingers()
    {
        $singer = new SingerController();
        return $singer->singer_list();
    }

    public function getAllSingerTypes()
    {
        $this->database()->clean();

        $this->database()
                ->select('mst.*')
                ->from(Phpfox::getT('m2bmusic_singer_type'), 'mst')
                ->order('mst.title ASC');

        $aSingerTypes = $this->database()->execute('getRows');

        return $aSingerTypes;
    }

    public function getAllSingers()
    {
        $this->database()->clean();

        $this->database()
                ->select('ms.*, mst.title AS singer_type_title')
                ->from(Phpfox::getT('m2bmusic_singer'), 'ms')
                ->leftJoin(Phpfox::getT('m2bmusic_singer_type'), 'mst', 'mst.singertype_id = ms.singer_type')
                ->order('ms.title ASC');

        $aSingers = $this->database()->execute('getRows');

        return $aSingers;
    }

    public function getSingersByTypeId($iTypeId)
    {
        $this->database()->clean();

        $this->database()
                ->select('ms.*, mst.title AS singer_type_title')
                ->from(Phpfox::getT('m2bmusic_singer'), 'ms')
                ->leftJoin(Phpfox::getT('m2bmusic_singer_type'), 'mst', 'mst.singertype_id = ms.singer_type')
                ->where('ms.singer_type = ' . (int) $iTypeId)
                ->order('ms.title ASC');

        $aSingers = $this->database()->execute('getRows');

        return $aSingers;
    }

    public function getSingerToTypes($type_id = null)
    {
        $singer = new SingerController();
        return $singer->singer_list_type($type_id);
    }

    public function getSongsInAlbum($idalbum, $user_log)
    {
        $album = new AlbumController();
        return $album->getSongsInAlbum($idalbum, $user_log);
    }

    /**
     * This function is used to query the old data. Use to fix bug UTF-8.
     * @param string $sQuery Query language.
     * @return array Array of data.
     */
    public function executeSql($sQuery, $bOneRow = false)
    {
        $oConnection = mysql_connect(phpFox::getParam(array('db', 'host')), phpFox::getParam(array('db', 'user')), phpFox::getParam(array('db', 'pass')));
        if (!$oConnection)
        {
            die("can't connect server");
        }
        
        mysql_query("SET character_set_results=utf8", $oConnection);
        
        $oDbSelected = mysql_select_db(phpFox::getParam(array('db', 'name')), $oConnection);
        if (!$oDbSelected)
        {
            die("have not database");
        }
        
        mysql_query("SET character_set_results=utf8", $oConnection);
        
        $aData = mysql_query($sQuery) or die(mysql_error() . "<b> SQL was: </b>" . $sQuery);
        
        $aResult = array();
        
        while ($oRow = mysql_fetch_assoc($aData))
        {
            if ($bOneRow)
            {
                return $oRow;
            }
            $aResult[] = $oRow;
        }
        
        return $aResult;
    }
    
    public function getSongsInPlaylist($iPlaylistId, $iUserId)
    {
        $oDb = $this->database();
        // Get songs in playlist without ordering.
        $sQuery = $oDb->select('album_song.*, user.user_id, user.full_name, user.user_name, user.user_image, album.title AS album_title, album.is_download, singer.title AS singer_title')
                ->from(Phpfox::getT('m2bmusic_playlist_song'), 'playlist_song')
                ->leftJoin(Phpfox::getT('m2bmusic_album_song'), 'album_song', 'album_song.song_id = playlist_song.album_song_id')
                ->leftJoin(Phpfox::getT('m2bmusic_album'), 'album', 'album_song.album_id = album.album_id')
                ->leftJoin(Phpfox::getT('user'), 'user', 'user.user_id = album.user_id')
                ->leftJoin(Phpfox::getT('m2bmusic_singer'), 'singer', 'singer.singer_id = album_song.singer_id')
                ->where('playlist_song.playlist_id = ' . (int) $iPlaylistId)
                ->execute();
        
        $aSongs = $this->executeSql($sQuery);
        
        // Assign to array.
        $aSongsList = array();
        foreach($aSongs as $aSong)
        {
            $aSongsList[$aSong['song_id']] = $aSong;
        }
        
        // Get order of songs.
        $aOrderSongs = $oDb->select('song_playlist_order.song_id, song_playlist_order.value')
                ->from(Phpfox::getT('m2bmusic_song_playlist_order'), 'song_playlist_order')
                ->where('song_playlist_order.playlist_id = ' . (int) $iPlaylistId)
                ->order('song_playlist_order.value ASC')
                ->execute('getRows');
        
        // Merge songs and order of songs.
        $aResult = array();
        
        foreach($aOrderSongs as $aOrder)
        {
            if (isset($aSongsList[$aOrder['song_id']]))
            {
                $aSongsList[$aOrder['song_id']]['value'] = $aOrder['value'];
                $aResult[] = $aSongsList[$aOrder['song_id']];
            }
        }
        
        return $aResult;
    }
    
    public function voteSong($iSongId, $iVote)
    {
        $aRating = $this->database()
                ->select('r.*')
                ->from(Phpfox::getT('m2bmusic_song_rating'), 'r')
                ->where('item_id = ' . (int) $iSongId . ' AND user_id = ' . (int) Phpfox::getUserId())
                ->execute('getRow');
        if ($aRating)
        {
            return false;
        }

        $aValues = array('item_id' => $iSongId, 'user_id' => Phpfox::getUserId(), 'rating' => $iVote);

        return $this->database()->insert(Phpfox::getT('m2bmusic_song_rating'), $aValues);
    }

    public function getVoteBySongId($idsong)
    {
        $song = new AlbumSongController();
        return $song->getVoteBySongId($idsong);
    }

    public function checkVoted($idsong, $iUserId)
    {
        $song = new AlbumSongController();
        return $song->checkVoted($idsong, $iUserId);
    }

    public function service_playcount($iSongId)
    {
        $this->database()->query("UPDATE " . Phpfox::getT('m2bmusic_album_song') . " SET play_count = play_count + 1 WHERE song_id = " . (int) $iSongId);
        $this->database()->query("UPDATE " . Phpfox::getT('m2bmusic_album') . " SET play_count = play_count + 1 WHERE album_id = (SELECT album_id FROM " . Phpfox::getT('m2bmusic_album_song') . " WHERE song_id = " . (int) $iSongId . ")");
    }

    public function song_track_info($song_id, $suppress = "yes")
    {
        $albumSong = new AlbumSongController();
        $song = $albumSong->album_song_info($song_id);
        if ($suppress == "yes")
        {
            $text = $song["lyric"];
            $text = str_replace("\u0009", "&#09;", $text);
            $text = str_replace("\u0020", "&nbsp;", $text);
            $text = str_replace("\u0032", "&nbsp;", $text);
            $text = str_replace(" ", "&nbsp;", $text);
            $text = str_replace("\n", "<br />", $text);
            $song["lyric"] = $text;
        }
        
        return $song;
    }
    
    public function get_firstSong($album_id)
    {
        $song = new AlbumSongController();
        return $song->get_firstSong($album_id);
    }

    public function getSongs($start = null, $limit = null, $sort_by = null, $select = null, $where = NULL, $album_id = NULL)
    {
        $album_song = new AlbumSongController();
        return $album_song->album_song_list($start, $limit, $sort_by, $select, $where, $album_id);
    }

    public function getSongsByPlaylistId($iPlaylistId)
    {
        $aSongs = $this->database()->select('playlist_song.song_id AS playlist_song_id, song_playlist_order.*, album_song.*')
                
                ->from(Phpfox::getT('m2bmusic_playlist_song'), 'playlist_song')
                
                ->leftJoin(Phpfox::getT('m2bmusic_album_song'), 'album_song', 'album_song.song_id = playlist_song.album_song_id')
                
                ->leftJoin(Phpfox::getT('m2bmusic_playlist'), 'playlist', 'playlist_song.playlist_id = playlist.playlist_id')
                
                ->leftJoin(Phpfox::getT('m2bmusic_album'), 'album', 'album_song.album_id = album.album_id')
                
                ->leftJoin(Phpfox::getT('m2bmusic_song_playlist_order'), 'song_playlist_order', 'playlist_song.album_song_id = song_playlist_order.song_id 
                    AND song_playlist_order.user_id = ' . Phpfox::getUserId() . ' 
                        AND song_playlist_order.playlist_id = ' . (int) $iPlaylistId)
                                
                ->where('playlist_song.playlist_id = ' . (int) $iPlaylistId)
                ->order('song_playlist_order.value ASC')
                ->execute('getRows');
        
        return $aSongs;
    }

    public function getPlaylistSongs($playlist_id = null, $start = null, $limit = null, $sort_by = null, $select = null)
    {
        $playlist_song = new PlaylistSongController();
        return $playlist_song->playlist_song_list($playlist_id, $start, $limit, $sort_by, $select);
    }

    public function get_total_song($where = NULL)
    {
        $album_song = new AlbumSongController();
        return $album_song->album_song_list_total(null, null, null, $where);
    }

    public function updateAlbumSong($aValues = array())
    {
        // Get table name.
        $sTable = Phpfox::getParam(array('db', 'prefix')) . 'm2bmusic_album_song';

        // Compose data.
        $aValues['title'] = Phpfox::getLib('parse.input')->clean($aValues['title']);
        $aValues['title_url'] = $this->convertURL($aValues['title']);
        $aValues['lyric'] = Phpfox::getLib('parse.input')->clean($aValues['lyric']);
        $aValues['other_singer'] = Phpfox::getLib('parse.input')->clean($aValues['other_singer']);
        $aValues['other_singer_title_url'] = Phpfox::getLib('parse.input')->clean($aValues['other_singer']);

        $this->cache()->remove('m2bmusic_album_song');

        // Add new category.
        return $this->database()->update($sTable, $aValues, 'song_id = ' . (int) $aValues['song_id']);
    }

    public function get_total_playlistsong($playlist_id = NULL)
    {
        $playlist_song = new PlaylistSongController();
        return $playlist_song->playlist_song_list_total($playlist_id, null, null, null);
    }

    public function get_total_album($where = null)
    {
        $album = new AlbumController();
        return $album->album_list_total(null, null, null, $where);
    }

    public function get_total_playlist($where = null)
    {
        $playlist = new PlaylistController();
        return $playlist->playlist_list_total(null, null, null, $where);
    }

    public function getArtists($start = null, $limit = null, $sort_by = null, $select = null, $where = NULL)
    {
        $album = new AlbumController();
        return $album->getArtists($start, $limit, $sort_by, $select, $where);
    }

    public function get_total_artist($where = null, $sort = null)
    {
        $album = new AlbumController();
        return $album->artist_total($where, $sort);
    }

    public function addtoPlaylist($idPlaylist, $idSong)
    {
        $playlistSongControl = new PlaylistSongController();
        $newPlaylistSong = new PlaylistSongModel(0, $idPlaylist, $idSong);
        return $playlistSongControl->playlist_song_create($newPlaylistSong);
    }

    public function checkPlaylist($idPlaylist, $idSong)
    {
        $playlistSongControl = new PlaylistSongController();
        return $playlistSongControl->check_playlist($idPlaylist, $idSong);
    }

    public function updateCounter($table = "", $sId = "", $iId, $sCounter, $bMinus = false)
    {
        $this->database()->update($table, array(
            $sCounter => array('= ' . $sCounter . ' ' . ($bMinus ? '-' : '+'), 1)
                ), $sId . ' = ' . (int) $iId
        );
    }

    public function updateCounterComment($song_id, $bool)
    {
        $song = new AlbumSongController();
        return $song->updateCounterComment($song_id, $bool);
    }

    public function getAlbumInfo($idalbum = null)
    {
		$friend_sql_select = "";
		if (phpFox::isModule('friend'))
		{
			$friend_sql_select = "f.friend_id AS is_friend, ";
		}
        $this->database()
                ->select("$friend_sql_select al.*,l.like_id as is_liked,us.user_name,us.full_name,us.user_image")
                ->from(Phpfox::getT('m2bmusic_album'), 'al')
                ->leftJoin(Phpfox::getT('like'), 'l', "l.type_id = 'musicsharing_album' AND l.item_id = al.album_id AND l.user_id = " . phpFox::getUserId()) 
				->leftJoin(Phpfox::getT('user'),"us", "us.user_id = al.user_id");
        
        if (Phpfox::isModule('friend'))
		{
			$this->database()->leftJoin(Phpfox::getT('friend'), 'f', "f.user_id = al.user_id AND f.friend_user_id = " . phpFox::getUserId());
		}

        $album_info = $this->database()
                ->where('album_id = ' . (int) $idalbum)
                ->execute('getRow');
        
        $album_info['num_track'] = $this->database()
                ->select('count(*)')
                ->from(Phpfox::getT('m2bmusic_album_song'))
                ->where('album_id = ' . (int) $idalbum)
                ->execute('getfield');
        
        $album_info['title'] = isset($album_info['title']) ? $this->preParse()->clean($album_info['title']) : '';
        
        return $album_info;
    }

    public function getPlaylistInfo($idplaylist = null)
    {
        $playlistControl = new PlaylistController();
        return $playlistControl->playlist_info($idplaylist);
    }

    public function getSingerInfo($idsinger = null)
    {
        $singerControl = new SingerController();
        return $singerControl->singer_info($idsinger);
    }

    //Minh - Delete
    //--Delete Playlist
    public function deletePlaylist($playlist_id)
    {
        $playlistControll = new PlaylistController();
        
        (phpFox::isModule('feed') ? phpFox::getService('feed.process')->delete('musicsharing_playlist', $playlist_id) : null);
        
        $this->database()->delete(phpFox::getT("pages_feed"), 'type_id = \'musicsharing_pagesplaylist\' AND item_id = ' . $playlist_id);
        
        return $playlistControll->playlist_delete($playlist_id);
    }

    public function deleteAlbum($album_id)
    {
        $albumControll = new AlbumController();
        
        (phpFox::isModule('feed') ? phpFox::getService('feed.process')->delete('musicsharing_album', $album_id) : null);
        
        $this->database()->delete(phpFox::getT("pages_feed"), 'type_id = \'musicsharing_pagesalbum\' AND item_id = ' . $album_id);
        
        return $albumControll->album_delete($album_id);
    }

    //Nhan - delete albums, delete all comments also
    public function deleteAlbums($iIds)
    {
        $albumControll = new AlbumController();
        return $albumControll->albums_delete($iIds);
    }

    public function deletePlaylistSong($iPlaylistSongId)
    {
        $aRow = $this->database()->select('*')
                ->from(Phpfox::getT('m2bmusic_playlist_song'))
                ->where('song_id = ' . $iPlaylistSongId)
                ->execute('getSlaveRow');

        $this->database()->delete(Phpfox::getT('m2bmusic_song_playlist_order'), 'song_id = ' . $aRow['album_song_id'] . ' AND playlist_id = ' . $aRow['playlist_id']);
        $this->database()->delete(Phpfox::getT('m2bmusic_playlist_song'), 'song_id = ' . $iPlaylistSongId);
        
        return true;
    }

    public function getSongFromId($iSongId)
    {
        $aRows = $this->database()->select('*')
                ->from(Phpfox::getT('m2bmusic_album_song'))
                ->where('song_id = ' . $iSongId)
                ->execute('getSlaveRows');
        if ($aRows)
        {
            return $aRows[0];
        }
        else
        {
            return false;
        }
    }

    public function deleteAlbumSong($iSongId)
    {
        $aSong = $this->getSongFromId($iSongId);

        if (count($aSong) == 0)
            return true;

        $sPathMp3 = PHPFOX_DIR_FILE . 'musicsharing' . PHPFOX_DS . $aSong['url'];

        if (file_exists($sPathMp3) && filesize($sPathMp3) > 0)
        {
            Phpfox::getLib('file')->unlink($sPathMp3);
        }

        // Delete in database with the ordering command.
        // Do not change them.
        $this->database()->delete(Phpfox::getT('m2bmusic_song_playlist_order'), 'song_id IN (
            SELECT ps.song_id 
            FROM ' . Phpfox::getT('m2bmusic_playlist_song') . ' AS ps 
            WHERE album_song_id = ' . (int) $iSongId . ')');

        $this->database()->delete(Phpfox::getT('m2bmusic_playlist_song'), 'album_song_id = ' . (int) $iSongId);

        $this->database()->delete(Phpfox::getT('m2bmusic_song_rating'), 'item_id = ' . (int) $iSongId);

        $this->database()->delete(Phpfox::getT('m2bmusic_album_song'), 'song_id = ' . (int) $iSongId);

        return true;
    }

    public function deleteSingerType($singertype_id)
    {
        $singTypeControll = new SingerTypeController();
        return $singTypeControll->singertype_delete($singertype_id);
    }

    public function deleteCategory($cat_id)
    {
        $catControll = new CategoryController();
        return $catControll->category_delete($cat_id);
    }

    public function deleteSinger($iSingerId)
    {
        Phpfox::getService('musicsharing.process')->deleteSingerImage($iSingerId, Phpfox::getUserId());

        $singerControll = new SingerController();
        return $singerControll->singer_delete($iSingerId);
    }

    //Update
    public function updateCategory($iCategoryId, $title)
    {
        // Get table name.
        $sTable = Phpfox::getParam(array('db', 'prefix')) . 'm2bmusic_category';

        Phpfox::getService('ban')->checkAutomaticBan($title);

        // Compose data.
        $aValues = array();
        $aValues['title'] = Phpfox::getLib('parse.input')->clean($title);
        $aValues['title_url'] = Phpfox::getLib('parse.input')->cleanTitle($title);

        $bResult = Phpfox::getLib('database')->update($sTable, $aValues, 'cat_id = ' . (int) $iCategoryId);

        if (!$bResult)
            return false;

        $this->cache()->remove('m2bmusic_category');

        return true;
    }

    public function updateSingerType($iSingerTypeId, $title)
    {
        // Get table name.
        $sTable = Phpfox::getParam(array('db', 'prefix')) . 'm2bmusic_singer_type';

        Phpfox::getService('ban')->checkAutomaticBan($title);

        // Compose data.
        $aValues = array();
        $aValues['title'] = Phpfox::getLib('parse.input')->clean($title);

        $bResult = Phpfox::getLib('database')->update($sTable, $aValues, 'singertype_id = ' . (int) $iSingerTypeId);

        if (!$bResult)
            return false;

        $this->cache()->remove('m2bmusic_singer_type');

        return true;
    }

    public function updateSinger($iSingerId, $sTitle)
    {
        $aValues = array();

        $aValues['singer_id'] = $iSingerId;
        $aValues['title'] = $sTitle;

        // Get table name.
        $sTable = Phpfox::getParam(array('db', 'prefix')) . 'm2bmusic_singer';

        Phpfox::getService('ban')->checkAutomaticBan($aValues['title']);

        // Compose data.
        $aValues['title'] = Phpfox::getLib('parse.input')->clean($aValues['title']);
        $aValues['title_url'] = $this->convertURL($aValues['title']);

        $bResult = Phpfox::getLib('database')->update($sTable, $aValues, 'singer_id = ' . (int) $aValues['singer_id']);

        if (!$bResult)
            return false;

        $this->cache()->remove('m2bmusic_singer');

        return true;
    }

    public function editSinger($aValues = array())
    {
        // Get table name.
        $sTable = Phpfox::getParam(array('db', 'prefix')) . 'm2bmusic_singer';

        Phpfox::getService('ban')->checkAutomaticBan($aValues['title']);

        // Compose data.
        $aValues['title'] = Phpfox::getLib('parse.input')->clean($aValues['title']);
        $aValues['title_url'] = $this->convertURL($aValues['title']);
        $aValues['singer_type'] = (int) $aValues['singer_type'];

        $bResult = $this->database()->update($sTable, $aValues, 'singer_id = ' . (int) $aValues['singer_id']);

        if (!$bResult)
            return false;

        $this->cache()->remove('m2bmusic_singer');

        return true;
    }

    public function updatePlayCountForAlbum($iAlbumId)
    {
        return $this->database()->query('UPDATE ' . Phpfox::getT('m2bmusic_album') . ' SET play_count = play_count + 1 WHERE album_id = ' . (int) $iAlbumId);
    }

    public function getPlaylisDefault($user_id)
    {
        $playlistControll = new PlaylistController();
        return $playlistControll->playlist_default($user_id);
    }

    function convertURL($str)
    {
        $str = strtolower($str);
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", "a", $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", "e", $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", "i", $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", "o", $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", "u", $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", "y", $str);
        $str = preg_replace("/(đ)/", "d", $str);
        $str = preg_replace("/(!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'| |\"|\&|\#|\[|\]|~|$|_)/", "-", $str);
        $str = preg_replace("/(-+-)/", "-", $str);
        $str = preg_replace("/(^\-+|\-+$)/", "", $str);
        $str = preg_replace("/(-)/", " ", $str);
        return $str;
    }

    public function getSongsAlbumId($album_id, $limit)
    {
        $album_song = new AlbumSongController();
        return $album_song->getSongsAlbumId($album_id, $limit);
    }

    //build filter/section menu
    public function getSectionMenu($aParentModule = false)
    {
        $pages_msf = phpFox::getLib('session')->get('pages_msf');
        $aFilterMenu = array();
        if (!defined('PHPFOX_IS_USER_PROFILE'))
        {
            $aFilterMenu[phpFox::getPhrase('musicsharing.browse_all')] = $aParentModule ? ($aParentModule['module_id'] . "." . $aParentModule['item_id'] . ".musicsharing.view_all") : 'musicsharing.view_all';
            $aFilterMenu[] = true;

            $aFilterMenu[phpFox::getPhrase('musicsharing.all_songs')] = $aParentModule ? ($aParentModule['module_id'] . "." . $aParentModule['item_id'] . ".musicsharing.song") : 'musicsharing.song';
            $aFilterMenu[phpFox::getPhrase('musicsharing.my_songs')] = $aParentModule ? ($aParentModule['module_id'] . "." . $aParentModule['item_id'] . ".musicsharing.song.view_my") : 'musicsharing.song.view_my';

            if (phpFox::isModule('friend') && !phpFox::getParam('core.friends_only_community'))
            {
                $aFilterMenu[phpFox::getPhrase('musicsharing.friends_songs')] = $aParentModule ? ($aParentModule['module_id'] . "." . $aParentModule['item_id'] . ".musicsharing.song.view_friend") : 'musicsharing.song.view_friend';
            }

            $aFilterMenu[] = true;

            $aFilterMenu[phpFox::getPhrase('musicsharing.all_albums')] = $aParentModule ? ($aParentModule['module_id'] . "." . $aParentModule['item_id'] . ".musicsharing.album") : 'musicsharing.album';
            //$aFilterMenu['My Albums'] = 'musicsharing.album.view_my';
            $aFilterMenu[phpFox::getPhrase('musicsharing.my_albums')] = $aParentModule ? ($aParentModule['module_id'] . "." . $aParentModule['item_id'] . ".musicsharing.myalbums") : 'musicsharing.myalbums';

            $aFilterMenu[] = true;

            $aFilterMenu[phpFox::getPhrase('musicsharing.all_playlists')] = $aParentModule ? ($aParentModule['module_id'] . "." . $aParentModule['item_id'] . ".musicsharing.playlist") : 'musicsharing.playlist';
            //$aFilterMenu['My Playlists'] = 'musicsharing.playlist.view_my';
            $aFilterMenu[phpFox::getPhrase('musicsharing.my_playlists')] = $aParentModule ? ($aParentModule['module_id'] . "." . $aParentModule['item_id'] . ".musicsharing.myplaylists") : 'musicsharing.myplaylists';

            $aFilterMenu[] = true;

            $aFilterMenu[phpFox::getPhrase('musicsharing.uploaders')] = $aParentModule ? ($aParentModule['module_id'] . "." . $aParentModule['item_id'] . ".musicsharing.artist") : 'musicsharing.artist';
        }

        if (isset($aParentModule['use_timeline']) && $aParentModule['use_timeline'])
        {
            
        }
        else
        {
            phpFox::getLib('template')->buildSectionMenu('musicsharing.song', $aFilterMenu);
        }
        
    }

    public function playlist_updatePlaycount($playlist_id)
    {
        $sSql = "UPDATE `" . Phpfox::getT('m2bmusic_playlist') . "` set
                    `play_count` = `play_count` + 1
                    WHERE playlist_id = " . (int) $playlist_id;
        
        return $this->database()->query($sSql);
    }

    public function check($sModule, $iItemId, $iUserId, $iPrivacy, $iIsFriend = null, $bReturn = false, $module_id = null)
    {
        $bCanViewItem = true;
        if ($iUserId != phpFox::getUserId() && !phpFox::getUserParam('privacy.can_view_all_items'))
        {
            switch ($iPrivacy) {
                case 1:
                    if ((int) $iIsFriend <= 0)
                    {
                        $bCanViewItem = false;
                    }
                    break;
                case 2:
                    if ((int) $iIsFriend > 0)
                    {
                        $bCanViewItem = true;
                    }
                    else
                    {
                        if (!phpFox::getService('friend')->isFriendOfFriend($iUserId))
                        {
                            $bCanViewItem = false;
                        }
                    }
                    break;
                case 3:
                    $bCanViewItem = false;
                    break;
                case 4:
                    if (phpFox::isUser())
                    {
                        $iCheck = (int) $this->database()->select('COUNT(privacy_id)')
                                        ->from(phpFox::getT('privacy'), 'p')
                                        ->join(phpFox::getT('friend_list_data'), 'fld', 'fld.list_id = p.friend_list_id AND fld.friend_user_id = ' . phpFox::getUserId())
                                        ->where('p.module_id = \'' . (($module_id == null) ? $this->database()->escape($sModule) : $module_id) . '\' AND p.item_id = ' . (int) $iItemId . '')
                                        ->execute('getSlaveField');
                        if ($iCheck === 0)
                        {
                            $bCanViewItem = false;
                        }
                    }
                    else
                    {
                        $bCanViewItem = false;
                    }
                    break;
            }
        }

        if ($bReturn === true)
        {
            return $bCanViewItem;
        }
        //echo $iPrivacy . "~~";
        if ($bCanViewItem === false)
        {
            phpFox::getLib('url')->send('privacy.invalid');
            // echo "iv";
        }
    }

    public function getUserById($id = null)
    {
        if ($id == null)
        {
            $id = phpFox::getUserId();
        }
        $id = (int) $id;
        $user = $this->database()->select(' * ')
                ->from(phpFox::getT('user'), 'u')
                ->where("u.user_id = $id")
                ->execute('getSlaveRow');
        return $user;
    }

    public function getTopPlaylistCache()
    {
        $pls = null;
        $prefix = phpFox::getParam(array('db', 'prefix'));
        $sCacheId = $this->cache()->set("msharing_newpl_home");
        // var_dump($this->cache());
        if (!($pls = $this->cache()->get($sCacheId, 60)))
        {
            $pls = $this->getPlaylists(0, 10, $prefix . "m2bmusic_playlist.creation_date desc", "", "Where search = 1 AND (SELECT count(*) FROM " . $prefix . "m2bmusic_playlist_song WHERE " . $prefix . "m2bmusic_playlist_song.playlist_id = " . $prefix . "m2bmusic_playlist.playlist_id) > 0");
            $this->cache()->save($sCacheId, $pls);
            echo "cached";
        }
        return $pls;
    }

    /**
     * @see Phpfox_Image_Library_Gd
     */
    public function createThumbnail()
    {
        $aRows = $this->database()
                ->select('m.*')
                ->from(phpFox::getT('music_album'), 'm')
                ->execute('getSlaveRows');
        foreach ($aRows as $aRow)
        {
            if (($aRow['image_path']))
            {
                $oImage = phpFox::getLib('image');
                $sMusicPath = PHPFOX_DIR_FILE . PHPFOX_DS . 'pic' . PHPFOX_DS . 'music' . PHPFOX_DS;
                $sMusicSharingPath = PHPFOX_DIR_FILE . 'pic' . PHPFOX_DS . 'musicsharing' . PHPFOX_DS;
                $sDestination = $sMusicSharingPath . $aRow['image_path'];
                $url = $sMusicPath . $aRow['image_path'];

                $url = str_replace("\\\\", "/", $url) . "";
                $url = str_replace("\\", "/", $url) . "";
                $url = str_replace("//", "/", $url) . "";
                
                $sDestination = str_replace("\\\\", "/", $sDestination) . "";
                $sDestination = str_replace("\\", "/", $sDestination) . "";
                $sDestination = str_replace("//", "/", $sDestination) . "";
                // Check folder exist.
                $sFolder = dirname($sDestination);
                if (!file_exists($sFolder))
                {
                    mkdir($sFolder, 0777);
                }
                // Copy original image.
                copy(sprintf($url, ""), sprintf($sDestination, ''));
                $_aPhotoSizes = array(50, 90, 115, 255, 345);
                foreach($_aPhotoSizes as $iSize)
                {
                    $oImage->createThumbnail(sprintf($url, ""), sprintf($sDestination, '_' . $iSize), $iSize, $iSize);
                }
            }
        }
    }

}

?>
