<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
{if $aSlideShowListing !== NULL}
{if !Phpfox::isMobile()}
<div class="jhslider" id="jhslider" style="opacity: 0;filter: alpha(opacity = 0);">
	<ul>
		{foreach from=$aSlideShowListing key=iKey item=aListing}
		<li>
			<div class="jhslider-info-detail">
				<table>
					<tr>
						<td>
							{img user=$aListing suffix='_50_square' max_width='50' max_height='50'}
						</td>
						<td>
							<div class="rowitem">
								<a title="{$aListing.title|clean|parse}" href="{$aListing.url}"><h3>{$aListing.title|shorten:50:"..."}</h3></a>
							</div>
							<div class="rowitem">
								<span class="price" style="font-size: 10pt;font-weight: bold;">
									{if $aListing.price == '0.00'}
										{phrase var='advancedmarketplace.free'}
									{else}
										{$aListing.currency_id|currency_symbol}{$aListing.price}
									{/if}
								</span>
							</div>
							<div class="rowitem color777">
								{$aListing.time_stamp|convert_time} <span>&middot;</span> {$aListing|user|shorten:20:"..."} <span>&middot;</span> <a class="js_hover_title" href="{url link='advancedmarketplace' location=$aListing.country_iso}">{$aListing.country_iso|location}<span class="js_hover_info">{if !empty($aListing.city)} {$aListing.city|clean} &raquo; {/if}{if !empty($aListing.country_child_id)} {$aListing.country_child_id|location_child} &raquo; {/if} {$aListing.country_iso|location}</span></a>
							</div>
							<div class="rowitem color363636">
								{*$aListing.short_description|parse|highlight:'search'|split:25|shorten:150:'advancedmarketplace.see_more':true*}
								{$aListing.short_description|parse|split:25|shorten:150:'...'}
							</div>
						</td>
					</tr>
				</table>
			</div>
			<div class="jhslider-info-quick">
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td>
							<div class="rowitem">
								<a title="{$aListing.title|clean|parse}" href="{$aListing.url}"><h3>{$aListing.title|shorten:15:"..."}</h3></a>
							</div>
							<div class="rowitem">
								<span class="price" style="font-size: 10pt;font-weight: bold;">
									{if $aListing.price == '0.00'}
										{phrase var='advancedmarketplace.free'}
									{else}
										{$aListing.currency_id|currency_symbol}{$aListing.price}
									{/if}
								</span>
							</div>
							<div class="rowitem color777">
								{$aListing.time_stamp|convert_time} <span>&middot;</span> {$aListing|user|shorten:20:"..."}{* <span>&middot;</span> <a class="js_hover_title" href="{url link='advancedmarketplace' location=$aListing.country_iso}">{$aListing.country_iso|location}<span class="js_hover_info">{if !empty($aListing.city)} {$aListing.city|clean} &raquo; {/if}{if !empty($aListing.country_child_id)} {$aListing.country_child_id|location_child} &raquo; {/if} {$aListing.country_iso|location}</span></a>*}
							</div>
						</td>
					</tr>
				</table>
			</div>
			<a title="{$aListing.title|clean|parse}" class="jhslider-info-link" href="{$aListing.url}">
                {if $aListing.image_path != NULL}
                    {img class='big-image' ref=$aListing.url title=$aListing.title server_id=$aListing.server_id path='core.url_pic' file='advancedmarketplace/'.$aListing.image_path suffix='_400' max_width=360 max_height=220}
                {else}
                    <img class="big-image" ref="{$aListing.url}" title="{$aListing.title}" src="{$corepath}module/advancedmarketplace/static/image/default/noimgbig_2.png" max_width='360' max_height='220' />
                {/if}
            </a>
		</li>
		{/foreach}
	</ul>
</div>
<div class="clear"></div>
{literal}
<script language="javascript" type="text/javascript">
	$Behavior.setupSlideShow = function() {
		setTimeout(function(){
			if($(".jhslider").hasClass("init-ed")) {
				return false;
			}
			$(".jhslider").unbind();
			$(".jhslider").children().unbind();
			
			var x = setTimeout("null", 0);
			for (var i = 0 ; i < x ; i++) {
				clearTimeout(i); 
			}
			$(".jhslider").css({
				"opacity": "1"
			});
			$(".jhslider").JHSlide(5000, 400);
			$(".jhslider").addClass("init-ed");
		}, 100);
	};
</script>
{/literal}
<link rel="stylesheet" type="text/css" href="{$core_url}module/advancedmarketplace/static/css/default/default/jhslide.css" />
<!-- Mobile View-->
{else}
	<div class="recent_listing">
	{foreach from=$aSlideShowListing key=iKey item=aListing}
		<div class="listing_1">
			<div class="row_title_image_header">
				<div class="row_title_image_header">
					<a href="{$aListing.url}" title="{$aListing.title|parse|clean}">
						{if $aListing.image_path != NULL}
                            {img server_id=$aListing.server_id path='core.url_pic' file='advancedmarketplace/'.$aListing.image_path suffix='_120' max_width=90 max_height=90 title=$aListing.title }
						{else}
                            <img title="{$aListing.title}" src="{$corepath}module/advancedmarketplace/static/image/default/noimage.png" style="max-width:90px;max-height:90px" max-width='90' max-height='90' />
						{/if}
					</a>
				</div>
			</div>
			<div class="row_title_image_header_body">
				<div class="row_title">	
					 <div class="row_title_image">
						{img user=$aListing suffix='_50_square' max_width='50' max_height='50'}
					</div>
				</div>
				<div class="row_title_info">
					<a class="advlink" href="{$aListing.url}" title="{$aListing.title|parse|clean}">{$aListing.title|shorten:15:"..."}</a>
                    <div class="advancedmarketplace_price_tag_cs">
                        {if $aListing.price == '0.00'}
                        {phrase var='advancedmarketplace.free'}
                        {else}
                        {$aListing.currency_id|currency_symbol}{$aListing.price}
                        {/if}
                    </div>	
					<div class="extra_info"> {$aListing.time_stamp|convert_time} <span>&middot;</span> {$aListing|user} <span>&middot;</span> <a class="js_hover_title" href="{url link='advancedmarketplace' location=$aListing.country_iso}">{$aListing.country_iso|location}<span class="js_hover_info">{if !empty($aListing.city)} {$aListing.city|clean} &raquo; {/if}{if !empty($aListing.country_child_id)} {$aListing.country_child_id|location_child} &raquo; {/if} {$aListing.country_iso|location}</span></a></div>
                    <div class="item_content">
                        {$aListing.short_description|parse|highlight:'search'|split:25|shorten:200:'advancedmarketplace.see_more':true}
                    </div>
				</div>
			</div>
		</div>
	{/foreach}
	</div>
	
{/if}

{/if}