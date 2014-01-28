<?php

/**
 * author: Nguyen Cong Minh
 */
//  METHODS IN THIS CLASS
//  playlist()
//  playlist_list()
//  playlist_list_total()
//  playlist_delete()
//  playlist_edit()
require_once "PlaylistModel.php";
include_once 'YounetDb.php';

class PlaylistController extends YounetDb {
    //create playlist
    function playlist_create($playlist) {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_client=utf8", $connection);
        mysql_query("SET character_set_connection=utf8", $connection);
        $title = $playlist->get_title();
        $title_url = $this->convertURL($playlist->get_title_url());
        $user_id = $playlist->get_userid();
        $description = $playlist->get_description();
        $playlist_image = $playlist->get_photo();
        $search = $playlist->get_search();
        $is_download = $playlist->get_is_download();
        $createion_date = $playlist->get_createion_date();
        $modified_date = $playlist->get_modified_date();
        $profile = $playlist->get_profile();
        $privacy = $playlist->get_privacy();
        $privacy_comment = $playlist->get_privacy_comment();
        if (!phpFox::getLib('session')->get('pages_msf')) {
            $strSQL = "INSERT INTO `" . $this->prefix() . "m2bmusic_playlist` (
                    `title` ,
                    `title_url` ,
                    `user_id` ,
                    `description` ,
                    `playlist_image` ,
                    `search` ,
                    `is_download`,
                    `creation_date` ,
                    `modified_date` ,
                    `profile`,
                    `privacy`,
                    `privacy_comment`
                    )
                    VALUES (
                    '" . htmlspecialchars($title, ENT_QUOTES) . "', '$title_url', '$user_id', '" . htmlspecialchars($description, ENT_QUOTES) . "', '$playlist_image', '$search','$is_download', '$createion_date', '$modified_date', '$profile', '$privacy', '$privacy_comment'
                    );";
        } else {
            $page_msf = phpFox::getLib('session')->get('pages_msf');
            $module_id = $page_msf['module_id'];
            $item_id = $page_msf['item_id'];
            $strSQL = "INSERT INTO `" . $this->prefix() . "m2bmusic_playlist` (
                    `title` ,
                    `title_url` ,
                    `user_id` ,
                    `description` ,
                    `playlist_image` ,
                    `search` ,
                    `is_download`,
                    `creation_date` ,
                    `modified_date` ,
                    `profile`,
                    `privacy`,
                    `privacy_comment`,
                    `module_id`,
                    `item_id`
                    )
                    VALUES (
                    '" . htmlspecialchars($title, ENT_QUOTES) . "', '$title_url', '$user_id', '" . htmlspecialchars($description, ENT_QUOTES) . "', '$playlist_image', '$search','$is_download', '$createion_date', '$modified_date', '$profile', '$privacy', '$privacy_comment','$module_id','$item_id'
                    );";
        }

        mysql_query($strSQL);
        $lastID = mysql_insert_id();
        if (!$lastID)
            $lastID = -1;
        //mysql_close($connection);
        return $lastID;
    }

    //Get list playlists
    function playlist_list($start=NULL, $limit=NULL, $sort_by=NULL, $select=NULL, $where= null) {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_results=utf8", $connection);
        $sTable_name = $this->prefix() . "m2bmusic_playlist";
        $sPrefix = $this->prefix();
        $prefix = $sPrefix;
        $iCurrentUserId = phpFox::getUserId();
        $sql = "SELECT ";
        if ($select)
            $sql .= " $select ";
        else
            $sql .= $this->prefix() . "m2bmusic_playlist.*, " . $this->prefix() . "user.user_name, " . $this->prefix() . "user.full_name, YEAR(" . $this->prefix() . "m2bmusic_playlist.creation_date) as year, UNIX_TIMESTAMP(" . $this->prefix() . "m2bmusic_playlist.creation_date) as `_creation_date`  ";
        $sql .= " FROM " . $this->prefix() . "m2bmusic_playlist LEFT JOIN " . $this->prefix() . "user ON " . $this->prefix() . "m2bmusic_playlist.user_id = " . $this->prefix() . "user.user_id ";
        if ($where)
            $sql .= " {$where} ";
        
		$sub_query = "SELECT ".$prefix."friend_list_data.friend_user_id FROM ".$prefix."friend_list INNER JOIN ".$prefix."privacy ON ".$prefix."privacy.friend_list_id = ".$prefix."friend_list.list_id INNER JOIN ".$prefix."user ON ".$prefix."user.user_id = ".$prefix."privacy.user_id INNER JOIN ".$prefix."friend_list_data ON ".$prefix."friend_list.list_id = ".$prefix."friend_list_data.list_id WHERE ".$prefix."privacy.module_id = \"musicsharing_playlist\" AND ".$prefix."privacy.item_id = ".$prefix."m2bmusic_playlist.playlist_id";
		$currentUserId = phpFox::getUserId();
		$sub_query_fof = "select f.user_id from ".$prefix."friend as f inner Join (select ffxf.friend_user_id from ".$prefix."friend as ffxf where ffxf.is_page=0 and ffxf.user_id = $currentUserId) as sf ON sf.friend_user_id = f.friend_user_id join ".$prefix."user as u ON u.user_id = f.friend_user_id";
		
		$sql .= (" AND (".$prefix."m2bmusic_playlist.user_id = $currentUserId OR ".$prefix."m2bmusic_playlist.privacy IN (0)
				OR (".$prefix."m2bmusic_playlist.privacy = 4 AND $currentUserId IN ($sub_query))
				OR (".$prefix."m2bmusic_playlist.privacy = 3 AND ".$prefix."m2bmusic_playlist.user_id = $currentUserId)
				OR (privacy IN (1) AND ".$prefix."m2bmusic_playlist.user_id IN (SELECT fr.user_id FROM " . $prefix . "friend as fr WHERE fr.friend_user_id = $currentUserId))".
				" OR (privacy IN (2) AND (".$prefix."m2bmusic_playlist.user_id IN ($sub_query_fof) OR ".$prefix."m2bmusic_playlist.user_id IN (SELECT fr.user_id FROM " . $prefix . "friend as fr WHERE fr.friend_user_id = $currentUserId)))".
				")"
				);
		
        if ($pages_msf = phpFox::getLib('session')->get('pages_msf')) {
            $sql.= "AND ( module_id = '" . $pages_msf['module_id'] . "'" . ' AND item_id = ' . $pages_msf['item_id'] . ' ) ';
        }else{
            $sql.= " AND ( module_id IS NULL OR module_id = \"\") ";
        }
        if ($sort_by)
            $sql .= " ORDER BY $sort_by ";
        else
            $sql .= "  ORDER BY creation_date DESC";

        // START AND LIMIT
        if ($start || $limit)
            $sql .= " LIMIT";
        if ($start)
            $sql .= " {$start}, ";
        if ($limit)
            $sql .= " {$limit} ";
        $aPlaylist = array();
        $playlistlist = mysql_query($sql) or die(mysql_error() . " <b>SQL was: </b>$sql");
        $index = 0;
        
        while ($obj = mysql_fetch_assoc($playlistlist)) {
            $playlist_id = isset($obj['playlist_id']) ? $obj['playlist_id'] : 0;
            $sql = "Select * from " . $this->prefix() . "m2bmusic_playlist_song where playlist_id = " . $playlist_id;
            $num_track = mysql_num_rows(mysql_query($sql));
            $index++;
            if ($num_track == "")
                $num_track = 0;
            $obj['num_track'] = $num_track;
            $obj['index'] = $index;
            $aPlaylist[] = $obj;
        }
        
        return $aPlaylist;
    }

    function playlist_list_id($id_playlist, $user_id) {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_client=utf8", $connection);
        mysql_query("SET character_set_connection=utf8", $connection);
        $sql = "select * from " . $this->prefix() . "m2bmusic_playlist where playlist_id=" . $id_playlist . " and user_id=" . $user_id;
        $a = mysql_query($sql);
        $a = mysql_fetch_assoc($a);
        return $a;
    }

    function playlist_list_before_id($id_playlist, $user_id) {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_client=utf8", $connection);
        mysql_query("SET character_set_connection=utf8", $connection);
        $sql = "select * from " . $this->prefix() . "m2bmusic_playlist where user_id=" . $user_id . " and order_id<(select al1.order_id from " . $this->prefix() . "m2bmusic_playlist al1 where al1.playlist_id=" . $id_playlist . ") order by order_id desc limit 1";
        $a = mysql_query($sql);
        $a = mysql_fetch_assoc($a);
        return $a;
    }

    function playlist_list_after_id($id_playlist, $user_id) {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_client=utf8", $connection);
        mysql_query("SET character_set_connection=utf8", $connection);
        $sql = "select * from " . $this->prefix() . "m2bmusic_playlist where user_id=" . $user_id . " and order_id>(select al1.order_id from " . $this->prefix() . "m2bmusic_playlist al1 where al1.playlist_id=" . $id_playlist . ") order by order_id asc limit 1";
        $a = mysql_query($sql);
        $a = mysql_fetch_assoc($a);
        return $a;
    }

    function playlist_update($id_playlist, $order_id) {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_client=utf8", $connection);
        mysql_query("SET character_set_connection=utf8", $connection);
        $sql = "update " . $this->prefix() . "m2bmusic_playlist set order_id=" . $order_id . " where playlist_id=" . $id_playlist;
        $a = mysql_query($sql);
        $a = mysql_fetch_assoc($a);
        return $a;
    }

    function playlist_update_order($user_id) {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_client=utf8", $connection);
        mysql_query("SET character_set_connection=utf8", $connection);
        $sql = "update " . $this->prefix() . "m2bmusic_playlist set order_id=order_id+1 where user_id=" . $user_id;
        $a = mysql_query($sql);
        $a = mysql_fetch_assoc($a);
        return $a;
    }

    function playlist_info($idplaylist = null) {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_results=utf8", $connection);
		
		$friend_sql_select = "";
		$friend_sql_from = "";
		if (phpFox::isModule('friend'))
		{
			$friend_sql_select = "f.friend_id AS is_friend, ";
			$friend_sql_from = " LEFT JOIN " . phpFox::getT('friend') . " AS f ON f.user_id = al.user_id AND f.friend_user_id = " . phpFox::getUserId() . " ";
			//$this->database()->select('f.friend_id AS is_friend, ')->leftJoin(phpFox::getT('friend'), 'f', "f.user_id = blog.user_id AND f.friend_user_id = " . phpFox::getUserId());
		}
        $sql = "SELECT $friend_sql_select al.*,l.like_id as is_liked,us.user_name,us.full_name,us.user_image".
                " FROM " . $this->prefix() . "m2bmusic_playlist as al" .
                " LEFT JOIN " . $this->prefix() . "like as l ON l.type_id = 'musicsharing_playlist' AND l.item_id = al.playlist_id AND l.user_id = '" . phpFox::getUserId() . "'"
                ." LEFT JOIN ".$this->prefix()."user as us ON us.user_id = al.user_id "  
				. $friend_sql_from
				. " WHERE playlist_id = '$idplaylist' LIMIT 1";

        $playlist = mysql_query($sql);
        $playlist_info = mysql_fetch_assoc($playlist);
        $sql = "Select * from " . $this->prefix() . "m2bmusic_playlist_song where playlist_id = " . $idplaylist;
        $num_track = mysql_num_rows(mysql_query($sql));
        //mysql_close($connection);     
        $playlist_info['num_track'] = $num_track;
        $playlist_info['title'] = mysql_escape_string((isset($playlist_info['title']))?$playlist_info['title']:"");
        return $playlist_info;
    }

    function playlist_default($user_id) {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_results=utf8", $connection);
        $playlist = mysql_query("SELECT " . $this->prefix() . "m2bmusic_playlist.* from " . $this->prefix() . "m2bmusic_playlist WHERE user_id = '$user_id' AND profile = '1' LIMIT 1");
        $playlist_info = mysql_fetch_assoc($playlist);
        return $playlist_info['playlist_id'];
    }

    function playlist_setprofile($playlist_id, $user_id) {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_results=utf8", $connection);
        mysql_query("Update " . $this->prefix() . "m2bmusic_playlist set profile = '0' where user_id = " . $user_id);
        mysql_query("Update " . $this->prefix() . "m2bmusic_playlist set profile = '1' where user_id = '" . $user_id . "' AND playlist_id = '" . $playlist_id . "'");
        return true;
    }

    //Get total playlist
    function playlist_list_total($start=NULL, $limit=NULL, $sort_by=NULL, $where = NULL) {
        $playlist_data = $this->playlist_list($start, $limit, $sort_by, 'COUNT(playlist_id) AS playlist_total', $where);
        return $playlist_data[0]['playlist_total'];
    }

    //Edit playlist
    function playlist_edit($playlist) {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_client=utf8", $connection);
        mysql_query("SET character_set_connection=utf8", $connection);
        $playlist_id = $playlist->get_playlist_id();
        $title = $playlist->get_title();
        $title_url = $this->convertURL($playlist->get_title_url());
        $description = $playlist->get_description();
        $playlist_image = $playlist->get_photo();
        $search = $playlist->get_search();
        $is_download = $playlist->get_is_download();
        $modified_date = $playlist->get_modified_date();
        $privacy = $playlist->get_privacy();
        $privacy_comment = $playlist->get_privacy_comment();
        $strSQL = "UPDATE `" . $this->prefix() . "m2bmusic_playlist` set
                    `title` = '" . htmlspecialchars($title, ENT_QUOTES) . "',
                    `title_url` = '$title_url',
                    `description` = '" . htmlspecialchars($description, ENT_QUOTES) . "',
                    `playlist_image` = '$playlist_image',
                    `search` = '$search',
                    `is_download` = '$is_download',
                    `modified_date` = '$modified_date',
                    `privacy` = '$privacy',
                    `privacy_comment` = '$privacy_comment'
                    WHERE playlist_id = $playlist_id";

        mysql_query($strSQL);

        //mysql_close($connection);
        return $playlist_id;
    }

    //Delete playlist
    function playlist_delete($playlist_id) {
        $connection = $this->ConnectDatabase();
        $sql = "SELECT * FROM " . $this->prefix() . "m2bmusic_playlist WHERE playlist_id='{$playlist_id}'";
        $playlist = mysql_fetch_assoc(mysql_query($sql));
        if (empty($playlist))
            return FALSE;
        //get list song belong to playlist
        $songs = mysql_query("SELECT * FROM " . $this->prefix() . "m2bmusic_playlist_song WHERE playlist_id='{$playlist_id}'");
        while ($song = mysql_fetch_assoc($songs)) {
            $songID = $song['song_id'];
            //Delete song in playlist
            $deletePlaylistSong = mysql_query("DELETE FROM " . $this->prefix() . "m2bmusic_playlist_song WHERE song_id='$songID' LIMIT 1");
        }
        
        // Delete images.
        $aSizes = array('', '_thumb', '_thumb_115x115');
        foreach($aSizes as $sSize)
        {
            $p = PHPFOX_DIR_FILE . 'pic' . PHPFOX_DS . 'musicsharing' . PHPFOX_DS . str_replace("_thumb", $sSize, $playlist['playlist_image']);
            
            file_exists($p) ? @unlink($p) : null;
        }
        
        mysql_query("DELETE FROM " . $this->prefix() . "m2bmusic_playlist WHERE playlist_id='{$playlist_id}' LIMIT 1");
          
        return TRUE;
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

    function album_getTotalPlaylist() {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_client=utf8", $connection);
        mysql_query("SET character_set_connection=utf8", $connection);
        $sql = "select count(*) as count from " . $this->prefix() . "m2bmusic_playlist";
		$prefix=phpFox::getParam(array('db', 'prefix'));
		$page_msf = phpFox::getLib('session')->get('pages_msf');
		if ($page_msf) {
			$module_id = $page_msf['module_id'];
			$item_id = $page_msf['item_id'];
			$sql .= " WHERE ".$prefix."m2bmusic_playlist.module_id = \"$module_id\" AND  ".$prefix."m2bmusic_playlist.item_id = $item_id";
		}else{
			$sql .= " WHERE ".$prefix."m2bmusic_playlist.module_id IS NULL";
		}
        $a = mysql_query($sql);
        $a = mysql_fetch_assoc($a);
        return $a["count"];
    }
	
	public function playlist_updatePlaycount ($playlist_id) {
		$strSQL = "UPDATE `" . $this->prefix() . "m2bmusic_playlist` set
                    `play_count` = `play_count` + 1
                    WHERE playlist_id = $playlist_id";

        mysql_query($strSQL);
	}

}

?>
