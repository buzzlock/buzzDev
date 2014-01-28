<?php
/**
 * author: Nguyen Cong Minh
 */ 
 class SingerModel
 {
    private $singer_id;
    private $title;
    private $title_url;
    private $singer_type;
    private $singer_image;
    
    public function get_title(){return $this->title;}
    public function set_title($title){$this->title = $title;}
    
    public function get_title_url(){return $this->title_url;}
    public function set_title_url($title_url){$this->title_url = $title_url;}
    
    public function get_singer_id(){return $this->singer_id;}
    public function set_singer_id($singer_id){$this->singer_id = singer_id;}
    
    public function get_singer_type(){return $this->singer_type;}
    public function set_singer_type($singer_type){$this->singer_type = $singer_type;}
    
    public function get_singer_image(){return $this->singer_image;}
    public function set_singer_image($singer_image){$this->singer_image = $singer_image;}
    
    
    function __construct($singer_id,$title,$title_url,$singer_type,$singer_image)
    {
        $this->singer_id = $singer_id;
        $this->title = $title;
        $this->title_url = $title_url;
        $this->singer_type = $singer_type;
        $this->singer_image = $singer_image;
    }

    function __destruct()
    {
    }
 }

?>