<div id="js_contest_block_invite_friends" class="js_contest_block page_section_menu_holder" style="display:none;">
	{if Phpfox::isModule('friend')}
	<form method="post" action="{url link='current'}" id="ynfr_edit_invite_friend_form" onsubmit="" enctype="multipart/form-data">
		{if $iEntryId}
			<input type='hidden' name='val[contest_entry_id]' value = '{$iEntryId}'/>
		{/if}
		
		<div style="width:75%; float:left; position:relative;">				
			<h3 style="margin-top:0px; padding-top:0px;">{phrase var='contest.invite_friends'}</h3>
			<div style="height:370px;">			
				{if isset($aForms.contest_id)}
				{module name='friend.search' input='invite' hide=true friend_item_id=$aForms.contest_id friend_module_id='contest'}
				{else}
				{module name='friend.search' input='invite' hide=true friend_item_id=$aContest.contest_id friend_module_id='contest'}
				{/if}
			</div>
			{/if}
			<h3>{phrase var='contest.invite_people_via_email'}</h3>
			<div class="p_4">
				<textarea cols="40" rows="8" name="val[emails]" style="width:98%; height:60px;"></textarea>
				<div class="extra_info">
					{phrase var='contest.separate_multiple_emails_with_a_comma'}
				</div>
			</div>

			
			<h3>{phrase var='contest.add_a_personal_message'}</h3>
			
			<div class="table">
				<div class="table_left">
					{phrase var='contest.subject'}:
				</div>
				<div class="table_right label_hover">
					<input type="text" name="val[subject]" value="{$aMessage.subject}" id="email_subject" size="60" style="width: 100%; height: 26px" />
				</div>
			</div>

			<div class="table">
				<div class="table_left">
					{phrase var='contest.message'}:
				</div>
				<div class="table_right label_hover">
					<textarea cols="40" rows="8" name="val[personal_message]" style="width:115%; height:250px;">
						{$aMessage.message}
					</textarea>			
				</div>
			</div>

			{module name='contest.keyword-placeholder'}
			
			<div class="p_top_8">
				{if $bIsPopup}
					<input type="button" name="val[submit_invite]"  value="{phrase var='contest.send_invitations'}"id='yncontest_invite_friend_button' class="button" onclick="$('#yncontest_invite_friend_button').attr('disabled', 'disabled'); $('#ynfr_edit_invite_friend_form').ajaxCall('contest.submitInviteForm', 'contest_id={$aContest.contest_id}'); return false;"/>
				{else}
					<input type="submit" name="val[submit_invite]"  value="{phrase var='contest.send_invitations'}" class="button" />
				{/if}
				
			</div>				

		</div>
		{if Phpfox::isModule('friend')}
		<div style="margin-left:77%; position:relative;">
			<div class="block">
				<div class="title">{phrase var='contest.new_guest_list'}</div>				
				<div class="content">
					<div class="label_flow" style="height:330px;">
						<div id="js_selected_friends"></div>
					</div>
				</div>
			</div>
		</div>		

		<div class="clear"></div>		
	</form>
	{/if}
</div>

<script type="text/javascript">
    $Behavior.yncontestSetupInviteLayout = function() {l}
         $("#js_friend_search_content").append('<div class="clear" style="padding:5px 0px 10px 0px;"><input type="button" onclick="yncontest.invite.clickAll();" value="{phrase var="contest.select_all"}" /><input type="button" onclick="yncontest.invite.unClickAll();" value="{phrase var="contest.un_select_all"}" /> </div>');
         $("#js_friend_search_content").parent().parent().css('height','');
    {r}
</script>