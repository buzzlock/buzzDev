<?php 
/**
 * [PHPFOX_HEADER]
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>

{if isset($aEntry)}
	{if ($aEntry.can_approve_entry)}
        <li id="js_entry_approve_{$aEntry.entry_id}">
			<a href="#" title="{phrase var='contest.approve'}" onclick="if(confirm('{phrase var='contest.are_you_sure_info'}')) $.ajaxCall('contest.actionEntry', '&entry_id={$aEntry.entry_id}&amp;is_owner=1&amp;type=approve', 'GET'); return false;">{phrase var='contest.approve'}</a>
        </li>
	{/if}

	{if ($aEntry.can_deny_entry)}
        <li id="js_entry_deny_{$aEntry.entry_id}">
			<a href="#" title="{phrase var='contest.deny'}" onclick="if(confirm('{phrase var='contest.are_you_sure_info'}')) $.ajaxCall('contest.actionEntry', '&entry_id={$aEntry.entry_id}&amp;is_owner=1&amp;type=deny', 'GET'); return false;">{phrase var='contest.deny'}</a>
        </li>
	{/if}

	{if ($aEntry.can_set_winning_entry)}
        <li id="js_entry_winning_{$aEntry.entry_id}">
			<a href="#" title="{phrase var='contest.set_as_winning_entry'}" onclick="$Core.box('contest.setWinning', 400, 'entry_id={$aEntry.entry_id}&amp;is_owner=1&amp;type=winning', 'GET'); return false;">{phrase var='contest.set_as_winning_entry'}</a>
        </li>
	{/if}

	{if ($aEntry.can_remove_entry_from_winning)}
        <li id="js_entry_winning_{$aEntry.entry_id}">
			<a href="#" title="{phrase var='contest.remove_from_winning_list'}" onclick="if(confirm('{phrase var='contest.are_you_sure_info'}')) $.ajaxCall('contest.removeWinningEntry', '&entry_id={$aEntry.entry_id}', 'GET'); return false;">{phrase var='contest.remove_from_winning_list'}</a>
        </li>
	{/if}
 
	{*{if isset($aEntry.delete) && $aEntry.delete}
        <li id="js_entry_deny_{$aEntry.entry_id}">
			<a href="#" title="{phrase var='contest.delete'}" onclick="if(confirm('{phrase var='contest.are_you_sure_info'}')) $.ajaxCall('contest.actionEntry', '&entry_id={$aEntry.entry_id}&amp;is_owner=1&amp;type=delete', 'GET'); return false;">{phrase var='contest.delete'}</a>
        </li>
	{/if}*}
{/if}