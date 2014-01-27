<?php
/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');
?>

<table width="100%"  style="border-collapse:separate; border-spacing:5px">
	<tr>
		<td width="40%"><a href="{url link='fundraising' view='ongoing'}"> <b>{phrase var='fundraising.on_going'} :</b></a></td>
		<td>{$aStats.ongoing} {phrase var='fundraising.campaign_s'} </td>
	</tr>
	<tr>
		<td><a href="{url link='fundraising' view='reached'}"> <b>{phrase var='fundraising.reached'} :</b></a></b></a></td>
		<td>{$aStats.reached} {phrase var='fundraising.campaign_s'} </td>
	</tr>
	<tr>
		<td><a href="{url link='fundraising' view='expired'}"><b>{phrase var='fundraising.expired'} :</b></a></td>
		<td>{$aStats.expired} {phrase var='fundraising.campaign_s'} </td>
	</tr>
	<tr>
		<td><a href="{url link='fundraising' view='closed'}"><b>{phrase var='fundraising.closed'} :</b></a></td>
		<td>{$aStats.closed} {phrase var='fundraising.campaign_s'} </td>
	</tr>
</table>