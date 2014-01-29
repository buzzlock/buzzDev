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
 
 <form method="post" action="{permalink module='resume.view' id=$aRes.resume_id title=$aRes.headline}">
 	<div><input type="hidden" name="note[resume_id]" value="{$aRes.resume_id}" /></div>
	<div class="table">
		<div class="table_left">
			{phrase var='resume.note'}:
		</div>
		<div class="table_right">
			<textarea cols="42" rows="6" name="note[text]" value="" id="note"/>
		</div>
		<div>
			({phrase var='resume.maximum_note_length'})
		</div>
	</div>
	<div class="table_clear">
		<input type="submit" name = "note[submit]" value="{phrase var='core.submit'}" class="button" />
	</div>
 </form>
 
