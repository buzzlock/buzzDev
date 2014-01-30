<?php 

defined('PHPFOX') or exit('NO DICE!'); 

?>
<div class="global_attachment_holder_section" id="global_attachment_videochannel">	
	<div><input type="hidden" name="val[video_inline]" value="1" /></div>
	<div class="table">
		<div class="table_left">
			{phrase var='videochannel.title'}:
		</div>
		<div class="table_right">
			<input type="text" name="val[video_title]" style="width:90%;" id="js_form_videochannel_title" />
		</div>
	</div>
	<div class="table">
		<div class="table_left">
			{phrase var='videochannel.video'}:
		</div>
		<div class="table_right">	
			<div><input type="file" name="video" id="global_attachment_videochannel_file_input" value="" onchange="$bButtonSubmitActive = true; $('.activity_feed_form_button .button').removeClass('button_not_active'); $Core.resetActivityFeedErrorMessage();" /></div>
			<div class="extra_info">
				{phrase var='videochannel.select_a_video_to_attach'}
			</div>
		</div>
	</div>
</div>
{literal}
<script type="text/javascript">
$ActivityFeedCompleted.resetVideoForm = function()
{
	$('#js_form_videochannel_title').val('');
	$('#global_attachment_videochannel_file_input').val('');
}
</script>
{/literal}
