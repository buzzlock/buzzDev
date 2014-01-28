<?php 

 
defined('PHPFOX') or exit('NO DICE!'); 

?>
{if !phpfox::isMobile()}
    <div class="artist-info">
            <div class="artist-img">
                <a href="{url link= $aArtist.user_name}" title="{$aArtist.full_name|clean}">
                    {img user=$aArtist suffix='_100_square' width=102 height=102}
                </a>
            </div>
        <div class="artist-info-block">
            <div>
                <a href="{url link=$aArtist.user_name}" title="{$aArtist.full_name}">
                    <strong>{$aArtist.full_name|clean|shorten:12:"...":false}</strong>
                </a>
            </div>
            <div style="font-size:8pt;">
                <a href="{if !isset($aParentModule)}{url link='musicsharing.album.user_'.$aArtist.user_id.'.name_'.$aArtist.full_name|clean}{else}{url link='pages.'.$aParentModule.item_id.'.musicsharing.album.user_'.$aArtist.user_id.'.name_'.$aArtist.full_name|clean}{/if}">
                    <span style="color: #8F8F8F; font-size: 10px;">
                        {$aArtist.total_album}  {if $aArtist.total_album > 1 } {phrase var="musicsharing.albums"} {else}{phrase var="musicsharing.album"}{/if}
                    </span>
                </a>
            </div>
        </div>
    </div>
{else}
    <ul class="artist-list-item">
        <li>
            {img user=$aArtist suffix='_50_square' width=50 height=50}
            <div class="artist-info-a">
                <a href="{url link= $aArtist.user_name}" title="{$aArtist.full_name}">{$aArtist.full_name|clean|shorten:20:"...":false}</a>
                <div class="extra_info">
                    <a href="{if !isset($aParentModule)}{url link='musicsharing.album.user_'.$aArtist.user_id.'.name_'.$aArtist.full_name|clean}{else}{url link='pages.'.$aParentModule.item_id.'.musicsharing.album.user_'.$aArtist.user_id.'.name_'.$aArtist.full_name|clean}{/if}">{$aArtist.total_album}&nbsp;{if $aArtist.total_album > 1}{phrase var="musicsharing.albums"}{else}{phrase var="musicsharing.album"}{/if}</a>
                    &nbsp;|&nbsp;
                    <a href="{if !isset($aParentModule)}{url link='musicsharing.song.user_'.$aArtist.user_id.'.name_'.$aArtist.full_name|clean}{else}{url link='pages.'.$aParentModule.item_id.'.musicsharing.song.user_'.$aArtist.user_id.'.name_'.$aArtist.full_name|clean}{/if}">{$aArtist.total_song}&nbsp;{if $aArtist.total_song > 1}{phrase var="musicsharing.songs"}{else}{phrase var="musicsharing.song"}{/if}</a>
                </div>
            </div>
        </li>
    </ul>
{/if}