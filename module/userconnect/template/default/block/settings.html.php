<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<div class="table">
    <div class="table_left">
        {phrase var='userconnect.connection_level_setting'}
    </div>
    <div class="table_right">
        {phrase var='userconnect.enter_the_maximum_level_depth_till_which_user_connections_are_to_be_shown_to_users_of_this_member_le'}
        <br/>
        <input type="text" value="{$settings.max_level_setting}" name="val[max_level_setting]" id="max_level_setting"/>
    </div>
    <div class="clear"></div>
</div>