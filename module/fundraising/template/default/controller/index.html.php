<?php 
/**
 * [PHPFOX_HEADER]
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>


{if $bInHomepage}
		{module name='fundraising.homepage.featured-slideshow'}
		
		{module name='fundraising.homepage.most-donated'}

		{module name='fundraising.homepage.most-liked'}

		{module name='fundraising.homepage.latest'}
	
	

{else}

	{if !count($aItems)}
	<div class="extra_info">
		{phrase var='fundraising.no_fundraisings_found'}
	</div>
	{else}

	{foreach from=$aItems  name=fundraising item=aCampaign}
		{if !phpfox::isMobile()}
			{template file='fundraising.block.campaign.entry'}
		{else}
			{template file='fundraising.block.mobile.mobile-entry'}
		{/if}
	{/foreach}
	<div class="clear"></div>
	{if Phpfox::getUserParam('fundraising.can_approve_campaigns') || Phpfox::getUserParam('fundraising.delete_user_campaign')}
	{/if}

	{pager}
	{/if}
{/if}
