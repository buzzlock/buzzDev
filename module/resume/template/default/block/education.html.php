<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 *
 * @copyright      YouNet Company
 * @author         VuDP, TienNPL, TrucPTM
 * @package        Module_Resume
 * @version        3.01
 * 
 */?>
 <!-- Education information layout here -->
 <div class="yns contact-info resume_experience">
 	{if count($aEducation) > 0 or $aOptions.can_edit }
 	<h3>{phrase var="resume.education"}
 		{ if $aOptions.can_edit }
			<a href="{url link='resume.education.id_'$aResume.resume_id}" class="add-new">+ {phrase var='resume.add_a_school'}</a>
		{/if}
	</h3>
 		{foreach from = $aEducation item = aEdu}
 			<div class="experience_content extra_info" id="education_{$aEdu.education_id}">
 				<!-- School Name -->
	 			<p class="f_14">
	 				<strong>{$aEdu.school_name}</strong>
	 				{ if $aOptions.can_edit }
		 				<a  class="f_11" href="{url link='resume.education.id_'$aEdu.resume_id'.exp_'$aEdu.education_id}">{phrase var="resume.edit"}</a>
		 			{/if}
		 			{ if $aOptions.can_delete }
		 				{ if $aOptions.can_edit } | {/if}
		 				<a class="f_11" href="javascript.void(0);" onclick="if(confirm('{phrase var='resume.are_you_sure'}'))$.ajaxCall('resume.delete_education','exp_id={$aEdu.education_id}');return false;">{phrase var='resume.delete'}</a>
		 			{/if}
	 			</p>
	 			<!-- Degree, Field -->
	 			<p>{$aEdu.degree}, {$aEdu.field}</p>
	 			<!-- Time Period -->
	 			<p>{$aEdu.start_year} - {$aEdu.end_year}</p>
	 			<!-- Grade -->
	 			{if $aEdu.grade}
	 				<p>{phrase var="resume.grade"}: {$aEdu.grade}</p>
	 			{/if}
	 			{ if $aEdu.activity_parsed }
	 			<!-- Activity -->
	 			<p><i>{phrase var="resume.activities_and_societies"}:</i></p>
	 			<p style="margin-left:10px;">{$aEdu.activity_parsed}</p>
	 			{/if }
	 			<!-- Note -->
	 			{if $aEdu.note_parsed }
		 			<p><i>{phrase var="resume.additional_notes"}:</i></p>
		 			<p style="margin-left:10px;">{$aEdu.note_parsed}</p>
	 			{/if}
			</div>		
		{/foreach}
 	{/if}
 </div>