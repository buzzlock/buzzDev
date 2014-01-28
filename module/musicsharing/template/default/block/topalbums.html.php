<?php
defined('PHPFOX') or exit('NO DICE!');

?>
<ul class="action">
{foreach from=$aTopAlbums item=aAlbum}
{if $aAlbum.num_track > 0}
       <li> <a class="first" href="{*
			*}{if !isset($aParentModule)}{*
				*}{url link='musicsharing.listen.album_'.$aAlbum.album_id}{*
			*}{else}{*
				*}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing.listen.album_'.$aAlbum.album_id}{*
			*}{/if}{*
		*}">{$aAlbum.title} </a> </li>
  {/if}
  {/foreach}
</ul>