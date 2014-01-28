<?php
/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');
?>

{template file='musicsharing.block.mexpect'}

{if $settings.max_playlist_created > $total_playlist} 
<!--h1>{phrase var='musicsharing.add_new_playlist'}</h1-->

<form enctype="multipart/form-data" name="addalbum" class="" action="{url link='current'}" method="post">                 
	<div id="js_custom_privacy_input_holder">
		
	</div>
	<div class="table">
        <div class="table_left"><span class="required">*</span>{phrase var='musicsharing.playlist_name'}:</div>
        <div class="table_right"><input type="text" name="val[title]" value="{if isset($title)}{$title}{/if}" id="title"/> </div>
    </div>
    <div class="table">
        <div class="table_left">{phrase var='musicsharing.playlist_description'}</div>
        <div class="table_right"><textarea id="description" name="val[description]" cols="45" rows="6">{if isset($description)}{$description}{/if}</textarea></div>
    </div>
	<input type="hidden" name="search" id="search" value="1"/>  {*phrase var='musicsharing.show_this_playlist_in_search_results'*}
    <!--<div class="table_clear">
       <input type="checkbox" name="download" id="download" value="1" checked="true"/>   Allow to download this playlist.
   </div>-->
    <div class="table">
        <div class="table_left">{phrase var='musicsharing.playlist_image'}:</div>
        <div class="table_right"><input id="playlist_image" type="file" name="playlist_image"></div>
    </div>

    {if phpFox::isModule('privacy') && phpFox::getUserParam('musicsharing.can_set_privacy_in_this_playlist')}
    <div class="table">
        <div class="table_left">
            {phrase var='musicsharing.privacy'}:
        </div>
        <div class="table_right">
            {module name='privacy.form' privacy_name='privacy' privacy_info=phpFox::getPhrase('musicsharing.control_who_can_see_this_playlist') default_privacy='musicsharing.default_privacy_setting'}
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
        <input type="submit" name="submit" value="{phrase var='musicsharing.save_playlist'}" class="button" /> 
    </div>
</form> 

{else}
<div style="color:red">
    {phrase var='musicsharing.you_have_reach_to_limit_for_number_of_playlists_please_contact_admin_to_get_more_information'}.
</div>
{/if}

