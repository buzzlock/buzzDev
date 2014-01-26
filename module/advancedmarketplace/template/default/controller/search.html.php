<?php

defined('PHPFOX') or exit('NO DICE!');
?>
{literal}
<script language="javascript" type="text/javascript">
	$Behavior.advmarket_indexaction = function(){
		if($("#jhslider").size() > 0) {
			$($(".header_bar_float").get(2)).hide();
			$($(".header_bar_float").get(1)).hide();
		}
	};
</script>
{/literal}
{if !count($aListings)}
<div class="extra_info">
    {phrase var='advancedmarketplace.no_advancedmarketplace_listings_found'}
</div>
{else}
{literal}
<style type="text/css">
	.view_more_link {
		background-position: 64% 60% !important;
		padding-left: 170px !important;
	}
</style>

{/literal}

{foreach from=$aListings name=listings item=aListing}
<div id="js_mp_item_holder_{$aListing.listing_id}" class="js_listing_parent {if $aListing.is_sponsor}row_sponsored {/if}{if $aListing.is_featured}row_featured {/if}{if $aListing.view_id == 1 && !isset($bIsInPendingMode)}row_moderate {/if}{if is_int($phpfox.iteration.listings)}row1{else}row2{/if}{if $phpfox.iteration.listings == 1} row_first{/if}">

    {if $aListing.view_id == '1' && !isset($bIsInPendingMode)}
    <div class="row_moderate_info">
        {phrase var='advancedmarketplace.pending_approval'}
    </div>
    {/if}		

    {if !Phpfox::isMobile()}
    <div class="row_title_image_header">

        {if isset($sView) && $sView == 'featured'}
        {else}
        <div id="js_featured_phrase_{$aListing.listing_id}" class="row_featured_link"{if !$aListing.is_featured} style="display:none;"{/if}>
             {phrase var='advancedmarketplace.featured'}
    </div>					
    {/if}	
    <div id="js_sponsor_phrase_{$aListing.listing_id}" class="js_sponsor_event row_sponsored_link"{if !$aListing.is_sponsor} style="display:none;"{/if}>
         {phrase var='advancedmarketplace.sponsored'}
</div>					

<a href="{$aListing.url}">
    {if $aListing.image_path != NULL}
        {img title=$aListing.title server_id=$aListing.server_id path='core.url_pic' file='advancedmarketplace/'.$aListing.image_path suffix='_120' max_width=120 max_height=90 }
    {else}
        <img title="{$aListing.title}" src="{$corepath}module/advancedmarketplace/static/image/default/noimage.png" style="max-width:120px; max-height:90px" max-width='120' max-height='90' />
    {/if}
</a>
	
	<div class="listing_rate" style="">
		<div>
			<?php for($i = 1; $i <= floor($this->_aVars["aListing"]["rating"] / 2); $i++) {ldelim} ?>
				<img src="{$corepath}module/advancedmarketplace/static/image/default/staronsm.png" />
			<?php {rdelim} ?>
			<?php for($i = 1; $i <= ceil(5 - $this->_aVars["aListing"]["rating"] / 2); $i++) {ldelim} ?>
				<img src="{$corepath}module/advancedmarketplace/static/image/default/staroffsm.png" />
			<?php {rdelim} ?>
		</div>
		<div>
			{$aListing.rating_count} {phrase var='advancedmarketplace.review_s'}
		</div>
	</div>
</div>				
<div class="row_title_image_header_body" style="min-height: 160px!important;">				
    {/if}
    <div class="row_title">				


        <div class="row_title_image">
            {img user=$aListing suffix='_50_square' max_width='50' max_height='50'}

            {if ($aListing.user_id == Phpfox::getUserId() && Phpfox::getUserParam('advancedmarketplace.can_edit_own_listing')) || Phpfox::getUserParam('advancedmarketplace.can_edit_other_listing')
            || ($aListing.user_id == Phpfox::getUserId() && Phpfox::getUserParam('advancedmarketplace.can_delete_own_listing')) || Phpfox::getUserParam('advancedmarketplace.can_delete_other_listings')
            || (Phpfox::getUserParam('advancedmarketplace.can_feature_listings'))
            }
            <div class="row_edit_bar_parent">
                <div class="row_edit_bar_holder">
                    <ul>
                        {template file='advancedmarketplace.block.menu'}
                    </ul>			
                </div>
                <div class="row_edit_bar">				
                    <a href="#" class="row_edit_bar_action"><span>{phrase var='advancedmarketplace.actions'}</span></a>							
                </div>
            </div>		
            {/if}						

            {if Phpfox::getUserParam('event.can_approve_events') || Phpfox::getUserParam('event.can_delete_other_event')}<a href="#{$aListing.listing_id}" class="moderate_link" rel="advancedmarketplace">Moderate</a>{/if}
        </div>
        <div class="row_title_info">		

            <a href="{$aListing.url}" class="advlink" title="{$aListing.title|clean}">{$aListing.title|clean|shorten:100:'...'|split:25}</a>{if $aListing.view_id == '2'}<span class="advancedmarketplace_item_sold">({phrase var='advancedmarketplace.sold'})</span>{/if}
			{if $aListing.post_status == 2}
				<div>{phrase var='advancedmarketplace.draft_info'}</div>
			{/if}
            <div class="advancedmarketplace_price_tag">
                {if $aListing.price == '0.00'}
                {phrase var='advancedmarketplace.free'}
                {else}
                {$aListing.currency_id|currency_symbol}{$aListing.price}
                {/if}
            </div>																

            <div class="extra_info">
                <ul class="extra_info_middot"><li>{$aListing.time_stamp|convert_time}</li><li><span>&middot;</span></li><li>{$aListing|user}</li><li>&middot;</li><li><a class="js_hover_title" href="{url link='advancedmarketplace' location=$aListing.country_iso}">{$aListing.country_iso|location}<span class="js_hover_info">{if !empty($aListing.city)} {$aListing.city|clean} &raquo; {/if}{if !empty($aListing.country_child_id)} {$aListing.country_child_id|location_child} &raquo; {/if} {$aListing.country_iso|location}</span></a></li></ul>
            </div>

            {if Phpfox::isMobile()}
            <a href="{$aListing.url}">
                {if $aListing.image_path != NULL}
                    {img server_id=$aListing.server_id title=$aListing.title path='core.url_pic' file='advancedmarketplace/'.$aListing.image_path suffix='_120' max_width='120' max_height='120'}
                {else}
                    <img title="{$aListing.title}" src="{$corepath}module/advancedmarketplace/static/image/default/noimage.png" style="max-width:120px; max-height:120px" max-width='120' max-height='120' />
                {/if}
            </a>
            {/if}

            <div class="item_content">
                {$aListing.description|strip_tags|parse|highlight:'search'|split:25|shorten:200:'advancedmarketplace.see_more':true}				
            </div>							

            {module name='feed.comment' aFeed=$aListing.aFeed aListing=$aListing}				

        </div>			


    </div>	
    {if !Phpfox::isMobile()}
</div>
{/if}
<div class="clear"></div>				
</div>
{/foreach}

{if Phpfox::getUserParam('advancedmarketplace.can_delete_other_listings') || Phpfox::getUserParam('advancedmarketplace.can_feature_listings') || Phpfox::getUserParam('advancedmarketplace.can_approve_listings')}
{moderation}
{/if}

{pager}
{/if}
