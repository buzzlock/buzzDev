<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<div class="certi-session">
{template file='resume.block.menu_add'}
</div>

<div style="position: relative">
	<h3  class="yns add-res">
		<ul class="yns menu-add">
			<li>{phrase var='resume.certifications'}</li>
			<li><a class="page_section_menu_link" href="{url link='resume.certification'}id_{$id}/">{phrase var="resume.add_a_certification"}</a></li>
		</ul>
		<ul class="yns action-add">
			{if $bIsEdit}<li><a class="page_section_menu_link" href="{permalink module='resume.view' id=$id}">{phrase var='resume.view_my_resume'}</a></li>{/if}
		</ul>
	</h3>
</div>

<form method="post" enctype="multipart/form-data" name="js_resume_add_form">

<div id="headline">

	<div class="table" style="padding-top: 10px;">
			<div class="table_left table_left_add">
			<label for="certification_name">{required}{phrase var='resume.certification_s_name'}:</label>
			</div>
			<div class="table_right">
				<input type="text" name="val[certification_name]" value="{value type='input' id='certification_name'}" id="certification_name" size="40" maxlength="100" />
			</div>
	</div>	
	
	<div class="table" style="padding-top: 10px;">
			<div class="table_left table_left_add">
			<label for="course_name">{phrase var='resume.course_s_name'}:</label>
			</div>
			<div class="table_right">
				<input type="text" name="val[course_name]" value="{value type='input' id='course_name'}" id="course_name" size="40" maxlength="100" />
			</div>
	</div>	
	
	<div class="table" style="padding-top: 10px;">
			<div class="table_left table_left_add">
			<label for="training_place">{phrase var='resume.training_in_place'}:</label>
			</div>
			<div class="table_right">
				<input type="text" name="val[training_place]" value="{value type='input' id='training_place'}" id="training_place" size="40" maxlength="100" />
			</div>
	</div>	
	
	<div class="table">
		<div class="table_left table_left_add">
			<label for="postal_code">{phrase var='resume.dates_attended'}:</label>
		</div>
		<div class="table_right">
			<select name="val[start_month]">
			<option value="">{phrase var='resume.uppercase_month'} ...</option>
			{foreach from=$aMonth item=month}
				<option {if !empty($aForms) && $aForms.start_month==$month}selected{/if}>{$month}</option>
			{/foreach}
			</select>
			<select name='val[start_year]' id="start_year">
				<option value="">{phrase var='resume.year'} ...</option>
				{foreach from=$aYear item=year}
					<option {if !empty($aForms) && $aForms.start_year==$year}selected{/if}>{$year}</option>
				{/foreach}
			</select>
			{phrase var='resume.to'}
			<select name="val[end_month]">
			<option value="">{phrase var='resume.uppercase_month'}</option>
			{foreach from=$aMonth item=month}
				<option {if !empty($aForms) && $aForms.end_month==$month}selected{/if}>{$month}</option>
			{/foreach}
			</select>
			<select name='val[end_year]' id="end_year">
			<option value="">{phrase var='resume.year'}</option>
				{foreach from=$aYear item=year}
					<option {if !empty($aForms) && $aForms.end_year==$year}selected{/if}>{$year}</option>
				{/foreach}
			</select>
		</div>
	</div>	
	
	<div class="table">
		<div class="table_left table_left_add">
			<label for="note">{phrase var='resume.note'}:</label>
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
			<input type="button" class="button" value ="{phrase var='resume.skip'}" onclick="window.location.href='{url link='resume.language'}id_{$id}'"/>
		</div>			
	</div>	
</div>

</form>

{if count($aRows)>0}
<div>
	<span style="font-size: 20px;">{phrase var="resume.list_of_certifications"}</span>
	{foreach from=$aRows item=aRow}
	<div style="padding-top:10px;" id='certification_{$aRow.certification_id}'>
	<span style="font-weight:bold;font-size:13px;">{if $aRow.certification_name!=""}{$aRow.certification_name}{else}No name{/if}</span> 
	<span style="padding-left:20px;">
		<a href="{url link='resume.certification'}id_{$id}/exp_{$aRow.certification_id}/">{phrase var="resume.edit"}</a> 
		| 
		<a href="#" onclick="if(confirm( '{phrase var='resume.are_you_sure'}' ))$.ajaxCall('resume.delete_certification','exp_id={$aRow.certification_id}');return false;">{phrase var='resume.delete'}</a>
	</span>
	<!-- Course Name -->
	<p>
		{if $aRow.course_name}
			{$aRow.course_name}
		{/if}
	</p>
	<!-- Time Period and Training Place -->
	<p>
	{if $aRow.start_month and $aRow.start_year and $aRow.end_month and $aRow.end_year}	
		<?php echo date('F, Y',mktime(0,0,0,$this->_aVars["aRow"]["start_month"],1,$this->_aVars["aRow"]["start_year"]));?>
		-
		<?php echo date('F, Y',mktime(0,0,0,$this->_aVars["aRow"]["end_month"],1,$this->_aVars["aRow"]["end_year"])); ?> 
	{/if}
	
	{if $aRow.training_place}
		{phrase var="resume.at"} {$aRow.training_place}
	{/if}
	</p>
	 			
	</div>
	{/foreach}
</div>
{/if}
