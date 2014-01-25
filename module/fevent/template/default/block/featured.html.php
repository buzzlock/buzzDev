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
{foreach from=$aFeatured item=aFeature name=featured}
<div class="{if is_int($phpfox.iteration.featured/2)}row1{else}row2{/if}{if $phpfox.iteration.featured == 1} row_first{/if}">
	<div style="float:left; width:50px;" class="t_center">
		<a href="{permalink module='fevent' id=$aFeature.event_id title=$aFeature.title}" title="{$aFeature.title|clean}">{img server_id=$aFeature.server_id path='event.url_image' file=$aFeature.image_path suffix='_50' max_width=50 max_height=50}</a>
	</div>
	<div style="margin-left:60px;">
		<a href="{permalink module='fevent' id=$aFeature.event_id title=$aFeature.title}" class="row_sub_link" title="{$aFeature.title|clean}">{$aFeature.title|clean|shorten:50:'...'|split:20}</a>
		<div class="extra_info_link">
			{phrase var='fevent.by'} {$aFeature|user}
		</div>
	</div>
	<div class="clear"></div>
</div>
{/foreach}
{if $bViewMore}
<div style="padding-top: 10px; text-align:right;"><a href="{url link='fevent' view='featured'}">{phrase var='core.view_more'}</a></div>
{/if}
