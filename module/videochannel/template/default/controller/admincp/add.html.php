<?php 
 
defined('PHPFOX') or exit('NO DICE!'); 

?>
<form method="post" action="{url link='admincp.videochannel.add'}">
{if $bIsEdit}
	<div><input type="hidden" name="id" value="{$aForms.category_id}" /></div>
{/if}
	<div class="table_header">
		{phrase var='videochannel.video_category_details'}
	</div>
	<div class="table">
		<div class="table_left">
			{phrase var='videochannel.name'}:
		</div>
		<div class="table_right">
			<input type="text" name="val[name]" size="30" maxlength="100" value="{value type='input' id='name'}" />
		</div>
		<div class="clear"></div>
	</div>	
	<div class="table">
		<div class="table_left">
			{phrase var='videochannel.parent_category'}:
		</div>
		<div class="table_right">
			<select name="val[parent_id]" style="width:300px;">
				<option value="">{phrase var='videochannel.select_form_select'}:</option>
				{$sOptions}
			</select>
		</div>
		<div class="clear"></div>
	</div>
	<div class="table_clear">
		<input type="submit" value="{phrase var='videochannel.submit'}" class="button" />
	</div>
</form>