<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond_Benc
 * @package 		Phpfox
 * @version 		$Id: add.html.php 4504 2012-07-11 15:08:44Z Raymond_Benc $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
{if false && Phpfox::isMobile()}
<div class="extra_info">
	{phrase var='advancedphoto.photos_unfortunately_cannot_be_uploaded_via_mobile_devices_at_this_moment'}
</div>
{else}
<div id="js_upload_error_message"></div>

<div id="js_photo_form_holder">
	<form method="post" action="{url link='advancedphoto.frame'}" id="js_photo_form" enctype="multipart/form-data" target="js_upload_frame" onsubmit="return startProcess(true, true);">
		
	{if $sModule}
		<div><input type="hidden" name="val[callback_module]" value="{$sModule}" /></div>
	{/if}
	{if $iItem}
		<div><input type="hidden" name="val[callback_item_id]" value="{$iItem}" /></div>
		<div><input type="hidden" name="val[group_id]" value="{$iItem}" /></div>
		<div><input type="hidden" name="val[parent_user_id]" value="{$iItem}" /></div>
	{/if}		
		
		{plugin call='advancedphoto.template_controller_upload_form'}
		{if Phpfox::getUserParam('advancedphoto.can_create_photo_album')}
			<div class="table" id="album_table">
				<div class="table_left">
					{phrase var='advancedphoto.photo_album'}
				</div>
				<div class="table_right_text">
					<span id="js_photo_albums"{if !count($aAlbums)} style="display:none;"{/if}>
						<select name="val[album_id]" id="js_photo_album_select" style="width:200px;" onchange="if (empty(this.value)) {l} $('#js_photo_privacy_holder').slideDown(); {r} else {l} $('#js_photo_privacy_holder').slideUp(); {r}">
							<option value="">{phrase var='advancedphoto.select_an_album'}:</option>
								{foreach from=$aAlbums item=aAlbum}
									<option value="{$aAlbum.album_id}"{if $iAlbumId == $aAlbum.album_id} selected="selected"{/if}>{$aAlbum.name|clean}</option>
								{/foreach}
						</select>
					</span>&nbsp;(<a href="#" class="no_ajax_link" onclick="$Core.box('advancedphoto.newAlbum', 500, 'module={$sModule}&amp;item={$iItem}'); return false;">{phrase var='advancedphoto.create_a_new_photo_album'}</a>)
				</div>
			</div>		
		{/if}		
		
		{if !$sModule && Phpfox::getParam('advancedphoto.allow_photo_category_selection') && Phpfox::getService('advancedphoto.category')->hasCategories()}
		<div class="table">
			<div class="table_left">
				<label for="category">{phrase var='advancedphoto.category'}:</label>
			</div>
			<div class="table_right">
				{module name='advancedphoto.drop-down'}
			</div>
		</div>		
		{/if}
		
		<div id="js_photo_privacy_holder" {if $iAlbumId} style="display:none;"{/if}>
			{if $sModule}
			<div><input type="hidden" id="privacy" name="val[privacy]" value="0" /></div>
			<div><input type="hidden" id="privacy_comment" name="val[privacy_comment]" value="0" /></div>
			{else}
				{if Phpfox::isModule('privacy')}
					<div class="table">
						<div class="table_left">
							{phrase var='advancedphoto.photo_s_privacy'}:
						</div>
						<div class="table_right">	
							{module name='privacy.form' privacy_name='privacy' privacy_info='advancedphoto.control_who_can_see_these_photo_s' default_privacy='advancedphoto.default_privacy_setting'}
						</div>			
					</div>
					<div class="table">
						<div class="table_left">
							{phrase var='advancedphoto.comment_privacy'}:
						</div>
						<div class="table_right">	
							{module name='privacy.form' privacy_name='privacy_comment' privacy_info='advancedphoto.control_who_can_comment_on_these_photo_s' privacy_no_custom=true}
						</div>			
					</div>		
				{/if}
			{/if}
		</div>		
		{if isset($sMethod) && $sMethod == 'massuploader'}
			<div><input type="hidden" name="val[method]" value="massuploader" /></div>
			<div class="table mass_uploader_table">
				<div id="swf_photo_upload_button_holder">
					<div class="swf_upload_holder">
						<div id="swf_photo_upload_button"></div>
					</div>
					
					<div class="swf_upload_text_holder">
						<div class="swf_upload_progress"></div>
						<div class="swf_upload_text">
							{phrase var='advancedphoto.select_photo_s'}
						</div>
					</div>				
				</div>
				<div class="extra_info">
					{phrase var='advancedphoto.you_can_upload_a_jpg_gif_or_png_file'}
					{if $iMaxFileSize !== null}
						<br />
						{phrase var='advancedphoto.the_file_size_limit_is_file_size_if_your_upload_does_not_work_try_uploading_a_smaller_picture' file_size=$iMaxFileSize|filesize}
					{/if}	
				</div>			
			</div>
			<div class="mass_uploader_link">{phrase var='advancedphoto.upload_problems_try_the_basic_uploader' link=$sMethodUrl}</div>	
		{else}				
			<div class="table">
				<div class="table_left">
					{phrase var='advancedphoto.select_photo_s'}:
				</div>
				<div class="table_right">
					<div id="js_photo_upload_input"></div>		
					
					<div class="extra_info">
						{phrase var='advancedphoto.you_can_upload_a_jpg_gif_or_png_file'}
						{if $iMaxFileSize !== null}
						<br />
						{phrase var='advancedphoto.the_file_size_limit_is_file_size_if_your_upload_does_not_work_try_uploading_a_smaller_picture' file_size=$iMaxFileSize|filesize}
						{/if}				
					</div>
				</div>
			</div>
			
			{if isset($bRawFileInput) && $bRawFileInput}
				<input type="button" name="Filedata" id="Filedata" value="Choose photo">
			{else}		
			<div class="table_clear">
				<input type="submit" value="{phrase var='advancedphoto.upload'}" class="button" />
			</div>		
			{/if}			
		{/if}		
		
	</form>
</div>
{/if}

<script type="text/javascript">

{literal}
$Behavior.swfUploadLoader = function()
{
	if($('#swf_photo_upload_button_holder').length > 0)
	{
		if (typeof bDoNotLoad != 'undefined' && bDoNotLoad == true)
		{

		}
		else
		{
			aFunction();
		}
	}

};


{/literal}
</script>