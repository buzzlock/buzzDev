<?php 

 
defined('PHPFOX') or exit('NO DICE!'); 

?>
{literal}
	<style type="text/css">
		.msg {
			background-color: #FDFCBE;
			padding: 5px;
		}
	</style>
{/literal}
<div id="js_menu_drop_down" style="display:none;">
	<div class="link_menu dropContent" style="display:block;">
		<ul>
			<li><a href="#" onclick="return $Core.advancedmarketplace.action(this, 'edit');">{phrase var='advancedmarketplace.edit'}</a></li>
			<li><a href="#" onclick="return $Core.advancedmarketplace.action(this, 'delete');">{phrase var='advancedmarketplace.delete'}</a></li>
			<li><a href="#" onclick="return $Core.advancedmarketplace.action(this, 'manage_customfield');">{phrase var='advancedmarketplace.manage_custom_fields'}</a></li>
		</ul>
	</div>
</div>
<div class="table_header">
	{phrase var='advancedmarketplace.categories'}
</div>
<form method="post" action="{url link='admincp.advancedmarketplace'}">
	<div class="table">
		<div class="sortable">
			{$sCategories}			
		</div>
	</div>
	<div class="table_clear">
		<input type="submit" value="{phrase var='advancedmarketplace.update_order'}" class="button" />
	</div>
</form>