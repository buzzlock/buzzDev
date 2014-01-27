<div class="main_break">
	<div class="title_add_job">
	<div clsss="title_add_job_create">
		<h1>
			{if !$bIsEdit}
				{phrase var='jobposting.create_new_job'}
			{else}
				{phrase var='jobposting.edit_job'}
			{/if}
		</h1>
	</div>
	
	{if $bIsEdit}
		<div>
			<a class="page_section_menu_link" href="{url link='jobposting'}{if $bIsEdit}{$job_id}/{/if}">{phrase var='jobposting.view_job'}</a>
		</div>
		{/if}
		</div>
	 <div class="clear"></div>
	{$sCreateJs}
	<form method="post" class="ync_add_edit_form" action="{url link='jobposting.add'}{if $bIsEdit}{$job_id}/{/if}" id="ync_edit_jobposting_form" name='ync_edit_jobposting_form' enctype="multipart/form-data">

		<div><input type="hidden" name="val[attachment]" class="js_attachment" value="{value type='input' id='attachment'}" /></div>
		<div><input type="hidden" id="popup_packages" name="val[packages]" value"0"></div>
		<div><input type="hidden" id="popup_publish" name="val[publish]" value"0"></div>
		<div><input type="hidden" id="popup_paypal" name="val[paypal]" value"0"></div>
		<div><input type="hidden" id="popup_feature" name="val[feature]" value"0"></div>
        <div id="js_coupon_block_main" class="js_coupon_block page_section_menu_holder">

            <div class="table">
                <div class="table_left">
                    <label for="title">{required}{phrase var='jobposting.job_title'}: </label>
                </div>
                <div class="table_right">
                    <input type="text" class="" name="val[title]" value="{value type='input' id='title'}" id="title" size="60" />
                </div>
            </div>
  
            <div class="table">
                <div class="table_left">
                    {required}{phrase var='jobposting.desired_skills_experience'}:
                    
                </div>
                <div class="table_right">
                    {editor id='skills'}
                </div>
            </div>
            
               <div class="table">
                <div class="table_left">
                    <label for="description">{required}{phrase var='jobposting.job_description'}:</label>
                     {if Phpfox::isModule('attachment') && !Phpfox::isMobile()}
                    <div class="extra_info">
                        <div class="global_attachment">
                            <div class="global_attachment_header">
                            	<div class="global_attachment_manage">
									<a class="border_radius_4{if !isset($aForms.total_attachment)} is_not_active{/if}" href="#" onclick="$('.js_attachment_list').slideToggle(); return false;">{phrase var='attachment.manage_attachments'}</a>
								</div>
                                <ul class="global_attachment_list">
						            <li class="global_attachment_title">{phrase var='attachment.insert'}:</li>
									<li><a href="#" onclick="return $Core.shareInlineBox(this, '{$aAttachmentShare.id}', {if $aAttachmentShare.inline}true{else}false{/if}, 'attachment.add', 500, '&amp;category_id={$aAttachmentShare.type}&amp;attachment_custom=photo');" class="js_global_position_photo js_hover_title">{img theme='feed/photo.png' class='v_middle'}<span class="js_hover_info">{phrase var='attachment.insert_a_photo'}</span></a></li>
									<li><a href="#" onclick="return $Core.shareInlineBox(this, '{$aAttachmentShare.id}', {if $aAttachmentShare.inline}true{else}false{/if}, 'link.attach', 600, '&amp;category_id={$aAttachmentShare.type}');" class="js_hover_title">{img theme='feed/link.png' class='v_middle'}<span class="js_hover_info">{phrase var='attachment.attach_a_link'}</span></a></li>
									{if Phpfox::isModule('video') && Phpfox::getParam('video.allow_video_uploading')}
									<li><a href="#" onclick="return $Core.shareInlineBox(this, '{$aAttachmentShare.id}', {if $aAttachmentShare.inline}true{else}false{/if}, 'attachment.add', 500, '&amp;category_id={$aAttachmentShare.type}&amp;attachment_custom=video');" class="js_hover_title">{img theme='feed/video.png' class='v_middle'}<span class="js_hover_info">{phrase var='attachment.insert_a_video'}</span></a></li>
									{/if}
									{if !isset($bNoAttachaFile)}
									<li><a href="#" onclick="return $Core.shareInlineBox(this, '{$aAttachmentShare.id}', {if $aAttachmentShare.inline}true{else}false{/if}, 'attachment.add', 500, '&amp;category_id={$aAttachmentShare.type}');" class="js_hover_title">{img theme='misc/application_add.png' class='v_middle'}<span class="js_hover_info">{phrase var='attachment.attach_a_file'}</span></a></li>
									{/if}
									{if Phpfox::isModule('emoticon')}
									<li><a href="#" onclick="return $Core.shareInlineBox(this, '{$aAttachmentShare.id}', {if $aAttachmentShare.inline}true{else}false{/if}, 'emoticon.preview', 400, '&amp;editor_id=description');" class="js_hover_title">{img theme='editor/emoticon.png' class='v_middle'}<span class="js_hover_info">{phrase var='attachment.insert_emoticon'}</span></a></li>
									{/if}
                                </ul>
                                <div class="clear"></div>
                            </div>
                        </div>
                        
                        <div class="js_attachment_list"{if !isset($aForms.total_attachment)} style="display:none;"{/if}>
							<h3>{phrase var='attachment.attachments_display'}</h3>
							<div class="js_attachment_list_holder"></div>
							{if isset($aForms.total_attachment) && $aForms.total_attachment && isset($aAttachmentShare.edit_id)}
								{module name='attachment.list' sType=$aAttachmentShare.type iItemId=$aAttachmentShare.edit_id attachment_no_header=true}
							{else}
							<div class="extra_info t_center">
								{phrase var='attachment.no_attachments_available'}
							</div>
							{/if}
						</div>

                    </div>
                    {/if}
                </div>
                <div class="table_right">
                    {editor id='description'}
                </div>
            </div>

            <div class="clear"></div>
            <br/>

 			 <div class="table">
                <div class="table_left">
                    <label for="language_prefer">{phrase var='jobposting.language_preference'}: </label>
                </div>
                <div class="table_right">
                    <input type="text" class="" name="val[language_prefer]" value="{value type='input' id='language_prefer'}" id="language_prefer" size="60" />
                </div>
            </div>
            
              <div class="table">
                <div class="table_left">
                    <label for="education_prefer">{phrase var='jobposting.education_preference'}: </label>
                </div>
                <div class="table_right">
                    <input type="text" class="" name="val[education_prefer]" value="{value type='input' id='education_prefer'}" id="education_prefer" size="60" />
                </div>
            </div>
            
              <div class="table">
                <div class="table_left">
                    <label for="working_place">{phrase var='jobposting.working_place'}: </label>
                </div>
                <div class="table_right">
                    <input type="text" class="" name="val[working_place]" value="{value type='input' id='working_place'}" id="working_place" size="60" />
                </div>
            </div>
            
               <div class="table">
                <div class="table_left">
                    <label for="working_time">{phrase var='jobposting.time'}: </label>
                </div>
                <div class="table_right">
                    <input type="text" class="" name="val[working_time]" value="{value type='input' id='working_time'}" id="working_time" size="60" />
                </div>
            </div>

			   <div class="table">
                <div class="table_left">
                   {phrase var='jobposting.expire_on'}:
                </div>
                <div class="table_right">
                    <div class="ync_disable" style="position: relative; {if $bIsEdit} {if !$aForms.time_expire}  display: none; {/if} {/if}">
                        {select_date prefix='time_expire_' id='_time_expire' start_year='current_year' end_year='+10' field_separator=' / ' field_order='MDY' default_all=true}
                    </div>

                </div>
            </div>
            
              <div class="table">
                <div class="table_left">
                    {phrase var='jobposting.job_privacy'}: 
                </div>
                <div class="table_right">
                	
                    {module name='privacy.form' privacy_name='privacy' privacy_info='Control who can see your job' privacy_no_custom=true}
                </div>
            </div>
            
            <div class="table">
			<div class="table_left">
				{phrase var='jobposting.comment_privacy'}:
			</div>
			<div class="table_right">	
				{module name='privacy.form' privacy_name='privacy_comment' privacy_info='jobposting.control_who_can_comment_on_this_job' privacy_no_custom=true}
			</div>			
		</div>
		
            <div class="table_clear">
            	<input type="submit" value="{if $bIsEdit && $aForms.post_status==1}{phrase var='jobposting.update'}{else}{phrase var='jobposting.save_as_draft'}{/if}" class="button"/>
            	<input type="button" value="{phrase var='jobposting.publish'}" class="button {if $bIsEdit && $aForms.post_status==1}button_off{/if}" {if $bIsEdit && $aForms.post_status==1}disabled{/if} onclick="$Core.box('jobposting.popupPublishJob', '500', 'id=0'); return false;"/>
            </div>

            {if Phpfox::getParam('core.display_required')}
            <div class="table_clear">
                {required} {phrase var='core.required_fields'}
            </div>
            {/if}
            
            
        </div>
	</form>
</div>
