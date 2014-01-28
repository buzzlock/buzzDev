<?php
/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');
?>

{template file='musicsharing.block.mexpect'}

<div>
    {phrase var='musicsharing.you_can_edit_playlist_info_by_entering_some_information_in_this_box_below'}
</div>

<table cellpadding='0' cellspacing='0' width='100%'><tr><td class='page'>
            {* SHOW RESULT MESSAGE *}
            {if $result != 0}
            <div class='success'><img src='{$core_path}module/musicsharing/static/image/music/success.gif' border='0' class='icon'> Your changes have been saved.</div>
            {/if}

            <br>
            <div>
                <div class="space-line"></div>
                <table class='tabs' cellpadding='0' cellspacing='0'>
                    <tr>
                        <td class='tab0'>&nbsp;</td>
                        <td class='tab1' NOWRAP><a href='{if !isset($aParentModule)}{url link="musicsharing.editplaylist.playlist_".$playlist_info.playlist_id}{else}{$aParentModule.msf.editplaylist}{/if}'>{phrase var='musicsharing.playlist_info'}</a></td><td class='tab'>&nbsp;</td>
                        <td class='tab2' NOWRAP><a href='{if !isset($aParentModule)}{url link="musicsharing.playlistsongs.playlist_".$playlist_info.playlist_id}{else}{$aParentModule.msf.playlistsongs}{/if}'>{phrase var='musicsharing.view_songs'}</a></td><td class='tab'>&nbsp;</td>
                        <td class='tab3'>&nbsp;</td>
                    </tr>
                </table>
            </div>
            <div class='group_row'>
                <table cellpadding="0" cellspacing="5" width="100%">
                    <tr><td valign="top" width="50%">
                            <form enctype="multipart/form-data" name="editplaylist" class="" action="{url link='current'}" method="post">                 
								<div id="js_custom_privacy_input_holder">
									{module name='privacy.build' privacy_item_id=$aForms.playlist_id privacy_module_id='musicsharing_playlist'}
								</div>
								<div class="table">
                                    <div class="table_left"><span class="required">*</span>{phrase var="musicsharing.playlist_name"}:</div>
                                    <div class="table_right"><input type="text" name="val[title]" id="title" value="{$playlist_info.title}"/> </div>
                                </div>
                                <div class="table">
                                    <div class="table_left">{phrase var='musicsharing.playlist_description'}</div>
                                    <div class="table_right"><textarea id="description" name="val[description]" cols="45" rows="6">{$playlist_info.description}</textarea></div>
                                </div>
                                <input type="hidden" name="search" id="search" value="1"/>
                                
                                {if $playlist_info.playlist_image != ""}
                                <div class="table_clear">
                                    {img server_id=$playlist_info.server_id path='musicsharing.url_image' file=$playlist_info.playlist_image max_width='100' max_height='100'}
                                </div>
                                {/if}
                                <div class="table">
                                    <div class="table_left">{phrase var='musicsharing.playlist_image'}:</div>
                                    <div class="table_right">  <input id="playlist_image" type="file" name="playlist_image">  </div>
                                </div>

                                {if phpFox::isModule('privacy') && phpFox::getUserParam('musicsharing.can_set_privacy_in_this_playlist')}
                                <div class="table">
                                    <div class="table_left">
                                        {phrase var='musicsharing.privacy'}:
                                    </div>
                                    <div class="table_right">
                                        {module name='privacy.form' privacy_name='privacy' privacy_info=phpFox::getPhrase('musicsharing.control_who_can_comment_on_this_playlist') default_privacy='musicsharing.default_privacy_setting'}
                                    </div>
                                </div>
                                {/if}

                                {if phpFox::isModule('comment') && phpFox::isModule('privacy') && phpFox::getUserParam('musicsharing.can_control_comments_on_playlists')}
                                <div class="table">
                                    <div class="table_left">
                                        {phrase var='musicsharing.comment_privacy'}:
                                    </div>
                                    <div class="table_right">
                                        {module name='privacy.form' privacy_name='privacy_comment' privacy_info=phpFox::getPhrase('musicsharing.control_who_can_comment_on_this_playlist') privacy_no_custom=true}
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
                                                            <a href='{if !isset($aParentModule)}{url link = "musicsharing.myplaylists"}{else}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing.myplaylists'}{/if}'>
								<img src='{$core_path}module/musicsharing/static/image/music/back16.gif' border='0' align="absmiddle" >
                                                            </a>
							</td>
							<td class='button' nowrap='nowrap' style="border-left: none;">
                                                            <a href='{if !isset($aParentModule)}{url link = "musicsharing.myplaylists"}{else}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing.myplaylists'}{/if}'>
								{phrase var='musicsharing.back_to_my_playlists'} {phrase var='musicsharing.playlists'}
                                                            </a>
							</td>
						</tr>
					</table>
				
        </td>
	</tr>
</table>

