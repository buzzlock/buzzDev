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
 <!-- Experience information layout here -->
 <div class="yns contact-info resume_experience">
 	{if count($aLanguages) > 0 or $aOptions.can_edit }
 	<h3>
 		{phrase var="resume.languages"}
 		{ if $aOptions.can_edit }
			<a href="{url link='resume.language.id_'$aResume.resume_id}" class="add-new">+ {phrase var='resume.add_a_language'}</a>
		{/if}
 	</h3>
 	
 		{foreach from = $aLanguages item = aLanguage}
 			<div class ="experience_content extra_info" id="language_{$aLanguage.language_id}">
		 		<!-- Language Name (Level) -->
		 		<p class="f_14">
		 			<strong>{$aLanguage.name}</strong> {if $aLanguage.level}({$aLanguage.level}){/if}
		 			{ if $aOptions.can_edit }
		 				<a  class="f_11" href="{url link='resume.language.id_'$aLanguage.resume_id'.exp_'$aLanguage.language_id}">{phrase var="resume.edit"}</a>
		 			{/if}
		 			{ if $aOptions.can_delete }
		 				|
		 				<a class="f_11" href="javascript:void(0);" onclick="if(confirm('{phrase var='resume.are_you_sure'}'))$.ajaxCall('resume.delete_language','exp_id={$aLanguage.language_id}');return false;">{phrase var='resume.delete'}</a>
		 			{/if}
		 			</p>
		 		<!-- Note -->
		 		{if $aLanguage.note}
		 			<i>{phrase var="resume.note"}:</i>
		 			<p>{$aLanguage.note}</p>
		 		{/if}
	 		</div>
 		{/foreach}
 	{/if}
 </div>