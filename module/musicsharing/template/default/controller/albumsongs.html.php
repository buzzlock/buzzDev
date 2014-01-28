<?php
/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');
?>

{template file='musicsharing.block.mexpect'}

<script language="javascript" type="text/javascript">
    function delete_song(song_id,div_id){ldelim}
        var mySong = document.getElementById(div_id);
        mySong.style.display="none";
        $.ajaxCall('musicsharing.deleteAlbumSong','idSong='+song_id);
    {rdelim}

    function check_all_songs(obj){ldelim}
        var root = document.getElementById('music_list_frame');
        for(i=0; i<root.childNodes.length; i++){ldelim}
            if(document.getElementById(root.childNodes[i].id+"_checkbox") != null){ldelim}
                document.getElementById(root.childNodes[i].id+"_checkbox").checked = obj.checked;
            {rdelim}
        {rdelim}
    {rdelim}
	
    function setDeleteAlbumButtonStatus(status) {ldelim}
        return false;
        if(status){ldelim}
            jQuery('input#delete').attr("disabled", '');
        {rdelim}else{ldelim}
            jQuery('input#delete').attr("disabled", 'disabled');
        {rdelim}
    {rdelim}
    function checkDisableStatus(){ldelim}
        return false;
        var status = false;
        $('.mysong_checkbox').each(function(index, element){ldelim}
            if(element.checked==true){ldelim}
                status =  true;
            {rdelim}
        {rdelim});
        setDeleteAlbumButtonStatus(status);
        return status;
    {rdelim}
</script>
{*<img src='{$core_path}module/musicsharing/static/image/music/music_icon.gif' border='0' class='icon_big' style="margin-bottom: 15px;">*}
<h1>{phrase var='musicsharing.edit_album_view_upload_songs'}{* <a href='{url link="musicsharing.albumsongs.album_".$album_info.album_id}'>{$album_info.title}</a>*}</h1>
<div style="padding-top: 0px;">
    {*phrase var='musicsharing.the_following_is_songs_you_have_added_to_your_albums'}. <br />
    {phrase var='musicsharing.total'}: {$album_info.num_track<br />*}
	{phrase var='musicsharing.album'}: <span style="color: #3B5998;">{$album_info.title}</span>
</div>

<table cellpadding='0' cellspacing='0' width='100%'><tr><td class='page'>
            {* SHOW RESULT MESSAGE *}
            {if isset($result)&&$result != 0}
            <div class='success'><img src='{$core_path}module/musicsharing/static/image/music/success.gif' border='0' class='icon'> {phrase var='musicsharing.your_changes_have_been_saved'}</div>
            {/if}

            <br>
            <div>
                <div class="space-line"></div>
                <table class='tabs' cellpadding='0' cellspacing='0'>
                    <tr>
                        <td class='tab0'>&nbsp;</td>
                        <td class='tab2' NOWRAP><a href='{if !isset($aParentModule)}{url link="musicsharing.editalbum.album_".$album_info.album_id}{else}{$aParentModule.msf.editalbum}{/if}'>{phrase var='musicsharing.album_info'}</a></td><td class='tab'>&nbsp;</td>
                        <td class='tab1' NOWRAP><a href='{if !isset($aParentModule)}{url link="musicsharing.albumsongs.album_".$album_info.album_id}{else}{$aParentModule.msf.albumsongs}{/if}'>{phrase var='musicsharing.view_upload_songs'}</a></td><td class='tab'>&nbsp;</td>
                        <td class='tab3'>&nbsp;</td>
                    </tr>
                </table>
                <div style='margin-top: 0;'>
                    <div class='button_music' style='float: left;'>
                        {if $settings.can_create_album eq 1}
							{if $settings.max_songs > $album_info.num_track }
								<img src='{$core_path}module/musicsharing/static/image/music/plus16.gif' border='0' class="icon"><a href='{if !isset($aParentModule)}{url link="musicsharing.upload.album_".$album_info.album_id}{else}{url link="pages.".$aParentModule.item_id.".musicsharing.upload.album_".$album_info.album_id}{/if}'>{phrase var='musicsharing.upload_songs'}</a>
							{else}
								<div style="color: red;">
									{phrase var='musicsharing.you_have_reach_to_limit_for_number_of_songs_for_this_album_please_contact_admin_to_get_more_informat'}.
								</div>
							{/if}
						{else}
							<div style="color: red;">
								{phrase var='musicsharing.you_do_not_have_permission_to_upload_songs'}
							</div>
                        {/if}
                    </div>
                    <div style='clear: both; height: 0px;'></div>
                </div>
                <br />
            </div>
            {* SHOW SONGS IF ANY EXIST *}
            {if $album_info.num_track == 0}
            <div align="center"> {phrase var='musicsharing.there_is_no_any_songs_yet'} </div>
            {else}
            <form action='{if !isset($aParentModule)}{url link="musicsharing.albumsongs.album_".$album_info.album_id}{else}{url link="pages.".$aParentModule.item_id.".musicsharing.albumsongs.album_".$album_id}{/if}' method='post'>

                {* HEADER *}
                <ul class="seMusicHeader" style='width: 100%'>
                    <li>
                        <table cellpadding='0' cellspacing='0' class="seMusicRowInnerTable" style="border:none">
                            <tr style="font-weight:bold; height:30px; background:url({$core_path}module/musicsharing/static/image/music/header_bg.gif)  repeat-x; ">
                                <td class="seMusicDeleteCheckbox" style="padding:0px 3px 0px 3px;text-align:left;border-color:#CCCCCC; border-width: 1px 0px 1px 0px; border-style:solid none solid none;">
                                    <input type='checkbox' onclick="check_all_songs(this);checkDisableStatus();" id='delete_music_check_all' name='delete_music_check_all' />
                                </td>
                                <td class="seMusicRowButton" align="center" style="padding:0px 3px 0px 3px;border-color:#CCCCCC; border-width: 1px 0px 1px 0px; border-style:solid none solid none;">
                                    &nbsp;
                                </td>
                                <td class='seMusicRowTitle' style="padding:0;border-color:#CCCCCC; border-width: 1px 0px 1px 0px; border-style:solid none solid none;">
                                    {phrase var='musicsharing.song'} {phrase var='musicsharing.name'}
                                </td>
                                <td class='seMusicRowTitle' style="padding:0;width:100px; padding:0;border-color:#CCCCCC; border-width: 1px 0px 1px 0px; border-style:solid none solid none;">
                                    {phrase var='musicsharing.singer'}
                                </td>
                                <td class='seMusicRowTitle' style="padding:0;width:100px; padding:0;border-color:#CCCCCC; border-width: 1px 0px 1px 0px; border-style:solid none solid none;">
                                    {phrase var='musicsharing.category'}
                                </td>
                                <td class="seMusicRowFilesize" align='center' style="padding:0;width:100px;padding:0;border-color:#CCCCCC; border-width: 1px 0px 1px 0px; border-style:solid none solid none;">
                                    {phrase var='musicsharing.filesize'}
                                </td>
                                <td  align='center' style="width:100px;padding:0;border-color:#CCCCCC; border-width: 1px 0px 1px 0px; border-style:solid none solid none;">
                                    {phrase var='musicsharing.options'}
                                </td>
                                <td align="left"  nowrap="nowrap" style="width:10px;border-color:#CCCCCC; border-width: 1px 0px 1px 0px; border-style:solid none solid none;">

                                </td>
                            </tr>
                        </table>
                    </li>
                </ul>
                <ul class="userMusicList" style='width: 100%;border:none;' id="music_list_frame">
                    {foreach from=$list_info key=index_song  item=iSong}
                    {template file='musicsharing.block.mysong_info'}
                    {/foreach}
                </ul>
                <tr>
                    <td colspan="6" class="classified_bottom" style="padding-top:5px">
                        <div class="table_bottom">
                            <input type="submit" name="delete" id="delete" value="{phrase var='musicsharing.delete_selected_song'}" class="sJsConfirm delete button sJsCheckBoxButton " />
                            <input type='hidden' name='task' value='dodelete' />
                        </div>
                    </td>
                </tr>
            </form>
            {/if}
</table>           <br/>
<div style="padding-left:5px ; padding-right: 5px;"> {pager}  </div>
<div class="clear"></div>

	<table cellpadding='0' cellspacing='3' width='150'>
		<tr>
			<td class='button' nowrap='nowrap' style="border-right: none;">
                            <a href='{if !isset($aParentModule)}{url link = "musicsharing.myalbums"}{else}{url link="pages.".$aParentModule.item_id.".musicsharing.myalbums"}{/if}'>
				<img src='{$core_path}module/musicsharing/static/image/music/back16.gif' border='0' align="absmiddle" >
                            </a>
			</td>
			<td class='button' nowrap='nowrap' style="border-left: none;">
                            <a href='{if !isset($aParentModule)}{url link = "musicsharing.myalbums"}{else}{url link="pages.".$aParentModule.item_id.".musicsharing.myalbums"}{/if}'>
				{phrase var='musicsharing.back_to_my_albums'}
                            </a>
			</td>
		</tr>
	</table>




