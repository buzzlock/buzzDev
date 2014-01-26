<?php 
/**
 * [PHPFOX_HEADER]
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<form method="post" id="frmEmailTemplate" action="{url link='admincp.contest.email'}" name="js_form">
    <div class="table_header">
        {phrase var='contest.email_templates'}
    </div>

    <div class="table">
        <div class="table_left">
            {required}{phrase var='contest.email_templates_types'}:
        </div>
        <div class="table_right">
            <select name="val[type_id]" id="type_id" onchange="$.ajaxCall('contest.fillEmailTemplate', 'type_id=' + $(this).val());">
                <option value="">{phrase var='contest.select'}:</option>
                {foreach from=$aTemplateTypes item=aTemplateType}
                    <option value="{$aTemplateType.id}"> {$aTemplateType.phrase}</option>
                {/foreach}
            </select>
        </div>
        <div class="clear"></div>
    </div>

    <div class="table">
        <div class="table_left">
            {phrase var='contest.subject'}:
        </div>
        <div class="table_right">
            <input type="text" name="val[subject]" value="{value type='input' id='subject'}" id="subject" size="40" maxlength="150" />
        </div>
        <div class="clear"></div>
    </div>

    <div class="table">
        <div class="table_left" id="lbl_html_text">
            {phrase var='contest.content'}:
        </div>
        <div class="table_right">
            {editor id='content' rows='15'}
        </div>
        <div class="clear"></div>
    </div>

    <div class="extra_info table">
        	{module name='contest.keyword-placeholder'}
    </div>
    <div class="table_clear">
        <input type="submit" value="{phrase var='contest.save_now'}" class="button" />
    </div>
    <div class="table_clear"></div>
</form>

<script type="text/javascript">
	$('#type_id option').each(function() {l} 
		if($(this).val() == {$iCurrentTypeId})
		{l}
			$(this).attr('selected', 'selected');
		{r}
	{r});
</script>