<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<div class="addition-session">
{template file='resume.block.menu_add'}
</div>

<div>
<h3 class="yns add-res">
	<ul class="yns menu-add">
		<li>{phrase var='resume.additional_information'}</li>
	</ul>
	<ul class="yns action-add">
		{if $bIsEdit}<li><a class="page_section_menu_link" href="{permalink module='resume.view' id=$id}">{phrase var='resume.view_my_resume'}</a></li>{/if}
	</ul>
	</h3>
</div>

<form method="post" enctype="multipart/form-data">

<div id="headline">

	<div class="table">
		<div class="table_left table_left_add">
			<label for="website">{phrase var='resume.websites'}:</label>
		</div>
		<div class="table_right">
			{if $bIsEdit && count($aForms.email)>0}
			{foreach from=$aForms.email item=aemail name=iemail}
			<div class="placeholder">
            	<div style="padding-top:6px;" class="js_prev_block">
                	<span class="class_answer">
                    	<input type="text" name="val[emailaddress][]" value="{$aemail}" size="30" class="js_predefined v_middle" />
                    </span>
                    <a href="#" onclick="return appendPredefined(this,'emailaddress');">
                    	{img theme='misc/add.png' class='v_middle'}
                    </a>
                    <a href="#" onclick="return removePredefined(this,'emailaddress');">
                    	{img theme='misc/delete.png' class='v_middle'}
                    </a>
                    </div>
               </div>
			
			{/foreach}
			{else}
			<div class="placeholder">
            	<div style="padding-top:6px;" class="js_prev_block">
                	<span class="class_answer">
                    	<input type="text" name="val[emailaddress][]" value="" size="30" class="js_predefined v_middle" />
                    </span>
                    <a href="#" onclick="return appendPredefined(this,'emailaddress');">
                    	{img theme='misc/add.png' class='v_middle'}
                    </a>
                    <a href="#" onclick="return removePredefined(this,'emailaddress');">
                    	{img theme='misc/delete.png' class='v_middle'}
                    </a>
                    </div>
               </div>

			{/if}	
			</div>		
		</div>			

	
	<div class="table">
		<div class="table_left table_left_add">
			<label for="sport">{phrase var='resume.sport'}:</label>
		</div>
		<div class="table_right">
			<textarea cols="70" rows="5" name='val[sport]'>{if $bIsEdit and isset($aForms.sport)}{$aForms.sport}{/if}</textarea>
		</div>
	</div>	
	
	<div class="table">
		<div class="table_left table_left_add">
			<label for="movies">{phrase var='resume.movies'}:</label>
		</div>
		<div class="table_right">
			<textarea cols="70" rows="5" name='val[movies]'>{if $bIsEdit and isset($aForms.movies)}{$aForms.movies}{/if}</textarea>
		</div>
	</div>	
	
	<div class="table">
		<div class="table_left table_left_add">
			<label for="interests">{phrase var='resume.interests'}:</label>
		</div>
		<div class="table_right">
			<textarea cols="70" rows="5" name='val[interests]'>{if $bIsEdit and isset($aForms.interests)}{$aForms.interests}{/if}</textarea>
		</div>
	</div>	
	
	<div class="table">
		<div class="table_left table_left_add">
			<label for="music">{phrase var='resume.music'}:</label>
		</div>
		<div class="table_right">
			<textarea cols="70" rows="5" name='val[music]'>{if $bIsEdit and isset($aForms.music)}{$aForms.music}{/if}</textarea>
		</div>
	</div>	
	
		
	<div class="table">
		<div class="table_left table_left_add">
			
		</div>
		<div class="table_right ">
			<input type="submit" class="button" value = "{phrase var='resume.update'}"/>
		</div>			
	</div>	
</div>

</form>
