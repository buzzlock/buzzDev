{*
<div class="p_bottom_10">
	<ul class="sub_menu_bar ">
	    <li>
            <div class="link_menu dropContent">
                <form method="post" action="{url link='forum.search'}">
                    <div class="div_menu">
                        <input type="text" name="search[keyword]" value="" class="v_middle" style="width:60%" placeholder="{phrase var='forum.search'}"/>
                         <input name="search[submit]" type="submit" value="{phrase var='forum.go'}" class="button v_middle" />
                    </div>
                    <div class="div_menu">
                        <label><input type="radio" name="search[result]" value="0" class="v_middle checkbox" checked="checked" /> {phrase var='forum.show_threads'}</label>
                        <label><input type="radio" name="search[result]" value="1" class="v_middle checkbox" /> {phrase var='forum.show_posts'}</label>
                    </div>
                </form>
                <ul>
                    <li><a href="{url link='forum.search'}">{phrase var='forum.advanced_search'}</a></li>
                </ul>
            </div>
        </li>
		
				
	</ul>
	<div class="clear"></div>
</div>
*}
<div class="p_10 {if phpfox::isMobile()}yn-forum-mobile{/if}" style="padding-top: 10px;">
{if !count($aForums)}
<div class="extra_info">
	{phrase var='forum.no_forums_have_been_created'}
	{if Phpfox::getUserParam('forum.can_add_new_forum')}
	<ul class="action">
		<li><a href="{url link='admincp.forum.add'}">{phrase var='forum.create_a_new_forum'}</a></li>
	</ul>
	{/if}
</div>
{else}
{template file='forum.block.entry'}
{/if}
</div>