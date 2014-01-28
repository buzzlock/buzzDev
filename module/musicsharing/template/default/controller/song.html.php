<?php
/**
 * [PHPFOX_HEADER]
 *
 * @copyright		YouNet
 * @author  		Minh Nguyen
 * @package 		Phpfox
 * @version 		$Id: song.html.php 1318 2010-12-10  $
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

{if $csinger}
	<div>
		{if $csinger != "musicsharing.this_is_all_song_by_other_singer"}
			{phrase var='musicsharing.this_is_all_song_of_singer'}&nbsp;<a href="{url link ='musicsharing.song.singer_'.$csinger.singer_id.'.sititle_'.$csinger.title}">{$csinger.title}</a>
		{else}
			{phrase var=$csinger}
		{/if}
		<br /><br />
	</div>
{/if}

{if $ccat}
	<div>
		{phrase var='musicsharing.this_is_all_song_of_category'}&nbsp;<a href="{url link = 'musicsharing.song.cat_'.$ccat.cat_id.'.catitle_'.$ccat.title}">{$ccat.title}</a>
		<br /><br />
	</div>
{/if}

{if $cartist}
	<div>
		{phrase var='musicsharing.this_is_all_song_of_artist'}&nbsp;<a href="{url link=$cartist.user_name}" title="{$cartist.full_name}">{$cartist.full_name|clean}</a>
		<br /><br />
	</div>
{/if}

{if count($list_info)}
	{if !phpfox::isMobile()}
	<table cellpading="0" cellspacing="0" border="0" width="100%" class="song-list">
		{foreach from=$list_info  item=iSong}
			{template file='musicsharing.block.song_info'}
		{/foreach}
	</table>
	{else}
		<div class="song-list">
		{foreach from=$list_info  item=iSong}
			{template file='musicsharing.block.mobile.song_info'}
		{/foreach}
		</div>
	{/if}
	<div style="msong-paginator"> {pager}  </div>
{else}
	<div align="left" class="red margin-right-10 margin-bottom-10 margin-top-10"> {phrase var='musicsharing.there_is_no_any_songs_yet'}.</div>
{/if}
<script language="javascript" type="text/javascript">
	$Behavior.ready_songList = function(){l}
		setTimeout(function(){l}
			$(".download-icon").unbind();
		{r}, 200);
	{r};
</script>
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
    
$Behavior.MusicSharingSong = function() {ldelim}
	$(document).ready(function(){ldelim}
		$(".header_bar_search_holder").find("input[type=text]").css({ldelim}
			"color": "#000000"
		{rdelim}).unbind("focus");
		setTimeout("unbix();", 200);
	{rdelim});
{rdelim}
</script>
{/if}