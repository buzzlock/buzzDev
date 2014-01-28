<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');                
?>
 <div class="table">
        <div class="table_left">
            {required}{phrase var='musicsharing.allow_viewing_of_albums'}
        </div>
        <div class="table_right">
            {phrase var='musicsharing.do_you_want_to_let_members_view_albums_if_set_to_no_some_other_settings_on_this_page_may_not_apply'}
            <div class="item_is_active_holder"> 
            
                <span class="js_item_active item_is_active"><input type="radio" name="val[can_view_album]" value="1" {if $settings.can_view_album eq 1 } {value type='radio' id='is_active' default='1' selected='true'}{/if}/> {phrase var='admincp.yes'}</span>
                <span class="js_item_active item_is_not_active"><input type="radio" name="val[can_view_album]" value="0" {if $settings.can_view_album eq 0 } {value type='radio' id='is_active' default='0' selected='true'}{/if}/> {phrase var='admincp.no'}</span>
            </div>
        </div>
          <div class="clear"></div>        
 </div>
  <div class="table">   
        <div class="table_left">
            {required}{phrase var='musicsharing.allow_music_on_profile'}
        </div>
        <div class="table_right">
            {phrase var='musicsharing.do_you_want_to_allow_users_to_upload_music_to_their_profile'}
            <div class="item_is_active_holder"> 
            
                <span class="js_item_active item_is_active"><input type="radio" name="val[can_post_on_profile]" value="1" {if $settings.can_post_on_profile eq 1 } {value type='radio' id='is_active' default='1' selected='true'}{/if}/> {phrase var='admincp.yes'}</span>
                <span class="js_item_active item_is_not_active"><input type="radio" name="val[can_post_on_profile]" value="0" {if $settings.can_post_on_profile eq 0 } {value type='radio' id='is_active' default='0' selected='true'}{/if}/> {phrase var='admincp.no'}</span>
            </div>
        </div>
          <div class="clear"></div>        
 </div>
 <div class="table">   
        <div class="table_left">
            {required}{phrase var='musicsharing.allow_editing_of_albums'}
        </div>
        <div class="table_right">
            {phrase var='musicsharing.do_you_want_to_let_members_edit_albums_if_set_to_no_some_other_settings_on_this_page_may_not_apply'}
            <div class="item_is_active_holder"> 
            
                <span class="js_item_active item_is_active"><input type="radio" name="val[can_edit_album]" value="1" {if $settings.can_edit_album eq 1 } {value type='radio' id='is_active' default='1' selected='true'}{/if}/> {phrase var='admincp.yes'}</span>
                <span class="js_item_active item_is_not_active"><input type="radio" name="val[can_edit_album]" value="0" {if $settings.can_edit_album eq 0 } {value type='radio' id='is_active' default='0' selected='true'}{/if}/> {phrase var='admincp.no'}</span>
            </div>
        </div>
          <div class="clear"></div>        
 </div>
  <!--div class="table">  
        <div class="table_left">
            {required}{phrase var='musicsharing.allow_commenting_on'} {phrase var='musicsharing.songs'}
        </div>
        <div class="table_right">
           {phrase var='musicsharing.do_you_want_to_let_members_of_this_level_comment_on'} {phrase var='musicsharing.songs'}
            <div class="item_is_active_holder"> 
            
                <span class="js_item_active item_is_active"><input type="radio" name="val[can_post_comment_on_song]" value="1" {if $settings.can_post_comment_on_song eq 1 } {value type='radio' id='is_active' default='1' selected='true'}{/if}/> {phrase var='admincp.yes'}</span>
                <span class="js_item_active item_is_not_active"><input type="radio" name="val[can_post_comment_on_song]" value="0" {if $settings.can_post_comment_on_song eq 0 } {value type='radio' id='is_active' default='0' selected='true'}{/if}/> {phrase var='admincp.no'}</span>
            </div>
        </div>
          <div class="clear"></div>        
</div-->
	<input type="hidden" name="val[can_post_comment_on_song]" value="1"/>
<div class="table">   
        <div class="table_left">
            {required}{phrase var='musicsharing.maximum_allowed'} {phrase var='musicsharing.songs'}
        </div>
        <div class="table_right">
           {phrase var='musicsharing.maximum_number_of_songs_per_album_enter_a_number_between_1_and_999'}
            <div class="item_is_active_holder">
               <input type="text" value="{$settings.max_songs}" name="val[max_songs]" id="max_songs"/>
            </div>
        </div>
          <div class="clear"></div>        
</div>
<div class="table">   
        <div class="table_left">
            {required}{phrase var='musicsharing.maximum_allowed_filesize'}
        </div>
        <div class="table_right">
           {phrase var='musicsharing.enter_the_maximum_filesize_for_uploaded_files_in_kb_this_must_be_a_number_between_1_and_204800'}
            <div class="item_is_active_holder"> 
               <input type="text" value="{$settings.max_file_size_upload}" name="val[max_file_size_upload]" id="max_file_size_upload"/>
            </div>
        </div>
          <div class="clear"></div>        
</div>
<div class="table">   
        <div class="table_left">
            {required}{phrase var='musicsharing.maximum_allowed_storage'}
        </div>        
        <div class="table_right">
           {phrase var='musicsharing.how_much_storage_space_in_kb_should_each_user_have_to_store_their_files'}
            <div class="item_is_active_holder"> 
               <input type="text" value="{$settings.max_storage_size}" name="val[max_storage_size]" id="max_storage_size"/>
            </div>
        </div>
          <div class="clear"></div>        
</div>
<div class="table">   
        <div class="table_left">
            {required}{phrase var='musicsharing.create_new_playlist'}
        </div>
        <div class="table_right">
           {phrase var='musicsharing.maximum_numbers_of_allowed_playlist_the_field_must_contain_an_integer_between_1_and_999'}
            <div class="item_is_active_holder"> 
               <input type="text" value="{$settings.max_playlist_created}" name="val[max_playlist_created]" id="max_playlist_created"/>
            </div>
        </div>
          <div class="clear"></div>        
</div>
<div class="table">   
         <div class="table_left">
            {required}{phrase var='musicsharing.maximum_albums'}
        </div>
        <div class="table_right">
           {phrase var='musicsharing.maximum_numbers_of_allowed_album_the_field_must_contain_an_integer_between_1_and_999'}
            <div class="item_is_active_holder"> 
               <input type="text" value="{$settings.max_album_created}" name="val[max_album_created]" id="max_album_created"/>
            </div>
        </div>
          <div class="clear"></div>        
</div>
<div class="table">   
         <div class="table_left">
            {required}{phrase var='musicsharing.create_new_album'}
        </div>
        <div class="table_right">
            {phrase var='musicsharing.allow_users_to_create_albums'}
            <div class="item_is_active_holder">            
                <span class="js_item_active item_is_active"><input type="radio" name="val[can_create_album]" value="1" {if isset($settings.can_create_album) && $settings.can_create_album eq 1 } {value type='radio' id='is_active' default='1' selected='true'}{/if}/> {phrase var='admincp.yes'}</span>
                <span class="js_item_active item_is_not_active"><input type="radio" name="val[can_create_album]" value="0" {if !isset($settings.can_create_album) || $settings.can_create_album eq 0 } {value type='radio' id='is_active' default='0' selected='true'}{/if}/> {phrase var='admincp.no'}</span>
            </div>
        </div>
  <div class="clear"></div>        
</div>
<div class="table">   
         <div class="table_left">
            {required}{phrase var='musicsharing.music_downloads'}
        </div>
        <div class="table_right">
            {phrase var='musicsharing.allow_users_to_download'} {phrase var='musicsharing.songs'} ?
            <div class="item_is_active_holder"> 
            
                <span class="js_item_active item_is_active"><input type="radio" name="val[can_download_song]" value="1" {if $settings.can_download_song eq 1 } {value type='radio' id='is_active' default='1' selected='true'}{/if}/> {phrase var='admincp.yes'}</span>
                <span class="js_item_active item_is_not_active"><input type="radio" name="val[can_download_song]" value="0" {if $settings.can_download_song eq 0 } {value type='radio' id='is_active' default='0' selected='true'}{/if}/> {phrase var='admincp.no'}</span>
            </div>
        </div>
  <div class="clear"></div>        
</div>
