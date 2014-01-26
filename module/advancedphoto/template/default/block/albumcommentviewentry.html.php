<li>
	<a href="{$aPhoto.link}{if isset($iForceAlbumId)}albumid_{$iForceAlbumId}/{/if}{if isset($sPhotoCategory)}category_{$sPhotoCategory}/{/if}" title="{phrase var='advancedphoto.title_by_full_name' title=$aPhoto.title|clean full_name=$aPhoto.full_name|clean}" class="ynadvphoto_thickbox photo_holder_image js_photo" rel="{$aPhoto.photo_id}">
		{if !phpfox::isMobile()}
			{img server_id=$aPhoto.server_id path='photo.url_photo' file=$aPhoto.destination suffix='_500' max_width=300 max_height=200 }
		{else}
			{img server_id=$aPhoto.server_id path='photo.url_photo' file=$aPhoto.destination suffix='_240' max_width=240 max_height=200 }
		{/if}
	</a>


	<div id="js_album_description">
		{if $sJsPhotoTagContent}
		<div class="extra_info" >
			<b>{phrase var='advancedphoto.in_this_photo'}:</b> <span id="ynadvphoto_photo_in_this_photo"> {$sJsPhotoTagContent}</span>
		</div>
		{/if}
		{$aForms.description|clean}					
		<div {if $aForms.view_id != 0}style="display:none;" class="js_moderation_on"{/if}>
			{module name='advancedphoto.yncomment'}
		</div>
	</div>
</li>