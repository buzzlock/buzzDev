<?php 

defined('PHPFOX') or exit('NO DICE!'); 
?>

{if isset($aAlbums)}
{foreach from=$aAlbums key = key item=aAlbum}
	<div class="" style="border-bottom: 1px solid #D7D7D7;padding-bottom: 5px; margin-bottom: 5px;">
		<div style="background-repeat: no-repeat;height: 85px;padding-left: 2px;background-image: url('{$core_path}module/musicsharing/static/image/m_size_avatar.png'); background-repeat: no-repeat;">
			<a href="{url link='musicsharing.listen.album_'.$aAlbum.album_id}" title="{$aAlbum.title|clean}">
				{if $aAlbum.album_image != ""}
					<img width="75" border="0" height="75" src="{$core_path}module/musicsharing/static/image/space.gif" style="float: left; margin-right: 10px; background: url('{img server_id=$aAlbum.server_id path='musicsharing.url_image' file=$aAlbum.album_image suffix='_115' max_width=115 max_height=115 return_url=true}') no-repeat scroll center center transparent;" alt="{$aAlbum.title}">
				{else}
					<img width="75" border="0" height="75" src="{$core_path}module/musicsharing/static/image/space.gif" style="float: left; margin-right: 10px; background: url('{$core_path}module/musicsharing/static/image/music/nophoto_album_thumb.png') no-repeat scroll center center transparent;" alt="{$aAlbum.title}">
				{/if}
			</a>
			<div class="" style="float: none;">
				<div style="padding-top:5px;">
					<a href="{url link='musicsharing.listen.album_'.$aAlbum.album_id}" title="{$aAlbum.title}">{$aAlbum.title|clean|shorten:20:"...":false}</a>
				</div>
				<div style="padding-top:5px;">
					{phrase var='musicsharing.uploader'}: <a href="{url link= $aAlbum.user_name}" title="{$aAlbum.full_name}">{$aAlbum.full_name|clean|shorten:15:"...":false}</a>
				</div>
				<div style="padding-top:5px;">
					{phrase var='musicsharing.play_count'}: <span>{$aAlbum.play_count}</span>
				</div>
			</div>
		</div>
	</div>
	<div class="clear"></div>
{/foreach}
{/if}