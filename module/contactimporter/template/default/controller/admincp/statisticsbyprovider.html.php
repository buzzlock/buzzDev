<?php
defined('PHPFOX') or exit('NO DICE!');
?>

{if count($providers)}
<div id="provider-list">
	<table id="title">
		<tr>
			<td width="70%">{phrase var='contactimporter.admincp_providers_title'}</td>
			<td>{phrase var='contactimporter.admincp_providers_totalinvitations'}</td>
		</tr>
	</table>
	<table id="table">
		{foreach from=$providers key=iKey item=provider}
		<tr class="tr" style="border-bottom: 1px #dfdfdf solid; height: 30px">
			<td width="70%">{$provider.title|convert|clean}</td>
			<td>{$provider.iTotalInvitations}</td>
		</tr>
		{/foreach}
	</table>
</div>
<div class="table_bottom"></div>
{/if}