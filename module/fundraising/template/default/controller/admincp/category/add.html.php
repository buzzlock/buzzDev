<?php 
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<form method="post" action="{url link='admincp.fundraising.category.add'}">
{if $bIsEdit}
	<div><input type="hidden" name="id" value="{$aForms.category_id}" /></div>
{/if}
	<div class="table_header">
		{phrase var='fundraising.campaign_category_detail'}
	</div>
	<div class="table">
		<div class="table_left">
			{phrase var='fundraising.title'}:
		</div>
		<div class="table_right">
			<input type="text" name="val[title]" size="30" maxlength="100" value="{value type='input' id='title'}" />
		</div>
		<div class="clear"></div>
	</div>	
	<div class="table">
		<div class="table_left">
			{phrase var='fundraising.parent_category'}:
		</div>
		<div class="table_right">
			<select name="val[parent_id]" style="width:300px;">
				<option value="">{phrase var='fundraising.select_form_select'}:</option>
				{$sOptions}
			</select>
		</div>
		<div class="clear"></div>
	</div>
	<div class="table_clear">
		<input type="submit" value="{phrase var='fundraising.submit'}" class="button" />
	</div>
</form>