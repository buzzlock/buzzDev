<?php
defined('PHPFOX') or exit('NO DICE!');
?>
<form method="post" action="{url link='admincp.mfox.navigation.edit'}">
{if $bIsEdit}
	<div><input type="hidden" name="id" value="{$aForms.id}" /></div>
{/if}
	<div class="table_header">
		{phrase var='mfox.navigation_info'}
	</div>
	<div class="table">
		<div class="table_left">
		{required}{phrase var='mfox.name'}:
		</div>
		<div class="table_right">
			<input type="text" name="val[name]" value="{value id='name' type='input'}" size="40" />
		</div>
		<div class="clear"></div>
	</div>
    <div class="table">
		<div class="table_left">
			{required}{phrase var='mfox.is_enabled'}:
		</div>
		<div class="table_right">	
			<div class="item_is_active_holder">		
				<span class="js_item_active item_is_active"><input type="radio" name="val[is_enabled]" value="1" {value type='radio' id='is_enabled' default='1' selected='true'}/> {phrase var='mfox.yes'}</span>
				<span class="js_item_active item_is_not_active"><input type="radio" name="val[is_enabled]" value="0" {value type='radio' id='is_enabled' default='0'}/> {phrase var='mfox.no'}</span>
			</div>
		</div>
		<div class="clear"></div>		
	</div>
    <div class="table">
		<div class="table_left">
		{required}{phrase var='mfox.label'}:
		</div>
		<div class="table_right">
			<input type="text" name="val[label]" value="{value id='label' type='input'}" size="40" />
		</div>
		<div class="clear"></div>
	</div>
    <div class="table">
		<div class="table_left">
		{required}{phrase var='mfox.layout'}:
		</div>
		<div class="table_right">
			<input type="text" name="val[layout]" value="{value id='layout' type='input'}" size="40" />
		</div>
		<div class="clear"></div>
	</div>
    <div class="table">
		<div class="table_left">
		{required}{phrase var='mfox.icon'}:
		</div>
		<div class="table_right">
			<input type="text" name="val[icon]" value="{value id='icon' type='input'}" size="40" />
		</div>
		<div class="clear"></div>
	</div>
    <div class="table">
		<div class="table_left">
		{phrase var='mfox.url'}:
		</div>
		<div class="table_right">
			<input type="text" name="val[url]" value="{value id='url' type='input'}" size="40" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="table_clear">
		<input type="submit" value="{phrase var='mfox.submit'}" class="button" />
	</div>
</form>