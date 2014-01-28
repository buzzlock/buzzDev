{literal}
<script type="text/javascript">
    $Behavior.MusicSharingMiniPlayer = function(){
        $(document).ready(function(){
            $('.m-viewmore').click(function(){
                if($('.showLess').size() > 0){
                    $('.mobile-lyrics').removeClass('showLess').addClass('showAll');
                }
                else{
                    $('.mobile-lyrics').removeClass('showAll').addClass('showLess');
                }
            });
        });        
    }
</script>
<style type="text/css">
	.js_rating_value{
		display:none!important;
	}
</style>
{/literal}
<?php
    if(strpos($this->_aVars['album_info']['description'], "<br />") === false){ldelim}
            $this->_aVars['album_info']['description'] = str_replace("\n", "<br />", $this->_aVars['album_info']['description']);
    {rdelim}
?>
<input type="hidden" id="core_path" name="core_path" value="{$core_path}" />
<div class="miniplayer-content">
	<div class="younet_html5_player mobile not-unbind">
		<input type="hidden" name="html5_player_type" value="{if isset($pid)}playlist{else}album{/if}" class="html5_player_type" />
		<div class="player-left">
			<div class="player-photo" style="background-image:url('{if isset($album_info.album_image)&& $album_info.album_image !=""}{img server_id=$album_info.server_id path='musicsharing.url_image' file=$album_info.album_image suffix='_255' max_width=204 max_height=204 return_url=true}{else}{$core_path}module/musicsharing/static/image/music.png{/if}')"></div>
			<div class="change_song_rating">
				{template file="musicsharing.block.rating}
			</div>
		</div>
		<div class="player-right">
			<div class="title-info">
				<MARQUEE WIDTH="70%" SCROLLDELAY="300"><span class="song-title-head">{$music_info.ordering}. {$music_info.title}</span> </MARQUEE>
				<p id="dynamicalbumtitle">{$album_info.title} </p>
			</div>
			<audio class="yn-audio-skin" style="width:100%" src="{$music_info.url}" type="audio/mp3" controls="controls" autoplay="false" preload="none"></audio>
		</div>
		<div class="clear"></div>
		<!-- menu tab -->
		<div id="pettabs" class="indentmenu">
			<ul>
				<li><a href="" rel="listsong" class="selected">{phrase var='musicsharing.list_songs'}</a></li>
				<li><a href="" rel="Lyric"  class="">{phrase var="musicsharing.lyric"}</a></li>
			</ul>
			<div class="space-line"></div>
		</div>
		<!-- End -->
		<div id="listsong">
			<ul class="mejs-list scroll-pane song-list">
				<!-- Playlist here -->
				{foreach from=$arSongs key=i  item=arSong}
					<li class="{if ($arSong.ordering == 1)}current{/if}">
						<p class="song-order">{$arSong.ordering}</p>
						<p class="play-icon"></p>
						<span class="song_id" style="display: none;">{$arSong.song_id}</span>
						<span class="link">{$arSong.url}</span>
						<span class="song-title" style="display:block">{$arSong.title}</span><br/>
						<span class="singer">
                            {if $arSong.singer_title != ""}{$arSong.singer_title}{else} {if $arSong.other_singer != ""}{$arSong.other_singer}{else} {phrase var='musicsharing.not_updated'}{/if}{/if}
                        </span>
					</li>
				{/foreach}
				<!-- End-->
			</ul>
		</div>
		<div id="Lyric" class="tabcontent">
			<div class="mobile-lyrics showLess" id="lyric_music_song">
				{if $music_info.lyric != ""}
				<div class="lyric-height-default">
				{$music_info.lyric}					
				</div>
				<a href="javascript:void(0)" class="m-viewmore video_info_toggle">
						<span class="m-more">{phrase var='musicsharing.view_more'}</span>
						<span class="m-less">{phrase var='musicsharing.view_less'}</span>
				</a>
				{else}
				
					{phrase var='musicsharing.no_lyric'}
				{/if}
			</div>
		</div>
	</div>
	
</div>
<div class="clear"></div>
	



	


	
