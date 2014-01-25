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
{if count($aCategories)}
<div id="js_menu_drop_down" style="display:none;">
    <div class="link_menu dropContent" style="display:block;">
        <ul>
            <li><a href="#active" onclick="return $Core.custom.action(this, 'active');">{phrase var='custom.set_to_inactive'}</a></li>
            <li><a href="#" onclick="return $Core.custom.action(this, 'delete');">{phrase var='custom.delete'}</a></li>
        </ul>
    </div>
</div>
<div class="table_header">
    {phrase var='fevent.custom_fields'}
</div>
<form method="post" action="{url link='admincp.fevent.custom'}">
    <div class="sortable">
        {$sCustoms}
    </div>
    <div class="table_clear">
        <input type="submit" value="{phrase var='custom.update_order'}" class="button" />
    </div>
</form>
{else}
<div class="extra_info">
    {phrase var='custom.no_custom_fields_have_been_added'}
    <ul class="action">
        <li><a href="{url link='admincp.custom.add'}">{phrase var='custom.add_a_new_custom_field'}</a></li>
    </ul>
</div>
{/if}