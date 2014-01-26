<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<h3>{phrase var='contactimporter.your_contacts'}</h3>

<p class="description" style="margin-bottom:5px;">{phrase var='contactimporter.you_can_send_max_invitations_per_time' max=$max_invitation}</p>
<p class="description" style="margin-bottom:5px;">{phrase var='contactimporter.the_following_people_are_not_your_friends'}.</p>
<div style='display:none' id="error">
	<ul class="form-errors"><li><ul class="errors"><li id='error_content'></li></ul></li></ul>
</div>
<table class='thTableOddRow' align='left' cellspacing='0' cellpadding='5px' style="width: 100%;border-left:2px solid #EDEDED;border-right:2px solid #EDEDED;">
<tr style='-moz-background-clip:border;-moz-background-inline-policy:continuous;-moz-background-origin:padding;background:#EDEDED none repeat scroll 0 50%;border-bottom:1px solid #C0C0C0;margin:10px auto 0;font-weight:bold;clear:both;width:100%'>

	<td width="50px">
		<input type='checkbox' id='checkallBox' onclick='toggleAll(this)' name='toggle_all' title='Select/Deselect all'>
	</td>
	<td align="left">&nbsp;&nbsp;&nbsp;{phrase var='contactimporter.name'}</td>
	<td>
		&nbsp;&nbsp;&nbsp;
	</td>
</tr>
</table>
<div class="clear"></div>
<div class="wrapper-list">
	<div id="div_list_view" style="width:100%;padding-left:0px;">
		<table class='thTableOddRow' align='left' cellspacing='0' cellpadding='5px' style="width:100%;border:0px solid" >
		<?php $counter=0;$is_contact=true;?>
		{foreach from=$aInviteLists key=letter item=invites}
		{if count($invites) > 0}
		<?php $is_contact=false;?>
			<tr  class='thTableOddRow' id="title">
				<td colspan="4" class="label"><div style="padding-left:5px;" id="letter_{$letter}">{$letter}</div></td>
			</tr>
			{foreach from=$invites item=invite}
				<?php $counter++?>
				<tr class='thTableOddRow'  id='row_<?php echo $counter?>'>
					<div class="wrapper-row">
					<td>
						<input id='check_<?php echo $counter?>' name='items[]' onclick='check_toggle(<?php echo $counter?>,document.getElementById("row_<?php echo $counter?>"),false);' value='{$invite.key}' type='checkbox' class='thCheckbox'>
						<input type='hidden' name='val[email_<?php echo $counter?>]' value='{$invite.key}'>
						<input type='hidden' name='val[name_<?php echo $counter?>]' value='{$invite.name}'></td>
						<td onclick='check_toggle(<?php echo $counter?>,document.getElementById("row_<?php echo $counter?>"),true);'>{$invite.name}</td>
						<td class="name" onclick='check_toggle(<?php echo $counter?>,document.getElementById("row_<?php echo $counter?>"),true);'>&lt;{$invite.name}&gt;</td>
						{if $provider_box!='youtube'}
						<td align="right">
						{if $invite.pic eq ''}
							<img height='30px' width='30px' src="{$core_url}module/contactimporter/static/image/nophoto_user_thumb_icon.png">
						{else}
							<img height='30px' width='30px' src='{$invite.pic}'>
						{/if}
						</td>
                        {/if}
					</div>
				</tr>
			{/foreach}
			{else}
				<script type="text/javascript">$('#id_letter_{$letter}').addClass('hidden');</script>
			{/if}
			{/foreach}
			<?php if($is_contact==true) echo "<tr class='thTableOddRow'><td align='center' style='padding:20px;' colspan='3'>".Phpfox::getPhrase('contactimporter.there_is_no_contact_in_your_account')."</td></tr>";?>
		</table>
	</div>
</div>
<div class="clear"></div>
<div style="float:left;margin:10px;0px;position:absolute;z-index:1000;">
    {if {$sLinkPrev}}<a href="{$sLinkPrev}">{phrase var='contactimporter.view_number_contacts_previous' number=5000}</a> | {/if}
    {if {$sLinkNext}}<a href="{$sLinkNext}">{phrase var='contactimporter.view_number_contacts_next' number=5000}</a>{/if}
</div>
{pager}
<div class="clear"></div>

<script type="text/javascript">{literal}var total_allow_select ={/literal}{$max_invitation}{literal} ;</script>{/literal}
<form method="post" action="{url link='contactimporter.addcontact'}" class="global_form" name='openinviter' enctype="application/x-www-form-urlencoded" onsubmit="return check_select();">
	<div class="form-wrapper" id="message-wrapper" style="clear:both">
		{if phpfox::getUserParam('contactimporter.hide_the_custom_invittation_message') == false}
		<div class="form-label" id="message-label" style="width: 120px;text-align: left;">
			<label class="optional" for="message">{phrase var='contactimporter.custom_message_title'}
				<textarea rows="6" cols="45" id="message" name="message">{phrase var='contactimporter.default_invite_message_text'}</textarea>
			</label>
		</div>
                {else}
                    <input type="hidden" id="message" name="message" style="display: none;" value="{phrase var='contactimporter.default_invite_message_text'}"/>
		{/if}
		<div class="form-element" id="message-element"></div>
	</div>
	<input type="hidden" value="{if isset($in_lst)}{$in_lst}{/if}" name="invite_list" />
	<input type="hidden" value="do_add" name="task" />
	<input type="hidden" value="{$plugType}" name="plugType" />
	<input type="hidden" value="send_invites" name="send_invite" />
	<input type="hidden" name="oi_session_id" value="{$oi_session_id}" />
	<input type="hidden" name="provider_box" value="{$provider_box}" />
	<span>
		<input class="button" type='submit' id='submit' name='send' value="{phrase var='contactimporter.send_invites'}"/>
		{if isset($friends_count) && $friends_count >= $max_invitation}<input class="button" type='button' name='send' value="{phrase var='contactimporter.send_all' contacts=$friends_count}" onclick="sendallPopup('{$provider_box}', {$friends_count})"/>{/if}
		<input class="button" type='button' id='' name='send' value ="{phrase var='contactimporter.skip'} &gt;&gt;" onclick="document.getElementById('skip').submit();">
	</span>
	<h3>{phrase var='invite.send_a_custom_invitation_link'}</h3>
	{phrase var='invite.send_friends_your_custom_invitation_link_by_copy_and_pasting_it_into_your_own_email_application'}:
	<div class="p_4">
		<input type="text" name="urlInviteLink"  value="{$sIniviteLink}" id="js_custom_link" size="40" style="width:75%;" onfocus="this.select();" onkeypress="return false;" />
	</div>
	<input type="hidden" value="" id="contacts"  name="contacts" />
</form>
<form method="post" action="{url link='contactimporter'}" id="skip">
	<input type="hidden" value="{if isset($in_lst)}{$in_lst}{/if}" name="invite_list" />
	<input type="hidden" value="{$plugType}" name="plugType" />
	<input type='hidden' name='oi_session_id' value="{$oi_session_id}" />
	<input type='hidden' name='provider_box' value="{$provider_box}">
	<input type="hidden" value="skip" name="task" />
</form>