<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');
/**
 * 
 * 
 * @copyright       [YOUNET_COPYRIGHT]
 * @author          YouNet Company
 * @package         YouNet_Event
 */
?>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language=en"></script>

{if empty($sView) && !empty($aFeatured)}
<style type="text/css">
.fevent_slide_info{l}
    background:url({$sCorePath}module/fevent/static/image/black50.png);
{r}
</style>

<div class="block">
    <div class="title">{phrase var='fevent.featured_events'}</div>
    <div class="border">
        <div class="content">
			{if !phpfox::isMobile()}
				<div id="fevent_slideshow">
				{foreach from=$aFeatured item=aEvent name=af}
					<div class="fevent_slide" id="slide_{$phpfox.iteration.af}">
						<div class="fevent_slide_info">
							<a href="{permalink module='fevent' id=$aEvent.event_id title=$aEvent.title}"><strong style="text-transform:uppercase;">{$aEvent.title}</strong></a>
							<div class="fevent_highlight">{phrase var='fevent.start_time'}: <strong>{$aEvent.convert_start_time}</strong><br />{phrase var='fevent.end_time'}&nbsp;&nbsp;: <strong>{$aEvent.convert_end_time}</strong></div>
							<div>{phrase var='fevent.location'}: {$aEvent.location}{if $aEvent.address} - {$aEvent.address}{/if}{if $aEvent.city} - {$aEvent.city}{/if}</div>
							<div>{phrase var='fevent.by'} {$aEvent|user}</div>
						</div>
						{img thickbox=true server_id=$aEvent.server_id title=$aEvent.title path='event.url_image' file=$aEvent.image_path suffix='' onerror=$sImageOnError}
					</div>
				{/foreach}
				</div>
				<div class="fevent_slide_thumbs">
				{foreach from=$aFeatured item=aEvent name=af}
				<a id="thumb_slide_{$phpfox.iteration.af}" href="javascript:void(0)" onclick="$('#fevent_slideshow').cycle({$phpfox.iteration.af}-1);">
					{img server_id=$aEvent.server_id title='' path='event.url_image' file=$aEvent.image_path suffix='_50' onerror=$sImageOnError}
				</a>
				{/foreach}
				</div>
			{else}
				{foreach from=$aFeatured item=aEvent name=af}
					<div id="js_event_item_holder_{$aEvent.event_id}">
						<div class="row_title_image_header">
							<a href="{permalink module='fevent' id=$aEvent.event_id title=$aEvent.title}">{img server_id=$aEvent.server_id title=$aEvent.title path='event.url_image' file=$aEvent.image_path suffix='_120' max_width='90' max_height='90'}</a>
						</div>
						<div class="row_title_image_header_body">
							<div class="row_title">									
								<div class="row_title_image">
									<a href="{permalink module='fevent' id=$aEvent.event_id title=$aEvent.title}">{img user=$aEvent suffix='_50_square' max_width='50' max_height='50'}</a>
								</div>
							</div>
							<div class="row_title_info">	
								<a href="{permalink module='fevent' id=$aEvent.event_id title=$aEvent.title}"><strong style="text-transform:uppercase;">{$aEvent.title}</strong></a>
							
								<div class="extra_info">
									<p>{phrase var='fevent.start_time'}: <strong>{$aEvent.convert_start_time}</strong></p>
									<p>{phrase var='fevent.end_time'}&nbsp;&nbsp;: <strong>{$aEvent.convert_end_time}</strong></p>
									<p>Location: {$aEvent.location}{if $aEvent.address} - {$aEvent.address}{/if}{if $aEvent.city} - {$aEvent.city}{/if}</p>
									<p>{phrase var='fevent.by'} {$aEvent|user}</p>
								</div>
							</div>
						</div>
					</div>
				{/foreach}
			{/if}
        </div>
    </div>
</div>
{if phpfox::isMobile()}
	{module name="fevent.calendar"}
{/if}
{/if}
{if !count($aEvents)}
<div class="extra_info">
	{phrase var='fevent.no_events_found'}
</div>
{else}

{foreach from=$aEvents key=sDate item=aGroups}
<div class="block">
	<div class="title">{$sDate}</div>	
	<div class="border">
		<div class="content">
			{foreach from=$aGroups name=events item=aEvent}
			<div id="js_event_item_holder_{$aEvent.event_id}" class="js_event_parent {if $aEvent.is_sponsor && !defined('PHPFOX_IS_GROUP_VIEW')}row_sponsored {elseif $aEvent.is_featured && $sView != 'featured'}row_featured {/if}{if is_int($phpfox.iteration.events)}row1{else}row2{/if}{if $phpfox.iteration.events == 1} row_first{/if}">
				{if !Phpfox::isMobile()}
				<div class="row_title_image_header">
					
					{if isset($sView) && $sView == 'featured'}
					{else}
					<div class="js_featured_event row_featured_link"{if !$aEvent.is_featured} style="display:none;"{/if}>
						{phrase var='fevent.featured'}
					</div>					
					{/if}	
					<div id="js_sponsor_phrase_{$aEvent.event_id}" class="js_sponsor_event row_sponsored_link"{if !$aEvent.is_sponsor} style="display:none;"{/if}>
						{phrase var='fevent.sponsored'}
					</div>					
					
					<a href="{$aEvent.url}">{img server_id=$aEvent.event_server_id title=$aEvent.title path='event.url_image' file=$aEvent.image_path suffix='_120' max_width='120' max_height='120'}</a>
				</div>				
				<div class="row_title_image_header_body">	
				{/if}
					<div class="row_title">	

						<div class="row_title_image">		
							<a href="{$aEvent.url}">{img user=$aEvent suffix='_50_square' max_width='50' max_height='50'}</a>
							{if ($aEvent.user_id == Phpfox::getUserId() && Phpfox::getUserParam('fevent.can_edit_own_event')) || Phpfox::getUserParam('fevent.can_edit_other_event')
								|| ($aEvent.view_id == 0 && ($aEvent.user_id == Phpfox::getUserId() && Phpfox::getUserParam('fevent.can_edit_own_event')) || Phpfox::getUserParam('fevent.can_edit_other_event'))
								|| ($aEvent.user_id == Phpfox::getUserId() && Phpfox::getUserParam('fevent.can_edit_own_event')) || Phpfox::getUserParam('fevent.can_edit_other_event')
								|| ($aEvent.user_id == Phpfox::getUserId() && Phpfox::getUserParam('fevent.can_delete_own_event')) || Phpfox::getUserParam('fevent.can_delete_other_event')
								|| (defined('PHPFOX_IS_PAGES_VIEW') && Phpfox::getService('pages')->isAdmin('' . $aPage.page_id . ''))
							}
							<div class="row_edit_bar_parent">
								<div class="row_edit_bar_holder">
									<ul>
										{template file='fevent.block.menu'}
									</ul>			
								</div>
								<div class="row_edit_bar">				
										<a href="#" class="row_edit_bar_action"><span>{phrase var='fevent.actions'}</span></a>							
								</div>
							</div>		
							{/if}							
							{if Phpfox::getUserParam('fevent.can_approve_events') || Phpfox::getUserParam('fevent.can_delete_other_event')}<a href="#{$aEvent.event_id}" class="moderate_link" rel="fevent">{phrase var='fevent.moderate'}</a>{/if}
						</div>
						<div class="row_title_info">		
							<a href="{$aEvent.url}" class="link">{$aEvent.title|clean|split:25}</a>
							<div class="extra_info">
                                {if $aEvent.time_left!=''}
                                    <p style="margin-bottom:5px;">{phrase var='fevent.time_left_until_event'}: <strong>{$aEvent.time_left}</strong></p>
                                {/if}
								<ul class="extra_info_middot">
                                    <li>{$aEvent.event_date}</li>
                                    <li><span>&middot;</span></li>
                                    <li>{$aEvent|user}</li>
                                </ul>
							</div>
							<div>
								{if $aEvent.isrepeat!=-1}{phrase var='fevent.repeat'}: {$aEvent.content_repeat}{/if}
							</div>
							{if Phpfox::isMobile()}
							<a href="{$aEvent.url}">{img server_id=$aEvent.event_server_id title=$aEvent.title path='event.url_image' file=$aEvent.image_path suffix='_120' max_width='120' max_height='120'}</a>
							{/if}
	
							{module name='feed.comment' aFeed=$aEvent.aFeed}				
							
						</div>			
						
					</div>	
				{if !Phpfox::isMobile()}
				</div>
				{/if}
			</div>
			{/foreach}
		</div>
	</div>
</div>
{/foreach}

{if Phpfox::getUserParam('fevent.can_approve_events') || Phpfox::getUserParam('fevent.can_delete_other_event')}
{moderation}
{/if}

{pager}
{/if}