<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');
/**
 * 
 * 
 * @copyright       [YOUNET_COPYRIGHT]
 * @author          YouNet Company
 * @package         YouNet_Event
 */
?>
<link rel="stylesheet" type="text/css" href="{$sCorePath}/module/fevent/static/calendar/jdpicker.css" />
<input type="hidden" name="calendar" id="calendar" value="{$sDate}" />
{literal}
<script type="text/javascript">
$Behavior.initCalendar = function(){
	if($('.jdpicker_w').length==0){
    	$('#calendar').jdPicker();
    	$(document.body).append('<div id="tooltip"></div>');
    } 
}
</script>
{/literal}
<script type="text/javascript">
	POOL = {l}
		"phrase_events":"{$sPhraseEvents}",
		"events":[],
		"search":{l}{r}
	{r};
	{foreach from=$aJsEvents item="aEvent"}
	{foreach from=$aEvent.calendar item="calendar"}
	POOL.events.push({l}
		"event_id":{$aEvent.event_id},
		"start_time":"{$calendar}"
	{r});
	{/foreach}
	{/foreach}
	{literal}
	for(var i=0; i<POOL.events.length; i++){
		if(typeof(POOL.search[POOL.events[i].start_time])=='undefined'){
			POOL.search[POOL.events[i].start_time] = new Array();
		}
		POOL.search[POOL.events[i].start_time].push(POOL.events[i].event_id);
	}
	{/literal}
</script>