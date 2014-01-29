{if $iCount > 0}
{foreach from=$aPhotos item=aPhoto}
<div class="dragWrapper float-left fb-photo-detail">
	<a ref="{$aPhoto.photo_id}" class="show-photos uiMediaThumb uiScrollableThumb uiMediaThumbLarge">
		<i style="background-image: url({$aPhoto.photo_thumb});"></i>
	</a>
	<div ref="{$aPhoto.photo_id}" class="context" id="context_{$aPhoto.photo_id}">
		<div class="checkbox-anchor">
			<a href="javascript:void(0);" class="moderate_link" alt="{$aPhoto.photo_id}" id="moderate_link_{$aPhoto.photo_id}" rel="sphoto_album">Moderate</a>
		</div>
		<div class="status">
			{if isset($aPhoto.is_imported) && $aPhoto.is_imported=='1'}
				<span style="color: #FF0000; font-weight: bold;">{phrase var='socialmediaimporter.imported'}</span>
			{else}
				{phrase var='socialmediaimporter.not_imported_yet'}
			{/if}			
		</div>
	</div>
</div>
{/foreach}
{else}
	<div class="message">{phrase var='socialmediaimporter.you_don_t_have_any_photos'  link='javascript:doRefresh();'}</div>
{/if}