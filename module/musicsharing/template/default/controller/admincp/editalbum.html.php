<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');                
?> 

{if $settings.can_edit_album eq 1}


<div style="padding-top: 18px;"></div>
<div class="table_header">Edit Album</div>
<table cellpadding='0' cellspacing='0' width='100%'><tr><td class='page'>
{* SHOW RESULT MESSAGE *}
{if $result != 0}
  <div class='success'><img src='{$core_path}module/musicsharing/static/image/music/success.gif' border='0' class='icon'> {phrase var='musicsharing.your_changes_have_been_saved'}.</div>
{/if}
        				fck {$core_path} + {$album_info.album_image}


<table cellpadding="0" cellspacing="5" width="100%">
<tr><td valign="top" width="50%">
        <form enctype="multipart/form-data" name="addalbum" class="" action="{url link='current'}" method="post" onsubmit="return checkValidate({$min_price_song});">
          <div class="table">
              <div class="table_left"><span class="required">*</span>{phrase var='musicsharing.album_name'}:</div>
              <div class="table_right"><input type="text" name="val[title]" id="title" value="{$album_info.title}"/> <span>Limit 50 characters</span></div>
          </div>
          {if $selling_settings.can_sell_song eq 1}
          <div class="table">
              <div class="table_left">{phrase var='musicsharing.album_price'}:</div>
              <div class="table_right">  <input id="price" type="text" name="val[price]" value="{$album_info.price}"></div>
          </div>
                    <div class="table_right">
              <div class="message">
                When you edit to upload songs to album, users can have them free if you do not add price to the songs.
              </div>
              </div>
          {/if}
          <div class="table">
              <div class="table_left">{phrase var='musicsharing.album_description'}:</div>
              <div class="table_right"><textarea id="description" name="val[description]" cols="45" rows="6">{$album_info.description}</textarea></div>
          </div>
           <div class="table">
             <div class="table_right">
           {if $album_info.search == 1}
              <input type="checkbox" name="search" id="search" value="1" checked="true"/>  
           {else}
               <input type="checkbox" name="search" id="search" value="1"/>  
             {/if} 
              {phrase var='musicsharing.show_this_album_in_search_results'}.
              </div>
          </div>
           <div class="table_right">
               {if $album_info.is_download == 1}
            <input type="checkbox" name="is_download" id="is_download" value="1" checked="true"/>  
           {else}
               <input type="checkbox" name="is_download" id="is_download" value="1" />  
             {/if}  
              {phrase var='musicsharing.allow_to_download_this_album'}.
          </div>
          {if $album_info.album_image != ""}
          <div class="table_right">
        <img src="{$core_path}file/pic/musicsharing/{$album_info.album_image}" width="100" height="60" />
		</div>
        {/if}
          <div class="table">
              <div class="table_left">{phrase var='musicsharing.album_image'}:</div>
              <div class="table_right">  <input id="album_image" type="file" name="album_image">  </div>
          </div>
      
          <div class="table_clear">
        <input type="submit" name="submit" value="Save Change" class="button" />    
        </div>
</form>
        </td>
    </tr>
</table>

</td></tr></table>

{else}         
    {phrase var='musicsharing.you_do_not_have_permission_to_edit_album'}.
{/if}

{literal}
<script type="text/javascript">

    function checkValidate(value)
    {
          if ( document.getElementById('price') == null)
       {
           return true;
       }
        var price = $('#price').val();
        if (!isNumber(price)) {

          alert('The price number is invalid!');
          return false;
        }
        else
        {
            if(price<value)
            {
                if(price!=0)
                {
                    alert('Album price is must larger than or equal to '+ value + '{/literal} {$currency}{literal}');
                    return false;
                }
            }
             var rtmp = roundNumber(price,2);
            if ( rtmp != price)
            {
                alert('Invalid format price. You can input the number with max 2 decimals') ;
                return false;
            }
        }
        return true;
    }
     function roundNumber(num, dec) {
        var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
        return result;
    }
    function isNumber(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
     }
</script>
{/literal}
           