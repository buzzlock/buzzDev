<?php 
/**
 * [PHPFOX_HEADER]
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>

{if isset($aCompany.canEditCompany) && $aCompany.canEditCompany}
<li><a href="{url link='jobposting.company.add' id=$aCompany.company_id}">{phrase var='jobposting.edit_company_info'}</a></li>
<li><a href="{url link='jobposting.company.add.packages' id=$aCompany.company_id}">{phrase var='jobposting.view_bought_packages'}</a></li>
<li><a href="{url link='jobposting.company.add.form' id=$aCompany.company_id}">{phrase var='jobposting.edit_submission_form'}</a></li>
<li><a href="{url link='jobposting.company.add.jobs' id=$aCompany.company_id}">{phrase var='jobposting.manage_job_posted'}</a></li>
{/if}

{if isset($aCompany.canApproveCompany) && $aCompany.canApproveCompany}
<li><a href="#" onclick="$.ajaxCall('jobposting.approveCompany', 'id={$aCompany.company_id}', 'GET'); return false;">{phrase var='jobposting.approve'}</a></li>
{/if}

{if isset($aCompany.canSponsorCompany) && $aCompany.canSponsorCompany}
<li><a href="#" onclick="$.ajaxCall('jobposting.sponsorCompany', 'id={$aCompany.company_id}', 'GET'); return false;">{phrase var='jobposting.sponsor'}</a></li>
{/if}

{if isset($aCompany.canunSponsorCompany) && $aCompany.canunSponsorCompany}
<li><a href="#" onclick="$.ajaxCall('jobposting.unsponsorCompany', 'id={$aCompany.company_id}', 'GET'); return false;">{phrase var='jobposting.un_sponsor'}</a></li>
{/if}

{if isset($aCompany.canDeleteCompany) && $aCompany.canDeleteCompany}
<li class="item_delete"><a href="#" onclick="if(confirm('{phrase var='jobposting.are_you_sure_info'}')) $.ajaxCall('jobposting.deleteCompany', '&company_id={$aCompany.company_id}', 'GET'); return false;">{phrase var='jobposting.delete'}</a></li>
{/if}
