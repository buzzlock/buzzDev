<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<div id="js_userconnect_content">
    {if count($aUsers)==0}
        {if $keyword == false}
            {phrase var='userconnect.there_are_no_friends_at_this_level'}
        {else}
            {phrase var='userconnect.unable_to_find_any_friends_with_the_current_search_criteria'}
        {/if}
    {else}
        {if $total_friends}
            <h3> {phrase var='userconnect.total'}: {$total_friends} {if $total_friends > 1}{phrase var='userconnect.friends'}{else} {phrase var='userconnect.friend'}{/if} </h3>	
            {if count($aUsers)>0}
                {template file="userconnect.block.entrynew"}
            {/if}
            <div id="js_userconnect_viewmore_show"></div>
                <div class="clear"></div>
                <div class="js_pager_view_more_link" >
                    <div class="pager_view_more_holder">
                        <div class="pager_view_more_link" id="js_userconnect_viewmore">
                            {if $ViewMore==1}
                                <a onclick="$(this).html($.ajaxProcess('Loading...')); $.ajaxCall('userconnect.viewMoreLevel', 'page={$Page}&amp;level={$level}'); return false;" class="pager_view_more no_ajax_link" href="{url link='userconnect/view'}page_{$Page}/level_{$level}">View More</a>
                            {/if}
                        </div>
                    </div>
                </div>
        {/if}
    {/if}
</div>
