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
  <form id="formpostsong" action="{if !isset($aParentModule)}{url link='musicsharing.albumsongs.album_'.$album.'.page_'.$page}{else}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing.albumsongs.album_'.$album.'.page_'.$page}{/if}" method="post">
    <div class="table">
        <div class="table_left">{phrase var='musicsharing.category'}:</div>
        <div class="table_right">
            <select name="songCat" id="songCat" style="width:180px">
                <option value="0" >{phrase var='musicsharing.other'}</option>
                {foreach from=$aCats key = key item=aCat}
                    <option value="{$aCat.cat_id}" {if $aCat.cat_id eq $song_info.cat_id} selected = "selected" {/if}>
                    {$aCat.title}
                    </option>
                {/foreach}
            </select>
        </div>
    </div>
     <div class="table">
          <div class="table_left"><span class="required">*</span>{phrase var='musicsharing.song_title'}:</div>
          <div class="table_right"><input style="width: 450px;" type="text" name="songTitle" id="songTitle" value="{$song_info.title}" /> </div>
    </div>
    <div class="table">
          <div class="table_left">{phrase var='musicsharing.singer'}:</div>
          <div class="table_right">
        <select name="songSinger" id="songSinger" style="width:180px" {if $song_info.singer_id==0} disabled="disabled" {/if} >
            <option value="0">&nbsp;</option>
            {foreach from=$aSingers key = key item=aSingerType}
                <option value = "0">{$aSingerType.title}</option>

                {foreach from=$aSingerType.singer item=aSinger}
                    <option value="{$aSinger.singer_id}" {if $aSinger.bDefault}selected = "selected"{/if}>--{$aSinger.title}</option>
                {/foreach}
            {/foreach}
        </select>
        <input type="checkbox"  name="check_other_singer" id="check_other_singer" onclick="showhideSinger()" {if $song_info.singer_id==0} checked="checked" {/if} />

               {phrase var='musicsharing.other'} {phrase var='musicsharing.singer'}
               <input type="text" id="songSingerName" name="songSingerName" style="width:172px;" value="{if $song_info.singer_id==0 || !$song_info.singer_id}{$song_info.other_singer}{/if}" {if $song_info.singer_id!=0} disabled="disabled" {/if}  />
            </div>
    </div>
    <div class="table">
          <div class="table_left">{phrase var="musicsharing.lyric"}:</div>
          <div class="table_right"><textarea  name="songLyric" id="songLyric" style="height:120px; width: 450px;">{$song_info.lyric}</textarea>  </div>
    </div>
        {if phpFox::getUserParam('musicsharing.can_post_on_musicsharing')}
		<input name="privacy" id="privacy" value="1" type="hidden" />
	{/if}
    <div class="table_clear">
      <input type="submit" name="submit" value="{phrase var='musicsharing.save_changes'}" class="button" onclick="if ($('#songTitle').val() != '') {left_curly} tb_remove(); {right_curly} {if isset($inMySong) && $inMySong == 'true'} $.ajaxCall('musicsharing.editSong_proc', $('#formpostsong').serialize()); return false;{/if}"/>
      <input type='hidden' name='task' value='editsong' />
      <input type='hidden' name='song_id' value='{$song_info.song_id}' />
      </div>
 </form>

