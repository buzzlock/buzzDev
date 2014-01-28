<?php
/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');
?> 

<form enctype="multipart/form-data" name="editsinger" class="" action="{url link='current'}" method="post">                 
    <div class="table_header">
        {phrase var='musicsharing.singer'} {phrase var='musicsharing.details'}
    </div>
    <div class="table">
        <div class="table_left"><span class="required">*</span>{phrase var='musicsharing.singer_name'}:</div>
        <div class="table_right"><input type="text" name="val[title]" id="title" value="{$singer_info.title}"/> </div>
    </div>
    <div class="table">
        <div class="table_left">{phrase var='musicsharing.singer'} {phrase var='musicsharing.type'}:</div>
        <div class="table_right">
            <select name="songSingerType" id="songSingerType" style="width:180px" >
                {foreach from=$aSingerTypes key = key item=aSingerType}         
                <option value = "{$aSingerType.singertype_id}" {if $aSingerType.singertype_id == $singer_info.singer_type} selected = "selected"{/if}>{$aSingerType.title}</option>
                {/foreach}
            </select>
        </div>
    </div>
    <div class="table">
        <div class="table_left">{phrase var='musicsharing.singer'} {phrase var='musicsharing.image'}:</div>
        <div class="table_right">
            <input id="singer_image" {if $singer_info.singer_image != ''} style="display: none;" {/if} type="file" name="singer_image">
            <span class="musicsharing-singer-image" {if $singer_info.singer_image == ''} style="display: none;" {/if}>
                {$sImage}
                <br />
                <a href="#" onclick="$Core.YouNet_Singer.deleteImage({$singer_info.singer_id}); return false;">
                    {phrase var='musicsharing.click_here_to_delete_this_image_and_upload_a_new_one_in_its_place'}
                </a>
            <span>
        </div>
    </div>

    <div class="table_clear">
        <input type="submit" name="submit" value="{phrase var='user.save_changes'}" class="button" />
    </div>
</form> 
