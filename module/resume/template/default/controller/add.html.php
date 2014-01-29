<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>

{if $is_import || $bIsEdit}
<div class="add-session">
{template file='resume.block.menu_add'}
</div>
<div>
<h3  class="yns add-res">
<ul class="yns menu-add">
	<li>{required}{phrase var='resume.basic_info'}</li>
</ul>
<ul class="yns action-add">
	{if $bIsEdit}<li><a class="page_section_menu_link" href="{permalink module='resume.view' id=$id}">{phrase var='resume.view_my_resume'}</a></li>{/if}
</ul>
</h3>
</div>
{$sCreateJs}
<form method="post" action="{url link='resume.add'}{if $id!=0}id_{$id}/{/if}" onsubmit="return startProcess(custom_js_event_form(), false);" name='js_resume_add_form' id="js_resume_add_form" enctype="multipart/form-data">
<input type="hidden" id="required_custom_fields" value='{$sOutJs}'/>
<div>
    {if $aPers.get_basic_information}
    <input type="hidden" value="1" name="val[is_synchronize]" >
    {else}
    <div class="summary_label">
		<input type="checkbox" value="1" {if isset($aForms.is_synchronize) && $aForms.is_synchronize} checked="checked" {/if} id="is_synchronize" name="val[is_synchronize]" >
        <strong>{phrase var='resume.synchronize_with_basic_information_in_profile'}</strong><br/>
        {phrase var='resume.your_basic_information_will_be_changed_if_you_change_the_below'}
	</div>    
    {/if}
    {if $aPers.display_date_of_birth || $aPers.display_gender || $aPers.display_relation_status}
        <div class="ynr-display"><strong>{phrase var='resume.display'}</strong></div>
    {/if}
    <div class="ynr-add-sumary summary_content">
		<div class="table" style="padding-top: 10px;">
			<div class="table_left">
				<label for="full_name">{required}{phrase var='resume.full_name'}:</label>
			</div>
			<div class="table_right" {if $aPers.get_basic_information} style="padding: 2px 0 6px;" {/if}>
                {if $aPers.get_basic_information}
                    <label class="default_profile_info">{if $aPers.get_basic_information} {value type='input' id='full_name'} {/if}</label>
                    <input type="hidden" value="{value type='input' id='full_name'}" name="val[full_name]" >
                {else}
                    <input type="text" name="val[full_name]"  value="{value type='input' id='full_name'}" id="full_name" size="30" maxlength="200" />
                {/if}
			</div>
		</div>
        
        <div class="table">
			<div class="table_left">
				<label for="full_name">{required}{phrase var='resume.date_of_birth'}:</label>
			</div>
			<div class="table_right" {if $aPers.get_basic_information} style="padding: 2px 0 6px;" {/if}>
                {if $aPers.get_basic_information}
                    <label class="default_profile_info">{if $aPers.get_basic_information} {value type='input' id='birth_day_full'} {/if}</label>
                    <input type="hidden" value="{value type='input' id='month'}" name="val[month]" />
                    <input type="hidden" value="{value type='input' id='day'}" name="val[day]" />
                    <input type="hidden" value="{value type='input' id='year'}" name="val[year]" />
                {else}
                    {select_date start_year=$sDobStart end_year=$sDobEnd field_separator=' / ' field_order='MDY' bUseDatepicker=false sort_years='DESC'}
                {/if}
			</div>
            <div class="ynr-display">
                {if $aPers.display_date_of_birth}
                    <input type="checkbox" {if isset($aForms.display_date_of_birth) && $aForms.display_date_of_birth} checked="checked" {/if} value="1" id="display_date_of_birth" name="val[display_date_of_birth]" />
                {/if}
            </div>
		</div>
        
        <div class="table" >
			<div class="table_left">
				<label for="full_name">{required}{phrase var='resume.gender'}:</label>
			</div>
			<div class="table_right" {if $aPers.get_basic_information} style="padding: 2px 0 6px;" {/if}>
                {if $aPers.get_basic_information}
                    <label class="default_profile_info">{if $aPers.get_basic_information} {value type='input' id='gender_phrase'} {/if}</label>
                    <input type="hidden" value="{value type='input' id='gender'}" name="val[gender]" />
                {else}
                    {select_gender}
                {/if}
			</div>
            <div class="ynr-display">
                {if $aPers.display_gender}
                    <input type="checkbox" {if isset($aForms.display_gender) && $aForms.display_gender} checked="checked" {/if} value="1" id="display_gender" name="val[display_gender]" />
                {/if}
            </div>
		</div>
        
        <div class="table" >
			<div class="table_left">
				<label for="full_name">{phrase var='resume.marital_status'}:</label>
			</div>
			<div class="table_right" {if $aPers.get_basic_information} style="padding: 2px 0 6px;" {/if}>
                {if $aPers.get_basic_information}
                    <label class="default_profile_info">{if $aPers.get_basic_information} {value type='input' id='marital_status_phrase'} {/if}</label>
                    <input type="hidden" value="{value type='input' id='marital_status'}" name="val[marital_status]" />
                {else}
                    <select name="val[marital_status]">
                        <option value='single' {if !empty($aForms) and $aForms.marital_status=='single'}selected{/if}>{phrase var='resume.single'}</option>
                        <option value='married' {if !empty($aForms) and $aForms.marital_status=='married'}selected{/if}>{phrase var='resume.married'}</option>
                        <option value='other' {if !empty($aForms) and $aForms.marital_status=='others'}selected{/if}>{phrase var='resume.others'}</option>
                    </select>
                {/if}
			</div>
            <div class="ynr-display">
                {if $aPers.display_relation_status}
                    <input type="checkbox" {if isset($aForms.display_marital_status) && $aForms.display_marital_status} checked="checked" {/if} value="1" id="display_marital_status" name="val[display_marital_status]" >
                {/if}
            </div>
		</div>
        
        <div class="table">
			<div class="table_left">
				<label for="city">{phrase var='resume.city'}:</label>
			</div>
			<div class="table_right" {if $aPers.get_basic_information} style="padding: 2px 0 6px;" {/if}>
                {if $aPers.get_basic_information}
                    <label class="default_profile_info">{if $aPers.get_basic_information} {value type='input' id='city'} {/if}</label>
                    <input type="hidden" value="{value type='input' id='city'}" name="val[city]" />
                {else}
                    <input type="text" name="val[city]" id="city" value="{value type='input' id='city'}" size="30" />
                {/if}
			</div>
			<div class="clear"></div>
		</div>

		<div class="table">
			<div class="table_left">
				<label for="zip_code">{phrase var='resume.zip_postal_code'}:</label>
			</div>
			<div class="table_right" {if $aPers.get_basic_information} style="padding: 2px 0 6px;" {/if}>
                {if $aPers.get_basic_information}
                    <label class="default_profile_info">{if $aPers.get_basic_information} {value type='input' id='zip_code'} {/if}</label>
                    <input type="hidden" value="{value type='input' id='zip_code'}" name="val[zip_code]" />
                {else}
                    <input type="text" name="val[zip_code]" id="zip_code" value="{value type='input' id='zip_code'}" size="10" />
                {/if}
			</div>
			<div class="clear"></div>
		</div>
    </div>
</div>

<div>
	<div class="summary_content">
		<div class="table">
			<div class="table_left table_left_add">
				<label for="phonenumber">{phrase var='resume.phone_number'}:</label>
			</div>
			<div class="table_right">
				{if !empty($aForms.phone) and count($aForms.phone)>0}
				{foreach from=$aForms.phone item=aPhone name=iphone}
				<div class="placeholder_phone">
	                <div style="padding-top:6px;" class="js_prev_block_phone">
	                	<span class="class_answer" >
	                    	<input type="text" name="val[phone][]" value="{$aPhone.text}" size="30" class="js_predefined_phone" />
	                    </span>
	                    	<select name="val[phonestyle][]" style="padding: 4px 4px 3px !important;">
								<option value='home' {if $aPhone.type=='home'}selected{/if}>{phrase var='resume.home'}</option>
								<option value='work' {if $aPhone.type=='work'}selected{/if}>{phrase var='resume.work'}</option>
								<option value='mobile' {if $aPhone.type=='mobile'}selected{/if}>{phrase var='resume.mobile'}</option>
							</select>
	                        <a href="#" onclick="return appendPredefined(this,'phone');">
	                        	{img theme='misc/add.png' class='v_middle'}
	                        </a>
	                        <a href="#" onclick="return removePredefined(this,'phone');">
	                        	{img theme='misc/delete.png' class='v_middle'}
	                       </a>
	             	 </div>
	             </div>
	             {/foreach}
	             {else}
	             <div class="placeholder_phone">
	                <div style="padding-top:6px;" class="js_prev_block_phone">
	                	<span class="class_answer">
	                    	<input type="text" name="val[phone][]" value="" size="30" class="js_predefined_phone" />
	                    </span>
	                    	<select name="val[phonestyle][]" style="padding: 4px 4px 3px !important;">
								<option value='home'>{phrase var='resume.home'}</option>
								<option value='work'>{phrase var='resume.work'}</option>
								<option value='mobile'>{phrase var='resume.mobile'}</option>
							</select>
	                        <a href="#" onclick="return appendPredefined(this,'phone');">
	                        	{img theme='misc/add.png' class='v_middle'}
	                        </a>
	                        <a href="#" onclick="return removePredefined(this,'phone');">
	                        	{img theme='misc/delete.png' class='v_middle'}
	                       </a>
	             	 </div>
	             </div>	
	             {/if}
			</div>			
		</div>
		
		<div class="table">
			<div class="table_left table_left_add">
				<label for="im">{phrase var='resume.im'}:</label>
			</div>
			<div class="table_right">
				{if !empty($aForms.imessage) and count($aForms.imessage)>0}
				{foreach from=$aForms.imessage item=aimessage name=imessage}
				<div class="placeholder_image">
	            	<div style="padding-top:6px;" class="js_prev_block_image">
	                	<span class="class_answer">
	                    	<input type="text" name="val[homepage][]" value="{$aimessage.text}" size="30" class="js_predefined_imail" />
	                   	</span>
	                    <select name="val[homepagestyle][]" style="padding: 4px 4px 3px !important;">
							<option value='aim' {if $aimessage.type=='aim'}selected{/if}>{phrase var='resume.aim'}</option>
							<option value='skype' {if $aimessage.type=='skype'}selected{/if}>{phrase var='resume.skype'}</option>
							<option value='windows_live_messenger' {if $aimessage.type=='windows_live_messenger'}selected{/if}>{phrase var='resume.windows_live_messenger'}</option>
							<option value='yahoo_messenger' {if $aimessage.type=='yahoo_messenger'}selected{/if}>{phrase var='resume.yahoo_messenger'}</option>
							<option value='icq' {if $aimessage.type=='icq'}selected{/if}>{phrase var='resume.icq'}</option>
							<option value='gtalk' {if $aimessage.type=='gtalk'}selected{/if}>{phrase var='resume.gtalk'}</option>
						</select>
	                    <a href="#" onclick="return appendPredefined(this,'homepage');">
	                    	{img theme='misc/add.png' class='v_middle'}
	                    </a>
	                    <a href="#" onclick="return removePredefined(this,'homepage');">
	                    	{img theme='misc/delete.png' class='v_middle'}
	                    </a>
	                    </div>
	             </div>
				 {/foreach}
				 {else}
				 <div class="placeholder_image">
	            	<div style="padding-top:6px;" class="js_prev_block_image">
	                	<span class="class_answer">
	                    	<input type="text" name="val[homepage][]" value="" size="30" class="js_predefined_imail" />
	                   	</span>
	                    <select name="val[homepagestyle][]" style="padding: 4px 4px 3px !important;">
							<option value='aim'>{phrase var='resume.aim'}</option>
							<option value='skype'>{phrase var='resume.skype'}</option>
							<option value='windows_live_messenger'>{phrase var='resume.windows_live_messenger'}</option>
							<option value='yahoo_messenger'>{phrase var='resume.yahoo_messenger'}</option>
							<option value='icq'>{phrase var='resume.icq'}</option>
							<option value='gtalk'>{phrase var='resume.gtalk'}</option>
						</select>
	                    <a href="#" onclick="return appendPredefined(this,'homepage');">
	                    	{img theme='misc/add.png' class='v_middle'}
	                    </a>
	                    <a href="#" onclick="return removePredefined(this,'homepage');">
	                    	{img theme='misc/delete.png' class='v_middle'}
	                    </a>
	                    </div>
	             </div>
				 {/if}
			</div>			
		</div>
		
		<div class="table">
			<div class="table_left table_left_add">
				<label for="emailaddress">{phrase var='resume.email_address'}:</label>
			</div>
			<div class="table_right">
				{if !empty($aForms) and count($aForms.email)>0}
				{foreach from=$aForms.email item=aemail name=iemail}
				<div class="placeholder">
	            	<div style="padding-top:6px;" class="js_prev_block">
	                	<span class="class_answer">
	                    	<input type="text" name="val[emailaddress][]" value="{$aemail}" size="30" class="js_predefined v_middle" />
	                    </span>
	                    <a href="#" onclick="return appendPredefined(this,'emailaddress');">
	                    	{img theme='misc/add.png' class='v_middle'}
	                    </a>
	                    <a href="#" onclick="return removePredefined(this,'emailaddress');">
	                    	{img theme='misc/delete.png' class='v_middle'}
	                    </a>
	                    </div>
	               </div>
				{/foreach}
				{else}
				<div class="placeholder">
	            	<div style="padding-top:6px;" class="js_prev_block">
	                	<span class="class_answer">
	                    	<input type="text" name="val[emailaddress][]" value="" size="30" class="js_predefined v_middle" />
	                    </span>
	                    <a href="#" onclick="return appendPredefined(this,'emailaddress');">
	                    	{img theme='misc/add.png' class='v_middle'}
	                    </a>
	                    <a href="#" onclick="return removePredefined(this,'emailaddress');">
	                    	{img theme='misc/delete.png' class='v_middle'}
	                    </a>
	                    </div>
	               </div>
				{/if}			
			</div>
		</div>
		
		 {module name='resume.custom'}
		
		<div class="table">
			<div class="table_left table_left_add">
				{phrase var='resume.photo'}:
			</div>
			<div class="table_right ">
				<input type="file" name="image" />
			</div>			
				
			{if $bIsEdit and $aForms.image_path!=""}
			<div style="padding-top:5px">
				{img server_id=$aForms.server_id path='core.url_pic' file='resume/'.$aForms.image_path suffix='_120' max_width='120' max_height='120'}
			</div>
			{/if}
		</div>
        {if Phpfox::isModule('privacy')}
		<div class="table">
			<div class="table_left">
				{phrase var='resume.privacy'}:
			</div>
			<div class="table_right">	
				{module name='resume.privacy.form' privacy_name='privacy' privacy_info='resume.control_who_can_see_this_resume' default_privacy='blog.default_privacy_setting' privacy_no_custom=true}
			</div>			
		</div>
		{/if}
                
		<div class="table">
			<div class="table_right ">
				<input type="submit" class="button" name="addresume" value = "{phrase var='resume.update'}"/>
			</div>			
		</div>
	</div>
</div>
</form>
{else}
<div class="error_message">
{phrase var='resume.each_users_only_can_create_maximum_limit_resume' limit=$total_allowed}
</div>
{/if}
