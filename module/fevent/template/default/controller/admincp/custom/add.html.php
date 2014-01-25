<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');
/**
 * 
 * 
 * @copyright       [YOUNET_COPYRIGHT]
 * @author          YouNet Company
 * @package         YouNet_Event
 */
?>
<div id="js_field_holder">
	{$sCustomCreateJs}
	<form method="post" action="{url link='admincp.fevent.custom.add'}" id="js_custom_field" onsubmit="{$sCustomGetJsForm}">
		<div class="table_header">
			{phrase var='fevent.field_details'}
		</div>
		<div class="table">
            <div class="table_left">
                {required}{phrase var='fevent.event_category'}:
            </div>
            <div class="table_right">
                {$sOptions}
            </div>
		</div>
			
		<div class="table">
			<div class="table_left">
				{phrase var='fevent.required'}:
			</div>
			<div class="table_right">
				<label><input type="radio" name="val[is_required]" value="1" class="v_middle checkbox" {value type='checkbox' id='is_required' default='1'}/>{phrase var='fevent.yes'}</label>
				<label><input type="radio" name="val[is_required]" value="0" class="v_middle checkbox" {value type='checkbox' id='is_required' default='0' selected=true}/>{phrase var='fevent.no'}</label>
			</div>
		</div>	
		
		<div class="table">
			<div class="table_left">
				{required}{phrase var='custom.type'}:
			</div>
			<div class="table_right">
				<select name="val[var_type]" class="var_type">
					<option value="">{phrase var='fevent.select'}:</option>
					<option value="textarea"{value type='select' id='var_type' default='textarea'}>{phrase var='custom.large_text_area'}</option>
					<option value="text"{value type='select' id='var_type' default='text'}>{phrase var='custom.small_text_area_255_characters_max'}</option>
					<option value="select"{value type='select' id='var_type' default='select'}>{phrase var='custom.selection'}</option>
					<option value="multiselect"{value type='select' id='var_type' default='multiselect'}>{phrase var='core.multiple_selection'}</option>
					<option value="radio"{value type='select' id='var_type' default='radio'}>{phrase var='core.radio'}</option>
					<option value="checkbox"{value type='select' id='var_type' default='checkbox'}>{phrase var='core.checkbox'}</option>
				</select>
			</div>
		</div>			
		
		<div class="table_header">
			{phrase var='fevent.field_name_amp_values'}
		</div>	
		
		<div class="table">
			<div class="table_left">
				{required}{phrase var='custom.name'}: 
			</div>
			<div class="table_right">
			{if isset($aForms.name) && is_array($aForms.name)}
				{foreach from=$aForms.name key=sPhrase item=aValues}
					{module name='language.admincp.form' type='text' id='name' mode='text' value=$aForms.name}
				{/foreach}
			{else}
				{module name='language.admincp.form' type='text' id='name' mode='text'}
			{/if}
			</div>
		</div>		
		
		{* This next block is used as a template *}
		<div class="table" id="js_multi_select"{if $bHideOptions} style="display:none;"{/if}>
			<div class="table_left">
				{phrase var='custom.values'}:
			</div>
			<div class="table_right">			
				<div id="js_sample_option">
					<div class="js_option_holder">
						<div class="p_4">
							<b>{phrase var='custom.option_html_count'}:</b> <span class="js_option_delete"></span>
							<div class="p_4">								
								{foreach from=$aLanguages item=aLang}
								<div>
								    <input type="text" name="val[option][#][{$aLang.language_code}][text]" value="" /> {$aLang.title}
								</div>
								{/foreach}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
        <div class="table" id="tbl_option_holder">
            <div class="table_left">{phrase var='fevent.values'}:</div>
            <div class="table_right"> 
            <div id="js_option_holder"></div>
            </div>
        </div>
        <div class="table" id="tbl_add_custom_option">
            <div class="table_left"></div>
            <div class="table_right">
            <a href="#" class="js_add_custom_option">{phrase var='custom.add_new_option'}</a>
            </div>
        </div>
		<div class="table_clear">
			<input type="submit" value="{phrase var='fevent.add'}" class="button" />			
		</div>
	</form>
</div>
