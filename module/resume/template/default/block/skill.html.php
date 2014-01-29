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
 <!-- Skill layout here -->
 <div class="yns contact-info">
 	{if count($aResume.skills) > 0 or $aOptions.can_edit }
 	<h3>
 		{phrase var="resume.skills_expertise"}
 		{if  $aOptions.can_edit }
			<a href="{url link='resume.skill'}id_{$aResume.resume_id}/" class="add-new">+ {phrase var='resume.add_a_skill'}</a>
		{/if}
	</h3>
	<div class="skill_education">
		{foreach from = $aResume.skills item = aSkill}
			<a>{$aSkill}</a>
		{/foreach}
	</div>
	{/if}
 </div>