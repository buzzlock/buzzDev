<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

defined('PHPFOX') or exit('NO DICE!');
?>
{literal}
<style type="text/css">
    .frpr1 {
        display: block;
        margin-bottom: 0;
        margin-left: 3px;
        margin-right: 3px;
        margin-top: 3px;
        overflow-x: hidden;
        overflow-y: hidden;
        padding-bottom: 10px;
        padding-left: 0;
        padding-right: 0;
        padding-top: 10px;
        position: relative;
    }
    .titleBread{
        color: #6B6B6B;
        text-decoration: none;
        font-weight: bold;
    }

    .cm_user
    {
        position: absolute;
        top:8px;
        left:4px;
    }
    .cm_user a img
    {
        width:50px;
        height:50px;
    }
    .comment_mini div.cm_info
    {
        min-height: 50px;
        width:350px;
    }
    .cm_info .extra_info
    {
        padding:5px 0px 0px 0px;
    }
    .cm_info .extra_info span
    {
        color:#484848;
    }
    .cm_info .extra_info span a
    {
        color: #3578A2;
    }

    div.cm_info{
        margin-left: 63px;
        margin-right:3px;
        color: #575757;
        min-height:50px;

    }
    #sidebar div.cm_info
    {
        margin-right:0px;
        margin-left: 65px;
    }
    .cm_info .extra_info .interact_time
    {
        color:#6F8CDC;
    }
    .connect_layout1
    {
        margin-top: 10px;
    }
    .layout1_item
    {
      margin-left: 70px;
      position: relative;
    }
    .connect_layout2
    {

        display: block;
        margin-right: 10px;                
        text-align: center;
        position: relative;
        margin-top: 10px;


    }
    .connect_layout3
    {
        float: left;
        margin-top: 10px;
    }
    .info_item{
        float: left;
        width:50px;
        text-align: center;
    }
    .info_nav{
        float:left;
        margin: 0px 6px;
        line-height: 50px;
    }
    .info_layout2
    {
    left: 70px;
    padding-top:3px;
    padding-bottom: 5px;
    position: absolute;
  

    }
</style>
{/literal}
{if $show_connection_path}
{if $showconnectionpath==1}
{if count($array_temp)>1}
<div class="titleBread">
    {$titleBread}
</div>
{foreach from=$array_temp value=temp}

{if $connection_layout==1}
<div id="js_item_sidebar_{$temp.user_id}" class="connect_layout1">
    <div style="min-height:50px;">
        <div style="float:left;">
            {img user=$temp suffix='_60_square' max_width=60 max_height=60}
        </div>
        <div class="layout1_item">
            <div style="font-size: 14px; margin: 4px 4px 4px 0px;">
                <a href="{url link=''}{$temp.user_name}/">{$temp.full_name}</a>
            </div>
            {if $temp.user_id!=$user_id}
            <div style="margin-top:4px">
            <div class="p_top_4"><a href="{url link='mail.compose' id=$temp.user_id}" title="{phrase var='user.send_message'}">{img theme='misc/email_go.png' alt='' class='v_middle'} {phrase var='user.send_message'}</a></div>
            {if $temp.user_id!= $friend_id}
            <div class="p_top_4"><a href="#" onclick="return $Core.addAsFriend('{$temp.user_id}');" class="inlinePopup" title="{phrase var='profile.add_to_friends'}">{img theme='misc/user_add.png' alt='' class='v_middle'} {phrase var='user.add_to_friends'}</a></div>
            {else}
            <div class="p_top_4"><a>{img theme='misc/friend_added.png' alt='' class='v_middle'} Current Friend</a></div>
            {/if}
            </div>
            {/if}
        </div>
    </div>
    <div class="clear"></div>
    
    {if $end_line!=$temp.user_id}
    <div style="margin:5px 0px 5px 17px;">
        <img src="{$corepath}module/userconnect/static/image/linedown.png"/>
    </div>
    {/if}
</div>
{/if}
{if $connection_layout==2}
<div id="js_item_sidebar_{$temp.user_id}" class="connect_layout2">    
    <div style="font-size:14px">
        <a href="{url link=''}{$temp.user_name}/">{$temp.full_name}</a>
    </div>
    <div class="info_layout2">
        {if $temp.user_id!=$user_id}
        <a href="{url link='mail.compose' id=$temp.user_id}" title="{phrase var='user.send_message'}">{img theme='misc/email_go.png' alt='' class='v_middle'}</a>
        {if $temp.user_id!= $friend_id}
        <a href="#" onclick="return $Core.addAsFriend('{$temp.user_id}');" class="inlinePopup" title="{phrase var='profile.add_to_friends'}">{img theme='misc/user_add.png' alt='' class='v_middle'}</a>
        {else}
        <a>{img theme='misc/friend_added.png' alt='' class='v_middle'}</a>
        {/if}
        {/if}
    </div>
    {if $temp.user_id !=$user_id}
    {if $end_line!=$temp.user_id}
    <div style="padding:28px 0px 0px 0px;">
        <img src="{$corepath}module/userconnect/static/image/linedown_small.png"/>
    </div>
    {/if}
    {else}
    {if $end_line!=$temp.user_id}
    <div style="padding:10px 0px 0px 0px;">
        <img src="{$corepath}module/userconnect/static/image/linedown_small.png"/>
    </div>
    {/if}
    {/if}
</div>
{/if}
{if $connection_layout==3}
<div id="js_item_sidebar_{$temp.user_id}" class="connect_layout3">
    <div class="info_item">
        <div style="height:50px; margin-bottom: 5px;">
            {img user=$temp suffix='_50_square' max_width=50 max_height=50}
        </div>
        <div class="info_icon">
            <div>
                <a href="{url link=''}{$temp.user_name}/">{$temp.full_name|clean|shorten:10:"...":false}</a>
            </div>
            {if $temp.user_id!=$user_id}
            <div class="p_top_4">
                <a href="{url link='mail.compose' id=$temp.user_id}" title="{phrase var='user.send_message'}">{img theme='misc/email_go.png' alt='' class='v_middle'}</a>
                 {if $temp.user_id != $friend_id}
                 <a href="#" onclick="return $Core.addAsFriend('{$temp.user_id}');" class="inlinePopup" title="{phrase var='profile.add_to_friends'}">{img theme='misc/user_add.png' alt='' class='v_middle'}</a>
                 {else}
                 <a>{img theme='misc/friend_added.png' alt='' class='v_middle'}</a>
                {/if}
            </div>
            {/if}
        </div>
    </div>
    {if $end_line!=$temp.user_id}
    <div class="info_nav">
        <img src="{$corepath}module/userconnect/static/image/line_small.png"/>
    </div>
    {/if}
    <div class="clear"></div>
</div>
{/if}
{/foreach}
<div class="clear"></div>
{else}
<div>{$message_show} </div>
{/if}
{else}
<div>
    {$message_show}
</div>
{/if}
{/if}