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
<div id="js_report_body">

    <div class="p_4">
        {phrase var='fevent.do_you_want_to_add_this_event_into_your_google_calendar_to_get_reminder'}
    </div>

    <div class="p_4" align="center">
        <input type="submit" value="{phrase var='fevent.yes'}" class="button" onclick="tb_remove(); window.location.href='{$core_path}module/fevent/static/gcalendar.php?event_id={$event_id}'" />&nbsp;
        <input type="button" value="{phrase var='fevent.no_thanks'}" class="button" onclick="tb_remove();" />
    </div>

</div>
