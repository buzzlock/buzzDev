<?php
/**
 * [PHPFOX_HEADER]
 *
 * @copyright        YouNet
 * @author          Minh Nguyen
 * @package         Phpfox
 * @version         $Id: song.html.php 1318 2010-12-10  $
 */
defined('PHPFOX') or exit('NO DICE!');
?>
{if Phpfox::isMobile()}
    {literal}
        <style type="text/css">
            .mobile_section_menu{display: none !important;}
            #section_menu{display:none !important;}
        </style>
    {/literal}
{/if}
{template file='musicsharing.block.mexpect'}

<div class="list">
    <!-- fullsite -->
    {if !phpfox::isMobile()}
        {foreach from=$list_info key=index item=playlist_info}
            <div class="border_top"></div>
            <div class="line_album">
                <div class="table_left left_line_album pic_album">
                    <span class=""></span>
                    <a href="{*
                        *}{if !isset($aParentModule)}{*
                            *}{url link='musicsharing.listen.playlist_'.$playlist_info.playlist_id}{*
                        *}{else}{*
                            *}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing.listen.playlist_'.$playlist_info.playlist_id}{*
                        *}{/if}{*
                    *}" title="{$playlist_info.title}">
                        <span class="img">
                            {if $playlist_info.playlist_image != ""}
                                <img width="115" border="0" height="108" src="{$core_path}module/musicsharing/static/image/space.gif" style="background: url('{img server_id=$playlist_info.server_id path='musicsharing.url_image' file=$playlist_info.playlist_image suffix='_115' max_width=115 max_height=115 return_url=true}') no-repeat scroll center center transparent;" alt="{$playlist_info.title}">
                            {else}
                                <img width="115" border="0" height="108" src="{$core_path}module/musicsharing/static/image/space.gif" style="background: url('{$core_path}module/musicsharing/static/image/music/nophoto_album_thumb.png') no-repeat scroll center center transparent;" alt="{$playlist_info.title}">
                            {/if}

                        </span>
                        <span class="overlay"></span>
                    </a>
                </div>
                <div class="table_right left_line_album" >
                    <div>
                        <h2><a class="gray12" href="{*
                            *}{if !isset($aParentModule)}{*
                                *}{url link='musicsharing.listen.playlist_'.$playlist_info.playlist_id}{*
                            *}{else}{*
                                *}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing.listen.playlist_'.$playlist_info.playlist_id}{*
                            *}{/if}{*
                        *}" title="{$playlist_info.title}">{$playlist_info.title|clean|shorten:100:"...":false}</a></h2>
                        <div class="author_play_track">
                            <div class="top-descript">
                                <?php
                                    $iDate = $this->_aVars['playlist_info']['_creation_date'];//mktime($date['hour'], $date['minute'], $date['second'], $date['month'], $date['day'], $date['year']);
                                    //
                                ?>
                                <span class="grey">{phrase var='musicsharing.play_s'}: {$playlist_info.play_count}</span><br/>
                                <span class="grey">{phrase var="musicsharing.created"}: {*$playlist_info.year*}<?php echo(phpFox::getLib('date')->convertTime($iDate)); ?> {phrase var="musicsharing.by"} <a href="{url link=$playlist_info.user_name}">{$playlist_info.full_name}</a></span><br/>
                                <span class="grey">{phrase var='musicsharing.songs'}: {$playlist_info.num_track}</span><br/>
                                {if $playlist_info.description|clean}
                                    <?php
                                        //@jh: hot fix
                                        if(strpos($this->_aVars['playlist_info']['description'], "<br />") === false){ldelim}
                                            $this->_aVars['playlist_info']['description'] = str_replace("\n", "<br />", $this->_aVars['playlist_info']['description']);
                                        {rdelim}
                                    ?>
                                    <span class="grey">{$playlist_info.description|shorten:220:"...":false}</span>
                                {else}
                                {/if}
                            </div>
                            <a class="play-playlist" title="{phrase var='musicsharing.listen_playlist'} : {$playlist_info.title}" href="{*
                                *}{if !isset($aParentModule)}{*
                                    *}{url link='musicsharing.listen.playlist_'.$playlist_info.playlist_id}{*
                                *}{else}{*
                                    *}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing.listen.playlist_'.$playlist_info.playlist_id}{*
                                *}{/if}{*
                            *}">
                                <span class="listen_playlist">{phrase var='musicsharing.listen_playlist'}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        {foreachelse}
            <div align="left" class="red margin-right-10 margin-bottom-10 margin-top-10"> {phrase var='musicsharing.there_is_no_any_playlists_yet'}. <!-- <a href="{url link='musicsharing.createplaylist'}"> Please create a playlist !</a> --></div>
        {/foreach}
        <div style="div-clear" ></div>
    {else}
        <div class="m_album_playlist"> 
            {if count($list_info)>0}
                {foreach from=$list_info key=index item=aAlbum}
                    {template file='musicsharing.block.mobile.playlist-info'}
                {/foreach}
            {else}
                <div align="left" class="red margin-right-10 margin-bottom-10 margin-top-10"> 
                    {phrase var='musicsharing.there_is_no_any_playlists_yet'}.
                </div>
            {/if}
        </div>
    {/if}
    
    {pager}
</div>


{if isset($sTextSearch) && $sTextSearch !== NULL}
<script language="javascript" type="text/javascript">
	function unbix(){ldelim}
		$(".header_bar_search_holder").find("input[type=text]").css({ldelim}
			"color": "#000000"
		{rdelim}).unbind("focus");
		$(".header_bar_search_holder").find("input[type=text]").focus(function(){ldelim}
			$(this).parent().find(".header_bar_search_input").addClass("focus");
		{rdelim}).blur(function(){ldelim}
			$(this).parent().find(".header_bar_search_input").removeClass("focus");
		{rdelim});
	{rdelim}
$Behavior.MusicSharingPlaylist = function() {ldelim}
	$(document).ready(function(){ldelim}
		$(".header_bar_search_holder").find("input[type=text]").css({ldelim}
			"color": "#000000"
		{rdelim}).unbind("focus");
		setTimeout("unbix();", 200);
	{rdelim});
{rdelim}
</script>
{/if}