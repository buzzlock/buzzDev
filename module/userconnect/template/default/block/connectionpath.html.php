
<?php


defined('PHPFOX') or exit('NO DICE!');

?>
{literal}
<style>
ul.connection_path .user_browse_image .quickFlipPanel
{
    background:url({/literal}{$core_path}{literal}module/userconnect/static/image/bg.png) scroll center center no-repeat;
}
</style>
{/literal}

<ul class="connection_path">
    {foreach from=$aConnections key=index item=view_path name=connectionpath}
    <li rel="{$view_path.user_id}" class="li_u_img" >
        {template file='userconnect.block.miniuser}
    </li>
    {if $phpfox.iteration.connectionpath < count($aConnections) }
        <li>
            <div class="arrow_path">
                <img src="{$core_path}module/userconnect/static/image/arrow.png" alt="arrow" class="img_arrow_path"/>
            </div>
        </li>
    {/if}
    {/foreach}
</ul>
<div class="clear"></div>


