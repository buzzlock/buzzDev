<?php 
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 *
 * @copyright      YouNet Company
 * @author         VuDP, TienNPL
 * @package        Module_Resume
 * @version        3.01
 * 
 */
?>
{$sCreateJs}
<form method="post" action="{url link="admincp.resume.addcategory"}" id="js_form" onsubmit="{$sGetJsForm}">
{if $bIsEdit}
	<div><input type="hidden" name="id" value="{$aForms.category_id}" /></div>
{/if}
	<div class="table_header">
		{phrase var='resume.category_details'}
	</div>
	<div class="table">
		<div class="table_left">
			{required}{phrase var='resume.category_name'}:
		</div>
		<div class="table_right">
			<input type="text" name="val[name]" value="{value type='input' id='name'}" id="name" size="30" />
			{help var='resume.please_insert_category_name'}
		</div>
		
		<div class="clear"></div>
	</div>
	<!-- <div class="table">
		<div class="table_left">
			{phrase var='resume.parent_category'}:
		</div>
		<div class="table_right">
			<select name="val[parent_id]" style="width:300px;">
				<option value="">{phrase var='resume.select'}</option>
				{$sOptions}
			</select>
		</div>
		<div class="clear"></div>
	</div> -->
	<div class="table_clear">
		<input type="submit" value="{phrase var='admincp.submit'}" class="button" />
	</div>
</form>