<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Photo
 * @version 		$Id: album-entry.html.php 4537 2012-07-19 10:29:48Z Raymond_Benc $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<div class="t_center photo_row" id="js_photo_album_id_{$aAlbum.album_id}">
	<div class="js_outer_photo_div js_mp_fix_holder photo_row_holder">	
		<div class="photo_row_height image_hover_holder advancedphoto-album">
			{if Phpfox::getParam('advancedphoto.auto_crop_photo')}
			<div class="photo_clip_holder_main">
			{/if}		

				{if ($aAlbum.profile_id == '0' && ((Phpfox::getUserId() == $aAlbum.user_id && Phpfox::getUserParam('advancedphoto.can_delete_own_photo_album')) || Phpfox::getUserParam('advancedphoto.can_delete_other_photo_albums')))
					|| ($aAlbum.profile_id == '0' && Phpfox::getUserId() == $aAlbum.user_id)
					|| ((Phpfox::getUserId() == $aAlbum.user_id && Phpfox::getUserParam('advancedphoto.can_edit_own_photo_album')) || Phpfox::getUserParam('advancedphoto.can_edit_other_photo_albums'))
				}
				<a href="#" class="image_hover_menu_link">{phrase var='advancedphoto.link'}</a>				
				<div class="image_hover_menu">
					<ul>
						{if $aAlbum.profile_id == '0' && ((Phpfox::getUserId() == $aAlbum.user_id && Phpfox::getUserParam('advancedphoto.can_delete_own_photo_album')) || Phpfox::getUserParam('advancedphoto.can_delete_other_photo_albums'))}
						<li class="item_delete"><a href="{url link='advancedphoto.albums' delete=$aAlbum.album_id}" id="js_delete_this_album" class="sJsConfirm">{phrase var='advancedphoto.delete'}</a></li>
						{/if}					
						{if $aAlbum.profile_id == '0' && Phpfox::getUserId() == $aAlbum.user_id}
						<li><a href="{url link='advancedphoto.add' album=$aAlbum.album_id}">{phrase var='advancedphoto.upload_photo_s'}</a></li>
						{/if}					
						{if (Phpfox::getUserId() == $aAlbum.user_id && Phpfox::getUserParam('advancedphoto.can_edit_own_photo_album')) || Phpfox::getUserParam('advancedphoto.can_edit_other_photo_albums')}
						<li><a href="{url link='advancedphoto.edit-album' id=$aAlbum.album_id}" id="js_edit_this_album">{phrase var='advancedphoto.edit'}</a></li>
						{/if}
						{if Phpfox::getUserParam('advancedphoto.can_feature_album')}
							<li id="js_album_feature_{$aAlbum.album_id}">
							{if $aAlbum.yn_is_featured}
								<a href="#" title="{phrase var='advancedphoto.un_feature_this_album'}" onclick="$.ajaxCall('advancedphoto.ynalbum.feature', 'album_id={$aAlbum.album_id}&amp;type=0', 'GET'); return false;">{phrase var='advancedphoto.un_feature'}</a>
							{else}
								<a href="#" title="{phrase var='advancedphoto.feature_this_album'}" onclick="$.ajaxCall('advancedphoto.ynalbum.feature', 'album_id={$aAlbum.album_id}&amp;type=1', 'GET'); return false;">{phrase var='advancedphoto.feature'}</a>
							{/if}
							</li>
						{/if}		

					
					</ul>
				</div>
				<div class="js_featured_album row_featured_link"{if !$aAlbum.yn_is_featured} style="display:none;"{/if}>
					{phrase var='advancedphoto.featured'}
				</div>	
			
			{/if}

			{if Phpfox::getParam('advancedphoto.auto_crop_photo')}
				<div class="photo_clip_holder_border">
					<a href="{$aAlbum.link}" style="background:url('{if Phpfox::isMobile()}{img server_id=$aAlbum.server_id path='photo.url_photo' file=$aAlbum.destination suffix='_75' max_width=75 max_height=75 return_url=true} {else}{img server_id=$aAlbum.server_id path='photo.url_photo' file=$aAlbum.destination suffix='_240' max_width=240 max_height=240 return_url=true}{/if}') no-repeat;" class="photo_clip_holder">{$aAlbum.name|clean|shorten:45:'...'|split:20}</a>
				</div>			
			{else}			
				<a href="{$aAlbum.link}" title="{phrase var='advancedphoto.name_by_full_name' name=$aAlbum.name|clean full_name=$aAlbum.full_name|clean}" id="js_album_inner_title_link_{$aAlbum.album_id}" class="album-photo-cover">
						{if Phpfox::isMobile()}{img server_id=$aAlbum.server_id path='photo.url_photo' file=$aAlbum.destination suffix='_75' max_width=75 max_height=75 title=$aAlbum.name class='js_mp_fix_width advancedphoto-album-option'}
					{else}
						<span style="background:url('{img return_url=true server_id=$aAlbum.server_id path='photo.url_photo' file=$aAlbum.destination suffix='_150'}') no-repeat center top;">
							{*img server_id=$aAlbum.server_id path='photo.url_photo' file=$aAlbum.destination suffix='_150' max_width=150 max_height=150 title=$aAlbum.name class='js_mp_fix_width advancedphoto-album-option'*}
							{$aAlbum.name}
						</span>
					{/if}
						
				</a>
			{/if}
			{if Phpfox::getParam('advancedphoto.auto_crop_photo')}
		</div>
			{/if}			
	</div>
		
			<div class="photo_row_info photo_row_info_album" >
				<a href="{permalink module='advancedphoto.album' id=$aAlbum.album_id title=$aAlbum.name}" id="js_album_inner_title_{$aAlbum.album_id}" class="row_sub_link">{$aAlbum.name|clean|shorten:70:'...'|split:40}</a>
				{if !defined('PHPFOX_IS_USER_PROFILE')}
				<div class="extra_info">				
					{phrase var='advancedphoto.by_lowercase'} {$aAlbum|user:'':'':50|split:10}				
					{plugin call='advancedphoto.template_block_album_entry_extra_info'}
				</div>		
				{/if}
			</div>						
		
	</div>
	{if isset($bIsInMyAlbum) && $bIsInMyAlbum  && isset($bIsInMyAlbumEditMode) && $bIsInMyAlbumEditMode }
		<div class="ynadvphoto_drag_selector"> </div>
	{/if}
</div>			
{if is_int($phpfox.iteration.albums/3)}
<div class="clear"></div>
{/if}