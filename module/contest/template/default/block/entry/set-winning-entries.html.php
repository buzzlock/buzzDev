{if $limit_entries==0}
<form method="post" id="form_set_winning" name="form_set_winning" class="yc_view_set_wining_form">
<div class="">
	{foreach from=$aEntries item=aItem}
		{template file='contest.block.entry.set-side-block-listing-wining'} 
	{/foreach}
		<div style="padding-top: 9px;padding-left: 70px;">	
						<div class="table_left" style="width: 50px;float:left;">
							&nbsp;
						</div>
						<div class="table_right">
							<input type="button" id='yncontest_ok_to_set_winning_button' value="{phrase var='contest.ok'}" class="button" onclick="$('#yncontest_ok_to_set_winning_button').attr('disabled', 'disabled'); $('#form_set_winning').ajaxCall('contest.submit_form_set_winning');return false;"/>
						</div>
					</div>

</form>

{if count($aEntries)==0}
    {phrase var='contest.currently_there_is_not_any_winning_entries' link=''}
{/if}
{else}
<div>
	{phrase var='contest.you_have_reached_limit_for_this_contest' link=$link}   
</div>
{/if}
