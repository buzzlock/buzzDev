<div class="ym-cover-section">
	{module name="profile.logo"}
</div>

<div id="mobile_profile_photo">

	{*{if $aUser.is_online}<span class="profile_online_status">({phrase var='profile.online'})</span>{/if}*}

	<div id="mobile_profile_photo_image">
		{$sProfileImage}
	</div>
	<div id="mobile_profile_photo_name">
		<div class="ym-profile-name">{$aUser.full_name|clean|split:50}</div>
		{if isset($aUserDetails) && isset($aUserDetails.birth_date) && !empty($aUserDetails.birth_date)}<p>{phrase var='mobiletemplate.born_on'} {$aUserDetails.birth_date}</p>{/if}
		{if isset($sRelationship) && !empty($sRelationship)}<p>{phrase var='mobiletemplate.relationship_status'}: {$sRelationship}</p>{/if}
		{if isset($aUserDetails) && isset($aUserDetails.location) && !empty($aUserDetails.location)}<p>{phrase var='mobiletemplate.lives_in'} {$aUserDetails.location}</p>{/if}
	</div>
</div>

