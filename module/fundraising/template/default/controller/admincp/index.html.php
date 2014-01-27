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
<form method="post" action="{url link="admincp.fundraising"}">
<div class="table_header">
	{phrase var='fundraising.search_filter'}
</div>
<div class="table">
	<div class="table_left">
		{phrase var='fundraising.search_for_text'}: 
	</div>
	<div class="table_right">
		{$aFilters.search}
	</div>
	<div class="clear"></div>
</div>
<div class="table">
	<div class="table_left">
		{phrase var='fundraising.search_for_user'}: 
	</div>
	<div class="table_right">
		{$aFilters.user}
	</div>
	<div class="clear"></div>
</div>
<div class="table">
	<div class="table_left">
		{phrase var='fundraising.status'}: 
	</div>
	<div class="table_right">
		{$aFilters.status} 
	</div>
	<div class="clear"></div>
</div>

<div class="table">
	<div class="table_left">
		{phrase var='fundraising.featured'}: 
	</div>
	<div class="table_right">
		{$aFilters.featured} 
	</div>
	<div class="clear"></div>
</div>

<div class="table">
	<div class="table_left">
		{phrase var='fundraising.approved'}: 
	</div>
	<div class="table_right">
		{$aFilters.approved} 
	</div>
	<div class="clear"></div>
</div>

<div class="table">
	<div class="table_left">
		{phrase var='fundraising.page'}: 
	</div>
	<div class="table_right">
		{$aFilters.pages} 
	</div>
	<div class="clear"></div>
</div>

<div class="table">
	<div class="table_left">
		{phrase var='fundraising.display'}: 
	</div>
	<div class="table_right">
		{$aFilters.display}
	</div>
	<div class="clear"></div>
</div>
<div class="table">
	<div class="table_left">
		{phrase var='fundraising.sort'}: 
	</div>
	<div class="table_right">
		{$aFilters.sort} {$aFilters.sort_by}
	</div>
	<div class="clear"></div>
</div>
<div class="table_clear">
	<input type="submit" name="search[submit]" value="{phrase var='fundraising.submit'}" class="button" />
	<input type="submit" name="search[reset]" value="{phrase var='fundraising.reset'}" class="button" />	

	
</div>
</form>
{pager}
{if count($aCampaigns)}
<form method="post" action="{url link='admincp.fundraising'}">
	<table colspan='1'>
	<tr>
		<th style="width:20px;"></th>
		<th>{phrase var='fundraising.campaign_name'}</th>
		<th width="60px !important">{phrase var='fundraising.status'}</th>
		<th>{phrase var='fundraising.featured'}</th>
		<th>{phrase var='fundraising.highlight'}</th>
		<th width="80px !important">{phrase var='fundraising.created_user'}</th>
		<th width="80px !important">{phrase var='fundraising.expired_date'}</th>
		<th>{phrase var='fundraising.page'}</th>
		<th width="100px !important">{phrase var='fundraising.fundraising_goal'}</th>
		<th>{phrase var='fundraising.raised_upper'}</th>
	</tr>
	{foreach from=$aCampaigns key=iKey item=aCampaign}        
	<tr id="js_row{$aCampaign.campaign_id}" class="checkRow{if is_int($iKey/2)} tr{else}{/if}">
		<td class="t_center">
			<a href="#" class="js_drop_down_link" title="{phrase var='fundraising.manage'}">{img theme='misc/bullet_arrow_down.png' alt=''}</a>
			<div class="link_menu">
				<ul>				
					
					<li><a href="{permalink module='fundraising' id=$aCampaign.campaign_id title=$aCampaign.title}">{phrase var='fundraising.view'}</a></li>	
					
					{if $aCampaign.can_edit_campaign}	
						<li><a href="{url link='fundraising.add' id=$aCampaign.campaign_id}">{phrase var='fundraising.edit'}</a></li>		
					{/if}
					{if $aCampaign.is_approved == '0'}					
						<li><a href="{url link='admincp.fundraising' approve=$aCampaign.campaign_id}" onclick="return confirm('{phrase var='admincp.are_you_sure' phpfox_squote=true}');">{phrase var='fundraising.approve'}</a></li>	
					{/if}

					<li><a href="{permalink module='fundraising.list' id=$aCampaign.campaign_id }">{phrase var='fundraising.view_statistics'}</a></li>										

					{if $aCampaign.can_delete_campaign}
						<li><a href="{url link='admincp.fundraising' delete=$aCampaign.campaign_id}" onclick="return confirm('{phrase var='admincp.are_you_sure' phpfox_squote=true}');">{phrase var='fundraising.delete'}</a></li>					
					{/if}
					
				</ul>
			</div>		
		</td>		
		<td id="js_fundraising_edit_title{$aCampaign.campaign_id}"><a href="{permalink module='fundraising' id=$aCampaign.campaign_id title=$aCampaign.title}" class="quickEdit" id="js_fundraising{$aCampaign.campaign_id}">{$aCampaign.title|convert|clean}</a></td>
		<td>{$aCampaign.campaign_status_text}</td>
		{if $aCampaign.is_approved == 1 && $aCampaign.status == $aCampaignStatus.ongoing}
		<td>		
		    <div class="js_item_is_active"{if !$aCampaign.is_featured} style="display:none;"{/if}>
				<a href="#?call=fundraising.feature&amp;campaign_id={$aCampaign.campaign_id}&amp;active=0&amp;admin=true" class="js_item_active_link" title="{phrase var='fundraising.un_feature'}">{img theme='misc/bullet_green.png' alt=''}</a>
		    </div>
              <div class="js_item_is_not_active"{if $aCampaign.is_featured} style="display:none;"{/if}>
				<a href="#?call=fundraising.feature&amp;campaign_id={$aCampaign.campaign_id}&amp;active=1&amp;admin=true" class="js_item_active_link" title="{phrase var='fundraising.feature'}">{img theme='misc/bullet_red.png' alt=''}</a>
              </div>                
		</td>
		<td>
			{if $aCampaign.status == $aCampaignStatus.ongoing && $aCampaign.module_id == 'fundraising'}
                <div class="js_item_is_active js_item_directsign_active"{if !$aCampaign.is_highlighted} style="display:none;"{/if}>
				<a href="#?call=fundraising.highlight&amp;campaign_id={$aCampaign.campaign_id}&amp;active=0&amp;admin=true" class="js_item_active_link js_item_directsign_link js_remove_default" title="{phrase var='fundraising.un_highlight_this_campaign'}">{img theme='misc/bullet_green.png' alt=''}</a>
		    </div>
                <div class="js_item_is_not_active js_item_directsign_not_active"{if $aCampaign.is_highlighted} style="display:none;"{/if}>
				<a href="#?call=fundraising.highlight&amp;campaign_id={$aCampaign.campaign_id}&amp;active=1&amp;admin=true" class="js_item_active_link js_item_directsign_link js_remove_default" title="{phrase var='fundraising.highlight_this_campaign'}">{img theme='misc/bullet_red.png' alt=''}</a>
                </div>
			 {else}
			 {img theme='misc/bullet_red.png' alt=''}
			 {/if}
		</td>
		{else}
		<td>		              
			{img theme='misc/bullet_red.png' alt=''}
		</td>
		<td>
               {img theme='misc/bullet_red.png' alt=''}
		</td>
		{/if}
		<td>{$aCampaign|user}</td>
		<td>{if $aCampaign.end_time } {$aCampaign.end_time|date:'fundraising.short_date_time_format'} {else} {phrase var='fundraising.unlimited_upper'} {/if}</td>
		
		<td>
			{if $aCampaign.module_id == 'pages'}
				<a href="{$aCampaign.page_link}">{$aCampaign.page_name}</a>			
			{else}
				{phrase var='fundraising.none'}
			{/if}
		</td>
		
		<td>{$aCampaign.financial_goal_text}</td>
		<td>{$aCampaign.total_amount_text}</td>
		
	</tr>
	{/foreach}
	</table>	
	{else}
	<div class="p_4">
		{phrase var='fundraising.no_campaigns_found'}
	</div>
	{/if}
</form>

<div class="extra_info" style="margin-right: 700px; width: 100px; font-weight:bold; position: absolute">
		{phrase var='fundraising.total_upper'} {$iTotalResults} {phrase var='fundraising.result_s'}
	</div>

{pager}