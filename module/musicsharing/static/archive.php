<?php 

define('PHPFOX',1);

 
        function remove_sign_string($str) 
        {
          $sign = array("à", "á", "ạ", "ả", "ã", "â", "ầ", "ấ", "ậ", "ẩ", "ẫ", "ă", "ằ", "ắ", "ặ", "ẳ", "ẵ", "è", "é", "ẹ", "ẻ", "ẽ", "ê", "ề", "ế", "ệ", "ể", "ễ", "ì", "í", "ị", "ỉ", "ĩ", "ò", "ó", "ọ", "ỏ",
              "õ", "ô", "ồ", "ố", "ộ", "ổ", "ỗ", "ơ", "ờ", "ớ", "ợ", "ở", "ỡ", "ù", "ú", "ụ", "ủ", "ũ", "ư", "ừ", "ứ", "ự", "ử", "ữ", "ỳ", "ý", "ỵ", "ỷ", "ỹ", "đ", "À", "Á", "Ạ", "Ả", "Ã", "Â", "Ầ", "Ấ", "Ậ", "Ẩ",
              "Ẫ", "Ă", "Ằ", "Ắ", "Ặ", "Ẳ", "Ẵ", "È", "É", "Ẹ", "Ẻ", "Ẽ", "Ê", "Ề", "Ế", "Ệ", "Ể", "Ễ", "Ì", "Í", "Ị", "Ỉ", "Ĩ", "Ò", "Ó", "Ọ", "Ỏ", "Õ", "Ô", "Ồ", "Ố", "Ộ", "Ổ", "Ỗ", "Ơ", "Ờ", "Ớ", "Ợ", "Ở", "Ỡ",
              "Ù", "Ú", "Ụ", "Ủ", "Ũ", "Ư", "Ừ", "Ứ", "Ự", "Ử", "Ữ", "Ỳ", "Ý", "Ỵ", "Ỷ", "Ỹ", "Đ", "ê", "ù", "à");

          $unsign = array("a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "i", "i", "i", "i", "i", "o", "o", "o", "o",
              "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "y", "y", "y", "y", "y", "d", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A",
              "A", "A", "A", "A", "A", "A", "A", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "I", "I", "I", "I", "I", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O",
              "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "Y", "Y", "Y", "Y", "Y", "D", "e", "u", "a");
          return str_replace($sign, $unsign, $str);
        }

//        $zip = new ZipArchive();
//              $file_path='../../../file/cache/';
//
//$res=$zip->open($file_path.'text.zip',ZIPARCHIVE::CREATE);
//if ($res === TRUE) {
//    $zip->addEmptyDir("abc");
//    echo "true";
//}
//else
//  echo "false";
//$zip->close();
//die();
//unlink("../../../file/cache/YKNMYU7fESvcnXXp.zip");
//die();
  if(!session_id())
    session_start();
  if(isset($_SESSION['downloadlist_downloadlist'])!=null)
    $iUserId=$_SESSION['downloadlist_downloadlist'];
  else
    $iUserId=0;

	if($iUserId==0)
		die("You must login to download this song");

function zipFiles($file_names,$archive_file_name,$file_path)
{
      
      $zip = new ZipArchive();

      if ($zip->open($file_path.$archive_file_name, ZIPARCHIVE::CREATE )!==TRUE) {
        exit("cannot open <$archive_file_name>\n");
      }

      if(is_array($file_names))
      foreach($file_names as $files)
      {
   
        $zip->addFile($files['url'],$files['title'].'.mp3');
      }
      $zip->close();
    
      return $archive_file_name;
}
function createFileName()
{
      $sid = 'abcdefghiklmnopqstvxuyz0123456789ABCDEFGHIKLMNOPQSTVXUYZ';
      $max =  strlen($sid) - 1;
      $res = "";
      for($i = 0; $i<16; ++$i)
      {
          $res .=  $sid[mt_rand(0, $max)];
      }  
      return $res;
}
/**********************************************/
require_once "../../../include/setting/server.sett.php";

$connection = mysql_connect($_CONF['db']['host'], $_CONF['db']['user'], $_CONF['db']['pass']);
$prefix = $_CONF['db']['prefix'];
if (!$connection)
    die("can't connect server");
$db_selected = mysql_select_db($_CONF['db']['name']);
if (!$db_selected)
    die ("have not database");
    
/************************************************/
if(isset($_GET['al']))
  $al = $_GET['al'];
else
  die("No songs or albums are found!");
if(!is_numeric($al) && $al!='all')
   die("No songs or albums are found!");
if(isset($_GET['dl']))
  $dl = $_GET['dl'];
if(isset($_GET['f']))
  $f=$_GET['f'];
/***********************************************/
$namedownload = createFileName().'.zip';

/*
 * get songs list from album_id
 */

$prefix = $_CONF['db']['prefix'];
$file_path='../../../file/cache/';
mysql_query("SET character_set_results=utf8", $connection);
if($al!='all')
{
  $sql="select * from ".$prefix."m2bmusic_album_song where album_id=".$al;
  $result=mysql_query($sql);
  if(mysql_num_rows($result)==0)
    die("No songs or albums are found!");
  $file_names=array();
  $iKey=0;
  while($row=mysql_fetch_array($result))
  {
    $file_names[$iKey]['url']="../../../file/musicsharing/".$row['url'];
    $file_names[$iKey]['title']=remove_sign_string($row['title']);
    $iKey++;
  }
  $linkdownload = zipFiles($file_names,$namedownload,$file_path);
  $linkdownload = $file_path.$linkdownload;
  $fsize = @filesize($linkdownload);
}
else{
   
  $sql="select *,song.title as song_title,album.title as album_title from ".$prefix."m2bmusic_downloadlist dll left join ".$prefix."m2bmusic_album_song song on song.song_id=dll.dl_song_id left join ".$prefix."m2bmusic_album album on album.album_id=dll.dl_album_id where dll.user_id=".$iUserId;
  $result=mysql_query($sql);
  if(mysql_num_rows($result)==0)
    die("No songs or albums are found!");
  $zip = new ZipArchive();
  if ($zip->open($file_path.$namedownload, ZIPARCHIVE::CREATE )!==TRUE) {
      exit("cannot open <$archive_file_name>\n");
   }
  while($row=mysql_fetch_array($result))
  {
       if($row['dl_song_id']!=0)
       {
          $file_names="../../../file/musicsharing/".$row['url'];
          $zip->addFile($file_names,$row['song_title'].'.mp3');
       }
       else
       {
         $zip->addEmptyDir(remove_sign_string($row['album_title']));
          //echo $row['album_title'];
          $row['album_title']=remove_sign_string($row['album_title']);
          $sql_album="select * from ".$prefix."m2bmusic_album_song where album_id=".$row['dl_album_id'];
          $result_album=mysql_query($sql_album);
          while($row_album=mysql_fetch_array($result_album))
          {
              $file_names="../../../file/musicsharing/".$row_album['url'];             
              $zip->addFile($file_names,remove_sign_string($row['album_title']."/".$row_album['title'].'.mp3'));
          }         
       }     
  }
  $zip->close();
  $linkdownload = $file_path.$namedownload;
  $fsize = @filesize($linkdownload);
  
}
 
 $fls= substr($namedownload, 0 , -4);
/*if($fls !=".mp3" && $fls !=".zip")
{
    $namedownload.=".zip";
}*/
// set headers
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: public");
header("Content-Description: File Transfer");
header("Content-Type: application/zip");
header("Content-Disposition: attachment; filename=\"$namedownload\"");
header("Content-Transfer-Encoding: binary");
header("Content-Length: " . $fsize);

// download
// @readfile($file_path);

$file = @fopen($linkdownload,"rb");
if ($file) {
  while(!feof($file)) {
    print(fread($file, 1024*8));
    flush();
    if (connection_status()!=0) {
      @fclose($file);
      @unlink($linkdownload);
      die();
    }
  }
  @fclose($file);
  @unlink($linkdownload);
}

?>