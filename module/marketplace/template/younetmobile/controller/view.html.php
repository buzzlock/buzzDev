{if (Phpfox::getParam('marketplace.days_to_expire_listing') > 0) && ( $aListing.time_stamp < (PHPFOX_TIME - (Phpfox::getParam('marketplace.days_to_expire_listing') * 86400)) )}
	<div class="error_message">
		{phrase var='marketplace.listing_expired_and_not_available_main_section'}
	</div>
{/if}
{if $aListing.view_id == '1'}
<div class="message js_moderation_off">
	{phrase var='marketplace.listing_is_pending_approval'}
</div>
{/if}

<div class="ynmb_event_img">{img server_id=$aListing.server_id title=$aListing.title path='marketplace.url_image' file=$aListing.image_path suffix='_120' max_width='120' max_height='75' itemprop='image'}</div>

{if ($aListing.user_id == Phpfox::getUserId() && Phpfox::getUserParam('marketplace.can_edit_own_listing')) || Phpfox::getUserParam('marketplace.can_edit_other_listing')
	|| ($aListing.user_id == Phpfox::getUserId() && Phpfox::getUserParam('marketplace.can_delete_own_listing')) || Phpfox::getUserParam('marketplace.can_delete_other_listings')
	|| (Phpfox::getUserParam('marketplace.can_feature_listings'))
}
<div class="item_bar ynmb_item_bar">
	<div class="item_bar_action_holder">
	{if (Phpfox::getUserParam('marketplace.can_approve_listings') && $aListing.view_id == '1')}
		<a href="#" class="item_bar_approve item_bar_approve_image" onclick="return false;" style="display:none;" id="js_item_bar_approve_image">{img theme='ajax/add.gif'}</a>			
		<a href="#" class="item_bar_approve" onclick="$(this).hide(); $('#js_item_bar_approve_image').show(); $.ajaxCall('marketplace.approve', 'inline=true&amp;listing_id={$aListing.listing_id}'); return false;">{phrase var='marketplace.approve'}</a>
	{/if}
		<a href="#" class="item_bar_action"><span>{phrase var='marketplace.actions'}</span></a>	
		<ul>
			{template file='marketplace.block.menu'}
		</ul>			
	</div>
	
</div>
{/if}
<div class="marketplace_price_tag" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
        <span itemprop="price">
        {if $aListing.price == '0.00'}
            {phrase var='marketplace.free'}
        {else}
            {$aListing.currency_id|currency_symbol}{$aListing.price|number_format:2}
        {/if}
        </span>
    </div>  
<div class="item_view">
	{module name='marketplace.info'}

	{plugin call='marketplace.template_default_controller_view_extra_info'}

	<div {if $aListing.view_id != 0}style="display:none;" class="js_moderation_on"{/if}>
		{module name='feed.comment'}
	</div>
</div>