<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		YOUNETCO
 * @author  		AnNT
 * @package 		YouNet SocialStream
 * @version 		3.03
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
{literal}
<script type="text/javascript">
$Core.remakePostUrl = function()
{
    var from = js_socialstream_statdate.js_from__datepicker.value.replace(/\//g,'-');
    var to = js_socialstream_statdate.js_to__datepicker.value.replace(/\//g,'-');
    var url = js_socialstream_statdate.action + 'from_' + from + '/' + 'to_' + to + '/';
    
    window.location.href = url;
}
</script>
{/literal}

{if empty($aServices)}
<div class="message">{phrase var='socialstream.no_providers_found'}</div>
{else}
<form name="js_socialstream_statdate" action="{url link='admincp.socialstream.statdate'}" onsubmit="$Core.remakePostUrl(); return false;">
<div class="st table table_clear">
    <div class="st_select">
		<div class="st_item">
			 <label>{phrase var='socialstream.from'}: </label>
			 {select_date prefix='from_' id='_from' start_year='current_year' end_year='+1' field_separator=' / ' field_order='MDY' default_all=true} 
		</div>
		<div class="st_item">
			 <label>{phrase var='socialstream.to'}: </label>
			 {select_date prefix='to_' id='_to' start_year='current_year' end_year='+1' field_separator=' / ' field_order='MDY' default_all=true}
		</div>
		<div class="st_item">
			<input type="submit" class="button" value="{phrase var='socialstream.go'}" />
		</div>
        <div class="clear"></div>
    </div>
</div>
</form>
<table id="js_socialstream_stat_by_date" cellpadding="0" cellspacing="0">
<tr>
	<th>{phrase var='socialstream.date'}</th>
    {foreach from=$aServices item=aService}
    <th align="center">{$aService.title} {phrase var='socialstream.feeds'}</th>
    {/foreach}
</tr>
{if empty($aStats)}
<tr>
	<td colspan="100%">
		{phrase var='socialstream.there_are_no_feeds_to_statistics'}
	</td>
</tr>
{else}
{foreach from=$aStats name=stats item=aStat}
<tr{if is_int($phpfox.iteration.stats/2)} class="tr"{/if}>
	<td>{$aStat.feeds_date}</td>
    {foreach from=$aServices item=aService}
    {assign var=service value=$aService.name}
    <td align="center">{$aStat[$service]}</td>
    {/foreach}
</tr>
{/foreach}
{/if}
</table>
{pager}
{/if}
