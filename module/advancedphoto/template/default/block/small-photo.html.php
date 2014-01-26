<li>
	<a href="{$aPhoto.link}{if isset($iForceAlbumId)}albumid_{$iForceAlbumId}/{/if}{if isset($sPhotoCategory)}category_{$sPhotoCategory}/{/if}" title="{phrase var='advancedphoto.title_by_full_name' title=$aPhoto.title|clean full_name=$aPhoto.full_name|clean}" class="adv-thumb-photo ynadvphoto_thickbox photo_holder_image {if Phpfox::getParam('advancedphoto.view_photos_in_theater_mode')} no_ajax_link {/if}" rel="{$aPhoto.photo_id}" >
		<span style="background:url('{img id='js_photo_view_image' return_url=true server_id=$aPhoto.server_id path='photo.url_photo' file=$aPhoto.destination suffix='_150' max_width=150 max_height=150 title=$aPhoto.title time_stamp=true }') no-repeat center top;"> {phrase var='advancedphoto.title_by_full_name' title=$aPhoto.title|clean full_name=$aPhoto.full_name|clean}</span>
	</a>
	<p>{phrase var='advancedphoto.by'} {$aPhoto|user|shorten:30:'...'|split:20}</p>
</li>
{*
<li>
{if Phpfox::getParam('advancedphoto.auto_crop_photo')}
				<div class="photo_clip_holder_border">
					<a href="{$aPhoto.link}{if isset($iForceAlbumId)}albumid_{$iForceAlbumId}/{/if}" style="background:url('{img server_id=$aPhoto.server_id path='photo.url_photo' file=$aPhoto.destination suffix='_240' max_width=240 max_height=240 return_url=true}') no-repeat;" class="thickbox photo_holder_image photo_clip_holder" rel="{$aPhoto.photo_id}" title="{phrase var='advancedphoto.title_by_full_name' title=$aPhoto.title|clean full_name=$aPhoto.full_name|clean}">{$aPhoto.title|clean|shorten:45:'...'|split:20}</a>
	</div>			
{else}
{if ($aPhoto.mature == 0 || (($aPhoto.mature == 1 || $aPhoto.mature == 2) && Phpfox::getUserId() && Phpfox::getUserParam('advancedphoto.photo_mature_age_limit') <= Phpfox::getUserBy('age'))) || $aPhoto.user_id == Phpfox::getUserId()}
<a href="{$aPhoto.link}{if isset($iForceAlbumId)}albumid_{$iForceAlbumId}/{/if}{if isset($sPhotoCategory)}category_{$sPhotoCategory}/{/if}" title="{phrase var='advancedphoto.title_by_full_name' title=$aPhoto.title|clean full_name=$aPhoto.full_name|clean}" class="thickbox photo_holder_image" rel="{$aPhoto.photo_id}">
	{img server_id=$aPhoto.server_id path='photo.url_photo' file=$aPhoto.destination suffix='_150' max_width=140 max_height=110 title=$aPhoto.title class='js_mp_fix_width photo_holder'}
</a>
{else}
<a href="{$aPhoto.link}{if isset($iForceAlbumId)}albumid_{$iForceAlbumId}/{/if}"{if $aPhoto.mature == 1} onclick="tb_show('{phrase var='advancedphoto.warning' phpfox_squote=true}', $.ajaxBox('advancedphoto.warning', 'height=300&amp;width=350&amp;link={$aPhoto.link}')); return false;"{/if} class="no_ajax_link">{img theme='misc/no_access.png' alt=''}</a>
{/if}
{/if}

{if Phpfox::getParam('advancedphoto.auto_crop_photo')}
</div>
{/if}
</li>
*}