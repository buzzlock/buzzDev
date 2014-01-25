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
{if phpfox::isMobile()}
	{module name="fevent.image"}
{/if}
{if $aEvent.view_id == '1'}
<div class="message js_moderation_off">
	{phrase var='fevent.event_is_pending_approval'}
</div>
{/if}

{if ($aEvent.user_id == Phpfox::getUserId() && Phpfox::getUserParam('fevent.can_edit_own_event')) || Phpfox::getUserParam('fevent.can_edit_other_event')
	|| ($aEvent.view_id == 0 && ($aEvent.user_id == Phpfox::getUserId() && Phpfox::getUserParam('fevent.can_edit_own_event')) || Phpfox::getUserParam('fevent.can_edit_other_event'))
	|| ($aEvent.user_id == Phpfox::getUserId() && Phpfox::getUserParam('fevent.can_edit_own_event')) || Phpfox::getUserParam('fevent.can_edit_other_event')
	|| ($aEvent.user_id == Phpfox::getUserId() && Phpfox::getUserParam('fevent.can_delete_own_event')) || Phpfox::getUserParam('fevent.can_delete_other_event')
}
<div class="item_bar">
	<div class="item_bar_action_holder">
	{if (Phpfox::getUserParam('fevent.can_approve_events') && $aEvent.view_id == '1')}
		<a href="#" class="item_bar_approve item_bar_approve_image" onclick="return false;" style="display:none;" id="js_item_bar_approve_image">{img theme='ajax/add.gif'}</a>			
		<a href="#" class="item_bar_approve" onclick="$(this).hide(); $('#js_item_bar_approve_image').show(); $.ajaxCall('fevent.approve', 'inline=true&amp;event_id={$aEvent.event_id}'); return false;">{phrase var='fevent.approve'}</a>
	{/if}
		<a href="#" class="item_bar_action"><span>{phrase var='fevent.actions'}</span></a>	
		<ul>
			{template file='fevent.block.menu'}
		</ul>			
	</div>
</div>
{/if}
{plugin call='fevent.template_default_controller_view_extra_info'}