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
<div class="table_header">
	{phrase var='resume.categories'}
</div>
{if $iCount > 0}
<div id="js_menu_drop_down" style="display:none;">
	<div class="link_menu dropContent" style="display:block;">
		<ul>
			<li><a href="#" onclick="return $Core.resume.action(this, 'edit','<?php echo Phpfox::getLib('url')->makeUrl("admincp.resume");?>');">{phrase var='resume.edit'}</a></li>
			<li><a href="#" onclick="return $Core.resume.action(this, 'delete');">{phrase var='resume.delete'}</a></li>
		</ul>
	</div>
</div>
<form method="post" action="{url link='admincp.resume.categories'}">
	<div class="table">
		<div class="sortable">
			{$sCategories}			
		</div>
	</div>
	<div class="table_clear">
		<input type="submit" value="{phrase var='resume.update_order'}" class="button" />
	</div>
</form>
{else}
	<div class="extra_info">{phrase var='resume.no_categories_had_been_added'}</div>
{/if}
