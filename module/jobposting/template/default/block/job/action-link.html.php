<?php 
/**
 * [PHPFOX_HEADER]
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>

{if isset($aJob.canEditJob) && $aJob.canEditJob}
<li><a href="{url link='jobposting.add'}{$aJob.job_id}/">{phrase var='jobposting.edit'}</a></li>
<li><a href="{url link='jobposting.company.manage' job=$aJob.job_id}">{phrase var='jobposting.view_applications'}</a></li>
{/if}

{if isset($aJob.canApproveJob) && $aJob.canApproveJob}
<li><a href="#" onclick="$.ajaxCall('jobposting.approveJob', 'job_id={$aJob.job_id}', 'GET'); return false;">{phrase var='jobposting.approve'}</a></li>
{/if}

{if isset($aJob.canFeatureJob) && $aJob.canFeatureJob}
<li><a href="#" onclick="{if isset($iFeatureJobFee) && $iFeatureJobFee > 0}if (confirm('{phrase var='jobposting.pay_fee_to_feature_this_job' fee=$iFeatureJobFee}')) {/if}$.ajaxCall('jobposting.featureJob', 'job_id={$aJob.job_id}', 'GET'); return false;">{phrase var='jobposting.feature'}</a></li>
{/if}

{if isset($aJob.canunFeatureJob) && $aJob.canunFeatureJob}
<li><a href="#" onclick="$.ajaxCall('jobposting.unfeatureJob', 'job_id={$aJob.job_id}', 'GET'); return false;">{phrase var='jobposting.un_feature'}</a></li>
{/if}

{if isset($aJob.canDeleteJob) && $aJob.canDeleteJob}
<li class="item_delete"><a href="#" onclick="if(confirm('{phrase var='jobposting.are_you_sure_info'}')) $.ajaxCall('jobposting.deleteJob_View', 'job_id={$aJob.job_id}', 'GET'); return false;">{phrase var='jobposting.delete'}</a></li>
{/if}
