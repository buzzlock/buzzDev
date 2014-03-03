<?php 
 
defined('PHPFOX') or exit('NO DICE!'); 

/**
 * author		Teamwurkz Technologies Inc.
 * package		tti_components
 */
 
?>

<form method="post" action="{url link='admincp.ttislideshow.addslide'}" enctype="multipart/form-data">
{if $bIsEdit}
	<div><input type="hidden" name="id" value="{$aForms.slide_id}" /></div>
{/if}
	<div class="table_header">
		Slide Details
	</div>
	<div class="table">
		<div class="table_left">
			Ordering:
		</div>
		<div class="table_right">
			<input type="text" name="val[ordering]" id="ordering" value="{value id='ordering' type='input'}" size="5" /> (eg 0,1,2...)
		</div>
		<div class="clear"></div>
	</div>

	<div class="table">
		<div class="table_left">
			{required}Title:
		</div>
		<div class="table_right">
			<input type="text" name="val[title]" id="title" value="{value id='title' type='input'}" size="40" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="table">
		<div class="table_left">
			{required}Title Link:
		</div>
		<div class="table_right">
			<input type="text" name="val[title_link]" id="title_link" value="{value id='title_link' type='input'}" size="40"/>
		</div>
		<div class="clear"></div>
	</div>	

	<div class="table">
		<div class="table_left">
			{required}Description:
		</div>
		<div class="table_right">
			<textarea cols="60" rows="10" name="val[description]" id="prod_desc" style="width:95%;">{value id='description' type='textarea'}</textarea>
			<div class="extra_info">
			Description will be parsed
			</div>
		</div>
		<div class="clear"></div>
	</div>	

	<div class="table">
		<div class="table_left">
			Image:
		</div>
		<div class="table_right">
			{if $bIsEdit && !empty($aForms.image_path)} 
			<div id="js_slideshow_image_holder">
				{img server_id=$aForms.server_id title=$aForms.title path='ttislideshow.url_image' file=$aForms.image_path suffix='_120' max_width='200' max_height='200'}
				<div class="extra_info">
					<a href="#" onclick="if (confirm('Are you sure?')) {left_curly} $('#js_slideshow_image_holder').remove(); $('#js_slideshow_upload_image').show(); $.ajaxCall('slideshow.deleteImage', 'slide_id={$aForms.slide_id}'); {right_curly} return false;">Change image.</a>
				</div>
			</div>
			{/if}
			<div id="js_slideshow_upload_image"{if $bIsEdit && !empty($aForms.image_path)} style="display:none;"{/if}>
				<input type="file" name="image" size="20" />
				<div class="extra_info">
					You can upload gif, jpg and png
				</div>
			</div>
		</div>
		<div class="clear"></div>
	</div>		

	<div class="table">
		<div class="table_left">
			Active:
		</div>
		<div class="table_right">	
			<div class="item_is_active_holder">		
				<span class="js_item_active item_is_active"><input type="radio" name="val[is_active]" value="1" {value type='radio' id='is_active' default='1' selected='true'}/> {phrase var='subscribe.yes'}</span>
				<span class="js_item_active item_is_not_active"><input type="radio" name="val[is_active]" value="0" {value type='radio' id='is_active' default='0'}/> {phrase var='subscribe.no'}</span>
			</div>
		</div>
		<div class="clear"></div>		
	</div>		
		
	<div class="table_clear">
		<input type="submit" value="Submit" class="button" />
	</div>
</form>