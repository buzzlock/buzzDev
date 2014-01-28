<?php
/**
 * author: Nguyen Cong Minh
 */ 
//  METHODS IN THIS CLASS

//  playlist_song()

//  playlist_song_list()

//  playlist_song_list_total()

//  playlist_song_delete()

//  playlist_song_edit()
include_once "PlaylistSongModel.php";
include_once 'YounetDb.php';

class PlaylistSongController extends YounetDb
{
  	//create playlist_song
    function playlist_song_create($playlist_song)
    {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_client=utf8", $connection);
        mysql_query("SET character_set_connection=utf8",  $connection);
        $playlist_id = $playlist_song->get_playlist_id();
        $album_song_id = $playlist_song->get_album_song_id();

        $strSQL = "INSERT INTO `".$this->prefix()."m2bmusic_playlist_song` (
                    `playlist_id` ,
                    `album_song_id` 
                    )
                    VALUES (
                    '$playlist_id', '$album_song_id'
                    );";
        
        mysql_query($strSQL);
        $lastID = mysql_insert_id();
        if(!$lastID)
            $lastID = -1;
        //mysql_close($connection);
        return $lastID;
    }
    
    //Get list playlist_songs
    function playlist_song_list($playlist_id = NULL, $start = NULL, $limit = NULL, $sort_by = NULL, $select = NULL)
    {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_results=utf8", $connection);
        // GENERATE QUERY
        $sql = "SELECT Distinct";
        if ($select)
            $sql .= " $select ";
        else
            $sql .= " " . $this->prefix() . "m2bmusic_album_song.*," . $this->prefix() . "m2bmusic_playlist_song.song_id as playlist_song_id , " . $this->prefix() . "m2bmusic_album.is_download , " . $this->prefix() . "m2bmusic_album.user_id ";
        $sql .= " FROM " . $this->prefix() . "m2bmusic_playlist_song";
        $sql .= " Left join " . $this->prefix() . "m2bmusic_album_song on " . $this->prefix() . "m2bmusic_album_song.song_id =  " . $this->prefix() . "m2bmusic_playlist_song.album_song_id 
            LEFT JOIN " . $this->prefix() . "m2bmusic_playlist ON " . $this->prefix() . "m2bmusic_playlist_song.playlist_id = " . $this->prefix() . "m2bmusic_playlist.playlist_id 
            Left join " . $this->prefix() . "m2bmusic_album on " . $this->prefix() . "m2bmusic_album_song.album_id =  " . $this->prefix() . "m2bmusic_album.album_id " .
            " Left join " . Phpfox::getT('m2bmusic_category') . " on " . Phpfox::getT('m2bmusic_album_song') . ".cat_id =  " . Phpfox::getT('m2bmusic_category') . ".cat_id " . 
            " Left join " . Phpfox::getT('m2bmusic_singer') . " on " . Phpfox::getT('m2bmusic_album_song') . ".singer_id =  " . Phpfox::getT('m2bmusic_singer') . ".singer_id ";
        //add order for song
        $sql.=" LEFT JOIN " . $this->prefix() . "m2bmusic_song_playlist_order ON (" . $this->prefix() . "m2bmusic_playlist_song.album_song_id  = " . $this->prefix() . "m2bmusic_song_playlist_order.song_id AND " . $this->prefix() . "m2bmusic_song_playlist_order.user_id = " . phpFox::getUserId() . " AND " . $this->prefix() . "m2bmusic_song_playlist_order.playlist_id  = $playlist_id" . ")";
        $sql.=" WHERE " . $this->prefix() . "m2bmusic_playlist_song.playlist_id = $playlist_id";
        //end
        // CUSTOM
        if ($sort_by)
            $sql .= " ORDER BY $sort_by ";
        else
            $sql .= "  ORDER BY " . $this->prefix() . "m2bmusic_album_song.song_id ASC";

        if ($start || $limit)
            $sql .= " LIMIT";
        if ($start)
            $sql .= " {$start}, ";
        if ($limit)
            $sql .= " {$limit} ";
            
        $aPlaylists = array();
        $playlist_songlist = mysql_query($sql) or die(mysql_error() . " <b>SQL was: </b>$sql");
        $index = 0;
        while ($obj = mysql_fetch_assoc($playlist_songlist))
        {
            $obj["filesize"] = isset($obj["filesize"]) ? $obj["filesize"] : 0;
            $obj["filesize"] = round($obj["filesize"] / (1024 * 1024), 2);
            $index++;
            $obj['index'] = $index;
            $aPlaylists[] = $obj;
        }

        return $aPlaylists;
    }

    //Get total playlist_song
  	function playlist_song_list_total($playlist_id=NULL,$start=NULL, $limit=NULL, $sort_by=NULL)
  	{
  		$playlist_song_data = $this->playlist_song_list($playlist_id, $start, $limit, $sort_by, 'COUNT(*) AS playlist_song_total');
		return $playlist_song_data[0]['playlist_song_total'];
  	}
  	//Delete playlist_song
  	function playlist_song_delete($playlist_song_id)
  	{
  	    $connection = $this->ConnectDatabase();
        
            // Delete the relationship.
            mysql_query("DELETE FROM " . Phpfox::getT('m2bmusic_song_playlist_order') . " WHERE song_id='{$playlist_song_id}' LIMIT 1");

            mysql_query("DELETE FROM " . Phpfox::getT('m2bmusic_playlist_song') . " WHERE album_song_id='{$playlist_song_id}' LIMIT 1");

            return TRUE;
  	}
    function check_playlist($idPlaylist,$idSong)
    {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_client=utf8", $connection);
        mysql_query("SET character_set_connection=utf8",  $connection);
        $sql = "Select count(*) as num from ".$this->prefix()."m2bmusic_playlist_song where playlist_id = $idPlaylist AND album_song_id = $idSong";
        $src = mysql_query($sql);
        $data = mysql_fetch_assoc($src);
        $num =$data['num'] ;
        //mysql_close($connection);
        return $num;
    }
    
}	

?>
