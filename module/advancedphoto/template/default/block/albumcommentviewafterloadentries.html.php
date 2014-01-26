<div class="clear"></div>
<div class="t_right" id="ynadvphoto_paging_real_holder">
</div>
<div class="extra_info" id="ynadvphoto_album_in_this_album_holder"  {if !$sJsAlbumTagContent} style="display:none" {/if}>
	<b>{phrase var='advancedphoto.in_this_album'}: </b> <span id="ynadvphoto_album_in_this_album"> {$sJsAlbumTagContent} </span>
</div>		

<div class="clear"></div>
<div {if $aForms.view_id != 0}style="display:none;" class="js_moderation_on"{/if}>
	{module name='advancedphoto.yncomment'}
</div>
<script type="text/javascript">
	$Behavior.ynadvphotoMovePagingToRealHolder = function() {l}
		ynphoto.movePagingToRealHolder();
	{r}
</script>