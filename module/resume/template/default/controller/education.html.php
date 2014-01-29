<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<div class="education-session">
{template file='resume.block.menu_add'}
</div>
<div style="position: relative">
<h3  class="yns add-res">
<ul class="yns menu-add">
	<li>{phrase var='resume.education'}</li>
	<li><a class="page_section_menu_link" href="{url link='resume.education'}id_{$id}/">{phrase var='resume.add_a_school'}</a></li>
</ul>
<ul class="yns action-add">
	{if $bIsEdit}<li><a class="page_section_menu_link" href="{url link='resume.view'}{$id}/">{phrase var='resume.view_my_resume'}</a></li>{/if}
</ul>
</h3>
</div>

<form method="post" name="js_resume_add_form" enctype="multipart/form-data">

<div id="headline">

	<div class="table" style="padding-top: 10px;">
			<div class="table_left table_left_add">
			<label for="school_name">{required}{phrase var='resume.shool_name'}:</label>
			</div>
			<div class="table_right">
				<input type="text" name="val[school_name]" value="{value type='input' id='school_name'}" id="school_name" size="40" maxlength="100" />
			</div>
	</div>	
	
	<div class="table" style="padding-top: 10px;">
			<div class="table_left table_left_add">
			<label for="degree">{required}{phrase var='resume.degree'}:</label>
			</div>
			<div class="table_right">
				<input type="text" name="val[degree]" value="{value type='input' id='degree'}" id="degree" size="40" maxlength="100" />
			</div>
	</div>	
	
	<div class="table" style="padding-top: 10px;">
			<div class="table_left table_left_add">
			<label for="field">{required}{phrase var='resume.field_of_study'}:</label>
			</div>
			<div class="table_right">
				<input type="text" name="val[field]" value="{value type='input' id='field'}" id="field" size="40" maxlength="100" />
			</div>
	</div>	
	
	<div class="table">
		<div class="table_left table_left_add">
			<label for="postal_code">{required}{phrase var='resume.dates_attended'}:</label>
		</div>
		<div class="table_right">
			<select name='val[start_year]' id="start_year">
				<option value="">{phrase var='resume.year'} ...</option>
				{foreach from=$aYear item=year}
					<option {if !empty($aForms) && $aForms.start_year==$year}selected{/if}>{$year}</option>
				{/foreach}
			</select>
			{phrase var='resume.to'}
			<select name='val[end_year]' id="end_year">
			<option value="">{phrase var='resume.year'} ...</option>
				{foreach from=$aYear item=year}
					<option {if !empty($aForms) && $aForms.end_year==$year}selected{/if}>{$year}</option>
				{/foreach}
			</select>
			<div>
				{phrase var='resume.tip_current_students_enter_your_expected_graduation_year'}
			</div>
		</div>
	</div>	
	
	<div class="table" style="padding-top: 10px;">
			<div class="table_left table_left_add">
			<label for="shoolname">{phrase var='resume.grade'}:</label>
			</div>
			<div class="table_right">
				<input type="text" name="val[grade]" value="{value type='input' id='grade'}" id="grade" size="40" maxlength="100" />
			</div>
	</div>	
	
	<div class="table">
		<div class="table_left table_left_add">
			<label for="activity">{phrase var='resume.activities_and_societies'}:</label>
		</div>
		<div class="table_right">
			<textarea cols="70" rows="5" name="val[activity]">{if !empty($aForms)}{$aForms.activity}{/if}</textarea>
		</div>
		<div>
			{phrase var='resume.tip_use_commas_to_separate_multiple_activities'}
		</div>
	</div>	
	
	<div class="table">
		<div class="table_left table_left_add">
			<label for="note">{phrase var='resume.additional_notes'}:</label>
		</div>
		<div class="table_right">
			<textarea cols="70" rows="5" name='val[note]'>{if !empty($aForms)}{$aForms.note}{/if}</textarea>
		</div>
	</div>	
	
		
	<div class="table">
		<div class="table_left table_left_add">
			
		</div>
		<div class="table_right ">
			<input type="submit" class="button" value = "{phrase var='resume.update'}"/>
			<input type="button" class="button" value ="{phrase var='resume.skip'}" onclick="window.location.href='{url link='resume.skill'}id_{$id}'"/>
		</div>			
	</div>	
</div>

</form>

{if count($aRows)>0}
<div>
	<span style="font-size: 20px;">{phrase var="resume.list_of_schools"}</span>
	{foreach from=$aRows item=aRow}
	<div style="padding-top:10px;" id='education_{$aRow.education_id}'>
	<span style="font-weight:bold;font-size:13px;">{if $aRow.school_name!=""}{$aRow.school_name}{else}No name{/if}</span> 
	<span style="padding-left:20px;">
		<a href="{url link='resume.education'}id_{$id}/exp_{$aRow.education_id}/">{phrase var="resume.edit"}</a> 
		| 
		<a href="#" onclick="if(confirm( '{phrase var='resume.are_you_sure'}' ))$.ajaxCall('resume.delete_education','exp_id={$aRow.education_id}');return false;">{phrase var='resume.delete'}</a></span>
	<!-- Degree, Field -->
	<p>{$aRow.degree}, {$aRow.field}</p>
	<!-- Time Period -->
	<p>{$aRow.start_year} - {$aRow.end_year}</p>	
	</div>
	{/foreach}
</div>
{/if}
