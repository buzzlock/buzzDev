<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

?>

{foreach from=$aSupporters item=aUser name=aUser}
	{template file='fundraising.block.campaign.user-entry'}
{/foreach}

<div class="clear"> </div>

{if count($aSupporters) > 0} 
	<div class="ynfr_view_more_link"> <a href="{url link='fundraising.user' view='supporter' id=$iCampaignId}"> {phrase var='fundraising.view_all'} </a> </div>
{/if}