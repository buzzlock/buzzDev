<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Friend
 * @version 		$Id: search.html.php 3710 2011-12-07 10:02:30Z Miguel_Espinoza $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
{foreach from=$aFriends name=friend item=aFriend}

<div style="position: relative" class="row2">
	
	{if $aFriend.not_show_name}	
		<span> <img src='{$sNoProfileImagePath}' height='50' width='50'/> </span>
		<span class ="js_donation" style="font-weight:bold; position: absolute; top:12px; left:55px;" id="js_friend_{$aFriend.user_id}"> {phrase var='donation.anonymous'}</span>
					<span style="position: absolute; bottom:12px; left:55px;color:#666666">{$aFriend.time_stamp|convert_time}</span>
					
	{elseif $aFriend.is_guest}
		<span> <img src='{$sNoProfileImagePath}' height='50' width='50'/> </span>
		<span class ="js_donation" style="font-weight:bold; position: absolute; top:12px; left:55px;" id="js_friend_{$aFriend.user_id}"> {$aFriend.temp_id|clean|shorten:20:'...'|split:20}</span>
					<span style="position: absolute; bottom:12px; left:55px;color:#666666">{$aFriend.time_stamp|convert_time}</span>
	{else}	
		<span>{$aFriend.img}</span>
		<span class ="js_donation" style="font-weight:bold; position: absolute; top:12px; left:55px;" id="js_friend_{$aFriend.user_id}">{$aFriend|user}{if isset($aFriend.is_active)} <em>({$aFriend.is_active})</em>{/if}{if isset($aFriend.canMessageUser) && $aFriend.canMessageUser == false} {phrase var='friend.cannot_select_this_user'}{/if}</span>
					<span style="position: absolute; bottom:12px; left:55px;color:#666666">{$aFriend.time_stamp|convert_time}</span>
	{/if}
	{if !$aFriend.not_show_money}
		<span  style="font-weight:bold; position: absolute; top:12px; left:250px;" id="js_friend_{$aFriend.user_id}"> {phrase var='donation.donated'} {$aFriend.quanlity} {phrase var='donation.currency_type'}</span>
	{else}
		<span  style="font-weight:bold; position: absolute; top:12px; left:250px;" id="js_friend_{$aFriend.user_id}">{phrase var='donation.donated'} {phrase var='donation.generously'}</span>
	{/if}
	{if $bModerator}
		<input onclick="deleteUser({$iPageId},{$aFriend.donation_id}, {$aFriend.is_guest});" style="position: absolute; right:0; top:20px;" type="button" name="deleteUser[]" class="button" value="{phrase var='donation.remove_from_list'}"/>
	{/if}
	
</div>

{/foreach}
               



