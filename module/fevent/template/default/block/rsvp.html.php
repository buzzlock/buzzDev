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
<form method="post" action="{url link='current'}" onsubmit="$('#js_event_rsvp_button').find('input:first').attr('disabled', true); $('#js_event_rsvp_update').html($.ajaxProcess('{phrase var="fevent.updating"}')).show(); $(this).ajaxCall('fevent.addRsvp', '&id={$aEvent.event_id}'); return false;">
{if isset($aCallback) && $aCallback !== false}
	<div><input type="hidden" name="module" value="{$aCallback.module}" /></div>
	<div><input type="hidden" name="item" value="{$aCallback.item}" /></div>
{/if}
	<div class="p_2">
		<label onclick="$('#js_event_rsvp_button').show(); $('.js_event_rsvp').attr('checked', false); $(this).find('.js_event_rsvp').attr('checked', true);"><input type="radio" name="rsvp" value="1" class="checkbox v_middle js_event_rsvp" {if $aEvent.rsvp_id == 1}checked="checked" {/if}/> {phrase var='fevent.attending'}</label>
	</div>
	<div class="p_2">
		<label onclick="$('#js_event_rsvp_button').show(); $('.js_event_rsvp').attr('checked', false); $(this).find('.js_event_rsvp').attr('checked', true);"><input type="radio" name="rsvp" value="2" class="checkbox v_middle js_event_rsvp" {if $aEvent.rsvp_id == 2}checked="checked" {/if}/> {phrase var='fevent.maybe_attending'}</label>
	</div>
	<div class="p_2">
		<label onclick="$('#js_event_rsvp_button').show(); $('.js_event_rsvp').attr('checked', false); $(this).find('.js_event_rsvp').attr('checked', true);"><input type="radio" name="rsvp" value="3" class="checkbox v_middle js_event_rsvp" {if $aEvent.rsvp_id == 3}checked="checked" {/if}/> {phrase var='fevent.not_attending'}</label>
	</div>
	<div id="js_event_rsvp_button" class="p_2" style="margin-top:10px;{if $aEvent.rsvp_id} display:none;{/if}">
		<input type="submit" value="{if $aEvent.rsvp_id}{phrase var='fevent.update_your_rsvp'}{else}{phrase var='fevent.submit_your_rsvp'}{/if}" class="button" /> <span id="js_event_rsvp_update"></span>
	</div>
    {if ($aEvent.rsvp_id == 1 || $aEvent.rsvp_id == 2)}
    <div id="js_event_gcalendar_button" class="p_2" style="margin-top:10px;">
        {if $bIsGapi}
        <input type="button" value="{phrase var='fevent.add_to_google_calendar'}" class="button" onclick="show_glogin()" />
        {/if}
	</div>
    {/if}
</form>

<script type="text/javascript">
    function show_glogin()
    {l}
        tb_remove();
        tb_show("{phrase var='fevent.google_calendar'}",$.ajaxBox("fevent.glogin","height=300;width=350&id="+{$aEvent.event_id}));
    {r}
</script>
