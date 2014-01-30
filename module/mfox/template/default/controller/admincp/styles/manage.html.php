<?php
defined('PHPFOX') or exit('NO DICE!');
?>
<form method="post" action="{url link='admincp.mfox.styles'}">
    <div class="table_bottom">
        <input type="submit" name="delete" value="{phrase var='mfox.add_custom_styles'}" class="button"/>
    </div>
</form>
<div id="js_category_holder">
    <br />
    <div class="table_header">
        {phrase var='mfox.manage_styles'}
    </div>

    <form method="post" action="{url link='admincp.mfox.styles.manage'}">
        {if count($aStyles)}
        <table>
            <tr>
                <th style="width:10px;"><input type="checkbox" name="val[id]" value="" id="js_check_box_all" class="main_checkbox" /></th>
                <th style="width:20px;"></th>
                <th>{phrase var='mfox.style_id'}</th>
                <th>{phrase var='mfox.style_name'}</th>
                <th>{phrase var='mfox.style_active'}</th>
            </tr>	
            {foreach from=$aStyles key=iKey item=aStyle}
            <tr id="js_row{$aStyle.style_id}" class="checkRow{if is_int($iKey/2)} tr{else}{/if}">
                <td><input type="checkbox" name="id[]" class="checkbox" value="{$aStyle.style_id}" id="js_id_row{$aStyle.style_id}" /></td>
                <td class="t_center">
                    <a href="#" class="js_drop_down_link" title="{phrase var='mfox.manage'}">{img theme='misc/bullet_arrow_down.png' alt=''}</a>
                    <div class="link_menu">
                        <ul>
                            <li><a href="{url link='admincp.mfox.styles' id={$aStyle.style_id}">{phrase var='mfox.edit'}</a></li>		
                        </ul>
                    </div>		
                </td>
                <td>{$aStyle.style_id}</td>
                
                <td>{$aStyle.name|convert|clean}</td>
                <td class="t_center">
                    <div class="js_item_is_active"{if !$aStyle.is_publish} style="display:none;"{/if}>
                         <a href="#?call=mfox.updateStyleStatus&amp;id={$aStyle.style_id}&amp;active=0" class="js_item_active_link js_remove_default" title="{phrase var='mfox.deactivate'}">{img theme='misc/bullet_green.png' alt=''}</a>
                    </div>
                    <div class="js_item_is_not_active"{if $aStyle.is_publish} style="display:none;"{/if}>
                         <a href="#?call=mfox.updateStyleStatus&amp;id={$aStyle.style_id}&amp;active=1" class="js_item_active_link js_remove_default" title="{phrase var='mfox.activate'}">{img theme='misc/bullet_red.png' alt=''}</a>
                    </div>		
                </td>
            </tr>
            {/foreach}
        </table>
        <div class="table_bottom">
            <input type="submit" name="delete" value="{phrase var='mfox.delete_selected'}" class="sJsConfirm delete button sJsCheckBoxButton disabled" disabled="true" />
        </div>
        {else}
        <div class="extra_info">
            {phrase var='mfox.no_styles'}
        </div>
        {/if}
    </form>
</div>
{pager}