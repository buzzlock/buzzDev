<?php 

defined('PHPFOX') or exit('NO DICE!'); 

?>

<div id="js_category_holder">
    <br />
    <div class="table_header">
        {phrase var='mobiletemplate.mobile_theme'}
    </div>

    <form method="post" action="{url link='admincp.mobiletemplate'}">
        {if count($aAllStyles)}
        <table>
            <tr>
                <th>{phrase var='mobiletemplate.theme_name'}</th>
                <th>{phrase var='mobiletemplate.style_name'}</th>
                <th>{phrase var='mobiletemplate.status'}</th>
            </tr>	
            {foreach from=$aAllStyles key=iKey item=aStyle}
            <tr id="js_row{$aStyle.style_id}" class="checkRow{if is_int($iKey/2)} tr{else}{/if}">
            	{foreach from=$aAllThemes item='aTheme'}
            		{if $aStyle.theme_id == $aTheme.theme_id}
            			<td>{$aTheme.name}</td>
            		{/if}
            	{/foreach}                
                <td>{$aStyle.name}</td>
                <td class="t_center">
                    <div class="js_item_is_active" {if !isset($aActiveStyle) || $aActiveStyle == null || $aStyle.style_id != $aActiveStyle.style_id} style="display:none;"{/if}>
                         <a href="#?call=mobiletemplate.updateMTActiveThemeStyleStatus&amp;id={$aStyle.style_id}&amp;active=0" class="js_item_active_link js_remove_default" title="{phrase var='mobiletemplate.deactivate'}">{img theme='misc/bullet_green.png' alt=''}</a>
                    </div>
                    <div class="js_item_is_not_active" {if isset($aActiveStyle) && $aActiveStyle != null && $aStyle.style_id == $aActiveStyle.style_id} style="display:none;"{/if}>
                         <a href="#?call=mobiletemplate.updateMTActiveThemeStyleStatus&amp;id={$aStyle.style_id}&amp;active=1" class="js_item_active_link js_remove_default" title="{phrase var='mobiletemplate.activate'}">{img theme='misc/bullet_red.png' alt=''}</a>
                    </div>		
                </td>
            </tr>
            {/foreach}
        </table>
        {else}
        <div class="extra_info">
            {phrase var='mobiletemplate.no_styles'}
        </div>
        {/if}
    </form>
</div>