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

<!-- Insert layout from here -->
{if !count($aResumes)}
		<div class="extra_info">
			{phrase var='resume.no_resumes_found'}
		</div>
{else}	
	{foreach from=$aResumes key=iKey item=aResume}
	<div class="yns resume_item my-resume" id="js_item_m_resume_{$aResume.resume_id}">
	
		<!-- Resume thumbnail image -->
		<div class="resume_item_left">
			<a class="moderate_link" href="#{$aResume.resume_id}" rel="resume">{phrase var='resume.moderate'}</a>		
		</div>
	
		<!-- Resume content summary -->
		<div class="resume_item_right">
			<!-- title -->
			<h4>
				<a href="{permalink module='resume.view' id=$aResume.resume_id title=$aResume.headline}">
					<strong>
						{if $aResume.headline}
							{$aResume.headline|shorten:50:"...":false}
						{else}
							{phrase var="resume.your_headline"}
						{/if}	
					</strong>
				</a>
				<!-- status -->
				{if $aResume.is_completed}
					{if $aResume.is_published}
						{if $aResume.status == 'approved'}
						<a href="javascript:void(0);" class="yns-item yns-publish" title="{phrase var='resume.published'}">{phrase var="resume.published"}</a>
						{else}
							<i style="color: red">{phrase var="resume.".$aResume.status}</i>
						{/if}
					{else}
						{if $aResume.status == 'approved'}
							<i style="color: red">{phrase var="resume.private"}</i>
						{elseif $aResume.status == 'denied'}
							<i style="color: red">{phrase var="resume.".$aResume.status}</i>
						{else}
							<a href="javascript:void(0);" class="yns-item yns-complete" title="{phrase var='resume.complete'}">{phrase var="resume.complete"}</a>
						{/if}
					{/if}
				{else}	
					<a href="#" class="yns-item yns-uncomplete" title="{phrase var='resume.incomplete'}">{phrase var='resume.incomplete'}</a>
				{/if}
			</h4>
			
			<!-- updated date  -->
			<div class="yns-res-info">
			<p>
				{phrase var ="resume.updated"}: {$aResume.time_update|date:'core.global_update_time'} - {$aResume.total_favorite} {phrase var="resume.favorites"}
			</p>
			</div>
		
			<!-- Categories -->
			<div class="yns-res-info">
				<p>{phrase var ="resume.categories"}: 
					{if $aResume.categories}
						{$aResume.categories}
					{else}
						{phrase var="resume.not_selected"}
					{/if}
				</p>
			</div>
			<!-- Favorite -->
			<div class="yns-res-info">
			
			</div>
			<!-- Option link -->
			<div class="my-res-option">
				{if $bCanEdit }
					{if $aResume.status != 'approving'}
						<a href="{url link='resume.add' id= $aResume.resume_id}">
							{phrase var="resume.edit"}
						</a>
					{else}
							{phrase var="resume.edit"}
					{/if}
				{/if}
				{if $bCanDelete }
					 |  
					{if $aResume.status != 'approving'}
						<a href="javascript:void(0);" onclick="if(confirm('{phrase var='resume.are_you_sure_you_want_to_delete_this_resume' phpfox_squote=true}')) window.location.href = '{url link='resume.delete' id=$aResume.resume_id}'; return false;">
							{phrase var="resume.delete"}
						</a>
					{else}
							{phrase var="resume.delete"}
					{/if}
				{/if}
				 <!-- button status -->
				 {if $aResume.is_completed}
				 	|
			 		<!-- not published yet -->
				 	{if !$aResume.is_published}
				 		<!-- has a resume is being approved or not -->
				 		{if !$bIsApproving and $aResume.status != 'denied'}
							<a href="javascript:void(0);" onclick="if(confirm('{phrase var='resume.are_you_sure_you_want_to_publish_this_resume' phpfox_squote=true}')) window.location.href ='{url link='resume.publish' id=$aResume.resume_id}'; return false;">
								{phrase var="resume.publish"}
							</a>
						{/if}
					<!-- published -->	
				 	{else}
				 		<!-- the current is being approved or not -->
			 			{if $aResume.status == 'approved'}
							<a href="javascript:void(0);" onclick="if(confirm('{phrase var='resume.are_you_sure_you_want_to_set_private_this_resume' phpfox_squote=true}')) window.location.href ='{url link='resume.private' id=$aResume.resume_id}'; return false;">
								{phrase var="resume.private"}
							</a>
						{/if}
					{/if}
			 {/if}
       <div style="margin-left:-3px;margin-top:6px;">
                
                 {if $aResume.status == 'approved' && $aResume.is_published == 1}
                    <input id="resume_id_{$aResume.resume_id}" type="checkbox" name="resume_id[]" value="{$aResume.resume_id}" {if $aResume.is_show_in_profile} checked="checked" {/if} class="show_in_profile_info" onChange="showInProfileInfo(this)"/>
                        <label for="resume_id_{$aResume.resume_id}">{phrase var="resume.show_in_profile_info"}</label>
                {/if}
             </div>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	{/foreach}
	<!-- pager -->	
	<div style='clear: both'>
		{pager}
	</div>
	<!-- moderation -->
	{if $bCanDelete }
		<div style='clear: both'>
			{moderation}
		</div>
	{/if}
	<div class="clear"></div>
{/if}
