

<form method="post" action="{url link='admincp.donation.addbuttonimage'}" onsubmit="return startProcess(true, false);" enctype="multipart/form-data">
		<div id="js_donation_block_photo_holder">
			<div class="table">
				<div class="table_left">
					{phrase var='donation.donation_default_image'}:
				</div>
				<div class="table_right">
					<div>
						<img src='{$sDefaultImagePath}'/>
					</div>
					<div style="height:20px;">
						<span style="float:right;"><input type="checkbox" name="default_use_as_default" id="default_use_as_default" {if $bIsUsingDefaultButtonImage} checked='true'{/if}/></span>
						<span style="float:right;margin-top:3px;margin-left:4px;">{phrase var='donation.use_this_image_as_default'}</span>
					</div> 	
				</div>
				</br>
			</div>
		</div>
		<div class="message" style="display:none;"></div>
		<div class="error_message" style="display:none;"></div>
		
		<input type='hidden' name='aVals[bIsAddForm]' value='1'/>
		<div id="js_donation_block_photo_holder">
			<div class="table">
				<div class="table_left">
					{phrase var='donation.donation_image'}:
				</div>
				<div class="table_right">
					{if $sAdminImagePath}
						<img src='{$sAdminImagePath}'/>
					{/if}
				</div>
				</br>
				<div class="table_right">
					
					
					<input type="file" name="image" class="js_uploader_files_input" size="30" >
					<div id="js_video_upload_image">
						<div id="js_progress_uploader"></div>
						<div class="extra_info">
							{phrase var='donation.you_can_upload_a_jpg_gif_or_png_file'}
							{if $iMaxFileSize !== null}
							{phrase var='donation.the_file_size_limit_is' iMaxFileSize_filesize=$iMaxFileSize_filesize}							
							{/if}						
						</div>
					</div>
					
					<div style="height:20px;">
						<span style="float:right;"><input type="checkbox" name="custom_use_as_default" id="custom_use_as_default" 
									{if !$bIsUsingDefaultButtonImage} checked='true'{/if}/></span>
						<span style="float:right;margin-top:3px;margin-left:4px;">{phrase var='donation.use_this_image_as_default'}</span>
					</div> 	
				</div>
			</div>
			
			<div id="js_submit_upload_image" class="table_clear">
				<input type="submit" value="{phrase var='donation.save'}" class="button" />
			</div>
		</div>		

	

</form>


{literal}
<script type="text/javascript" language="javascript">
    $Behavior.DonationAddImageButton = function() {
        $('input[name=default_use_as_default]').change(function() {
            var changedValue = !$('input[name=custom_use_as_default]').is(':checked');
            $('input[name=custom_use_as_default]').attr('checked', changedValue );
        });

        $('input[name=custom_use_as_default]').change(function() {
            $('input[name=default_use_as_default]').attr('checked', !$('input[name=default_use_as_default]').is(':checked'));
        });        
    }
</script>
{/literal}
		
		