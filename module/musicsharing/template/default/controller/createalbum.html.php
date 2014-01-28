<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');
?>

{template file='musicsharing.block.mexpect'}

{if $settings.can_create_album eq 1}
{if $settings.max_album_created > $total_album}
 <!--h1>{phrase var='musicsharing.create_album'}</h1-->
		<form enctype="multipart/form-data" name="addalbum" class="" action="{url link='current'}" method="post">
			<div id="js_custom_privacy_input_holder">
				
			</div>
		  <div class="table">
              <div class="table_left"><span class="required">*</span>{phrase var='musicsharing.album_name'}:</div>
              <div class="table_right"><input type="text" name="val[title]" value="{if isset($title)}{$title}{/if}" id="title"/> </div>
          </div>
          <div class="table">
              <div class="table_left">{phrase var='musicsharing.album_description'}:</div>
              <div class="table_right"><textarea id="description" name="val[description]" cols="45" rows="6">{if isset($description)}{$description}{/if}</textarea></div>
          </div>
              <input type="hidden" name="search" id="search" value="1"/>
           <div class="table_clear">
              <input type="checkbox" name="is_download" id="download" value="1" checked="true"/>   {phrase var='musicsharing.allow_to_download_this_album'}
          </div>
          <div class="table">
              <div class="table_left">{phrase var='musicsharing.album_image'}:</div>
              <div class="table_right">  <input id="album_image" type="file" name="album_image">  </div>
          </div>

        {if phpFox::isModule('privacy') && phpFox::getUserParam('musicsharing.can_set_privacy_in_this_album')}
        <div class="table">
                <div class="table_left">
                        {phrase var='musicsharing.privacy'}:
                </div>
                <div class="table_right">
                        {module name='privacy.form' privacy_name='privacy' privacy_info=phpFox::getPhrase('musicsharing.control_who_can_see_this_album') default_privacy='musicsharing.default_privacy_setting'}
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
        <input type="submit" name="submit" value="{phrase var="musicsharing.save_album"}" class="button" />

</form>
{else}
    <div style="color:red">
        {phrase var='musicsharing.you_have_reach_to_limit_for_number_of_albums_please_contact_admin_to_get_more_information'}.
   </div>
 {/if}
 {else}
{phrase var='musicsharing.you_do_not_have_permission_to_create_album'}.
 {/if}