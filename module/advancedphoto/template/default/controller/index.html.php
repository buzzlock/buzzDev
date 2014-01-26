<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Photo
 * @version 		$Id: index.html.php 4166 2012-05-15 06:44:59Z Raymond_Benc $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
{if isset($bIsUserProfile) && $bIsUserProfile}
<div class="menu">
	<ul id="advancedphoto_tab">
		<li class="active">
			<a href="{url link=$aUser.user_name}advancedphoto"><span>{phrase var='advancedphoto.photos'}</span></a> 
		</li>
		<li>
			<a href="{url link=$aUser.user_name}advancedphoto/albums"><span>{phrase var='advancedphoto.albums'}</span></a>	
		</li>
	</ul>
	<div class="clear"></div>
	<br/>
</div>

	{/if}

{if $sView == 'my' && (count($aPhotos) || $bIsUseTimelineInterface)}
	
		<div class="item_bar">
			<div class="item_bar_action_holder">				
				<a href="#" class="item_bar_action"><span>{phrase var='advancedphoto.actions'}</span></a>		
				<ul>
					<li><a href="{url link='advancedphoto' view='my' mode='edit'}">{phrase var='advancedphoto.mass_edit_photos'}</a></li>
				</ul>			
			</div>		
		</div>	    
{/if}
<div id="js_actual_photo_content">
	<div id="js_album_outer_content">
		{if count($aPhotos) || $bIsUseTimelineInterface}
		{if isset($bIsEditMode)}
		<form method="post" action="#" onsubmit="$('#js_photo_multi_edit_image').show(); $('#js_photo_multi_edit_submit').hide(); $(this).ajaxCall('advancedphoto.massUpdate'{if $bIsMassEditUpload}, 'is_photo_upload=1'{/if}); return false;">
			{foreach from=$aPhotos item=aForms}
				{template file='advancedphoto.block.edit-photo'}
			{/foreach}
			<div class="photo_table_clear">
				<div id="js_photo_multi_edit_image" style="display:none;">
					{img theme='ajax/add.gif'}
				</div>		
				<div id="js_photo_multi_edit_submit">
					<input type="submit" value="{phrase var='advancedphoto.update_photo_s'}" class="button" />
				</div>
			</div>
			{pager}
		</form>
		{else}
			{if $bIsUseTimelineInterface}
				{module name='advancedphoto.yntimelinephoto'}	
			{else}
				{template file='advancedphoto.block.photo-entry'}
			{/if}
		{if Phpfox::getUserParam('advancedphoto.can_approve_photos') || Phpfox::getUserParam('advancedphoto.can_delete_other_photos')}
		{moderation}
		{/if}
		{/if}
		{else}
		<div class="extra_info">
			{phrase var='advancedphoto.no_photos_found'}			
		</div>
		{/if}	
	</div>
</div>