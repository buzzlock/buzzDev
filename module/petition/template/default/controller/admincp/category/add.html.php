<?php 

defined('PHPFOX') or exit('NO DICE!'); 

?>
{$sCreateJs}
<form method="post" action="{url link="admincp.petition.category.add"}" id="js_form" onsubmit="{$sGetJsForm}">
	<div class="table_header">
	{phrase var='petition.category_details'}
	</div>
	<div class="table">
		<div class="table_left">
			{phrase var='petition.category'}:
		</div>
		<div class="table_right">
			<input type="text" name="val[name]" value="{value type='input' id='name'}" id="name" size="30" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="table_clear">
		<input type="submit" value="{phrase var='petition.submit'}" class="button" />
	</div>
</form>