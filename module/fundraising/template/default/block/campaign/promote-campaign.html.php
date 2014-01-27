<?php
/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');
?>

<script type="text/javascript">
	$Core.loadStaticFile('{css file='ynfundraising.css' module='fundraising'}');
</script>

<form id="ynfr_promote_campaign_form">
	<div style="width:300px;">
		<h3>{phrase var='fundraising.donate_box_code'}:</h3>
		<div class="table_right">
			<textarea id="ynfr_promote_campaign_badge_code_textarea" readonly="readonly" cols="40" rows="15" style="width:300px; height:150px;">{$sBadgeCode}</textarea>
		</div>

		<div class="clear"></div>
		<h3>{phrase var='fundraising.option_to_show'}:</h3>
		<input type="checkbox" checked ="true" name="val[donate_button]" onclick="$('#ynfr_promote_campaign_form').ajaxCall('fundraising.changePromoteBadge', 'campaign_id={$iCampaignId}&amp')" > {phrase var='fundraising.donate_button'} <br> 
		<input type="checkbox" checked ="true" name="val[donors]" onclick="$('#ynfr_promote_campaign_form').ajaxCall('fundraising.changePromoteBadge', 'campaign_id={$iCampaignId}&amp')" > {phrase var='fundraising.donors_upper'} <br> 
	</div>

	<div class="ynfr promote_campaign donate_box">
		
		<div id ="ynfr_promote_iframe">{$sBadgeCode} </div>
	
	</div>
</form>

