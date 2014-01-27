<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

?>
<div class="ynfr-campaign-goal">
	<div>
		<p class="ynfr-mn">{$aCampaign.total_amount_text}</p>
		<p>{phrase var='fundraising.total_amount_raised_of_financial_goal_goal' total_amount='' financial_goal=$aCampaign.financial_goal_text} </p>
	</div>
	<div class="ynfr-highligh-detail">
            <div class="meter-wrap-l">
				<div class="meter-wrap-r">
					<div class="meter-wrap">
						<div class="meter-value" style="width: {$aCampaign.financial_percent}">
							{$aCampaign.financial_percent}
						</div>
					</div>
				</div>
            </div>
			{if isset($aCampaign.remain_time)}
				<div class="ynfr-time">{$aCampaign.remain_time}</div>
			{/if}
    </div>
	<div class="ynfr-info">
		{$aCampaign.total_donor} {phrase var='fundraising.donors_lower'} - {$aCampaign.total_like} {phrase var='fundraising.likes_lower'} - {$aCampaign.total_view} {phrase var='fundraising.views_lower'}
	</div>
	{if $aCampaign.status == 1}
	<div class="ynfr-donate">		
		<div id="sign_now">
			<a href="{url link='fundraising.donate' id=$aCampaign.campaign_id}" >{phrase var='fundraising.donate'}</a>

		</div>
	
	</div>
	{/if}
</div>
