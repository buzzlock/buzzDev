<?php
/**
 * [PHPFOX_HEADER]
 *
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author
 * @package  		Module_Contactimporter
 * @version
 */

defined('PHPFOX') or exit('NO DICE!');

?>
<form method="post" action="{url link='admincp.contactimporter.settings'}" id="admincp_contactimporter_form_message">
<input type="hidden" name="action" value="global_settings"/>
 <div class="message" style="padding:10px;">
    {phrase var='contactimporter.all_social_api_keys_configuration_was_setup_in'}<br/><br/>
    <a href="{url link='admincp.socialbridge.providers'}" target="_blank">{url link='admincp.socialbridge.providers'}</a>
</div>
<div class="table_header">
       {phrase var='contactimporter.global_settings'}
        </div>
    <div class="table">
        <div class="table_left">
            {phrase var='contactimporter.maximum_number_of_providers_per_home_page'}:
        </div>
        <div class="table_right">
            <input type="text" name="val[number_provider_display]" value="{$number_provider_display}" />
        </div>
        <br/>
         <div class="table_right">
             <span><em>{phrase var='contactimporter.if_you_put_1_that_mean_all_of_providers_will_display_on_home_page'}</em></span>
        </div>
        <div class="clear"></div>
    </div>
	<div class="table">
        <div class="table_left">
            {phrase var='contactimporter.icon_size_on_homepage'}:
        </div>
        <div class="table_right">
			<select name="val[icon_size]">
	            <option value="30" {if $icon_size==30}selected="selected"{/if} />30</option>
				<option value="35" {if $icon_size==35}selected="selected"{/if} />35</option>
				<option value="40" {if $icon_size==40}selected="selected"{/if} />40</option>
				<option value="45" {if $icon_size==45}selected="selected"{/if} />45</option>
				<option value="50" {if $icon_size==50}selected="selected"{/if} />50</option>
			</select>
        </div>
        <div class="clear"></div>
    </div>
    <div class="table" style="display:none;">
        <div class="table_left">
            {required}{phrase var='contactimporter.unsubcribe'}
        </div>
        <div class="table_right">
            <div class="item_is_active_holder">
                <span class="js_item_active item_is_active"><input type="radio" name="val[is_unsubcribed]" value="1" {if $is_unsubcribed eq 1 } {value type='radio' id='is_active' default='1' selected='true'}{/if}/> {phrase var='admincp.yes'}</span>
                <span class="js_item_active item_is_not_active"><input type="radio" name="val[is_unsubcribed]" value="0" {if $is_unsubcribed eq 0 } {value type='radio' id='is_active' default='0' selected='true'}{/if}/> {phrase var='admincp.no'}</span>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="table_clear">
	 <input type="submit" value="{phrase var='contactimporter.save_change'}" class="button" name="save_global_settings"/>
    </div>
</form><br />
<form method="post" action="{url link='admincp.contactimporter.settings'}" id="admincp_contactimporter_form_message">
<input type="hidden" name="action" value="add"/>
	<div class="table_header">
		{phrase var='contactimporter.default_invite_message'}
	</div>
	<div class="table">
		<div class="table_left">
			{phrase var='contactimporter.message_label'}
		</div>
		<div class="table_right">
			<textarea id="default_message" name="default_message" rows="5" cols="40">{$lang_message.text}</textarea>
		</div>
		<div class="clear"></div>
	</div>
	<div class="table_clear">
		<input type="submit" value="{phrase var='contactimporter.save_change'}" class="button" name="save_message_invite"/>
	</div>
</form>
<br />

<div class="table_header">
	{phrase var='contactimporter.member_lever_settings'}
</div>
<p>{phrase var='contactimporter.member_lever_settings_description'}</p>
<form method="post" id="admincp_contactimporter_form_maxInvitation" action="{url link='admincp.contactimporter.settings'}">
	<table id="js_drag_drop">
	<tr>
		<th style="width:200px;text-align:right;padding-right: 10px;">{phrase var='contactimporter.member_level'}</th>
		<th style="text-align:left;">{phrase var='contactimporter.number_of_contacts_view_on_each_page'}</th>
	</tr>
	{foreach from=$maximum_invitations item=max}
	<tr>
		<td class="drag_handle" style="text-align:right;border-right: none;">
			{$max.title}:
		</td>
		<td><input type="text" name="val[max_value_{$max.user_group_id}]" value="{if $max.number_invitation != null} {$max.number_invitation}{else}60{/if}" size="40" /></td>
	</tr>
	{/foreach}
	</table>
	<div class="table_bottom">
		<input type="submit" value="{phrase var='contactimporter.save_change'}" class="button" name ="save_maximum_invitation"/>

	</div>
</form><br />