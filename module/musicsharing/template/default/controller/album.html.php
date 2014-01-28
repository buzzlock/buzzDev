<?php
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package 		Phpfox
 * @version 		$Id: album.html.php 1586 2010-05-17 17:10:46Z Raymond_Benc $
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

{if !isset($suppress) && $cartist}
	<div>
		{phrase var='musicsharing.this_is_all_album_of_artist'}&nbsp;<a href="{url link=$cartist.user_name}" title="{$cartist.full_name}">{$cartist.full_name|clean}</a>
		<br /><br />
	</div>
{/if}

{if !isset($suppress)}
{literal}
<script language="javascript" type="text/javascript">
$Behavior.MusicSharingTTInit = function() {
    $(document).ready(function(){
        tt_Init();
    });
}
</script>
{/literal}

	<style type="text/css">
		.pic_album .overlay
		{ldelim}
			background: url("{$core_path}/module/musicsharing/static/image/m_size.png") no-repeat scroll 0 0 transparent;    
		{rdelim}
		.border_top{ldelim}
			border-top: 1px solid #DFDFDF;
			margin-bottom: 10px;
		{rdelim}
	</style>
    
<!-- FullSite -->
{if !phpfox::isMobile()}
	<div class="album_list">
		<div class="margin-top-0">
		</div>
		<div class="list"> 
			{if count($list_info)>0}
				{foreach from=$list_info key=index item=aAlbum}
					{template file='musicsharing.block.album_info_3'}
				{/foreach}
			{else}
				<div align="left" class="red margin-right-10 margin-bottom-10 margin-top-10" style="">{phrase var='musicsharing.there_is_no_album_yet'}</div> 
			{/if}
			<br class="clear">
		</div>
	</div>
	<div class="clear">
{else}
	{if count($list_info)>0}
		<div class="m_album_playlist">
		{foreach from=$list_info key=index item=aAlbum}
			{template file='musicsharing.block.mobile.album_info'}
		{/foreach}
		</div>
	{else}
		<div align="left" class="red margin-right-10 margin-bottom-10 margin-top-10" style="">{phrase var='musicsharing.there_is_no_album_yet'}</div> 
	{/if}
{/if}
	</div>
	{pager} 
{else}
	{phrase var='musicsharing.you_do_not_have_permission_to_view_this_album'}
{/if}
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
$Behavior.MusicSharingAlbum = function() {ldelim}
	$(document).ready(function(){ldelim}
		$(".header_bar_search_holder").find("input[type=text]").css({ldelim}
			"color": "#000000"
		{rdelim}).unbind("focus");
		setTimeout("unbix();", 200);
	{rdelim});
{rdelim}
</script>
{/if}