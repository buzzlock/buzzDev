<?php

/**
 * author: Nguyen Cong Minh
 */
//  METHODS IN THIS CLASS
//  album_create()
//  album_list()
//  album_list_total()
//  album_delete()
//  album_edit()
//  album_rating()
//  album_like()
require_once "AlbumModel.php";
include_once 'YounetDb.php';

class AlbumController extends YounetDb
{
    //create album
    function album_create($album) {
        $connection = $this->ConnectDatabase();
        
        mysql_query("SET character_set_client=utf8", $connection);
        mysql_query("SET character_set_connection=utf8", $connection);
        
        $album instanceof AlbumModel;

        $title = $album->get_title();
        $title_url = $this->convertURL($album->get_title_url());
        $user_id = $album->get_userid();
        $description = $album->get_description();
        $album_image = $album->get_photo();
        $search = $album->get_search();
        $createion_date = $album->get_createion_date();
        $modified_date = $album->get_modified_date();
        $play_count = $album->get_play_count();
        $download_count = $album->get_download_count();
        $is_download = $album->get_is_download();
        $is_featured = $album->get_is_featured();
        $privacy = $album->get_privacy();
        $privacy_comment = $album->get_privacy_comment();
        
        if (!phpFox::getLib('session')->get('pages_msf')) {
            $strSQL = "INSERT INTO `" . $this->prefix() . "m2bmusic_album` (
                    `title` ,
                    `title_url` ,
                    `user_id` ,
                    `description` ,
                    `album_image` ,
                    `search` ,
                    `creation_date` ,
                    `modified_date` ,
                    `play_count` ,
                    `download_count` ,
                    `is_featured` ,
                    `is_download`,
                    `privacy`,
                    `privacy_comment`
                    )
                    VALUES (
                    '" . htmlspecialchars($title, ENT_QUOTES) . "', '$title_url', '$user_id', '" . htmlspecialchars($description, ENT_QUOTES) . "', '$album_image', '$search', '$createion_date', '$modified_date', '$play_count', '$download_count', '$is_featured','$is_download','$privacy','$privacy_comment'
                    );";
        } else {
            $page_msf = phpFox::getLib('session')->get('pages_msf');
            $module_id = $page_msf['module_id'];
            $item_id = $page_msf['item_id'];
            $strSQL = "INSERT INTO `" . $this->prefix() . "m2bmusic_album` (
                    `title` ,
                    `title_url` ,
                    `user_id` ,
                    `description` ,
                    `album_image` ,
                    `search` ,
                    `creation_date` ,
                    `modified_date` ,
                    `play_count` ,
                    `download_count` ,
                    `is_featured` ,
                    `is_download`,
                    `privacy`,
                    `privacy_comment`,
                    `module_id`,
                    `item_id`
                    )
                    VALUES (
                    '" . htmlspecialchars($title, ENT_QUOTES) . "', '$title_url', '$user_id', '" . htmlspecialchars($description, ENT_QUOTES) . "', '$album_image', '$search', '$createion_date', '$modified_date', '$play_count', '$download_count', '$is_featured','$is_download','$privacy','$privacy_comment','$module_id','$item_id'
                    );";
        }
        
        mysql_query($strSQL);
        $lastID = mysql_insert_id();
        if (!$lastID)
            $lastID = -1;
        //mysql_close($connection);
        return $lastID;
    }

    //Get list albums
    function album_list($start=NULL, $limit=NULL, $sort_by=NULL, $select=NULL, $where = null, $cross_module_id = false) {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_results=utf8", $connection);
        $sTable_name = $this->prefix() . "m2bmusic_album";
        $sPrefix = $this->prefix();
        $prefix = $sPrefix;
        $iCurrentUserId = phpFox::getUserId();
        
        // GENERATE QUERY
        $sql = "SELECT ";
        if ($select)
            $sql .= " $select ";
        else
            $sql .= $this->prefix() . "m2bmusic_album.*, " . $this->prefix() . "user.user_id, " . $this->prefix() . "user.full_name, " . $this->prefix() . "user.user_name, YEAR(" . $this->prefix() . "m2bmusic_album.creation_date) as year ";
        $sql .= " FROM " . $this->prefix() . "m2bmusic_album LEFT JOIN " . $this->prefix() . "user ON " . $this->prefix() . "m2bmusic_album.user_id = " . $this->prefix() . "user.user_id ";

        // CUSTOM
        if ($where)
            $sql .= " WHERE " . $where;

		$currentUserId = phpFox::getUserId();
		$sub_query = "SELECT ".$prefix."friend_list_data.friend_user_id FROM ".$prefix."friend_list INNER JOIN ".$prefix."privacy ON ".$prefix."privacy.friend_list_id = ".$prefix."friend_list.list_id INNER JOIN ".$prefix."user ON ".$prefix."user.user_id = ".$prefix."privacy.user_id INNER JOIN ".$prefix."friend_list_data ON ".$prefix."friend_list.list_id = ".$prefix."friend_list_data.list_id WHERE ".$prefix."privacy.module_id = \"musicsharing_album\" AND ".$prefix."privacy.item_id = ".$prefix."m2bmusic_album.album_id";
		$sub_query_fof = "select f.user_id from ".$prefix."friend as f inner Join (select ffxf.friend_user_id from ".$prefix."friend as ffxf where ffxf.is_page=0 and ffxf.user_id = $currentUserId) as sf ON sf.friend_user_id = f.friend_user_id join ".$prefix."user as u ON u.user_id = f.friend_user_id";

		$sql .= (" AND (".$prefix."m2bmusic_album.user_id = $currentUserId OR ".$prefix."m2bmusic_album.privacy IN (0) " .
				" OR (".$prefix."m2bmusic_album.privacy = 4 AND $currentUserId IN ($sub_query)) " .
				" OR (".$prefix."m2bmusic_album.privacy = 3 AND ".$prefix."m2bmusic_album.user_id = $currentUserId) " .
				" OR (privacy IN (1) AND ".$prefix."m2bmusic_album.user_id IN (SELECT fr.user_id FROM " . $prefix . "friend as fr WHERE fr.friend_user_id = $currentUserId))".
				" OR (privacy IN (2) AND (".$prefix."m2bmusic_album.user_id IN ($sub_query_fof) OR ".$prefix."m2bmusic_album.user_id IN (SELECT fr.user_id FROM " . $prefix . "friend as fr WHERE fr.friend_user_id = $currentUserId)))".
				")"
				);

        if ($pages_msf = phpFox::getLib('session')->get('pages_msf')) {
            $sql.= " AND ( module_id = '" . $pages_msf['module_id'] . "'" . ' AND item_id = ' . $pages_msf['item_id'] . ' ) ';
        }else{
            $sql.= ($cross_module_id===true?"":" AND ( module_id IS NULL OR module_id = \"\") ");
        }
        if ($sort_by)
            $sql .= " ORDER BY " . $sort_by;
        else
            $sql .= "  ORDER BY creation_date DESC";
        // START AND LIMIT
        if ($start || $limit)
            $sql .= " LIMIT";
        if ($start)
            $sql .= " {$start}, ";
        if ($limit)
            $sql .= " {$limit} ";
        
        $albumlist = mysql_query($sql) or die(mysql_error() . "<b>SQL was: </b>$sql");
        $aAlbum = null;
        $index = 0;
        while ($obj = mysql_fetch_assoc($albumlist)) {
            $album_id = isset($obj['album_id']) ? $obj['album_id'] : 0;
            $sql = "Select * from " . $this->prefix() . "m2bmusic_album_song where album_id = " . $album_id;
            $num_track = mysql_num_rows(mysql_query($sql));
            $index++;
            if ($num_track == "")
                $num_track = 0;
            
            $obj['num_track'] = $num_track;
            $obj['index'] = $index;
            $aAlbum[] = $obj;
        }
        return $aAlbum;
    }

    function album_list_id($id_album, $user_id) {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_client=utf8", $connection);
        mysql_query("SET character_set_connection=utf8", $connection);
        $sql = "select * from " . $this->prefix() . "m2bmusic_album where album_id=" . $id_album . " and user_id=" . $user_id;
        $a = mysql_query($sql);
        $a = mysql_fetch_assoc($a);
		//var_dump̣($a);
        return $a;
    }

    function album_list_before_id($id_album, $user_id) {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_client=utf8", $connection);
        mysql_query("SET character_set_connection=utf8", $connection);
        $sql = "select * from " . $this->prefix() . "m2bmusic_album where user_id=" . $user_id . " and order_id<(select al1.order_id from " . $this->prefix() . "m2bmusic_album al1 where al1.album_id=" . $id_album . ") order by order_id desc limit 1";
        $a = mysql_query($sql);
        $a = mysql_fetch_assoc($a);
        return $a;
    }

    function album_list_after_id($id_album, $user_id) {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_client=utf8", $connection);
        mysql_query("SET character_set_connection=utf8", $connection);
        $sql = "select * from " . $this->prefix() . "m2bmusic_album where user_id=" . $user_id . " and order_id>(select al1.order_id from " . $this->prefix() . "m2bmusic_album al1 where al1.album_id=" . $id_album . ") order by order_id asc limit 1";
        $a = mysql_query($sql);
        $a = mysql_fetch_assoc($a);
        return $a;
    }

    function album_update($id_album, $order_id) {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_client=utf8", $connection);
        mysql_query("SET character_set_connection=utf8", $connection);
        $sql = "update " . $this->prefix() . "m2bmusic_album set order_id=" . $order_id . " where album_id=" . $id_album;
        $a = mysql_query($sql);
        $a = mysql_fetch_assoc($a);
        return $a;
    }

    function album_update_order($user_id) {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_client=utf8", $connection);
        mysql_query("SET character_set_connection=utf8", $connection);
        $sql = "update " . $this->prefix() . "m2bmusic_album set order_id=order_id+1 where user_id=" . $user_id;
        $a = mysql_query($sql);
        $a = mysql_fetch_assoc($a);
        return $a;
    }

    function album_getTotalAlbum($user_id=0) {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_client=utf8", $connection);
        mysql_query("SET character_set_connection=utf8", $connection);
        $sql = "select count(*) as count from " . $this->prefix() . "m2bmusic_album";
		$prefix=phpFox::getParam(array('db', 'prefix'));
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

    //Get total album
    function album_list_total($start=NULL, $limit=NULL, $sort_by=NULL, $where = null) {
        $album_data = $this->album_list($start, $limit, $sort_by, 'COUNT(album_id) AS album_total', $where);
        return $album_data[0]['album_total'];
    }

    //Edit album
    function album_edit($album) {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_client=utf8", $connection);
        mysql_query("SET character_set_connection=utf8", $connection);
        $album_id = $album->get_album_id();
        $title = $album->get_title();
        $title_url = $this->convertURL($album->get_title_url());
        $description = $album->get_description();
        $album_image = $album->get_photo();
        $search = $album->get_search();
        $modified_date = $album->get_modified_date();
        $is_download = $album->get_is_download();
        $privacy = $album->get_privacy();
        $privacy_comment = $album->get_privacy_comment();
        $strSQL = "UPDATE `" . $this->prefix() . "m2bmusic_album` set
                    `title` = '" . htmlspecialchars($title, ENT_QUOTES) . "',
                    `title_url` = '$title_url',
                    `description` = '" . htmlspecialchars($description, ENT_QUOTES) . "',
                    `album_image` = '$album_image',
                    `search` = '$search',
                    `modified_date` = '$modified_date',
                    `is_download` = '$is_download' ,
                    `privacy` = '$privacy' ,
                    `privacy_comment` = '$privacy_comment'
                    WHERE album_id = $album_id";
        mysql_query($strSQL);
        //mysql_close($connection);
        return $album_id;
    }

    //Delete album
    function album_delete($album_id) {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_results=utf8", $connection);
        $sql = "SELECT * FROM " . $this->prefix() . "m2bmusic_album WHERE album_id='{$album_id}'";
        $album = mysql_fetch_assoc(mysql_query($sql));
        if (empty($album))
            return FALSE;
        
        //get list song belong to album
        $songs = mysql_query("SELECT * FROM " . $this->prefix() . "m2bmusic_album_song WHERE album_id='{$album_id}'");
        while ($song = mysql_fetch_assoc($songs)) {
            $songID = $song['song_id'];
            //Delete song belong to this album
            $deleteAlbumSong = mysql_query("DELETE FROM " . $this->prefix() . "m2bmusic_album_song WHERE song_id='$songID' LIMIT 1");
            //Delete song in playlist
            $deletePlaylistSong = mysql_query("DELETE FROM " . $this->prefix() . "m2bmusic_playlist_song WHERE album_song_id='$songID' LIMIT 1");

            $urllink = "../file/musicsharing/" . $song['url'];
            if (file_exists($urllink)) {
                @unlink($urllink);
            }
        }
        // Delete images.
        $aSizes = array('', '_thumb', '_thumb_115x115', '_thumb_345x250');
        foreach($aSizes as $sSize)
        {
            $p = PHPFOX_DIR_FILE . 'pic' . PHPFOX_DS . 'musicsharing' . PHPFOX_DS . str_replace("_thumb", $sSize, $album['album_image']);
            
            file_exists($p) ? @unlink($p) : null;
        }
        
        mysql_query("DELETE FROM " . $this->prefix() . "m2bmusic_album WHERE album_id='{$album_id}' LIMIT 1");
        
        return TRUE;
    }

    //Delete albums
    function albums_delete($sIds) {
        //delete all comment in relations
        if (phpFox::isModule('feed')) {
            $aIds = explode(",", $sIds);
            //explode ids
            foreach ($aIds as $key => $value) {
                $iId = (int) $value;
                phpFox::getService('feed.process')->delete('musicsharing_album', $iId);
				phpFox::getLib("database")->delete(phpFox::getT("pages_feed"), 'type_id = \'musicsharing_pagesalbum\' AND item_id = ' . $iId);
                $this->album_delete($iId);
            }
        }
        return TRUE;
    }

    function album_rating($album_id) {

    }

    public function replaceTitle($str) {
        $str = preg_replace("/(&)/", "&amp;", $str);
        $str = preg_replace("/( )/", "&nbsp;", $str);
        $str = preg_replace("/(\")/", "&quot;", $str);
        $str = preg_replace("/(\')/", "&apos; ", $str);
        $str = preg_replace("/(<)/", "&lt;", $str);
        $str = preg_replace("/(>)/", "&gt;", $str);
        return $str;
    }

    public function getSongsInAlbum($idalbum, $user_log)
    {
        $connection = $this->ConnectDatabase();
        $sql = "SELECT ".$this->prefix()."m2bmusic_album_song.*, " . $this->prefix() . "m2bmusic_singer.title AS singer_title FROM ".$this->prefix()."m2bmusic_album_song ";
        $sql.=" LEFT JOIN ".$this->prefix()."m2bmusic_song_playlist_order ON (".$this->prefix()."m2bmusic_album_song.song_id  = ".$this->prefix()."m2bmusic_song_playlist_order.song_id AND ".$this->prefix()."m2bmusic_song_playlist_order.user_id = ".$user_log." AND ".$this->prefix()."m2bmusic_song_playlist_order.album_id  = $idalbum" .")"  ;
        $sql.=" LEFT JOIN " . $this->prefix() . "m2bmusic_singer ON (" . $this->prefix() . "m2bmusic_album_song.singer_id = " . $this->prefix() . "m2bmusic_singer.singer_id)";
        $sql.=" WHERE ".$this->prefix()."m2bmusic_album_song.album_id = $idalbum";
        $sql.=" ORDER BY value ASC";
        mysql_query("SET character_set_results=utf8", $connection);
        $resource = mysql_query($sql) or die(mysql_error()." <b>SQL was: </b>$sql");
        
        $arResult = array();
        while( $musiclist=mysql_fetch_assoc($resource) )
        {
            $arResult[] = $musiclist;
        }
        return $arResult;
    }
    
    function getArtists($start=null, $limit=null, $sort_by=null, $select=null, $where=NULL) {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_results=utf8", $connection);
        $sql = "Select distinct ";
        $sql .= " " . $this->prefix() . "user.*, (select count(*) from " . $this->prefix() . "m2bmusic_album as pp where pp.user_id = " . $this->prefix() . "user.user_id) as " . $this->prefix() . "album_count from " . $this->prefix() . "user INNER JOIN " . $this->prefix() . "m2bmusic_album ON " . $this->prefix() . "user.user_id = " . $this->prefix() . "m2bmusic_album.user_id";
        // CUSTOM
        if ($where)
            $sql .= " WHERE " . $where;
        if ($pages_msf = phpFox::getLib('session')->get('pages_msf')) {
            if ($where) {
                $sql.=" AND ";
            } else {
                $sql.=" WHERE ";
            }

            $sql.= " ( module_id = '" . $pages_msf['module_id'] . "'" . ' AND item_id = ' . $pages_msf['item_id'] . ' ) ';
        }
        if ($sort_by)
            $sql .= " ORDER BY " . $sort_by . " DESC ";
        else
            $sql .= "  ORDER BY " . $this->prefix() . "m2bmusic_album.creation_date DESC";

        // START AND LIMIT
        if ($start || $limit)
            $sql .= " LIMIT";
        if ($start)
            $sql .= " {$start}, ";
        if ($limit)
            $sql .= " {$limit} ";
            
        $artistlist = mysql_query($sql) or die(mysql_error() . "<b>SQL was: </b>$sql");
        $aArtist = null;
        $index = 0;
        while ($obj = mysql_fetch_assoc($artistlist)) {
            $user_id = $obj['user_id'];
            mysql_query("SET character_set_results=utf8", $connection);
            $sql = "Select * from " . $this->prefix() . "m2bmusic_album where user_id = " . $user_id . " AND search = 1";
            $num_album = mysql_num_rows(mysql_query($sql));
            ;
            $index++;
            if ($num_album == "")
                $num_album = 0;
            //{
            $obj['num_album'] = $num_album;
            $obj['index'] = $index;
            $aArtist[] = $obj;
            //}
        }
        // mysql_close($connection);
        return $aArtist;
    }

    function artist_total($where = null,$sort = null) {
        $artist_data = $this->getArtists(null, null, $sort, null, $where);
        return count($artist_data);
    }

}

?>
