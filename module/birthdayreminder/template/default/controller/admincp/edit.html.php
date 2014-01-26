<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

?>
{literal}
<style type="text/css">
.table_left{
    width: 135px;
}
.table_right{
    margin-left: 140px;
}    
</style>
{/literal}
<form method="post" action="{url link='admincp.birthdayreminder.edit'}">
	<div class="table_header">
		{phrase var='birthdayreminder.admin_email'}
	</div>
	<div class="table">
        <div class="clear" style="margin-bottom: 5px;"></div>   
		<div class="table_left">
			{phrase var='birthdayreminder.admin_subject'}
		</div>
		<div class="table_right">
			<input type="text" name="val[subject]" value="{$aEmail.subject}" id="subject" size="50" maxlength="150"  />
		</div>
        <div class="clear" style="margin-bottom: 5px;"></div>   
		<div class="table_left" id="lbl_html_text">
			
		</div>
		
		
		<div class="table">
			<div class="table_left">
				<label for="text">{phrase var='birthdayreminder.admin_text'}</label>
			</div>
			<div class="table_right">
				{editor id='text'}
			</div>			
		</div>				
	
	<div class="table_clear">
        <input type="submit" value="Save" class="button" id="save" name="save" />
	</div>
	<div class="table_clear"></div>
</form> 

