<?php 
/**
 * [PHPFOX_HEADER]
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
{literal}
<script type="text/javascript">
   function checkSignatureGoal()
   {
	var val = $('#signature_goal').val();
	if ( val.search(/^-?[0-9]+$/) != 0 || parseInt(val,10) < 0)
	{
		 bIsValid = false;
		 $('#core_js_petition_form_msg').message(oTranslations['petition.signature_goal_must_be_a_integer_number'], 'error');
		 $('#signature_goal').addClass('alert_input');
		 $('html, body').animate({ scrollTop: 0 }, 0);
		 return false;
	 }
	 else
	 {
		 return true;
	 }
	}
				
	function checkEmails()
	{
		var sEmails = $('#target_email').val();                                          
		if(sEmails.length == 0)
		 return true;                                          
		var aEmails = sEmails.split(',');
		
		if(aEmails.length == 0)
		{								
		 $('html, body').animate({scrollTop:0}, 0);
		 return false;
		}
		for (var i = 0; i < aEmails.length; i++)
		{
		 if ($.trim(aEmails[i]).search(/^[0-9a-zA-Z]([\-.\w]*[0-9a-zA-Z]?)*@([0-9a-zA-Z][\-\w]*[0-9a-zA-Z]\.)+[a-zA-Z]{2,}$/) == -1)
		 {
			bIsValid = false;
			$('#core_js_petition_form_msg').message(oTranslations['petition.provide_a_valid_email_address'], 'error');
			$('#target_email').addClass('alert_input');
			$('html, body').animate({scrollTop:0},0);
			return false;
		 }
		  
		}

		return true;
	} 
							
	function plugin_addFriendToSelectList()
	{
		$('#js_allow_list_input').show();
	}
	
	$Behavior.initPetitionForm = (function(){
		$('#signature_goal').keydown(function (e) {
                  if (e.altKey || e.ctrlKey) { 
			    e.preventDefault();         
			}
			else if (e.shiftKey && !(e.keyCode >= 35 && e.keyCode <= 40)){ 			    
				  e.preventDefault();     
			} else {
			    var n = e.keyCode;
			    if (!((n == 8)             
			    || (n == 46)               
			    || (n >= 35 && n <= 40)    
			    || (n >= 48 && n <= 57)    
			    || (n >= 96 && n <= 105))  
			    ) {
				  e.preventDefault();     
			    }
			}
		});   
	});
	
	$Behavior.setDescEditor = (function(){
	   $("a[rel='js_petition_block_detail']").bind("click", function(){
			Editor.sEditorId="description";
			if(typeof customTinyMCE_init !='undefined')
			{
				customTinyMCE_init("description");	
			}		
		});
	   $("a[rel='js_petition_block_letter']").bind("click", function(){
			Editor.sEditorId="letter";
			if(typeof customTinyMCE_init !='undefined')
			{
				customTinyMCE_init("letter");
			}
		});
		
		  if('{/literal}{$sTab}{literal}' == 'letter')
		  {		
				Editor.sEditorId="letter";
				if(typeof customTinyMCE_init !='undefined')
				{
					customTinyMCE_init("letter");
				}
			}
			else
			{
				Editor.sEditorId="description";
				if(typeof customTinyMCE_init !='undefined')
				{
					customTinyMCE_init("description");
				}
			}
     });
</script>
<style type="text/css">
	div.row_focus {
		background: none repeat scroll 0 0 #FEFBD9;
	 }
</style>
{/literal}
<div class="main_break">
	{$sCreateJs}	
	<form method="post" action="{url link='current'}" id="core_js_petition_form" onsubmit="return checkEmails() && Validation_core_js_petition_form();" enctype="multipart/form-data">
		<div id="js_custom_privacy_input_holder">
		{if $bIsEdit && empty($sModule)}
			{module name='privacy.build' privacy_item_id=$aForms.petition_id privacy_module_id='petition'}
		{/if}
		</div>
		
		<div><input type="hidden" name="val[attachment]" class="js_attachment" value="{value type='input' id='attachment'}" /></div>
		<div><input type="hidden" name="val[selected_categories]" id="js_selected_categories" value="{value type='input' id='selected_categories'}" /></div>
		<div><input type="hidden" name="val[is_approved]" value="{value type='input' id='is_approved'}" /></div>

		{if !empty($sModule)}
			<div><input type="hidden" name="module" value="{$sModule|htmlspecialchars}" /></div>
		{/if}
		{if !empty($iItem)}
			<div><input type="hidden" name="item" value="{$iItem|htmlspecialchars}" /></div>
		{/if}
		{if $bIsEdit}
			<div><input type="hidden" name="id" value="{$aForms.petition_id}" /></div>
		{/if}
		{plugin call='petition.template_controller_add_hidden_form'}
		
		<!--Begin Block detail-->
		
		<div id="js_petition_block_detail" class="js_petition_block page_section_menu_holder">
			{if $bIsEdit && $aForms.is_approved == '1'}
			<div class="table">
				<div class="table_left">
					{phrase var='petition.petition_status'}:
				</div>
				<div class="table_right">
					<select name="val[petition_status]">
						<option {if isset($aForms.petition_status) && $aForms.petition_status == 2}selected{/if} value="2">{phrase var='petition.on_going'}</option>
						<option {if isset($aForms.petition_status) && $aForms.petition_status == 3}selected{/if} value="3">{phrase var='petition.victory'}</option>
						<option {if isset($aForms.petition_status) && $aForms.petition_status == 1}selected{/if} value="1">{phrase var='petition.closed'}</option>					
					</select>			
				</div>			
			</div>
			{/if}
			{if empty($sModule)}
			<div class="table">
				<div class="table_left">
					{phrase var='petition.categories'}:
				</div>
				<div class="table_right">
					<select name="val[selected_categories]">
					{foreach from=$aCategories item=aCategory}				
					    <option {if isset($aForms.category_id) && $aForms.category_id == $aCategory.category_id}selected{/if} value="{$aCategory.category_id}">{$aCategory.name}</option>
					{/foreach}
					</select>			
				</div>			
			</div>
			{/if}
			<div class="table">
				<div class="table_left">
					<label for="target">{required}{phrase var='petition.target'}: {phrase var='petition.enter_the_name_of_an_individual_organization_who_you_want_to_petition'}</label>
				</div>
				<div class="table_right">
					<input type="text" name="val[target]" value="{value type='input' id='target'}" id="target" style="width:60%" />
				</div>			
			</div>		
	
			<div class="table">
				<div class="table_left">
					<label for="target_email">{phrase var='petition.target_emails'}: </label>
				</div>
				<div class="table_right">
					<input type="text" name="val[target_email]" value="{value type='input' id='target_email'}" id="target_email" style="width:60%" />
					<div class="extra_info">
						{phrase var='petition.separate_multiple_emails_with_a_comma'}
					</div>
				</div>			
			</div>			
			
			<div class="table">
				<div class="table_left">
					<label for="title">{required}{phrase var='petition.title'}: {phrase var='petition.what_do_you_want_them_to_do'}</label>
				</div>
				<div class="table_right">
					<input type="text" name="val[title]" value="{value type='input' id='title'}" id="title" style="width:60%" />
				</div>			
			</div>
			
			<div class="table">
				<div class="table_left">
					<label for="petition_goal">{required}{phrase var='petition.petition_goal'}: {phrase var='petition.what_do_you_actually_want_the_target_to_do'}</label>
				</div>
				<div class="table_right">
					<input type="text" name="val[petition_goal]" value="{value type='input' id='petition_goal'}" id="petition_goal" style="width:60%" />
				</div>			
			</div>
			
			<div class="table">
				<div class="table_left">
					<label for="short_description">{required}{phrase var='petition.short_description'}: {phrase var='petition.provide_a_brief_info_about_this_petition'}</label>
				</div>
				<div class="table_right">
					<textarea style="width:60%" rows="10" name="val[short_description]" class="js_edit_petition_form" style="height:70px;">{value id='short_description' type='textarea'}</textarea>						
				</div>			
			</div>
			{plugin call='petition.template_controller_add_textarea_start'}
			
			<div class="table">
				<div class="table_left">
					<label for="description">{phrase var='petition.main_description'}: {phrase var='petition.why_is_this_important'}</label>
					{if Phpfox::isModule('attachment') && !Phpfox::isMobile()}
					<div class="extra_info">
						<script type="text/javascript">$Behavior.loadAttachmentStaticFiles = function(){l}$Core.loadStaticFile('{jscript file='share.js' module='attachment'}');{r}</script>
						<div class="global_attachment">
							<div class="global_attachment_header">	
								<ul class="global_attachment_list">	
									<li class="global_attachment_title">{phrase var='attachment.insert'}:</li>
									<li><a href="#" onclick="return $Core.shareInlineBox(this, '{$aAttachmentShare.id}', {if $aAttachmentShare.inline}true{else}false{/if}, 'attachment.add', 500, '&amp;category_id={$aAttachmentShare.type}&amp;attachment_custom=photo');" class="js_global_position_photo js_hover_title">{img theme='feed/photo.png' class='v_middle'}<span class="js_hover_info">{phrase var='attachment.insert_a_photo'}</span></a></li>									
									{if Phpfox::isModule('emoticon')}
									<li><a href="#" onclick="return $Core.shareInlineBox(this, '{$aAttachmentShare.id}', {if $aAttachmentShare.inline}true{else}false{/if}, 'emoticon.preview', 400, '&amp;editor_id=' + Editor.getId());" class="js_hover_title">{img theme='editor/emoticon.png' class='v_middle'}<span class="js_hover_info">{phrase var='attachment.insert_emoticon'}</span></a></li>
									{/if}
								</ul>
								<div class="clear"></div>	
							</div>
						</div>
					</div>
					{/if}
				</div>
				<div class="table_right">
					{editor id='description'}
				</div>			
			</div>
			{plugin call='petition.template_controller_add_textarea_end'}
			<div class="table">
				<div class="table_left">
					<label for="signature_goal">{required}{phrase var='petition.signature_goal'}:</label>				
				</div>
				<div class="table_right">
					{if !$bIsEdit}
						<input type="text" name="val[signature_goal]" value="{$iDefaultSignature}" id="signature_goal" style="width:60%" />					
					{else}
						<input type="text" name="val[signature_goal]" value="{value type='input' id='signature_goal'}" id="signature_goal" style="width:60%" />					
					{/if}
				</div>			
			</div>
			{*
			<div class="table">
				<div class="table_left">
					{phrase var='petition.send_personal_thank_you_note_to_each_signer_of_this_petition_signature_goal'}:
				</div>
				<div class="table_right">
					<div class="item_is_active_holder">		
						<span class="js_item_active item_is_active"><input type="radio" name="val[is_send_thank]" value="1" class="checkbox" style="vertical-align:middle;"{value type='checkbox' id='is_send_thank' default='1' selected=true}/> {phrase var='petition.yes'}</span>
						<span class="js_item_active item_is_not_active"><input type="radio" name="val[is_send_thank]" value="0" class="checkbox" style="vertical-align:middle;"{value type='checkbox' id='is_send_thank' default='0'}/> {phrase var='petition.no'}</span>
					</div>
				</div>
			</div>
			*}
			<div class="table">
				<div class="table_left">
					{phrase var='petition.petition_end_date'}:
				</div>
				<div class="table_right">
					<div style="position: relative;">
						{select_date prefix='end_time_' id='_end_time' start_year='current_year' end_year='+1' field_separator=' / ' field_order='MDY' default_all=true}						
					</div>
					<div class="extra_info">
						{phrase var='petition.petition_end_date_is_just_available_from_current_date_to_the_final_date_of_next_year'}
					</div>
				</div>
			</div>	
			{if empty($sModule) && Phpfox::isModule('tag') && Phpfox::getUserParam('petition.can_add_tags_on_petitions')}{module name='tag.add' sType=petition}{/if}
			{if empty($sModule) && Phpfox::isModule('privacy') && Phpfox::getUserParam('petition.can_set_allow_list_on_petitions')}
			<div class="table">
				<div class="table_left">
					{phrase var='petition.privacy'}:
				</div>
				<div class="table_right">	
					{module name='privacy.form' privacy_name='privacy' privacy_info='petition.control_who_can_see_this_petition' default_privacy='0'}
				</div>			
			</div>
			{/if}
			
			{if empty($sModule) && Phpfox::isModule('comment') && Phpfox::isModule('privacy') && Phpfox::getUserParam('petition.can_control_comments_on_petitions')}
			<div class="table">
				<div class="table_left">
					{phrase var='petition.comment_privacy'}:
				</div>
				<div class="table_right">	
					{module name='privacy.form' privacy_name='privacy_comment' privacy_info='petition.control_who_can_comment_on_this_petition' privacy_no_custom=true}
				</div>			
			</div>
			{/if}			
			
			{if empty($sModule)  && Phpfox::isModule('privacy') && Phpfox::getUserParam('petition.can_control_sign_on_petitions')}
			<div class="table">
				<div class="table_left">
					{phrase var='petition.sign_privacy'}
				</div>
				<div class="table_right">	
					{module name='privacy.form' privacy_name='privacy_sign' privacy_info='petition.control_who_can_sign_on_this_petition' privacy_no_custom=true}
				</div>			
			</div>
			{/if}
			
			<div class="table_clear">
				<ul class="table_clear_button">
					{plugin call='petition.template_controller_add_submit_buttons'}
					<li><input type="submit" name="val[{if $bIsEdit}update{else}add{/if}]" value="{if $bIsEdit}{phrase var='petition.update'}{else}{phrase var='petition.create_petition'}{/if}" class="button" onclick="return checkSignatureGoal();" /></li>
				</ul>
				<div class="clear"></div>
			</div>
						
			{if Phpfox::getParam('core.display_required')}
			<div class="table_clear">
				{required} {phrase var='core.required_fields'}
			</div>
			{/if}

		</div>
		<!--End Block detail-->
		{if $bIsEdit}
		<!--Begin Block photos-->
		<div id="js_petition_block_photos" class="js_petition_block page_section_menu_holder" style="display:none;">
			<div id="js_petition_block_photos_holder">
				{if $iMaxUpload != 0}
				<div class="table">
					<div class="table_left">
						{phrase var='petition.select_image_s'}:
					</div>
					<div class="table_right">
						<div id="js_petition_upload_image">
							<div id="js_progress_uploader"></div>
							<div class="extra_info">
								{phrase var='petition.you_can_upload_a_jpg_gif_or_png_file'}
								{if $iMaxFileSize !== null}
								<br />
								{phrase var='petition.the_file_size_limit_is_filesize_if_your_upload_does_not_work_try_uploading_a_smaller_picture' filesize=$iMaxFileSize}
								{/if}
								<br/>
								{phrase var='petition.maximum_photos_imaximum' iMaximum=$iMaxUpload}
							</div>
						</div>
					</div>
				</div>
				
				<div id="js_submit_upload_image" class="table_clear">
					<input type="submit" name="val[submit_photo]" value="{phrase var='petition.upload_photo'}" class="button" />
				</div>
				{else}
				<div class="error_message">{phrase var='petition.you_have_reached_your_upload_limit'}</div>
				{/if}
			</div>			
			{module name='petition.photos' iId=$aForms.petition_id}
		</div>
		<!--End Block photos-->

		<!--Begin Block invite-->
		<div id="js_petition_block_invite" class="js_petition_block page_section_menu_holder" style="display:none;">			
			{if Phpfox::isModule('friend')}
			<div style="width:75%; float:left; position:relative;">				
				<h3 style="margin-top:0px; padding-top:0px;">{phrase var='petition.invite_friends'}</h3>
				<div style="height:370px;">			
					{if isset($aForms.petition_id)}
						{module name='friend.search' input='invite' hide=true friend_item_id=$aForms.petition_id friend_module_id='petition'}
					{/if}
				</div>
				{/if}
				<h3>{phrase var='petition.invite_people_via_email'}</h3>
				<div class="p_4">
					<textarea cols="40" rows="8" name="val[emails]" style="width:98%; height:60px;"></textarea>
					<div class="extra_info">
						{phrase var='petition.separate_multiple_emails_with_a_comma'}
					</div>
				</div>
				
				<h3>{phrase var='petition.add_a_personal_message'}</h3>
				<div class="p_4">
					<textarea cols="40" rows="8" name="val[personal_message]" style="width:98%; height:120px;">
						{$sFriendMessageTemplate}
					</textarea>					
				</div>				
				
				<div class="p_top_8">
					<input type="submit" name="val[submit_invite]"  value="{phrase var='petition.send_invitations'}" class="button" />
				</div>				
			</div>
			{if Phpfox::isModule('friend')}
			<div style="margin-left:77%; position:relative;">
				<div class="block">
					<div class="title">{phrase var='petition.new_guest_list'}</div>				
					<div class="content">
						<div class="label_flow" style="height:330px;">
							<div id="js_selected_friends"></div>
						</div>
					</div>
				</div>
			</div>		
			
			<div class="clear"></div>		
			{/if}
		</div>
		<!--End Block invite-->		
		
		<!--Begin Block letter-->
		<div id="js_petition_block_letter" class="js_petition_block page_section_menu_holder" style="display:none;">
			<div class="table">
				<div class="table_left">
					{phrase var='petition.subject'}:
				</div>
				<div class="table_right label_hover">
					<input type="text" name="val[letter_subject]" value="{value type='input' id='letter_subject'}" id="letter_subject" size="60" style="width: 80%;" />
				</div>			
			</div>
			<div class="table">
				<div class="table_left">
					{phrase var='petition.message'}:
                        </div>
				<div class="table_right label_hover">
					{editor id="letter"}                           
				</div>				
			</div>
               {if !empty($aForms.target_email)}
			<div class="table">
				<div class="table_left">
					{phrase var='petition.send_petition_letter_online'}
				</div>
				<div class="table_right">
					<div class="item_is_active_holder">
                                 <span class="js_item_active item_is_not_active"><input type="radio" name="val[is_send_online]" value="0" class="checkbox" style="vertical-align:middle;"{value type='checkbox' id='is_send_online' default='0' selected=true}/> {phrase var='petition.no'}</span>
					   <span class="js_item_active item_is_active"><input type="radio" name="val[is_send_online]" value="1" class="checkbox" style="vertical-align:middle;"{value type='checkbox' id='is_send_online' default='1'}/> {phrase var='petition.yes'}</span>						
					</div>
				</div>
			</div>
                  {/if}
			<div class="table_clear">
				<ul class="table_clear_button">
					<li><input type="submit" name="val[submit_letter]" value="{phrase var='petition.save'}" class="button"/></li>
				</ul>
				<div class="clear"></div>
			</div>
		</div>
		<!--End Block letter-->
		{/if}
	</form>

</div>
<!--P_Check-->
{if $bIsEdit && $sTab != ''}
{literal}
<script type="text/javascript">
    $Behavior.pageSectionMenuRequest = function() {
        if (!bIsFirstRun) {
            $Core.pageSectionMenuShow('#js_petition_block_{/literal}{$sTab}{literal}');
            if ($('#page_section_menu_form').length > 0) {
                $('#page_section_menu_form').val('js_petition_block_detail'); 
            }
            bIsFirstRun = true;
        }	
    }
</script>
{/literal}
{/if}
{if $bIsEdit}
<script type="text/javascript">
    $Behavior.setupInviteLayout = function() {l}
         $("#js_friend_search_content").append('<div class="clear" style="padding:5px 0px 10px 0px;"><input type="button" onclick="$(\'input.checkbox\').attr(\'checked\', \'checked\');" value="{phrase var='petition.select_all'}" /> <input type="button" onclick="$(\'input.checkbox\').removeAttr(\'checked\');" value="{phrase var='petition.un_select_all'}"/></div>');
         $("#js_friend_search_content").parent().parent().css('height','');
    {r}
</script>
{/if}
