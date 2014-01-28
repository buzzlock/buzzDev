<?php

###############################################################
# File Download 1.31
###############################################################
###############################################################
# Sample call:
#    download.php?f=phptutorial.zip
#
# Sample call (browser will try to save with new file name):
#    download.php?f=phptutorial.zip&fc=php123tutorial.zip
###############################################################

// Allow direct file download (hotlinking)?
// Empty - allow hotlinking
// If set to nonempty value (Example: example.com) will only allow downloads when referrer contains this text
define('ALLOWED_REFERRER', '');

// Download folder, i.e. folder where you keep all files for download.
// MUST end with slash (i.e. "/" )
//echo $_SERVER['DOCUMENT_ROOT'];
//$currentDirectory = array_pop(explode("/", getcwd()));
//echo $currentDirectory;
//echo BASE_DIR;
// log downloads?  true/false
define('LOG_DOWNLOADS',true);

// log file name
define('LOG_FILE','downloads.log');


define('PHPFOX',1);


   
    //$sName='12user_id';

    require_once "../../../include/setting/server.sett.php";

    $connection = mysql_connect($_CONF['db']['host'], $_CONF['db']['user'], $_CONF['db']['pass']);
    $prefix = $_CONF['db']['prefix'];
    if (!$connection)
        die("can't connect server");

    $db_selected = mysql_select_db($_CONF['db']['name']);
    if (!$db_selected)
        die ("have not database");
    //$sql="select * from ".$prefix."setting where module_id="."'core' and var_name="."'session_prefix'";
    //$result_actual=mysql_query($sql) or die(mysql_error());
    //$row = mysql_fetch_array($result_actual, MYSQL_ASSOC);
    //$actual=$row['value_actual'];
    //$actual=$actual.$sName;
    if(isset($_GET['idsong'])==false)
    {
        $url=$_GET['f'];
        $index1 = strpos($url,"file/musicsharing");
        $url = substr($url,$index1+1 + strlen("file/musicsharing"));
		$filemp3=$url;
		
        $sql="select * from ".$prefix."m2bmusic_album_song where url = '".$filemp3."'";
        $result=mysql_query($sql);
        $row=mysql_fetch_array($result);
        if(isset($row['song_id'])==null)
            die("This song is not found");
        else
            $_GET['idsong']=$row['song_id'];
						
    }

    $sql="select * from ".$prefix."m2bmusic_album_song where song_id=".$_GET['idsong'];
    $result=mysql_query($sql) or die(mysql_error());
    $row = mysql_fetch_array($result, MYSQL_ASSOC);

    session_start();
    $song_id=$_GET['idsong']; 
    // if(isset($_SESSION['downloadlist_downloadlist'])!=null)
        // $iUserId=$_SESSION['downloadlist_downloadlist'];
    // else
        // $iUserId=0;

	// if($iUserId==0)
		// die("You must login to download this song");
		
    // if($iUserId==0)
		// die("You do not have permission to download this song!");
	
    if(false)
    {
        $sql="select * from ".$prefix."m2bmusic_album_song song,".$prefix."m2bmusic_album album where song.album_id=album.album_id and song.song_id=".$song_id;
        $result=mysql_query($sql);
        $row=mysql_fetch_array($result);
        if($iUserId==$row['user_id'])
        {
            $sql="select * from ".$prefix."user where user_id=".$iUserId;
            $result=mysql_query($sql);
            $row=mysql_fetch_array($result);
            $user_group_id=$row['user_group_id'];
            $sql="select * from ".$prefix."m2bmusic_settings where user_group_id=".$user_group_id." and name="."'can_download_song'";
            $result=mysql_query($sql);
            $row=mysql_fetch_array($result);
            $default_value=0;
            if(isset($row['default_value'])!=null)
                $default_value=$row['default_value'];
            if($default_value==0)
                die("Admin do not allow you download this file!");
        }
        else
        {

            $sql="select * from ".$prefix."m2bmusic_downloadlist where dl_song_id=".$song_id." and user_id=".$iUserId;
            $result=mysql_query($sql);
            $row=mysql_fetch_array($result);
            if(isset($row['dl_id'])==null)
            {
                $sql="select * from ".$prefix."m2bmusic_album_song song,".$prefix."m2bmusic_downloadlist download where download.user_id=".$iUserId." and download.dl_album_id=song.album_id and song.song_id=".$song_id;
                $result=mysql_query($sql);
                $row=mysql_fetch_array($result);
                if(isset($row['dl_id'])==null)
                    die("You do not have permission to download this file!");
            }
        }
    }
    else
    {
			if($iUserId>0)
			{
				$sql="select * from ".$prefix."user where user_id=".$iUserId;
            $result=mysql_query($sql);
            $row=mysql_fetch_array($result);
            $user_group_id=$row['user_group_id'];
            $sql="select * from ".$prefix."m2bmusic_settings where user_group_id=".$user_group_id." and name="."'can_download_song'";
            $result=mysql_query($sql);
            $row=mysql_fetch_array($result);
            $default_value=0;
            if(isset($row['default_value'])!=null)
                $default_value=$row['default_value'];
            if($default_value==0)
                die("Admin do not allow you download this file!");
			}
    }

    if(isset($_GET['idsong']))
    {
        $idsong =  $_GET['idsong'];
        $sql = "UPDATE ".$prefix."m2bmusic_album_song SET download_count = download_count + 1 WHERE song_id = ".$idsong;
        $sqlalbum = "UPDATE ".$prefix."m2bmusic_album SET download_count = download_count + 1 WHERE album_id = (select album_id from ".$prefix."m2bmusic_album_song where song_id = ".$idsong.")";
        mysql_query($sqlalbum) or die(mysql_error()." <b>SQL was: </b>$sqlalbum");
        $resource = mysql_query($sql) or die(mysql_error()." <b>SQL was: </b>$sql");
    }

// Allowed extensions list in format 'extension' => 'mime type'
// If myme type is set to empty string then script will try to detect mime type 
// itself, which would only work if you have Mimetype or Fileinfo extensions
// installed on server.
$allowed_ext = array (

  // archives
  'zip' => 'application/zip',

  // documents
  'pdf' => 'application/pdf',
  'doc' => 'application/msword',
  'xls' => 'application/vnd.ms-excel',
  'ppt' => 'application/vnd.ms-powerpoint',
  
  // executables
  'exe' => 'application/octet-stream',

  // images
  'gif' => 'image/gif',
  'png' => 'image/png',
  'jpg' => 'image/jpeg',
  'jpeg' => 'image/jpeg',

  // audio
  'mp3' => 'audio/mpeg',
  'wav' => 'audio/x-wav',

  // video
  'mpeg' => 'video/mpeg',
  'mpg' => 'video/mpeg',
  'mpe' => 'video/mpeg',
  'mov' => 'video/quicktime',
  'avi' => 'video/x-msvideo'
);



####################################################################
###  DO NOT CHANGE BELOW
####################################################################

// If hotlinking not allowed then make hackers think there are some server problems
if (ALLOWED_REFERRER !== ''
&& (!isset($_SERVER['HTTP_REFERER']) || strpos(strtoupper($_SERVER['HTTP_REFERER']),strtoupper(ALLOWED_REFERRER)) === false)
) {
  die("Internal server error. Please contact system administrator.");
}

// Make sure program execution doesn't time out
// Set maximum script execution time in seconds (0 means no limit)
set_time_limit(0);

if (!isset($_GET['f']) || empty($_GET['f'])) {
  die("Please specify file name for download.");
}

// Nullbyte hack fix
if (strpos($_GET['f'], "\0") !== FALSE) die('');

// Get real file name.
// Remove any path info to avoid hacking by adding relative path, etc.
$fname = basename($_GET['f']);
//echo $fname;
// Check if the file exists
// Check in subfolders too
function find_file ($dirname, $fname, &$file_path) {
  $dir = opendir($dirname);

  while ($file = readdir($dir)) {
    if (empty($file_path) && $file != '.' && $file != '..') {
      if (is_dir($dirname.'/'.$file)) {
        find_file($dirname.'/'.$file, $fname, $file_path);
      }
      else {
        if (file_exists($dirname.'/'.$fname)) {
          $file_path = $dirname.'/'.$fname;
          return;
        }
      }
    }
  }

} // find_file

// get full file path (including subfolders)
$file_path = '';
$path_tmp = $_GET['f'];  
$index1 = strpos($path_tmp,'/musicsharing/') + 1;  

$path_tmp = substr($path_tmp,$index1);
$index1 = strpos($path_tmp,"/");
$dir = substr($path_tmp,0,$index1);
define('BASE_DIR2','../../../file/'.$dir)  ;
find_file(BASE_DIR2, $fname, $file_path);

if (!is_file($file_path)) {
  die("File does not exist. Make sure you specified correct file name."); 
}

// file size in bytes
$fsize = filesize($file_path); 

// file extension
$fext = strtolower(substr(strrchr($fname,"."),1));

// check if allowed extension
if (!array_key_exists($fext, $allowed_ext)) {
  die("Not allowed file type."); 
}

// get mime type
if ($allowed_ext[$fext] == '') {
  $mtype = '';
  // mime type is not set, get from server settings
  if (function_exists('mime_content_type')) {
    $mtype = mime_content_type($file_path);
  }
  else if (function_exists('finfo_file')) {
    $finfo = finfo_open(FILEINFO_MIME); // return mime type
    $mtype = finfo_file($finfo, $file_path);
    finfo_close($finfo);  
  }
  if ($mtype == '') {
    $mtype = "application/force-download";
  }
}
else {
  // get mime type defined by admin
  $mtype = $allowed_ext[$fext];
}

// Browser will try to save file with this filename, regardless original filename.
// You can override it if needed.

if (!isset($_GET['fc']) || empty($_GET['fc'])) {
  $asfname = $fname;
}
else {
  // remove some bad chars
  $asfname = str_replace(array('"',"'",'\\','/'), '', $_GET['fc']);
  if ($asfname === '') $asfname = 'musicsharing';
}
$fls= substr($asfname, -4);
if($fls !=".mp3")
{
    $asfname.=".mp3";
}
// set headers
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-Type: $mtype");
header("Content-Disposition: attachment; filename=\"$asfname\"");
header("Content-Transfer-Encoding: binary");
header("Content-Length: " . $fsize);

// download
// @readfile($file_path);


$file = @fopen($file_path,"rb");
if ($file) {
  while(!feof($file)) {
    print(fread($file, 1024*8));
    flush();
    if (connection_status()!=0) {
      @fclose($file);
      die();
    }
  }
  @fclose($file);
}
 //Count Download

//move connect database up

//mysql_close($connection);    
// log downloads
if (!LOG_DOWNLOADS) die();

$f = @fopen(LOG_FILE, 'a+');
if ($f) {
  @fputs($f, date("m.d.Y g:ia")."  ".$_SERVER['REMOTE_ADDR']."  ".$fname."\n");
  @fclose($f);
}
ob_get_clean();
ob_end_clean();
ob_end_flush();
?>