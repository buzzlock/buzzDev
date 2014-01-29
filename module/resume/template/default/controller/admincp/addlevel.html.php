<?php 
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 *
 * @copyright      YouNet Company
 * @author         VuDP, TienNPL
 * @package        Module_Resume
 * @version        3.01
 * 
 */
?>

<!-- Add Level Form Layout -->
<form method="post" action="{url link='admincp.resume.addlevel'}" id="resume_add_level_form" enctype="multipart/form-data">
	<div class="table_header">
		{phrase var='resume.level_details'}
	</div>
    <div class="table">
		<div class="table_left">
			{required}{phrase var='resume.level_title'}:
		</div>
		<div class="table_right">
			<input type="text" name="val[title]" value="" id="title" size="40" maxlength="150" />
		</div>
		<div class="clear"></div>
	</div>
	<div class="table_clear">
		<input type="submit" value="{phrase var='resume.add_level'}" class="button" />
	</div>
</form>