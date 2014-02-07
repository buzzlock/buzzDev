<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<?php /* Cached: February 7, 2014, 12:01 am */ ?>
<?php 
 

?>


<script type="text/javascript" src="<?php echo $this->_aVars['core_path']; ?>module/musicsharing/static/jscript/mediaelement-and-player.min.js"></script>
<script type="text/javascript" src="<?php echo $this->_aVars['core_path']; ?>module/musicsharing/static/jscript/controller_player.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $this->_aVars['core_path']; ?>module/musicsharing/static/css/default/default/mediaelementplayer.min.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->_aVars['core_path']; ?>module/musicsharing/static/css/default/default/mejs-audio-skins.css" media="screen" />

<?php if ($this->_aVars['idplaylist'] > 0): ?>
<?php if ($this->_aVars['music_info']['song_id'] > 0): ?>
    <div align="center">
<?php if ($this->_aVars['bHasSong']): ?>
        <div class="younet_html5_player_profile init">
            <audio class="yn-small-audio-skin" id="mejs-small" width="180" src="<?php echo $this->_aVars['arFirstSong']['url']; ?>" type="audio/mp3" controls="controls" autoplay="true" preload="none"></audio>

            <ul class="mejs-list scroll-pane small-playlist song-list">
                <!-- Playlist here -->
<?php if (count((array)$this->_aVars['arSongs'])):  foreach ((array) $this->_aVars['arSongs'] as $this->_aVars['i'] => $this->_aVars['arSong']): ?>
                    <li class="<?php if (( $this->_aVars['arSong']['ordering'] == 1 )): ?>current<?php endif; ?>">
                        <span class="song_id" style="display: none;"><?php echo $this->_aVars['arSong']['song_id']; ?></span>
                        <span class="link"><?php echo $this->_aVars['arSong']['url']; ?></span>
                        <span class="song-title"><?php echo $this->_aVars['arSong']['ordering']; ?>. <?php echo $this->_aVars['arSong']['title']; ?></span>
                    </li>
<?php endforeach; endif; ?>
                <!-- End-->
            </ul>
        </div>
<?php endif; ?>
</div>     
<?php else: ?>
<?php echo Phpfox::getPhrase('musicsharing.there_are_no'); ?> <?php echo Phpfox::getPhrase('musicsharing.songs'); ?> <?php echo Phpfox::getPhrase('musicsharing.added_yet'); ?>.
<?php endif;  else: ?>
<?php if ($this->_aVars['idplaylist'] != -1): ?>
<?php echo Phpfox::getPhrase('musicsharing.there_are_no_playlist_set_default_yet'); ?>.
	<?php endif;  endif;  echo '
<script type="text/javascript">
$Behavior.XPlayer = function(){
	CONTROLLER_PLAYER.initialize();
}


</script>
'; ?>
 
