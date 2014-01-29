<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
{if $is_temp==0}
<style type="text/css">
    {literal}
    .profile_completed{
        background-color: #007700;
        height: 24px;
    }
    .profile_project_content{
        padding: 10px;
        background-color: #E9F4FA;
        -moz-border-radius: 3px 3px 3px 3px;
    }
 
    .layout_profile_completeness_profile_completeness >ul >li +li{
        margin-top: 5px;
        font-size: 1.0em;
        
    }

    .profile_project_content a:link,a:visiter{
        color:#5F93B4;
        text-decoration: none;
        
    }
    {/literal}


</style>

<div class="layout_profile_completeness_profile_completeness">
<ul class="profile_project_content">
    <li>
      {$iPercent}% {phrase var='profilecompleteness.profile_completeness'}
    </li>
    <li>
        <div style="background-color: {$colorbackground};">
            <div class="profile_completed" style="width: {$iPercent}%;">
            </div>
        </div>
    </li>
    {if $PercentTotal!=100}
    <li>
        {phrase var='profilecompleteness.next'}: <a href="{if $isPhoTo==1}{url link='user.profile' group={$iGroup_id}{else}{url link='user.photo'}{/if}">+ {$Key} (+{$PercentValue}%)</a>
    </li>
    <li>
        <a href="{url link='user.profile'}">{phrase var='profilecompleteness.update_profile'}</a>
    </li>
    {/if}
</ul>
</div>
{/if}


