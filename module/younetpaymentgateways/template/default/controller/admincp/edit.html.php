<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package 		Phpfox
 * @version 		$Id: add.html.php 979 2009-09-14 14:05:38Z Raymond_Benc $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<form method="post" action="{url link='admincp.younetpaymentgateways.edit'}">
	<div><input type="hidden" name="id" value="{$aForms.gateway_id}" /></div>	
	<div class="table_header">
		{phrase var='younetpaymentgateways.gateway_details'}
	</div>	
	<div class="table">
		<div class="table_left">
			{phrase var='younetpaymentgateways.title'}:
		</div>
		<div class="table_right">
			<input type="text" name="val[title]" id="title" value="{value type='input' id='title'}" size="40" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="table">
		<div class="table_left">
			{phrase var='younetpaymentgateways.description'}:
		</div>
		<div class="table_right">
			<textarea cols="50" rows="6" name="val[description]" id="description">{value type='textarea' id='description'}</textarea>
		</div>
		<div class="clear"></div>
	</div>	
	<div class="table">
		<div class="table_left">
			{phrase var='admincp.active'}:
		</div>
		<div class="table_right">
			<div class="item_is_active_holder">
				<span class="js_item_active item_is_active"><input type="radio" name="val[is_active]" value="1" {value type='radio' id='is_active' default='1' selected='true'}/> {phrase var='admincp.yes'}</span>
				<span class="js_item_active item_is_not_active"><input type="radio" name="val[is_active]" value="0" {value type='radio' id='is_active' default='0'}/> {phrase var='admincp.no'}</span>
			</div>
		</div>
		<div class="clear"></div>
	</div>		
	<div class="table">
		<div class="table_left">
			{phrase var='younetpaymentgateways.test_mode'}:
		</div>
		<div class="table_right">
			<div class="item_is_active_holder">
				<span class="js_item_active item_is_active"><input type="radio" name="val[is_test]" value="1" {value type='radio' id='is_test' default='1' selected='true'}/> {phrase var='admincp.yes'}</span>
				<span class="js_item_active item_is_not_active"><input type="radio" name="val[is_test]" value="0" {value type='radio' id='is_test' default='0'}/> {phrase var='admincp.no'}</span>
			</div>
		</div>
		<div class="clear"></div>
	</div>		
	
	<div class="table_clear">
		<input type="submit" value="{phrase var='younetpaymentgateways.update'}" class="button" />
	</div>
</form>