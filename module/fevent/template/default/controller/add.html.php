<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');
/**
 * 
 * 
 * @copyright       [YOUNET_COPYRIGHT]
 * @author          YouNet Company
 * @package         YouNet_Event
 */
?>
{$sCreateJs}
<input type="hidden" id="required_custom_fields"/>
<form method="post" action="{url link='current'}" enctype="multipart/form-data" onsubmit="return startProcess(custom_js_event_form(), false);" id="js_event_form">
<input type="hidden" name="val[attachment]" class="js_attachment" value="{value type='input' id='attachment'}" />
{if !empty($sModule)}
	<div><input type="hidden" name="module" value="{$sModule|htmlspecialchars}" /></div>
{/if}
{if !empty($iItem)}
	<div><input type="hidden" name="item" value="{$iItem|htmlspecialchars}" /></div>
{/if}
{if $bIsEdit}
	<div><input type="hidden" name="id" value="{if isset($aForms.event_id)}{$aForms.event_id}{else}0{/if}" /></div>
{/if}
	<div id="js_event_block_detail" class="js_event_block page_section_menu_holder">
	    
		<div class="table">
			<div class="table_left">
				<label for="category">{phrase var='fevent.category'}:</label>
			</div>
			<div class="table_right" id="categories">
				{$sCategories}
			</div>
		</div>
		<div class="separate"></div>
		
        <div id="ajax_custom_fields">
        {if $bIsEdit && isset($aCustomFields)}
            {module name="fevent.custom" aCustomFields=$aCustomFields}
        {/if}
        </div>
	
		<div class="table">
			<div class="table_left">
			{required}<label for="title">{phrase var='fevent.what_are_you_planning'}:</label>
			</div>
			<div class="table_right">
				<input type="text" name="val[title]" value="{value type='input' id='title'}" id="title" size="40" maxlength="100" />
			</div>
		</div>		
		
		<div class="table">
			<div class="table_left">
				<label for="description">{phrase var='fevent.description'}:</label>
			</div>
			<div class="table_right">
				{editor id='description' rows='6'}
			</div>
		</div>			
		<input type="hidden" id="bIsEdit" value="{$bIsEdit}"/>
		<div class="table">
			<div class="table_left">
				{phrase var='fevent.start_time'}:
			</div>
			<div class="table_right">
				<div style="position: relative;">
					{select_date prefix='start_' id='_start' start_year='' end_year='+100' field_separator=' / ' field_order='MDY' default_all=true add_time=true start_hour='+1' time_separator='fevent.time_separator'}				
				</div>
				{if !$bIsEdit}
				<div class="extra_info" id="extra_info_date">
					<a href="#" onclick="$(this).parent().hide(); $('#bIsEdit').val(1);$('#js_event_add_end_time').show(); return false;">{phrase var='fevent.add_end_time'}</a>
				</div>
				{/if}
			</div>
		</div>	
		
		<div class="table" id="js_event_add_end_time"{if !$bIsEdit || (isset($aEvent.isrepeat) && $aEvent.isrepeat!=-1)} style="display:none;"{/if}>
			<div class="table_left">
				{phrase var='fevent.end_time'}:
			</div>
			<div class="table_right">
				<div style="position: relative;">
				{select_date prefix='end_' id='_end' start_year='' end_year='+100' field_separator=' / ' field_order='MDY' default_all=true add_time=true start_hour='+4' time_separator='fevent.time_separator'}
				</div>
			</div>
		</div>		

		<div class="table">
			<div class="table_left">
				<input type="checkbox" {if isset($aEvent.isrepeat) && $aEvent.isrepeat!=-1}checked{/if} onclick="showrepeat(1)" id="cbrepeat"/> {phrase var='fevent.repeat'}: <span id="chooserepeat" >{if !isset($aEvent.isrepeat) || $aEvent.isrepeat==-1}...{else}{$content_repeat}{/if}</span><span style="padding-left:3px;"><a href="javascript:void(0)" onclick="showrepeat(2)"><span id="editrepeat">{if isset($aEvent.isrepeat) && $aEvent.isrepeat>=0}{phrase var='fevent.edit'}{/if}</span></a></span>
				<input type="hidden" value="{if isset($aEvent.isrepeat)}{$aEvent.isrepeat}{else}-1{/if}" id="txtrepeat" name="val[txtrepeat]"/>
				<input type="hidden" value="{$until}" name="val[daterepeat]" id="daterepeat"/>
			</div>
			<div class="table_right">
				
			</div>
		</div>
		
		<div class="table">
			<div class="table_left">
			{required}<label for="location">{phrase var='fevent.location_venue'}:</label>
			</div>
			<div class="table_right">
				<input type="text" name="val[location]" value="{value type='input' id='location'}" id="location" size="40" maxlength="200" />
				{if !$bIsEdit}
				<div class="extra_info">
					<a href="#" onclick="$(this).parent().hide(); $('#js_event_add_country').show(); return false;">{phrase var='fevent.add_address_city_zip_country'}</a>
				</div>
				{/if}				
			</div>
		</div>
		
		<div id="js_event_add_country"{if !$bIsEdit} style="display:none;"{/if}>	
			 
			<div class="table">
				<div class="table_left">
					<label for="country_iso">{phrase var='fevent.country'}:</label>
				</div>
				<div class="table_right">
					{select_location}
					{module name='core.country-child'}
				</div>
			</div>				 
			 
			<div class="table">
				<div class="table_left">
					<label for="street_address">{phrase var='fevent.address'}</label>
				</div>
				<div class="table_right">
					<input type="text" name="val[address]" value="{value type='input' id='address'}" id="address" size="30" maxlength="200" />
				</div>
			</div>			 			 
				
			<div class="table">
				<div class="table_left">
					<label for="city">{phrase var='fevent.city'}:</label>
				</div>
				<div class="table_right">
					<input type="text" name="val[city]" value="{value type='input' id='city'}" id="city" size="20" maxlength="200" />
				</div>
			</div>		
			
			<div class="table">
				<div class="table_left">
					<label for="postal_code">{phrase var='fevent.zip_postal_code'}:</label>
				</div>
				<div class="table_right">
					<input type="text" name="val[postal_code]" value="{value type='input' id='postal_code'}" id="postal_code" size="10" maxlength="20" />
				</div>
			</div>	
			
			<div class="table">
				<div class="table_left">
					<label for="range_value">{phrase var='fevent.range'}:</label>
				</div>
				<div class="table_right">
					<input type="text" name="val[range_value]" value="{value type='input' id='range_value'}" id="range_value" size="10" maxlength="20" />
					<select name="val[range_type]">
						<option value="0" {if isset($aEvent.range_type) && $aEvent.range_type==0}selected{/if}>{phrase var='fevent.miles'}</option>
						<option value="1" {if isset($aEvent.range_type) && $aEvent.range_type==1}selected{/if}>{phrase var='fevent.km'}</option>
					</select>
				</div>
			</div>		
			 
		</div>
		
		{if $bCanAddMap}
        <div class="table">
            <div class="table_left">
            <input id="refresh_map" type="button" value="{phrase var='fevent.refresh_map'}" onclick="inputToMap();"/>
            </div>
            <div class="table_right">
                <input type="hidden" name="val[gmap][latitude]" value="{value type='input' id='input_gmap_latitude'}" id="input_gmap_latitude" />
                <input type="hidden" name="val[gmap][longitude]" value="{value type='input' id='input_gmap_longitude'}" id="input_gmap_longitude" />
                <div id="mapHolder"></div>
            </div>
        </div>
        <div class="clear"></div>
        <br/>
        {/if}
        
		{if empty($sModule) && Phpfox::isModule('privacy')}
		<div class="table">
			<div class="table_left">
				{phrase var='fevent.event_privacy'}:
			</div>
			<div class="table_right">	
				{module name='privacy.form' privacy_name='privacy' privacy_info='fevent.control_who_can_see_this_event' privacy_no_custom=true default_privacy='fevent.display_on_profile'}
			</div>			
		</div>
		<div class="table">
			<div class="table_left">
				{phrase var='fevent.share_privacy'}:
			</div>
			<div class="table_right">	
				{module name='privacy.form' privacy_name='privacy_comment' privacy_info='fevent.control_who_can_share_on_this_event' privacy_no_custom=true}
			</div>			
		</div>
		{/if}
		<div class="table_clear">
		{if $bIsEdit}
			<input type="submit" name="val[update_detail]" value="{phrase var='fevent.update'}" class="button" />
		{else}	
			<input type="submit" name="val[submit_detail]" value="{phrase var='fevent.submit'}" class="button" />
		{/if}
		</div>
		
	</div>

	<div id="js_event_block_customize" class="js_event_block page_section_menu_holder" style="display:none;">
		<div id="js_event_block_customize_holder">
			<div class="table">
				<div class="table_left">
					{phrase var='fevent.select_images'}
				</div>
				<div class="table_right">
					<div id="js_event_upload_image">
						<div id="js_progress_uploader"></div>
						<div class="extra_info">
							{phrase var='fevent.you_can_upload_a_jpg_gif_or_png_file'}
							{if $iMaxFileSize !== null}
							<br />
							{phrase var='fevent.the_file_size_limit_is_filesize_if_your_upload_does_not_work_try_uploading_a_smaller_picture' filesize=$iMaxFileSize}
							{/if}							
						</div>
					</div>
				</div>
			</div>
			
			<div id="js_submit_upload_image" class="table_clear">
				<input type="submit" name="val[upload_photo]" value="{phrase var='fevent.upload_photo'}" class="button" />
			</div>
		</div>
        {module name='fevent.photo'}
	</div>
	
	<div id="js_event_block_invite" class="js_event_block page_section_menu_holder" style="display:none;">	
	
			{if Phpfox::isModule('friend')}
			<div style="width:75%; float:left; position:relative;">				
				<h3 style="margin-top:0px; padding-top:0px;">{phrase var='fevent.invite_friends'}</h3>
				<div style="height:370px;">			
					{if isset($aForms.event_id)}
					{module name='friend.search' input='invite' hide=true friend_item_id=$aForms.event_id friend_module_id='fevent'}
					{/if}
				</div>
				{/if}
				<h3>{phrase var='fevent.invite_people_via_email'}</h3>
				<div class="p_4">
					<textarea cols="40" rows="8" name="val[emails]" style="width:98%; height:60px;"></textarea>
					<div class="extra_info">
						{phrase var='fevent.separate_multiple_emails_with_a_comma'}
					</div>
				</div>
				
				<h3>{phrase var='fevent.add_a_personal_message'}</h3>
				<div class="p_4">
					<textarea cols="40" rows="8" name="val[personal_message]" style="width:98%; height:60px;"></textarea>					
				</div>				
				
				<div class="p_top_8">
					<input type="submit" name="val[send_invitations]" value="{phrase var='fevent.send_invitations'}" class="button" />
				</div>				
			</div>
			{if Phpfox::isModule('friend')}
			<div style="margin-left:77%; position:relative;">
				<div class="block">
					<div class="title">{phrase var='fevent.new_guest_list'}</div>				
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
	
	{if $bIsEdit}
	<div id="js_event_block_manage" class="js_event_block page_section_menu_holder" style="display:none;">	
		{module name='fevent.list'}
	</div>
	{/if}
	
	{if $bIsEdit && Phpfox::getUserParam('fevent.can_mass_mail_own_members')}
	<div id="js_event_block_email" class="js_event_block page_section_menu_holder" style="display:none;">
		<div id="js_send_email"{if !$bCanSendEmails} style="display:none;"{/if}>
			<div class="extra_info">
				{phrase var='fevent.send_out_an_email_to_all_the_guests_that_are_joining_this_event'}
				{if isset($aForms.mass_email) && $aForms.mass_email}
				<br />
				{phrase var='fevent.last_mass_email'}: {$aForms.mass_email|date:'mail.mail_time_stamp'}
				{/if}
			</div>
			<br />
			<div class="table">
				<div class="table_left">
					{phrase var='fevent.subject'}:
				</div>
				<div class="table_right">
					<input type="text" name="val[mass_email_subject]" value="" size="30" id="js_mass_email_subject" />
				</div>
			</div>
			<div class="table">
				<div class="table_left">
					{phrase var='fevent.text'}:
				</div>
				<div class="table_right">
					<textarea cols="50" rows="8" name="val[mass_email_text]" id="js_mass_email_text"></textarea>
				</div>
			</div>		
			<div class="table_clear">
				<ul class="table_clear_button">
					<li><input type="button" value="{phrase var='fevent.send'}" class="button" onclick="$('#js_event_mass_mail_li').show(); $.ajaxCall('fevent.massEmail', 'type=message&amp;id={$aForms.event_id}&amp;subject=' + $('#js_mass_email_subject').val() + '&amp;text=' + $('#js_mass_email_text').val()); return false;" /></li>
					<li id="js_event_mass_mail_li" style="display:none;">{img theme='ajax/add.gif' class='v_middle'} <span id="js_event_mass_mail_send">Sending mass email...</span></li>
				</ul>
				<div class="clear"></div>
			</div>
		</div>
		<div id="js_send_email_fail"{if $bCanSendEmails} style="display:none;"{/if}>
			<div class="extra_info">
				{phrase var='fevent.you_are_unable_to_send_out_any_mass_emails_at_the_moment'}
				<br />
				{phrase var='fevent.please_wait_till'}: <span id="js_time_left">{$iCanSendEmailsTime|date:'mail.mail_time_stamp'}</span>
			</div>			
		</div>
	</div>
	{/if}
	
</form>
{if $sTab == 'photo'}
{literal}
<script type="text/javascript">
    $Behavior.pageSectionMenuRequest = function() {
        if (!bIsFirstRun) {
            $Core.pageSectionMenuShow('#js_event_block_customize');
            if ($('#page_section_menu_form').length > 0) {
                $('#page_section_menu_form').val('js_event_block_detail'); 
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
         $("#js_friend_loader").append('<div class="clear" style="padding:5px 0px 10px 0px;"><input type="button" onclick="$(\'input.checkbox\').each(function(i,e){l}if($(e).attr(\'checked\')==\'checked\'){l}return{r} $(e).attr(\'checked\', \'checked\'); addFriendToSelectList(e, $(e).attr(\'id\').replace(\'js_friends_checkbox_\',\'\')); $(\'.friend_search_holder\').addClass(\'friend_search_active\');{r});" value="{phrase var='core.select_all'}" /> <input type="button" onclick="$(\'input.checkbox\').each(function(i,e){l}if(!$(e).is(\':checked\')){l}return{r} $(e).removeAttr(\'checked\'); var jsName = $(e).parent().parent().find(\'.user_profile_link_span:first\').attr(\'id\'); $(\'#js_selected_friends #\'+jsName).parent().remove(); $(\'.js_cached_friend_name\').remove(); $(\'.friend_search_holder\').removeClass(\'friend_search_active\');{r});" value="{phrase var='core.un_select_all'}"/></div>');
         $("#js_friend_loader").parent().css('height','');
    {r}
    
   
    
</script>
{/if}

<script type="text/javascript">
	function showrepeat(value)
    {l}
    	var txtrepeat=$('#txtrepeat').val();
    	var daterepeat=$('#daterepeat').val();
    	var check=$('#cbrepeat').attr('checked');
    	if(check)
    		tb_show("{phrase var='fevent.repeat'}",$.ajaxBox("fevent.repeat","height=300;width=350&value="+value+"&txtrepeat="+txtrepeat+"&daterepeat="+daterepeat));
    	else
    	{l}
    		var bIsEdit=$('#bIsEdit').val();
    		if(!bIsEdit)
    			$('.extra_info').css('display','block');
    		else
    			$('#js_event_add_end_time').css('display','block');
    		$('#chooserepeat').html("...");
    		$('#txtrepeat').val("-1");
    		$('#daterepeat').val("");
    		$('#editrepeat').html("");
    	{r}	
    {r}
    
    
</script>
