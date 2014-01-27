<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

?>

	<form method="post" action="{url link='current'}" class="ynfr_add_edit_form" id="ynfr_edit_campaign_contact_information_form" onsubmit="return ynfr_checkEmails('#email_address')" enctype="multipart/form-data">
	<div id="js_fundraising_block_contact_information" class="js_fundraising_block page_section_menu_holder" style="display:none;">

        <div class="table">
            <div class="table_left">
                <div class="row_info_title">
                    {phrase var='fundraising.personal_information'}
                </div>
            </div>
        </div>

        <div class="table">
            <div class="table_left">
                <label for="full_name">{phrase var='fundraising.full_name'}: </label>
            </div>
            <div class="table_right">
                <input type="text" name="val[contact_full_name]" value="{value type='input' id='contact_full_name'}" id="contact_full_name" size="60" />
            </div>
        </div>

        <div class="table">
            <div class="table_left">
                <label for="phone">{phrase var='fundraising.phone'}: </label>
            </div>
            <div class="table_right">
                <input type="text" class="ynfr" name="val[contact_phone]" value="{value type='input' id='contact_phone'}" id="contact_phone" size="60" />
            </div>
        </div>

        <div class="table">
            <div class="table_left">
                <label for="email_address">{phrase var='fundraising.email'}: </label>
            </div>
            <div class="table_right">
                <input type="text" class="ynfr email" name="val[contact_email_address]" value="{value type='input' id='contact_email_address'}" id="contact_email_address" size="60" />
            </div>
        </div>

        <div class="table">
            <div class="table_left">
                <label for="country_iso">{phrase var='fundraising.country'}:</label>
            </div>
            <div class="table_right">
                {select_location}
                {module name='core.country-child'}
            </div>
        </div>

        <div class="table">
            <div class="table_left">
                <label for="state">{phrase var='fundraising.state'}:</label>
            </div>
            <div class="table_right">
                <input type="text" name="val[contact_state]" value="{value type='input' id='contact_state'}" id="contact_state" size="20" maxlength="200" />
            </div>
        </div>

        <div class="table">
            <div class="table_left">
                <label for="city">{phrase var='fundraising.city'}:</label>
            </div>
            <div class="table_right">
                <input type="text" name="val[contact_city]" value="{value type='input' id='contact_city'}" id="contact_city" size="20" maxlength="200" />
            </div>
        </div>

        <div class="table">
            <div class="table_left">
                <label for="street">{phrase var='fundraising.street'}</label>
            </div>
            <div class="table_right">
                <input type="text" name="val[contact_street]" value="{value type='input' id='contact_street'}" id="contact_street" size="30" maxlength="200" />
            </div>
        </div>

        <div class="table">
            <div class="table_left">
                <label for="about_me">{phrase var='fundraising.about_me'}: </label>
            </div>
            <div class="table_right">
                {editor id='contact_about_me' value='$aForms.contact_about_me'}
            </div>
        </div>

        <div class="table_clear">
            <input type="submit" name="val[submit_contact_information]" value="{phrase var='fundraising.save'}" class="button" />
            {if $bIsEdit && $aForms.is_draft == 1}
            <input type="submit" name="val[publish_contact_information]" value="{phrase var='fundraising.publish'}" class="button"/>
            {/if}
        </div>

	</div>

	 </form>
