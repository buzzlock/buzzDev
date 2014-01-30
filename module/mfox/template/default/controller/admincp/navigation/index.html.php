<?php
defined('PHPFOX') or exit('NO DICE!');
?>
<form method="post" action="{url link='admincp.mfox.navigation.edit'}">
    <div class="table_bottom">
        <input type="submit" name="delete" value="{phrase var='mfox.add_navigation'}" class="button"/>
    </div>
</form>
<br />
<div class="table_header">
	{phrase var='mfox.manage_navigations'}
</div>
<form method="post" id="admincp_navigation_form_edit" action="{url link='admincp.mfox.navigation'}">
	<table id="js_drag_drop">
	<tr>
		<th style="width:10px;">{phrase var='mfox.order'}</th>
		
		<th style="width:10px;"><input type="checkbox" name="val[id]" value="" id="js_check_box_all" class="main_checkbox" /></th>
		<th style="width:20px;"></th>
		<th>{phrase var='mfox.label'}</th>
        <th class="t_center" style="width:60px;">{phrase var='mfox.enable'}</th>
	</tr>
	{foreach from=$aNavigations key=iKey item=aNavigation}
        <tr class="checkRow{if is_int($iKey/2)} tr{else}{/if}">
            <td class="drag_handle"><input type="hidden" name="val[ordering][{$aNavigation.id}]" value="{$aNavigation.sort_order}" /></td>
            <td><input type="checkbox" name="id[]" class="checkbox" value="{$aNavigation.id}" id="js_id_row" /></td>
            <td class="t_center">
                <a href="#" class="js_drop_down_link" title="{phrase var='mfox.manage'}">{img theme='misc/bullet_arrow_down.png' alt=''}</a>
                <div class="link_menu">
                    <ul>
                        <li><a href="{url link='admincp.mfox.navigation.edit' id={$aNavigation.id}">{phrase var='mfox.edit'}</a></li>		
                    </ul>
                </div>		
            </td>
            <td><input type="text" name="title_{$aNavigation.id}" value="{$aNavigation.label}" size="40" /></td>
            
            <td class="t_center">
                <div class="js_item_is_active"{if !$aNavigation.is_enabled} style="display:none;"{/if}>
                    <a href="#?call=mfox.updateNavigationStatus&amp;id={$aNavigation.id}&amp;active=0" class="js_item_active_link" title="{phrase var='mfox.deactivate'}">{img theme='misc/bullet_green.png' alt=''}</a>
                </div>
                <div class="js_item_is_not_active"{if $aNavigation.is_enabled} style="display:none;"{/if}>
                    <a href="#?call=mfox.updateNavigationStatus&amp;id={$aNavigation.id}&amp;active=1" class="js_item_active_link" title="{phrase var='mfox.activate'}">{img theme='misc/bullet_red.png' alt=''}</a>
                </div>		
            </td>
        </tr>
	{/foreach}
	</table>
	<div class="table_bottom">
		<input type="submit" name="delete" value="{phrase var='contact.delete_selected'}" class="sJsConfirm delete button sJsCheckBoxButton disabled" disabled="true" />
		<input type="submit" name="update" value="{phrase var='contact.edit'}" class="button" />
	</div>
</form>