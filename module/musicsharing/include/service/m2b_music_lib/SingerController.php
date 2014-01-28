<?php
/**
 * author: Nguyen Cong Minh
 */ 

//  METHODS IN THIS CLASS

//  singer()

//  singer_list()

//  singer_delete()

//  singer_edit()
require_once "SingerModel.php";
include_once 'YounetDb.php';

class SingerController extends YounetDb
{
    function singer_create($singer)
    {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_client=utf8", $connection);  
        $title = $singer->get_title();
        $title_url = $this->convertURL($singer->get_title_url()); 
        $type =  $singer->get_singer_type();
        $singer_image = $singer->get_singer_image();
        $strSQL = "INSERT INTO `".$this->prefix()."m2bmusic_singer` (
                `title`,
                `title_url`,
                `singer_type`,
                `singer_image`
                )
                VALUES (
                 '".htmlspecialchars($title,ENT_QUOTES)."','$title_url','$type','$singer_image');";
        
        mysql_query($strSQL) or die(mysql_error()." <b>SQL was: </b>$strSQL"); ;
        $lastID = mysql_insert_id();
        if(!$lastID)
            $lastID = -1;
        //mysql_close($connection);
        return $lastID;
          
    }
  	//Get list singers
  	function singer_list()
  	{
  		$connection = $this->ConnectDatabase();
        
        mysql_query("SET character_set_client=utf8", $connection);
        
        $type_singer_list = array();
        $sql_get_types = "SELECT * FROM ".$this->prefix()."m2bmusic_singer_type ORDER BY `title` ASC";
        $get_type_rs = mysql_query($sql_get_types) or die(mysql_error()." <b>SQL was: </b>$sql");
        $index = 0;
        while($type_info = mysql_fetch_assoc($get_type_rs))
        {
            $type_singer_list[$index]['info'] = $type_info;
            
            $sql_get_singer = "SELECT * FROM ".$this->prefix()."m2bmusic_singer WHERE `singer_type` = ".$type_info['singertype_id']." ORDER BY `title` ASC";
            
            $get_singer_rs = mysql_query($sql_get_singer)or die(mysql_error()." <b>SQL was: </b>$sql");
            
            while($singer_info = mysql_fetch_assoc($get_singer_rs)){
                $type_singer_list[$index]['singer'][] = $singer_info;
            }
            $index ++;
        }
        
        return $type_singer_list;
  	}
  	//Get list singers type
      function singer_list_type($type_id)
      {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_client=utf8", $connection);
        $index = 0;
        $type_singer_list = array();
        $sql_get_singer = "SELECT * FROM ".$this->prefix()."m2bmusic_singer WHERE `singer_type` = ".$type_id." ORDER BY `title` ASC";
        $get_singer_rs = mysql_query($sql_get_singer)or die(mysql_error()." <b>SQL was: </b>$sql");
        while($singer_info = mysql_fetch_assoc($get_singer_rs))
        {
            $index ++; 
            $singer_info['index'] =  $index;
            $type_singer_list[] = $singer_info;
        }
        //  mysql_close($connection);    
        return $type_singer_list;
      }
  	//Edit singer
  	function singer_edit($singer)
  	{
  	    $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_client=utf8", $connection);  
        $title = $singer->get_title();
        $title_url = $this->convertURL($singer->get_title_url()); 
        $type =  $singer->get_singer_type();
        $singer_image = $singer->get_singer_image();
        $singer_id = $singer->get_singer_id();
        $strSQL = "Update `".$this->prefix()."m2bmusic_singer` Set
                `title` = '".htmlspecialchars($title,ENT_QUOTES)."',
                `title_url` = '$title_url',
                `singer_type` = '$type',
                `singer_image` = '$singer_image' 
                Where 
                 singer_id = '$singer_id'";
        mysql_query($strSQL) or die(mysql_error()." <b>SQL was: </b>$strSQL"); ;
        //mysql_close($connection);
        return $singer_id;
  	}
  	//Delete singer
  	function singer_delete($singer_id)
  	{
  	    $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_client=utf8", $connection);
        $sql =  "SELECT * FROM ".$this->prefix()."m2bmusic_singer WHERE singer_id='{$singer_id}'" ;
      	$singer = mysql_fetch_assoc(mysql_query($sql));
    	if( empty($singer) )
      		return FALSE;
		mysql_query("DELETE FROM ".$this->prefix()."m2bmusic_singer WHERE singer_id='{$singer_id}' LIMIT 1");
     	//mysql_close($connection);    
        return TRUE;
  	}
    //Edit singer title
      function singer_edit_title($singer_id,$title)
      {
          $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_client=utf8", $connection);
        $sql =  "SELECT * FROM ".$this->prefix()."m2bmusic_singer WHERE singer_id='{$singer_id}'";
          $singertype = mysql_fetch_assoc(mysql_query($sql));
        if( empty($singertype) )
              return FALSE;
        mysql_query("Update ".$this->prefix()."m2bmusic_singer set title = '".$title."' Where singer_id='{$singer_id}'");
        // mysql_close($connection);
         return TRUE;
      }
      function singer_info($idsinger = null)
    {
        $connection = $this->ConnectDatabase(); 
        mysql_query("SET character_set_results=utf8", $connection);    
        $singer = mysql_query("SELECT ".$this->prefix()."m2bmusic_singer.* from ".$this->prefix()."m2bmusic_singer WHERE singer_id = '$idsinger' LIMIT 1");
        $singer_info = mysql_fetch_assoc($singer);
        //mysql_close($connection);     
        return $singer_info;
    }
}	

?>