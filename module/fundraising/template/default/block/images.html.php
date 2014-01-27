<?php
/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');
?>
{literal}
<script type="text/javascript">  
   $Behavior.FundraisingShowProfileImage = function(){
         $('.js_fundraising_click_image').click(function(){
               var oNewImage = new Image();
               oNewImage.onload = function(){
                     $('#js_marketplace_click_image_viewer').show();
                     $('#js_marketplace_click_image_viewer_inner').html('<img src="' + this.src + '" style="max-width: 580px; max-height: 580px" alt="" />');			
                     $('#js_marketplace_click_image_viewer_close').show();
               };
               oNewImage.src = $(this).attr('href');
               
               return false;
         });
         
         $('#js_marketplace_click_image_viewer_close a').click(function(){
               $('#js_marketplace_click_image_viewer').hide();
               return false;
         });
   }
</script>
{/literal}
<div id="js_marketplace_click_image_viewer" style="width: 600px;">
	<div id="js_marketplace_click_image_viewer_inner">
		{phrase var='fundraising.loading'}
	</div>
	<div id="js_marketplace_click_image_viewer_close">
		<a href="#">{phrase var='fundraising.close'}</a>
	</div>
</div>

<div class="fundraising_large_image">
	
    <a {if !phpfox::isMobile()}class="js_fundraising_click_image no_ajax_link" href="{img return_url=true server_id=$aCampaign.server_id title=$aCampaign.title path='core.url_pic' file=$aCampaign.image_path suffix=''}"{/if}>
		{img server_id=$aCampaign.server_id title=$aCampaign.title path='core.url_pic' file=$aCampaign.image_path suffix='_240' }
		{if $aCampaign.status == 4}
		<div class="ynfr_close_link">
			{phrase var='fundraising.closed'}
		</div>	
		{elseif $aCampaign.status == 2}
		 <div class="ynfr_reached_link">
			{phrase var='fundraising.reached'}
		 </div>
		{elseif $aCampaign.status == 3}
			 <div class="ynfr_close_link">
				{phrase var='fundraising.expired'}
			 </div>
		{/if}
	</a>
</div>

<div class="ynfr profile fundraising_rate_body">
	<div class="ynfr profile fundraising_rate_display">
		{module name='rate.display'}
	</div>

</div>
{if !phpfox::isMobile()}
<div class="ynfr profile detail_link">
    <ul>
		
{if Phpfox::isUser()  && $aCampaign.status == $aCampaignStatus.ongoing && $aCampaign.is_approved == 1}

        <li><a href="#" onclick="$Core.box('fundraising.inviteBlock',800,'&id={$aCampaign.campaign_id}&url={$sUrl}'); return false;">{phrase var='fundraising.invite_friends'}</a></li>

{/if}

{if $aCampaign.status == $aCampaignStatus.ongoing && $aCampaign.is_approved == 1}
	<li><a href="#" onclick="$Core.box('fundraising.getPromoteCampaignBox',650,'&id={$aCampaign.campaign_id}'); return false;">{phrase var='fundraising.promote_campaign'}</a></li>
{/if}

{if Phpfox::isUser() && Phpfox::getUserId() != $aCampaign.user_id && $aCampaign.status == $aCampaignStatus.ongoing && $aCampaign.is_approved == 1}
	{if !$aCampaign.is_followed} 
		 <li id="ynfr_profile_follow_link">
			 <div style="float:left">
            		<a href="#" title ="{phrase var='fundraising.follow_this_campaign'}" onclick="$.ajaxCall('fundraising.follow','campaign_id={$aCampaign.campaign_id}&amp;type=1', 'GET'); return false;">{phrase var='fundraising.follow'}</a>
			 </div>
					<div  style="float:left" title="{phrase var='fundraising.you_will_receive_updated_information'}">
						
						<div class="ynfr-question-tooltip js_hover_title" ></div>
					</div>
            	</li>		
	
	{else}
			<li id="ynfr_profile_follow_link"><a href="#" title ="{phrase var='fundraising.un_follow_this_campaign'}"onclick="$.ajaxCall('fundraising.follow','campaign_id={$aCampaign.campaign_id}&amp;type=0', 'GET'); return false;">{phrase var='fundraising.un_follow'}</a></li>
		{/if}
    </ul>
{/if}
</div>
{/if}
<div class="clear"> </div>
{if phpfox::isMobile()}
{module name='fundraising.campaign.side-campaign-goal'}
{/if}

