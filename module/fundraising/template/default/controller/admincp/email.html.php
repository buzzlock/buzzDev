<?php 
/**
 * [PHPFOX_HEADER]
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
{$sCreateJs}
<form method="post" id="frmEmailTemplate" action="{url link='admincp.fundraising.email'}" name="js_form">
    <div class="table_header">
        {phrase var='fundraising.email_templates'}
    </div>

    <div class="table">
        <div class="table_left">
            {required}{phrase var='fundraising.email_templates_types'}:
        </div>
        <div class="table_right">
            <select name="val[type_id]" id="type_id" onchange="$.ajaxCall('fundraising.fillEmailTemplate', 'type_id=' + $(this).val());">
                <option value="">{phrase var='fundraising.select'}:</option>
                <option value="{$aTypes.createcampaignsuccessful_owner}">{phrase var='fundraising.create_campaign_successfull_owner'}</option>
                <option value="{$aTypes.thankdonor_donor}">{phrase var='fundraising.thank_donor'}</option>
                <option value="{$aTypes.updatedonor_owner}">{phrase var='fundraising.update_donor_owner'}</option>
                <option value="{$aTypes.campaignexpired_owner}">{phrase var='fundraising.campaign_expired_owner'}</option>
                <option value="{$aTypes.campaignexpired_donor}">{phrase var='fundraising.campaign_expired'}</option>
                <option value="{$aTypes.campaigncloseduetoreach_owner}">{phrase var='fundraising.campaign_closed_due_to_reach_owner'}</option>
                <option value="{$aTypes.campaigncloseduetoreach_donor}">{phrase var='fundraising.campaign_closed_due_to_reach'}</option>
                <option value="{$aTypes.campaignclose_owner}">{phrase var='fundraising.campaign_closed_owner'}</option>
                <option value="{$aTypes.invitefriendletter_template}">{phrase var='fundraising.invite_friend_letter_template'}</option>
            </select>
        </div>
        <div class="clear"></div>
    </div>

    <div class="table">
        <div class="table_left">
            {phrase var='fundraising.subject'}:
        </div>
        <div class="table_right">
            <input type="text" name="val[email_subject]" value="{value type='input' id='email_subject'}" id="email_subject" size="40" maxlength="150" />
        </div>
        <div class="clear"></div>
    </div>

    <div class="table">
        <div class="table_left" id="lbl_html_text">
            {phrase var='fundraising.content'}:
        </div>
        <div class="table_right">
            {editor id='email_template' rows='15'}
        </div>
        <div class="clear"></div>
    </div>

    <div class="extra_info table">
        	{module name='fundraising.keyword-placeholder'}
    </div>
    <div class="table_clear">
        <input type="submit" value="{phrase var='fundraising.save_now'}" class="button" />
    </div>
    <div class="table_clear"></div>
</form>

<script type="text/javascript">
$Behavior.ffundAdmincpEmail = function() {l}
	$('#type_id option').each(function() {l} 
		if($(this).val() == {$iCurrentTypeId})
		{l}
			$(this).attr('selected', 'selected');
		{r}
	{r});
{r}
</script>