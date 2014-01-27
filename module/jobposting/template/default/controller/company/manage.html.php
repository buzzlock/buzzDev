<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright        [YOUNET_COPPYRIGHT]
 * @author           AnNT
 * @package          Module_jobposting
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>

<div class="table">
    <div class="table_left">
        {phrase var='jobposting.job_title'}: <a href="{permalink module='jobposting' id=$aJob.job_id title=$aJob.title}">{$aJob.title}</a>
    </div>
</div>

<table class="default_table" cellpadding="0" cellspacing="0">
    <tr>
        <th align="left">{phrase var='jobposting.candidate'}</th>
        <th>{phrase var='jobposting.submitted_date'}</th>		
        <th>{phrase var='jobposting.status'}</th>
        <th align="left">{phrase var='jobposting.option'}</th>
    </tr>
    {foreach from=$aApplications name=application item=aApplication}
    <tr{if is_int($phpfox.iteration.application/2)} class="on"{/if} id="js_ja_{$aApplication.application_id}">
        <td><a href="{url link=''}{$aApplication.user_name}/">{$aApplication.full_name}</a></td>
        <td class="t_center">{$aApplication.time_stamp_text}</td>
        <td class="t_center">{phrase var='jobposting.'+$aApplication.status_name}</td>
        <td>
            <a class="no_ajax_link" href="{$urlModule}jobposting/static/php/download.php?id={$aApplication.application_id}">{phrase var='jobposting.download'}</a>
            | <a href="#" onclick="ynjobposting.application.view({$aApplication.application_id}, '{phrase var='jobposting.view_application'}'); return false;">{phrase var='jobposting.view'}</a>
            | <a href="#" onclick="ynjobposting.application.confirm_delete({$aApplication.application_id}, '{phrase var='core.are_you_sure'}'); return false;">{phrase var='jobposting.delete'}</a>
            {if $aApplication.status_name=='pending' || $aApplication.status_name=='passed'}
            | <a href="#" onclick="ynjobposting.application.reject({$aApplication.application_id}); return false;">{phrase var='jobposting.reject'}</a>
            {/if}
            {if $aApplication.status_name=='pending' || $aApplication.status_name=='rejected'}
            | <a href="#" onclick="ynjobposting.application.pass({$aApplication.application_id}); return false;">{phrase var='jobposting.pass'}</a>
            {/if}
        </td>
    </tr>
    {foreachelse}
    <tr>
        <td colspan="6">
            <div class="extra_info">{phrase var='jobposting.no_application_found'}.</div>
        </td>
    </tr>
    {/foreach}
</table>
{pager}

<div class="table_clear" style="margin-top: 10px;">
    <ul class="table_clear_button">
        <li><input type="button" class="button" value="{phrase var='jobposting.download_all_resumes'}" onclick="window.location.href='{$urlModule}jobposting/static/php/downloadzip.php?id={$aJob.job_id}'" /></li>
    </ul>
    <div class="clear"></div>
</div>
