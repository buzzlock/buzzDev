<?php
/**
 * author: Duong Huynh Nghia
 */ 
 class SettingsModel
 {
    private $setting_id;
    private $user_group_id;
    private $module_id;
    private $name;
    private $default_value;
    protected $table_name ;
    

   
    
    
    function __construct($params = array())
    {
        $table_name = "mb2music_settings";
        if ( isset($params['setting_id']))
            $this->setting_id = $params['settting_id'];
        if ( isset($params['user_group_id']))
            $this->user_group_id = $params['user_group_id'];
        if ( isset($params['module_id']))
            $this->module_id = $params['module_id'];
        if ( isset($params['name']))
            $this->name = $params['name'];
        if ( isset($params['default_value']))
            $this->default_value = $params['default_value'];
       
       
    }
    public function getTableName()
    {
        return $this->table_name;
    }
    public function getId()
    {
        return $this->setting_id;
    }
    public function setId($id)
    {
        $this->setting_id = $id;
    }
    public function getUser_group_id()
    {
        return $this->user_group_id;
    }
    public function setUser_group_id($id)
    {
        $this->user_group_id = $id;
    }
     public function getModule_id()
    {
        return $this->module_id;
    }
    public function setModule_id($id)
    {
        $this->module_id= $id;
    }
    public function getDefault_value()
    {
        return $this->default_value;
    }
     public function setDefault_value($value)
    {
        $this->default_value=$value;
    }
    function __destruct()
    {
    }
 }

?>