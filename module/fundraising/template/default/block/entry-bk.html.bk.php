<?php 
/**
 * [PHPFOX_HEADER]
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<div id="js_fundraising_entry{$aItem.campaign_id}"{if !isset($bFundraisingView)} class="js_fundraising_parent {if is_int($phpfox.iteration.fundraising/2)}row1{else}row2{/if}{if $phpfox.iteration.fundraising == 1 && !PHPFOX_IS_AJAX} row_first{/if}{if $aItem.is_approved != 1} {/if}"{/if}>	
	{if !isset($bFundraisingView)}
	<div class="row_title_image_header">
          {if $aItem.is_approved != 1}
	     <div class="row_pending_link">
		    {phrase var='fundraising.pending'}
	     </div>
		{elseif $aItem.status == 4}
	     <div class="js_closed_fundraising row_pending_link ynfr_pending_campaign_link">
		    {phrase var='fundraising.closed'}
	     </div>		
          {elseif $aItem.status == 2}
	     <div class="row_sponsored_link ynfr_reached_campaign_link">
		    {phrase var='fundraising.reached'}
	     </div>
          {elseif $aItem.status == 3}
	     <div class="row_sponsored_link ynfr_expired_campaign_link">
		    {phrase var='fundraising.expired'}
	     </div>
		  {elseif $aItem.is_highlighted}
	     <div class="row_sponsored_link ynfr_highlighted_campaign_link ">
		    {phrase var='fundraising.highlight'}
	     </div>
          {else}
		<div class="js_featured_fundraising row_featured_link"{if !$aItem.is_featured} style="display:none;"{/if}>
			{phrase var='fundraising.featured'}
		</div>
          {/if}
		<a href="{permalink module='fundraising' id=$aItem.campaign_id title=$aItem.title}" title="{$aItem.title|clean}">
			{img server_id=$aItem.server_id path='core.url_pic' file=$aItem.image_path suffix='_120' max_width='120' max_height='120' class='js_mp_fix_width'}
		</a>
	</div>
	<div class="row_title_image_header_body">		
		<div class="row_title">	
			<div class="row_title_image">
                        {img user=$aItem suffix='_50_square' max_width=50 max_height=50}
				{if (Phpfox::getUserParam('fundraising.edit_own_campaign') && Phpfox::getUserId() == $aItem.user_id) || Phpfox::getUserParam('fundraising.edit_user_campaign')
					|| (Phpfox::getUserParam('fundraising.delete_own_campaign') && Phpfox::getUserId() == $aItem.user_id) || Phpfox::getUserParam('fundraising.delete_user_campaign')
                              || (defined('PHPFOX_IS_PAGES_VIEW') && Phpfox::getService('pages')->isAdmin('' . $aPage.page_id . ''))
				}	
				<div class="row_edit_bar_parent">
					<div class="row_edit_bar_holder">
						<ul>
							{template file='fundraising.block.link'}
						</ul>			
					</div>
					<div class="row_edit_bar">				
							<a href="#" class="row_edit_bar_action"><span>{phrase var='fundraising.actions'}</span></a>							
					</div>
				</div>
				{/if}				
				{if Phpfox::getUserParam('fundraising.can_approve_campaigns') || Phpfox::getUserParam('fundraising.delete_user_campaign') }<a href="#{$aItem.campaign_id}" class="moderate_link" rel="fundraising">{phrase var='fundraising.moderate'}</a>{/if}			
				
			</div>	
			
			<div class="row_title_info">			
				<span id="js_fundraising_edit_title{$aItem.campaign_id}">
					<a href="{permalink module='fundraising' id=$aItem.campaign_id title=$aItem.title}" id="js_fundraising_edit_inner_title{$aItem.campaign_id}" class="link ajax_link">{$aItem.title|clean|shorten:55:'...'|split:55}</a>
				</span>
				
				<div class="extra_info">
                                        {phrase var='fundraising.created_by'} {$aItem|user}{if !defined('PHPFOX_IS_PAGES_VIEW')} {phrase var='fundraising.in'} <a href="{permalink module='fundraising.category' id=$aItem.category_id title=$aItem.category_title}">{$aItem.category_title}</a>{/if}
                                        <br/>
										{* todo: later{if $aItem.is_directsign == 1}<span class="total_sign"> {$aItem.total_sign}</span>{phrase var='fundraising.total_sign_signatures' total_sign=''}{else}{phrase var='fundraising.total_sign_signatures' total_sign=$aItem.total_sign}{/if}*} - {phrase var='fundraising.total_like_likes' total_like=$aItem.total_like} - {phrase var='fundraising.total_view_views' total_view=$aItem.total_view}                                
                                        {plugin call='fundraising.template_block_entry_date_end'}
				</div>                                
			
		{/if}	
			<div class="fundraising_content">
				<div id="js_fundraising_edit_text{$aItem.campaign_id}">	
					<div class="item_content item_view_content">
					{if isset($bFundraisingView)}
						{$aItem.description|parse|highlight:'search'|split:55}
						{else}
						<div>
							{$aItem.description|strip_tags|highlight:'search'|split:55|shorten:$iShorten:'...'}
						</div>
					{/if}
					</div>			
				</div>
                    			
				
				{plugin call='fundraising.template_block_entry_text_end'}			
			</div>
		
		{plugin call='fundraising.template_block_entry_end'}	
		{if !isset($bFundraisingView)}
			</div>					
		</div>
	</div>	
	{/if}
</div>