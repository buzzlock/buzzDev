<?php
/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');
?>

{literal}
<script type="text/javascript">
	validate_reason_close_form = function() {
		if(trim($('#ynfr_close_reason_text').val()) == '')	
		{
			$('#ynfr_empty_reason').show();
			return false;
		}
		else
		{
			return true;
		}
	}
</script>
{/literal}
<form method="post" onsubmit="if(validate_reason_close_form()) {l}$(this).ajaxCall('fundraising.closeCampaign', 'campaign_id={$aCampaign.campaign_id}&amp;is_owner=0&amp;submit_reason=1');js_box_remove(this);{r}return false;">
	<div id="ynfr_empty_reason" class="error_message" style="display:none">
		{phrase var='fundraising.please_enter_the_reason'}
	</div>
	<div class="table">
		<div class="table_left">
			{phrase var='fundraising.reason'}:
		</div>
		<div class="table_right">
			<textarea cols="59" rows="10" name="message" id="ynfr_close_reason_text"></textarea>
		</div>
		<div class="extra_info" >
			* {phrase var='fundraising.send_reason_notice'}
		</div>
		<input type="submit" class="button" value="{phrase var='fundraising.close_this_campaign'}">

	</div>
</form>