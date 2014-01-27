<?php

/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');


?>
<h2 class="ynfr-title-block"><span>{phrase var='fundraising.most_donated'}</span></h2>
{foreach from=$aMostDonatedCampaigns item=aCampaign name=fundraising}
	{if !phpfox::isMobile()}
		{template file='fundraising.block.campaign.entry'}
	{else}
		{template file='fundraising.block.mobile.mobile-entry'}
	{/if}
{/foreach}

<div class="clear"></div>

<a href="{url link='fundraising' view='ongoing' sort='most-donated'}"> {phrase var='fundraising.view_more'}</a>