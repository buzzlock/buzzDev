<?php
/**
 * [PHPFOX_HEADER]
 */
defined('PHPFOX') or exit('NO DICE!');
?>
<div class="placeholder">
	<div style="padding-top:6px;" class="js_prev_block">
		<span class="class_answer">
			<input type="text" name="val[predefined][{$iKey}]" value="{$aPredefined}" size="30" class="js_predefined v_middle number greater_than_minimum" />
		</span>
		<a href="#" onclick="return appendPredefined(this);">
			{img theme='misc/add.png' class='v_middle'}
		</a>
		<a href="#" onclick="return removePredefined(this);">
			{img theme='misc/delete.png' class='v_middle'}
		</a>
	</div>
</div>
