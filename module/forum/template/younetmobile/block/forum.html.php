{item name='Thing'}
		<div class="table_row">
			<div class="forum_image">
				<div class="forum_large_{if $aForum.is_closed}closed{else}{if $aForum.is_seen}old{else}new{/if}{/if}" title="{if $aForum.is_closed}{phrase var='forum.forum_is_closed_for_posting'}{else}{if $aForum.is_seen}{phrase var='forum.forum_contains_no_new_posts'}{else}{phrase var='forum.forum_contains_new_posts'}{/if}{/if}">
				   {$aForum.total_post|number_format} <br/>
				    <span>{phrase var='forum.posts'}</span>
				 </div>
			</div>			
			<div class="forum_title">
				<header>
					<h1 itemprop="name"><a href="{permalink module='forum' id=$aForum.forum_id title=$aForum.name}"{if !empty($aForum.description)} title="{$aForum.description|parse}" {/if} class="forum_title_link" itemprop="url">{$aForum.name|clean|convert}</a></h1>
				</header>	
			</div>
			
		</div>
{/item}