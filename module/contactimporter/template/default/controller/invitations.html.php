<?php
defined('PHPFOX') or exit('NO DICE!');
?>
{if count($aInvites)}
<form method="post" action="{url link='current'}" id="js_form" class="yncotact_form_invitations">
    <div class="main_break" style=" margin: -5px 5px 4px;padding: 5px 5px 10px;">
        {foreach from=$aInvites name=invite item=aInvite}
        <div id="js_invite_{$aInvite.invite_id}" class="js_selector_class_{$aInvite.invite_id} {if is_int($phpfox.iteration.invite/2)}row1{else}row2{/if}{if $phpfox.iteration.invite == 1} row_first{/if}">
            <div class="go_left t_center" style="width:20px;">
                <input type="checkbox" name="val[]" value="{$aInvite.invite_id}" onclick="$Core.inviteContactimpoter.enableDelete(this)" class="checkbox" id="js_selector_checkbox_{$aInvite.invite_id}" />
            </div>
            <div class="go_left" style="width:400px;">                                         
				{if isset($aInvite.invited_name) && $aInvite.invited_name}
					{$aInvite.count}. {$aInvite.invited_name} ({$aInvite.email|shorten:30:'...'})
				{else}
					{$aInvite.count}. {$aInvite.email|shorten:50:'...'}
				{/if}
            </div>
            <div class="t_right">
                {literal}
                <script type="text/javascript">
                    function reSendInvitation(id)
                    {
                        $.ajaxCall('contactimporter.reSendInvitation','invite_id='+id);                                                
                    }
                </script>
                {/literal}
                <ul id = "resend_{$aInvite.invite_id}" class="yncontact_invitations_action">
                	<li>
	                    <a title="{phrase var='contactimporter.delete_invitation'}" href="{url link='current' del=$aInvite.invite_id}">{img theme='misc/delete.png' alt='' class='go_right' }</a>
                	</li>
                	<li>
                		{if $aInvite.canResendMail && $aInvite.is_resend == 0}
                        <a class="inlinePopup"  title="{phrase var='contactimporter.invitation_message'}"  border="0" href="#?call=contactimporter.reSendInvitation&invite_id={$aInvite.invite_id}&width=300&height=200"><img alt="{phrase var='contactimporter.resend_invitation'}" title="{phrase var='contactimporter.resend_invitation'}" border="0" width="15" height="15" src="{$core_url}module/contactimporter/static/image/send_mail.png"></a>
                        {/if}
                	</li>
                </ul>

            </div>
            <div class="clear"></div>
        </div>
        {/foreach}
    </div>
</form>

{pager}
<div class="moderation_holder" style="margin-bottom: 15px;">
    <a rel="select" class="moderation_action moderation_action_select" href="#" style="display: inline;" onclick="$Core.inviteContactimpoter.localSelector('all');">{phrase var='contactimporter.select_all'}</a>
    <a rel="unselect" class="moderation_action moderation_action_unselect" href="#" style="display: none;" onclick="$Core.inviteContactimpoter.localSelector('none');" >{phrase var='contactimporter.un_select_all'}</a>
    <span class="moderation_process"><img alt="" src="http://dev.hm/testbug/theme/frontend/default/style/facebookish/image/ajax/add.gif"></span>
    <a class="moderation_drop not_active" href="#"><span>{phrase var='core.with_selected'}{*(<strong class="js_global_multi_total">1</strong>)*}</span></a>		
    <ul style="display: none; margin-top: -97px;">
        <li><a class="moderation_clear_all" href="#"  onclick="$Core.inviteContactimpoter.localSelector('none');">{phrase var='core.clear_all_selected'}</a></li>
        <li><a rel="delete" class="moderation_process_action" href="#" onclick="return $Core.inviteContactimpoter.doAction(this.rel);" >{phrase var='invite.delete'}</a></li>
    </ul>
</div>
{else}
<div class="extra_info">
    {phrase var='invite.there_are_no_pending_invitations'}
    <ul class="action">
        <li><a href="{url link='contactimporter'}">{phrase var='invite.invite_your_friends'}</a></li>
    </ul>
</div>
{/if}