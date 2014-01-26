<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
{literal}
<style type="text/css">
    div#content #js_block_border_advancedmarketplace_listingdetail div.menu
    {
        height:34px;
        background: #ececec;
        border-bottom: #dfdfdf;
    }
    div#content #js_block_border_advancedmarketplace_listingdetail div.menu ul
    {
        padding-left: 10px;
    }
    div#content #js_block_border_advancedmarketplace_listingdetail div.menu ul li a
    {
        line-height: 33px;
        font-size: 14px;
        color: #000;
    }
    div#content #js_block_border_advancedmarketplace_listingdetail div.menu ul li.active
    {
        background: url({/literal}{$corepath}{literal}module/advancedmarketplace/static/image/default/menu-l.png) no-repeat;
    padding-left: 14px;
    margin-top: -4px;
    }
    div#content #js_block_border_advancedmarketplace_listingdetail div.menu ul li.active a
    {
        background: url({/literal}{$corepath}{literal}module/advancedmarketplace/static/image/default/menu-r.png) no-repeat 100% 0;
    display: block;
    line-height: 38px;
    padding-right: 22px;
    }
    div#content #js_block_border_advancedmarketplace_listingdetail div.menu ul li a
    {
        border:none;
        border-radius:0;
        background: none;
    }
    .short_description_title
    {
        background: url({/literal}{$corepath}{literal}module/advancedmarketplace/static/image/default/border.png) repeat-x bottom;
    margin: 20px 0;
    }
    .description_title
    {
        background: #fff;
        font-size: 14px;
        padding: 4px 19px 4px 0;
    }
    .listing_detail
    {
        padding-left: 10px;
    }
    .short_description_content
    {
        color: #7B7B7B;
        font-size: 12px;
    }
    .short_description_content table tr td
    {
        height: 20px;
        width: 135px;
    }
	
	#yn_advmarket_wrapper .content {
		
	}
</style>    
{/literal}
<div id="yn_advmarket_wrapper">
	<!---Short description -->
	<div class="listing_detail">
		<div class="short_description">
			<div class="short_description_title"><span class="description_title">{phrase var='advancedmarketplace.short_description'}</span></div>
			<div class="short_description_content">
				{$aListing.short_description|parse}
			</div>
		</div>
	</div>
	<!---Listing information -->
	<div class="listing_detail">
		<div class="short_description">
			<div class="short_description_title"><span class="description_title">{phrase var='advancedmarketplace.listing_information'}</span></div>
			<div class="short_description_content">
				<table>
					<tr>
						<td>{phrase var='advancedmarketplace.posted_on'}:</td><td>{$aListing.time_stamp|date:'advancedmarketplace.advancedmarketplace_view_time_stamp'}</td>
					</tr>
					{if is_array($aListing.categories) && count($aListing.categories)}
						<tr>
							<td>{phrase var='advancedmarketplace.category'}:</td><td>{$aListing.categories|category_display}</td>
						</tr>		
					{/if}	
					<tr>
						<td>{phrase var='advancedmarketplace.posted_by'}:</td><td>{$aListing|user}</td>
					</tr>
					<tr>
						<td>{phrase var='advancedmarketplace.location'}:</td><td>
							{(if !empty($aListing.location)}
								<div class="p_2">{$aListing.location|clean}</div>
							{/if}
							{{if !empty($aListing.address)}
								<div class="p_2">{$aListing.address|clean}</div>
							{/if}			
							{if !empty($aListing.city)}
								<div class="p_2">{$aListing.city|clean}</div>
							{/if}					
							{if !empty($aListing.postal_code)}
								<div class="p_2">{$aListing.postal_code|clean}</div>
							{/if}
							{$aListing.country_iso|location}
							{if !empty($aListing.country_child_id)}
							<div class="p_2">&raquo; {$aListing.country_child_id|location_child}</div>
							{/if}
							{*{if !empty($aListing.city)}
							<div class="p_2">&raquo; {$aListing.city|clean|split:50} </div>
							{/if}*}		
						</td>
					</tr>
				</table>
			</div>
			{if isset($aListing.map_location) && $aListing.map_location != ""}
				<div style="width:390px; height:170px; position:relative;">
					<div style="margin-left:-8px; margin-top:-8px; position:absolute; background:#fff; border:8px blue solid; width:12px; height:12px; left:50%; top:50%; z-index:200; overflow:hidden; text-indent:-1000px; border-radius:12px;">Marker</div>
					<a href="http://maps.google.com/?q={$aListing.map_location}" target="_blank" title="{phrase var='advancedmarketplace.view_this_on_google_maps'}"><img src="http://maps.googleapis.com/maps/api/staticmap?center={$aListing.map_location}&amp;zoom=16&amp;size=390x170&amp;sensor=false&amp;maptype=roadmap" alt="" /></a>
				</div>		
				<div class="p_top_4">					
					<a href="http://maps.google.com/?q={$aListing.map_location}" target="_blank">{phrase var='advancedmarketplace.view_this_on_google_maps'}</a>
				</div>
			{/if}	
		</div>
	</div>
	{if count($aCustomFields) > 0}
		{module name="advancedmarketplace.frontend.viewcustomfield" aCustomFields=$aCustomFields cfInfors=$cfInfors}
	{/if}
	<div class="listing_detail">
		<div class="short_description">
			<div class="short_description_title"><span class="description_title">{phrase var='advancedmarketplace.over_view'}</span></div>
			<div class="short_description_content">
				{$aListing.description|parse}
			</div>
		</div>
	</div>
</div>