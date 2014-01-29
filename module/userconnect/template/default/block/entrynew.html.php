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

{if count($aUsers)}
    {foreach from=$aUsers name=users item=aUser}
        {if $bExtend}
            <div class="left" id="js_parent_user_{$aUser.user_id}">
                {if $aUser.user_id != Phpfox::getUserId()}
                    <div class="user_browse_menu"></div>
		{/if}
		<div class="user_browse_image">
                    {img user=$aUser suffix='_120' max_width=100 max_height=100}
		</div>
		<div class="user_info" align="center">
                    {phrase var='user.name'}: <a href="{url link=$aUser.user_name}" title="{$aUser.full_name}">{$aUser.full_name|clean|shorten:9:'...'}</a> <br />
                    {phrase var='user.gender'}: {if !empty($aUser.gender)}{$aUser.gender|gender}{else}Unknown {/if}
                    {if $level > 1}
                        <br /><a href="javascript:void(0);" onclick="viewConnection({$aUser.owner_id},{$aUser.user_id},{$level})">{phrase var='userconnect.connection_path'}</a>
                    {/if}
                </div>
            </div>
        {else}
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
    {/if}
    </div>
{/if}
