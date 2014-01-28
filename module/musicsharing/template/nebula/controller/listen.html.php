{if Phpfox::isMobile()}
    {literal}
        <style type="text/css">
            .mobile_section_menu{display: none !important;}
            #section_menu{display:none !important;}
            
        </style>  
    {/literal}
{/if}
{literal}
 <style type="text/css">
#left{
	display:none;
}
#main_content{
	margin-left: 0;
	border-left: none;
}
#content.content3{
	width: 780px;
}
.listen-page {
	width: 100%;
	}
</style>  
{/literal}

{template file='musicsharing.block.mexpect'}

{if isset($album_info)}
<div {if !phpfox::isMobile()}class="listen-page"{/if}>
{if !phpfox::isMobile()}
        <div id="currentAlbum">
            <div id="currentAlbumHeader">
                <span id="dynamicalbumtitle" class="title">
                    {$album_info.title}
                </span>&nbsp;<span class="by">{if isset($album_info.playlist_id)}{phrase var="musicsharing.created_by"}{else}{phrase var="musicsharing.uploaded_by"} {/if}</span>&nbsp;<a target="_blank" href="{url link=$album_info.user_name}" class="artist" id="dynamicartistname">{$album_info.full_name}</a>
            </div>
            <div id="currentAlbumImg" style="text-align: center; min-width: 204px;">
                <img style="max-height: 204px; max-width: 204px;" max-height="204" max-width="204" src="{if isset($album_info.album_image)&& $album_info.album_image !=""}{img server_id=$album_info.server_id path='musicsharing.url_image' suffix='' file=$album_info.album_image return_url=true}{else}{$core_path}module/musicsharing/static/image/music.png{/if}"  id="currentalbum"/>
                <div id="share" class="p_4">
                    {template file='musicsharing.block.sharethis'}
                </div>
            </div>
                <?php
                        //@jh: hot fix
                        if(strpos($this->_aVars['album_info']['description'], "<br />") === false){ldelim}
                                $this->_aVars['album_info']['description'] = str_replace("\n", "<br />", $this->_aVars['album_info']['description']);
                        {rdelim}
                ?>
            <input type="hidden" id="core_path" name="core_path" value="{$core_path}" />

        <div id="songContainer">
                
            <!-- HTML5 -->
            <div class="younet_html5_player init not-unbind">
                <input type="hidden" name="html5_player_type" value="{if isset($pid)}playlist{else}album{/if}" class="html5_player_type" />

                <div class="yn-music">
                    <div class="song-info">
                        <MARQUEE WIDTH="70%" SCROLLDELAY="300">
                            <span class="song-title-head" style="color:#fff">{$music_info.ordering}. {$music_info.title}</span>
                        </MARQUEE>
                        <div class="change_song_rating">
                            {template file="musicsharing.block.rating}
                        </div>
                    </div>

                    <audio class="yn-audio-skin" class="mejs" width="493" src="{$music_info.url}" type="audio/mp3" controls="controls" autoplay="true" preload="none"></audio>
                </div>

                <ul class="mejs-list scroll-pane song-list">
                    <!-- Playlist here -->
                    {foreach from=$arSongs key=i  item=arSong}
                        <li class="{if ($arSong.ordering == 1)}current{/if}">
                            <span class="song_id" style="display: none;">{$arSong.song_id}</span>
                            <span class="link">{$arSong.url}</span>
                            <span class="song-title">{$arSong.ordering}. {$arSong.title}</span>
                            <span class="yn-play">{phrase var='musicsharing.plays'}: {$arSong.play_count}</span>
                            <a href="javascript:void(0)" class="younet-add-to-playlist-html5"><span class="yn-add-playlist">{phrase var='musicsharing.add_to_playlist'}</span></a>
                            <a href="javascript:void(0)" class="younet-download-music-html5"><span class="yn-download">{phrase var='musicsharing.download'}</span></a>
                        </li>
                    {/foreach}
                    <!-- End-->
                </ul>
            </div>
            
            <!-- END -->
            <div class="iframe-download-html5-player" style="display: none;"></div>
        </div>
        <div class="clear"></div>
    </div>
		
    {template file="musicsharing.block.album_info_des}
		
<!-- Mobile Player -->	
{else}
    {template file='musicsharing.block.mobile.miniplayer'}
{/if}
<!-- End -->
<div class="clear"></div>

<div id="Comment" {if phpfox::isMobile()} class="comment-on-mobile" {/if}>
        {if $settings.can_post_comment_on_song eq 1}

                {module name='feed.comment'}
        {else}
                {phrase var='musicsharing.you_do_not_have_permission_to_comment_on_songs'}.
        {/if}
</div>
</div>
    {if isset($pid)}
            <input type="hidden" value="1" id="_idplaylist" />
    {else}
            <input type="hidden" value="1" id="_idalbum" />
    {/if}
    
    {literal}
        <script type="text/javascript">
            $Behavior.VideoChannelDDTabContent = function(){
                $(document).ready(function(){
                    var mypets = new ddtabcontent("pettabs");
                    mypets.setpersist(false);
                    mypets.setselectedClassTarget("link");
                    mypets.init(20000000);
                });
            }
        </script>
    {/literal}
{else}
        <div class="listen-page">
            <div id="currentAlbum">
                <div id="currentAlbumHeader">
                        <span id="dynamicalbumtitle" class="title">
                                {$album_info2.title}
                        </span>
                        <span class="by">
                                {if isset($album_info2.playlist_id)}{phrase var="musicsharing.created_by"}{else}{phrase var="musicsharing.uploaded_by"} {/if}
                        </span>
                        <a target="_blank" href="{url link=$album_info2.user_name}" class="artist" id="dynamicartistname">{$album_info2.full_name}</a>
                </div>
                <div align="left" class="red margin-right-10 margin-bottom-10 margin-top-10" style="">{phrase var='musicsharing.there_are_no_songs_added_yet'}</div>
            </div>
        </div>
{/if}
<div class="clear"></div>

