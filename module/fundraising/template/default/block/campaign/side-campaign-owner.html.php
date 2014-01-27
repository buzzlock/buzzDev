<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

?>
<script type="text/javascript">
	$Behavior.ynfrRateCampaignOwner = function() {l}
		$Core.rate.init({l}
			module: 'fundraising_owner', 
			display: false, 
			error_message: '{phrase var='fundraising.you_can_not_rate_this'}'
		{r}); 	
	{r}
	 
</script>

<div class="ynfr campaign_owner full_name">
	<a href="{url link=''$aCampaign.user_name''}">
		{$aCampaign.full_name|shorten:20:'...'|split:20}
	</a>
</div>
{$sCampaignOwnerImage}

<div class="ynfr profile fundraising_rate_body">
	<div class="ynfr campaign_owner rate_display">
		{module name='rate.display'}
	</div>
				
</div>