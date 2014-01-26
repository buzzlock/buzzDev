<form id="yncontest_promote_contest_form">
	<div style="width:300px;">
		<div class="table_right">
			<textarea id="yncontest_promote_contest_badge_code_textarea" readonly="readonly" cols="40" rows="15" style="width:300px; height:150px;">{$sBadgeCode}</textarea>
		</div>

		<div class="clear" style="margin-bottom: 10px;"></div>

		<input type="checkbox" checked ="true" name="val[photo]" onclick="$('#yncontest_promote_contest_form').ajaxCall('contest.changePromoteBadge', 'contest_id={$iContestId}&amp')" /> {phrase var='contest.show_photo'} <br /> 
		<input type="checkbox" checked ="true" name="val[description]" onclick="$('#yncontest_promote_contest_form').ajaxCall('contest.changePromoteBadge', 'contest_id={$iContestId}&amp')" /> {phrase var='contest.show_description'} <br /> 
	</div>

	<div class="yncontest promote_contest promote_box">
		<div id ="yncontest_promote_iframe">{$sBadgeCode}</div>
	</div>
</form>

