<div class="yc large_item  ycs_item_list list_items_blogmusic image_hover_holder">
	<div class="yc_view_image">
		{img user=$aEntry suffix='_50_square'}
		
		{if $aEntry.status_entry==0}
		<span class="small_pending">{phrase var='contest.pending'}</span>
		{elseif $aEntry.status_entry==2}
		<span class="small_pending denied">{phrase var='contest.denied'}</span>
		{elseif $aEntry.status_entry==3}
		<span class="small_pending draft">{phrase var='contest.draft'}</span>
		{/if}
	</div>
	<div class="large_item_info">
		<div>
			<a class="small_title" href="{permalink module='contest' id=$aEntry.contest_id title=$aEntry.contest_name}entry_{$aEntry.entry_id}/" title="{$aEntry.title}">
				{$aEntry.title|clean|shorten:18:'...'|split:18}
			</a>
		</div>
        {if (isset($bInHomepage) && $bInHomepage) || (isset($bIsEntryIndex) && $bIsEntryIndex)}
        <p>{phrase var='contest.in'} <a href="{permalink module='contest' id=$aEntry.contest_id title=$aEntry.contest_name}" title="{$aEntry.contest_name}">{$aEntry.contest_name|clean|shorten:25:'...'|split:25}</a></p>
        {/if}
        {if isset($sView) && $sView == 'winning'}
		<div class="extra_info">
			<p title="{$aEntry.award|clean}">{$aEntry.award|clean|shorten:50:'...'|split:50}</p>
		</div>
        {/if}
		<div class="large_item_action">
			<div class="ycvotes">
				<p>{$aEntry.total_vote}</p>
			</div>
			<div class="ycviews">
				<p>{$aEntry.total_view}</p>
			</div>
		</div>
	</div>
	{if isset($sView) && $sView == 'winning'}
	<div class="entries_win">
		{$aEntry.rank}
	</div>
	{/if}
    {if isset($sView) && $sView=='pending_entries' && $aEntry.have_action_on_entry}
	<a href="#" class="image_hover_menu_link">{phrase var='contest.link'}</a>
	<div class="image_hover_menu">
		<ul>
			{template file='contest.block.entry.action-link'}
		</ul>			
	</div>
	{/if}
	{if isset($showaction) && $showaction==true && (!isset($is_hidden_action) || $is_hidden_action!=1)}
	<div class="video_moderate_link">
		<a href="#{$aEntry.entry_id}" class="moderate_link" rel="contestentry">{phrase var='contest.moderate'}</a>					  
	</div>
	{/if}
</div>
