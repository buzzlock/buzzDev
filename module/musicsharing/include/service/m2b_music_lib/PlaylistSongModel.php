<?php
/**
 * author: Nguyen Cong Minh
 */ 
 class PlaylistSongModel
 {
    private $song_id;
    private $playlist_id;
    private $album_song_id;
   
    
    
    public function get_song_id(){return $this->song_id;}
    public function set_song_id($song_id){$this->song_id = $song_id;}
    
    public function get_playlist_id(){return $this->playlist_id;}
    public function set_playlist_id($playlist_id){$this->playlist_id = $playlist_id;}
    
    public function get_album_song_id(){return $this->album_song_id;}
    public function set_album_song_id($album_song_id){$this->album_song_id = $album_song_id;}
    
   
    
    
    function __construct($song_id,$playlist_id,$album_song_id)
    {
        $this->song_id = $song_id;
        $this->playlist_id = $playlist_id;
        $this->album_song_id = $album_song_id;
       
    }
    function __destruct()
    {
    }
 }

?>