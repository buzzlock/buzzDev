<?php 
defined('PHPFOX') or exit('NO DICE!'); 

?>
{if !phpfox::isMobile()}
<ul class="action">
{foreach from=$aTopAlbums item=aAlbum}
{if $aAlbum.num_track > 0}
       <li> <a class="first" href="{*
			*}{if !isset($aParentModule)}{*
				*}{url link='musicsharing.listen.album_'.$aAlbum.album_id}{*
			*}{else}{*
				*}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing.listen.album_'.$aAlbum.album_id}{*
			*}{/if}{*
		*}">{$aAlbum.title} </a> </li>
  {/if}
  {/foreach}
</ul>
{else}
<ul class="music-mobile lof-main-wapper new-albums-mobile" style="padding-top:10px;">
		{foreach from=$aTopAlbums item=aAlbum name=anew} 
			<li>
				<a target="_parent" href="{*
							*}{if !isset($aParentModule)}{*
								*}{url link='musicsharing.listen' album=$aAlbum.album_id}{*
							*}{else}{*
								*}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing.listen' album=$aAlbum.album_id}{*
							*}{/if}{*
						*}">
					{if isset($aAlbum.album_image) && $aAlbum.album_image !=""}       
                        {img server_id=$aAlbum.server_id path='musicsharing.url_image' suffix='_115' file=$aAlbum.album_image max_width='75' max_height='75' title=$aAlbum.title}
					{else}
						<img src="{$sLink2}module/musicsharing/static/image/music.png" title="{$aAlbum.title|clean}" width="75">
					{/if}
					<div class="lof-main-item-desc">
						<h3 style="text-align: left;">							
							{$aAlbum.title|clean|shorten:15:"...":false}
						</h3>
						<div class="m-album-info extra_info">
							<p style="text-align: left;">{$aAlbum.full_name|clean|shorten:50:"...":false}</p>
							<p class="icon-play">{$aAlbum.play_count}</p>
						</div>
						
					</div>
				</a>
			</li> 
		 {/foreach}
		  </ul>  	
{/if}