<?php
defined('PHPFOX') or exit('NO DICE!');
?>
<div class="song-main-item m-songlist">
	<?php $iDate = $this->_aVars['iSong']['_creation_date']; ?>
	<a href="{if !isset($aParentModule)}{url link='musicsharing.listen.music_'.$iSong.song_id}{else}{url link='pages.'.$aParentModule.item_id.'.musicsharing.listen.music_'.$iSong.song_id}{/if}">
		<p class="song-title title_thongtin2" >{if $iSong.title == ""}{phrase var='musicsharing.no_name'}{else}{$iSong.title}{/if}</p>
		<div class="profile_blogentry_date">
		   {phrase var='musicsharing.singer'}:  {if $iSong.singer_title != ""}
			   <a class="singer" href="{if !isset($aParentModule)}{url link='musicsharing.song.singer_'.$iSong.singer_id}{else}{url link='pages.'.$aParentModule.item_id.'.musicsharing.song.singer_'.$iSong.singer_id}{/if}">{$iSong.singer_title}</a>
		   {else}
				{if $iSong.other_singer != ""}
                    <a class="singer" href="{if !isset($aParentModule)}{url link='musicsharing.song.wheresinger_'.$iSong.other_singer|base64_encode}{else}{url link='pages.'.$aParentModule.item_id.'.musicsharing.song.wheresinger_'.$iSong.other_singer|base64_encode}{/if}">{$iSong.other_singer}</a>
				{else} 
                    <span class="singer">{phrase var='musicsharing.not_updated'} </span>
                {/if}
		   {/if}
		</div>
		<div  class="profile_blogentry_date">
           <p class="icon-play">{$iSong.play_count}</p>
		 </div>
	</a>
</div>