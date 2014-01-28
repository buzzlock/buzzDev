<?php
/**
 * author: Nguyen Cong Minh
 */ 
class AlbumModel
{
    private $album_id;
    private $title;
    private $title_url;
    private $user_id;
    private $description;
    private $album_image;
    private $search;
    private $createion_date;
    private $modified_date;
    private $play_count;
    private $download_count;
    private $is_featured;
    private $is_download;
    private $privacy;
    private $privacy_comment;
    
    public function get_title(){return $this->title;}
    public function set_title($title){$this->title = $title;}
    
    public function get_title_url(){return $this->title_url;}
    public function set_title_url($title_url){$this->title_url = $title_url;}
    
    public function get_userid(){return $this->user_id;}
    public function set_userid($user_id){$this->user_id = $user_id;}
    
    public function get_description(){return $this->description;}
    public function set_description($description){$this->description = $description;}
    
    public function get_photo(){return $this->album_image;}
    public function set_photo($album_image){$this->album_image = $album_image;}
    
    public function get_search(){return $this->search;}
    public function set_search($search){$this->search = $search;}
    
    public function get_createion_date(){return $this->createion_date;}
    public function set_createion_date($createion_date){$this->createion_date = $createion_date;}
    
    public function get_modified_date(){return $this->modified_date;}
    public function set_modified_date($modified_date){$this->modified_date = $modified_date;}
    
    public function get_play_count(){return $this->play_count;}
    public function set_play_count($play_count){$this->play_count = $play_count;}
    
    public function get_download_count(){return $this->download_count;}
    public function set_download_count($download_count){$this->download_count = $download_count;} 
    
    public function get_is_featured(){return $this->is_featured;}
    public function set_is_featured($is_featured){$this->is_featured = $is_featured;} 
    
    public function get_is_download(){return $this->is_download;}
    public function set_is_download($is_download){$this->is_download = $is_download;}
    
    public function get_album_id(){return $this->album_id;}
    public function set_album_id($album_id){$this->album_id = $album_id;}

    public function get_privacy(){return $this->privacy;}
    public function set_privacy($privacy){$this->privacy = $privacy;}

    public function get_privacy_comment(){return $this->privacy_comment;}
    public function set_privacy_comment($privacy_comment){$this->privacy_comment = $privacy_comment;}

    function __construct($album_id,$title,$title_url,$user_id,$description,$album_image,$search,$creation_date,$modified_date,$play_count,$download_count,$is_featured,$is_download,$privacy,$privacy_comment)
    {
        $this->album_id = $album_id;
        $this->title = $title;
        $this->title_url = $title_url;
        $this->user_id = $user_id;
        $this->description = $description;
        $this->album_image = $album_image;
        $this->search = $search;
        $this->createion_date = $creation_date;
        $this->modified_date = $modified_date;
        $this->play_count = $play_count;
        $this->download_count = $download_count;
        $this->is_download = $is_download;
        $this->is_featured = $is_featured;
        $this->privacy = $privacy;
        $this->privacy_comment = $privacy_comment;
    }

    function __destruct()
    {
    }
} 

?>