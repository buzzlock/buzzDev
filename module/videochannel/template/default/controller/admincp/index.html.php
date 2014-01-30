<?php 
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<div id="js_menu_drop_down" style="display:none;">
	<div class="link_menu dropContent" style="display:block;">
		<ul>
			<li><a href="#" onclick="return $Core.videochannel.action(this, 'edit');">{phrase var='videochannel.edit'}</a></li>
			<li><a href="#" onclick="return $Core.videochannel.action(this, 'delete');">{phrase var='videochannel.delete'}</a></li>
		</ul>
	</div>
</div>
<div class="table_header">
	{phrase var='videochannel.categories'}
</div>
<form method="post" action="{url link='admincp.videochannel'}">
	<div class="table">
		<div class="sortable">
			{$sCategories}			
		</div>
	</div>
	<div class="table_clear">
		<input type="submit" value="{phrase var='videochannel.update_order'}" class="button" />
	</div>
</form>