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
{foreach from=$aUpcoming item=aUpEvent name=Upcoming}
<div class="{if is_int($phpfox.iteration.Upcoming/2)}row1{else}row2{/if}{if $phpfox.iteration.Upcoming == 1} row_first{/if}">
	<div style="float:left; width:50px;" class="t_center">
		<a href="{permalink module='fevent' id=$aUpEvent.event_id title=$aUpEvent.title}" title="{$aUpEvent.title|clean}">{img server_id=$aUpEvent.server_id path='event.url_image' file=$aUpEvent.image_path suffix='_50' max_width=50 max_height=50}</a>
	</div>
	<div style="margin-left:60px;">
		<a href="{permalink module='fevent' id=$aUpEvent.event_id title=$aUpEvent.title}" class="row_sub_link" title="{$aUpEvent.title|clean}">{$aUpEvent.title|clean|shorten:50:'...'|split:20}</a>
		<div class="extra_info_link">
			{phrase var='fevent.by'} {$aUpEvent|user}
		</div>
	</div>
	<div class="clear"></div>
</div>
{/foreach}
{if $bViewMore}
<div style="padding-top: 10px; text-align:right;"><a href="{url link='fevent' when='upcoming'}">{phrase var='core.view_more'}</a></div>
{/if}
