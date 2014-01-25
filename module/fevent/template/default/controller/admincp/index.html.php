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
<div id="js_menu_drop_down" style="display:none;">
	<div class="link_menu dropContent" style="display:block;">
		<ul>
			<li><a href="#" onclick="return $Core.event.action(this, 'edit');">{phrase var='fevent.edit'}</a></li>
			<li><a href="#" onclick="return $Core.event.action(this, 'delete');">{phrase var='fevent.delete'}</a></li>
		</ul>
	</div>
</div>
<div class="table_header">
	{phrase var='fevent.categories'}
</div>
<form method="post" action="{url link='admincp.fevent'}">
	<div class="table" style="padding:0; border:none;">
		<div class="sortable">
			{$sCategories}			
		</div>
	</div>
	<div class="table_clear">
		<input type="submit" value="{phrase var='fevent.update_order'}" class="button" />
	</div>
</form>