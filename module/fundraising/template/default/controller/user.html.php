<?php
/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');
?>

<style type="text/css">
.ynfr-user p{l}
	color:#000;
{r}	
</style>
<div class="ynfr-alluser">
	<h2>
		
		{if $sView == 'donor'} 
			<span>{$iTotal} {phrase var='fundraising.donors_with_upper'}</span>
		{/if}
		{if $sView == 'supporter'} 
			<span>{$iTotal} {phrase var='fundraising.supporters_with_upper'}</span>
		{/if}
		<ul class="ynfr-user-option"> 
			<li><a {if $sView == 'donor'} class="active" {/if} href="{url link='fundraising.user' view='donor' id=$iCampaignId}">{phrase var='fundraising.donors_upper'} </a></li>
			<li><a {if $sView == 'supporter'} class="active" {/if}  href="{url link='fundraising.user' view='supporter' id=$iCampaignId}">{phrase var='fundraising.supporters'}</a></li>
		</ul>
	</h2>

	{if !count($aUsers)} 
	<div class="clear"></div>
	
	<div class="extra_info">
		{phrase var='fundraising.no_user_found'}
	</div>

	{else}

	<div class="clear"></div>
	{foreach from=$aUsers item=aUser name=aUser}
		{template file='fundraising.block.campaign.user-entry'}
	{/foreach}


	<div class="clear"></div>

	{pager}
	{/if}

</div>
