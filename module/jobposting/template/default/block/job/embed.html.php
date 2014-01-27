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

<div class="ynjp_embed_job_wrapper" style='border: 1px solid #dfdfdf; padding: 4px;'>
	{if $en_photo}
		<div class="ynjp_embed_job_photo">
			<a href="{permalink module='jobposting' id=$aJob.job_id title=$aJob.title}" title="{$aJob.title|clean}" target="_blank">
				{img server_id=$aJob.image_server_id path='core.url_pic' file='jobposting/'.$aJob.image_path suffix='_200' max_width='160' max_height='160'}
			</a>
		</div>
	{/if}

	<div class="ynjp_embed_job_info">
		<p style="text-align: center; font-weight: bold;">
			<a class="ynjp_embed_job_title" href="{permalink module='jobposting' id=$aJob.job_id title=$aJob.title}" title="{$aJob.title|clean}" target="_blank">
				{$aJob.title_shorten}
			</a>

			{if $en_description}
			<div class="extra_info">
				{$aJob.description_shorten}
			</div>
			{/if}
		</p>
	</div>

 	<input type='button' style="background: #627AAC; color: #fff; border: 1px #365FAF solid; font-weight: bold; height: 26px; cursor: pointer;" value="{phrase var='jobposting.view_job'}" target="_blank" onclick="window.open('{permalink module='jobposting' id=$aJob.job_id title=$aJob.title}'); return false;">
</div>
