{if $bInHomepage}
    {module name='jobposting.job.featured-slideshow'}
{/if}

{if !count($aJobs)}
	<div>{phrase var='jobposting.no_jobs_found'}</div>
{else}
{foreach from=$aJobs item=aJob}
	{template file='jobposting.block.job.entry'}
{/foreach}

{if Phpfox::getUserParam('jobposting.can_approve_job') || Phpfox::getUserParam('jobposting.can_delete_job_other_user')}
{moderation}
{/if}

{pager}
{/if}
