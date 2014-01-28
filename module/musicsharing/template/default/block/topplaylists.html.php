<div class="js_newtopplaylist {if phpfox::isMobile()} m-playlist {/if}">
	<?php $i=0; ?>
	{foreach from=$aNewPlaylists key=iKey item=aPlaylist}
		{if $aPlaylist.num_track > 0}
		<?php $i++; ?>
			<div class="playlist_musicsharing"{*
				*}{if $isOdd && ($iCount - 1) === $iKey}{*
					*}style="border-bottom: none;"{*
				*}{/if}{*
			*}>
				<div class="image_left">
					<a class="" href="{*
						*}{if !isset($aParentModule)}{*
							*}{url link='musicsharing.listen.playlist_'.$aPlaylist.playlist_id}{*
						*}{else}{*
							*}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing.listen.playlist_'.$aPlaylist.playlist_id}{*
						*}{/if}{*
					*}" style="display: block; overflow: hidden;width: 70px; height: 56px;">
						{if $aPlaylist.playlist_image != ""}
                            {img server_id=$aPlaylist.server_id path='musicsharing.url_image' suffix='_112' file=$aPlaylist.playlist_image max_width='70' max_height='70'}
						{else}
							<img src="{$core_path}module/musicsharing/static/image/ex.jpg" style="max-width: 70px;max-height: 70px">
						{/if}
					</a>
				</div>
				<div class="content_right_info">
				   <ul>
					   <li> <a class="title_thongtin2" href="{*
							*}{if !isset($aParentModule)}{*
								*}{url link='musicsharing.listen.playlist_'.$aPlaylist.playlist_id}{*
							*}{else}{*
								*}{url link=$aParentModule.module_id.".".$aParentModule.item_id.'.musicsharing.listen.playlist_'.$aPlaylist.playlist_id}{*
							*}{/if}{*
						*}">{$aPlaylist.title} </a> </li>
                        {if !phpfox::isMobile()}
					   <li>{phrase var='musicsharing.uploader'}: <a href="{url link=$aPlaylist.user_name}">{$aPlaylist.full_name}</a> <span>|</span></li>
                       {/if}
					   <li>
                           {if !phpfox::isMobile()}
                                {phrase var='musicsharing.play_s'}: {$aPlaylist.play_count}
                           {else} 
								<p class="icon-play extra_info">{$aPlaylist.play_count}</p>
							{/if}
                       </li>
				   </ul>
                    <div class="clear"></div>
				</div>
			</div>
		{/if}
	{/foreach}
	<div class="clear"></div>
</div>