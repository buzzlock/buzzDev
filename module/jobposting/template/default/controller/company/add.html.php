<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright        [YOUNET_COPPYRIGHT]
 * @author           AnNT
 * @package          Module_jobposting
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<div class="main_break">
{$sCreateJs}
<form method="post" action="{url link='current'}" name="js_jc_form" id="core_js_jobposting_company_form" onsubmit="{$sGetJsForm}" enctype="multipart/form-data">
    {if isset($iItem) && isset($sModule)}
    <div><input type="hidden" name="val[module_id]" value="{$sModule|htmlspecialchars}" /></div>
    <div><input type="hidden" name="val[item_id]" value="{$iItem|htmlspecialchars}" /></div>
    {/if}
    
    {if $bIsEdit}
    <div><input type="hidden" name="val[company_id]" value="{$aForms.company_id}" /></div>
    <div><input type="hidden" name="val[is_sponsor]" value="{$aForms.is_sponsor}" /></div>
    <div id="js_custom_privacy_input_holder">{module name='privacy.build' privacy_item_id=$aForms.company_id privacy_module_id='jobposting'}</div>
    {/if}
    
    <!--Company Information-->
    <div id="js_jobposting_company_block_info" class="js_jobposting_company_block page_section_menu_holder">
        <div class="table">
            <div class="table_left">
                <label for="name">{required}{phrase var='jobposting.company_name'}:</label>
            </div>
            <div class="table_right">
                <input type="text" name="val[name]" value="{value type='input' id='name'}" id="name" size="40" maxlength="255" />
            </div>
        </div>
        
        {plugin call='jobposting.template_controller_company_add_textarea_start'}
        
        <div class="table">
            <div class="table_left">
                <label for="description">{required}{phrase var='jobposting.description'}:</label>
            </div>
            <div class="table_right">
                {editor id='description'}
            </div>
        </div>
        
        <div class="table">
            <div class="table_left">
                <label for="location">{required}{phrase var='jobposting.headquaters_location'}:</label>
            </div>
            <div class="table_right">
                <input type="text" name="val[location]" value="{value type='input' id='location'}" id="location" size="40" maxlength="255" />
                <div class="extra_info">
                    {if !$bIsEdit}
                    <a href="#" id="js_link_show_add" onclick="$(this).hide(); $('#js_mp_add_city').show(); $('#js_link_hide_add').show(); return false;">{phrase var='jobposting.add_city_zip_country'}</a>
                    <a href="#" id="js_link_hide_add" style="display: none;" onclick="$(this).hide(); $('#js_mp_add_city').hide(); $('#js_link_show_add').show(); return false;">{phrase var='jobposting.hide_city_zip_country'}</a>
                    {/if}
                </div>
            </div>
        </div>
    
        <div id="js_mp_add_city" {if !$bIsEdit} style="display:none;"{/if} >
    
            <div class="table">
                <div class="table_left">
                    <label for="city">{phrase var='jobposting.city'}:</label>
                </div>
                <div class="table_right">
                    <input type="text" name="val[city]" value="{value type='input' id='city'}" id="city" size="25" maxlength="255" />
                </div>
            </div>
            
            <div class="table">
                <div class="table_left">
                    <label for="postal_code">{phrase var='jobposting.zip_postal_code'}:</label>
                </div>
                <div class="table_right">
                    <input type="text" name="val[postal_code]" value="{value type='input' id='postal_code'}" id="postal_code" size="10" maxlength="20" />
                </div>
            </div>
    
            <div class="table">
                <div class="table_left">
                    <label for="country_iso">{phrase var='jobposting.country'}:</label>
                </div>
                <div class="table_right">
                    {select_location}
                    {module name='core.country-child'}
                </div>
            </div>
        </div>
    
        <div class="table">
            <div class="table_left">
                <input id="refresh_map" type="button" value="{phrase var='jobposting.refresh_map'}" onclick="inputToMap();"/>
            </div>
            <div class="table_right">
                <input type="hidden" name="val[gmap][latitude]" value="{value type='input' id='input_gmap_latitude'}" id="input_gmap_latitude" />
                <input type="hidden" name="val[gmap][longitude]" value="{value type='input' id='input_gmap_longitude'}" id="input_gmap_longitude" />
                <div id="mapHolder" style="width: 400px; height: 400px"></div>
            </div>
        </div>
        
        <div class="table">
            <div class="table_left">
                <label for="website">{phrase var='jobposting.website'}:</label>
            </div>
            <div class="table_right">
                <input type="text" name="val[website]" value="{value type='input' id='website'}" id="website" size="40" maxlength="255" />
            </div>
        </div>
        
        <div class="table">
            <div class="table_left">
                <label for="size">{required}{phrase var='jobposting.company_size'}:</label>
            </div>
            <div class="table_right">
                {phrase var='jobposting.from'} <input type="text" name="val[size_from]" value="{value type='input' id='size_from'}" id="size_from" size="5" maxlength="10" />
                {phrase var='jobposting.to'} <input type="text" name="val[size_to]" value="{value type='input' id='size_to'}" id="size_to" size="5" maxlength="10" />
                {phrase var='jobposting.employees'}
            </div>
        </div>
        
        <div class="table">
            <div class="table_left">
                <label for="industry">{required}{phrase var='jobposting.industry'}:</label>
            </div>
            <div class="table_right">
                {$sIndustries}
            </div>            
            <div class="extra_info">{phrase var='jobposting.you_can_add_up_to_3_industries'}</div>
        </div>
        
        <div class="table">
            <div class="table_left">
                <label for="contact">{phrase var='jobposting.contact_information'}:</label>
            </div>
            <div class="table_right">
                <label for="contact_name">{required}{phrase var='jobposting.name'}:</label><br />
                <input type="text" name="val[contact_name]" value="{value type='input' id='contact_name'}" id="contact_name" size="40" maxlength="255" /><br />
                <label for="contact_phone">{required}{phrase var='jobposting.phone'}:</label><br />
                <input type="text" name="val[contact_phone]" value="{value type='input' id='contact_phone'}" id="contact_phone" size="40" maxlength="255" /><br />
                <label for="contact_email">{required}{phrase var='jobposting.email'}:</label><br />
                <input type="text" name="val[contact_email]" value="{value type='input' id='contact_email'}" id="contact_email" size="40" maxlength="255" /><br />
                <label for="contact_fax">{phrase var='jobposting.fax'}:</label><br />
                <input type="text" name="val[contact_fax]" value="{value type='input' id='contact_fax'}" id="contact_fax" size="40" maxlength="255" /><br />
            </div>
        </div>
        
        {if empty($sModule) && Phpfox::isModule('privacy')}
        <div class="table">
            <div class="table_left">
                {phrase var='jobposting.company_privacy'}:
            </div>
            <div class="table_right">    
                {module name='privacy.form' privacy_name='privacy' privacy_info='jobposting.control_who_can_see_your_company_information' privacy_no_custom=true}
            </div>            
        </div>
        <div class="table">
            <div class="table_left">
                {phrase var='jobposting.share_privacy'}:
            </div>
            <div class="table_right">    
                {module name='privacy.form' privacy_name='privacy_comment' privacy_info='jobposting.control_who_can_share_on_your_company' privacy_no_custom=true}
            </div>            
        </div>
        {/if}
        
        <div style="display: none;"><input type="checkbox" name="val[sponsor]" id="js_jc_sponsor_checkbox" /></div>
        
    	<div class="table_clear">
            <ul class="table_clear_button">
            	{if $bIsEdit}
            		{if $aForms.post_status != 1}
					<li><input type="submit" name="val[draft_update]" value="{phrase var='jobposting.update'}" class="button" onclick="this.form.action='{url link='jobposting.company.add' id=$aForms.company_id}'" /></li>
					<li><input type="submit" name="val[draft_publish]" value="{phrase var='jobposting.publish'}" class="button js_jc_draft_publish_btn" /></li>
            		{else}
					<li><input type="submit" name="val[update]" value="{phrase var='jobposting.update'}" class="button" onclick="this.form.action='{url link='jobposting.company.add' id=$aForms.company_id}'" /></li>
	            		{if $aForms.is_approved && $aForms.is_sponsor != 1 && Phpfox::getUserParam('jobposting.can_sponsor_company')}
	            		<li><input type="button" value="{phrase var='jobposting.sponsor_company'}" class="button js_jc_sponsor_btn" /><span class="js_jc_add_loading"></span></li>
	            		{/if}
            		{/if}
            	{else}
				<li><input type="submit" name="val[publish]" value="{phrase var='jobposting.publish'}" class="button js_jc_publish_btn" /></li>
				<li><input type="submit" name="val[draft]" value="{phrase var='jobposting.save_as_draft'}" class="button button_off" /></li>
            	{/if}
            </ul>
            <div class="clear"></div>
        </div>
    </div>
    <!--//Company Information-->
    
    {if $bIsEdit}
    <!--Photos-->
    <div id="js_jobposting_company_block_photos" class="js_jobposting_company_block page_section_menu_holder" style="display:none;">
		<div id="js_jobposting_company_block_photos_holder">
			<div class="table">
				<div class="table_left">
					{phrase var='jobposting.select_images'}
				</div>
				<div class="table_right">
					<div id="js_jobposting_company_upload_image">
						<div id="js_progress_uploader"></div>
						<div class="extra_info">
							{phrase var='jobposting.you_can_upload_a_jpg_gif_or_png_file'}
							{if $iMaxFileSize !== null}
							<br />
							{phrase var='jobposting.the_file_size_limit_is_filesize_if_your_upload_does_not_work_try_uploading_a_smaller_picture' filesize=$iMaxFileSize}
							{/if}
						</div>
					</div>
				</div>
			</div>
			
			<div id="js_submit_upload_image" class="table_clear">
                <ul class="table_clear_button">
    				<li><input type="submit" name="val[upload_photo]" value="{phrase var='jobposting.upload_photos'}" class="button" onclick="this.form.action='{url link='jobposting.company.add.photos' id=$aForms.company_id}'" /></li>
                	{if $aForms.post_status != 2 && $aForms.is_approved && $aForms.is_sponsor != 1 && Phpfox::getUserParam('jobposting.can_sponsor_company')}
	            	<li><input type="button" value="{phrase var='jobposting.sponsor_company'}" class="button js_jc_sponsor_btn" /><span class="js_jc_add_loading"></span></li>
	            	{/if}
                </ul>
                <div class="clear"></div>
			</div>
		</div>
        {module name='jobposting.company.photo'}
	</div>
    <!--//Photos-->
    
    <!--My Bought Packages-->
    <div id="js_jobposting_company_block_packages" class="js_jobposting_company_block page_section_menu_holder" style="display:none;">
    	<input type="hidden" id="currency_jobposting" value="{$currency}"/>
        <div class="table">
            <div class="table_left">
                {phrase var='jobposting.your_existing_packages'}
            </div>
            <div class="table_right">
                <table class="default_table" cellpadding="0" cellspacing="0" id="js_jc_bought_packages">
                    <tr>
                        <th align="left">{phrase var='jobposting.package_name'}</th>
                        <th>{phrase var='jobposting.fee'}</th>
                        <th>{phrase var='jobposting.remaining_job_posts'}</th>
                        <th>{phrase var='jobposting.valid_time'}</th>		
                        <th>{phrase var='jobposting.payment_status'}</th>
                    </tr>
                    {foreach from=$aForms.packages name=package item=aPackage}
                    <tr{if is_int($phpfox.iteration.package/2)} class="on"{/if}>
                        <td>{$aPackage.name}</td>
                        <td class="t_center">{$aPackage.fee_text}</td>
                        <td class="t_center">{if $aPackage.post_number==0}{phrase var='jobposting.unlimited'}{else}{$aPackage.remaining_post}{/if}</td>
                        <td class="t_center">{$aPackage.expire_text}</td>
                        <td class="t_center">{$aPackage.status_text}</td>		
                    </tr>
                    {foreachelse}
                    <tr>
                        <td colspan="5">
                            <div class="extra_info">{phrase var='jobposting.no_package_found'}.</div>
                        </td>
                    </tr>
                    {/foreach}
                </table>
            </div>
        </div>
        
        <div class="table">
            <div class="table_left">
               {phrase var='jobposting.your_can_purchase_additional_packages'}
            </div>
            <div class="table_right">
                <ul class="jc_list" id="js_jc_tobuy_packages">
                    {foreach from=$aForms.tobuy_packages name=tbpackage item=aTBPackage}
                    <li><label><input type="checkbox" name="val[packages][]" value="{$aTBPackage.package_id}" id="js_jc_package_{$aTBPackage.package_id}" class="js_jc_package" fee_value="{$aTBPackage.fee}" />{$aTBPackage.name} - {$aTBPackage.fee_text} - {if $aTBPackage.post_number==0}{phrase var='jobposting.unlimited'}{else}{phrase var='jobposting.remaining'} {$aTBPackage.post_number} {phrase var='jobposting.job_posts'}{/if} - {$aTBPackage.expire_text}</label></li>
                    {foreachelse}
                    <li><div class="extra_info">{phrase var='jobposting.no_package_found'}.</div></li>
                    {/foreach}
                </ul>
            </div>
        </div>
        
        <div class="table_clear">
            <ul class="table_clear_button">
          
                <li><input type="button" value="{phrase var='jobposting.pay_packages'}" class="button button_off js_jc_pay_packages_btn" disabled="disabled" /><span class="js_jc_add_loading"></span></li>
				  	{if $aForms.post_status == 2}
				<li><input type="submit" name="val[draft_update]" value="{phrase var='jobposting.update'}" class="button" onclick="this.form.action='{url link='jobposting.company.add.packages' id=$aForms.company_id}'" /></li>
				<li><input type="submit" name="val[draft_publish]" value="{phrase var='jobposting.publish'}" class="button js_jc_draft_publish_btn" /></li>
        		{else}
				
	        		{if $aForms.is_approved && $aForms.is_sponsor != 1 && Phpfox::getUserParam('jobposting.can_sponsor_company')}
	        		<li><input type="button" value="{phrase var='jobposting.sponsor_company'}" class="button js_jc_sponsor_btn" /></li>
	        		{/if}
        		{/if}
            </ul>
            <div class="clear"></div>
        </div>
	</div>
    <!--//My Bought Packages-->
    
    <!--Submission Form-->
    <div id="js_jobposting_company_block_form" class="js_jobposting_company_block page_section_menu_holder" style="display:none;">
        <div class="table">
            <div class="table_left">
                <label for="form_title">{required}{phrase var='jobposting.form_title'}:</label>
            </div>
            <div class="table_right">
                <input type="text" name="val[form_title]" value="{if isset($aForms.form_title)}{value type='input' id='form_title'}{else}{$aForms.name}{/if}" id="form_title" size="40" maxlength="255" />
            </div>
            <div class="extra_info clear">{phrase var='jobposting.enter_the_title_for_the_submission_form'}</div>
        </div>
        
        <div class="table">
            <div class="table_left">
                <label for="form_description">{phrase var='jobposting.form_description'}:</label>
            </div>
            <div class="table_right">
                <textarea name="val[form_description]" id="form_description" cols="40" rows="5">{value type='textarea' id='form_description'}</textarea>
            </div>
            <div class="extra_info clear">{phrase var='jobposting.enter_the_description_for_the_form_this_will_appear_below_the_form_title'}</div>
        </div>
        
        <div class="table">
            <div class="table_left">
                <label for="company_logo">{phrase var='jobposting.company_logo'}:</label>
            </div>
            <div class="table_right">
                {if isset($aForms.logo_image)}
                <div id="js_jc_logo_holder" style="position: relative; width: 120px; margin: 0 0 2px 2px;" onmouseover="$('#js_jc_remove_button').show()" onmouseout="$('#js_jc_remove_button').hide()">
                    <div id="js_jc_remove_button" style="position: absolute; display: none;">
                        <a href="#" title="{phrase var='jobposting.delete_this_image'}" onclick="if (confirm('{phrase var='jobposting.are_you_sure' phpfox_squote=true}')) {l} $('#js_jc_logo_holder').remove(); $.ajaxCall('jobposting.deleteLogo', 'id={$aForms.company_id}'); {r} return false;">{img theme='misc/delete_hover.gif' alt=''}</a>
                    </div>
                    {$aForms.logo_image}
                </div>
                {/if}
                <input type="file" name="company_logo" id="company_logo" />
            </div>
        </div>
        
        <div class="table">
            <div class="table_left">
                {phrase var='jobposting.job_title'}:
            </div>
            <div class="table_right">
                <label><input type="checkbox" name="val[job_title_enable]" id="job_title_enable" {if array_key_exists('job_title_enable', $aForms) && $aForms.job_title_enable!='0'}checked="checked"{/if} /> {phrase var='jobposting.show_job_title'}</label>
            </div>
        </div>
        
        <div class="table">
            <div class="table_left">
                {phrase var='jobposting.candidate_name'}:
            </div>
            <div class="table_right">
                <label><input type="checkbox" name="val[candidate_name_enable]" id="candidate_name_enable" {if array_key_exists('candidate_name_enable', $aForms) && $aForms.candidate_name_enable!='0'}checked="checked"{/if} /> {phrase var='jobposting.enable_your_name_field'}</label><br />
                <label><input type="checkbox" name="val[candidate_name_require]" id="candidate_name_require" {if array_key_exists('candidate_name_require', $aForms) && $aForms.candidate_name_require!='0'}checked="checked"{/if} /> {phrase var='jobposting.required_field'}</label>
            </div>
        </div>
        
        <div class="table">
            <div class="table_left">
                {phrase var='jobposting.candidate_photo'}:
            </div>
            <div class="table_right">
                <label><input type="checkbox" name="val[candidate_photo_enable]" id="candidate_photo_enable" {if array_key_exists('candidate_photo_enable', $aForms) && $aForms.candidate_photo_enable!='0'}checked="checked"{/if} /> {phrase var='jobposting.enable_your_photo_field'}</label><br />
                <label><input type="checkbox" name="val[candidate_photo_require]" id="candidate_photo_require" {if array_key_exists('candidate_photo_require', $aForms) && $aForms.candidate_photo_require!='0'}checked="checked"{/if} /> {phrase var='jobposting.required_field'}</label>
            </div>
        </div>
        
        <div class="table">
            <div class="table_left">
                {phrase var='jobposting.candidate_email'}:
            </div>
            <div class="table_right">
                <label><input type="checkbox" name="val[candidate_email_enable]" id="candidate_email_enable" {if array_key_exists('candidate_email_enable', $aForms) && $aForms.candidate_email_enable!='0'}checked="checked"{/if} /> {phrase var='jobposting.enable_your_email_field'}</label><br />
                <label><input type="checkbox" name="val[candidate_email_require]" id="candidate_email_require" {if array_key_exists('candidate_email_require', $aForms) && $aForms.candidate_email_require!='0'}checked="checked"{/if} /> {phrase var='jobposting.required_field'}</label>
            </div>
        </div>
        
        <div class="table">
            <div class="table_left">
                {phrase var='jobposting.candidate_telephone'}:
            </div>
            <div class="table_right">
                <label><input type="checkbox" name="val[candidate_telephone_enable]" id="candidate_telephone_enable" {if array_key_exists('candidate_telephone_enable', $aForms) && $aForms.candidate_telephone_enable!='0'}checked="checked"{/if} /> {phrase var='jobposting.enable_your_telephone_field'}</label><br />
                <label><input type="checkbox" name="val[candidate_telephone_require]" id="candidate_telephone_require" {if array_key_exists('candidate_telephone_require', $aForms) && $aForms.candidate_telephone_require!='0'}checked="checked"{/if} /> {phrase var='jobposting.required_field'}</label>
            </div>
        </div>
        
        <div class="table">
            <div class="table_left">
                {phrase var='jobposting.resume'}:
            </div>
            <div class="table_right">
                <label><input type="checkbox" name="val[resume_enable]" id="resume_enable" {if !Phpfox::isModule('resume')}disabled{/if} {if array_key_exists('resume_enable', $aForms) && $aForms.resume_enable!='0'}checked="checked"{/if} /> {phrase var='jobposting.allow_candidate_to_apply_this_job_by_using_his_her_resume_on_module_resume'}</label>
                {if !Phpfox::isModule('resume')}
                <br/><span style="font-size: smalll;">
                	{phrase var='jobposting.to_use_this_option_please_install_module_a_href_link_resume_a' link="http://phpfox.younetco.com/v3-resume.html"}
                </span>
                {/if}
            </div>
        </div>
        
        <div class="table">
            <div class="table_left">
                {phrase var='jobposting.resume_format'}:
            </div>
            <div class="table_right ynjp_resumeFormat_right">
                {phrase var='jobposting.ms_word_pdf_zip_size_maximum' size='500 kb'}
            </div>
        </div>
        
        <div id="js_custom_field_review_holder">{$sCustomField}</div>
		
		<div class="ynjp_addField clear">
			<a href="#" onclick="tb_show('{phrase var='jobposting.add_field_question'}', $.ajaxBox('jobposting.controllerAddField', 'height=300&width=300&action=add&company_id={$aForms.company_id}')); return false;">{phrase var='jobposting.add_field_question'}</a>
		</div>
        
        <div class="table_clear">
            <ul class="table_clear_button">
        		{if $aForms.post_status == 2}
				<li><input type="submit" name="val[draft_update]" value="{phrase var='jobposting.update'}" class="button" onclick="this.form.action='{url link='jobposting.company.add.form' id=$aForms.company_id}'" /></li>
				<li><input type="submit" name="val[draft_publish]" value="{phrase var='jobposting.publish'}" class="button js_jc_draft_publish_btn" /></li>
        		{else}
				<li><input type="submit" name="val[update]" value="{phrase var='jobposting.update'}" class="button" onclick="this.form.action='{url link='jobposting.company.add.form' id=$aForms.company_id}'" /></li>
	        		{if $aForms.is_approved && $aForms.is_sponsor != 1 && Phpfox::getUserParam('jobposting.can_sponsor_company')}
	        		<li><input type="button" value="{phrase var='jobposting.sponsor_company'}" class="button js_jc_sponsor_btn" /><span class="js_jc_add_loading"></span></li>
	        		{/if}
        		{/if}
            </ul>
            <div class="clear"></div>
        </div>
    </div>
    <!--//Submission Form-->
    
    <!--Manage Job Posted-->
    <div id="js_jobposting_company_block_jobs" class="js_jobposting_company_block page_section_menu_holder" style="display:none;">
		<div class="ynjp_manageJobPosted">
			<div class="clear">
				<div class="ynjp_search">
					<div class="ynjp_searchForm">
						<div>
							<label>{phrase var='jobposting.job_title'}</label>
							<input type="text" name="search_title" value="{value type='input' id='search_title'}" id="search_title" maxlength="255" />
						</div>
						<div>
							<label>{phrase var='jobposting.posted_from'}</label>
							<div style="position: relative;">{select_date prefix='from_' id='_from' start_year='current_year' end_year='+1' field_separator=' / ' field_order='MDY' default_all=true}</div>
						</div>	
						<div>
							<label>{phrase var='jobposting.to'}</label>
							<div style="position: relative;">{select_date prefix='to_' id='_to' start_year='current_year' end_year='+1' field_separator=' / ' field_order='MDY' default_all=true}</div>
						</div>						
						<div>
							<label>{phrase var='jobposting.status'}</label>
							<select name="search_status">
								<option value="all">{phrase var='jobposting.all'}</option>
								<option value="show"{if isset($aForms.search_status) && $aForms.search_status=='show'} selected="selected"{/if}>{phrase var='jobposting.show'}</option>
								<option value="hide"{if isset($aForms.search_status) && $aForms.search_status=='hide'} selected="selected"{/if}>{phrase var='jobposting.hide'}</option>
							</select>
						</div>
						<div>
							<div class="buttons">
								<input type="button" value="{phrase var='jobposting.search'}" class="button" id="js_jc_search_jobs" />
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="clear"> </div>
		      
        <table class="default_table" cellpadding="0" cellspacing="0" id="js_jc_job_posted" style="margin: 20px 0;">
            <tr>
                <th align="left">{phrase var='jobposting.job_title'}</th>
                <th>{phrase var='jobposting.posted_date'}</th>
                <th>{phrase var='jobposting.expired_date'}</th>
                <th>{phrase var='jobposting.show'}</th>		
                <th>{phrase var='jobposting.status'}</th>
                <th align="left">{phrase var='jobposting.option'}</th>
            </tr>
            {foreach from=$aJobs name=job item=aJob}
            <tr{if is_int($phpfox.iteration.job/2)} class="on"{/if} id="js_jp_job_{$aJob.job_id}">
                <td><a href="{permalink module='jobposting' id=$aJob.job_id title=$aJob.title}">{$aJob.title}</a></td>
                <td class="t_center">{$aJob.posted_text}</td>
                <td class="t_center">{$aJob.expire_text}</td>
                <td class="t_center"><a href="#" onclick="$.ajaxCall('jobposting.changeJobHide', 'id={$aJob.job_id}'); return false;">{if $aJob.is_hide==1}{phrase var='jobposting.hide'}{else}{phrase var='jobposting.show'}{/if}</a></td>
                <td class="t_center">{$aJob.status_jobs}</td>
                <td>
                    {if !isset($aJob.is_expired) || $aJob.is_expired == 0}
                        <a href="{permalink module='jobposting.add' id=$aJob.job_id}">{phrase var='jobposting.edit'}</a> | 
                    {/if}
                    <a href="#" onclick="if(confirm('{phrase var='core.are_you_sure'}')) $.ajaxCall('jobposting.deleteJob', 'id={$aJob.job_id}'); return false;">{phrase var='jobposting.delete'}</a> | 
                    {if $aJob.post_status!=1}
                    <a href="javascript:void(0);" onclick="$Core.box('jobposting.popupPublishJob', '500', 'id={$aJob.job_id}'); return false;">{phrase var='jobposting.publish'}</a>
                    {else}
                    {if $aJob.total_application>0}
                    <a href="{url link='jobposting.company.manage' job=$aJob.job_id}">{phrase var='jobposting.view_applications'} ({$aJob.total_application})</a> | 
                    <a class="no_ajax_link" href="{$urlModule}jobposting/static/php/downloadzip.php?id={$aJob.job_id}">{phrase var='jobposting.download_all_resumes'}</a>
                    {else}
                    {phrase var='jobposting.view_applications'} (0) | {phrase var='jobposting.download_all_resumes'}
                    {/if}
                    {/if}
                </td>
            </tr>
            {foreachelse}
            <tr>
                <td colspan="6">
                    <div class="extra_info">{phrase var='jobposting.no_job_found'}.</div>
                </td>
            </tr>
            {/foreach}
        </table>
        {pager}
        
        <div class="table_clear">
            <ul class="table_clear_button">
        		{if $aForms.post_status == 2}
				<li><input type="submit" name="val[draft_update]" value="{phrase var='jobposting.update'}" class="button" onclick="this.form.action='{url link='jobposting.company.add.jobs' id=$aForms.company_id}'" /></li>
				<li><input type="submit" name="val[draft_publish]" value="{phrase var='jobposting.publish'}" class="button js_jc_draft_publish_btn" /></li>
        		{else}
	        		{if $aForms.is_approved && $aForms.is_sponsor != 1 && Phpfox::getUserParam('jobposting.can_sponsor_company')}
	        		<li><input type="button" value="{phrase var='jobposting.sponsor_company'}" class="button js_jc_sponsor_btn" /><span class="js_jc_add_loading"></span></li>
	        		{/if}
        		{/if}
            </ul>
            <div class="clear"></div>
        </div>
	</div>
    <!--//Manage Job Posted-->
    
    <!--Admins-->
    <div id="js_jobposting_company_block_admins" class="js_jobposting_company_block page_section_menu_holder" style="display:none;">					
		<div class="go_left" style="margin-right:5px;">
			<div id="js_custom_search_friend"></div>
			<div class="table_clear">
                <ul class="table_clear_button">
	        		{if $aForms.post_status == 2}
					<li><input type="submit" name="val[draft_update]" value="{phrase var='jobposting.update'}" class="button" onclick="this.form.action='{url link='jobposting.company.add.admins' id=$aForms.company_id}'" /></li>
					<li><input type="submit" name="val[draft_publish]" value="{phrase var='jobposting.publish'}" class="button js_jc_draft_publish_btn" /></li>
	        		{else}
					<li><input type="submit" name="val[update]" value="{phrase var='jobposting.update'}" class="button" onclick="this.form.action='{url link='jobposting.company.add.admins' id=$aForms.company_id}'" /></li>
		        		{if $aForms.is_approved && $aForms.is_sponsor != 1 && Phpfox::getUserParam('jobposting.can_sponsor_company')}
		        		<li><input type="button" value="{phrase var='jobposting.sponsor_company'}" class="button js_jc_sponsor_btn" /><span class="js_jc_add_loading"></span></li>
		        		{/if}
	        		{/if}
                </ul>
                <div class="clear"></div>
            </div>
		</div>
		<div>		
			<div id="js_custom_search_friend_placement">{if count($aForms.admins)}
				<div class="js_custom_search_friend_holder">			
					<ul>
					{foreach from=$aForms.admins item=aAdmin}
						<li>
							<a href="#" class="friend_search_remove" title="Remove" onclick="$(this).parents('li:first').remove(); return false;">{phrase var='pages.remove'}</a>
							<div class="friend_search_image">{img user=$aAdmin suffix='_50_square' max_width='25' max_height='25'}</div>
							<div class="friend_search_name">{$aAdmin.full_name|clean}</div>
							<div class="clear"></div>
							<div><input type="hidden" name="admins[]" value="{$aAdmin.user_id}" /></div>
						</li>
					{/foreach}
					</ul>
				</div>
				{/if}</div>					
		</div>
		<div class="clear"></div>		
				
		<script type="text/javascript">
			$Behavior.ynjpSearchFriends = function(){l}
				$Core.searchFriends({l}
					'id': '#js_custom_search_friend',
					'placement': '#js_custom_search_friend_placement',
					'width': '300px',
					'max_search': 10,
					'input_name': 'admins',
					
					'default_value': '{phrase var='pages.search_friends_by_their_name'}'					
				{r});		
			{r};
		</script>						
	</div>
    <!--//Admins-->
    {/if}

</form>

{if Phpfox::getParam('core.display_required')}
<div class="table_clear">
    {required} {phrase var='core.required_fields'}
</div>
{/if}
</div>

<script type="text/javascript">
$Behavior.pageSectionMenuRequest = function(){l}
    $Core.pageSectionMenuShow('#js_jobposting_company_block_{$sNewReq}'); 
    if ($('#page_section_menu_form').length > 0){l}
        $('#page_section_menu_form').val('js_jobposting_company_block_{$sNewReq}'); 
    {r}
{r};

$Behavior.ynjpConfirmSponsor = function(){l}
	$('.js_jc_publish_btn').live('click', function(){l}
		ynjobposting.company.confirmSponsor('{$bCanSponsorPublishedCompany}', '{$iSponsorFee}');
	{r});
{r};

{if $bIsEdit}
$Behavior.initPayPackagesBtn = function(){l}
    ynjobposting.company.updatePayPackagesBtn();
{r};

$Behavior.ynjpHandleEvent = function(){l}

	$('.js_jc_draft_publish_btn').live('click', function(){l}
		this.form.action = '{url link='jobposting.company.add' id=$aForms.company_id}';
		ynjobposting.company.confirmSponsor('{$bCanSponsorPublishedCompany}', '{$iSponsorFee}');
	{r});
	
	$('#core_js_jobposting_company_form').on('submit', function(){l}
	    return ynjobposting.company.submitForm();
	{r});
	
	$('.js_jc_sponsor_btn').live('click', function(){l}
	    ynjobposting.company.sponsor({$aForms.company_id}, '{$iSponsorFee}');
	{r});
	
	$('.js_jc_package').live('click', function(){l}
		ynjobposting.company.updatePayPackagesBtn();
	{r});
	
	$('.js_jc_pay_packages_btn').live('click', function(){l}
	    ynjobposting.company.payPackages({$aForms.company_id});
	{r});
	
	$('#js_jc_search_jobs').live('click',function(){l}
	    ynjobposting.company.searchJobs('{url link='jobposting.company.add.jobs' id=$aForms.company_id}');
	{r});
	
	$('[rel=js_jobposting_company_block_info]').click(function(){l}
	   $Core.loadInit();
	{r});
{r};

$Behavior.clickInitJob = function(){l}
    $('[rel=js_jobposting_company_block_jobs]').click(function(){l}
        var next=$('.pager_next_link').attr('href');
        if(next)
    	{l}
    		var nnext=next.replace(/company\/add(.*)\/id/g,"company/add/jobs/id");
    		$('.pager_next_link').attr('href',nnext);
    	{r}
        
    	var prew=$('.pager_previous_link').attr('href');
    	if(prew)
    	{l}
    		var nprew=prew.replace(/company\/add(.*)\/id/g,"company/add/jobs/id");
    		$('.pager_previous_link').attr('href',nprew);
    	{r}
    {r});
{r};

{/if}
</script>

