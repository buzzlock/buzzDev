<?php
/**
 * author: Nguyen Cong Minh
 */ 
class PlaylistModel
{
    private $playlist_id;
    private $title;
    private $title_url;
    private $user_id;
    private $description;
    private $playlist_image;
    private $search;
    private $is_download;  
    private $profile;
    private $createion_date;
    private $modified_date;
    private $privacy;
    private $privacy_comment;

    
    public function get_title(){return $this->title;}
    public function set_title($title){$this->title = $title;}
    
    public function get_playlist_id(){return $this->playlist_id;}
    public function set_playlist_id($playlist_id){$this->playlist_id = $playlist_id;}
    
    public function get_title_url(){return $this->title_url;}
    public function set_title_url($title_url){$this->title_url = $title_url;}
    
    public function get_userid(){return $this->user_id;}
    public function set_userid($user_id){$this->user_id = $user_id;}
    
    public function get_description(){return $this->description;}
    public function set_description($description){$this->description = $description;}
    
    public function get_photo(){return $this->playlist_image;}
    public function set_photo($playlist_image){$this->playlist_image = $playlist_image;}
    
    public function get_search(){return $this->search;}
    public function set_search($search){$this->search = $search;}
    
    public function get_profile(){return $this->profile;}
    public function set_profile($profile){$this->profileprofile = $profile;}
    
    public function get_is_download(){return $this->is_download;}
    public function set_is_download($is_download){$this->is_download = $is_download;}
    
    public function get_createion_date(){return $this->createion_date;}
    public function set_createion_date($createion_date){$this->createion_date = $createion_date;}
    
    public function get_modified_date(){return $this->modified_date;}
    public function set_modified_date($modified_date){$this->modified_date = $modified_date;}

    public function get_privacy(){return $this->privacy;}
    public function set_privacy($privacy){$this->privacy = $privacy;}

    public function get_privacy_comment(){return $this->privacy_comment;}
    public function set_privacy_comment($privacy_comment){$this->privacy_comment = $privacy_comment;}

  
    function __construct($playlist_id,$title,$title_url,$user_id,$description,$playlist_image,$search,$is_download,$profile,$creation_date,$modified_date,$privacy,$privacy_comment)
    {
        $this->playlist_id = $playlist_id;
        $this->title = $title;
        $this->title_url = $title_url;
        $this->user_id = $user_id;
        $this->description = $description;
        $this->playlist_image = $playlist_image;
        $this->search = $search;
        $this->is_download = $is_download; 
        $this->createion_date = $creation_date;
        $this->modified_date = $modified_date;
        $this->profile = $profile;
        $this->privacy = $privacy;
        $this->privacy_comment = $privacy_comment;
    }

    function __destruct()
    {
    }
} 

?>