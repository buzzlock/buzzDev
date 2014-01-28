<?php 

 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<td>
<div id="js_album_{$aAlbum.album_id}" style="margin-bottom: 1px;" >
<div class="musicsharing_album_info">
    <div style="width: 180px;"><a href="{url link= $aArtist.user_name}">{$aArtist.full_name|clean}</a></div>
        <div style="margin-top:3px; font-size:8pt;">
              <a href="{url link='musicsharing.album.user_'.$aArtist.user_id.'.name_'.$aArtist.full_name|clean}"> {$aArtist.num_album} {if $aArtist.num_album > 1 } albums {else}album{/if}</a>
        </div>      
</div>
<div class="musicsharing_album_image" style="height: 75px;">
     {img user=$aArtist suffix='_75' max_width=75 max_height=75}    
</div>
</div> 
</td>            
