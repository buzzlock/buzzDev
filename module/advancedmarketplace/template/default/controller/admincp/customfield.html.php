<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package 		Phpfox
 * @version 		$Id: index.html.php 2197 2010-11-22 15:26:08Z Raymond_Benc $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<div id="js_menu_drop_down" style="display:none;">
	<div class="link_menu dropContent" style="display:block;">
		<ul>
			<li><a href="#" onclick="return $Core.advancedmarketplace.action(this, 'edit');">{phrase var='advancedmarketplace.edit'}</a></li>
			<li><a href="#" onclick="return $Core.advancedmarketplace.action(this, 'delete');">{phrase var='advancedmarketplace.delete'}</a></li>
			<li><a href="#" onclick="return $Core.advancedmarketplace.action(this, 'manage_customfield');">{phrase var='advancedmarketplace.admin_menu_manage_custom_fields'}</a></li>
		</ul>
	</div>
</div>
<div class="table_header">
	{phrase var='advancedmarketplace.admin_menu_manage_custom_fields'}
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