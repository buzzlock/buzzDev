<?php 

defined('PHPFOX') or exit('NO DICE!'); 

?>
<div class="tip">{phrase var='mobiletemplate.warning_for_style'}</div>
<div id="js_category_holder">
    <br />
    <div class="table_header">
        {phrase var='mobiletemplate.manage_mobile_custom_styles'}
    </div>

    <form method="post" action="{url link='admincp.mobiletemplate.managestyles'}">
        {if count($aAllCustomStyles)}
        <table>
            <tr>
                <th style="width:10px;"><input type="checkbox" name="val[id]" value="" id="js_check_box_all" class="main_checkbox" /></th>
                <th style="width:20px;"></th>
                <th>{phrase var='mobiletemplate.name'}</th>
                <th>{phrase var='mobiletemplate.status'}</th>
            </tr>	
            {foreach from=$aAllCustomStyles key=iKey item=aStyle}
            <tr id="js_row{$aStyle.style_id}" class="checkRow{if is_int($iKey/2)} tr{else}{/if}">
            	<td><input type="checkbox" name="id[]" class="checkbox" value="{$aStyle.style_id}" id="js_id_row{$aStyle.style_id}" /></td>
                <td class="t_center">
                    <a href="#" class="js_drop_down_link" title="{phrase var='mobiletemplate.manage'}">{img theme='misc/bullet_arrow_down.png' alt=''}</a>
                    <div class="link_menu">
                        <ul>
                            <li><a href="{url link='admincp.mobiletemplate.addstyle' id={$aStyle.style_id}">{phrase var='mobiletemplate.edit'}</a></li>		
                            <li><a href="{url link='admincp.mobiletemplate.managestyles.delete' id={$aStyle.style_id}" onclick="return confirm('{phrase var='core.are_you_sure'}');">{phrase var='mobiletemplate.delete'}</a></li>
                        </ul>
                    </div>		
                </td>
                <td>{$aStyle.name|convert|clean}</td>
                <td class="t_center">
                    <div class="js_item_is_active" {if $aStyle.is_active == 0} style="display:none;"{/if}>
                         <a href="#?call=mobiletemplate.updateMobileCustomStyleStatus&amp;id={$aStyle.style_id}&amp;active=0" class="js_item_active_link js_remove_default" title="{phrase var='mobiletemplate.deactivate'}">{img theme='misc/bullet_green.png' alt=''}</a>
                    </div>
                    <div class="js_item_is_not_active" {if $aStyle.is_active == 1} style="display:none;"{/if}>
                         <a href="#?call=mobiletemplate.updateMobileCustomStyleStatus&amp;id={$aStyle.style_id}&amp;active=1" class="js_item_active_link js_remove_default" title="{phrase var='mobiletemplate.activate'}">{img theme='misc/bullet_red.png' alt=''}</a>
                    </div>		
                </td>
            </tr>
            {/foreach}            
        </table>
        <div class="table_bottom">
            <input type="submit" name="deleteSelected" value="{phrase var='mobiletemplate.delete_selected'}" class="sJsConfirm delete button sJsCheckBoxButton disabled" disabled="true" />
        </div>
        {else}
        <div class="extra_info">
            {phrase var='mobiletemplate.no_styles'}
        </div>
        {/if}
    </form>
</div>