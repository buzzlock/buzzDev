 <?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<div class="experience-session">
{template file='resume.block.menu_add'}
</div>
<div style="position: relative">
	<h3  class="yns add-res">
		<ul class="yns menu-add">
			<li>{phrase var='resume.experience'}</li>
			<li><a class="page_section_menu_link" href="{url link='resume.experience'}id_{$id}/">{phrase var='resume.add_a_position'}</a></li>
		</ul>

		<ul class="yns action-add">
			<li><a class="page_section_menu_link" href="{url link='resume.view'}{$id}/">{phrase var='resume.view_my_resume'}</a></li>
		</ul>
	</h3>
</div>

<form method="post" name="js_resume_add_form" enctype="multipart/form-data">

<div id="headline">
	<div class="table" style="padding-top: 10px;">
			<div class="table_left table_left_add">
			<label for="company_name">{required}{phrase var='resume.company_name'}:</label>
			</div>
			<div class="table_right">
				<input type="text" name="val[company_name]" value="{value type='input' id='company_name'}" id="company_name" size="40" maxlength="100" />
			</div>
	</div>	
	
	<div class="table">
		<div class="table_left table_left_add">
			<label for="level">{required}{phrase var='resume.level'}:</label>
		</div>
		<div class="table_right">
			<select name="val[level_id]">
					<option value="">{phrase var="resume.select"}</option>
				{foreach from=$aLevel item=level}
					<option value="{$level.level_id}" {if ($iExp!=0 || !$is_calloff) && isset($aForms.level_id) && $level.level_id==$aForms.level_id}selected{/if}>{$level.name}</option>
				{/foreach}
			</select>
		</div>
	</div>	
	
	<div class="table">
		<div class="table_left table_left_add">
			<label for="title">{required}{phrase var='resume.title'}:</label>
		</div>
		<div class="table_right">
			<input type="text" name="val[title]" value="{value type='input' id='title'}" id="title" size="40" maxlength="200" />
		</div>
	</div>	
	
	<div class="table">
		<div class="table_left table_left_add">
			<label for="location">{phrase var='resume.location'}:</label>
		</div>
		<div class="table_right">
			<input type="text" name="val[location]" value="{value type='input' id='location'}" id="location" size="40" maxlength="200" />
		</div>
	</div>	
	
	
	<!-- Time working period -->
	<div class="table">
		<div class="table_left table_left_add">
			<label for="postal_code">{required}{phrase var='resume.time_period'}:</label>
		</div>
		<div class="table_right">
			<!-- Working here -->
			<input type="checkbox" {if ($iExp!=0 || !$is_calloff) && (isset($aForms.is_working_here) && $aForms.is_working_here==1)}checked=true{/if} id='check_experience' name='val[is_working_here]'/> {phrase var='resume.i_currently_work_here'}
			<!-- Working Period-->
			<div>
				<!-- Start Month -->
				<select name='val[start_month]'>
				<option value="-1">{phrase var='resume.uppercase_month'} ...</option>
				{foreach from=$aMonth item=month}
					<option {if ($iExp!=0 || !$is_calloff) && (isset($aForms.start_month) && $aForms.start_month==$month)}selected{/if}>{$month}</option>
				{/foreach}
				</select>
				<!-- Start Year -->
				<select name='val[start_year]' id="start_year">
					<option value="-1">{phrase var='resume.year'} ...</option>
						{foreach from=$aYear item=year}
					<option {if ($iExp!=0 || !$is_calloff) && (isset($aForms.start_year) && $aForms.start_year==$year)}selected{/if}>{$year}</option>
				{/foreach}
				</select>
				<!-- End Period -->
				<span class='end_experience' {if ($iExp!=0 || !$is_calloff) && (isset($aForms.is_working_here) && $aForms.is_working_here==1)}style="display:none"{/if}>
					{phrase var='resume.to'} 
					<!-- End Month -->
					<select name='val[end_month]'>
						<option value="-1">{phrase var='resume.uppercase_month'} ...</option>
						{foreach from=$aMonth item=month}
							<option {if ($iExp!=0 || !$is_calloff) && (isset($aForms.end_month) && $aForms.end_month==$month)}selected{/if}>{$month}</option>
						{/foreach}
					</select>
					<!-- End Year -->
					<select name='val[end_year]' id="end_year">
						<option value="-1">{phrase var='resume.year'} ...</option>
							{foreach from=$aYear item=year}
						<option {if ($iExp!=0 || !$is_calloff) && (isset($aForms.end_year) && $aForms.end_year==$year)}selected{/if}>{$year}</option>
						{/foreach}
					</select>
				</span>
			</div>
		</div>
	</div>
	
	<div class="table">
		<div class="table_left table_left_add">
			<label for="description">{phrase var='resume.description'}:</label>
		</div>
		<div class="table_right">
			<textarea cols="70" rows="5" name='val[description]'>{if ($iExp!=0 || !$is_calloff) && isset($aForms.description)}{$aForms.description}{/if}</textarea>
		</div>
	</div>	
	
		
	<div class="table">
		<div class="table_left table_left_add">
			
		</div>
		<div class="table_right ">
			<input type="submit" class="button" value = "{phrase var='resume.update'}"/>
			<input type="button" class="button" value ="{phrase var='resume.skip'}" onclick="window.location.href='{url link='resume.education'}id_{$id}'"/>
		</div>			
	</div>	
</div>

</form>

<!-- Experience Listig Space -->
{if count($aRows)>0}
<div>
	<span style="font-size: 20px;">{phrase var='resume.list_of_positions'}</span>
	{foreach from=$aRows item=aRow}
	<div style="padding-top:10px;" id='experience_{$aRow.experience_id}'>
		<!-- Company Name -->
		<span style="font-weight:bold;font-size:13px;">{if $aRow.company_name!=""}{$aRow.company_name}{else}No name{/if} {if $aRow.title!=""}- {$aRow.title}{/if}</span>
		<!-- Options --> 
		<span style="padding-left:20px;">
			<a href="{url link='resume.experience'}id_{$id}/exp_{$aRow.experience_id}/">{phrase var='resume.edit'}</a> 
			| 
			<a href="#" onclick="if(confirm( '{phrase var='resume.are_you_sure'}'))$.ajaxCall('resume.delete_experience','exp_id={$aRow.experience_id}');return false;">{phrase var='resume.delete'}</a>
		</span>
		<!-- Time period -->
		<p>
		   <!-- Start Time -->
		   <?php echo date('F, Y',mktime(0,0,0,$this->_aVars["aRow"]["start_month"],1,$this->_aVars["aRow"]["start_year"])); ?>
		    - 
		   <!-- End Time --> 
		   {if $aRow.is_working_here || !$aRow.end_month || !$aRow.end_year}
		   		{phrase var="resume.present"}
		   {else}
	   	 		<?php echo date('F, Y',mktime(0,0,0,$this->_aVars["aRow"]["end_month"],1,$this->_aVars["aRow"]["end_year"])); ?>
	   	   {/if}
		</p>
	</div>
	{/foreach}
</div>
{/if}
