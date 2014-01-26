
{if $bIsUserProfile}
<script type='text/javascript'>

$Behavior.ynadvancedphotoInitUserIdInProfile = function() {l}
	oCore['profile.user_id'] = {$ynadvancedphoto_user_id}	
{r}
</script>
{/if}
{if $iMostRecentYear !== 0}
	<div class="clear"> </div>
	<input type="hidden" id="yn_timeline_photo_header_is_load_more_{$iMostRecentYear}" value="0"/>
	<div class="yn_timeline_photo_year_header">
		<span class="adv-calendar"><img src="{$corepath}module/advancedphoto/static/image/calendar.png" /></span>
		{*<span class="open-close"><img src="{$corepath}module/advancedphoto/static/image/add.png" /></span>*}
		<a href="#" onclick='ynphoto.togglePhotoYear({$iMostRecentYear}, 1, {$iMaxPhotosPerLoad}); return false;'> {$iMostRecentYear}</a>
	</div>
	<div class="clear"> </div>

	<div id='yn_loadmore_space_holder_{$iMostRecentYear}' > 
		{module name='advancedphoto.yntimelinelistphoto' bIsAlreadySetSearch='1' iYear=$iMostRecentYear}	
	</div>
	
{/if}

{foreach from=$aYears item=iYear}
	<input type="hidden" id="yn_timeline_photo_header_is_load_more_{$iYear}" value="1"/>
	<div class="yn_timeline_photo_year_header">
		<span class="adv-calendar"><img src="{$corepath}module/advancedphoto/static/image/calendar.png" /></span>
		<span><img src="{$corepath}module/advancedphoto/static/image/add.png" /></span>
		<a href="#" onclick='ynphoto.togglePhotoYear({$iYear}, 1, {$iMaxPhotosPerLoad}); return false;'> {$iYear}</a>
	</div>
	<div id='yn_loadmore_space_holder_{$iYear}' style="display:none;"> </div>
{/foreach}


