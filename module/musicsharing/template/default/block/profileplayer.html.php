<?php 
defined('PHPFOX') or exit('NO DICE!'); 

?>


<script type="text/javascript" src="{$core_path}module/musicsharing/static/jscript/mediaelement-and-player.min.js"></script>
<script type="text/javascript" src="{$core_path}module/musicsharing/static/jscript/controller_player.js"></script>
<link rel="stylesheet" type="text/css" href="{$core_path}module/musicsharing/static/css/default/default/mediaelementplayer.min.css" media="screen" />
<link rel="stylesheet" type="text/css" href="{$core_path}module/musicsharing/static/css/default/default/mejs-audio-skins.css" media="screen" />

{if $idplaylist > 0}   
    {if $music_info.song_id > 0} 
    <div align="center">
    {if $bHasSong}
        <div class="younet_html5_player_profile init">
            <audio class="yn-small-audio-skin" id="mejs-small" width="180" src="{$arFirstSong.url}" type="audio/mp3" controls="controls" autoplay="true" preload="none"></audio>

            <ul class="mejs-list scroll-pane small-playlist song-list">
                <!-- Playlist here -->
                {foreach from=$arSongs key=i  item=arSong}
                    <li class="{if ($arSong.ordering == 1)}current{/if}">
                        <span class="song_id" style="display: none;">{$arSong.song_id}</span>
                        <span class="link">{$arSong.url}</span>
                        <span class="song-title">{$arSong.ordering}. {$arSong.title}</span>
                    </li>
                {/foreach}
                <!-- End-->
            </ul>
        </div>
	 {/if}
</div>     
    {else}
    {phrase var='musicsharing.there_are_no'} {phrase var='musicsharing.songs'} {phrase var='musicsharing.added_yet'}.
    {/if}
{else}
	{if $idplaylist != -1}
		{phrase var='musicsharing.there_are_no_playlist_set_default_yet'}.
	{/if}
{/if}
{literal}
<script type="text/javascript">
$Behavior.XPlayer = function(){
	CONTROLLER_PLAYER.initialize();
}


</script>
{/literal} 