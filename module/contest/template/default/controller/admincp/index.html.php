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
<form method="post" action="{url link="admincp.contest"}">
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
		{phrase var='contest.featured'}: 
	</div>
	<div class="table_right">
		{$aFilters.featured} 
	</div>
	<div class="clear"></div>
</div>

<div class="table">
	<div class="table_left">
		{phrase var='contest.premium'}: 
	</div>
	<div class="table_right">
		{$aFilters.premium} 
	</div>
	<div class="clear"></div>
</div>

<div class="table">
	<div class="table_left">
		{phrase var='contest.ending_soon'}: 
	</div>
	<div class="table_right">
		{$aFilters.ending_soon} 
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
<div class="table">
	<div class="table_left">
		{phrase var='contest.sort'}: 
	</div>
	<div class="table_right">
		{$aFilters.sort} {$aFilters.sort_by}
	</div>
	<div class="clear"></div>
</div>
<div class="table_clear">
	<input type="submit" name="search[submit]" value="{phrase var='contest.submit'}" class="button" />
	<input type="submit" name="search[reset]" value="{phrase var='contest.reset'}" class="button" />	

	
</div>
</form>
{pager}
{if count($aContests)}
<form method="post" action="{url link='admincp.contest'}">
	<table colspan='1'>
	<tr>
		<th style="width:20px;"></th>
		<th>{phrase var='contest.contest_name'}</th>
		<th width="60px !important">{phrase var='contest.status'}</th>
		<th>{phrase var='contest.featured'}</th>
		<th>{phrase var='contest.premium'}</th>
		<th>{phrase var='contest.ending_soon'}</th>
		<th width="80px !important">{phrase var='contest.created_user'}</th>
		<th width="80px !important">{phrase var='contest.end_date'}</th>
	</tr>
	{foreach from=$aContests key=iKey item=aContest}        
	<tr id="js_row{$aContest.contest_id}" class="checkRow{if is_int($iKey/2)} tr{else}{/if}">
		<td class="t_center">
			<a href="#" class="js_drop_down_link" title="{phrase var='contest.manage'}">{img theme='misc/bullet_arrow_down.png' alt=''}</a>
			<div class="link_menu">
				<ul>				
					
					<li><a href="{permalink module='contest' id=$aContest.contest_id title=$aContest.contest_name}">{phrase var='contest.view'}</a></li>

					{if $aContest.can_edit_contest}
						<li><a href="{url link="contest.add" id=$aContest.contest_id}">{phrase var='contest.edit'}</a></li>
					{/if}

					{if $aContest.can_approve_deny_contest}
					    <li id="js_contest_approve__{$aContest.contest_id}">
					        <a href="#" title="{phrase var='contest.approve_this_contest'}" onclick="if(confirm('{phrase var='contest.are_you_sure_info'}')) {l} 
						        $('#js_contest_approve__{$aContest.contest_id} a').attr('onclick', 'return false'); 
						        $.ajaxCall('contest.approveContest', '&contest_id={$aContest.contest_id}', 'GET'); 
					        {r} 
					        return false;">{phrase var='contest.approve'}</a>
					    </li>

					    <li id="js_contest_deny_{$aContest.contest_id}">
					        <a href="#" title="{phrase var='contest.deny_this_contest'}" onclick="if(confirm('{phrase var='contest.are_you_sure_info'}')) {l} 
						        $('#js_contest_deny_{$aContest.contest_id} a').attr('onclick', 'return false'); 
						        $.ajaxCall('contest.denyContest', '&contest_id={$aContest.contest_id}', 'GET'); 
					        {r} 
					        return false;">{phrase var='contest.deny'}</a>
					    </li>
					{/if}

					{if $aContest.can_close_contest}
					        <li id="js_contest_close_{$aContest.contest_id}">
								<a href="#" title="{phrase var='contest.close_this_contest'}" onclick="if(confirm('{phrase var='contest.are_you_sure_info'}')) {l} 
									$('#js_contest_close_{$aContest.contest_id} a').attr('onclick', 'return false');  
									$.ajaxCall('contest.closeContest', '&contest_id={$aContest.contest_id}&amp;is_owner=1', 'GET'); 
								{r} 
								return false;">{phrase var='contest.close'}</a>

					        </li>
					{/if}

					{if $aContest.can_delete_contest}
					        <li id="js_contest_close_{$aContest.contest_id}">
								<a href="#" title="{phrase var='contest.delete_this_contest'}" onclick="if(confirm('{phrase var='contest.are_you_sure_info'}')) $.ajaxCall('contest.deleteContest', '&contest_id={$aContest.contest_id}&amp;is_admincp=1', 'GET'); return false;">{phrase var='contest.delete'}</a>

					        </li>
					{/if}

				</ul>
			</div>		
		</td>		
		<td id="js_contest_edit_title{$aContest.contest_id}"><a href="{permalink module='contest' id=$aContest.contest_id title=$aContest.contest_name}" class="quickEdit" id="js_contest{$aContest.contest_id}">{$aContest.contest_name|convert|clean}</a></td>
		<td>{$aContest.contest_status_text}</td>

		{if $aContest.can_feature_contest}
			<!-- Fetured-->
			<td>		
			    <div class="js_item_is_active"{if !$aContest.is_feature} style="display:none;"{/if}>
					<a href="#?call=contest.feature&amp;contest_id={$aContest.contest_id}&amp;type=0&amp;admin=true" class="js_item_active_link" title="{phrase var='contest.un_feature'}">{img theme='misc/bullet_green.png' alt=''}</a>
			    </div>

	             <div class="js_item_is_not_active"{if $aContest.is_feature} style="display:none;"{/if}>
					<a href="#?call=contest.feature&amp;contest_id={$aContest.contest_id}&amp;type=1&amp;admin=true" class="js_item_active_link" title="{phrase var='contest.feature'}">{img theme='misc/bullet_red.png' alt=''}</a>
	             </div>              
			</td>
		{else}
			<td>		              
				   {if $aContest.is_feature}	       
						{img theme='misc/bullet_green.png' alt=''}       
					{else}
					   {img theme='misc/bullet_red.png' alt=''}
				    {/if}
			</td>
		{/if}
			<!-- Premium-->

		{if $aContest.can_premium_contest}
			<td>		
			    <div class="js_item_is_active"{if !$aContest.is_premium} style="display:none;"{/if}>
					<a href="#?call=contest.premium&amp;contest_id={$aContest.contest_id}&amp;type=0&amp;admin=true" class="js_item_active_link" title="{phrase var='contest.un_premium'}">{img theme='misc/bullet_green.png' alt=''}</a>
			    </div>

	             <div class="js_item_is_not_active"{if $aContest.is_premium} style="display:none;"{/if}>
					<a href="#?call=contest.premium&amp;contest_id={$aContest.contest_id}&amp;type=1&amp;admin=true" class="js_item_active_link" title="{phrase var='contest.premium'}">{img theme='misc/bullet_red.png' alt=''}</a>
	             </div>              
			</td>
		{else}
			<td>	{if $aContest.is_premium}	       
						{img theme='misc/bullet_green.png' alt=''}       
					{else}
					   {img theme='misc/bullet_red.png' alt=''}
				    {/if}
			</td>
		{/if}

			<!-- Ending Soon-->
		{if $aContest.can_ending_soon_contest}
			<td>		
			    <div class="js_item_is_active"{if !$aContest.is_ending_soon} style="display:none;"{/if}>
					<a href="#?call=contest.endingSoon&amp;contest_id={$aContest.contest_id}&amp;type=0&amp;admin=true" class="js_item_active_link" title="{phrase var='contest.un_ending_soon'}">{img theme='misc/bullet_green.png' alt=''}</a>
			    </div>

	             <div class="js_item_is_not_active"{if $aContest.is_ending_soon} style="display:none;"{/if}>
					<a href="#?call=contest.endingSoon&amp;contest_id={$aContest.contest_id}&amp;type=1&amp;admin=true" class="js_item_active_link" title="{phrase var='contest.ending_soon'}">{img theme='misc/bullet_red.png' alt=''}</a>
	             </div>              
			</td>
		{else}
			<td>		              
				   {if $aContest.is_ending_soon}	       
						{img theme='misc/bullet_green.png' alt=''}       
					{else}
					   {img theme='misc/bullet_red.png' alt=''}
				    {/if}
			</td>
		{/if}

		<td>{$aContest|user}</td>
		<td>{if $aContest.end_time } {$aContest.end_time|date:'contest.contest_short_date_time_format'}{/if}</td>

		
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