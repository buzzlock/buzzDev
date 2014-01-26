

{foreach from=$aPremiumContests  name=contest item=aItem}
		{template file='contest.block.contest.side-block-listing-item'}
{/foreach}
{if $iCntPremiumContests>$iLimit}
<a href="{url link='contest'}view_premium/" class="yc_view_more"> {phrase var='contest.view_more'}</a>
{/if}