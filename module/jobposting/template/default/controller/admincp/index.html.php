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
    ul.packages li {
        list-style: disc inside none;
    }
</style>
{/literal}
<form method="post" action="{url link="admincp.jobposting"}">
<div class="table_header">
	{phrase var='jobposting.search_filter'}
</div>
<div class="table">
	<div class="table_left">
		{phrase var='jobposting.employer'}
	</div>
	<div class="table_right">
		{$aFilters.search}
	</div>
	<div class="clear"></div>
</div>
<div class="table">
	<div class="table_left">
		{phrase var='jobposting.representative'}: 
	</div>
	<div class="table_right">
		{$aFilters.user}
	</div>
	<div class="clear"></div>
</div>
<div class="table">
	<div class="table_left">
		{phrase var='jobposting.sponsor'}
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
{if count($aCompanies)}
<form method="post" action="{url link='admincp.jobposting'}">
	<table colspan='1'>
	<tr>
		<th>{phrase var='jobposting.employer'}</th>
		<th width="150px !important">{phrase var='jobposting.representative'}</th>
		<th width="100px !important">{phrase var='jobposting.pay_to_sponsor'}</th>
		<th>{phrase var='jobposting.sponsor'}</th>
		<th>{phrase var='jobposting.valid_packages'}</th>
		<th width="100px !important">{phrase var='jobposting.action'}</th>
	</tr>
	{foreach from=$aCompanies key=iKey item=aCompany}        
	<tr id="js_row{$aCompany.company_id}" class="checkRow{if is_int($iKey/2)} tr{else}{/if}">
			
		<td id="js_job_edit_title{$aCompany.company_id}"><a href="{permalink module='jobposting.company' id=$aCompany.company_id title=$aCompany.name}" class="quickEdit" id="js_job{$aCompany.company_id}">{$aCompany.name|convert|clean}</a></td>
		<td>{$aCompany|user}</td> 
		
		<td style="text-align:center">
            {if $aCompany.paid_to_sponsor}{phrase var='jobposting.paid'}{/if}
		</td>
		<td style="text-align:center" id="item_update_sponsor_{$aCompany.company_id}">		    
			{if $aCompany.is_approved==1}          
			<a href="javascript:void(0);" onclick="$.ajaxCall('jobposting.updateSponsor', 'company_id={$aCompany.company_id}&iIsSponsor={$aCompany.is_sponsor}');return false;">
				<div style="width:50px;">
					{if $aCompany.is_sponsor }
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
	
		<td>
            {if count($aCompany.packages)}
            <ul class="packages">
                {foreach from=$aCompany.packages item=aPackage}
                    <li>{$aPackage.name}</li>
                {/foreach}
            </ul>
            {/if}
        </td>
		<td>
			<a href="{url link='jobposting.company.add.jobs' id=$aCompany.company_id}" class="quickEdit" id="js_job{$aCompany.company_id}">{phrase var='jobposting.view_jobs'}</a>	
		</td>
		
	</tr>
	{/foreach}
	</table>	
	{else}
	<div class="p_4">
		{phrase var='jobposting.no_companies_found'}
	</div>
	{/if}
</form>

<div class="extra_info" style="margin-right: 700px; width: 100px; font-weight:bold; position: absolute">
		{phrase var='jobposting.total_upper'} {$iTotalResults} {phrase var='jobposting.result_s'}
	</div>

{pager}