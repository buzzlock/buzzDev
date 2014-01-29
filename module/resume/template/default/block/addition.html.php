<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 
 * @copyright      YouNet Company
 * @author         VuDP, TienNPL, TrucPTM
 * @package        Module_Resume
 * @version        3.01
 * 
 */?>
 <!-- Experience information layout here -->
 <div class="yns contact-info extra_info">
 	{if !empty($aAddition.email) or !empty($aAddition.sport) or !empty($aAddition.movies) or !empty($aAddition.interests) or !empty($aAddition.music) or $aOptions.can_edit }
 	<h3>
 		{phrase var="resume.additional_information"}
 		{ if $aOptions.can_edit }
			<a href="{url link='resume.addition.id_'$aResume.resume_id}" class="add-new">{phrase var='resume.edit'}</a>
		{/if}
 	</h3>
 		<div class ="experience_content extra_info">
 		<!-- Web Site -->
			{if !empty($aAddition.email)}
			<div class="info ">
				<div class="info_left">{phrase var="resume.websites"}:</div>
				<div class="info_right">
					<p></p>
					{foreach from=$aAddition.email item=aWebsite}
					<p>{$aWebsite}</p>
					{/foreach}
				</div>
			</div>
			{/if}
	 		<!-- Sports -->
	 		{if !empty($aAddition.sport) }
			<div class="info ">
				<div class="info_left">{phrase var="resume.sport"}: </div>
				<div class="info_right">{$aAddition.sport}</div>
			</div>
			{/if}
	 		<!-- Movies -->
	 		{if !empty($aAddition.movies) }
			<div class="info ">
				<div class="info_left">{phrase var="resume.movies"}: </div>
				<div class="info_right">{$aAddition.movies}</div>
			</div>
			{/if}
	 		<!-- Interests -->
	 		{if !empty($aAddition.interests) }
			<div class="info ">
				<div class="info_left">{phrase var="resume.interests"}:</div>
				<div class="info_right">{$aAddition.interests}</div>
			</div>
			{/if}
	 		<!-- Music -->
	 		{if !empty($aAddition.music) }
			<div class="info ">
				<div class="info_left">{phrase var="resume.music"}:</div>
				<div class="info_right">{$aAddition.music}</div>
			</div>
			{/if}
 		</div>
 	{/if}
 </div>