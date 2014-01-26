<?php 
/**
 * [PHPFOX_HEADER]
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond Benc
 * @package  		Module_Photo
 * @version 		$Id: form.html.php 4418 2012-06-29 07:32:51Z Raymond_Benc $
 */
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
		{if isset($aForms.view_id) && $aForms.view_id == 1}
		<div class="message" style="width:85%;">
			{phrase var='advancedphoto.image_is_pending_approval'}
		</div>
		{/if}
		<div class="table">
			<div class="table_left">
				<label for="title">{phrase var='advancedphoto.title'}</label>:
			</div>
			<div class="table_right">
				<input type="text" name="val{if isset($aForms.photo_id)}[{$aForms.photo_id}]{/if}[title]" value="{if isset($aForms.title)}{$aForms.title|clean}{else}{value type='input' id='title'}{/if}" size="30" maxlength="150" onfocus="this.select();" />
			</div>			
		</div>
		<div class="table">
			<div class="table_left">
				{phrase var='advancedphoto.description'}:
			</div>
			<div class="table_right">
				<textarea cols="30" rows="4" name="val{if isset($aForms.photo_id)}[{$aForms.photo_id}]{/if}[description]">{if isset($aForms.description)}{$aForms.description|clean}{else}{value type='input' id='description'}{/if}</textarea>
			</div>			
		</div>		
		
		{if isset($aForms.group_id) && $aForms.group_id != '0'}
		
		{else}
		{if Phpfox::getService('advancedphoto.category')->hasCategories()}
		<div class="table">
			<div class="table_left">
				{phrase var='advancedphoto.category'}:
			</div>
			<div class="table_right js_category_list_holder">
				{if isset($aForms.photo_id)}<div class="js_photo_item_id" style="display:none;">{$aForms.photo_id}</div>{/if}				
				{if isset($aForms.category_list)}<div class="js_photo_active_items" style="display:none;">{$aForms.category_list}</div>{/if}
				{module name='advancedphoto.drop-down'}
			</div>			
		</div>	
		{/if}
		{/if}
	
		{if isset($aForms.group_id) && $aForms.group_id != '0'}
		
		{else}		
			{if Phpfox::isModule('tag') && Phpfox::getUserParam('advancedphoto.can_add_tags_on_photos')}{if isset($aForms.photo_id)}{module name='tag.add' sType='photo' separate=false id=$aForms.photo_id}{else}{module name='tag.add' sType='photo' separate=false}{/if}{/if}
		{/if}
		
{if isset($bSingleMode)}
	<div class="table">
		<div class="table_left">
			{phrase var='advancedphoto.date'}:
		</div>
		<div class="table_right">
			<div style="position: relative; width: 300px;">
				{select_date prefix='ynadvphoto_' id='_ynadvphoto' start_year='-200' end_year='+1' field_separator=' / ' field_order='MDY' default_all=true add_time=true start_hour='+1' time_separator='advancedphoto.time_separator'}				
			</div>
		</div>
	</div>	
{/if}


	<div class="table">
		<div class="table_left">
		<label for="location">{phrase var='advancedphoto.location'}:</label>
		</div>
		<div class="table_right">
			<input type="text" name="val{if isset($aForms.photo_id)}[{$aForms.photo_id}]{/if}[yn_location]" value="{if isset($aForms.yn_location)}{$aForms.yn_location|clean}{else}{value type='input' id='yn_location'}{/if}" size="40" maxlength="200" />
		</div>
	</div>

		
			{if Phpfox::getUserParam('advancedphoto.can_add_mature_images')}
			<div class="table">
				<div class="table_left">
					{phrase var='advancedphoto.mature_content'}:
				</div>
				<div class="table_right">
					<label><input type="radio" name="val{if isset($aForms.photo_id)}[{$aForms.photo_id}]{/if}[mature]" value="2" style="vertical-align:middle;" class="checkbox"{value type='radio' id='mature' default='2'}/> {phrase var='advancedphoto.yes_strict'}</label>
					<label><input type="radio" name="val{if isset($aForms.photo_id)}[{$aForms.photo_id}]{/if}[mature]" value="1" style="vertical-align:middle;" class="checkbox"{value type='radio' id='mature' default='1'}/> {phrase var='advancedphoto.yes_warning'}</label>
					<label><input type="radio" name="val{if isset($aForms.photo_id)}[{$aForms.photo_id}]{/if}[mature]" value="0" style="vertical-align:middle;" class="checkbox"{value type='radio' id='mature' default='0' selected=true}/> {phrase var='advancedphoto.no'}</label>
				</div>			
			</div>
			{/if}
			
			{if Phpfox::getParam('advancedphoto.can_rate_on_photos') && Phpfox::getUserParam('advancedphoto.can_add_to_rating_module')}
			<div class="table js_public_rating">
				<div class="table_left">
					{phrase var='advancedphoto.public_rating'}:
				</div>
				<div class="table_right">
					<label><input type="radio" name="val{if isset($aForms.photo_id)}[{$aForms.photo_id}]{/if}[allow_rate]" value="1" style="vertical-align:middle;" class="checkbox"{value type='radio' id='allow_rate' default='1' selected=true}/> {phrase var='advancedphoto.yes'}</label>
					<label><input type="radio" name="val{if isset($aForms.photo_id)}[{$aForms.photo_id}]{/if}[allow_rate]" value="0" style="vertical-align:middle;" class="checkbox"{value type='radio' id='allow_rate' default='0'}/> {phrase var='advancedphoto.no'}</label>				
				</div>
			</div>
			{/if}			
			
			<div class="table">
				<div class="table_left">
					{phrase var='advancedphoto.download_enabled'}:
				</div>
				<div class="table_right">
					<label><input type="radio" name="val{if isset($aForms.photo_id)}[{$aForms.photo_id}]{/if}[allow_download]" value="1" style="vertical-align:middle;" class="checkbox"{value type='radio' id='allow_download' default='1' selected=true}/> {phrase var='advancedphoto.yes'}</label>
					<label><input type="radio" name="val{if isset($aForms.photo_id)}[{$aForms.photo_id}]{/if}[allow_download]" value="0" style="vertical-align:middle;" class="checkbox"{value type='radio' id='allow_download' default='0'}/> {phrase var='advancedphoto.no'}</label>
					<div class="extra_info">
						{phrase var='advancedphoto.enabling_this_option_will_allow_others_the_rights_to_download_this_photo'}
					</div>				
				</div>
			</div>