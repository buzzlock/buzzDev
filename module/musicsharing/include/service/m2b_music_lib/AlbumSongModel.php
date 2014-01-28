<?php
/**
 * author: Nguyen Cong Minh
 */ 
 class AlbumSongModel
 {
    private $song_id;
    private $title;
    private $title_url;
    private $album_id;
    private $filesize;
    private $url;
    private $ext;
    private $comment_count;
    private $lyric;
    private $cat_id;
    private $singer_id;
    private $other_singer;
    private $play_count;
    private $download_count;
    private $privacy;
    
    
    public function get_song_id(){return $this->song_id;}
    public function set_song_id($song_id){$this->song_id = $song_id;}
    
    public function get_title(){return $this->title;}
    public function set_title($title){$this->title = $title;}
    
    public function get_title_url(){return $this->title_url;}
    public function set_title_url($title_url){$this->title_url = $title_url;}
    
    public function get_album_id(){return $this->album_id;}
    public function set_album_id($album_id){$this->album_id = $album_id;}
    
    public function get_filesize(){return $this->filesize;}
    public function set_filesize($filesize){$this->filesize = $filesize;}
    
    public function get_url(){return $this->url;}
    public function set_url($url){$this->url = $url;}
    
    public function get_ext(){return $this->ext;}
    public function set_ext($ext){$this->ext = $ext;}
    
    public function get_comment_count(){return $this->comment_count;}
    public function set_comment_count($comment_count){$this->comment_count = $comment_count;}
    
    public function get_lyric(){return $this->lyric;}
    public function set_lyric($lyric){$this->lyric = $lyric;}

    public function get_privacy(){return $this->privacy;}
    public function set_privacy($privacy){$this->privacy = $privacy;}
    
    public function get_cat_id(){return $this->cat_id;}
    public function set_cat_id($cat_id){$this->cat_id = $cat_id;}
    
    public function get_singer_id(){return $this->singer_id;}
    public function set_singer_id($singer_id){$this->singer_id = $singer_id;}
    
    public function get_other_singer(){return $this->other_singer;}
    public function set_other_singer($other_singer){$this->other_singer = $other_singer;}
    
    public function get_play_count(){return $this->play_count;}
    public function set_play_count($play_count){$this->play_count = $play_count;}
    
    public function get_download_count(){return $this->download_count;}
    public function set_download_count($download_count){$this->download_count = $download_count;}
    function __construct($song_id = 0,$title="",$title_url="",$album_id=0,$filesize=0,$url="",$ext=".mp3",$comment_count=0,$lyric="",$cat_id=0,$singer_id=0,$other_singer=0,$play_count=0,$download_count=0,$privacy=0)
    {
        $this->song_id = $song_id;
        $this->title = $title;
        $this->title_url = $title_url;
        $this->album_id = $album_id;
        $this->filesize = $filesize;
        $this->url = $url;
        $this->ext = $ext;
        $this->comment_count = $comment_count;
        $this->lyric = $lyric;
        $this->cat_id = $cat_id;
        $this->singer_id = $singer_id;
        $this->other_singer = $other_singer;
        $this->play_count = $play_count;
        $this->download_count = $download_count;
        $this->privacy = $privacy;
    }
    
    function __destruct()
    {
    }
 }

?>