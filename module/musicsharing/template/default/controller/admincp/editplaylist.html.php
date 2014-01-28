<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');                
?> 

<div style="padding-top: 18px;"></div>
<div class="table_header">Edit Playlist</div>
<table cellpadding='0' cellspacing='0' width='100%'><tr><td class='page'>
{* SHOW RESULT MESSAGE *}
{if $result != 0}
  <div class='success'><img src='{$core_path}module/musicsharing/static/image/music/success.gif' border='0' class='icon'> {phrase var='musicsharing.your_changes_have_been_saved'}.</div>
{/if}

<br>

 <div class='group_row'>
<table cellpadding="0" cellspacing="5" width="100%">
<tr><td valign="top" width="50%">
    <form enctype="multipart/form-data" name="editplaylist" class="" action="{url link='current'}" method="post">                 
          <div class="table">
              <div class="table_left"><span class="required">*</span>{phrase var='musicsharing.playlist_name'}:</div>
              <div class="table_right"><input type="text" name="val[title]" id="title" value="{$playlist_info.title}"/> </div>
          </div>
          <div class="table">
              <div class="table_left">{phrase var='musicsharing.playlist_description'}:</div>
              <div class="table_right"><textarea id="description" name="val[description]" cols="45" rows="6">{$playlist_info.description}</textarea></div>
          </div>
           <div class="table_right">
           {if $playlist_info.search == 1}
              <input type="checkbox" name="search" id="search" value="1" checked="true"/>  
           {else}
               <input type="checkbox" name="search" id="search" value="1"/>  
             {/if} 
               {phrase var='musicsharing.show_this_playlist_in_search_results'}.
          </div>
           <!--<div class="table_clear">
               {if $playlist_info.is_download == 1}
            <input type="checkbox" name="is_download" id="is_download" value="1" checked="true"/>  
           {else}
               <input type="checkbox" name="is_download" id="is_download" value="1" />  
             {/if}  
              Allow to download this playlist.
          </div>-->
          {if $playlist_info.playlist_image != ""}
          <div class="table_right">
        <img src="{$core_path}file/pic/musicsharing/{$playlist_info.playlist_image}" width="100" height="60" />
        </div>
        {/if}
          <div class="table">
              <div class="table_left">{phrase var='musicsharing.playlist_image'}:</div>
              <div class="table_right">  <input id="playlist_image" type="file" name="playlist_image">  </div>
          </div>
          
          <div class="table_clear">
        <input type="submit" name="submit" value="{phrase var='musicsharing.save_change'}" class="button" />    
        </div>
</form>
        </td>
    </tr>
</table>


</td></tr></table>
         
           