{if !count($aItems)}
<div class="extra_info">
	{phrase var='blog.no_blogs_found'}
</div>
{else}
{foreach from=$aItems name=blog item=aItem}
	{template file='blog.block.entry'}
{/foreach} 

{unset var=$aItems}
{pager}
{/if}