<?php

defined('PHPFOX') or exit('NO DICE!');

?>
<tr id="playlist_{$iPlaylist.playlist_id}" class='classified_row {if $iPlaylist.index % 2 == 0 } classified_even{else} classified_odd{/if}' style="height:30px;">
    <td valign="middle" class=" line-middle" style="vertical-align: middle;font-weight: bold; text-align: center">{$iPlaylist.index}</td>
    <td valign="middle" class=" line-middle" align="left" width="5%" style="padding:0;font-weight:bold; vertical-align: middle;text-align: left;">
        <input type='checkbox' class="playlist_checkbox" id="playlist_{$iPlaylist.playlist_id}_checkbox" name='delete_playlist[]' value='{$iPlaylist.playlist_id}' onclick="checkDisableStatus()"/>
    </td>
	<td valign="middle" class=" line-middle" width="30%" style="font-weight:normal;vertical-align: middle;">
        <a href='{if !isset($aParentModule)}{url link="musicsharing.playlistsongs.playlist_".$iPlaylist.playlist_id}{else}{url link=$aParentModule.module_id.".".$aParentModule.item_id.".musicsharing.playlistsongs.playlist_".$iPlaylist.playlist_id}{/if}'>{$iPlaylist.title}</a>
    </td>
<!--    <td valign="middle" style='color: #888888;' width="10%" align="left" >
        &nbsp;&nbsp;<a href="{url link='musicsharing.myplaylists.orderplaylist_'.$iPlaylist.playlist_id.'/page_'.$cur_page}"><img src="{$core_path}module/musicsharing/static/image/pic_up.bmp"/></a>
        <a href="{url link='musicsharing.myplaylists.orderplaylistdown_'.$iPlaylist.playlist_id.'/page_'.$cur_page}"><img src="{$core_path}module/musicsharing/static/image/pic_down.gif"/></a>
    </td>-->


    <td valign="middle" class=" line-middle" style='color: #888888;vertical-align: middle;' width="20%" align="center" >
		<a href='{if !isset($aParentModule)}{url link="musicsharing.playlistsongs.playlist_".$iPlaylist.playlist_id}{else}{url link=$aParentModule.module_id.".".$aParentModule.item_id.".musicsharing.playlistsongs.playlist_".$iPlaylist.playlist_id}{/if}'>{$iPlaylist.num_track}</a>
    </td>
    <td valign="middle" class=" line-middle" width="30%" align="center" style='color: #888888;vertical-align: middle;'>
        <a href="javascript:void(0);" onclick="if (confirm('{phrase var='musicsharing.are_you_sure' phpfox_squote=true}')) {literal}{{/literal} delete_playlist('{$iPlaylist.playlist_id}','playlist_{$iPlaylist.playlist_id}'){literal}}{/literal} return false;">{phrase var='musicsharing.delete'}</a>
		&nbsp;|&nbsp;
        <a href="{if !isset($aParentModule)}{url link='musicsharing.editplaylist.playlist_'.$iPlaylist.playlist_id}{else}{url link=$aParentModule.module_id.".".$aParentModule.item_id.".musicsharing.editplaylist.playlist_".$iPlaylist.playlist_id}{/if}">{phrase var='musicsharing.edit'}</a>
        &nbsp;|&nbsp;
		<a target="_blank" href="{if !isset($aParentModule)}{url link='musicsharing.listen.playlist_'.$iPlaylist.playlist_id}{else}{url link=$aParentModule.module_id.".".$aParentModule.item_id.".musicsharing.listen.playlist_".$iPlaylist.playlist_id}{/if}">{phrase var='musicsharing.play'}</a>
    </td>
	<td valign="middle" class=" line-middle" width="20%" align="center" style='color: #888888;vertical-align: middle;'>
		{if $iPlaylist.profile eq 1}
            <img src="{$core_path}module/musicsharing/static/image/music/gs16.png" style="margin-left:23px;margin-right:24px;" />
        {else}
            <a href="{*
				*}{if !isset($aParentModule)}{*
					*}{url link='musicsharing.myplaylists.setdefaultplaylist_'.$iPlaylist.playlist_id}{*
				*}{else}{*
					*}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing.myplaylists.setdefaultplaylist_'.$iPlaylist.playlist_id}{*
				*}{/if}{*
			*}">{phrase var='musicsharing.set_default'}</a>
        {/if}
	</td>
    </tr>
    <tr><td valign="middle" colspan="6" class="classified_bottom line-middle"></td></tr>