<?php


defined('PHPFOX') or exit('NO DICE!');

?>
<div class="artist-info">
        <div class="artist-img">
                <a href="{url link= $aArtist.user_name}" title="{$aArtist.full_name|clean}">
                    {img user=$aArtist suffix='_100_square' width=102 height=102}
                </a>
        </div>
    <div class="artist-info-block">
        <div style=""><a href="{url link= $aArtist.user_name}" title="{$aArtist.full_name}">{$aArtist.full_name|clean|shorten:15:"...":false}</a></div>
        <div style="font-size:8pt;">
              <a href="{if !isset($aParentModule)}{url link='musicstore.album.user_'.$aArtist.user_id.'.name_'.$aArtist.full_name|clean}{else}{url link='pages.'.$aParentModule.item_id.'.musicstore.album.user_'.$aArtist.user_id.'.name_'.$aArtist.full_name|clean}{/if}"> {$aArtist.num_album} album(s)</a>
        </div>
    </div>
</div>