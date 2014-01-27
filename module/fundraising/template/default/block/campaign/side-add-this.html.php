<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

?>
<div id="ynfr-shareinfo">
    <input type='hidden' value='{$aCampaign.campaign_id}' id='campaign_id' />
    <input type='hidden' value='{$sToken}' id='token' />
</div>
<div class="ynfr-addthis ynfr-share"><span>{phrase var='fundraising.shares_upper'}</span><span class="ynfr-question" title="{phrase var='fundraising.total_shares_for_this_time_period'}">?</span><span class="ynfr-value">{$iShare}</span></div>
<div class="ynfr-addthis ynfr-click"><span>{phrase var='fundraising.clicks_upper'}</span><span class="ynfr-question" title="{phrase var='fundraising.total_traffic_back_from_shares_for_this_time_period'}">?</span><span class="ynfr-value">{$iClick}</span></div>
<div class="ynfr-addthis viral"><span>{phrase var='fundraising.viral_lift'}</span><span class="ynfr-question" title="{phrase var='fundraising.percentage_increase_in_traffic_due_to_shares_and_clicks'}">?</span><span class="ynfr-value">{$iViralLift}%</span></div>

<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style " onclick="share();" 
     addthis:title="{phrase var='fundraising.share_this_page_now'}"
     addthis:description="{phrase var='fundraising.share_this_page_now'}">
    <a class="addthis_button_facebook"></a>
    <a class="addthis_button_twitter"></a>
    <a class="addthis_button_preferred_3"></a>
    <a class="addthis_button_compact"></a>
    <a class="addthis_counter addthis_bubble_style"></a>
</div>
{literal}
<script type="text/javascript">
   

	$Behavior.ynfrloadAddthisJs = function() {
		 var addthis_config = {
			"data_track_addressbar":false
		};
		window._ate= null;
		window.addthis = null;
		$.getScript("http://s7.addthis.com/js/300/addthis_widget.js");	
	};

</script>

{/literal}

{literal}
<script type="text/javascript">
    function share() {
        $.ajaxCall('fundraising.SupporterShare', 'campaign_id=' + $('#campaign_id').val());
    }
</script>
{/literal}
<!-- AddThis Button END -->