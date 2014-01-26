<?php 
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<div id="js_menu_drop_down" style="display:none;">
	<div class="link_menu dropContent" style="display:block;">
		<ul>
			<li><a href="#" onclick="return $Core.contest.action(this, 'edit');">{phrase var='contest.edit'}</a></li>
			<li><a href="#" onclick="return $Core.contest.action(this, 'delete');">{phrase var='contest.delete'}</a></li>
		</ul>
	</div>
</div>
<div class="table_header">
	{phrase var='contest.categories'}
</div>
<form method="post" action="{url link='admincp.contest.category'}">
	<div class="table">
		<div class="sortable">
			{$sCategories}			
		</div>
	</div>
	<div class="table_clear">
		<input type="submit" value="{phrase var='contest.update_order'}" class="button" />
	</div>
</form>