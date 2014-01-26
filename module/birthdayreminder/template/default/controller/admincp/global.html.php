<?php
/**
 * [PHPFOX_HEADER]
 *
 * @copyright        [PHPFOX_COPYRIGHT]
 * @author          
 * @package          Module_MusicSharing
 * @version         
 */

defined('PHPFOX') or exit('NO DICE!');

?>

<form method="post" action="{url link='admincp.birthdayreminder.global'}" id="admincp_birthdayreminder_form_message">
	<div class="table_header">
		{phrase var='birthdayreminder.admin_settings'}
	</div>
	<br/>
    <div>
        {phrase var='birthdayreminder.create_event_when_sending_reminder'}
		<br/>
		<br/>
        <div class="item_is_active_holder"> 
            <span class="js_item_active item_is_active"><input type="radio" name="val[create_event]" value="1" {if $create_event eq 1 } {value type='radio' id='is_active' default='1' selected='true'}{/if}/> {phrase var='admincp.yes'}</span>
            <span class="js_item_active item_is_not_active"><input type="radio" name="val[create_event]" value="0" {if $create_event eq 0 } {value type='radio' id='is_active' default='0' selected='true'}{/if}/> {phrase var='admincp.no'}</span>
        </div>
    </div>
	<br/>
	<br/>	
	<div>
            {phrase var='birthdayreminder.the_number_of_day_before_birthday_to_send_mail'}:
	</div>
	<br/>
    <div>
            <input type="text" name="val[send_mail_date]" id="send_mail_date" value="{$send_mail_date}"/> {phrase var='birthdayreminder.admin_days'}
    </div>
	<br/>
	<br/>
	<div>
            {phrase var='birthdayreminder.the_number_of_day_before_birthday_to_create_event'}:
	</div>
	<br/>
    <div>
            <input type="text" name="val[create_event_date]" id="create_event_date" value="{$create_event_date}"/> {phrase var='birthdayreminder.admin_days'}
    </div>
	
	<br/>
	<div class="table_clear">
        <input type="submit" value="Save" class="button" id="save" name="save" />
	</div>
	<div class="table_clear"></div>
</form>
