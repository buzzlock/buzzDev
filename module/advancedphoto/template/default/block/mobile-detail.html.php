<div class="mobile-bg-img">
	<a href="{$aPhoto.link}{if isset($iForceAlbumId)}albumid_{$iForceAlbumId}/{/if}{if isset($sPhotoCategory)}category_{$sPhotoCategory}/{/if}">
	{img server_id=$aPhoto.server_id path='photo.url_photo' file=$aPhoto.destination suffix='_150' height=70 rel=$aPhoto.photo_id}
	</a>
</div>