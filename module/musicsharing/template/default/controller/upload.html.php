<?php ?>

{template file='musicsharing.block.mexpect'}
{if isset($iUploadNumber)}
    <input type="hidden" value="0" id="uploaded_number" name="uploaded_number">
    <ul style="display: none;" id="uploaded_song_msf">
        <li>&nbsp;</li>        
    </ul>
{/if}

<form method="post" action="{url link='musicsharing.up'}" id="js_msf_form" enctype="multipart/form-data" target="js_upload_frame" onsubmit="return startProcess(true, true);">
    <script language="javascript" type="text/javascript">
		$Behavior.initMusicSharingUpload = (function(){l}
            var $album = $("#album");
            var $ms_musicupload_wrapper = $("#ms-musicupload-wrapper");
			var base_url = "{url link="musicsharing.upload"}";
        
            $album.change(function(evt){l}
                var $this = $(this);
                var $this_val = $this.val();
				
            
                if($this_val != -1){l}
					window.location = base_url + "album_" + $this_val;
                {r}else{l}
					window.location = base_url;
                {r}
            {r});
        {r});
    </script>

    <div class="album-select">
        <label for="album">{phrase var="musicsharing.choose_album"}</label>
        <select name="album" id="album">
            <option value="-1">{phrase var="musicsharing.select_an_album"}...</option>
            {foreach from=$aAlbums  item=aAlbum}
            <option value="{$aAlbum.album_id}" {if $album_id eq $aAlbum.album_id}selected="selected"{/if}>{$aAlbum.title}</option>
            {/foreach}
        </select>
    </div>
    <div id="ms-musicupload-wrapper" class="{if $album_id eq 0}hidden{/if}">
        <div class="top-message">{phrase var='musicsharing.browse_for_music_files_on_your_computer_and_upload_them_to_your_album'}</div>  <br/>
        <div>{phrase var='musicsharing.you_may_upload_files_with_sizes_up_to'} <font color="red" class="max-file-size">{$settings.max_file_size_upload_mb} {phrase var='musicsharing.mb'} </font>.</div>
        <div>{phrase var='musicsharing.you_may_upload_files_of_the_following_types'} <font color="red" class="file-type">mp3</font>.</div>
        <div>{phrase var='musicsharing.you_may_upload'} <font color="red" class="num-song">{$rest_number_song} </font> {phrase var='musicsharing.songs_for_this_album'} </div>
        <div>{phrase var='musicsharing.total_used_space'} :<font color="red" class="total-space">{$total_space_used} {phrase var='musicsharing.mb'}</font> .</div>  <br/>
        {if $settings.max_file_size_upload > 0}
        <div id="js_upload_error_message"></div>
        <input type="hidden" name="val[method]" value="massuploader"/>
        <!--input type="hidden" name="album" value="{$album_id}"/-->
        <div>
            <input type="hidden" name="val[method]" value="massuploader"/>
        </div>
        <div class="table mass_uploader_table" style="border: none;">
            <div id="swf_msf_upload_button_holder">
                <div class="swf_upload_holder">
                    <div id="swf_msf_upload_button"></div>
                </div>

                <div class="swf_upload_text_holder">
                    <div class="swf_upload_progress"></div>
                    <div class="swf_upload_text">
                        {phrase var='musicsharing.select_file'}(s)
                    </div>
                </div>
            </div>

        </div>
        {/if}
    </div>
</form>