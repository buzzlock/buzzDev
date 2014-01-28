<?php 
    defined('PHPFOX') or exit('NO DICE!');      
?>
{if !Phpfox::getParam('core.allow_cdn')}
    <div class="error_message">
        {phrase var='musicsharing.warning_message_migrations'}
    </div>
    <div class="tip">
        {phrase var='musicsharing.following_the_instruction_below_to_import_music_from_phpfox_music_module'}:<br/>
        {phrase var='musicsharing.migrations_instruction_step_1'}<br/>
        {phrase var='musicsharing.migrations_instruction_step_2'}<br/>
        {phrase var='musicsharing.migrations_instruction_step_3'}<br/>
        {phrase var='musicsharing.migrations_instruction_step_4'}<br/>
        {phrase var='musicsharing.migrations_instruction_step_5'}<br/>    
    </div>

    <div class="table_header">
        {phrase var='musicsharing.import_details'}
    </div>
    <div class="table">
        <div class="table_left">
            {phrase var='musicsharing.process'} :
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
            {phrase var='musicsharing.details'} :
        </div>
        <div class="table_right">
              <div id="info_process"></div>
        </div>
        <div class="clear"></div>
    </div>
    <br clear="all" />
    <div class="table_clear">
        <input type="submit" value="{phrase var='musicsharing.import'}" class="button" id="migrate" onclick="javascript:mir()"/>
    </div>
    <script type="text/javascript">
    {literal}
        function mir(){
            $.ajaxCall('musicsharing.migrateData', '');
        }
    {/literal}
    </script>
{else}
<div class="error_message">{phrase var='musicsharing.import_music_function_does_not_support_cdn'}</div>
{/if}    