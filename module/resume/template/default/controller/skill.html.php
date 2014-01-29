<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<div class="skill-session">
{template file='resume.block.menu_add'}
</div>

<div style="position: relative">
	<h3  class="yns add-res">
		<ul class="yns menu-add">
			<li>{phrase var='resume.add_skill_expertise'}</li>
		</ul>
		<ul class="yns action-add">
			{if $bIsEdit}<li><a class="page_section_menu_link" href="{url link='resume.view'}{$id}/">{phrase var='resume.view_my_resume'}</a></li>{/if}
		</ul>
	</h3>
</div>

<form method="post" enctype="multipart/form-data">

<div id="headline">
	<div class="table">
		<div class="table_left table_left_add">
			
		</div>
		<div class="table_right">
			<input type="text" name="val[kill_name]" value="" size="20" maxlength="200" id= 'element_name'/>
			<a id="add_more_element" href="#" onclick="javascript:void(0);return false;" style="font-size:12px;" title="{phrase var ='resume.add_skill_expertise'}">
				{img theme='misc/add.png' class='v_middle'}
			</a>
		</div>	
	</div>
	
	<div class="table" style="display:none">
		<div class="table_left table_left_add">
		</div>
		<div class="table_right">
			<textarea cols="70" rows="5" name='val[kill_list]' id='element_list'>
				{if $bIsEdit}{$aForms.kill_list}{/if}
			</textarea>
		</div>
	</div>	
	
	<div class="table textareaselect">
		<div class="table_left table_left_add">
		</div>
		<div class="table_right tablecontent" >
			{if isset($aForms.akill_list)}
			{foreach from=$aForms.akill_list item=kill}
				<ul class="chzn-choices">
					<li id="selEEW_chzn_c_1" class="search-choice">
						<span>{$kill}</span>
						<a rel="1" class="search-choice-close closeskill" href="javascript:void(0);" onclick="removeElement($(this));return false;" ></a>
					</li>
				</ul>
			{/foreach}
			{/if}
		</div>
	</div>	
	
	<div class="table">
		<div class="table_left table_left_add">
			
		</div>
		<div class="table_right ">
			<input type="submit" class="button" value = "{phrase var='resume.update'}"/>
			<input type="button" class="button" value ="{phrase var='resume.skip'}" onclick="window.location.href='{url link='resume.certification'}id_{$id}'"/>
		</div>			
	</div>	
</div>

</form>
