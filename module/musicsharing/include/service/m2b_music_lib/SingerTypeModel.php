<?php
/**
 * author: Nguyen Cong Minh
 */ 
 class SingerTypeModel
 {
    private $singertype_id;
    private $title;

    public function get_title(){return $this->title;}
    public function set_title($title){$this->title = $title;}
    
    function __construct($singertype_id,$title)
    {
        $this->singertype_id = $singertype_id;
        $this->title = $title;
    }

    function __destruct()
    {
    }
 }

?>