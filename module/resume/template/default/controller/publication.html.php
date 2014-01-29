<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<div class="public-session">
{template file='resume.block.menu_add'}
</div>

<div style="position: relative">
<h3  class="yns add-res">
	<ul class="yns menu-add">
		<li>{phrase var='resume.publications'}</li>
		<li><a class="page_section_menu_link" href="{url link='resume.publication'}id_{$id}/">{phrase var='resume.add_a_publication'}</a></li>
	</ul>
	<ul class="yns action-add">
		{if $bIsEdit}<li><a class="page_section_menu_link" href="{url link='resume.view'}{$id}/">{phrase var='resume.view_my_resume'}</a></li>{/if}
	</ul>
</h3>
</div>

<form method="post" name="js_resume_add_form" enctype="multipart/form-data">
<div id="headline">
	<!-- Publication type -->
	<div class="table" style="padding-top: 10px;">
		<div class="table_left table_left_add">
			<label for="magazine">{required}{phrase var='resume.publication_type'}:</label>
		</div>
		<div class="table_right">
			<select name="val[type_id]" id ="publication_type">
				<option value="1" {if isset($aForms.type_id) and $aForms.type_id == 1}selected{elseif !isset($aForms.type_id)}selected{/if}>{phrase var='resume.book'}</option>
				<option value="2" {if isset($aForms.type_id) and $aForms.type_id == 2}selected{/if}>{phrase var='resume.magazine'}</option>
				<option value="0" {if isset($aForms.type_id) and $aForms.type_id == 0}selected{/if}>{phrase var='resume.other'}</option>
			</select>
			<input type="text" name="val[other_type]" value="{value type='input' id='other_type'}" id="other_type" size="20" maxlength="255" {if isset($aForms.type_id) and $aForms.type_id == 0}style="display:inline;"{else}style="display:none;"{/if}/>
		</div>
	</div>
	<!-- Publication Title -->
	<div class="table">
		<div class="table_left table_left_add">
			<label for="title">{required}{phrase var='resume.title'}:</label>
		</div>
		<div class="table_right">
			<input type="text" name="val[title]" value="{value type='input' id='title'}" id="title" size="60" maxlength="255" />
		</div>
	</div>	
	<!-- Publication Publisher -->
	<div class="table">
		<div class="table_left table_left_add">
			<label for="publisher">{phrase var='resume.publisher'}:</label>
		</div>
		<div class="table_right">
			<input type="text" name="val[publisher]" value="{value type='input' id='publisher'}" id="publisher" size="60" maxlength="255" />
		</div>
	</div>	
	<!-- Publication URL -->
	<div class="table">
		<div class="table_left table_left_add">
			<label for="publication_url">{phrase var='resume.publication_url'}:</label>
		</div>
		<div class="table_right">
			<input type="text" name="val[publication_url]" value="{value type='input' id='publication_url'}" id="publication_url" size="60" maxlength="255" />
		</div>
	</div>
	<!-- Publication Time -->
	<div class="table">
		<div class="table_left table_left_add">
			<label for="published_year">{phrase var='resume.year_of_publication'}:</label>
		</div>
		<div class="table_right">
			<!-- Day -->
			<select name='val[published_day]'>
				<option value="">{phrase var='resume.uppercase_day'} ...</option>
				{foreach from=$aDay item=day}
					<option {if !empty($aForms) && $aForms.published_day==$day}selected{/if}>{$day}</option>
				{/foreach}
			</select>
			<!-- Month -->
			<select name='val[published_month]'>
				<option value="">{phrase var='resume.uppercase_month'} ...</option>
				{foreach from=$aMonth item=month}
					<option {if !empty($aForms) && $aForms.published_month==$month}selected{/if}>{$month}</option>
				{/foreach}
			</select>
			<!-- Year -->
			<select name='val[published_year]' id="published_year">
				<option value="">{phrase var='resume.year'} ...</option>
				{foreach from=$aYear item=year}
					<option {if !empty($aForms) && $aForms.published_year==$year}selected{/if}>{$year}</option>
				{/foreach}
			</select>
		</div>
	</div>	
	<!-- Publication Author -->
	<div class="table">
		<div class="table_left table_left_add">
			{phrase var="resume.author"}:
		</div>
		<div class="table_right">
			<input type="text" name="val[author]" value="" size="20" maxlength="200" id='element_name'/>
			<a id="add_more_element" href="#" onclick="javascript:void(0);return false;" style="font-size:12px;" title="{phrase var ='resume.add_author'}">
				{img theme='misc/add.png' class='v_middle'}
			</a>
		</div>	
	</div>
	
	<div class="table" style="display:none">
		<div class="table_left table_left_add">
		</div>
		<div class="table_right">
			<textarea cols="70" rows="5" name='val[author_list]' id='element_list'>
				{if isset($aForms.author_list)}{$aForms.author_list}{/if}
			</textarea>
		</div>
	</div>	
	
	<div class="table textareaselect">
		<div class="table_left table_left_add">
		</div>
		<div class="table_right tablecontent" >
			{if isset($aForms.array_author_list)}
			{foreach from=$aForms.array_author_list item=sAuthor}
				<ul class="chzn-choices">
					<li id="selEEW_chzn_c_1" class="search-choice">
						<span>{$sAuthor}</span>
						<a rel="1" class="search-choice-close closeskill" href="javascript:void(0)" onclick="removeElement($(this));return false;" ></a>
					</li>
				</ul>
			{/foreach}
			{/if}
		</div>
	</div>
	<!-- Publication Note -->
	<div class="table" style="padding-top: 10px;">
		<div class="table_left table_left_add">
			<label for="note">{phrase var='resume.summary'}:</label>
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
			<input type="button" class="button" value ="{phrase var='resume.skip'}" onclick="window.location.href='{url link='resume.addition'}id_{$id}'"/>
		</div>			
	</div>	
</div>

</form>

{if count($aRows)>0}
<div>
	<span style="font-size: 20px;">{phrase var="resume.list_of_publication"}</span>
	{foreach from=$aRows item=aRow}
	<div style="padding-top:10px;font-size:13px;" id='publication_{$aRow.publication_id}'>
		<div class="publication_info">
			<!-- Publication Type -->
			<span>
				<strong>
					{if $aRow.type_id == 1}
						{phrase var="resume.book"}
					{elseif $aRow.type_id == 2}
						{phrase var="resume.magazine"}
					{else}
						{$aRow.other_type}
					{/if}
				</strong>
			</span>
			{if $aRow.publisher}
				 - 
				 <span style="font-weight:bold;font-size:13px;">
				 	<strong>{$aRow.publisher}</strong>
				 </span>
			{/if}
			<!-- Published Time -->
			{if $aRow.published_month and $aRow.published_year}
				 , <?php echo date('d F Y',mktime(0,0,0,$this->_aVars["aRow"]["published_month"],$this->_aVars["aRow"]["published_day"],$this->_aVars["aRow"]["published_year"])); ?>
			{/if} 
			<!-- Publication Manage Action  -->
			<span style="padding-left:20px;">
				<a href="{url link='resume.publication'}id_{$id}/exp_{$aRow.publication_id}/">{phrase var="resume.edit"}</a> 
				| 
				<a href="#" onclick="if(confirm( '{phrase var='resume.are_you_sure'}' ))$.ajaxCall('resume.delete_publication','exp_id={$aRow.publication_id}');return false;">{phrase var="resume.delete"}</a>
			</span>
		</div>
		<!-- Publication Title and Url -->
		
		<!-- Publication Authors -->
		{if isset($aRow.author_list)}
			<div class="publication_info">
				<i>{phrase var="resume.author"}:</i>
				{$aRow.author_list}
			</div>
		{/if}
		<!-- Publication Summary -->
		{if $aRow.note_parsed}
			<div class ="publication_summary">
				<i>{phrase var="resume.summary"}:</i>
				<div style="margin-left:10px;">
					{$aRow.note_parsed}
				</div>
			</div>
		{/if}
	</div>
	{/foreach}
</div>
{/if}
