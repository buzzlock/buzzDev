
{$aBlogEntry.blog_content_parsed}

{if isset($aBlogEntry) && $aBlogEntry.total_attachment}
	{if $bIsPreview}
		 {module name='contest.entry.content.attachment.list' sType=blog iItemId=$aBlogEntry.blog_id}
	{else}
	    {module name='contest.entry.content.attachment.list' sType=contest_entry_blog iItemId=$aBlogEntry.entry_id}
	{/if}
{/if}