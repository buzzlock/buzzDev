<div class="yc large_item" style='border: 1px solid #dfdfdf; padding: 4px;'>
	{if $bIsShowContestPhoto}
		<div class="yc_view_image">
			<a class="large_item_image" href="{permalink module='contest' id=$aContest.contest_id title=$aContest.contest_name}" title="{$aContest.contest_name|clean}" style="background-image:url({img return_url=true path='core.url_pic' file='contest/'.$aContest.image_path suffix='_160' max_width=150})">
			</a>
		</div>
	{/if}

	<div class="large_item_info" style="padding: 5px;">
		<a class="small_title" href="{permalink module='contest' id=$aContest.contest_id title=$aContest.contest_name}" title="{$aContest.contest_name|clean}" target="_blank" style="padding-bottom: 5px; text-decoration: none;">
			{$aContest.contest_name|clean|shorten:50:'...'}
		</a>

		{if $bIsShowDescription}
		<div class="extra_info">
			{$aContest.short_description}
		</div>
		{/if}
	</div>

 	<input type='button' style="margin: 5px 0 5px 40px; background: #627AAC; color: #fff; border: 1px #365FAF solid; height: 30px; cursor: pointer;"  name='val[join]' value="{phrase var='contest.join'} {phrase var='contest.contest'}" target="_blank" onclick="window.open( '{permalink module='contest' id=$aContest.contest_id title=$aContest.contest_name}'); return false;" />


</div>
