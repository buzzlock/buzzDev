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


<div>
	{phrase var='resume.upgrade_to_premium_account_to_see_all_resume_as_well_as_use_who_s_viewed_me_service'}
	<span style="margin-top: 5px;display:block;">
		<input onclick="tb_remove();$.ajaxCall('resume.upgradeAccount','view=1');return false;" type="button" class="button" value="{phrase var='resume.upgrade_to_premium_account'}"/>
		<input onclick="tb_remove();return false;" value="{phrase var='resume.cancel'}" class="button" style="max-width: 50px;"/>
	</span>
</div>
