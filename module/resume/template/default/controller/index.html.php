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

{literal}
<style>
	#right{
	display:none;
	}
	.content3{
		width: 100% !important;
	}
</style>
{/literal}

{module name='resume.advanced-search'}
<!-- Insert layout from here -->
{if $bIsSelfView or $sView == 'my'}
	<!-- my resume page layout -->
	{template file='resume.block.my-resume-item'}
{else}	
	{if !count($aResumes)}
		{if !$bViewResumeRegistry and !$sView and !$bIsProfile}
			<div id="public_message" class="public_message" style="display:block;">
				{phrase var="resume.currently_you_can_only_view_your_friend_resumes"}
				<a href="javascript:void(0);" onclick="$Core.box('resume.registerViewResume',500,'');return false;">{phrase var='resume.click_here'}</a> 
				{phrase var='resume.to_register_view_all_resume_service_to_view_full_list_of_resume'}
			</div>
		{/if}
		<div class="extra_info">
			{phrase var='resume.no_resumes_found'}
		</div>
	{else}	
	<!-- normal resume page layout -->		
		<!-- Registration Button -->
		{if !$bViewResumeRegistry and !$sView and !$bIsProfile}
			<div style="text-align: center;">
				<input type="button" value="{phrase var='resume.upgrade_your_account_to_see_full_list_of_resumes'}" class="button" onclick="$Core.box('resume.registerViewResume',500,'');return false;"/>
			</div>
		{/if}
			<!-- Resume items -->
		{foreach from=$aResumes key=iKey item=aResume}
			{template file='resume.block.resume-item'}
		{/foreach}
               
		{pager}
	{/if}
{/if}