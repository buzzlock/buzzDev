<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 *
 * @copyright      YouNet Company
 * @author         VuDP, TienNPL, TrucPTM
 * @package        Module_Resume
 * @version        3.01
 * 
 */?>
 
 <form method="post" onsubmit="$(this).ajaxCall('resume.updateNote');return false;">
 	<div><input type="hidden" name="resume_id" value="{$aView.resume_id}" /></div>
	<div class="table">
		<div class="table_left">
			{phrase var='resume.note'}:
		</div>
		<div class="table_right">
			<textarea cols="55" rows="7" name="text" id="note">{$aView.note}</textarea>
		</div>
		<div>
			({phrase var='resume.maximum_note_length'})
		</div>
	</div>
	<div class="table_clear">
		<input type="submit" name = "note[submit]" value="{phrase var='core.submit'}" class="button" />
	</div>
 </form>
 
