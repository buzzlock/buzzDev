<?php 
/**
 * [PHPFOX_HEADER]
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
{literal}
<style type="text/css">
	.item_tag{
		display: none !important;
	}
	.item_tag_holder{
		border-top: none !important;
		margin-top: 0 !important;
		padding-top: 0 !important;
	}
</style>
{/literal}

<script type="text/javascript">
	$Behavior.ynfrRateCampaign = function() {l}
		$Core.rate.init({l}
			module: 'fundraising', 
			display: {$aFrRatingParams.display}, 
			error_message: '{$aFrRatingParams.error_message}'
		{r}); 	
	{r}
	 
</script>
<div class="item_view">	
	{if $aLastCategory}
		<h2>
			<p style="color:gray;font-size:12px">{phrase var='fundraising.category'} :  <a href="{$aLastCategory[1]}"> {$aLastCategory[0]} </a> </p>
		</h2>
	{/if}
	{if $aCampaign.is_approved != 1 && !$aCampaign.is_draft}
	<div class="message js_moderation_off" id="js_approve_message">
		{phrase var='fundraising.this_fundraising_is_pending_an_admins_approval'}
	</div>
	{/if}
	
	
	<div class="item_bar">
		<div class="item_bar_action_holder">
			{if $aCampaign.is_approved != 1 && !$aCampaign.is_draft && Phpfox::getUserParam('fundraising.can_approve_campaigns')}
				<a href="#" class="item_bar_approve item_bar_approve_image" onclick="return false;" style="display:none;" id="js_item_bar_approve_image">{img theme='ajax/add.gif'}</a>			
				<a href="#" class="item_bar_approve" onclick="$(this).hide(); $('#js_item_bar_approve_image').show(); $.ajaxCall('fundraising.approve', 'inline=true&amp;id={$aCampaign.campaign_id}'); return false;">{phrase var='fundraising.approve'}</a>
			{/if}
               
				{if $aCampaign.having_action_button}

					<a href="#" class="item_bar_action"><span>{phrase var='fundraising.actions'}</span></a>		
					<ul>
						{template file='fundraising.block.link'}
					</ul>
				{/if}
		</div>		
	</div>
	{if !phpfox::isMobile()}
		{module name='fundraising.campaign.gallery' iCampaignId=$aCampaign.campaign_id}	
		{module name='fundraising.detail' sType=description id=$aCampaign.campaign_id}
	{else}
		{module name='fundraising.images'}	
		{module name='fundraising.detail' sType=description id=$aCampaign.campaign_id}
		{module name='fundraising.detail' sType=donations id=$aCampaign.campaign_id}
		{module name='fundraising.detail' sType=about id=$aCampaign.campaign_id}
	{/if}
	
	
	{plugin call='fundraising.template_controller_view_end'}
	<div id="fundraising_comment_block">
		<div {if $aCampaign.is_approved != 1}style="display:none;" class="js_moderation_on"{/if}>		
			{module name='feed.comment'}
		</div>
	</div>
</div>
<script type="text/javascript">
    $Behavior.setupInviteLayout = function() {l}
    $("#js_friend_search_content").append('<div class="clear" style="padding:5px 0px 10px 0px;"><input type="button" onclick="ClickAll($(this));" value="{phrase var="fundraising.select_all"}" /> </div>');
    $("#js_friend_search_content").parent().parent().css('height','');
    {r}

</script>