<?php
/**
 * author: Nguyen Cong Minh
 */ 
 class CategoryModel
 {
    private $cat_id;
    private $title;
    private $title_url;
    
    public function get_cat_id(){return $this->cat_id;}
    public function set_cat_id($cat_id){$this->cat_id = $cat_id;}
    
    public function get_title(){return $this->title;}
    public function set_title($title){$this->title = $title;}
    
    public function get_title_url(){return $this->title_url;}
    public function set_title_url($title_url){$this->title_url = $title_url;}
    
    
    function __construct($cat_id,$title,$title_url)
    {
        $this->cat_id = $cat_id;
        $this->title = $title;
        $this->title_url = $title_url;
    }

    function __destruct()
    {
    }
 }

?>
