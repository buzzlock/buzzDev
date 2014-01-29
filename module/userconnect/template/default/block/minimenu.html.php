<?php
  defined('PHPFOX') or exit('NO DICE!');
?>
<div class="uscn_mini_menu">
    <div class="uscn_control">
        {if $view_path.user_id != $user_id}
            {if (isset($phpfox.iteration.connectionpath) &&  $phpfox.iteration.connectionpath> 2)||(isset($level_start_id)&& $level_start_id>=2)}
            <span>
                <a href="#?call=friend.request&amp;width=420&amp;user_id={$view_path.user_id}" class="inlinePopupUserConnect" title="{phrase var='profile.add_to_friends'}">
                    {img theme='misc/user_add.png' alt='' class='v_middle'}
                </a>
            </span>
            {/if}
            <span>
                <a href="#?call=mail.compose&amp;height=300&amp;width=500&amp;id={$view_path.user_id}" class="inlinePopupUserConnect" title="{phrase var='user.send_message'}">
                    {img theme='misc/email_go.png' alt='' class='v_middle'}
                </a>
            </span>
            {if (isset($phpfox.iteration.connectionpath) && $phpfox.iteration.connectionpath< count($aConnections))|| (isset($is_view_js)&& $is_view_js == true)}
                <span>
                    <a href="javascript:void(0);" class="find_path" title="{phrase var="userconnect.change"}" onclick="javascript:findPath(this,{$view_path.user_id},{$from_id},{$level});return false;">
                       <img class="v_middle" alt="" src="{$core_path}module/userconnect/static/image/refresh.png">
                    </a>
                </span>
            {/if}
        {/if}
        <div>
            <div class="clear"></div>
        </div>
    </div>
</div>
