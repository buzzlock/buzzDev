<?php 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<ul class="action">
{foreach from=$aTopSongs item=aSong}
       <li> <a class="first" href="{*
			*}{if !isset($aParentModule)}{*
				*}{url link='musicsharing.listen.music_'.$aSong.song_id}{*
			*}{else}{*
				*}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing.listen.music_'.$aSong.song_id}{*
			*}{/if}{*
		*}">{$aSong.title} </a> </li>
{/foreach}
</ul>