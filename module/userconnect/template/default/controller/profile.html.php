<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

defined('PHPFOX') or exit('NO DICE!');
?>

{if count($array_temp)>1}
    {if $showconnectionpath==1}
        {foreach from=$array_temp value=temp}
            <div style="margin:10px 15px 0px;float: left;width:100px; text-align: center;"" >
                <div style="height:92px;background:#E3F0F9; border: 1px solid #51A0DA; padding: 10px 4px; ">
                    <div style="height:50px;">
                        {img user=$temp suffix='_50_square' height='50' max_width=50 max_height=50}
                    </div>
                    <div class="p_top_4">
                        <a href="{url link=''}{$temp.user_name}/" title="{$temp.full_name}">{$temp.full_name|clean|shorten:12:"...":false}</a>
                    </div>
                    {if $temp.user_id!=phpfox::getUserId()}
                    <div class="p_top_4">
                        <a href="{url link='mail.compose' id=$aUser.user_id}" title="{phrase var='user.send_message'}">{img theme='misc/email_go.png' alt='' class='v_middle'}</a>
                        {if $temp.user_id!= $friend_id}
                        <a href="#?call=friend.request&amp;user_id={$temp.user_id}&amp;width=420&amp;height=250" class="inlinePopup" title="{phrase var='profile.add_to_friends'}">{img theme='misc/user_add.png' alt='' class='v_middle'}</a>
                        {else}
                        <a>{img theme='misc/friend_added.png' alt='' class='v_middle'}</a>
                        {/if}

                    </div>
                    {/if}
                </div>
            </div>
            {if $end_line!=$temp.user_id}
                <div style="float: left; line-height: 140px;padding:0px;">
                    <img src="{$corepath}module/userconnect/static/image/line.png"/>
                </div>
            {/if}
        {/foreach}
    {else}
        <div>
            {phrase var='userconnect.there_is_no_permission_to_view_connection_path'}
        </div>
    {/if}
{else}
    {if count($array_temp)==1}
        <div>
            {phrase var='userconnect.there_is_no_connection'}
        </div>
    {else}
        <div>
           {phrase var='userconnect.there_is_no_connection_to_this_user'}
        </div>
    {/if}
{/if}
