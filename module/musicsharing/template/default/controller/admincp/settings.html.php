<?php
/**
 * [PHPFOX_HEADER]
 *
 * @copyright        [PHPFOX_COPYRIGHT]
 * @author          
 * @package          Module_MusicSharing
 * @version         
 */

defined('PHPFOX') or exit('NO DICE!');

?>

<form method="post" action="{url link='admincp.musicsharing.settings'}" id="admincp_musicsharing_form_message">
<input type="hidden" name="action" value="add"/>
    <div class="table_header">
        {phrase var='musicsharing.global_settings'}
    </div>
     <div class="table">
        <div class="table_left">
            {required}{phrase var='musicsharing.public_permissions'} :
        </div>
        <div class="table_right">
            {phrase var='musicsharing.select_whether_or_not_you_want_to_let_the_public_visitors_that_are_not_logged_in_to_view_the_follo'}.
            <div class="item_is_active_holder"> 
            
                <span class="js_item_active item_is_active"><input type="radio" name="val[is_public_permission]" value="1" {if $is_public_permission eq 1 } {value type='radio' id='is_active' default='1' selected='true'}{/if}/> {phrase var='admincp.yes'}</span>
                <span class="js_item_active item_is_not_active"><input type="radio" name="val[is_public_permission]" value="0" {if $is_public_permission eq 0 } {value type='radio' id='is_active' default='0' selected='true'}{/if}/> {phrase var='admincp.no'}</span>
            </div>
        </div>
        <div class="clear"></div>        
    </div>
    <div class="table">
        <div class="table_left">
            {phrase var='musicsharing.number_song_per_page'} :
        </div>
        <div class="table_right">
            <input type="text" name="val[number_song_per_page]" id="number_song_per_page" value="{$default_number_song}"/>
        </div>
        <div class="clear"></div>
    </div>
    <div class="table">
        <div class="table_left">
            {phrase var='musicsharing.number_song_per_page_widget'} :
        </div>
        <div class="table_right">
            <input type="text" name="val[number_song_per_page_widget]" id="number_song_per_page_widget" value="{$default_number_song_widget}"/>
        </div>
        <div class="clear"></div>
    </div>
    <div class="table">
        <div class="table_left">
            {phrase var='musicsharing.number_album_per_page'} :
        </div>
        <div class="table_right">
            <input type="text" name="val[number_album_per_page]" id="number_album_per_page" value="{$default_number_album}"/>
        </div>
        <div class="clear"></div>
    </div>
    <div class="table">
        <div class="table_left">
            {phrase var='musicsharing.number_playlist_per_page'} :
        </div>
        <div class="table_right">
            <input type="text" name="val[number_playlist_per_page]" id="number_playlist_per_page" value="{$default_number_playlist}"/>
        </div>
        <div class="clear"></div>
    </div>
    <div class="table">
        <div class="table_left">
            {phrase var='musicsharing.number_artist_per_page'} :
        </div>
        <div class="table_right">
            <input type="text" name="val[number_artist_per_page]" id="number_artist_per_page" value="{$default_number_artist}"/>
        </div>
        <div class="clear"></div>
    </div>
    <div class="table_clear">
        <input type="submit" value="{phrase var='musicsharing.save_changes'}" class="button" name="save_change_global_setings"/>
    </div>
</form>

<br />

<div class="table_header">
    {phrase var='musicsharing.user_group_settings'}
</div>
<p>{phrase var='musicsharing.these_settings_are_applied_on_a_per_member_level_basis_start_by_selecting_the_member_level_you_want'}.</p>
<form method="post" id="admincp_musicsharing_form_member_level_settings" action="{url link='admincp.musicsharing.settings'}" onsubmit="return checkValidate();">
     <div class="table">
        <div class="table_left">
            {phrase var='musicsharing.group_membe'}
        </div>
        <div class="table_right">
            <select id="select_group_member" name="val[select_group_member]" onchange="loadGroupSetting(this.value)">
            {foreach from=$group_members item=gr}
                <option value="{$gr.user_group_id}" {if $default_view_group eq $gr.user_group_id}selected{/if} > {$gr.title}</option>
            {/foreach}
            </select>
            <span id="loading"></span>
        </div>
        <div class="clear"></div>
        <div id="div_settings"></div>     
    </div>
    <div class="table_bottom">
        <input type="submit" value="{phrase var='musicsharing.save_changes'}" class="button" name ="save_change_group_setting" id="save_change_group_setting" onclick="return checkValidate();"/>
        
    </div>
    
</form>
<script type="text/javascript">
    {literal}
        function loadGroupSetting(value) 
        {
            $(document).ready(function(){
                $('#loading').html('Loading data ...');
                $('#div_settings').html('');
                $('#div_settings').ajaxCall('musicsharing.loadSettings','user_group_id=' + value);
            });
        }
    
        $Behavior.MusicSharingSettings = function() {
            loadGroupSetting(
    {/literal}
                {$default_view_group}
    {literal}
            );
        }
        
    function isNumber(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }

    function checkValidate()
    {
        var max_songs = $('#max_songs').val();
        var max_file_size_upload = $('#max_file_size_upload').val();
        var max_storage_size = $('#max_storage_size').val();
        var max_playlist_created = $('#max_playlist_created').val();
        var max_album_created = $('#max_album_created').val();
        if (!isNumber(max_songs) || max_songs<=0 || !(max_songs >=1 && max_songs <= 999)) {
         
          alert('The max song number is invalid');
          return false;
        }
        if (!isNumber(max_file_size_upload) || max_file_size_upload <= 0 ) {
          alert('The max file size upload  is invalid');
          return false;
        }
        if (!isNumber(max_storage_size) || max_storage_size <= 0) {
          alert('The max file size storage  is invalid');
          return false;
        }
        if (!isNumber(max_playlist_created) || max_playlist_created <= 0) {
          alert('The max playlist number is invalid');
          return false;
        }
         if (!isNumber(max_album_created) || max_album_created <= 0) {
          alert('The max album number  is invalid');
          return false;
        }
        return true;
    }
     {/literal}  
</script>
