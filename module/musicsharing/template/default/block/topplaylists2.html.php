<?php 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<ul class="action">
{foreach from=$aNewPlaylists item=aPlaylist}
{if $aPlaylist.num_track > 0}
       <li> <a class="first" href="{*
			*}{if !isset($aParentModule)}{*
				*}{url link='musicsharing.listen.playlist_'.$aPlaylist.playlist_id}{*
			*}{else}{*
				*}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing.listen.playlist_'.$aPlaylist.playlist_id}{*
			*}{/if}{*
		*}">{$aPlaylist.title} </a> </li>
  {/if}
  {/foreach}
</ul>