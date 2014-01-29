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

<!-- Level Management Space -->
<div class="table_header">
	{phrase var='resume.levels'}
</div>
{if count($aLevelList) > 0 }
<form action="{url link='admincp.resume.levels'}" method="post" id="admincp_resume_level_list" >
	<table id="js_drag_drop" align='center'>
		<tr>
            <th style="width:10px;">{phrase var='contact.order'}</th>
            
			<th><input type="checkbox" onclick="checkAllLevel();" id="resume_level_list_check_all" name="resume_level_list_check_all"/></th>
			<th>{phrase var='resume.level_title'}</th>
			<th style="text-align: center" width="10%">{phrase var='resume.used'}</th>
			<th style="text-align: center" width="20%">{phrase var='resume.options'}</th>
		</tr>
		{foreach from=$aLevelList key=iKey item=oLevel}
		<tr id="resume_level_{$oLevel.level_id}" class="checkRow resume_level_row {if $iKey%2 == 0 } resume_row_even_background {else} resume_row_odd_background {/if}">
            <td class="drag_handle"><input type="hidden" name="val[ordering][{$oLevel.level_id}]" value="{$oLevel.ordering}" /></td>
            
			<td style="width:20px">
				<input type = "checkbox" class="resume_level_row_checkbox" id="level_{$oLevel.level_id}" name="level_row[]" value="{$oLevel.level_id}" onclick="checkDisableStatus();"/>
			</td>
			<td id ='js_resume_level_edit_title{$oLevel.level_id}'>
				<a href="#?type=input&amp;id=js_resume_level_edit_title{$oLevel.level_id}&amp;content=js_resume_level{$oLevel.level_id}&amp;call=resume.updateLevelTitle&amp;level_id={$oLevel.level_id}" class="quickEdit" id="js_resume_level{$oLevel.level_id}">
					{$oLevel.name|convert|clean}</a>
			</td>
			<td style="text-align: center">
				{$oLevel.used}
			</td>
			<td style="width:200px;text-align: center">
				{if $oLevel.used == 0}
				 <a  href="javascript:void(0);" onclick="return deleteResumeLevel('{$oLevel.level_id}');">{phrase var='resume.delete'}</a>
				 {else}
				 	<font style="color:gray">{phrase var='resume.delete'} </font>
				 {/if}
			</td>
		</tr>
		{/foreach}
	</table>
	<div class="table_bottom">
        <input type="submit" name="delete_selected" id="delete_selected" disabled value="{phrase var='resume.delete_selected'}" class="sJsConfirm delete_selected button disabled" />
        <input type='hidden' name='task' value='do_delete_selected' />
    </div>
</form>
{pager}
{else}
	<div class="extra_info">{phrase var='resume.no_levels_had_been_added'}</div>
{/if}