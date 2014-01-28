<?php
defined('PHPFOX') or exit('NO DICE!');

?>
{literal}
  <script language="javascript1.2" type="text/javascript" >
    function showhideSinger() {
        element1=document.getElementById('songSingerName');
        element2=document.getElementById('songSinger');
        checkbox = document.getElementById('check_other_singer');
        if(checkbox.checked) {
            element2.value=0;
            element1.disabled="";
            element2.disabled="disabled";
        } else {
            element1.disabled="disabled";
            element1.value="";
            element2.disabled="";
        }
    }
</script>
{/literal}
<div style="padding-top: 18px;"></div>
<div class="table_header">Edit Song</div>


{* SHOW RESULT MESSAGE *}
{if $result != 0}
  <div class='success'><img src='{$core_path}module/musicsharing/static/image/music/success.gif' border='0' class='icon'> {phrase var='musicsharing.your_changes_have_been_saved'}.</div>
{/if}
<form action="{url link='current'}" method="post" onsubmit="return checkValidate({$min_price_song});">
    <div class="table">
          <div class="table_left">Category:</div>
          <div class="table_right">
          <select name="songCat" id="songCat" style="width:180px">
                    <option value="0" >Other</option>
                    {foreach from=$aCats key = key item=aCat}
                        <option value="{$aCat.cat_id}" {if $aCat.cat_id eq $song_info.cat_id} selected = "selected" {/if}>
                        {$aCat.title}
                        </option>
                    {/foreach}
                    </select>
              {if $selling_settings.can_sell_song  eq 1}
                  <span style="margin-left: 5px">Price ({$currency})</span>
                  <input type="text" style="width: 172px;margin-left: 21px;" value="{$song_info.price}" name="price" id="price"/>
              {/if}
          </div>
    </div>
     <div class="table">
          <div class="table_left"><span class="required">*</span>Song Title:</div>
          <div class="table_right"><input style="width: 450px;" type="text" name="songTitle" id="songTitle" value="{$song_info.title}" /> </div>
    </div>
    <div class="table">
          <div class="table_left">{phrase var='musicsharing.singer'}:</div>
          <div class="table_right">
          <select name="songSinger" id="songSinger" style="width:180px" {if $song_info.singer_id==0} disabled="disabled" {/if} >
                                    <option value="0">&nbsp;</option>
                                     {foreach from=$aSingers key = key item=aSingerType}
                                          <option value = "0">{$aSingerType.info.title}</option>
                                          {foreach from=$aSingerType.singer item = iSinger}
                                          <option value = "{$iSinger.singer_id}" {if $iSinger.singer_id == $song_info.singer_id}selected = "selected"{/if}>--{$iSinger.title}</option>
                                          {/foreach}
                                      {/foreach}
                                 </select>

                                 <input type="checkbox"  name="check_other_singer" id="check_other_singer" onclick="showhideSinger()" />

               Other singer
               <input type="text" id="songSingerName" name="songSingerName" style="width:172px;" value="{if $song_info.singer_id==0 || !$song_info.singer_id}{$song_info.other_singer}{/if}" {if $song_info.singer_id!=0} disabled="disabled" {/if}  />
            </div>
    </div>
    <div class="table">
          <div class="table_left">Lyric:</div>
          <div class="table_right"><textarea  name="songLyric" id="songLyric" style="height:120px; width: 450px;">{$song_info.lyric}</textarea>  </div>
    </div>
    <div class="table_clear">
      <input type="submit" name="submit" value="Save change" class="button" onclick=""/>
      <input type='hidden' name='task' value='editsong' />
      <input type='hidden' name='song_id' value='{$song_info.song_id}' />
      </div>
 </form>
 {literal}
<script type="text/javascript">

    function checkValidate(value)
    {
       if ( document.getElementById('songTitle') == null || $('#songTitle').val() == "")
       {
           alert('Please insert the title name');
           return false;
       }
       if ( document.getElementById('price') == null)
       {
           return true;
       }
        var price = $('#price').val();
        if (!isNumber(price)) {

          alert('The price number is invalid');
          return false;
        }
        else
        {
            if(price<value)
            {
                if(price!=0)
                {
                    alert('Song price is must larger than or equal to '+ value + '{/literal} {$currency}{literal}');
                    return false;
                }
                else
                {
                    alert('Your song allow free download!');
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

    function isNumber(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
     }
     function roundNumber(num, dec) {
        var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
        return result;
    }
     showhideSinger();
</script>
  {/literal}
