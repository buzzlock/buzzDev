<div class="block ynjp_jobDetailApply">
	<div class="ynjp_jobDetailTitle">
		<a href="{permalink module='jobposting.company' id=$aCompany.company_id title=$aCompany.name}">{$aCompany.name}</a>
	</div>
	<div class="extra_info">
		{$aCompany.location}<br/>
		{phrase var='jobposting.website'}: <a href="javascript:void(0);"> {$aCompany.website} </a>
	</div>
    {if !$bIsApplied}
	<div class="ynjp_applyJob_btn_holder">
		<div class="ynjp_applyJob_btn" onclick="" href="{permalink module='jobposting.applyjob' id=$aJob.job_id title=$aJob.title}"> 
			<a class="ynjp_applyJob_btn_a" href="{permalink module='jobposting.applyjob' id=$aJob.job_id title=$aJob.title}"> {phrase var='jobposting.apply_job'} </a> 
		</div>
	</div>
    {/if}
</div>