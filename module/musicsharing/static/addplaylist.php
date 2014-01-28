<?php
if (!@define(PHPFOX))
    @define(PHPFOX,1);
require_once "../../../include/setting/server.sett.php";
   if(isset($_REQUEST['musicid']))
        $song_id = $_REQUEST['musicid'];
    else
       $song_id= 0; 
    if(isset($_REQUEST['userid']))
        $user_id = $_REQUEST['userid'];
    else
        $user_id = 0;
    if(isset($_REQUEST['pathmodule']))
        $path = $_REQUEST['pathmodule'];
    else
        $path = "";
     if(isset($_REQUEST['pathurl']))
        $pathurl = $_REQUEST['pathurl'];
    else
        $pathurl = "";
    echo '<head>
            <link rel="stylesheet" type="text/css" href="'.$pathurl.'theme/frontend/default/style/default/css/layout.css"/>
            <link rel="stylesheet" type="text/css" href="'.$pathurl.'theme/frontend/default/style/default/css/common.css" />
            <link rel="stylesheet" type="text/css" href="'.$pathurl.'theme/frontend/default/style/default/css/thickbox.css" />
            <style type="text/css">
                 body {
                 background: none ;
                 }
                .table {
                    padding-left: 20px;
                }
            </style>
            </head> ';
     $connection = mysql_connect($_CONF['db']['host'], $_CONF['db']['user'], $_CONF['db']['pass']);
     
     $prefix = $_CONF['db']['prefix'];
     if (!$connection)
        die("can't connect server");
    $db_selected = mysql_select_db($_CONF['db']['name']);
	mysql_query("SET character_set_client=utf8", $connection);
     mysql_query("SET character_set_connection=utf8",  $connection);
	 mysql_query("SET character_set_results=utf8", $connection);  
    if (!$db_selected)
        die ("have not database");
  
     $sqlplaylist = "Select mp.playlist_id,mp.title from ".$prefix."m2bmusic_playlist mp where user_id = ".$user_id;
     $sourcePlaylist = mysql_query($sqlplaylist) or die(mysql_error()." <b>SQL was: </b>$sqlplaylist");  
     $aPlaylist = array();
     while($obj = mysql_fetch_assoc($sourcePlaylist) )
    {
            $aPlaylist[] = $obj;  
            
    }          
    $aTemp = array();
    foreach($aPlaylist as $playlist)
    {
        $idPlaylist = $playlist['playlist_id'];
        $sql = "Select count(*) as num from ".$prefix."m2bmusic_playlist_song where playlist_id = $idPlaylist AND album_song_id = $song_id";
        $src = mysql_query($sql);
        $data = mysql_fetch_assoc($src);
        $num =$data['num'] ;
        if($num <= 0)
            $aTemp[] = $playlist;
    }  
  echo ' <form name="addplaylist" action="addplaylist.php" method="post">
  <div class="table">';
  if(count($aTemp) > 0)
  {
        $index = 0;
  echo' <div class="table_left" style="font-weight: bold;">Playlists</div>
        <div class="table_right" style="margin-bottom: 10px;">
        <select name="playlistid" id="playlistid" style="min-width: 200px;" >';     
                 
         foreach ($aTemp as $iPlaylist)
         {
             $index++;
            echo '<option value="'.$iPlaylist["playlist_id"].'">'.$iPlaylist["title"].'</option>';
         } 
        
  echo ' </select> 
  </div>
              </div>
                <div class="table_clear">             
                 <input type="submit" id="submit" name="submit" value="Add to playlist" class="button" onclick="self.parent.tb_remove();" />          
                 </div>
                 <input type="hidden" name="songid" value="'.$song_id.'"> 
                 <input type="hidden" name="user_id" value="'.$user_id.'"> 
                 </form>';
  }
  else
    echo' Please insert new playlist ! <a title="Add new playlist" onclick="self.parent.parent.location.href=\''.$path.'/createplaylist\';self.parent.tb_remove();" href="#"> Click here to create new </a>';  
 if(isset($_POST['submit']))          
 {
    
     $playlistid = $_POST['playlistid'];
     $songid = $_POST['songid'];
     $user_id = $_POST['user_id'];
      mysql_query("SET character_set_client=utf8", $connection);
        mysql_query("SET character_set_connection=utf8",  $connection);
     $strSQL = "INSERT INTO `".$prefix."m2bmusic_playlist_song` (
                    `playlist_id` ,
                    `album_song_id` 
                    )
                    VALUES (
                    '$playlistid', '$songid'
                    );";
        
     mysql_query($strSQL);
     
     //$last_insert_id = mysql_insert_id();
      $strSQL = "INSERT INTO `".$prefix."m2bmusic_song_playlist_order` (
                    `user_id` ,
                    `playlist_id`,
                    `album_id`,
                    `song_id`,
                    `value`
                    )
                    VALUES (
                    $user_id,$playlistid,0,$songid,".-time()."
                    );";
        
     mysql_query($strSQL);
 }
?>