<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
{literal}
<style type="text/css">
    .today_listing .large_listing_image img
    {
        max-width: 200px;
        max-height: 200px;
        padding:0 2px;
    }
   .content_listing_view
   {
       text-align: center;
   }
   .listing_rate
   {
       /* background: url({/literal}{$corepath}{literal}module/advancedmarketplace/static/image/default/rate.png); */
       height: 25px;
   }
   .total_view_listing
   {
       font-size: 12px;
       color: #565656;
       padding:10px;
   }
   .yn_detail_feature_listing
   {
		left: 2px;
		margin-top: 2px;
   }
   .yn_detail_sponsor_listing
   {
		left: 2px;
		margin-top: 2px;
   }
{/literal}
{if ((int)PHPFOX::getUserId() !== ((int)$aListing.user_id)) }
   .content_listing_view:hover {l}
		cursor: pointer;
   {r}
   
   .content_listing_view:hover .total_view_listing {l}
		cursor: pointer;
		text-decoration: underline;
   {r}
{/if}
</style>

<div class="today_listing">
    <div class="large_listing_image">
        {if isset($aListing.is_expired)}
    		<div class="row_featured_link yn_detail_feature_listing">
    			{phrase var='advancedmarketplace.expired'}
    		</div>
        {else}
    		{if isset($sView) && $sView == 'featured'}
    		{else}
    		<div id="js_featured_phrase_{$aListing.listing_id}"  class="row_featured_link yn_detail_feature_listing"{if !$aListing.is_featured} style="display:none;"{/if}>
    			{phrase var='advancedmarketplace.featured'}
    		</div>					
    		{/if}	
    		<div id="js_sponsor_phrase_{$aListing.listing_id}" class="row_sponsored_link yn_detail_sponsor_listing"{if !$aListing.is_sponsor} style="display:none;"{/if}>
    			{phrase var='advancedmarketplace.sponsored'}
    		</div>
        {/if}
		{if $aListing.image_path != NULL}
            <a title="{$aListing.title}" class="js_marketplace_click_image no_ajax_link" href="{img return_url=true server_id=$aListing.server_id path='core.url_pic' file='advancedmarketplace/'.$aListing.image_path suffix='_400'}">
                {img title=$aListing.title path='core.url_pic' file='advancedmarketplace/'.$aListing.image_path suffix='_200' server_id=$aListing.server_id max_width='180' max_height='180'}
			</a>
        {else}
            <img title="{$aListing.title}" src="{$corepath}module/advancedmarketplace/static/image/default/noimage.png" max_width='180' max_height='180' style="max-width: 180px; max-height: 180px;" />
        {/if}
	</div>
    <div class="content_listing_view" {if ((int)PHPFOX::getUserId() !== ((int)$aListing.user_id)) }onclick="tb_show('{phrase var="advancedmarketplace.rating"}', $.ajaxBox('advancedmarketplace.ratePopup', 'height=300&width=550&id={$aListing.listing_id}')); return false;"{/if}>
        <div class="listing_rate_detail">
			<?php for($i = 1; $i <= floor($this->_aVars["rating"] / 2); $i++) {ldelim} ?>
				<img src="{$corepath}module/advancedmarketplace/static/image/default/staron.png" />
			<?php {rdelim} ?>
			<?php for($i = 1; $i <= ceil(5 - $this->_aVars["rating"] / 2); $i++) {ldelim} ?>
				<img src="{$corepath}module/advancedmarketplace/static/image/default/staroff.png" />
			<?php {rdelim} ?>
		</div>
        <div class="total_view_listing">{*phrase var='advancedmarketplace.nreview_review_s' nreview=$iRatingCount*}<span class="review-count">{$iRatingCount}</span> {phrase var='advancedmarketplace.review_s'}</div>
    </div>
</div>

