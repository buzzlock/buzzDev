<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

?>

{foreach from=$aDonors item=aUser name=aUser}
	{template file='fundraising.block.campaign.user-entry'}
{/foreach}


{if count($aDonors) > 0}
	<div class="ynfr_view_more_link"> <a href="{url link='fundraising.user' view='donor' id=$iCampaignId}"> {phrase var='fundraising.view_all'} </a> </div>
{/if}