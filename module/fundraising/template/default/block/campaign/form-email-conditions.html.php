<?php
/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');
?>


<div id="js_fundraising_block_email_conditions" class="js_fundraising_block page_section_menu_holder" style="display:none;">
<form method="post" action="{url link='current'}" id="ynfr_edit_email_conditions_form" onsubmit="" enctype="multipart/form-data">
    <div style="width:75%; float:left; position:relative;">
        <h3 style="margin-top:0px; padding-top:0px;">{phrase var='fundraising.thanks_donor'}</h3>
        <div class="table">
            <div class="table_left">
                {phrase var='fundraising.subject'}:
            </div>
            <div class="table_right label_hover">
                <input type="text" name="val[email_subject]" value="{$aForms.email_subject}" id="email_subject" size="60" style="width: 80%;" />
            </div>
        </div>
        <div class="table">
            <div class="table_left">
                {phrase var='fundraising.message'}:
            </div>
            <div class="table_right label_hover">
              
                
            </div>
        </div>
        {if !empty($aForms.target_email)}
        <div class="table">
            <div class="table_left">
                {phrase var='fundraising.send_fundraising_letter_online'}
            </div>
            <div class="table_right">
                <div class="item_is_active_holder">
                    <span class="js_item_active item_is_not_active"><input type="radio" name="val[is_send_online]" value="0" class="checkbox" style="vertical-align:middle;"{value type='checkbox' id='is_send_online' default='0' selected=true}/> {phrase var='fundraising.no'}</span>
                    <span class="js_item_active item_is_active"><input type="radio" name="val[is_send_online]" value="1" class="checkbox" style="vertical-align:middle;"{value type='checkbox' id='is_send_online' default='1'}/> {phrase var='fundraising.yes'}</span>
                </div>
            </div>
        </div>
        {/if}

		{module name='fundraising.keyword-placeholder'}


        <h3>{phrase var='fundraising.term_condition'}</h3>
        <div class="table_right">
            <textarea cols="40" rows="8" name="val[term_condition]" style="width:98%; height:60px;">{$aForms.term_condition}</textarea>
        </div>

        <div class="table_clear">
            <ul class="table_clear_button">
                <li><input type="submit" name="val[submit_email_conditions]" value="{phrase var='fundraising.save'}" class="button"/></li>
                {if $bIsEdit && $aForms.is_draft == 1}
                <li><input type="submit" name="val[publish_email_conditions]" value="{phrase var='fundraising.publish'}" class="button"/></li>
                {/if}
            </ul>
            <div class="clear"></div>
        </div>
    </div>
</form>
</div>
