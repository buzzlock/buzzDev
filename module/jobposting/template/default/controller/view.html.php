{if $bIsApplied}
<div class="message">{phrase var='jobposting.you_have_already_applied_this_job'}</div>
{/if}

<h1 class="ynjp_jobDetail_title"><a href="{permalink module='jobposting' id=$aJob.job_id title=$aJob.title}">{$aJob.title}</a></h1>
<div class="item_view ynjp_jobDetail_container">
	<div class="item_info">
		{phrase var='jobposting.expire_on'}: {$aJob.time_expire_phrase}
	</div>
	{if $aJob.action}
	<div class="item_bar" style="padding-top: 10px">
		<div class="item_bar_action_holder">
			<a href="#" class="item_bar_action"><span>{phrase var='jobposting.actions'}</span></a>     
			<ul>
			   {template file='jobposting.block.job.action-link'}
		   </ul>
		</div>
	</div>
	{/if}
	<div class="yns job_detail_information">
		<h4><span> {phrase var='jobposting.job_description'} </span></h4>
		<p>
			{$aJob.description_parsed|parse}
		</p>
		
		{if $aJob.total_attachment}
			{module name='attachment.list' sType=jobposting iItemId=$aJob.job_id}
		{/if}
			
		<h4><span> {phrase var='jobposting.desired_skills_experience'} </span></h4>
		<p>
			{$aJob.skills_parsed}
		</p>
		<h4><span> {phrase var='jobposting.addition_information'} </span></h4>
		<p>
			- {phrase var='jobposting.language_preference'}: {$aJob.language_prefer}<br/>
			- {phrase var='jobposting.education_preference'}: {$aJob.education_prefer}<br/>
			- {phrase var='jobposting.working_place'}: {$aJob.working_place}<br/>
			- {phrase var='jobposting.time'}: {$aJob.working_time}
			
		</p>	
	</div>
	
	<div>
		{module name='feed.comment'}
	</div>
</div>

<!-- Temp - Left Column Content -->
