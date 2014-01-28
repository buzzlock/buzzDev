<?php 

defined('PHPFOX') or exit('NO DICE!'); 

?>

<div class="tip">{phrase var='mobiletemplate.warning_for_style'}</div>
<form method="post" action="{url link='admincp.mobiletemplate.addstyle'}">
    {if isset($bIsEdit) && $bIsEdit}
        <div><input type="hidden" name="id" value="{$iStyleId}" /></div>
    {/if}
    
	<div class="table_header">
		{phrase var='mobiletemplate.main_information'}: 
	</div>
	<div class="table">
		<div class="table_left">
			{phrase var='mobiletemplate.name'}:
		</div>
		<div class="table_right">
			<div class="item_is_active_holder">
				<input type="type" name="val[name]" value="{if isset($aForms.name)}{$aForms.name}{else}{/if}" maxlength="200" size="60" />
			</div>
		</div>
		<div class="clear"></div>
	</div>
	
	<div class="table_header">
		{phrase var='mobiletemplate.custom_css_details'}: 
	</div>
	{foreach from=$aStyles name=styles item=aStyle}
	<div class="table">
		<div class="table_left">
			{$aStyle.name}:
		</div>
		<div class="table_right">
			<div class="item_is_active_holder">
				{if $aStyle.type == 'color'}
				<input type="text" name="val[{$aStyle.key}]" value="{$aStyle.value}" class="color_input" />
				{else}
				<input type="text" name="val[{$aStyle.key}]" value="{$aStyle.value}" />
				{/if}
			</div>
		</div>
		<div class="clear"></div>
	</div>
	{/foreach}
	
	<div class="table_clear">
		<input type="submit" value="{phrase var='mobiletemplate.save'}" class="button" />
	</div>
</form>
<script type="text/javascript">
$Behavior.ynmtInitColorPicker = function() {l}
    $('.color_input').minicolors();
{r};
</script>
