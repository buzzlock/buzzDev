{plugin call='mobiletemplate.get_var_to_hide_profile_header'}
{if !isset($isHideProfileHeader) || $isHideProfileHeader != 'yes'}
<div id="mobile_profile_header">
        {module name="mobiletemplate.profile-top"}
		<div>
			<ul class="display-box ym-button-profile">
			{if Phpfox::getUserId() != $aUser.user_id}
				{if Phpfox::isModule('mail') && Phpfox::getService('user.privacy')->hasAccess('' . $aUser.user_id . '', 'mail.send_message')}
					<li class='display-box-item'><a href="#" onclick="$Core.composeMessage({left_curly}user_id: {$aUser.user_id}{right_curly}); return false;">{phrase var='profile.message'}</a></li>
				{/if}
				{if Phpfox::isModule('friend') && !$aUser.is_friend}
					<li class='display-box-item' id="js_add_friend_on_profile"><a href="#" onclick="return $Core.addAsFriend('{$aUser.user_id}');" title="{phrase var='profile.add_to_friends'}">{phrase var='profile.add_to_friends'}</a></li>
				{/if}
				{if $bCanPoke && Phpfox::getService('user.privacy')->hasAccess('' . $aUser.user_id . '', 'poke.can_send_poke')}
					<li class='display-box-item' id="liPoke">
						<a href="#" id="section_poke" onclick="$Core.box('poke.poke', 400, 'user_id={$aUser.user_id}'); return false;">{phrase var='poke.poke' full_name=''}</a>
					</li>
				{/if}			
			{/if}
			</ul>
			<div class="clear"></div>				
		</div>	
		{module name="mobiletemplate.profile-info"}	
		{*	
    	<ul class="mobile_profile_header_menu">
    		<li><a href="{url link=$aUser.user_name'.wall'}"{if !$bIsInfo} class="active"{/if}>{phrase var='profile.wall'}</a></li>
    		<li><a href="{url link=$aUser.user_name'.info'}"{if $bIsInfo} class="active"{/if}>{phrase var='profile.info'}</a></li>
    	</ul>
    	*}
	<div class="clear"></div>
</div>
{/if}