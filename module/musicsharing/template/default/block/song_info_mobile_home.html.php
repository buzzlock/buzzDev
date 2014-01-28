<?php
defined('PHPFOX') or exit('NO DICE!');

?>
<tr>
	<td class="music-mobile song-info-items" >
		<a style="display:block" href="{if !isset($aParentModule)}{url link='musicsharing.listen.music_'.$iSong.song_id}{else}{url link='pages.'.$aParentModule.item_id.'.musicsharing.listen.music_'.$iSong.song_id}{/if}">
            <p class="song-title" class="title_thongtin2">{if $iSong.title == ""}{phrase var='musicsharing.no_name'}{else}{$iSong.title}{/if}</p>
			<div>
				{phrase var='musicsharing.singer'}:  
				{if $iSong.singer_title != ""}
				   {$iSong.singer_title}
				{else}
					{if $iSong.other_singer != ""}
						 {$iSong.other_singer}
					{else} {phrase var='musicsharing.not_updated'} {/if}
				{/if}
			</div>
			<p class="icon-play">{$iSong.play_count}</p>
		</a>
    </td>
</tr>