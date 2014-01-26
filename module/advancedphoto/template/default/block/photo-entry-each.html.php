<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Photo
 * @version 		$Id: photo-entry.html.php 4532 2012-07-19 10:03:18Z Miguel_Espinoza $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<div class="{if $aPhoto.view_id == 1 && !isset($bIsInApproveMode)} row_moderate_image{/if} {if $aPhoto.is_sponsor} row_sponsored_image{/if}{if isset($sView) && $sView == 'featured'}{else}{if $aPhoto.is_featured} row_featured_image{/if}{/if} photo_row advancedphoto-view-photo" id="js_photo_id_{$aPhoto.photo_id}" >
	<div class="js_outer_photo_div js_mp_fix_holder photo_row_holder">
	
		<div class="photo_row_height image_hover_holder advancedphoto-photo">
			{if Phpfox::getParam('advancedphoto.auto_crop_photo')}
			<div class="photo_clip_holder_main">
			{/if}
				{if ($aPhoto.view_id == 1 && Phpfox::getUserParam('advancedphoto.can_approve_photos')) || ($aPhoto.user_id == Phpfox::getUserId() && (Phpfox::getUserParam('advancedphoto.can_edit_own_photo_album') || Phpfox::getUserParam('advancedphoto.can_edit_own_photo') || Phpfox::getUserParam('advancedphoto.can_delete_own_photo'))) || (Phpfox::getUserParam('advancedphoto.can_edit_other_photo_albums') || Phpfox::getUserParam('advancedphoto.can_edit_other_photo') || Phpfox::getUserParam('advancedphoto.can_delete_other_photos'))
				|| (defined('PHPFOX_IS_PAGES_VIEW') && Phpfox::getService('pages')->isAdmin('' . $aPage.page_id . ''))
				}
				<a href="#" class="image_hover_menu_link">{phrase var='advancedphoto.link'}</a>
				
				<div class="image_hover_menu">
					<ul>
				    {if ((Phpfox::getUserParam('advancedphoto.can_delete_own_photo') && $aPhoto.user_id == Phpfox::getUserId()) || Phpfox::getUserParam('advancedphoto.can_delete_other_photos'))
					|| (defined('PHPFOX_IS_PAGES_VIEW') && Phpfox::getService('pages')->isAdmin('' . $aPage.page_id . ''))
					}
					   <li class="item_delete"><a href="#" title="{phrase var='advancedphoto.delete_this_photo'}" onclick="if (confirm('{phrase var='advancedphoto.are_you_sure' phpfox_squote=true}')) $.ajaxCall('advancedphoto.deletePhoto', 'photo_id={$aPhoto.photo_id}'); return false;">{phrase var='advancedphoto.delete_photo'}</a></li>
				    {/if}	
					
					{if Phpfox::getUserParam('advancedphoto.can_sponsor_photo')}
						<li id="js_photo_sponsor_{$aPhoto.photo_id}">
							<a href="#" onclick="$.ajaxCall('advancedphoto.sponsor', 'photo_id={$aPhoto.photo_id}&type={if $aPhoto.is_sponsor == 1}0{else}1{/if}');return false;"> {if $aPhoto.is_sponsor == 1}{phrase var='advancedphoto.unsponsor_this_photo'}{else}{phrase var='advancedphoto.sponsor_this_photo'}{/if}</a>
						</li>
					{/if}	
					
					{if Phpfox::getUserParam('advancedphoto.can_feature_photo') && !$aPhoto.is_sponsor}
						<li id="js_photo_feature_{$aPhoto.photo_id}">
						{if $aPhoto.is_featured}
							<a href="#" title="{phrase var='advancedphoto.un_feature_this_photo'}" onclick="$.ajaxCall('advancedphoto.feature', 'photo_id={$aPhoto.photo_id}&amp;type=0', 'GET'); return false;">{phrase var='advancedphoto.un_feature'}</a>
						{else}
							<a href="#" title="{phrase var='advancedphoto.feature_this_photo'}" onclick="$.ajaxCall('advancedphoto.feature', 'photo_id={$aPhoto.photo_id}&amp;type=1', 'GET'); return false;">{phrase var='advancedphoto.feature'}</a>
						{/if}
						</li>
					{/if}						

				    {if $aPhoto.user_id == Phpfox::getUserId()}
					<li>
						<a href="#" title="Set this photo as your profile image." onclick="tb_show('', '', null, '{phrase var='advancedphoto.setting_this_photo_as_your_profile_picture_please_hold'}', true); $.ajaxCall('advancedphoto.makeProfilePicture', 'photo_id={$aPhoto.photo_id}', 'GET'); return false;">{phrase var='advancedphoto.make_profile_picture'}</a>
					</li>
					{if Phpfox::getUserParam('profile.can_change_cover_photo')}
					<li>
						<a href="#" title="{phrase var='user.set_this_photo_as_your_profile_cover_photo'}" onclick="$.ajaxCall('user.setCoverPhoto', 'photo_id={$aPhoto.photo_id}', 'GET'); return false;">{phrase var='user.set_as_cover_photo'}</a>
					</li>			
					{/if}
					{/if}					
					
				    {if (Phpfox::getUserParam('advancedphoto.can_edit_own_photo') && $aPhoto.user_id == Phpfox::getUserId()) || Phpfox::getUserParam('advancedphoto.can_edit_other_photo')}
					    <li><a href="#" title="{phrase var='advancedphoto.edit_this_photo'}"onclick="$Core.box('advancedphoto.editPhoto', 700, 'photo_id={$aPhoto.photo_id}&amp;inline=true'); $('#js_tag_photo').hide();return false;">{phrase var='advancedphoto.edit_photo'}</a></li>
				    {/if}

					{plugin call='advancedphoto.template_default_block_photo_entry_hover_end'}
					</ul>
				</div>
				{/if}
				{plugin call='advancedphoto.template_default_block_photo_entry_tool'}
				
				
			{if isset($sView) && $sView == 'featured'}
			{else}
			<div class="js_featured_photo row_featured_link"{if !$aPhoto.is_featured} style="display:none;"{/if}>
				{phrase var='advancedphoto.featured'}
			</div>					
			{/if}	
			<div class="js_sponsor_photo row_sponsored_link"{if !$aPhoto.is_sponsor} style="display:none;"{/if}>
				{phrase var='advancedphoto.sponsored'}
			</div>
			{if isset($sView) && $sView == 'pending'}
			{else}
			<div class="js_pending_photo row_pending_link"{if $aPhoto.view_id != '1'} style="display:none;"{/if}>
				{phrase var='advancedphoto.pending'}
			</div>
			{/if}
			
			{if Phpfox::getUserParam('advancedphoto.can_approve_photos') || Phpfox::getUserParam('advancedphoto.can_delete_other_photos')}
			<div class="video_moderate_link"><a href="#{$aPhoto.photo_id}" class="moderate_link" rel="photo">{phrase var='advancedphoto.moderate'}</a></div>				
			{/if}
			
			{if Phpfox::getParam('advancedphoto.auto_crop_photo')}
				<div class="photo_clip_holder_border">
					<a href="{$aPhoto.link}{if isset($iForceAlbumId)}albumid_{$iForceAlbumId}/{/if}" style="background:url('{img server_id=$aPhoto.server_id path='photo.url_photo' file=$aPhoto.destination suffix='_240' max_width=240 max_height=240 return_url=true}') no-repeat;" class="ynadvphoto_thickbox photo_holder_image photo_clip_holder {if Phpfox::getParam('advancedphoto.view_photos_in_theater_mode')} no_ajax_link {/if}" rel="{$aPhoto.photo_id}" title="{phrase var='advancedphoto.title_by_full_name' title=$aPhoto.title|clean full_name=$aPhoto.full_name|clean}">{$aPhoto.title|clean|shorten:45:'...'|split:20}</a>
				</div>			
			{else}
			{if ($aPhoto.mature == 0 || (($aPhoto.mature == 1 || $aPhoto.mature == 2) && Phpfox::getUserId() && Phpfox::getUserParam('advancedphoto.photo_mature_age_limit') <= Phpfox::getUserBy('age'))) || $aPhoto.user_id == Phpfox::getUserId()}
			<a href="{$aPhoto.link}{if isset($iForceAlbumId)}albumid_{$iForceAlbumId}/{/if}{if isset($sPhotoCategory)}category_{$sPhotoCategory}/{/if}" title="{phrase var='advancedphoto.title_by_full_name' title=$aPhoto.title|clean full_name=$aPhoto.full_name|clean}" class="ynadvphoto_thickbox photo_holder_image {if Phpfox::getParam('advancedphoto.view_photos_in_theater_mode')} no_ajax_link {/if}" rel="{$aPhoto.photo_id}">
				<span style="background:url('{img return_url=true server_id=$aPhoto.server_id path='photo.url_photo' file=$aPhoto.destination suffix='_150' max_width=150 max_height=150}') no-repeat center top;display:block;height:113px;text-indent:-99999px">
					{*img server_id=$aPhoto.server_id path='photo.url_photo' file=$aPhoto.destination suffix='_150' max_width=140 max_height=110 title=$aPhoto.title class='js_mp_fix_width photo_holder'*}
					{phrase var='advancedphoto.title_by_full_name' title=$aPhoto.title|clean full_name=$aPhoto.full_name|clean}
				</span>
			</a>
			{else}
			<a href="{$aPhoto.link}{if isset($iForceAlbumId)}albumid_{$iForceAlbumId}/{/if}"{if $aPhoto.mature == 1} onclick="tb_show('{phrase var='advancedphoto.warning' phpfox_squote=true}', $.ajaxBox('advancedphoto.warning', 'height=300&amp;width=350&amp;link={$aPhoto.link}')); return false;"{/if} class="no_ajax_link">{img theme='misc/no_access.png' alt=''}</a>
			{/if}
			{/if}
			
			{if Phpfox::getParam('advancedphoto.auto_crop_photo')}
			</div>
			{/if}
		</div>
		
		<div class="photo_row_info">			
			{if !isset($bIsInAlbumMode)}
			<div class="extra_info_link">
				{phrase var='advancedphoto.by_user_info' user_info=$aPhoto|user|shorten:30:'...'|split:20}
				{if !empty($aPhoto.album_name)}
				<div>{phrase var='advancedphoto.in'} <a href="{permalink module='advancedphoto.album' id=$aPhoto.album_id title=$aPhoto.album_name}" title="{$aPhoto.album_name|clean}">{if $aPhoto.album_profile_id > 0}{phrase var='advancedphoto.profile_pictures'}{else}{$aPhoto.album_name|clean|shorten:45:'...'|split:20}{/if}</a></div>
				{/if}
			</div>
			{/if}
			{if isset($sView) && $sView == 'top-rated'}
			<div class="p_2">
			{phrase var='advancedphoto.total_rating_out_of_5' total_rating=$aPhoto.total_rating|round}
			</div>
			{elseif isset($sView) && $sView == 'top-battle'}
			<div class="p_2">
			{phrase var='advancedphoto.total_battle_win_s' total_battle=$aPhoto.total_battle}
			</div>			
			{/if}
			{plugin call='advancedphoto.template_default_block_photo_entry_info'}
		</div>			
		{if isset($bIsInPhotosOfMyAlbum) && $bIsInPhotosOfMyAlbum}
			<div class="ynadvphoto_album_photo_drag_selector"> </div>
		{/if}
	</div>
</div>

