<?php
/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');
?>

{template file='musicsharing.block.mexpect'}

<!--img src='{$core_path}module/musicsharing/static/image/music/music_icon.gif' border='0' class='icon_big' class="margin-bottom-15"-->
{if $settings.can_edit_album eq 1}
<!--div class='page_header'>{phrase var='musicsharing.edit_album'} <a href='{url link="musicsharing.albumsongs.album_".$album_info.album_id}'>{$album_info.title}</a></div-->
<div>
    {phrase var='musicsharing.you_can_edit_albums_info_by_entering_some_information_in_this_box_below'}
</div>

<table cellpadding='0' cellspacing='0' width='100%'><tr><td class='page'>
            {if $result != 0}
                <div class='success'><img src='{$core_path}module/musicsharing/static/image/music/success.gif' border='0' class='icon'> {phrase var='musicsharing.your_changes_have_been_saved'}</div>
            {/if}

            <br>
            <div>
                <div class="space-line"></div>
                <table class='tabs' cellpadding='0' cellspacing='0'>
                    <tr>
                        <td class='tab0'>&nbsp;</td>
                        <td class='tab1' NOWRAP><a href='{if !isset($aParentModule)}{url link="musicsharing.editalbum.album_".$album_info.album_id}{else}{$aParentModule.msf.editalbum}{/if}'>{phrase var='musicsharing.album_info'}</a></td><td class='tab'>&nbsp;</td>
                        <td class='tab2' NOWRAP><a href='{if !isset($aParentModule)}{url link="musicsharing.albumsongs.album_".$album_info.album_id}{else}{$aParentModule.msf.albumsongs}{/if}'>{phrase var='musicsharing.view_upload_songs'}</a></td><td class='tab'>&nbsp;</td>
                        <td class='tab3'>&nbsp;</td>
                    </tr>
                </table>
            </div>
            <div class='group_row'>
                <table cellpadding="0" cellspacing="5" width="100%">
                    <tr><td valign="top" width="50%">
                            <form enctype="multipart/form-data" name="addalbum" class="" action="{url link='current'}" method="post">
								<div id="js_custom_privacy_input_holder">
									{module name='privacy.build' privacy_item_id=$aForms.album_id privacy_module_id='musicsharing_album'}
								</div>
                                <div class="table">
                                    <div class="table_left"><span class="required">*</span>{phrase var='musicsharing.album_name'}:</div>
                                    <div class="table_right"><input type="text" name="val[title]" id="title" value="{$album_info.title}"/> </div>
                                </div>
                                <div class="table">
                                    <div class="table_left">{phrase var='musicsharing.album_description'}:</div>
                                    <div class="table_right"><textarea id="description" name="val[description]" cols="45" rows="6">{$album_info.description}</textarea></div>
                                </div>
								<input type="hidden" name="search" id="search" value="1"/>
								<input type="hidden" name="val[server_id]" value="{$album_info.server_id}"/>
                                <div class="table_clear">
                                    {if $album_info.is_download == 1}
                                    <input type="checkbox" name="is_download" id="is_download" value="1" checked="true"/>
                                    {else}
                                    <input type="checkbox" name="is_download" id="is_download" value="1" />
                                    {/if}
                                    {phrase var='musicsharing.allow_to_download_this_album'}
                                </div>
                                {if $aForms.album_image != ""}
                                    <div class="table_clear">
                                        {img server_id=$aForms.server_id path='musicsharing.url_image' suffix='_115' file=$aForms.album_image max_width='100' max_height='100'}
                                    </div>
                                {/if}
                                <div class="table">
                                    <div class="table_left">{phrase var='musicsharing.album_image'}:</div>
                                    <div class="table_right">  <input id="album_image" type="file" name="album_image">  </div>
                                </div>

                                {if phpFox::isModule('privacy') && phpFox::getUserParam('musicsharing.can_set_privacy_in_this_album')}
                                <div class="table_clear">
                                    <div class="table_left">
                                        {phrase var='musicsharing.privacy'}:
                                    </div>
                                    <div class="table_right">
                                        {module
											name='privacy.form'
											privacy_name='privacy'
											privacy_info=phpFox::getPhrase('musicsharing.control_who_can_see_this_album')
											default_privacy='musicsharing.default_privacy_setting'
                                        }
                                    </div>
                                </div>
                                {/if}

                                {if phpFox::isModule('comment') && phpFox::isModule('privacy') && phpFox::getUserParam('musicsharing.can_control_comments_on_albums')}
                                <div class="table">
                                    <div class="table_left">
                                        {phrase var='musicsharing.comment_privacy'}:
                                    </div>
                                    <div class="table_right">
                                        {module name='privacy.form' privacy_name='privacy_comment' privacy_info=phpFox::getUserParam('musicsharing.control_who_can_comment_on_this_album') privacy_no_custom=true}
                                    </div>
                                </div>
                                {/if}

                                <div class="table_clear">
                                    <input type="submit" name="submit" value="{phrase var='musicsharing.save_changes'}" class="button" />
                                </div>
                            </form>
                        </td>
                    </tr>
                </table>
				
					<table cellpadding='0' cellspacing='3' width='150'>
						<tr>
							<td class='button' nowrap='nowrap' style="border-right: none;">
                                                            <a href='{if !isset($aParentModule)}{url link = "musicsharing.myalbums"}{else}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing.myalbums'}{/if}'>
								<img src='{$core_path}module/musicsharing/static/image/music/back16.gif' border='0' align="absmiddle" >
                                                            </a>
							</td>
							<td class='button' nowrap='nowrap' style="border-left: none;">
                                                            <a href='{if !isset($aParentModule)}{url link = "musicsharing.myalbums"}{else}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing.myalbums'}{/if}'>
								{phrase var='musicsharing.back_to_my_albums'}
                                                            </a>
							</td>
						</tr>
					</table>
				
        </td></tr></table>

{else}
{phrase var='musicsharing.you_do_not_have_permission_to_edit_album'}.
{/if}
