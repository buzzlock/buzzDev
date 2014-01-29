<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<div class="lang-session">
{template file='resume.block.menu_add'}
</div>

<div style="position: relative">
<h3  class="yns add-res">
	<ul class="yns menu-add">
		<li>{phrase var='resume.languages'}</li>
		<li><a class="page_section_menu_link" href="{url link='resume.language'}id_{$id}/">{phrase var='resume.add_a_language'}</a></li>
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
			<label for="name">{required}{phrase var='resume.name'}:</label>
			</div>
			<div class="table_right">
				<input type="text" name="val[name]" value="{value type='input' id='name'}" id="name" size="40" maxlength="100" />
			</div>
	</div>	
	
	<div class="table" style="padding-top: 10px;">
			<div class="table_left table_left_add">
			<label for="level">{phrase var='resume.level'}:</label>
			</div>
			<div class="table_right">
				<input type="text" name="val[level]" value="{value type='input' id='level'}" id="level" size="40" maxlength="100" />
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
		<div class="table_right">
			<input type="submit" class="button" value = "{phrase var='resume.update'}"/>
			<input type="button" class="button" value ="{phrase var='resume.skip'}" onclick="window.location.href='{url link='resume.publication'}id_{$id}'"/>
		</div>			
	</div>	
</div>

</form>

{if count($aRows)>0}
<div>
	<span style="font-size: 20px;">{phrase var='resume.list_of_languages'}</span>
	{foreach from=$aRows item=aRow}
	<div style="padding-top:10px;" id='language_{$aRow.language_id}'>
	<span style="font-weight:bold;font-size:13px;">{if $aRow.name!=""}{$aRow.name}{else}{phrase var="resume.no_name"}{/if}</span> 
	{if $aRow.level}({$aRow.level}){/if}
	<span style="padding-left:20px;">
		<a href="{url link='resume.language'}id_{$id}/exp_{$aRow.language_id}/">{phrase var="resume.edit"}</a> 
		| 
		<a href="#" onclick="if(confirm( '{phrase var='resume.are_you_sure'}' ))$.ajaxCall('resume.delete_language','exp_id={$aRow.language_id}');return false;">{phrase var='resume.delete'}</a></span>
	</div>
	{/foreach}
</div>
{/if}
