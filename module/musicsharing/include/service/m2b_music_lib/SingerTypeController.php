<?php
/**
 * author: Nguyen Cong Minh
 */ 
 
//  METHODS IN THIS CLASS


//  singertype_list()

//  singertype_delete()

//  singertype_edit()
require_once "SingerTypeModel.php";
include_once 'YounetDb.php';

class SingerTypeController extends YounetDb
{
	 function singertype_create($singer)
    {
        $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_client=utf8", $connection);  
        $title = $singer->get_title();
        $strSQL = "INSERT INTO `".$this->prefix()."m2bmusic_singer_type` (
                `title`
                )
                VALUES (
                 '".htmlspecialchars($title,ENT_QUOTES)."');";
        mysql_query($strSQL);
        $lastID = mysql_insert_id();
        if(!$lastID)
            $lastID = -1;
        //mysql_close($connection);
        return $lastID;
          
    }
  	//Get list singertypes
  	function singertype_list()
  	{
  		 $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_client=utf8", $connection);  
  		// GENERATE QUERY
    	$sql = "SELECT * ";
    	$sql .= " FROM ".$this->prefix()."m2bmusic_singer_type ORDER BY title DESC";
		$singertypelist = mysql_query($sql) or die(mysql_error()." <b>SQL was: </b>$sql");
		$singertypes= null;
        $index = 0;
        $num_track = "";
        while($obj = mysql_fetch_assoc($singertypelist) )
        {
            $index ++;
            if($num_track == "") $num_track = 0;
                $obj['index'] = $index;
                $singertypes[] = $obj;  
        }
         //mysql_close($connection);     
        return $singertypes;
  	}
  	
  	//Edit singertype
  	function singertype_edit($type_id,$title)
  	{
  		$connection = $this->ConnectDatabase();
        mysql_query("SET character_set_client=utf8", $connection);
        $sql =  "SELECT * FROM ".$this->prefix()."m2bmusic_singer_type WHERE singertype_id='{$type_id}'";
          $singertype = mysql_fetch_assoc(mysql_query($sql));
        if( empty($singertype) )
              return FALSE;
        mysql_query("Update ".$this->prefix()."m2bmusic_singer_type set title = '".htmlspecialchars($title,ENT_QUOTES)."' Where singertype_id='{$type_id}'");
        // mysql_close($connection);
         return TRUE;
  	}
  	//Delete singertype
  	function singertype_delete($singertype_id)
  	{
  		 $connection = $this->ConnectDatabase();
        mysql_query("SET character_set_client=utf8", $connection);
        $sql = "SELECT * FROM ".$this->prefix()."m2bmusic_singer_type WHERE singertype_id='{$singertype_id}'" ;
      	$singertype = mysql_fetch_assoc(mysql_query($sql));
    	if( empty($singertype) )
      		return FALSE;
		mysql_query("DELETE FROM ".$this->prefix()."m2bmusic_singer_type WHERE singertype_id='{$singertype_id}' LIMIT 1");
     	return TRUE;
  	}
}	

?>
