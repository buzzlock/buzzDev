{literal}
<script type="text/javascript">
	$Behavior.fwallPhotoHook = function(){
		var description = $('.photo_view_comment div[id^="js_photo_description_"]:first');
		description.html(description.text());
	}
</script>
{/literal}