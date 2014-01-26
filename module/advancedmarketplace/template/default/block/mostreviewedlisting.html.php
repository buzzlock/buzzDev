<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
{literal}
<style type="text/css">
    .view_content_listing
    {
        margin-bottom: 20px;
    }
    .view_content_listing .row_listing_image
    {
		float: left;
		overflow: hidden;
		width: 65px;
    }
    .view_content_listing .row_listing_image img
    {
        max-width: 60px;
        margin-right: 5px;
    }
    .view_more_link
    {
        background: url("{/literal}{$corepath}{literal}module/advancedmarketplace/static/image/default/view_more.png") no-repeat center left;
        padding-left:13px;
        margin: 10px 0;
    }
    .view_more_link a
    {
        font-size: 13px;
    }
	
	.detail_listing_info {
		padding-left: 5px;
	}
	.listing_rate
	{
		padding-left: 5px;
	}
</style>
{/literal}
	{foreach from=$aReviewedListings key=iKey item=aListing}
    <div class="view_content_listing">
        <div class="row_listing_image">
			<a href="{$aListing.url}" title="{$aListing.title|parse|clean}">
                {if $aListing.image_path != NULL}
                    {img title=$aListing.title server_id=$aListing.server_id path='core.url_pic' file='advancedmarketplace/'.$aListing.image_path suffix='_120' max-width=60 max-height=60 }
                {else}
                    <img title="{$aListing.title}" src="{$corepath}module/advancedmarketplace/static/image/default/noimage.png" max-width='60' max-height='60' />
                {/if}
            </a>
        </div>
        <div class="row_title_info">
            <a class="advlink" href="{$aListing.url}" title="{$aListing.title|parse|clean}">{$aListing.title|shorten:50:'...'|split:25}</a>
            <div class="advancedmarketplace_price_tag">
				{if $aListing.price == '0.00'}
				{phrase var='advancedmarketplace.free'}
				{else}
				{$aListing.currency_id|currency_symbol}{$aListing.price}
				{/if}
			</div>
            <div class="detail_listing_info">{$aListing.time_stamp|convert_time} <span>&middot;</span> {$aListing|user} <span>&middot;</span> <a class="js_hover_title" href="{url link='advancedmarketplace' location=$aListing.country_iso}">{$aListing.country_iso|location}<span class="js_hover_info">{if !empty($aListing.city)} {$aListing.city|clean} &raquo; {/if}{if !empty($aListing.country_child_id)} {$aListing.country_child_id|location_child} &raquo; {/if} {$aListing.country_iso|location}</span></a></div>
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
					{phrase var="advancedmarketplace.nreview_review_s" nreview=$aListing.rating_count}
				</div>
			</div>
        </div>
    </div>
    <div class="clear"></div>
    {/foreach}
    {if $bIsViewMore}<div class="view_more_link"><a href="{url link='advancedmarketplace.search.sort_most-reviewed'}">{phrase var='advancedmarketplace.view_more'}</a></div>{/if}
