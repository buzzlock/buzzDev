<?php
defined('PHPFOX') or exit('NO DICE!');
?>
<tr>
	<?php $iDate = $this->_aVars['iSong']['_creation_date']; ?>
	<td width="80%" class="song-main-item {if phpfox::isMobile()}m-songlist{/if}">
		<a class="song-title title_thongtin2" {if !phpfox::isMobile()} style="width: 400px; overflow: hidden;display: block;" {/if} href="{if !isset($aParentModule)}{url link='musicsharing.listen.music_'.$iSong.song_id}{else}{url link='pages.'.$aParentModule.item_id.'.musicsharing.listen.music_'.$iSong.song_id}{/if}">{if $iSong.title == ""}{phrase var='musicsharing.no_name'}{else}{$iSong.title}{/if}</a>
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
            {if !phpfox::isMobile()}
                {phrase var='musicsharing.posted_date'}:&nbsp;
                <?php echo(phpFox::getLib('date')->convertTime($iDate)); ?>
                &nbsp;{phrase var='musicsharing.by'}&nbsp;
                <a style="border: none;" href="{url link=$iSong.user_name}" title="{$iSong.full_name}">{$iSong.full_name}</a>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                {phrase var='musicsharing.play_count'}: {$iSong.play_count}
            {else}
                <p class="icon-play">{$iSong.play_count}</p>
            {/if}
		</div>
    </td>
    {if !phpfox::isMobile()}
        <td align="right" class="action-group" style="vertical-align: top;" >
            <div class="" style="float: right;width: 92px;">
                {if isset($inMySong) && $inMySong=="true"}
                    <a style="float: right;" href="#" onclick="confirmDelete({$iSong.song_id}); return false;" class="delete-icon float-right" title="{phrase var='musicsharing.delete_this_song'}"> </a>
                    <a style="float: right;" href="#?call=musicsharing.editsong&height=350&width=500&idsong={$iSong.song_id}&page=1&album={$iSong.album_id}&inMySong=true" class="inlinePopup edit-icon float-right" title="{phrase var="musicsharing.edit_this_song"}"> </a>
                {/if}
                {if ( $iSong.is_download eq 1 ) && $settings.can_download_song eq 1 }
                    <a style="float: right;" class="download-icon" title="{phrase var='musicsharing.download'}" href="{url link='musicsharing.download' iSongId=$iSong.song_id}" onclick="window.location.href=this.href; return false;"> </a>
                {else}
                    <a style="float: right; background-position: -47px -38px" class="float-right download-icon" href="#" onclick="alert(oTranslations['musicsharing.you_do_not_have_permission_to_download_songs']);return false;"><img src="{$core_path}module/musicsharing/static/image/music/icon_download_disable.png" /> </a>
                {/if}
                <a style="float: right;" href="#?call=musicsharing.addplaylist&amp;height=100&amp;width=400&amp;idsong={$iSong.song_id}" class="inlinePopup add-icon float-right" title="{phrase var='musicsharing.add_song_to_playlist'}"> </a>
            </div>
        </td>
    {/if}
</tr>
{literal}
<script language="javascript" type="text/javascript">
    function confirmDelete(iSongId) {
        if (confirm(oTranslations['musicsharing.are_you_sure'])) { 
            $.ajaxCall('musicsharing.deleteAlbumSong', 'idSong=' + iSongId);
        } 
        return false;
    }
</script>
{/literal}