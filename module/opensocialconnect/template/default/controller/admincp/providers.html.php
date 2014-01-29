<?php

/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package 		Phpfox
 * @version 		$Id: index.html.php 1544 2010-04-07 13:20:17Z Raymond_Benc $
 */

defined('PHPFOX') or exit('NO DICE!');

?>
{literal}
<style>
.t_center {
	vertical-align:middle;
}
</style>
{/literal}
{if !Phpfox::isModule('socialbridge')}
<div class="error_message">
	<strong>{phrase var='opensocialconnect.please_install_social_bridge_plugin_first'}</strong>
</div>
{else}
<div class="error_message">
<strong>
	{phrase var='opensocialconnect.all_social_api_keys_configuration_was_setup_in'} <a href="{url link='admincp.socialbridge.providers'}" target="_blank">{url link='admincp.socialbridge.providers'}</a>
</strong>
</div>
<div class="table_header">
	{phrase var='opensocialconnect.mange_social_providers'}
</div>
<table id="js_drag_drop" cellpadding="0" cellspacing="0">
	<tr>
		<th></th>
		<th class="t_center" style="width:40px;"></th>
		<th class="t_center" style="width:80px;">{phrase var='opensocialconnect.name'}</th>
		<th >{phrase var='opensocialconnect.title'}</th>
		<th class="t_center" style="width:60px;">{phrase var='rss.active'}</th>
	</tr>
	{foreach from=$aOpenProviders key=iKey item=aOpenProvider}
	<tr class="checkRow{if is_int($iKey/2)} tr{else}{/if}">
		<td class="drag_handle"><input type="hidden" name="val[ordering][{$aOpenProvider.service_id}]" value="{$aOpenProvider.ordering}" /></td>
		<td class="t_center">
			<img src="{$sCoreUrl}module/opensocialconnect/static/image/{$aOpenProvider.name}.png" alt="{$aOpenProvider.title}" width="32px"/>
		</td>	
        <td class="t_center">{$aOpenProvider.name}</td>
		<td class="t_center">                
			{$aOpenProvider.title}
		</td>
		<td class="t_center">
			<div class="js_item_is_active"{if !$aOpenProvider.is_active} style="display:none;"{/if}>
				<a href="#?call=opensocialconnect.updateActivity&amp;id={$aOpenProvider.service_id}&amp;active=0" class="js_item_active_link" title="{phrase var='rss.deactivate'}">{img theme='misc/bullet_green.png' alt=''}</a>
			</div>
			<div class="js_item_is_not_active"{if $aOpenProvider.is_active} style="display:none;"{/if}>
				<a href="#?call=opensocialconnect.updateActivity&amp;id={$aOpenProvider.service_id}&amp;active=1" class="js_item_active_link" title="{phrase var='rss.activate'}">{img theme='misc/bullet_red.png' alt=''}</a>
			</div>		
		</td>
	</tr>
	{/foreach}
</table>
{/if}
