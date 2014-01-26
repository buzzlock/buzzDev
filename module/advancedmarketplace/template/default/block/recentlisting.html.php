<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
{if $aRecentListing !== NULL}

<div class="recent_listing">
    {foreach from=$aRecentListing key=iKey item=aListing}
    <div class="listing_1">
        <div class="row_title_image_header">
        
			{if isset($aListing.is_expired)}
				<div class="row_featured_link">
					{phrase var='advancedmarketplace.expired'}
				</div>
			{else}
                {if isset($sView) && $sView == 'featured'}
                {else}
                <div id="js_featured_phrase_{$aListing.listing_id}" class="row_featured_link"{if !$aListing.is_featured} style="display:none;"{/if}>
                    {phrase var='advancedmarketplace.featured'}
                </div>					
                {/if}	
                <div id="js_sponsor_phrase_{$aListing.listing_id}" class="js_sponsor_event row_sponsored_link"{if !$aListing.is_sponsor} style="display:none;"{/if}>
                    {phrase var='advancedmarketplace.sponsored'}
                </div>
            {/if}
            
			{if !phpfox::isMobile()}
				<a href="{$aListing.url}" title="{$aListing.title|parse|clean}">
                    {if $aListing.image_path != NULL}
                        {img server_id=$aListing.server_id path='core.url_pic' file='advancedmarketplace/'.$aListing.image_path suffix='_120' max_width=120 max_height=90 title=$aListing.title }
                        <!--<img title="{$aListing.title}" src="<?php echo $this->_aVars['advancedmarketplace_url_image'] . PHPFOX::getService("advancedmarketplace")->proccessImageName($this->_aVars["aListing"]["image_path"], "_120"); ?>" style="max-width: 120px; max-height: 90px;" max-width='120' max-height='90' />-->
                    {else}
                        <img title="{$aListing.title}" src="{$corepath}module/advancedmarketplace/static/image/default/noimage.png" style="max-width:120px;max-height:90px" max-width='120' max-height='90' />
                    {/if}
                </a>
			{else}
				<a href="{$aListing.url}" title="{$aListing.title|parse|clean}">
                    {if $aListing.image_path != NULL}
                        {img server_id=$aListing.server_id path='core.url_pic' file='advancedmarketplace/'.$aListing.image_path suffix='_120' max_width=90 max_height=90 title=$aListing.title }
                    {else}
                        <img title="{$aListing.title}" src="{$corepath}module/advancedmarketplace/static/image/default/noimage.png" style="max-width:90px;max-height:90px" max-width='90' max-height='90' />
                    {/if}
                </a>
			{/if}
        </div>	
        <div class="row_title_image_header_body">				
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
                    <a class="advlink" href="{$aListing.url}" title="{$aListing.title|parse|clean}">
						{if !phpfox::isMobile()}
							{$aListing.title}
						{else}
							{$aListing.title|shorten:15:"..."}
						{/if}
					</a>
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
        <div class="clear"></div>				
    </div>
    {/foreach}
</div>
<div {if !phpfox::isMobile()}class="view_more_link" style="padding-left: 430px!important;background-position: 414px 60% !important;"{/if}><a href="{url link='advancedmarketplace.search.sort_latest'}">{phrase var='advancedmarketplace.view_more'}</a></div>
{/if}