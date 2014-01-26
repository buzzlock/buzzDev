<?php

defined('PHPFOX') or exit('NO DICE!');

?>

{if $bInHomepage}
	{module name='contest.homepage.featured-slideshow'}
	{module name='contest.homepage.ending-soon-contest'}
	{module name='contest.homepage.recent-contest'}
    {module name='contest.homepage.entries'}
{else}
    <div class="wrap_list_items">
        {foreach from=$aContests  name=contest item=aContest}
            {template file='contest.block.contest.listing-item'}
        {foreachelse}
        <div>
            {phrase var='contest.no_contest_found'}
        </div>
        {/foreach}
    </div>
    
    <div class="clear"></div>
    
    {if $sView == 'closed' && Phpfox::isAdmin()}
        {moderation}
    {/if}
    
    <div class="t_right">
        {pager}
    </div>
{/if}