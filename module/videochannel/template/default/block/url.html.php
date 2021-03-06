<?php 

defined('PHPFOX') or exit('NO DICE!'); 

?>
{if PHPFOX_IS_AJAX}
<div id="js_video_done" style="display:none;">
	<div class="valid_message">
		{phrase var='videochannel.video_successfully_added'}
	</div>
</div>
{/if}
<div id="js_video_error" class="error_message" style="display:none;"></div>
<form method="post" action="{$strPostLink}"{if PHPFOX_IS_AJAX} onsubmit="$(this).ajaxCall('videochannel.addShare'); return false;"{/if}>
	{if $sModule}
		<div><input type="hidden" name="val[callback_module]" value="{$sModule}" /></div>
	{/if}
	{if $iItem}
		<div><input type="hidden" name="val[callback_item_id]" value="{$iItem}" /></div>
	{/if}	
	{if !empty($sEditorId)}
		<div><input type="hidden" name="editor_id" value="{$sEditorId}" /></div>
	{/if}
	<div class="table">
		<div class="table">
			<div class="table_left">
				<label for="category">{required} {phrase var='videochannel.category'}:</label>
			</div>
			<div class="table_right">
				{$sCategories}
			</div>
		</div>
		<div class="table_left">
			{required} {phrase var='videochannel.video_url'}:
		</div>
		<div class="table_right">
			<input type="text" name="val[url]" value="{value type='input' id='url'}" size="40" style="width:90%;" />
			<div class="extra_info">
				{phrase var='videochannel.click_here_to_view_a_list_of_supported_sites'}
			</div>
		</div>
	</div>	

	<div id="js_custom_privacy_input_holder_video">
	{if isset($aForms.video_id)}
		{module name='privacy.build' privacy_item_id=$aForms.video_id privacy_module_id='videochannel'}	
	{/if}
	</div>	
	
	{if !$sModule}
	{if Phpfox::isModule('privacy')}
	<div class="table">
		<div class="table_left">
			{phrase var='videochannel.privacy'}:
		</div>
		<div class="table_right">	
			{module name='privacy.form' privacy_name='privacy' privacy_info='videochannel.control_who_can_see_this_video' privacy_custom_id='js_custom_privacy_input_holder_video' default_privacy='videochannel.display_on_profile'}
		</div>			
	</div>
	{/if}
		
	{if Phpfox::isModule('comment') && Phpfox::isModule('privacy')}
	<div class="table">
		<div class="table_left">
			{phrase var='videochannel.comment_privacy'}:
		</div>
		<div class="table_right">	
			{module name='privacy.form' privacy_name='privacy_comment' privacy_info='videochannel.control_who_can_comment_on_this_video' privacy_no_custom=true}
		</div>			
	</div>
	{/if}		
	{/if}
	
	<div class="table_clear">
		<input type="submit" value="{phrase var='videochannel.add'}" class="button" />
	</div>
</form>