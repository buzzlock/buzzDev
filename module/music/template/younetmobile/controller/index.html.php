{if count($aSongs)}
{foreach from=$aSongs name=songs item=aSong}
	{template file='music.block.entry'}
{/foreach}

{pager}
{else}
<div class="extra_info">
	{phrase var='music.no_songs_found'}
</div>
{/if}