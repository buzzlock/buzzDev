<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 *
 * @copyright      YouNet Company
 * @author         VuDP, TienNPL
 * @package        Module_Resume
 * @version        3.01
 * 
 */
 ?>
 
 <form method="post" action="{url link='admincp.resume.globalsettings'}" id="js_form">
 	<div class="table_header">
 		{phrase var ="resume.admin_menu_global_settings"}
 	</div>
 	<!-- Group Setup for Who 's Viewed Me Service -->
 	
 	<div class="table" style="border-bottom: none !important">
 		<p><strong>Note:</strong></p>
 		{foreach from=$aCustomGroups item=aGroupParant}
 			{if $aGroupParant.view_all_resume}
 				<p> {phrase var='resume.was_set_permission_to_view_resume' group=$aGroupParant.title}</p>
 			{/if}
 		{/foreach}
 	</div>
 	
 	<div class="table" style="border-bottom: none !important">
 		<p><strong>{phrase var="resume.configure_the_group_for_using_who_s_viewed_me_service"}</strong></p>
 	</div>
 	
 	{foreach from=$aCustomGroups item=aGroupParant}
 	<div class="table">
 		<div class="table_left" style="margin-left: 50px;">
 			{phrase var='resume.group_is_transferred' title = $aGroupParant.title}
 		</div>
 		<div class="table_right" style="margin-left: 350px;">
 			<select name="val[whoview][{$aGroupParant.user_group_id}]">
				<option value="">{phrase var='resume.select'}</option>
				{foreach from=$aCustomGroups item=aGroup}
					{assign var="check" value="1"}
					{foreach from=$aWhoViewedMeGroup item=WhoViewedMeGroup}
						{if $WhoViewedMeGroup.begin_group == $aGroupParant.user_group_id && $WhoViewedMeGroup.end_group == $aGroup.user_group_id}
							{assign var="check" value="2"}
						{/if}
					{/foreach}
					<option value="{$aGroup.user_group_id}" {if $check == 2}selected{/if}>{$aGroup.title}</option>
				{/foreach}
			</select>
 		</div>
 	</div>
 	{/foreach}
 	<!-- Group Setup for View Resume Service -->
 	<br/><br/>
 	<div class="table" style="border-bottom: none !important">
 		<p><strong>{phrase var="resume.configure_the_group_for_using_view_all_resume_service"}</strong></p>
 	</div>
 	
 	{foreach from=$aCustomGroups item=aGroupParant}
 	<div class="table" >
 		<div class="table_left" style="margin-left: 50px;">
 			{phrase var='resume.group_is_transferred' title = $aGroupParant.title}
 		</div>
 		<div class="table_right" style="margin-left: 350px;">
 			<select name="val[viewme][{$aGroupParant.user_group_id}]">
				<option value="">{phrase var='resume.select'}</option>
				{foreach from=$aCustomGroups item=aGroup}
					{assign var="check" value="1"}
					{foreach from=$aViewAllResumeGroup item=ViewAllResumeGroup}
						{if $ViewAllResumeGroup.begin_group == $aGroupParant.user_group_id && $ViewAllResumeGroup.end_group == $aGroup.user_group_id}
							{assign var="check" value="2"}
						{/if}
					{/foreach}
					<option value="{$aGroup.user_group_id}" {if $check == 2}selected{/if}>{$aGroup.title}</option>
				{/foreach}
			</select>
 		</div>
 	</div>
 	{/foreach}
 	
 	<div class="table">
 		<div style="font-size:12px;padding:2px 0 6px;position:absolute;width:290px">
 			<p><strong>{phrase var='resume.public_all_resumes_for_all_members_of_this_site'}</strong></p>
 		</div>
 		<div class="table_right" style="margin-left: 350px;">
 			<select name="val[public_resume]">
 				<option {if $aPublic==1}selected{/if} value="1">{phrase var='resume.everyone'}</option>
 				<option {if $aPublic==2}selected{/if} value="2">{phrase var='resume.registered_members'}</option>
 				<option {if $aPublic==3}selected{/if} value="3">{phrase var='resume.specific_user_groups'}</option>
 			</select>
 		</div>
 	</div>
    
    <div class="table">
 		<div style="font-size:12px;padding:2px 0 6px;position:absolute;width:290px">
 			<p><strong>{phrase var='resume.configure_to_get_basic_information_from_profile'}</strong></p>
 		</div>
 		<div class="table_right" style="margin-left: 350px;">
 			<select name="val[get_basic_information]">
                <option value="1" {if $aPers.get_basic_information}selected="selected"{/if}>{phrase var='resume.true'}</option>
                <option value="0" {if !$aPers.get_basic_information}selected="selected"{/if}>{phrase var='resume.false'}</option>
 			</select>
 		</div>
 	</div>
    
    <div class="table" style="border-bottom: none !important">
 		<p><strong>{phrase var="resume.configure_to_display_basic_information_on_resume"}</strong></p>
 	</div>
 	
 	<div class="table">
 		<div class="table_left" style="margin-left: 50px;">
 			{phrase var='resume.date_of_birth'}
 		</div>
 		<div class="table_right" style="margin-left: 350px;">
 			<select name="val[display_date_of_birth]">
                <option value="1" {if $aPers.display_date_of_birth}selected="selected"{/if}>{phrase var='resume.true'}</option>
                <option value="0" {if !$aPers.display_date_of_birth}selected="selected"{/if}>{phrase var='resume.false'}</option>
			</select>
 		</div>
 	</div>
    
 	<div class="table">
 		<div class="table_left" style="margin-left: 50px;">
 			{phrase var='resume.gender'}
 		</div>
 		<div class="table_right" style="margin-left: 350px;">
 			<select name="val[display_gender]">
                <option value="1" {if $aPers.display_gender}selected="selected"{/if}>{phrase var='resume.true'}</option>
                <option value="0" {if !$aPers.display_gender}selected="selected"{/if}>{phrase var='resume.false'}</option>
			</select>
 		</div>
 	</div>
    
 	<div class="table">
 		<div class="table_left" style="margin-left: 50px;">
 			{phrase var='resume.relation_status'}
 		</div>
 		<div class="table_right" style="margin-left: 350px;">
 			<select name="val[display_relation_status]">
                <option value="1" {if $aPers.display_relation_status}selected="selected"{/if}>{phrase var='resume.true'}</option>
                <option value="0" {if !$aPers.display_relation_status}selected="selected"{/if}>{phrase var='resume.false'}</option>
			</select>
 		</div>
 	</div>
    
    <div class="table">
 		<div style="font-size:12px;padding:2px 0 6px;position:absolute;width:290px">
 			<p><strong>{phrase var='resume.configure_position_to_put_resume_in_basic_info_block_of_profile_page'}</strong></p>
 		</div>
 		<div class="table_right" style="margin-left: 350px;">
 			<select name="val[display_resume_in_profile_info]">
                <option value="1" {if $aPers.display_resume_in_profile_info}selected="selected"{/if}>{phrase var='resume.true'}</option>
                <option value="0" {if !$aPers.display_resume_in_profile_info}selected="selected"{/if}>{phrase var='resume.false'}</option>
 			</select>
 		</div>
 	</div>
    <div class="table">
        <div class="table_left" style="margin-left: 50px;">
 			<label><input type="radio" {if $aPers.position == 1} checked="checked" {/if} value="1" style="vertical-align:bottom;" name="val[position]">{phrase var='resume.the_beginning_of_basic_information_block'}</label>
        </div>
        <div class="table_right" style="margin-left: 350px;">&nbsp;</div>
    </div>
    <div class="table">
        <div class="table_left" style="margin-left: 50px;">
            <label><input type="radio" {if $aPers.position == 2} checked="checked" {/if} value="2" style="vertical-align:bottom;" name="val[position]">{phrase var='resume.the_end_of_basic_information_block'}</label>
        </div>
        <div class="table_right" style="margin-left: 350px;">&nbsp;</div>
    </div>
 	<!-- Submit Button -->
 	<div class="table_clear">
		<input type="submit" value="{phrase var='admincp.update'}" class="button" />
	</div>
 </form>
