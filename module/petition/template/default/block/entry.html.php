<?php 
/**
 * [PHPFOX_HEADER]
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<div id="js_petition_entry{$aItem.petition_id}"{if !isset($bPetitionView)} class="js_petition_parent {if is_int($phpfox.iteration.petition/2)}row1{else}row2{/if}{if $phpfox.iteration.petition == 1 && !PHPFOX_IS_AJAX} row_first{/if}{if $aItem.is_approved != 1} {/if}"{/if}>	
	{if !isset($bPetitionView)}
	{if !phpfox::isMobile()}
	<div class="row_title_image_header">
          {if $aItem.is_approved != 1}
	     <div class="row_pending_link">
		    {phrase var='petition.pending'}
	     </div>
		{elseif $aItem.petition_status == 1}
	     <div class="row_featured_link">
		    {phrase var='petition.closed'}
	     </div>		
          {elseif $aItem.petition_status == 3}
	     <div class="row_sponsored_link">
		    {phrase var='petition.victory'}
	     </div>
          {else}
		<div class="js_featured_petition row_featured_link"{if !$aItem.is_featured} style="display:none;"{/if}>
			{phrase var='petition.featured'}
		</div>
          {/if}
		 
		<a href="{permalink module='petition' id=$aItem.petition_id title=$aItem.title}" title="{$aItem.title|clean}">
			{img server_id=$aItem.server_id path='core.url_pic' file=$aItem.image_path suffix='_120' max_width='120' max_height='120' class='js_mp_fix_width'}
		</a>
	</div>
	
	<div class="row_title_image_header_body">
	{/if}	
		<div class="row_title">	
			<div class="row_title_image">
                        {img user=$aItem suffix='_50_square' max_width=50 max_height=50}
				{if (Phpfox::getUserParam('petition.edit_own_petition') && Phpfox::getUserId() == $aItem.user_id) || Phpfox::getUserParam('petition.edit_user_petition')
					|| (Phpfox::getUserParam('petition.delete_own_petition') && Phpfox::getUserId() == $aItem.user_id) || Phpfox::getUserParam('petition.delete_user_petition')
                              || (defined('PHPFOX_IS_PAGES_VIEW') && Phpfox::getService('pages')->isAdmin('' . $aPage.page_id . ''))
				}	
				<div class="row_edit_bar_parent">
					<div class="row_edit_bar_holder">
						<ul>
							{template file='petition.block.link'}
						</ul>			
					</div>
					<div class="row_edit_bar">				
							<a href="#" class="row_edit_bar_action"><span>{phrase var='petition.actions'}</span></a>							
					</div>
				</div>
				{/if}				
				{if Phpfox::getUserParam('petition.can_approve_petitions') || Phpfox::getUserParam('petition.delete_user_petition') }<a href="#{$aItem.petition_id}" class="moderate_link" rel="petition">{phrase var='petition.moderate'}</a>{/if}			
				
			{if !Phpfox::isMobile() && !Phpfox::isAdminPanel() && Phpfox::getParam('petition.petition_digg_integration')}
			<script type="text/javascript">
				digg_url = '{permalink module='petition' id=$aItem.petition_id title=$aItem.title}';
				digg_window = 'new';
				digg_skin = 'medium';
			</script>
			<script src="http://digg.com/tools/diggthis.js" type="text/javascript"></script>
			{/if}	
			
			</div>	
			
			<div class="row_title_info">			
				<span id="js_petition_edit_title{$aItem.petition_id}">
					<a href="{permalink module='petition' id=$aItem.petition_id title=$aItem.title}" id="js_petition_edit_inner_title{$aItem.petition_id}" class="link ajax_link">{$aItem.title|clean|shorten:55:'...'|split:55}</a>
				</span>
				
				<div class="extra_info">
                                        {phrase var='petition.created_by'} {$aItem|user}{if !defined('PHPFOX_IS_PAGES_VIEW')} {phrase var='petition.in'} <a href="{permalink module='petition.category' id=$aItem.category_id title=$aItem.category_name}">{$aItem.category_name}</a>{/if}
                                        <br/>
										{if $aItem.is_directsign == 1}<span class="total_sign">{$aItem.total_sign}</span>{phrase var='petition.total_sign_signatures' total_sign=''}{else}{phrase var='petition.total_sign_signatures' total_sign=$aItem.total_sign}{/if} - {phrase var='petition.total_like_likes' total_like=$aItem.total_like} - {phrase var='petition.total_view_views' total_view=$aItem.total_view}                                
                                        {plugin call='petition.template_block_entry_date_end'}
				</div>   
				{if phpfox::isMobile()}
					<a href="{permalink module='petition' id=$aItem.petition_id title=$aItem.title}" title="{$aItem.title|clean}">
						{img server_id=$aItem.server_id path='core.url_pic' file=$aItem.image_path suffix='_120' max_width='120' max_height='120' class='js_mp_fix_width'}
					</a>
				{/if}
			
		{/if}	
			<div class="petition_content">
				<div id="js_petition_edit_text{$aItem.petition_id}">	
					<div class="item_content item_view_content">
					{if isset($bPetitionView)}
						{$aItem.description|parse|highlight:'search'|split:55}
						{else}
						<div>
							{$aItem.description|strip_tags|highlight:'search'|split:55|shorten:$iShorten:'...'}
						</div>
					{/if}
					</div>		

					
				</div>
                    			
				{if Phpfox::isModule('tag') && !defined('PHPFOX_IS_PAGES_VIEW') && isset($aItem.tag_list)}
				{module name='tag.item' sType=$sTagType sTags=$aItem.tag_list iItemId=$aItem.petition_id iUserId=$aItem.user_id}
				{/if}
				
				{if !isset($bPetitionView)}
				{module name='feed.comment' aFeed=$aItem.aFeed}
				{/if}
				
				{plugin call='petition.template_block_entry_text_end'}			
			</div>
		
		{plugin call='petition.template_block_entry_end'}	
		{if !isset($bPetitionView)}
			</div>					
		</div>
	{if !phpfox::isMobile()}
	</div>	
	{/if}
	{/if}
</div>