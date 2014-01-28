{if !count($aEvents)}
<div class="extra_info">
	{phrase var='event.no_events_found'}
</div>
{else}

{foreach from=$aEvents key=sDate item=aGroups}
<div class="block">
	<div class="title">{$sDate}</div>	
	<div class="border">
		<div class="content">
			{foreach from=$aGroups name=events item=aEvent}
			{item name='Event'}
				<div id="js_event_item_holder_{$aEvent.event_id}" class="js_event_parent {if $aEvent.is_sponsor && !defined('PHPFOX_IS_GROUP_VIEW')}row_sponsored {elseif $aEvent.is_featured && $sView != 'featured'}row_featured {/if}{if is_int($phpfox.iteration.events)}row1{else}row2{/if}{if $phpfox.iteration.events == 1} row_first{/if}">
						<div class="row_title">	
	
							<div class="row_title_image">		
								<a href="{$aEvent.url}">{img server_id=$aEvent.server_id title=$aEvent.title path='event.url_image' file=$aEvent.image_path suffix='_50' max_width='50' max_height='50' itemprop='image'}</a>							
							</div>
							<div class="row_title_info">		
								<header>
									<h1 itemprop="name"><a href="{$aEvent.url}" class="link" itemprop="url">{$aEvent.title|clean|split:25}</a></h1>
								</header>
								<div class="extra_info ynmb_extra_info">
									<ul class="extra_info_middot">{if isset($aEvent.start_time_micro)}<li itemprop="startDate" style="display:none;">{$aEvent.start_time_micro}<li>{/if}{$aEvent.start_time_phrase} {phrase var='event.at'} {$aEvent.start_time_phrase_stamp}</li><li><span>&middot;</span></li><li>{$aEvent|user}</li></ul>
									 
								</div>										
							</div>			
							
						</div>	
					{if !Phpfox::isMobile()}
					</div>
					{/if}
				</div>
			{/item}
			{/foreach}
		</div>
	</div>
</div>
{/foreach}

{pager}
{/if}