{if $aAlbums}
{foreach from=$aAlbums item=aAlbum}
<div class="dragWrapper float-left gaid gaid_{$aAlbum.album_id}">
	<a ref="{$aAlbum.album_id}" class="show-photoalbums uiMediaThumb uiScrollableThumb uiMediaThumbLarge uiMediaThumbAlb uiMediaThumbAlbLarge">
		<span class="uiMediaThumbWrap">
			<i style="background-image: url({$aAlbum.photo_cover});"></i>
		</span>
	</a>
	<div class="clearfix pvs photoDetails">
		<div class="photoText">
			<a ref="{$aAlbum.album_id}" class="show-photoalbums" onclick="return false;">
				<strong>{$aAlbum.name|clean|shorten:22:'...'|split:22}</strong>
			</a>
			<div class="fsm fwn fcg">{$aAlbum.size} {phrase var='socialmediaimporter.photo_s'}</div>
		</div>
	</div>
	<div ref="{$aAlbum.album_id}" class="context" id="context_{$aAlbum.album_id}">
		<div class="checkbox-anchor">
			<a href="javascript:void(0);" class="moderate_link" alt="{$aAlbum.album_id}" title="{$aAlbum.name}" id="moderate_link_{$aAlbum.album_id}" rel="sphoto_album">Moderate</a>
		</div>		
		<div class="status">
			{if isset($aAlbum.is_imported) && $aAlbum.is_imported=='1'}
				<span style="color: #FF0000; font-weight: bold;">{phrase var='socialmediaimporter.imported'}</span>
			{else}
				{phrase var='socialmediaimporter.not_imported_yet'}
			{/if}
		</div>		
	</div>
</div>
{/foreach}
{elseif $iPage == 1}
<div class="message">{phrase var='socialmediaimporter.you_don_t_have_any_albums'}</div>
{/if}