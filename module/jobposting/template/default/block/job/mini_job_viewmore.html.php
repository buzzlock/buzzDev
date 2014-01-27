{foreach from=$aJobsSearch item=JobsSearch}
<div class="row_title" style="border-bottom: 1px solid #ccc;padding-bottom: 2px;padding-top:2px;">
	<div class="row_title_image">
		<a href="{permalink module='jobposting' id=$JobsSearch.job_id title=$JobsSearch.title}" title="{$JobsSearch.title}">
			{img server_id=$JobsSearch.image_server_id path='core.url_pic' file="jobposting/".$JobsSearch.image_path suffix='_50' max_width='50' max_height='50' class='js_mp_fix_width'}
		</a>
        {if $JobsSearch.permission.canEditJob || $JobsSearch.permission.canDeleteJob}
		<div class="row_edit_bar_parent">
			<div class="row_edit_bar_holder">
				<ul>
					{if $JobsSearch.permission.canEditJob}
					<li><a href="{url link='jobposting.add'}{$JobsSearch.job_id}/">{phrase var='jobposting.edit_job'}</a></li>
					<li><a href="{url link='jobposting.company.manage' job=$JobsSearch.job_id}">{phrase var='jobposting.view_application'}</a></li>
					{/if}
					{if $JobsSearch.permission.canDeleteJob}
					<li class="item_delete"><a href="javascript:void(0);" onclick="$.ajaxCall('jobposting.deleteJob_View', 'job_id={$JobsSearch.job_id}&page_view=2&company_id={$aCompany.company_id}', 'GET'); return false;" class="no_ajax_link" onclick="return confirm('Are you sure you want to delete this job?');" title="{phrase var='jobposting.delete'}">{phrase var='jobposting.delete_job'}</a></li>
					{/if}
				</ul>			
			</div>
			<div class="row_edit_bar">				
					<a href="#" class="row_edit_bar_action"><span>{phrase var='jobposting.actions'}</span></a>							
			</div>
		</div>
        {/if}	
	
	</div>						
	<div class="row_title_info">
		<span id="">
			<a href="{permalink module='jobposting' id=$JobsSearch.job_id title=$JobsSearch.title}" id="" class="link ajax_link">{$JobsSearch.title|clean|shorten:55:'...'|split:20}</a>
		</span>							
		<div class="extra_info">
			<span>{phrase var='jobposting.posted_date'}: {$JobsSearch.time_stamp_phrase}</span> - <span> {phrase var='jobposting.expired_date'}: {$JobsSearch.time_expire_phrase} </span>
		</div>							
	</div>					
</div>
{/foreach}