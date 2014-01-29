
{literal}
<style>
	#right{
	display:none;
	}
	.content3{
		width: 100% !important;
	}
	#content_holder{
		overflow:hidden;
	}
</style>
{/literal}
<div>
	{phrase var='resume.your_profile_has_been_viewed_by_total_view_people_in_this_time_period' total_view=$iCnt}
</div>

{foreach from=$aResumes key=iKey item=aResume}
	{template file='resume.block.who-view-resume-item'}
{/foreach}

{if $bWhoViewRegistration}
	{pager}
{else}
	<div style="padding-top:10px;">
		<input type="button" class="button" value="{phrase var='resume.upgrade_you_account_to_see_the_full_list_of_who_s_viewed_you_resume'}" onclick="$Core.box('resume.register',500,'');return false;"/>
	</div>
{/if}