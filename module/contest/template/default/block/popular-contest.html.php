
{foreach from=$aPopularContests  name=contest item=aItem}
		{template file='contest.block.contest.side-block-listing-item'}
{/foreach}
{if $iCntPopularContests>$iLimit}
<a href="{url link='contest'}sort_most-viewed/" class="yc_view_more"> {phrase var='contest.view_more'}</a>
{/if}