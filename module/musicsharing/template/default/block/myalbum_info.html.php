<?php 

defined('PHPFOX') or exit('NO DICE!');

?>
<tr id="album_{$iAlbum.album_id}" class='classified_row {if $iAlbum.index % 2 == 0 } classified_even{else} classified_odd{/if}' style="height:30px;">
    <td valign="middle" class=" line-middle" style="vertical-align: middle;font-weight: bold; text-align: center">{$iAlbum.index}</td>
    <td valign="middle" class=" line-middle" width="5%" style="vertical-align: middle;padding:0;font-weight:bold; vertical-align: middle;"> 
    <input type='checkbox' class="album_checkbox" id="album_{$iAlbum.album_id}_checkbox" name='delete_album[]' onclick="checkDisableStatus();" value='{$iAlbum.album_id}' />
    </td>
    <td valign="middle" class=" line-middle" width="30%" style="vertical-align: middle;font-weight:normal; ">
        <a href='{if !isset($aParentModule)}{url link="musicsharing.albumsongs.album_".$iAlbum.album_id}{else}{url link=$aParentModule.module_id.".".$aParentModule.item_id.".musicsharing.albumsongs.album_".$iAlbum.album_id}{/if}'>{$iAlbum.title}</a>
    </td>
<!--    <td valign="middle" style='color: #888888;' width="10%" align="left" >
        &nbsp;&nbsp;<a href="{url link='musicsharing.myalbums.orderalbum_'.$iAlbum.album_id.'/page_'.$cur_page}"><img src="{$core_path}module/musicsharing/static/image/pic_up.bmp"/></a>
        <a href="{url link='musicsharing.myalbums.orderalbumdown_'.$iAlbum.album_id.'/page_'.$cur_page}"><img src="{$core_path}module/musicsharing/static/image/pic_down.gif"/></a>
    </td>-->
    <td valign="middle" class=" line-middle" style='vertical-align: middle;color: #888888;' width="10%" align="center" >
		<a class="gray12" target="_blank" href="{*
			*}{if !isset($aParentModule)}{*
				*}{url link='musicsharing.listen.album_'.$iAlbum.album_id}{*
			*}{else}{*
				*}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing.listen.album_'.$iAlbum.album_id}{*
			*}{/if}{*
		*}" title="{$iAlbum.title} - {$iAlbum.user_name}">
			{$iAlbum.play_count}{*jh*}
		</a>
    </td>    
    <td valign="middle" class=" line-middle" style='vertical-align: middle;color: #888888;' width="20%" align="center" >
           <a href='{url link="musicsharing.upload.album_".$iAlbum.album_id}'>{$iAlbum.num_track}</a>
    </td>
    <td valign="middle" class=" line-middle" width="20%" align="center" style='vertical-align: middle;color: #888888;'>
        <a href="javascript:void(0);" onclick="if (confirm('{phrase var='musicsharing.are_you_sure' phpfox_squote=true}')) {literal}{{/literal} delete_album('{$iAlbum.album_id}','album_{$iAlbum.album_id}'){literal}}{/literal} return false;">{phrase var='musicsharing.delete'}</a>
		&nbsp;|&nbsp;&nbsp;
        <a href="{if !isset($aParentModule)}{url link='musicsharing.editalbum.album_'.$iAlbum.album_id}{else}{url link=$aParentModule.module_id.".".$aParentModule.item_id.".musicsharing.editalbum.album_".$iAlbum.album_id}{/if}">{phrase var='musicsharing.edit'}</a>
		{if $settings.can_create_album eq 1}
			&nbsp;|&nbsp;&nbsp;
			{if $settings.max_songs > $iAlbum.num_track }
				<a href='{if !isset($aParentModule)}{url link="musicsharing.upload.album_".$iAlbum.album_id}{else}{url link="pages.".$aParentModule.item_id.".musicsharing.upload.album_".$iAlbum.album_id}{/if}'>{phrase var='musicsharing.upload'}</a>
			{else}
				<span>{phrase var='musicsharing.upload'}</span>
			{/if}
		{else}
			<span>{phrase var='musicsharing.upload'}</span>
		{/if}
        {*<a href="{if !isset($aParentModule)}{url link='musicsharing.albumsongs.album_'.$iAlbum.album_id}{else}{url link=$aParentModule.module_id.".".$aParentModule.item_id.".musicsharing.albumsongs.album_".$iAlbum.album_id}{/if}">{phrase var='musicsharing.upload'}</a>*}
        &nbsp;|&nbsp;&nbsp;
        <a class="gray12" target="_blank" href="{*
			*}{if !isset($aParentModule)}{*
				*}{url link='musicsharing.listen.album_'.$iAlbum.album_id}{*
			*}{else}{*
				*}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing.listen.album_'.$iAlbum.album_id}{*
			*}{/if}{*
		*}" title="{$iAlbum.title} - {$iAlbum.user_name}">
			{phrase var='musicsharing.play'}
		</a>&nbsp;&nbsp
    </td>
    </tr>
    <tr><td valign="middle" class=" line-middle" colspan="7" class="classified_bottom"></td></tr>