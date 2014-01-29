
<?php


defined('PHPFOX') or exit('NO DICE!');

?>

<link rel="stylesheet" type="text/css" href="{$core_path}module/userconnect/static/css/default/default/userconnect.css" /> 
<script type="text/javascript" src="{$core_path}module/userconnect/static/jscript/userconnection.js"> </script> 
{literal}
<style>
ul.connection_path .user_browse_image .quickFlipPanel
{
    background:url({/literal}{$core_path}{literal}module/userconnect/static/image/bg.png) scroll center center no-repeat;
}
</style>
{/literal}
{if count($aConnections) >1}
    <ul class="connection_path front_end">
        {foreach from=$aConnections key=index item=view_path name=connectionpath}
            <li rel="{$view_path.user_id}" class="li_u_img" >
                 {template file='userconnect.block.miniuser}
            </li>
            {if $phpfox.iteration.connectionpath < count($aConnections) }
                <li>
                    <div class="arrow_path_sidebar">
                        <img src="{$core_path}module/userconnect/static/image/arrowv.png" alt="arrow" class="img_arrow_path"/>
                    </div>
                </li>
            {/if}
        {/foreach}
    </ul>
    <div class="clear"></div>
    <script type="text/javascript">
         $Behavior.LoadinitMenuViewPath = function(){l}
            $Behavior.initMenuViewPath();
        {r}
    </script>
{/if}
<div class="clear"></div>


