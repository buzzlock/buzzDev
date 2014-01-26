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
<form method="post" action="{url link="admincp.contest.transaction"}">
<div class="table_header">
	{phrase var='contest.search_filter'}
</div>
<div class="table">
	<div class="table_left">
		{phrase var='contest.search_for_text'}: 
	</div>
	<div class="table_right">
		{$aFilters.search}
	</div>
	<div class="clear"></div>
</div>
<div class="table">
	<div class="table_left">
		{phrase var='contest.search_for_user'}: 
	</div>
	<div class="table_right">
		{$aFilters.user}
	</div>
	<div class="clear"></div>
</div>

<div class="table">
	<div class="table_left">
		{phrase var='contest.status'}: 
	</div>
	<div class="table_right">
		{$aFilters.status} 
	</div>
	<div class="clear"></div>
</div>

<div class="table">
	<div class="table_left">
		{phrase var='contest.display'}: 
	</div>
	<div class="table_right">
		{$aFilters.display}
	</div>
	<div class="clear"></div>
</div>

<div class="table_clear">
	<input type="submit" name="search[submit]" value="{phrase var='contest.submit'}" class="button" />
	<input type="submit" name="search[reset]" value="{phrase var='contest.reset'}" class="button" />	

	
</div>
</form>
{pager}
{if count($aTransactions)}
<form method="post" action="{url link='admincp.contest'}">
	<table colspan='1'>
	<tr>
		<th>{phrase var='contest.contest_name'}</th>
		<th width="80px !important">{phrase var='contest.created_user'}</th>
		<th>{phrase var='contest.service_type'}</th>
		<th>{phrase var='contest.registered_date'}</th>
		<th>{phrase var='contest.fee'}</th>
		<th width="60px !important">{phrase var='contest.status'}</th>
		<th width="80px !important">{phrase var='contest.note'}</th>
	</tr>
	{foreach from=$aTransactions key=iKey item=aTransaction}    

	<tr id="js_row{$aTransaction.transaction_id}" class="checkRow{if is_int($iKey/2)} tr{else}{/if}">		
		<td id="js_contest_edit_title{$aTransaction.transaction_id}">
			<a href="{permalink module='contest' id=$aTransaction.contest_id title=$aTransaction.contest_name}" class="quickEdit" id="js_contest{$aTransaction.contest_id}">{$aTransaction.contest_name|convert|clean}</a>
		</td>
		<td>{$aTransaction|user}</td>
		<td>{$aTransaction.service_type_string}</td>
		<td>{if $aTransaction.time_stamp } {$aTransaction.time_stamp|date:'contest.contest_short_date_time_format'}{/if}</td>
		<td>{$aTransaction.fee_text}</td>
		<td>{$aTransaction.status_text}</td>	
	</tr>
	{/foreach}
	</table>	
	{else}
	<div class="p_4">
		{phrase var='contest.no_contests_found'}
	</div>
	{/if}
</form>

<div class="extra_info" style="margin-right: 700px; width: 100px; font-weight:bold; position: absolute">
		{phrase var='contest.total'} {$iTotalResults} {phrase var='contest.results'}
	</div>

{pager}