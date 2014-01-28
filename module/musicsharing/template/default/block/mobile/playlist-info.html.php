<?php defined('PHPFOX') or exit('NO DICE!'); ?>
<a class="def" title="{$aAlbum.title|clean} - {$aAlbum.user_name}" alt="{$aAlbum.title|clean} - {$aAlbum.user_name}" href="{if !isset($aParentModule)}{url link='musicsharing.listen.playlist_'.$aAlbum.playlist_id}{else}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing.listen.playlist_'.$aAlbum.playlist_id}{/if}">
    <div class="table_left pic_album">
        <span class="img"  {if $aAlbum.playlist_image != ""}style="background: url('{img server_id=$aAlbum.server_id path='musicsharing.url_image' suffix='_115' file=$aAlbum.playlist_image max_width='115' max_height='115' return_url=true}') no-repeat scroll center center transparent;"{else} style="background: url('{$core_path}module/musicsharing/static/image/music/nophoto_album_thumb_mobile.png') no-repeat scroll center center transparent; background-size: 100%;"{/if}>
        </span>
    </div>
    <div class="table_right">
		<h2>{$aAlbum.title|clean|shorten:100:"...":false}</h2>
		<p class="icon-play extra_info">{$aAlbum.play_count}</p>
    </div>
	<div class="clear"></div>
</a>    
   

