<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
{if $aInterestedListings !== NULL}
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
        max-width: 100px;
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

    .row_title_image_header_body .js_user_name_link_admin a,.user_profile_link_span a, .js_hover_title {
        color: #808080;
    }
</style>
{/literal}

<div class="viewed_listing">
	{foreach from=$aInterestedListings key=iKey item=aInterestedListing}
	   <div class="view_content_listing">
	        <div class="row_listing_image">
				<a title="{$aListing.title|clean|parse}" href="{$aInterestedListing.url}">
                    {if $aInterestedListing.image_path != NULL}
                        {img server_id=$aInterestedListing.server_id path='core.url_pic' file='advancedmarketplace/'.$aInterestedListing.image_path suffix='_120' max_width=60 max_height=60 }
                    {else}
                        <img title="{$aListing.title}" src="{$corepath}module/advancedmarketplace/static/image/default/noimage.png" max-width='60' max-height='60' style="max-height: 60px; max-width: 60px; margin-right: 5px;" />
                    {/if}
				</a>
	        </div>
	        <div class="row_title_info">
	            <a class="advlink" title="{$aInterestedListing.title|clean|parse}" href="{$aInterestedListing.url}">{$aInterestedListing.title|shorten:50:'...'|split:25}</a>
	            <div class="advancedmarketplace_price_tag">
					{if $aInterestedListing.price == '0.00'}
					{phrase var='advancedmarketplace.free'}
					{else}
					{$aInterestedListing.currency_id|currency_symbol}{$aInterestedListing.price}
					{/if}
				</div>
	            <div class="detail_listing_info">{$aInterestedListing.time_stamp|convert_time} <span>&middot;</span> {$aInterestedListing|user} <span>&middot;</span> <a class="js_hover_title" href="{url link='advancedmarketplace' location=$aInterestedListing.country_iso}">{$aInterestedListing.country_iso|location}<span class="js_hover_info">{if !empty($aInterestedListing.city)} {$aInterestedListing.city|clean} &raquo; {/if}{if !empty($aInterestedListing.country_child_id)} {$aInterestedListing.country_child_id|location_child} &raquo; {/if} {$aInterestedListing.country_iso|location}</span></a></div>
	        </div>
	    </div>
	    <div class="clear"></div>
    {/foreach}
    {if $bIsViewMore}<div class="view_more_link"><a href="{url link='advancedmarketplace.search' interesting=$aListing.listing_id}" title="{phrase var='advancedmarketplace.view_more'}">{phrase var='advancedmarketplace.view_more'}</a></div>{/if}
</div>
{/if}