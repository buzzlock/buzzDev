<script type="text/javascript">{literal}var total_allow_select ={/literal}{$max_invitation}{literal} ;</script>{/literal}
<input type='hidden' id='provider' value='{$sProvider}'>
<input type='hidden' id='friends_count' value='{$iCnt}'>
<input type="hidden" id="contacts" name="contacts" value="" />
<div id="openinviter">
    {if count($aJoineds)}
    {phrase var='friend.the_following_users_are_already_a_member_of_our_community'}:
    <div class="p_4">
        <div class="label_flow" style="padding-bottom:5px; max-height: 100px;">
            {foreach from=$aJoineds name=users item=aUser}
            <div class="{if is_int($phpfox.iteration.users/2)}row1{else}row2{/if} {if $phpfox.iteration.users == 1} row_first{/if}" id="js_invite_user_{$aUser.user_id}">
                {if $aUser.user_id == Phpfox::getUserId()}
                {$aUser.email} - {phrase var='friend.that_s_you'}
                {else}
                {$aUser.email} - {$aUser|user}{if !isset($aUser.friend_id) || !$aUser.friend_id} - <a href="#?call=friend.request&amp;user_id={$aUser.user_id}&amp;width=420&amp;height=250&amp;invite=true" class="inlinePopup" title="{phrase var='profile.add_to_friends'}">{phrase var='friend.add_to_friends'}</a>{/if}
                {/if}
            </div>
            {/foreach}
        </div>
    </div>
    {/if}
    <h3>{phrase var='contactimporter.your_contacts'}</h3>
    {if count($aInviteLists) == 0}
    {if count($aJoineds) == 0}
    <div class="error_message">{phrase var='contactimporter.there_is_no_contact_in_your_account'}</div>
    {else}
    {phrase var='contactimporter.you_have_sent_invitations_to_all_of_your_friends'}
    {/if}
    {else}
    <p class="description" style="margin-bottom: 5px;">{phrase var='contactimporter.are_not_joined_yet'}.</p>
	<div class="extra_info"> * {phrase var='contactimporter.notice_manual_select_amount'} </div>
    {*<p class="description" style="margin-bottom:5px;">{phrase var='contactimporter.you_can_send_max_invitations_per_time' max=$max_invitation}</p>*}
    <br />
    <div style='display:none' id="error">
        <ul class="form-errors"><li><ul class="errors"><li id='error_content'></li></ul></li></ul>
    </div>
    <div class="clear"></div> 
    <div class="wrapper-list">
        <table class='thTableOddRow yncontact_email_header' align='left' cellspacing='0' cellpadding='5px' style="width: 100%; border:0px solid">
            <tr>
                <td width="30px">
                    <input class="contact_checkall" type='checkbox' onclick="checkAll('items[]', this.checked ? 1 : 0)" title='Select/Deselect all'>
                </td>
                <td width = '60%'>{phrase var='contactimporter.name'}</td>
                <td style="width:150px">{phrase var='user.email'}</td> 
            </tr>
        </table>
        <div id="div_list_view" style="width:100%;padding-left:0px;">
            <table class='thTableOddRow' align='left' cellspacing='0' cellpadding='5px' style="width:100%; border:0px solid">
                {php}$counter=0;{/php}
                {foreach from=$aInviteLists key=letter item=aInviteList}
                {if $aInviteList} 
                <tr class="thTableOddRow yncontact_email_letter">
                    <td colspan="3" class="label"><div id="letter_{$letter}" style="padding-left:5px;">{$letter}</div></td>
                </tr>
                {foreach from=$aInviteList key=i item=aInvite}
                <tr class='thTableOddRow yncontact_email_contact' id="row_{php}echo ++$counter;{/php}" >
                    <td align="left" width="30px">
                        <input id="check_{php}echo $counter;{/php}" class="contact_item" type="checkbox" name="items[]" value="{$aInvite.email}" />
                    </td>
                    <td width = '60%' onclick='check_toggle({php}echo $counter;{/php},document.getElementById("row_{php}echo $counter;{/php}"),true);'>{$aInvite.name}</td>
                    <td class="name" style="width:150px">&lt;{$aInvite.email}&gt;</td>
                </tr>
                {/foreach}
                {/if}
                {/foreach}
            </table>
        </div>
    </div>
    <div class="clear"></div>
    {pager}
    <div class="clear"></div>
    <form method="post" action="{url link='contactimporter.invite'}" class="global_form yncontact_emal_invite" name='openinviterform' id="openinviterform" enctype="application/x-www-form-urlencoded" onsubmit="return check_select_invite();">
        <div class="form-wrapper" id="message-wrapper" style="clear:both;margin-top:5px">
            {if phpfox::getUserParam('contactimporter.hide_the_custom_invittation_message') == false}
            <div class="form-label" id="message-label"><br/>
                <p>
                	<label class="optional" for="message" style="margin-top:5px">{phrase var='contactimporter.custom_message_title'}</label>
                </p>                    
                <textarea rows="6" cols="45" id="message" name="message">{phrase var='contactimporter.default_invite_message_text'}</textarea>
                
            </div>
            {else}
            <input type="hidden" id="message" name="message" style="display: none;" value="{phrase var='contactimporter.default_invite_message_text'}"/>
            {/if}
        </div>
        <div class="yncontact_custom_message">
            <input id="send-button" class="button" type="button" value="{phrase var='contactimporter.send_invites'}" />
            {if  ($sProvider == 'yahoo' || $sProvider == 'gmail' || $sProvider == 'hotmail')}
            <input id="sendall-button" class="button" type='button' name='send' value="{phrase var='contactimporter.send_all' contacts=$iCnt}"/>
            {/if}
            <input id="skip-button" class="button" type='button' value ="{phrase var='contactimporter.skip'} &gt;&gt;" onclick="location.href='{url link='contactimporter'}';">
        </div>
        { if Phpfox::getUserId() > 0}
        <h3>{phrase var='invite.send_a_custom_invitation_link'}</h3>
        {phrase var='invite.send_friends_your_custom_invitation_link_by_copy_and_pasting_it_into_your_own_email_application'}:
        <div class="p_4">
            <input type="text" name="null" value="{$sIniviteLink}" id="js_custom_link" size="40" style="width:75%;" onfocus="this.select();" onkeypress="return false;" />
        </div>
        {/if}
    </form>
    {/if}
</div>