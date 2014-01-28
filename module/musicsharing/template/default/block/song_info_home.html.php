<?php
defined('PHPFOX') or exit('NO DICE!');

?>
<tr>
	<?php
		//$date = date_parse_from_format("Y-m-d H:i:s", $this->_aVars['iSong']['creation_date']);
		$iDate = $this->_aVars['iSong']['_creation_date'];//mktime($date['hour'], $date['minute'], $date['second'], $date['month'], $date['day'], $date['year']);
		//
		//var_dump($this->_aVars['iSong']);
	?>
	<td class="song-info-items" width="85%" style="">
            <a class="song-title" href="{if !isset($aParentModule)}{url link='musicsharing.listen.music_'.$iSong.song_id}{else}{url link='pages.'.$aParentModule.item_id.'.musicsharing.listen.music_'.$iSong.song_id}{/if}"  class="title_thongtin2">{if $iSong.title == ""}{phrase var='musicsharing.no_name'}{else}{$iSong.title}{/if}</a>
            <!--div  class="profile_blogentry_date" style="padding-top:5px;">
               {phrase var='musicsharing.album_upper'}: <a href="{if !isset($aParentModule)}{url link='musicsharing.listen.album_'.$iSong.album_id'}{else}{url link='pages.'.$aParentModule.item_id.'.musicsharing.listen.album_'.$iSong.album_id}{/if}">{$iSong.album_title}</a>
            </div-->
			<div  class="profile_blogentry_date" style="">
				{phrase var="musicsharing.album"}: <a href="{if !isset($aParentModule)}{url link='musicsharing.listen.album_'.$iSong.album_id'}{else}{url link='pages.'.$aParentModule.item_id.'.musicsharing.listen.album_'.$iSong.album_id}{/if}">{$iSong.album_title}</a>{*
				*} <span>&nbsp;|&nbsp;</span> {*
				*}{phrase var='musicsharing.play_s'}: <span style="color: #3B5998;">{$iSong.play_count}</span>
				{*<span>|</span>*}
			</div>
			<div  class="" style="">
				{phrase var='musicsharing.singer'}:  {if $iSong.singer_title != ""}
				   <a href="{if !isset($aParentModule)}{url link='musicsharing.song.singer_'.$iSong.singer_id}{else}{url link='pages.'.$aParentModule.item_id.'.musicsharing.song.singer_'.$iSong.singer_id}{/if}">{$iSong.singer_title}</a>
				{else}
					{if $iSong.other_singer != ""}
						  <a href="{if !isset($aParentModule)}{url link='musicsharing.song.wheresinger_'.$iSong.other_singer|base64_encode}{else}{url link='pages.'.$aParentModule.item_id.'.musicsharing.song.wheresinger_'.$iSong.other_singer|base64_encode}{/if}">{$iSong.other_singer}</a>
					{else} <span style="color: #3B5998;">{phrase var='musicsharing.not_updated'}</span> {/if}
				{/if}{*
				*} <span>&nbsp;|&nbsp;</span> {*
				*}{phrase var='musicsharing.uploader'}: <a style="border: none;" href="{url link=$iSong.user_name}" title="{$iSong.full_name}">{$iSong.full_name}</a>
			</div>
    </td>
    <td align="right" valign="top" style="vertical-align:top; padding:7px; padding-right: 0px; border-bottom:1px solid #D9D9D9;/*border-right:1px solid #D9D9D9*/" >
		<div style="">
			{if ( $iSong.is_download eq 1 ) && $settings.can_download_song eq 1 }
				<a id="download_sbps{$iSong.song_id}" style="float: right;" class="download-icon" title="{phrase var='musicsharing.download'}" href="{url link='musicsharing.download' iSongId=$iSong.song_id}" onclick="window.location.href=this.href;return false;"> </a>
			{else}
				<a style="float: right; background-position: -47px -38px" class="download-icon" href="javascript:void(0);" onClick="alert(oTranslations['musicsharing.you_do_not_have_permission_to_download_songs']); return false;"><img src="{$core_path}module/musicsharing/static/image/music/icon_download_disable.png" /> </a>
			{/if}
			<a style="float: right;" onclick="return addPL(this);" href="&height=100&width=400&idsong={$iSong.song_id}{*#?call=musicsharing.addplaylist&amp;height=100&amp;width=400&amp;idsong={$iSong.song_id}*}" class="addpllnk add-icon" title="{phrase var='musicsharing.add_song_to_playlist'}">  </a>
		</div>
    </td>
</tr>