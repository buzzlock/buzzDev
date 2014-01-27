<div class="table_header">{phrase var='jobposting.manage_transactions'}</div>
<!-- Search -->
<form method="post" action="{url link='admincp.jobposting.transaction'}">
	
		<div class="table">
		<div class="table_left">
			{phrase var='jobposting.company'}
		</div>
		<div class="table_right">
			<input type="text" name="search[company]" value="{if isset($aForms.company)}{$aForms.company}{/if}"/>
		</div>
	</div>
	
		<div class="table">
		<div class="table_left">
			{phrase var='jobposting.type'}
		</div>
		<div class="table_right">
			<select name="search[type]">
				<option value="0" {if $aForms.type==0}selected{/if}>{phrase var='jobposting.all'}</option>
				<option value="2" {if $aForms.type==2}selected{/if}>{phrase var='jobposting.package'}</option>
				<option value="4" {if $aForms.type==4}selected{/if}>{phrase var='jobposting.feature'}</option>	
				<option value="1" {if $aForms.type==1}selected{/if}>{phrase var='jobposting.sponsor'}</option>				
			</select>
		</div>
	</div>
	
	<div class="table">
		<div class="table_left">
			{phrase var='jobposting.search_from'}:
		</div>
		<div class="table_right">
			<input name="search[fromdate]" id="js_from_date_filter" type="text" value="{if isset($sFromDate) && $sFromDate}{$sFromDate}{/if}" />
			<a href="#" id="js_from_date_filter_anchor">
				<img src="<?php echo Phpfox::getLib('template')->getStyle('image', 'jquery/calendar.gif'); ?>" />
			</a>
		</div>
	</div>
	<div class="table">
		<div class="table_left">
			{phrase var='jobposting.to_date'}
		</div>
		<div class="table_right">
			<input name="search[todate]" id="js_to_date_filter" type="text" value="{if isset($sToDate) && $sToDate}{$sToDate}{/if}" />
			<a href="#" id="js_to_date_filter_anchor">
				<img src="<?php echo Phpfox::getLib('template')->getStyle('image', 'jquery/calendar.gif'); ?>" />
			</a>
		</div>
	</div>
	
	<div class="table">
		<div class="table_left">
			{phrase var='jobposting.status'}
		</div>
		<div class="table_right">
			<select name="search[status_pay]">
				<option value="0">{phrase var='jobposting.all'}</option>
				<option value="2" {if $aForms.status_pay==2}selected{/if}>{phrase var='jobposting.pending'}</option>
				<option value="3" {if $aForms.status_pay==3}selected{/if}>{phrase var='jobposting.completed'}</option>
			</select>
		</div>
	</div>
	<!-- Submit button -->
	<div class="table_clear">
		<input type="submit" class="button" value="{phrase var='jobposting.search'}"></span>
	</div>
</form>

{if count($aTransactions) > 0}
<form action="{url link='current'}" method="post" id="karaoke_recording_list" >
	<table>
		<!-- Table rows header -->
		<tr>
			<th width='20%'>{phrase var='jobposting.employer'}</th>
			
			<th>{phrase var='jobposting.type'}</th>
			<th>{phrase var='jobposting.package'}</th>
			
			
			<th>{phrase var='jobposting.job'}</th>
			<th width='20%'>{phrase var='jobposting.purchased_date'}</th>
			<th>{phrase var='jobposting.fee'}</th>
			<th width='10%'>{phrase var='jobposting.payment_status'}</th>
		</tr>
		<!-- Request rows -->
		{foreach from=$aTransactions key=iKey item=aTransaction}
		<tr id="resume_view_{$aTransaction.transaction_id}" class="checkRow{if is_int($iKey/2)} tr{else}{/if}">
			<td>
				<a href="{permalink module='jobposting.company' id=$aTransaction.company_id title=$aTransaction.name}">
					{$aTransaction.name}
				</a>
			</td>
			<!-- Feature -->
			<td class="type_1">
				{$aTransaction.type_text}
			</td>
			<td >
				{$aTransaction.invoice_text}
				</td>
			<!-- Statistic -->
				<td  class="type_2">
					{if $aTransaction.is_job_text}
					<a href="{permalink module='jobposting' id=$aTransaction.job_id title=$aTransaction.title}">
						{$aTransaction.job_text}
					</a>
					{else}
						{$aTransaction.job_text}
					{/if}
			</td>
			<td>
				{$aTransaction.time_stamp_text}
			</td>
				<td>{$aTransaction.amount_text}</td>
				<td>{if $aTransaction.status_pay!=3}{phrase var='jobposting.pending'}{else}{phrase var='jobposting.completed'}{/if}</td>
		</tr>
		{/foreach}
	</table>
	<!-- Delete selected button -->
	{if count($total_money)>0}
		<div style="align:left">
	        {phrase var='jobposting.total_fee'}:
	       	{foreach from=$total_money key=key item=item}
	       		{$item}{$key} &nbsp;
	       	{/foreach}     
	 	</div>
 	{/if}
</form>
{pager}
{else}
<div class="extra_info">
	{phrase var='jobposting.no_transaction_found'}
	</div>
{/if}


