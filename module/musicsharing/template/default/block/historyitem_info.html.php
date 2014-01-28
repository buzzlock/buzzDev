<?php
defined('PHPFOX') or exit('NO DICE!');

?>
<tr id="download_list_id_{$index}">
  
    <td width="50%" style="padding:7px;border-bottom:1px solid #D9D9D9;border-right:1px solid #D9D9D9;">
        {if $iSong.album_title eq ""}<!-- Song Item-->
            <a href="{url link='musicsharing.listen.music_'.$iSong.song_id}"  class="title_thongtin2">{$iSong.title}</a>
            <div class="profile_blogentry_date" style="padding-top:5px;">
                Type: <span style="font-weight: bold;color:red">Song</span>
               {if $iSong.singer_id eq 0 and $iSong.singer_id eq NULL}{phrase var='musicsharing.uploaders'}{else}{phrase var='musicsharing.singer'}{/if}:
               {if $iSong.singer_title != ""}
                   <a href="{url link='musicsharing.song.singer_'.$iSong.singer_id}">{$iSong.singer_title}</a>
               {else}
                    {if $iSong.other_singer != ""}
                          <a href="{url link='musicsharing.song.wheresinger_'.$iSong.other_singer|base64_encode}">{$iSong.other_singer}</a>
                    {else} Not updated {/if}
               {/if}
            </div>
        {else} <!-- Album Item-->
            <a href="{url link='musicsharing.listen.album_'.$iSong.album_id}"  class="title_thongtin2">{$iSong.album_title}</a>
            <div class="profile_blogentry_date" style="padding-top:5px;">
                Type: <span style="font-weight: bold;color:red">Album</span>

            </div>
            <div id="album_item_download_{$iSong.album_id}" ></div>
        {/if}

    </td>

   <td  align="center" style="padding:7px;border-bottom:1px solid #D9D9D9;border-right:1px solid #D9D9D9">
        <div>
           
            {if $iSong.ext=="mp3"}<img src="{$core_path}module/musicsharing/static/image/music/icon_mp3.png" />
            {else}<img src="{$core_path}module/musicsharing/static/image/music/Album.png" />{/if}
            <br>{if $iSong.filesize == 0}{if $iSong.album_title eq "" }N/A{/if}{else}
             {$iSong.sizemb} Mb

            {/if}
        </div>
    </td>
    <td  align="center" style="padding:2px;border-bottom:1px solid #D9D9D9;">
        {$iSong.count}

    </td>
</tr>

