<?php

/**
 * author: Nguyen Cong Minh
 */
//  METHODS IN THIS CLASS
//  album_song_create()
//  album_song_list()
//  album_song_list_total()
//  album_song_delete()
//  album_song_edit()

require_once "AlbumSongModel.php";
include_once 'YounetDb.php';

class AlbumSongController extends YounetDb {

    //create album_song
    function album_song_create($song) {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_client=utf8", $connection);
        mysql_query("SET character_set_connection=utf8", $connection);
        $title = $song->get_title();
        $title_url = $this->convertURL($song->get_title_url());
        $album_id = $song->get_album_id();
        $filesize = $song->get_filesize();
        $url = $song->get_url();
        $ext = $song->get_ext();
        $comment_count = $song->get_comment_count();
        $lyric = $song->get_lyric();
        $cat_id = $song->get_cat_id();
        $singer_id = $song->get_singer_id();
        $other_singer = $song->get_other_singer();
        $play_count = $song->get_play_count();
        $download_count = $song->get_download_count();
        $strSQL = "INSERT INTO `" . $this->prefix() . "m2bmusic_album_song` (
                `title` ,
                `title_url` ,
                `album_id` ,
                `filesize` ,
                `url` ,
                `ext` ,
                `comment_count` ,
                `lyric` ,
                `cat_id` ,
                `singer_id` ,
                `other_singer` ,
                `play_count` ,
                `download_count`
                )
                VALUES (
                 '" . htmlspecialchars($title, ENT_QUOTES) . "', '$title_url', '$album_id', '$filesize', '$url', '$ext', '$comment_count', '" . htmlspecialchars($lyric, ENT_QUOTES) . "' , '$cat_id', '$singer_id', '" . htmlspecialchars($other_singer, ENT_QUOTES) . "' , '$play_count', '$download_count'
                );";
        mysql_query($strSQL);
        $lastID = mysql_insert_id();
        if (!$lastID)
            $lastID = -1;
        //mysql_close($connection);
        return $lastID;
    }

    //Get list album_songs
    function album_song_list($start=NULL, $limit=NULL, $sort_by=NULL, $select=NULL, $where=NULL, $album_id=null) {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_results=utf8", $connection);
        // GENERATE QUERY
        $sql = "SELECT ";
        if ($select)
            $sql .= " $select ";
        else
            $sql .= "" . $this->prefix() . "m2bmusic_album_song.*, " . $this->prefix() . "m2bmusic_album.album_id, UNIX_TIMESTAMP(" . $this->prefix() . "m2bmusic_album.creation_date) as `_creation_date`, " . $this->prefix() . "m2bmusic_album.is_download, " . $this->prefix() . "m2bmusic_album.title as album_title, " . $this->prefix() . "m2bmusic_singer.title as singer_title, " . $this->prefix() . "m2bmusic_singer.singer_id," . $this->prefix() . "user.*," . $this->prefix() . "m2bmusic_category.title as cat_title";
        $sql .= " FROM " . $this->prefix() . "m2bmusic_album_song LEFT JOIN " . $this->prefix() . "m2bmusic_album ON " . $this->prefix() . "m2bmusic_album_song.album_id = " . $this->prefix() . "m2bmusic_album.album_id LEFT JOIN " . $this->prefix() . "m2bmusic_singer ON " . $this->prefix() . "m2bmusic_singer.singer_id = " . $this->prefix() . "m2bmusic_album_song.singer_id LEFT JOIN " . $this->prefix() . "user ON " . $this->prefix() . "m2bmusic_album.user_id = " . $this->prefix() . "user.user_id  LEFT JOIN " . $this->prefix() . "m2bmusic_category on " . $this->prefix() . "m2bmusic_album_song.cat_id = " . $this->prefix() . "m2bmusic_category.cat_id ";

        //add order for song
        if ($album_id != null) {

            $sql.=" LEFT JOIN " . $this->prefix() . "m2bmusic_song_playlist_order ON (" . $this->prefix() . "m2bmusic_album_song.song_id  = " . $this->prefix() . "m2bmusic_song_playlist_order.song_id AND " . $this->prefix() . "m2bmusic_song_playlist_order.user_id = " . phpFox::getUserId() . " AND " . $this->prefix() . "m2bmusic_song_playlist_order.album_id  = $album_id" . ")";
        }

        $sql.=" WHERE 1 = 1 ";
        //end
        if ($where) {
            $sql.= " AND " . $where;
        }

        //filter module_id
        $m2bmusic_album_table_name = $this->prefix() . "m2bmusic_album";
        if ($pages_msf = phpFox::getLib('session')->get('pages_msf')) {
            $sql.= " AND ( module_id = '" . $pages_msf['module_id'] . "' AND item_id = " . $pages_msf['item_id'] . " ) ";
        }else{
            $sql.= " AND ( $m2bmusic_album_table_name.module_id IS NULL OR $m2bmusic_album_table_name.module_id = \"\") ";
        }

		$prefix = $this->prefix();
		$sub_query = "SELECT ".$prefix."friend_list_data.friend_user_id FROM ".$prefix."friend_list INNER JOIN ".$prefix."privacy ON ".$prefix."privacy.friend_list_id = ".$prefix."friend_list.list_id INNER JOIN ".$prefix."user ON ".$prefix."user.user_id = ".$prefix."privacy.user_id INNER JOIN ".$prefix."friend_list_data ON ".$prefix."friend_list.list_id = ".$prefix."friend_list_data.list_id WHERE ".$prefix."privacy.module_id = \"musicsharing_album\" AND ".$prefix."privacy.item_id = ".$prefix."m2bmusic_album.album_id";
		$currentUserId = phpFox::getUserId();
		$sub_query_fof = "select f.user_id from ".$prefix."friend as f inner Join (select ffxf.friend_user_id from ".$prefix."friend as ffxf where ffxf.is_page=0 and ffxf.user_id = $currentUserId) as sf ON sf.friend_user_id = f.friend_user_id join ".$prefix."user as u ON u.user_id = f.friend_user_id";
		
		$sql .= (" AND (".$prefix."m2bmusic_album.user_id = $currentUserId OR ".$prefix."m2bmusic_album.privacy IN (0) " .
				" OR (".$prefix."m2bmusic_album.privacy = 4 AND $currentUserId IN ($sub_query)) " .
				" OR (".$prefix."m2bmusic_album.privacy = 3 AND ".$prefix."m2bmusic_album.user_id = $currentUserId) " .
				" OR (".$prefix."m2bmusic_album.privacy IN (1) AND ".$prefix."m2bmusic_album.user_id IN (SELECT fr.user_id FROM " . $prefix . "friend as fr WHERE fr.friend_user_id = $currentUserId))".
				" OR (".$prefix."m2bmusic_album.privacy IN (2) AND (".$prefix."m2bmusic_album.user_id IN ($sub_query_fof) OR ".$prefix."m2bmusic_album.user_id IN (SELECT fr.user_id FROM " . $prefix . "friend as fr WHERE fr.friend_user_id = $currentUserId)))".
				")"
			);
		
		if ($pages_msf = phpFox::getLib('session')->get('pages_msf')) {
            $sql.= "AND ( module_id = '" . $pages_msf['module_id'] . "'" . ' AND item_id = ' . $pages_msf['item_id'] . ' ) ';
        }
        if ($sort_by)
            $sql .= " ORDER BY " . $sort_by;
        else
            $sql .= "  ORDER BY " . $this->prefix() . "m2bmusic_album_song.song_id DESC";
        // START AND LIMIT
        if ($start || $limit)
            $sql .= " LIMIT";
        if ($start)
            $sql .= " {$start}, ";
        if ($limit)
            $sql .= " {$limit} ";
		
        $album_songlist = mysql_query($sql) or die(mysql_error() . " <b>SQL was: </b>$sql");
        $aAlbumSong = null;
        $index = 0;
        while ($obj = mysql_fetch_assoc($album_songlist)) {

			if(isset($obj["filesize"])){
				$obj["filesize"]  =  @round( $obj["filesize"]/(1024*1024),2) or 0 ;
			}else{
				$obj["filesize"] = 0;
			}
            //$obj["filesize"] = 0;
            $index++;
            $obj['index'] = $index;
            $aAlbumSong[] = $obj;
        }
        return $aAlbumSong;
    }

    //Get total album_song
    function album_song_list_total($start=NULL, $limit=NULL, $sort_by=NULL, $where=NULL) {
        $album_song_data = $this->album_song_list($start, $limit, $sort_by, 'COUNT(song_id) AS album_song_total', $where);
        return $album_song_data[0]['album_song_total'];
    }

    //Edit album_song
    function album_song_edit($song) {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_client=utf8", $connection);
        mysql_query("SET character_set_connection=utf8", $connection);
        $song_id = $song->get_song_id();
        $title = $song->get_title();
        $title_url = $this->convertURL($song->get_title_url());
        $lyric = $song->get_lyric();
        $privacy = $song->get_privacy();
        $cat_id = $song->get_cat_id();
        $singer_id = $song->get_singer_id();
        $other_singer = $song->get_other_singer();
        $other_singer_title_url = $this->convertURL($song->get_other_singer());
        $strSQL = "Update `" . $this->prefix() . "m2bmusic_album_song` set
                `title` = '" . htmlspecialchars($title, ENT_QUOTES) . "',
                `title_url` = '$title_url',
                `lyric` = '" . htmlspecialchars($lyric, ENT_QUOTES) . "',
                `cat_id` = '$cat_id',
                `singer_id` = '$singer_id',
                `other_singer` = '" . htmlspecialchars($other_singer, ENT_QUOTES) . "',
                `other_singer_title_url` = '" . htmlspecialchars($other_singer_title_url, ENT_QUOTES) . "',
                `privacy` = '$privacy'
                WHERE song_id = $song_id";
        mysql_query($strSQL);
        //mysql_close($connection);
        return $song_id;
    }

    //Delete album_song
    function album_song_delete($album_song_id) {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_results=utf8", $connection);
        $sql = "SELECT * FROM " . $this->prefix() . "m2bmusic_album_song WHERE song_id='{$album_song_id}'";
        $album_song = mysql_fetch_assoc(mysql_query($sql));
        if (empty($album_song))
            return FALSE;
        mysql_query("DELETE FROM " . $this->prefix() . "m2bmusic_album_song WHERE song_id='{$album_song_id}' LIMIT 1");
        mysql_query("DELETE FROM " . $this->prefix() . "m2bmusic_playlist_song WHERE album_song_id='{$album_song_id}' LIMIT 1");
        $urllink = "../file/musicsharing/" . $album_song['url'];
        if (file_exists($urllink)) {
            @unlink($urllink);
        }
        //mysql_close($connection);
        return TRUE;
    }

    //get information of one song
    function album_song_info($album_song_id) {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_results=utf8", $connection);
        
        $music = mysql_query("SELECT " . $this->prefix() . "m2bmusic_album_song.* , " . $this->prefix() . "m2bmusic_album.is_download , " . $this->prefix() . "m2bmusic_album.user_id, " . $this->prefix() . "m2bmusic_singer.title AS singer_title
            FROM " . $this->prefix() . "m2bmusic_album_song 
            LEFT JOIN " . $this->prefix() . "m2bmusic_album ON " . $this->prefix() . "m2bmusic_album_song.album_id = " . $this->prefix() . "m2bmusic_album.album_id 
            LEFT JOIN " . $this->prefix() . "m2bmusic_singer ON " . $this->prefix() . "m2bmusic_album_song.singer_id = " . $this->prefix() . "m2bmusic_singer.singer_id 
            WHERE song_id = '$album_song_id' 
            LIMIT 1");
        $track_info = mysql_fetch_assoc($music);
        
        return $track_info;
    }

    function getVoteBySongId($song_id) {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_results=utf8", $connection);
        
        $strSql = ' SELECT sr.*
                    FROM `' . $this->prefix() . 'm2bmusic_song_rating` AS sr
                    WHERE sr.`item_id` = ' . $song_id . ';';
        
        $arResults = mysql_query($strSql) or die(mysql_error() . " <b>SQL was: </b>$strSql");
        
        $aVote = array();
        while ($obj = mysql_fetch_assoc($arResults))
        {
            $aVote[] = $obj;
        }
        return $aVote;
    }
    
    function checkVoted($iSongId, $iUserId) {
        
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_results=utf8", $connection);
        
        $strSql = ' SELECT sr.*
                    FROM `' . $this->prefix() . 'm2bmusic_song_rating` AS sr
                    WHERE sr.`item_id` = ' . (int) $iSongId . ' AND sr.`user_id` = '. (int) $iUserId .';';
        
        $arResults = mysql_query($strSql) or die(mysql_error() . " <b>SQL was: </b>$strSql");
        
        $aVote = array();
        while ($obj = mysql_fetch_assoc($arResults))
        {
            $aVote[] = $obj;
        }
        
        if (count($aVote) > 0)
            return true;
        else
            return false;
    }
    
    //get First song in album.
    function get_firstSong($album_id) {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_results=utf8", $connection);
        $music = mysql_query("SELECT " . $this->prefix() . "m2bmusic_album_song.* , " . $this->prefix() . "m2bmusic_album.is_download , " . $this->prefix() . "m2bmusic_album.user_id from " . $this->prefix() . "m2bmusic_album_song LEFT JOIN " . $this->prefix() . "m2bmusic_album ON " . $this->prefix() . "m2bmusic_album_song.album_id = " . $this->prefix() . "m2bmusic_album.album_id WHERE " . $this->prefix() . "m2bmusic_album_song.album_id = '$album_id' order by " . $this->prefix() . "m2bmusic_album_song.song_id ASC LIMIT 1");
        $track_info = mysql_fetch_assoc($music);
        return $track_info;
    }

    function updateCounterComment($song_id, $bool) {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_results=utf8", $connection);
        $sql = "";
        if ($bool == true)
            $sql = "UPDATE " . $this->prefix() . "m2bmusic_album_song SET comment_count  = comment_count   + 1 WHERE song_id = " . $song_id;
        else
            $sql = "UPDATE " . $this->prefix() . "m2bmusic_album_song SET comment_count = comment_count - 1 WHERE song_id = " . $song_id;
        $song = mysql_query($sql);
        //mysql_close($connection);
        return true;
    }

    function album_getTotalSong() {
        $connection = $this->ConnectDatabase();
		$prefix=phpFox::getParam(array('db', 'prefix'));
        mysql_query("SET character_set_client=utf8", $connection);
        mysql_query("SET character_set_connection=utf8", $connection);
        $sql = "select count(*) as count from " . $this->prefix() . "m2bmusic_album_song inner join ".$prefix."m2bmusic_album on ".$prefix."m2bmusic_album.album_id = ".$prefix."m2bmusic_album_song.album_id";
		
		$page_msf = phpFox::getLib('session')->get('pages_msf');
		if ($page_msf) {
			$module_id = $page_msf['module_id'];
			$item_id = $page_msf['item_id'];
			$sql .= " WHERE ".$prefix."m2bmusic_album.module_id = \"$module_id\" AND  ".$prefix."m2bmusic_album.item_id = $item_id";
		}else{
			$sql .= " WHERE ".$prefix."m2bmusic_album.module_id IS NULL";
		}
		
        $a = mysql_query($sql);
        $a = mysql_fetch_assoc($a);
        return $a["count"];
    }

    public function getSongsAlbumId($album_id, $limit) {
        $sql = "select * from " . $this->prefix() . "m2bmusic_album_song where album_id=" . $album_id . " limit " . $limit;
        $album_songlist = mysql_query($sql) or die(mysql_error() . " <b>SQL was: </b>$sql");
        $aAlbumSong = array();
        while ($obj = mysql_fetch_assoc($album_songlist)) {
            $aAlbumSong[] = $obj;
        }
        return $aAlbumSong;
    }

}

?>
