<?php
/**
 * [PHPFOX_HEADER]
 *
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Miguel Espinoza
 * @package  		Module_Contact
 * @version 		$Id: index.html.php 1424 2010-01-25 13:34:36Z Raymond_Benc $
 */

defined('PHPFOX') or exit('NO DICE!');

?>
<form method="post" action="{url link='admincp.contactimporter.statistics'}">
	<div class="table_header">
        {phrase var='admincp.search_filter'}
    </div>
	<div class="table">
		<div class="table_left">
			{phrase var='contactimporter.from'}
		</div>
		<div class="js_event_select table_right">
			{select_date prefix='start_' id='_start' start_year='current_year' end_year='+1' field_separator=' / ' field_order='YMD' default_all=true  time_separator='event.time_separator'}				
		</div>
	</div>
	<div class="table">
		<div  class="table_left">
			{phrase var='contactimporter.to'}
		</div>
		<div class="js_event_select table_right">
			{select_date prefix='end_' id='_end' start_year='current_year' end_year='+1' field_separator=' / ' field_order='YMD' default_all=true  time_separator='event.time_separator'}
		</div>
	</div>
	<div class="table_clear">
        <input type="submit" name="submit" value="{phrase var='core.submit'}" class="button" />
    </div>
</form>
{pager}
<br/>
{if count($items) > 0}
<form action="{url link='admincp.contactimporter.invitations'}" method="post" onsubmit="return getsubmit();" >
    <table>
        <tr>
            <th>{phrase var='contactimporter.date'}</th>
            <th>{phrase var='contactimporter.admincp_providers_totalinvitations'}</th>
        </tr>
        {foreach from=$items key=iKey item=date}    
        <tr class="checkRow{if is_int($iKey/2)} tr{else}{/if}">
            <td>{$date.date|clean}</td>
            <td>{$date.total|clean}</td>
        </tr>    
        {/foreach}
    </table>
</form>
{pager}
{else}
<br/>
<div class="extra_info">
    <strong>{phrase var='contactimporter.there_are_no_invitations'}</strong>
</div>
{/if}
<script type="text/javascript">
{literal}
	$Behavior.resetDatepicker = function(){
		$('.js_event_select .js_date_picker').datepicker('option', 'maxDate', '+1y');
	};
{/literal}
</script>