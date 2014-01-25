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
<form method="post" action="{url link='admincp.fevent.add'}">
{if $bIsEdit}
	<div><input type="hidden" name="id" value="{$aForms.category_id}" /></div>
{/if}
	<div class="table_header">
		{phrase var='fevent.event_category_details'}
	</div>
	<div class="table">
		<div class="table_left">
			{phrase var='fevent.name'}:
		</div>
		<div class="table_right">
			<input type="text" name="val[name]" size="30" maxlength="100" value="{value type='input' id='name'}" />
		</div>
		<div class="clear"></div>
	</div>	
	<div class="table">
		<div class="table_left">
			{phrase var='fevent.parent_category'}:
		</div>
		<div class="table_right">
			{$selectBox}
		</div>
		<div class="clear"></div>
	</div>
	<div class="table_clear">
		<input type="submit" value="{phrase var='fevent.submit'}" class="button" />
	</div>
</form>