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
{if !$bIsInBrowse}
<div style="padding-bottom:5px;">
	<select name="rsvp" onchange="$.ajaxCall('fevent.browseList', 'id={$aEvent.event_id}&amp;rsvp=' + this.value + '&amp;page=1', 'GET');">
	{foreach from=$aLists key=sPhrase item=iListId}
		<option value="{$iListId}">{$sPhrase}</option>
	{/foreach}
	</select>
</div>
<div id="js_event_browse_guest_list">
{/if}
	<div style="height:300px;" class="label_flow">
		{foreach from=$aInvites name=invites item=aInvite}
		<div class="{if is_int($phpfox.iteration.invites/2)}row1{else}row2{/if}{if $phpfox.iteration.invites == 1} row_first{/if}">
			<div class="go_left" style="width:55px; text-align:center;">
				{img user=$aInvite suffix='_50' max_width=50 max_height=50}	
			</div>
			<div style="margin-left:55px;">
				{$aInvite|user}
			</div>
			<div class="clear"></div>
		</div>
		{/foreach}
	</div>
	{pager}
{if !$bIsInBrowse}
</div>
{/if}