<?php
/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');
?>

{if !phpfox::isMobile()}
	
<script type="text/javascript">
{literal}
$Behavior.ynfHomepageSlide = function(){
	$(function(){
		var startSlide = 1;
			
		$('#ynfr_slides').slides({
			preload: true,
			play: 7000,
			pause: 2500,
			hoverPause: true,
			generatePagination: false,
			start: startSlide,
		});
	});
}

$Behavior.ynfundraisingOverrideLoadInit = function() {
	$Core.loadInit = ynfundraising.overridedLoadInit;
}
{/literal}
</script>
<div id="ynfr_slides">

	<div class="slides_container ynfr_featured_slides_container">
		{foreach from=$aFeaturedCampaigns item=aCampaign name=fundraising}

		<div class="ynfr-feature">
			<div class="ynfr-feature-info">
				<a href="{permalink module='fundraising' id=$aCampaign.campaign_id title=$aCampaign.title}" title="{$aCampaign.title|clean}">
					<span style="background:url({img return_url=true server_id=$aCampaign.server_id path='core.url_pic' file=$aCampaign.image_path suffix='_240' max-height=161 max-width=240}) no-repeat top center;display:block;height:161px;width:240px;text-indent:-99999px">			
						{*img server_id=$aCampaign.server_id path='core.url_pic' file=$aCampaign.image_path suffix='_120' max_width='120' max_height='120' class='js_mp_fix_width'}
					</span>
				</a>
				<div>
					<p id="js_fundraising_edit_title{$aCampaign.campaign_id}" class="ynfr-title">
						<a href="{permalink module='fundraising' id=$aCampaign.campaign_id title=$aCampaign.title}" id="js_fundraising_edit_inner_title{$aCampaign.campaign_id}" class="link ajax_link">{$aCampaign.title|clean|shorten:40:'...'|split:20}</a>
					</p>
					<p>{phrase var='fundraising.created_by'} <a href="javascript:void(0)">{$aCampaign|user}</a></p>
					<p><span class="total_sign">{$aCampaign.total_donor}</span>{phrase var='fundraising.total_donor' total_donor=''} - {phrase var='fundraising.total_like' total_like=$aCampaign.total_like} - {phrase var='fundraising.total_view' total_view=$aCampaign.total_view}</p>
					<p class="ynfr-short-des">{$aCampaign.short_description|clean|shorten:160:'...'|split:30}</p>
				</div>
			</div>
			<div class="ynfr-feature-donated">
				<div class="extra_info">
					<p class="ynfr-m"><span>{$aCampaign.total_amount_text} {phrase var='fundraising.raised_upper'}</span><span>{$aCampaign.financial_goal_text} {phrase var='fundraising.goal_upper'}</span></p>
					<div class="meter-wrap-l">
						<div class="meter-wrap-r">
							<div class="meter-wrap">
								<div class="meter-value" style="width: {$aCampaign.financial_percent}">
									{$aCampaign.financial_percent}
								</div>
							</div>
						</div>
					</div>
					<p class="ynfr-remain">

						{if isset($aCampaign.remain_time)}
							{$aCampaign.remain_time}
						{/if}
					</p>
				</div>
				<div class="ynfr-donor">		
					<p class="ynfr-thankyou-donor"><span>{phrase var='fundraising.thankyou_donors'}</span></p>
					{if $aCampaign.donor_list}
						{foreach from=$aCampaign.donor_list item=aUser}
							{module name='fundraising.campaign.user-image-entry'}
						{/foreach}
					{else}
					<div class="ynfr-be-the-first-phrase">
						<a href="{permalink module='fundraising' id=$aCampaign.campaign_id title=$aCampaign.title}" > {phrase var='fundraising.be_the_first_donor_of_this_campaign'} </a>
					</div>
					{/if}

				</div>  
			</div>
		</div>
		{/foreach}
	</div>
	<a href="#" class="ynfr-prev prev">Previous</a>
	<a href="#" class="ynfr-next next">Next</a>
</div>
{else}
{template file='fundraising.block.mobile.featured-slideshow'}

{/if}
<div class="clear"></div>