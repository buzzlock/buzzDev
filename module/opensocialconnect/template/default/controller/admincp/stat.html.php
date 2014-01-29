<?php

/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		YOUNETCO
 * @author  		AnNT
 * @package 		YouNet SocialConnect
 * @version 		3.03
 */

defined('PHPFOX') or exit('NO DICE!');

?>

{if empty($aStats)}
<div class="message">{phrase var='opensocialconnect.no_providers_found'}</div>
{else}
<table id="js_opensocialconnect_stat" cellpadding="0" cellspacing="0">
    <tr>
    	<th>{phrase var='opensocialconnect.provider'}</th>
        <th align="center">{phrase var='opensocialconnect.total_signup'}</th>
        <th align="center">{phrase var='opensocialconnect.total_login'}</th>
    </tr>
    {foreach from=$aStats name=stats item=aStat}
    <tr{if is_int($phpfox.iteration.stats/2)} class="tr"{/if}>
    	<td>{$aStat.title}</td>
        <td align="center">{$aStat.total_signup}</td>
        <td align="center">{$aStat.total_login}</td>
    </tr>
    {/foreach}
</table>
{/if}
