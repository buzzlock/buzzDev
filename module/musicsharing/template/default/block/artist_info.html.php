<?php 

 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<div class="artist_info">
        <div class="artist_img">
			<a href="{url link= $aArtist.user_name}" title="{$aArtist.full_name|clean}">
				{img user=$aArtist suffix='_50_square' width=50 height=50}
			</a>
        </div>
    <div class="artist_info_block">
        <div style="padding-top:5px; font-weight: bold;"><a href="{url link= $aArtist.user_name}" title="{$aArtist.full_name}">{$aArtist.full_name|clean|shorten:18:"...":false}</a></div>
        <div style="margin-top:3px; font-size:8pt;">
              <a style="color: #8F8F8F;" href="{if !isset($aParentModule)}{url link='musicsharing.album.user_'.$aArtist.user_id.'.name_'.$aArtist.full_name|clean}{else}{url link='pages.'.$aParentModule.item_id.'.musicsharing.album.user_'.$aArtist.user_id.'.name_'.$aArtist.full_name|clean}{/if}"> {$aArtist.num_album} {if $aArtist.num_album > 1 } {phrase var="musicsharing.albums"} {else}{phrase var="musicsharing.album"}{/if}</a>
        </div>
    </div>
</div>
<div class="clear"></div>