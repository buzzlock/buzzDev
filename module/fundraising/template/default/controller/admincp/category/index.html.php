<?php 
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<div id="js_menu_drop_down" style="display:none;">
	<div class="link_menu dropContent" style="display:block;">
		<ul>
			<li><a href="#" onclick="return $Core.fundraising.action(this, 'edit');">{phrase var='fundraising.edit'}</a></li>
			<li><a href="#" onclick="return $Core.fundraising.action(this, 'delete');">{phrase var='fundraising.delete'}</a></li>
		</ul>
	</div>
</div>
<div class="table_header">
	{phrase var='fundraising.categories'}
</div>
<form method="post" action="{url link='admincp.fundraising.category'}">
	<div class="table">
		<div class="sortable">
			{$sCategories}			
		</div>
	</div>
	<div class="table_clear">
		<input type="submit" value="{phrase var='fundraising.update_order'}" class="button" />
	</div>
</form>