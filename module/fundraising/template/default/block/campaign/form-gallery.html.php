<?php
/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');
?>


<form method="post" class="ynfr_add_edit_form" action="{url link='current'}" id="ynfr_edit_campaign_gallery_form" onsubmit="" enctype="multipart/form-data">
<div id="js_fundraising_block_gallery" class="js_fundraising_block page_section_menu_holder" style="display:none;">
	<div id="js_fundraising_block_gallery_holder">
		{if $iMaxUpload > 0}
		<div class="table">
			<div class="table_left">
				{phrase var='fundraising.select_image_s'}:
			</div>
			<div class="table_right">
				<div id="js_fundraising_upload_image">
					<div id="js_progress_uploader"></div>
					<div class="extra_info">
						{phrase var='fundraising.you_can_upload_a_jpg_gif_or_png_file'}
						{if $iMaxFileSize !== null}
						<br />
						{phrase var='fundraising.the_file_size_limit_is_filesize_if_your_upload_does_not_work_try_uploading_a_smaller_picture' filesize=$iMaxFileSize}
						{/if}
						<br/>
						{phrase var='fundraising.maximum_photos_imaximum' iMaximum=$iMaxUpload}
					</div>
				</div>
			</div>
		</div>

	
		{else}
		<div class="error_message">{phrase var='fundraising.you_have_reached_your_upload_limit'}</div>
		{/if}
	</div>			
	{module name='fundraising.photos' iId=$aForms.campaign_id}
	{module name='fundraising.campaign.form-gallery-video' iCampaignId=$aForms.campaign_id}
		<div id="js_submit_upload_image" class="table_clear">
			<input type="submit" name="val[submit_gallery]" value="{phrase var='fundraising.save_gallery'}" class="button" />
			{if $bIsEdit && $aForms.is_draft == 1}
				<input type="submit" name="val[publish_video]" value="{phrase var='fundraising.publish'}" class="button"/>
			{/if}
		</div>
</div>
 </form>