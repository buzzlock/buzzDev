{if count($aParticipants)>0}
{foreach from=$aParticipants  name=participant item=aItem}
	{template file='contest.block.participant.listing-item'}
{/foreach}
{/if}
