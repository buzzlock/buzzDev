{if !count($aPolls)}
<div class="extra_info">
	{phrase var='poll.no_polls_found'}
</div>
{else}
{foreach from=$aPolls item=aPoll key=iKey name=polls}
	{template file='poll.block.entry'}
{/foreach}
{if Phpfox::getUserParam('poll.poll_can_moderate_polls')}

{/if}
{pager}
{/if}