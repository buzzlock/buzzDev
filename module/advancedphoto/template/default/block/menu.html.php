<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Photo
 * @version 		$Id: menu.html.php 4370 2012-06-27 07:41:51Z Miguel_Espinoza $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
		{if (Phpfox::getUserParam('advancedphoto.can_edit_own_photo') && $aForms.user_id == Phpfox::getUserId()) || Phpfox::getUserParam('advancedphoto.can_edit_other_photo')}
		<li><a href="#" onclick="if ($Core.exists('.js_box_image_holder_full')) {l} js_box_remove($('.js_box_image_holder_full').find('.js_box_content')); {r} $Core.box('advancedphoto.editPhoto', 700, 'photo_id={$aForms.photo_id}'); $('#js_tag_photo').hide();return false;">{phrase var='advancedphoto.edit_this_photo'}</a></li>
		{/if}
		
		{if $aForms.user_id == Phpfox::getUserId()}
		<li>
			<a href="#" title="Set this photo as your profile image." onclick="if ($Core.exists('.js_box_image_holder_full')) {l} js_box_remove($('.js_box_image_holder_full').find('.js_box_content')); {r} tb_show('', '', null, '{phrase var='advancedphoto.setting_this_photo_as_your_profile_picture_please_hold'}', true); $.ajaxCall('advancedphoto.makeProfilePicture', 'photo_id={$aForms.photo_id}', 'GET'); return false;">{phrase var='advancedphoto.make_profile_picture'}</a>
		</li>
		{if Phpfox::getUserParam('profile.can_change_cover_photo')}
		<li>
			<a href="#" title="{phrase var='user.set_this_photo_as_your_profile_cover_photo'}" onclick="$.ajaxCall('user.setCoverPhoto', 'photo_id={$aForms.photo_id}', 'GET'); return false;">{phrase var='user.set_as_cover_photo'}</a>
		</li>			
		{/if}		
		{/if}	
		
		{if Phpfox::getUserParam('advancedphoto.can_feature_photo') && !$aForms.is_sponsor}
		    <li id="js_photo_feature_{$aForms.photo_id}">
		    {if $aForms.is_featured}
			    <a href="#" title="{phrase var='advancedphoto.un_feature_this_photo'}" onclick="$.ajaxCall('advancedphoto.feature', 'photo_id={$aForms.photo_id}&amp;type=0', 'GET'); return false;">{phrase var='advancedphoto.un_feature'}</a>
		    {else}
			    <a href="#" title="{phrase var='advancedphoto.feature_this_photo'}" onclick="$.ajaxCall('advancedphoto.feature', 'photo_id={$aForms.photo_id}&amp;type=1', 'GET'); return false;">{phrase var='advancedphoto.feature'}</a>
		    {/if}
		    </li>
		{/if}		

		{if Phpfox::getUserParam('advancedphoto.can_sponsor_photo') && !defined('PHPFOX_IS_GROUP_VIEW')}     
		<li id="js_sponsor_{$aForms.photo_id}" class="" style="{if $aForms.is_sponsor}display:none;{/if}">
			    <a href="#" onclick="$('#js_sponsor_{$aForms.photo_id}').hide();$('#js_unsponsor_{$aForms.photo_id}').show();$.ajaxCall('advancedphoto.sponsor','photo_id={$aForms.photo_id}&type=1'); return false;">
				{phrase var='advancedphoto.sponsor_this_photo'}
			    </a>
		</li>		    
		<li id="js_unsponsor_{$aForms.photo_id}" class="" style="{if $aForms.is_sponsor != 1}display:none;{/if}">
			    <a href="#" onclick="$('#js_sponsor_{$aForms.photo_id}').show();$('#js_unsponsor_{$aForms.photo_id}').hide();$.ajaxCall('advancedphoto.sponsor','photo_id={$aForms.photo_id}&type=0'); return false;">
				{phrase var='advancedphoto.unsponsor_this_photo'}
			    </a>
		</li>
		{elseif Phpfox::getUserParam('advancedphoto.can_purchase_sponsor')  && !defined('PHPFOX_IS_GROUP_VIEW')
		    && $aForms.user_id == Phpfox::getUserId()
		    && $aForms.is_sponsor != 1}
		    <li>
			<a href="{permalink module='ad.sponsor' id=$aForms.photo_id}section_photo/">
				{phrase var='advancedphoto.sponsor_this_photo'}
			</a>
		    </li>
		{/if}
		
		{if PHPFOX_IS_AJAX && isset($bIsTheater) && $bIsTheater && (Phpfox::getUserParam('advancedphoto.can_edit_own_photo') && $aForms.user_id == Phpfox::getUserId()) || Phpfox::getUserParam('advancedphoto.can_edit_other_photo')}
					<li>
						<a href="#" onclick="$('#photo_view_ajax_loader').show(); $('#menu').remove(); $('#noteform').hide(); $('#js_photo_view_image').imgAreaSelect({left_curly} hide: true {right_curly}); $('#js_photo_view_holder').hide(); $.ajaxCall('advancedphoto.rotate', 'photo_id={$aForms.photo_id}&amp;photo_cmd=right'); return false;">
							{phrase var='advancedphoto.rotate_right'}
						</a>
					</li>
					<li>
						<a href="#" onclick="$('#photo_view_ajax_loader').show(); $('#menu').remove(); $('#noteform').hide(); $('#js_photo_view_image').imgAreaSelect({left_curly} hide: true {right_curly}); $('#js_photo_view_holder').hide(); $.ajaxCall('advancedphoto.rotate', 'photo_id={$aForms.photo_id}&amp;photo_cmd=left'); return false;">		{phrase var='advancedphoto.rotate_left'}							
						</a>
					</li>		
		{/if}
		
		{plugin call='advancedphoto.template_block_menu'}
		
		{if (Phpfox::getUserParam('advancedphoto.can_delete_own_photo') && $aForms.user_id == Phpfox::getUserId()) || Phpfox::getUserParam('advancedphoto.can_delete_other_photos')}
		<li class="item_delete"><a href="{url link='advancedphoto' delete=$aForms.photo_id}" class="sJsConfirm">{phrase var='advancedphoto.delete_this_photo'}</a></li>
		{/if}		