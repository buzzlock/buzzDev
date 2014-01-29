<?php
/**
 *
 *
 * @copyright        [YouNet_COPYRIGHT]
 * @author           YouNet Company
 * @package          Module_GettingStarted
 * @version          2.01
 */
defined('PHPFOX') or exit('NO DICE!');
?>

<form method="post"  action="{url link='admincp.userconnect.settings'}" id="admincp_userconnection_form_message">
    <input type="hidden" name="action" value="add"/>
    <div class="table_header">
        {phrase var='userconnect.global_settings'}
    </div>

    <div class="table">
        <div class="table_left">
            {phrase var='userconnect.position_of_the_connection_path_widget'}
        </div>
        <div class="table_right">
            {phrase var='userconnect.select_the_position_for_the_connection_path_widget_with_will_show_the_connection_path_between_the_pr'}
            <br/>
            <div style="padding-top: 15px;padding-left: 0px;line-height: 20px;">
                <input type="radio" value="1" {if $connection_layout==1}checked{/if} name="val[connection_layout]"/><span style="color: #195B85;padding-left: 2px;">{phrase var='userconnect.sidebar_vertical'}</span><br/>
                {phrase var='userconnect.for_this_please_enable_the_connection_path_widget_in_the_sidebar_of_member_profile_page'}
                <br/><input type="radio" value="2" {if $connection_layout==2}checked{/if} name="val[connection_layout]"/><span style="color: #195B85;padding-left: 2px;">{phrase var='userconnect.sidebar_vertical_without_image'}</span><br/>
                {phrase var='userconnect.for_this_please_enable_the_connection_path_widget_in_the_sidebar_of_member_profile_page'}
                <br/><input type="radio" value="3" {if $connection_layout==3}checked{/if}  name="val[connection_layout]"/><span style="color: #195B85;padding-left: 2px;">{phrase var='userconnect.sidebar_leveled'}</span><br/>
                {phrase var='userconnect.for_this_please_enable_the_connection_path_widget_in_the_sidebar_of_member_profile_page'}
            </div>
        </div>
        <div class="clear"></div>
    </div>

    <div class="table_clear">
        <input type="submit" value="Save Changes" class="button" name="save_change_global_setings"/>
    </div>
</form>



<script type="text/javascript">
    
    {literal}
    function isNumber(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }

    function checkValidateSetting()
    {

        return true;
    }

    function checkValidate()
    {
        var max_level_setting = $('#max_level_setting').val();
        if (!isNumber(max_level_setting) || max_level_setting<2 || max_level_setting>5) {

            alert('Level Setting value is invalid');
            return false;
        }
        return true;
    }
    {/literal}
</script>
