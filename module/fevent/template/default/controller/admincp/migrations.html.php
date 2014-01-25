<?php
    defined('PHPFOX') or exit('NO DICE!');
?>
<div class="error_message">
    <b>{phrase var='fevent.backup_database'} </b> {phrase var='fevent.before_run_the_import_from_original_event_default_event_of_phpfox_to_fevent_module'} .<br/>
    {phrase var='fevent.fevent_does_not_import_activity_feeds_notifications_from_old_event'}.
</div>
<div class="tip">
    {phrase var='fevent.following_the_instruction_below_to_import_event_from_phpfox_event_module'} :<br/>
    1. {phrase var='fevent.backup_database_lower'}.<br/>
    2. {phrase var='fevent.click_import_button'}.<br/>

</div>

<div class="table_header">
       {phrase var='fevent.import_details'}
</div>
    <div class="table">
        <div class="table_left">
            {phrase var='fevent.process'} :
        </div>
        <div class="table_right">
            <div id="contener_pro" style="width: 100%;border:1px solid black;height:20px;text-align: center;">
                <div id="contener_percent" style="background-color: fuchsia; height: 100%; width: 0%; padding-top: 4px;">
                   &nbsp;0%
                </div>

            </div>
        </div>
        <div class="clear"></div>
    </div>
     <div class="table" style="min-height: 20px;">
        <div class="table_left">
            {phrase var='fevent.details'} :
        </div>
        <div class="table_right">
              <div id="info_process"></div>
        </div>
        <div class="clear"></div>
    </div>
	<br clear="all" />
	<div class="table_clear">
		<input type="submit" value="Import" class="button" id="migrate" onclick="javascript:mir()"/>
	</div>
 <script type="text/javascript">
 {literal}
    function mir()
    {
        $.ajaxCall('fevent.migrateData','');
    }
 {/literal}
 </script>
    