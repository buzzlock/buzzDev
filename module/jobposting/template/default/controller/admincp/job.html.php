<?php 
/**
 * [PHPFOX_HEADER]
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
{literal}
<style type="text/css">
	input[name='quick_edit_input']{
		width: 90%;
		margin-bottom: 2px;
	}
</style>
{/literal}
<form method="post" action="{url link="admincp.jobposting.job"}">

<div class="table_header">
	{phrase var='jobposting.search_filter'}
</div>
<div class="table">
	<div class="table_left">
		{phrase var='jobposting.job'}
	</div>
	<div class="table_right">
		{$aFilters.search}
	</div>
	<div class="clear"></div>
</div>

<div class="table">
	<div class="table_left">
		{phrase var='jobposting.company'}
	</div>
	<div class="table_right">
		{$aFilters.searchcompany}
	</div>
	<div class="clear"></div>
</div>

<div class="table">
	<div class="table_left">
		{phrase var='jobposting.industry'}
	</div>
	<div class="table_right">
		{$aIndustryBlock}
	</div>
	<div class="clear"></div>
</div>

<div class="table">
	<div class="table_left">
		{phrase var='jobposting.feature'}
	</div>
	<div class="table_right">
		{$aFilters.feature} 
	</div>
	<div class="clear"></div>
</div>

<div class="table">
	<div class="table_left">
		{phrase var='jobposting.status'}
	</div>
	<div class="table_right">
		{$aFilters.status} 
	</div>
	<div class="clear"></div>
</div>

<div class="table_clear">
	<input type="submit" name="search[submit]" value="{phrase var='jobposting.submit'}" class="button" />	
</div>
</form>
{pager}
{if count($aJobs)}
<form method="post" action="{url link='admincp.jobposting.job'}">
	<table colspan='1'>
	<tr>
		<th width="180px !important">{phrase var='jobposting.employer'}</th>
		<th width="180px !important">{phrase var='jobposting.job'}</th>
		<th>{phrase var='jobposting.pay_to_feature'}</th>
		<th>{phrase var='jobposting.feature'}</th>
		<th width="150px !important">{phrase var='jobposting.industry'}</th>
		<th width="80px !important;">{phrase var='jobposting.number_of_application'}</th>
		<th width="80px !important;">{phrase var='jobposting.status'}</th>
	</tr>
	{foreach from=$aJobs key=iKey item=aJob}     
	<tr id="js_row{$aJob.job_id}" class="checkRow{if is_int($iKey/2)} tr{else}{/if}">
			<td><a href="{permalink module='jobposting.company' id=$aJob.company_id title=$aJob.name}"}>{$aJob.name}</a></td> 
		<td id="js_job_edit_title{$aJob.job_id}"><a href="{permalink module='jobposting' id=$aJob.job_id title=$aJob.title}" class="quickEdit" id="js_job{$aJob.job_id}">{$aJob.title|convert|clean}</a></td>
		
		<td style="text-align:center">
               {if $aJob.is_paid==1}{phrase var='jobposting.paid'}{else}{phrase var='jobposting.n_a'}{/if}
		</td>
		
		<td id ="item_update_featured_{$aJob.job_id}">		    
			{if $aJob.post_status==1}          
			<a href="javascript:void(0);" onclick="$.ajaxCall('jobposting.updateFeatured', 'job_id={$aJob.job_id}&iIsFeatured={$aJob.is_featured}');return false;">
						<div style="width:50px;">
							{if $aJob.is_featured }
								{img theme='misc/bullet_green.png' alt=''}
							{else}
								{img theme='misc/bullet_red.png' alt=''}
							{/if}
						</div>
					</a>
					{else}
					{phrase var='jobposting.n_a'}
			{/if}
		</td>

	
		<td>{$aJob.industrial_phrase}</td>
		<td style="text-align:center"> 
			{$aJob.total_application}	
		</td>
		<td>
			{$aJob.status_jobs}
		</td>
	</tr>
	{/foreach}
	</table>	
	{else}
	<div class="p_4">
		{phrase var='jobposting.no_jobs_found'}
	</div>
	{/if}
</form>

<div class="extra_info" style="margin-right: 700px; width: 100px; font-weight:bold; position: absolute">
		{phrase var='jobposting.total_upper'} {$iTotalResults} {phrase var='jobposting.result_s'}
	</div>

{pager}