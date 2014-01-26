<?php
/**
 * [PHPFOX_HEADER]
 *
 * @copyright        [PHPFOX_COPYRIGHT]
 * @author          Miguel Espinoza
 * @package          Module_Contact
 * @version         $Id: index.html.php 1424 2010-01-25 13:34:36Z Raymond_Benc $
 */
defined('PHPFOX') or exit('NO DICE!');
?>
{literal}
<script type="text/css">
    .table_right input{
      width:200px;
    }
</script>
{/literal}
<form method="post" action="{url link='admincp.contactimporter.invitations'}">
    <div class="table_header">
        {phrase var='admincp.search_filter'}
    </div>
    <div class="table">
        <div class="table_left">
            {phrase var='contactimporter.keywords'}:
        </div>
        <div class="table_right">
            {$aFilters.title}
        </div>
        <div class="clear"></div>
    </div>

    <div class="table_clear">
        <input type="submit" name="search[submit]" value="{phrase var='core.submit'}" class="button" />
        <input type="submit" name="search[reset]" value="{phrase var='core.reset'}" class="button" />

    </div>
</form>
{pager}
{if count($items) > 0}
<form action="{url link='admincp.contactimporter.invitations'}" method="post" onsubmit="return getsubmit();" >
    <table>
        <tr>
            <th width="10px"><input type="checkbox" value="" id = "checkAll" name="checkAll" onclick="javascript:selectAll()"/></th>
            <th>{phrase var='contactimporter.inviter'}</th>
            <th>{phrase var='contactimporter.full_name'}</th>
            <th>{phrase var='contactimporter.email'}</th>
            <th>{phrase var='contactimporter.options'}</th>

        </tr>
        {foreach from=$items key=iKey item=inviter}    
        <tr id="{$inviter.invite_id}" class="checkRow{if is_int($iKey/2)} tr{else}{/if}">
            <td style="width:10px">
                <input type="checkbox" value="{$inviter.invite_id}" name="is_selected"/>
            </td>
            <td>{$inviter.user_name|clean}</td>
            <td>{$inviter.full_name|clean}</td>
            <td>
				{if isset($inviter.invited_name) && $inviter.invited_name}
					{$inviter.invited_name} ({$inviter.receive_email|shorten:30:'...'}) 
				{else}
					{$inviter.receive_email}
				{/if}
			</td>
            <td width="40px">
                <div id = "resend_{if isset($aInvite)}{$aInvite.invite_id}{/if}" align="center">
                    <span style="float:left;width:10px;margin-left: 4px;">
                        {if $inviter.canResendMail && $inviter.is_resend == 0}
                        <a class="inlinePopup"  title="{phrase var='contactimporter.invitation_message'}"  border="0" href="#?call=contactimporter.reSendInvitation&invite_id={$inviter.invite_id}&width=300&height=200"><img alt="{phrase var='contactimporter.resend_invitation'}" title="{phrase var='contactimporter.resend_invitation'}" border="0" width="15" height="15" src="{$core_url}module/contactimporter/static/image/send_mail.png"></a>
                        {/if}
                    </span>
                    {if isset($inviter)}
                    <a title="{phrase var='contactimporter.delete_invitation'}" href="{url link='admincp.contactimporter.invitations.page_'.$iPage del=$inviter.invite_id }">{img theme='misc/delete.png' alt='' class='go_right'}</a>
                    {/if}
                </div>
            </td>
        </tr>    
        {/foreach}
        <tr><td colspan="5">
                <div class="table_bottom">
                    <input type="hidden" value="" name="arr_selected" id="arr_selected"/>
                    <input type="hidden" value="" name="feed_selected" id="feed_selected"/>
                    <input type="submit" name="deleteselect" value="{phrase var='contactimporter.delete_selected'}" class="button" onclick="javascript:setValue();"/>
                </div>
            </td></tr>
    </table>
</form>
{pager}
{else}
<br/>
<div class="extra_info">
    <strong>{phrase var='invite.there_are_no_pending_invitations'}</strong>
</div>
{/if}
<script type="text/javascript">
    <!--
    {literal}
    function reSendInvitation(id)
    {
        {/literal}                         
        $.ajaxCall('contactimporter.reSendInvitation','invite_id='+id);    
        {literal}    
    }
    {/literal}
    -->
</script>