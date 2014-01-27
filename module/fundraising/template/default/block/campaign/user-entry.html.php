<?php 
/**
 * [PHPFOX_HEADER]
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<div class="ynfr-user {if ($phpfox.iteration.aUser%3)==2}item-middle{/if}">
	{module name='fundraising.campaign.user-image-entry'}	
	<div>
		<p> {if !isset($aUser.is_guest)}
				{$aUser|user|shorten:20:'...'|split:20}
			{* if guest is set -> in donor list *}
			{elseif !$aUser.is_guest && !$aUser.is_anonymous}
				{$aUser|user|shorten:20:'...'|split:20}
			{else} 
				{if $aUser.is_anonymous} 
					{phrase var='fundraising.anonymous_upper'} 
				{else}
					{$aUser.donor_name} 
				{/if}
			{/if}
		</p>
		{if isset($aUser.amount) && $aUser.amount > 0 && !isset($aUser.total_donate)}
			<p>{phrase var='fundraising.donated_upper'} {$aUser.amount_text}</p>
		{elseif isset($aUser.total_share)}
			<p>{$aUser.total_share} {phrase var='fundraising.shares_lower'}</p>
		{elseif isset($aUser.total_donate)}
			<p>{phrase var='fundraising.donated_in_total_donate_campaigns' total_donate=$aUser.total_donate}</p>
		{/if}
	</div>
</div>
