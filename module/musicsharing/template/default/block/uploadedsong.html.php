<?php
defined('PHPFOX') or exit('NO DICE!');
?>
<div class="uploadedsong-wrapper">
    {if count($uploadedSong) > 0}
    <input type ="hidden" name ="uploaded_number" id="uploaded_number" value="1"/>
    <ul id="uploaded_song_msf" class="">
        {foreach from=$uploadedSong key=iKey item=auSong}
        <li><span><?php echo sprintf("%d.", $this->_aVars['iKey'] + 1)?></span> <a class="first" href="{*
			*}{if !isset($aParentModule)}{*
				*}{url link='musicsharing.listen.music_'.$auSong.song_id}{*
			*}{else}{*
				*}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing.listen.music_'.$auSong.song_id}{*
			*}{/if}{*
		*}">{$auSong.title} </a> </li>
        {/foreach}
    </ul>
    {else}
    <input type ="hidden" name ="uploaded_number" id="uploaded_number" value="0"/>
    <ul id="uploaded_song_msf" class="action">{phrase var='musicsharing.there_are_no_songs_uploaded_yet'}</ul>
    {/if}
	<div class="padding-top-10">
		<input type="submit" onclick="window.location='{url link="musicsharing.myalbums"}';return false;" class="ddzbutton" style="font-weight: bold;font-family: lucida grande,tahoma,verdana,arial,sans-serif; font-size: 11px;" value="{phrase var='musicsharing.back_to_my_albums'}" id="" name="" />
	</div>
</div>