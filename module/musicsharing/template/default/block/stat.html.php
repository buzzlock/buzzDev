<?php

defined('PHPFOX') or exit('NO DICE!');

?>
<ul class="action">
    <li><a href="{*
		*}{if !isset($aParentModule)}{*
			*}{url link ='musicsharing.album'}{*
		*}{else}{*
			*}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing.album'}{*
		*}{/if}{*
	*}"><img src="{$core_path}/module/musicsharing/static/image/album.png" alt="" style="vertical-align: middle;"> {phrase var='musicsharing.total_albums'}: {$album}</a></li>
    <li><a href="{*
		*}{if !isset($aParentModule)}{*
			*}{url link ='musicsharing.song'}{*
		*}{else}{*
			*}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing.song'}{*
		*}{/if}{*
	*}"><img src="{$core_path}/module/musicsharing/static/image/song.png" alt="" style="vertical-align: middle;"> {phrase var='musicsharing.total'} {phrase var='musicsharing.songs'}: {$song}</a></li>
    <li><a href="{*
		*}{if !isset($aParentModule)}{*
			*}{url link ='musicsharing.playlist'}{*
		*}{else}{*
			*}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing.playlist'}{*
		*}{/if}{*
	*}"><img src="{$core_path}/module/musicsharing/static/image/playlist.png" alt="" style="vertical-align: middle;"> {phrase var='musicsharing.total_playlists'}: {$playlist}</a></li>
</ul>