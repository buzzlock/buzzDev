
<div class="yc_detail_contest">
    {$aContest.description_show|shorten:'350':'comment.view_more':true|split:350|parse}
</div>

{if $aContest.total_attachment}
	{module name='attachment.list' sType=contest iItemId=$aContest.contest_id}
{/if}