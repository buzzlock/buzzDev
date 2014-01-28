<?php 

defined('PHPFOX') or exit('NO DICE!'); 

?>
<td>
<div class="album_info" {if $index eq 0 }style=" border-top:1px solid #BFC7C8;"{/if}>
    <div class="album_info_img">
        <a href="{url link='musicsharing.listen.album_'.$aAlbum.album_id}">
        {if $aAlbum.album_image != ""}
            <img class="img130" src="{$core_path}file/pic/musicsharing/{$aAlbum.album_image}" style="height: 60px;width: 80px;"/>
        {else}
             <img class="img130" src="{$core_path}module/musicsharing/static/image/music/nophoto_album_thumb.png" style="height: 60px; width: 80px;"/>    
        {/if}
        </a>
    </div>
    <div class="album_description">
         <a class="title" href="{url link='musicsharing.listen.album_'.$aAlbum.album_id}">{$aAlbum.title|clean|split:50..}</a> 
                <!-- Insert cart shop -->
             {if phpFox::isUser()}
                 {if in_array($aAlbum.album_id, $hiddencartalbum) or ($aAlbum.price eq 0)}
                      <a id="album_id_cart_{$aAlbum.album_id}" href="javascript:alert('The album\'s already in your cartshop or in download list or it is not allowed to sell.\n Please check again')"> <img src="{$core_path}module/musicsharing/static/image/addtocart_ic_ds.jpg" /></a>
                 {else}
                 {if $selling_settings.can_buy_song eq 1}
                     <span id="album_id_cart_{$aAlbum.album_id}"><a onmouseover="javascript:show_price({$aAlbum.album_id},'album',{$aAlbum.price},'{$currency}')" onmouseout="javascript:hide_price('album',{$aAlbum.album_id},'{$currency}')" href="javascript:addtocart({$aAlbum.album_id},'album')"> <img src="{$core_path}module/musicsharing/static/image/addtocart_ic.jpg" /></a></span>
                 {else}
                 <a href="#" onclick='alert("You do not have permission to buy it !");return false;'><img src="{$core_path}module/musicsharing/static/image/addtocart_ic.jpg" /></a>
                 {/if}
                 {/if}
             {/if}
             <span class="p_4" style="color: red;font-weight: bold;clear: both;">
                <span id="album_price_id_{$aAlbum.album_id}"></span>
             </span>
             <!-- END-->
         <div class="author_play_track">
            <span><strong>{$aAlbum.full_name|clean}</strong></span> |
            <span>{phrase var='musicsharing.released'}: {$aAlbum.year}</span> |
            <span> <a href="{url link='musicsharing.listen.album_'.$aAlbum.album_id}">{$aAlbum.play_count} {phrase var='musicsharing.plays'}</a> </span> |
            <span> <a href="{url link='musicsharing.song.album_'.$aAlbum.album_id.'.title_'.$aAlbum.title}">{$aAlbum.num_track} {phrase var='musicsharing.tracks'}</a></span>
             
        </div>
        <div class="extra_info_des">
             {$aAlbum.description}
        </div>
            
         </div>
    </div>
</div> 
 <div class="album_end_info"></div>
</td>
