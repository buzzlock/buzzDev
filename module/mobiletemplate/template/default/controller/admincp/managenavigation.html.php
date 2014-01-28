<?php 

defined('PHPFOX') or exit('NO DICE!'); 

?>
<div class="tip">{phrase var='mobiletemplate.warning_for_style'}</div>
<div class="table_header">
	{phrase var='mobiletemplate.manage_menu_navigation'}
</div>
<form method="post" action="{url link='admincp.mobiletemplate.managenavigation'}">
	<table id="js_drag_drop">
		<tr>
			<th style="width:10px;">{phrase var='mobiletemplate.order'}</th>			
			<th>{phrase var='mobiletemplate.label'}</th>
	        <th class="t_center" style="width:60px;">{phrase var='mobiletemplate.enable'}</th>
		</tr>
		{foreach from=$refreshMenuNavigation key=iKey item=aNavigation}
		<tr class="checkRow{if is_int($iKey/2)} tr{else}{/if}">
			<td class="drag_handle"><input type="hidden" name="val[ordering][{$aNavigation.menu_id}]" value="{$aNavigation.ordering}" /></td>
			<td>{$aNavigation.display_name|convert|clean}</td>
            <td class="t_center">
                <div class="js_item_is_active"{if !$aNavigation.is_active} style="display:none;"{/if}>
                    <a href="#?call=mobiletemplate.updateMenuNavigationStatus&amp;id={$aNavigation.menu_id}&amp;active=0" class="js_item_active_link" title="{phrase var='mobiletemplate.deactivate'}">{img theme='misc/bullet_green.png' alt=''}</a>
                </div>
                <div class="js_item_is_not_active"{if $aNavigation.is_active} style="display:none;"{/if}>
                    <a href="#?call=mobiletemplate.updateMenuNavigationStatus&amp;id={$aNavigation.menu_id}&amp;active=1" class="js_item_active_link" title="{phrase var='mobiletemplate.activate'}">{img theme='misc/bullet_red.png' alt=''}</a>
                </div>		
            </td>
		</tr>
		{/foreach}
	</table>
</form>
