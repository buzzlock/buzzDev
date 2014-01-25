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
{foreach from=$aPast item=aPEvent name=Past}
<div class="{if is_int($phpfox.iteration.Past/2)}row1{else}row2{/if}{if $phpfox.iteration.Past == 1} row_first{/if}">
	<div style="float:left; width:50px;" class="t_center">
		<a href="{permalink module='fevent' id=$aPEvent.event_id title=$aPEvent.title}" title="{$aPEvent.title|clean}">{img server_id=$aPEvent.server_id path='event.url_image' file=$aPEvent.image_path suffix='_50' max_width=50 max_height=50}</a>
	</div>
	<div style="margin-left:60px;">
		<a href="{permalink module='fevent' id=$aPEvent.event_id title=$aPEvent.title}" class="row_sub_link" title="{$aPEvent.title|clean}">{$aPEvent.title|clean|shorten:50:'...'|split:20}</a>
		<div class="extra_info_link">
			{phrase var='fevent.by'} {$aPEvent|user}
		</div>
	</div>
	<div class="clear"></div>
</div>
{/foreach}
{if $bViewMore}
<div style="padding-top: 10px; text-align:right;"><a href="{url link='fevent' when='past'}">{phrase var='core.view_more'}</a></div>
{/if}
