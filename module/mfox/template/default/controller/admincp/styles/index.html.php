<?php
/**
 * [PHPFOX_HEADER]
 *
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		Raymond_Benc
 * @package 		Phpfox
 * @version 		$Id: add.html.php 4132 2012-04-25 13:38:46Z Raymond_Benc $
 */

defined('PHPFOX') or exit('NO DICE!');
?>
<form method="post" action="{url link='admincp.mfox.styles'}">
    {if $bIsEdit}
        <div><input type="hidden" name="id" value="{$iStyleId}" /></div>
    {/if}
    
	<div class="table_header">
		{phrase var='mfox.custom_css_details'}
	</div>
	{foreach from=$aStyles name=styles item=aStyle}
	<div class="table">
		<div class="table_left">
			{$aStyle.name}:
		</div>
		<div class="table_right">
			<div class="item_is_active_holder">
				<input type="type" name="val[{$aStyle.name}]" value="{$aStyle.value}" default='0'}/>
			</div>
		</div>
		<div class="clear"></div>
	</div>
	{/foreach}
	<div class="table_clear">
		<input type="submit" value="{phrase var='mfox.submit'}" class="button" />
	</div>
</form>