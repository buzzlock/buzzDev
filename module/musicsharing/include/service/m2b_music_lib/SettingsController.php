<?php
/**
 * author: Duong Huynh Nghia
 */ 

include_once "SettingsModel.php";
include_once 'YounetDb.php';

class SettingsController extends YounetDb
{
    function delete_settings_user_group($user_group_id)
    {
       $sql = "DELETE FROM ".$this->prefix()."m2bmusic_settings WHERE user_group_id  = ".$user_group_id;
       $results = mysql_query($sql) or die(mysql_error()." <b>SQL was: </b>$sql");           
                     
    }
    function insert_settings_user_group($params = array(),$user_group_id)
    {
        $sql = " INSERT IGNORE INTO  ".$this->prefix()."m2bmusic_settings(user_group_id,module_id,name,default_value)". 
                    " VALUES ";
        foreach($params as $key=>$value)
        {
            if ( $key != 'select_group_member')
            {
                $value = "(". $user_group_id .",'musicsharing','".$key."',".$value.")," ;
                $sql .= $value;   
            }
            
        }
        $sql = substr($sql,0,strlen($sql)-1);
         $results = mysql_query($sql) or die(mysql_error()." <b>SQL was: </b>$sql");       
         
    }
    function set_settings_user_group($params = array())
    {
        $setting = new SettingsModel($params);
        $sql = "";
        $is_insert = false;
        if ( isset($params['setting_id']) && $params['setting_id'] > 0 )//update setting
        {
              $sql = " UPDATE ".$this->prefix()."m2bmusic_settings". 
                     " SET defaul_value = ".$params['default_value'].
                     " WHERE setting_id = ".$params['setting_id']
                     ;
             
              
              
        }
        else//insert new settings
        {
            $is_insert = true;
             $sql = " INSERT INTO  ".$this->prefix()."m2bmusic_settings(user_group_id,module_id,name,default_value)". 
                    " VALUES ( ".
                        $params['user_group_id'].",'".
                        $params['module_id']."','".
                        $params['name']."',".
                        $params['default_value'].",".
                    ")"
                     ;
        }
        $results = mysql_query($sql) or die(mysql_error()." <b>SQL was: </b>$sql");           
        if ( $is_insert)
        {
            $lastID = mysql_insert_id();
            if(!$lastID)
                $lastID = -1;
            //mysql_close($connection);
            return $lastID;
        }
        else
        {
             return -2;
        }
        
    }
  	
    
    
}	

?>
