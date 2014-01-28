{foreach from=$aNewAlbums item=aNewAlbum name=anew} 
    <li>
        <a target="_parent" href="{if !isset($aParentModule)}{url link='musicsharing.listen' album=$aNewAlbum.album_id}{else}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing.listen' album=$aNewAlbum.album_id}{/if}">
            {if isset($aNewAlbum.album_image) && $aNewAlbum.album_image !=""}  
                {img server_id=$aNewAlbum.server_id path='musicsharing.url_image' suffix='_115' file=$aNewAlbum.album_image max_width='75' max_height='75' title=$aNewAlbum.title}
            {else}
                <img src="{$sLink2}module/musicsharing/static/image/music.png" title="{$aNewAlbum.title|clean}" width="75">
            {/if}
            <div class="lof-main-item-desc">
                <h3 style="text-align: left;">{$aNewAlbum.title|clean|shorten:15:"...":false}</h3>
                <div class="m-album-info extra_info">
                    <p style="text-align: left;">{$aNewAlbum.full_name|clean|shorten:50:"...":false}</p>
                    <p class="icon-play">{$aNewAlbum.play_count}</p>
                </div>
            </div>
        </a>
    </li> 
{/foreach}
