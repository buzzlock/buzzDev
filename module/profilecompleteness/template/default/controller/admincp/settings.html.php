<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<form method="post" action="{url link='admincp.profilecompleteness.settings'}">
<div class="table">
    <div class="table_left">
        {phrase var='profilecompleteness.profile_address'}
    </div>
    <div class="table_right">
        {phrase var='profilecompleteness.enter_gauge_color_in_hex'}
        <div style="padding-top: 5px;">
            <input class="izzyColor" id="color1" name="val[gaugecolor]" value="{$aRow.gaugecolor}" style="width: 200px;"/>
        </div>
    </div>
</div>    
            <input type="hidden" name="val[user_image]" value="{$aRow.user_image}" />
  <div class="table">
    <div class="table_left">
        {phrase var='profilecompleteness.whether_show_widget_or_not_when_100_completed'}
    </div>
    <div class="table_right">
       <input type="checkbox" {if $aRow.check_complete eq 1}checked{/if} name="val[check_complete]"/> {phrase var='profilecompleteness.not_show_the_widget_when_100_completed'}
    </div>
</div>

<div style="padding-top: 10px;">
    <input type="submit" class="button" id="profileCompletenessSettings" name="profileCompletenessSettings" value="{phrase var='profilecompleteness.save_changes'}"/>
</div>
</form>
