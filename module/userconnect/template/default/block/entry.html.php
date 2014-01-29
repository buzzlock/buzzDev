<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<?php
/**
 * [PHPFOX_HEADER]
 *
 * @copyright		[YOUNET COPYRIGHT]
 * @author  		YouNet Company
 * @package  		Module_UserConnection
 * {* *}
 */

defined('PHPFOX') or exit('NO DICE!');

?>

{if defined('PHPFOX_IS_ADMIN_SEARCH')}
    <div class="table_header">
        {phrase var='user.members'}
    </div>
    <form method="post" action="{url link='current'}">
        <table cellpadding="0" cellspacing="0" {if !Phpfox::getParam('user.randomize_featured_members') && isset($bShowFeatured) && $bShowFeatured == 1} id="js_drag_drop"{/if}>
            <tr>
                <th style="width:10px;"><input type="checkbox" name="val[id]" value="" id="js_check_box_all" class="main_checkbox" /></th>
		<th style="width:20px;"></th>
		<th>{phrase var='user.user_id'}</th>
		<th>{phrase var='user.photo'}</th>
		<th>{phrase var='user.display_name'}</th>
		<th>{phrase var='user.email_address'}</th>
		<th>{phrase var='user.group'}</th>
		<th>{phrase var='user.last_activity'}</th>
            </tr>
            {foreach from=$aUsers name=users key=iKey item=aUser}
            <tr class="checkRow{if is_int($iKey/2)} tr{else}{/if}" id="js_user_{$aUser.user_id}">
                <td><input type="checkbox" name="id[]" class="checkbox" value="{$aUser.user_id}" id="js_id_row{$aUser.user_id}" /></td>
		{if !Phpfox::getParam('user.randomize_featured_members') && isset($bShowFeatured) && $bShowFeatured == 1}
                    <td class="drag_handle"><input type="hidden" name="val[ordering][{$aUser.user_id}]" value="{$aUser.featured_order}" /></td>
		{/if}
		<td class="t_center">
                    <a href="#" class="js_drop_down_link" title="{phrase var='user.manage'}">{img theme='misc/bullet_arrow_down.png' alt=''}</a>
                    <div class="link_menu">
                        <ul>
                            <li>
                                <a href="{url link='admincp.user.add' id=$aUser.user_id}">{phrase var='user.edit_user'}</a>
                            </li>
                            {if $aUser.view_id == '1'}
                                <li class="js_user_pending_{$aUser.user_id}"><a href="#" onclick="$.ajaxCall('user.userPending', 'type=1&amp;user_id={$aUser.user_id}'); return false;">{phrase var='user.approve_user'}</a></li>
                                <li class="js_user_pending_{$aUser.user_id}"><a href="#" onclick="$.ajaxCall('user.userPending', 'type=2&amp;user_id={$aUser.user_id}'); return false;">{phrase var='user.deny_user'}</a></li>
                            {/if}
                            <li>
                                <div class="js_feature_{$aUser.user_id}">
                                    {if !isset($aUser.is_featured) || $aUser.is_featured < 0}<a href="#" onclick="$.ajaxCall('user.feature', 'user_id={$aUser.user_id}&amp;feature=1'); return false;">{phrase var='user.feature_user'}{else}<a href="#" onclick="$.ajaxCall('user.feature', 'user_id={$aUser.user_id}&amp;feature=0'); return false;">{phrase var='user.unfeature_user'}{/if}</a>
                                </div>
                            </li>
                                        {if (isset($aUser.pendingMail) && $aUser.pendingMail != '') || (isset($aUser.unverified) && $aUser.unverified > 0)}
                                            <li><div class="js_verify_email_{$aUser.user_id}"> <a href="#" onclick="$.ajaxCall('user.verifySendEmail', 'iUser={$aUser.user_id}'); return false;">{phrase var='user.resend_verification_mail'}</a></div></li>
                                            <li><div class="js_verify_email_{$aUser.user_id}"> <a href="#" onclick="$.ajaxCall('user.verifyEmail', 'iUser={$aUser.user_id}'); return false;">{phrase var='user.verify_this_user'}</a></div></li>
					{/if}
					<li id="js_ban_{$aUser.user_id}">
                                            {if (int) $aUser.user_group_id === (int) Phpfox::getParam('core.banned_user_group_id')}
                                                <a href="#" onclick="$.ajaxCall('user.ban', 'user_id={$aUser.user_id}&amp;type=0'); return false;">{phrase var='user.un_ban_user'}</a>
                                            {else}
                                                <a href="#" onclick="$.ajaxCall('user.ban', 'user_id={$aUser.user_id}&amp;type=1'); return false;">{phrase var='user.ban_user'}</a>
                                            {/if}
					</li>

					<li>
                                            <div class="user_delete"><a href="#" onclick="tb_show('{phrase var='user.delete_user' phpfox_squote=true}', $.ajaxBox('user.deleteUser', 'height=240&amp;width=400&amp;iUser={$aUser.user_id}'));return false;" title="{phrase var='user.delete_user_full_name' full_name=$aUser.full_name|clean}">{phrase var='user.delete_user'}</a></div>
                                        </li>
					</ul>
				</div>
			</td>
			<td class="t_center">#{$aUser.user_id}</td>
			<td class="t_center">{img user=$aUser suffix='_50' max_width=50 max_height=50}</td>
			<td>{$aUser|user}</td>
			<td><a href="mailto:{$aUser.email}">{if (isset($aUser.pendingMail) && $aUser.pendingMail != '')} {$aUser.pendingMail} {else} {$aUser.email} {/if}</a>{if isset($aUser.unverified) && $aUser.unverified > 0} <span class="js_verify_email_{$aUser.user_id}" onclick="$.ajaxCall('user.verifyEmail', 'iUser={$aUser.user_id}');">{phrase var='user.verify'}</span>{/if}</td>
			<td>
			{if ($aUser.status_id == 1)}
				<div class="js_verify_email_{$aUser.user_id}">{phrase var='user.pending_email_verification'}</div>
			{/if}
			{if Phpfox::getParam('user.approve_users') && $aUser.view_id == '1'}
				<span id="js_user_pending_group_{$aUser.user_id}">{phrase var='user.pending_approval'}</span>
			{elseif $aUser.view_id == '2'}
				{phrase var='user.not_approved'}
			{else}
				{$aUser.user_group_title}
			{/if}
			</td>
			<td>
			{if $aUser.last_activity > 0}
				{$aUser.last_activity|date:'core.profile_time_stamps'}
			{/if}
				{if !empty($aUser.last_ip_address)}
				<div class="p_4">
					(<a href="{url link='admincp.core.ip' search=$aUser.last_ip_address_search}" title="{phrase var='user.view_all_the_activity_from_this_ip'}">{$aUser.last_ip_address}</a>)
				</div>
				{/if}
			</td>
		</tr>
            {/foreach}
        </table>
            <div class="table_clear">
                {phrase var='user.with_selected'}:
		<input type="submit" name="approve" value="{phrase var='user.approve'}" class="button sJsCheckBoxButton disabled" disabled="true" />
		<input type="submit" name="ban" value="{phrase var='user.ban'}" class="sJsConfirm button sJsCheckBoxButton disabled" disabled="true" />
		<input type="submit" name="unban" value="{phrase var='user.un_ban'}" class="button sJsCheckBoxButton disabled" disabled="true" />
		<input type="submit" name="verify" value="{phrase var='user.verify'}" class="button sJsCheckBoxButton disabled" disabled="true" />
		<input type="submit" name="resend-verify" value="{phrase var='user.resend_verification_mail'}" class="button sJsCheckBoxButton disabled" disabled="true" />
		<input type="submit" name="delete" value="{phrase var='user.delete'}" class="sJsConfirm button sJsCheckBoxButton disabled" disabled="true" />
            </div>
	</form>
<!--{pager}-->
<br />
<br />
<br />
{else}
<div class="main_break"></div>
{if count($aUsers)}
{foreach from=$aUsers name=users item=aUser}
{if $bExtend}
   
        {if isset($Page)==null or $Page==1}
	<div class="{if is_int($phpfox.iteration.users/2)}row1{else}row2{/if}{if $phpfox.iteration.users == 1} row_first{/if}" style="position:relative; min-height:110px;" id="js_parent_user_{$aUser.user_id}">
        {else}
       
           <div class="row2" style="position:relative; min-height:150px; height:auto !important; height:150px;" id="js_parent_user_{$aUser.user_id}">
        {/if}
	<div class="user_browse_info">
            {phrase var='user.name'}: <a href="{url link=$aUser.user_name}">{$aUser.full_name|clean|shorten:50:'...'}</a> <br />
            {if !empty($aUser.gender) && Phpfox::getUserGroupParam('' . $aUser.user_group_id . '', 'user.can_edit_gender_setting')}
                {phrase var='user.gender'}: {$aUser.gender|gender} <br />
            {/if}
            {if Phpfox::getUserGroupParam('' . $aUser.user_group_id . '', 'user.can_edit_dob')}
                {if !empty($aUser.birthday) && isset($aUser.dob_setting)!=null && $aUser.dob_setting != '3'}
                    {if isset($aUser.dob_setting)!=null && $aUser.dob_setting == '4'}
                        {phrase var='user.birthday'}: {$aUser.month} {$aUser.day} <br />
                    {else}
                    {phrase var='user.age'}: {$aUser.birthday|age}
                    {if isset($aUser.dob_setting)!=null && $aUser.dob_setting == '1'}
                        ({$aUser.month} {$aUser.day})
                    {elseif isset($aUser.dob_setting)!=null && $aUser.dob_setting == '2'}
                    {else}
                        ({$aUser.month} {$aUser.day}, {$aUser.year})
                    {/if}
                    <br />
                {/if}
            {/if}
            {/if}
                {if !empty($aUser.country_iso)}
                    {phrase var='user.location'}: {if !empty($aUser.city_location)}{$aUser.city_location|clean} &raquo; {/if}{if !empty($aUser.country_child_id)}{$aUser.country_child_id|location_child} &raquo; {/if}{$aUser.country_iso|location} <br />
                {/if}
            </div>
    {if $aUser.user_id != Phpfox::getUserId()}
	<div class="user_browse_menu">
            <ul class="mini_action">
                <li><a href="{url link=$aUser.user_name}">{img theme='misc/user.png' alt='' class='v_middle'} {phrase var='user.view_profile'}</a></li>
		<li><a href="{url link='mail.compose' id=$aUser.user_id}">{img theme='misc/email_go.png' alt='' class='v_middle'} {phrase var='user.send_message'}</a></li>
                    {if empty($aUser.is_friend) && $level!=1}
                        <li><a href="#" onclick="javascript:addtoFriend({$aUser.user_id});return false;" class="inlinePopup" title="{phrase var='profile.add_to_friends'}">{img theme='misc/user_add.png' alt='' class='v_middle'} {phrase var='user.add_to_friends'}</a></li>
                    {/if}
                    {if Phpfox::getUserParam('user.can_feature')}
                        <li {if empty($aUser.is_featured)} style="display:none;" {/if} class="user_unfeature_member"><a href="#" title="{phrase var='user.un_feature_this_member'}" onclick="$(this).parent().hide(); $(this).parents('.mini_action:first').find('.user_feature_member:first').show(); $.ajaxCall('user.feature', 'user_id={$aUser.user_id}&amp;feature=0&amp;type=1&amp;view=1'); return false;">{img theme='misc/photo_unfeature.png' alt='' width='16' height='16' class='v_middle'} {phrase var='user.unfeature'}</a></li>
			<li  {if isset($aUser.is_featured) && $aUser.is_featured} style="display:none;" {/if} class="user_feature_member"><a href="#" title="{phrase var='user.feature_this_member'}" onclick="$(this).parent().hide(); $(this).parents('.mini_action:first').find('.user_unfeature_member:first').show(); $.ajaxCall('user.feature', 'user_id={$aUser.user_id}&amp;feature=1&amp;type=1'); return false;">{img theme='misc/photo_feature.png' alt='' width='16' height='16' class='v_middle'} {phrase var='user.feature'}</a></li>
                    {/if}
            </ul>
	</div>
    {/if}
    <div class="user_browse_image">
        {img user=$aUser suffix='_75' max_width=75 max_height=75}
    </div>
    <div class="clear"></div>
    </div>
{else}
	<div class="go_left t_center" style="width:30%; padding:4px;"  id="js_parent_user_{$aUser.user_id}">
            {if Phpfox::getUserParam('user.can_feature')}
		<div style="position:relative; width:150px; margin:auto; min-height:90px; height:auto !important; height:90px;" class="js_outer_photo_div js_mp_fix_holder image_hover_holder">
                    <div class="image_hover_menu">
                        <a {if empty($aUser.is_featured)} style="display:none;" {/if} href="#" class="user_unfeature_member" title="{phrase var='user.un_feature_this_member'}" onclick="$(this).hide(); $(this).parent().find('.user_feature_member:first').show(); $.ajaxCall('user.feature', 'user_id={$aUser.user_id}&amp;feature=0&amp;type=1&amp;view={$sView}'); return false;">{img theme='misc/photo_unfeature.png' alt='' width='16' height='16'}</a>
			<a {if isset($aUser.is_featured) && $aUser.is_featured} style="display:none;" {/if} href="#" class="user_feature_member" title="{phrase var='user.feature_this_member'}" onclick="$(this).hide(); $(this).parent().find('.user_unfeature_member:first').show(); $.ajaxCall('user.feature', 'user_id={$aUser.user_id}&amp;feature=1&amp;type=1'); return false;">{img theme='misc/photo_feature.png' alt='' width='16' height='16'}</a>
                    </div>
		{/if}
                    <div class="p_4">{$aUser|user|split:25}</div>
                        {img user=$aUser suffix='_75' max_width=75 max_height=75 class='js_mp_fix_width'}
			{if isset($sView)!=null && $sView == 'top'}
                            <div class="p_2">
                                {phrase var='user.total_score_out_of_10' total_score=$aUser.total_score|round}
                            </div>
			{/if}
		{if Phpfox::getUserParam('user.can_feature')}
		</div>
		{/if}
	</div>
	{if is_int($phpfox.iteration.users / 3)}
            <div class="clear"></div>
	{/if}
{/if}
{/foreach}
    {if !$bExtend}
        <div class="clear"></div>
    {/if}
    <!--{pager}-->
    {else}
        <div class="extra_info">
            {if isset($sView)}
                {if $sView == 'online'}
                    {phrase var='user.there_are_no_members_online'}
                {elseif $sView == 'top'}
                    {phrase var='user.no_top_members_found'}
                {elseif $sView == 'featured'}
                    {phrase var='user.no_featured_members'}
                {else}
                    {if isset($aCallback.no_member_message)}
                        {$aCallback.no_member_message}
                    {else}
                        {phrase var='user.unable_to_find_any_members_with_the_current_browse_criteria'}
                        <ul class="action">
                                <li><a href="{url link='user.browse'}">{phrase var='user.reset_browse_criteria'}</a></li>
                        </ul>
                    {/if}
                    {phrase var='user.unable_to_find_any_members_with_the_current_browse_criteria'}
                    <ul class="action">
                        <li><a href="{url link='user.browse'}">{phrase var='user.reset_browse_criteria'}</a></li>
                    </ul>
                {/if}
            {else}
            {/if}
        </div>
    {/if}
{/if}
