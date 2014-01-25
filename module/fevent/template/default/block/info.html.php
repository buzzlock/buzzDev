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
<div class="info_holder">

	<div class="info">
		<div class="info_left">
			{phrase var='fevent.time'}
		</div>
		<div class="info_right">
			{$aEvent.event_date}	
		</div>
	</div>	
	
    {if $aEvent.time_left != ''}
	<div class="info" style="height: 28px;">
		<div class="info_left">
			{phrase var='fevent.time_left_n_until_event'}
		</div>
		<div class="info_right">
			{$aEvent.time_left}	
		</div>
	</div>
    {/if}

	{if is_array($aEvent.categories) && count($aEvent.categories)}
	<div class="info">
		<div class="info_left">
			{phrase var='fevent.category'}
		</div>
		<div class="info_right">
			{$aEvent.categories|category_display}
		</div>
	</div>		
	{/if}
	
	<div class="info">
		<div class="info_left">
			{phrase var='fevent.location'}
		</div>
		<div class="info_right">				 	
			{$aEvent.location|clean|split:60}
			{if !empty($aEvent.address)}
			<div class="p_2">{$aEvent.address|clean}</div>
			{/if}			
			{if !empty($aEvent.city)}
			<div class="p_2">{$aEvent.city|clean}</div>
			{/if}					
			{if !empty($aEvent.postal_code)}
			<div class="p_2">{$aEvent.postal_code|clean}</div>
			{/if}								
			{if !empty($aEvent.country_child_id)}
			<div class="p_2">{$aEvent.country_child_id|location_child}</div>
			{/if}	
			<div class="p_2">{$aEvent.country_iso|location}</div>
			{if isset($aEvent.map_location) && $bCanViewMap}						
			<div style="width:390px; height:170px; position:relative;">
				<div style="margin-left:-8px; margin-top:-8px; position:absolute; background:#fff; border:8px blue solid; width:12px; height:12px; left:50%; top:50%; z-index:200; overflow:hidden; text-indent:-1000px; border-radius:12px;">Marker</div>
				<a href="http://maps.google.com/?q={$aEvent.map_location}" target="_blank" title="{phrase var='fevent.view_this_on_google_maps'}"><img src="http://maps.googleapis.com/maps/api/staticmap?center={$aEvent.map_location}&amp;zoom=16&amp;size=390x170&amp;sensor=false&amp;maptype=roadmap" alt="" /></a>
			</div>		
			<div class="p_top_4">					
				<a href="http://maps.google.com/?q={$aEvent.map_location}" target="_blank">{phrase var='fevent.view_on_google_maps'}</a>
			</div>			
			{/if}
		</div>
	</div>
	<div class="info">
		<div class="info_left">
			{phrase var='fevent.range'}
		</div>
		<div class="info_right">
			{$aEvent.range_value} {if $aEvent.range_type==0}{phrase var='fevent.miles'}{else}{phrase var='fevent.km'}{/if}	
		</div>
	</div>
	<div class="info">
		<div class="info_left">
			{phrase var='fevent.created_by'}
		</div>
		<div class="info_right">
			{$aEvent|user}	
		</div>
	</div>
	
	<div class="info" {if $content_repeat==""}style="display:none"{/if}>
		{phrase var='fevent.repeat'}: {$content_repeat}
	</div>
    
    {foreach from=$aEvent.custom item=aCustom}
    <div class="info">
        <div class="info_left">
            {phrase var=$aCustom.phrase_var_name}
        </div>
        <div class="info_right">
            {$aCustom.value}
        </div>
    </div>
    {/foreach}

	{$aEvent.description|parse|split:70}
	
	<div class="info">
	{if $aEvent.total_attachment}
	{module name='attachment.list' sType=fevent iItemId=$aEvent.event_id}
	{/if}
	</div>

</div>