<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');                
?> 

{template file='musicsharing.block.mexpect'}

<script type="text/javascript">
var limit_song_number_upload = {$rest_number_song};
var max_file_size_upload_mb = {$settings.max_file_size_upload_mb};
{literal}                                         
$(function(){
   $('#swfupload-control').swfupload({    {/literal}    
        upload_url: "{url link='current'}",
        file_post_name: 'uploadfile',
        file_size_limit : "{$settings.max_file_size_upload}",
        file_types : "*.mp3",
        file_types_description : "Song files",
        file_upload_limit :{$rest_number_song},
        flash_url : "{$core_path}module/musicsharing/static/swf/swfupload.swf",
        button_image_url : '{$core_path}module/musicsharing/static/image/wdp_buttons_upload_114x29.png',
        button_width : 114,
        button_height : 29,
        button_placeholder : $('#button')[0],
        debug: false {literal} 
    })
        .bind('fileQueued', function(event, file){
            var listitem='<li id="'+file.id+'" >'+
                'File: <em>'+file.name+'</em> ('+Math.round(file.size/1024)+' KB) <span class="progressvalue" ></span>'+
                '<div class="progressbar" ><div class="progress" ></div></div>'+
                '<p class="status" >Pending</p>'+
                '<span class="cancel" >&nbsp;</span>'+
                '</li>';
            $('#log').append(listitem);
            $('li#'+file.id+' .cancel').bind('click', function(){
                var swfu = $.swfupload.getInstance('#swfupload-control');
                swfu.cancelUpload(file.id);
                $('li#'+file.id).slideUp('fast');
            });
            $(this).swfupload('startUpload');
        })
        .bind('fileQueueError', function(event, file, errorCode, message){
			var listitem='';
                 listitem='<li id="'+file.id+'" class="fileQueueError">'+
                '<p> Size of the file ' + file.name + ' is greater than limit '+ max_file_size_upload_mb + ' MB </p>' +
                '</li>';

            $('#queuestatus_status').append(listitem);
           
        })
        .bind('fileDialogComplete', function(event, numFilesSelected, numFilesQueued){
            $('#queuestatus').text('Files Selected: '+numFilesSelected+' / Queued Files: '+numFilesQueued);
            if(numFilesSelected==0)
            {
                $('#queuestatus_status').text('');
            }
        })
        .bind('uploadStart', function(event, file){
            $('#log li#'+file.id).find('p.status').text('Uploading...');
            $('#log li#'+file.id).find('span.progressvalue').text('0%');
            $('#log li#'+file.id).find('span.cancel').hide();
        })
        .bind('uploadProgress', function(event, file, bytesLoaded){
            var percentage=Math.round((bytesLoaded/file.size)*100);
            $('#log li#'+file.id).find('div.progress').css('width', percentage+'%');
            $('#log li#'+file.id).find('span.progressvalue').text(percentage+'%');
        })
        .bind('uploadSuccess', function(event, file, serverData){
            var item=$('#log li#'+file.id);
            item.find('div.progress').css('width', '100%');
            item.find('span.progressvalue').text('100%');
            item.addClass('success').find('p.status').html('Complete!!! ');
        })
        .bind('uploadComplete', function(event, file){
            $(this).swfupload('startUpload');
        })
         .bind('fileDialogStart', function(){
             $('#queuestatus_status').text('');
             $('#log').text('');
        })
    
});    

</script>
{/literal}
<h1>{phrase var='musicsharing.upload_new_music'}</h1>
 {if $settings.can_create_album eq 1}
        {if $settings.max_songs > $album_info.num_track }
            {if $album_id  <= 0 }
                <div style="color:red">
                    {phrase var='musicsharing.invalid_album'}
                </div>
            {else}
            <div id="swfupload-control" style="padding-left: 10px;">
            <div style="font-size: 11pt;">{phrase var='musicsharing.browse_for_music_files_on_your_computer_and_upload_them_to_your_album'}</div>  <br/>
            <div>{phrase var='musicsharing.you_may_upload_files_with_sizes_up_to'} <font color="red" style="font-weight: bold;">{$settings.max_file_size_upload_mb} {phrase var='musicsharing.mb'} </font>.</div>
            <div>{phrase var='musicsharing.you_may_upload_files_of_the_following_types'} <font color="red" style="font-weight: bold;">mp3</font>.</div>
            <div>{phrase var='musicsharing.you_may_upload'} <font color="red" style="font-weight: bold;">{$rest_number_song} </font> {phrase var='musicsharing.songs_for_this_album'} </div>
            <div>{phrase var='musicsharing.total_used_space'} :<font color="red" style="font-weight: bold;">{$total_space_used} {phrase var='musicsharing.mb'}</font> .</div>  <br/>
            {if $settings.max_file_size_upload > 0}
                <input type="button" id="button" />
                
                <p id="queuestatus" style="color: red;" ></p>
                <ol id="queuestatus_status" style="color: red;" ></ol>
                <ol id="log"></ol>
            {/if}         
            </div>  
            <table cellpadding='0' cellspacing='3' width='150'>
                  <tr>
                      <td class='button' nowrap='nowrap'><img src='{$core_path}module/musicsharing/static/image/music/back16.gif' border='0' align="absmiddle" ><a href='{url link="musicsharing.albumsongs.album_".$album_id}'> {phrase var='musicsharing.back_to_current_album'}</a></td>
                      <td class='button' nowrap='nowrap'><img src='{$core_path}module/musicsharing/static/image/music/back16.gif' border='0' align="absmiddle" ><a href='{url link = "musicsharing.myalbums"}'> {phrase var='musicsharing.back_to_my_albums'}</a></td>
                  </tr>
            </table>
            {/if}

        {else}
            <div style="color: red;">
            {phrase var='musicsharing.you_have_reach_to_limit_for_number_of_songs_for_this_album'}.{phrase var='musicsharing.please_contact_admin_to_get_more_information'}.
            </div>
        {/if}
   {else}
    <div style="color: red;">
        {phrase var='musicsharing.you_do_not_have_permission_to_upload_songs'}
    </div>
   {/if}

          

      
  