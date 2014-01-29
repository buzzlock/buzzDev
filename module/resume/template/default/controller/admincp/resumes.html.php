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
 */
?>

<!-- Resume search form layout -->
<form style="margin-bottom:10px;" method="post" action="{url link='admincp.resume.resumes'}">
	<div class="table_header">{phrase var='resume.admin_menu_manage_resumes'}</div>
	<!-- Resume headline element -->
	<div class="table">
		<div class="table_left">
			{phrase var='resume.headline'}:
		</div>
		<div class="table_right">
			<input type="text" name="search[headline]" value="{value type='input' id='headline'}" id="headline" size="50" />
		</div>
	</div>
	<!-- Resume full name element -->
	<div class="table">
		<div class="table_left">
			{phrase var='resume.owner'}:
		</div>
		<div class="table_right">
			<input type="text" name="search[full_name]" value="{value type='input' id='full_name'}" id="full_name" size="50" />
		</div>
	</div>
	<!-- Resume status element -->
	<div class="table">
		<div class="table_left">
			{phrase var='resume.status'}:
		</div>
		<div class="table_right">
			<select name="search[status]">
				<option value="all" {value type='select' id='status' default = all}>{phrase var='resume.all'}</option>
				<option value="incomplete" {value type='select' id='status' default = incomplete}>{phrase var='resume.incomplete'}</option>
				<option value="completed" {value type='select' id='status' default = completed}>{phrase var='resume.completed'}</option>
				<option value="approving" {value type='select' id='status' default = approving}>{phrase var='resume.approving'}</option>
				<option value="approved" {value type='select' id='status' default = approved}>{phrase var='resume.published'}</option>
				<option value="denied" {value type='select' id='status' default = denied}>{phrase var='resume.denied'}</option>
				<option value="private" {value type='select' id='status' default = private}>{phrase var='resume.private'}</option>
			</select>
		</div>
	</div>
	<!-- Submit button -->
	<div class="table_clear">
		<input type="submit" id="filter_submit" name="search[submit]" value="{phrase var='resume.search'}" class="button" />
		<input type="submit" id="filter_submit" name="search[reset]" value="{phrase var='resume.reset'}" class="button" />
	</div>
</form>
<!-- Resume Management Space -->
{if count($aResumes) > 0}
<form action="{url link='current'}" method="post" id="resume_list" >
	<table align='center'>
		<!-- Table rows header -->
		<tr>
			<th><input type="checkbox" onclick="checkAllResume();" id="resume_list_check_all" name="resume_list_check_all"/></th>
			<th class="table_row_header"></th>
			<th>{phrase var='resume.headline'}</th>
			<th>{phrase var='resume.owner'}</th>
			<th class="table_row_header">{phrase var='resume.complete'}</th>
			<th class="table_row_header">{phrase var='resume.status'}</th>
			<th class="table_row_header">{phrase var='resume.backend_favorites'}</th>
			<th class="table_row_header">{phrase var='resume.backend_views'}</th>
			<th class="table_row_header">{phrase var='resume.created'}</th>
		</tr>
		<!-- Resume Rows -->
		{ foreach from=$aResumes key=iKey item=aResume }
		<tr id="resume_{$aResume.resume_id}" class="resume_row {if $iKey%2 == 0 } resume_row_even_background{else} resume_row_odd_background{/if}">
				<!-- Check Box -->
				<td style="width:20px">				
					<input type = "checkbox" class="resume_row_checkbox" id="resume_{$aResume.resume_id}" name="resume_row[]" value="{$aResume.resume_id}" onclick="checkDisableStatus();"/>
				</td>
				<!-- Options -->
				<td class="t_center">
					<a href="#" class="js_drop_down_link" title="Options">{img theme='misc/bullet_arrow_down.png' alt=''}</a>
					<div class="link_menu">
						<ul>
							<li><a href="{url link='resume.add' id=$aResume.resume_id}">{phrase var='admincp.edit'}</a></li>		
							<li><a href="javascript:void(0);" onclick="return deleteResume('{$aResume.resume_id}');">{phrase var='admincp.delete'}</a></li>					
						</ul>
					</div>		
				</td>
				<!-- Resume headline -->
				<td>
					<a href="{permalink module='resume.view' id = $aResume.resume_id title = $aResume.headline}">
						{$aResume.headline|shorten:35:'...'}
					</a>
				</td>
				<!-- Resume owner -->
				<td>
					{$aResume|user}
				</td> 
				<!-- Resume Complete -->
				<td class="table_row_column">
					{if $aResume.is_completed }
						{phrase var='resume.completed'}
					{else}
						{phrase var='resume.incomplete'}	
					{/if}	
				</td> 
				<!-- Status -->
				<td class="table_row_column">
					{if $aResume.status == 'approving'}
						<div id="approve_select_resume_{$aResume.resume_id}">
							<a  href="javascript:void(0);" onclick="return approveResume('{$aResume.resume_id}');">{phrase var='resume.approve'}</a>
							|
							<a  href="javascript:void(0);" onclick="return denyResume('{$aResume.resume_id}');">{phrase var='resume.deny'}</a>
						</div>
						<div id ="approved_resume_{$aResume.resume_id}" style="display:none">
							{phrase var ="resume.published"}
						</div>
						<div id = "denied_resume_{$aResume.resume_id}" style="display:none">
							{phrase var ="resume.denied"}
						</div>
                        <div id = "private_resume_{$aResume.resume_id}" style="display:none">
							{phrase var="resume.private"}
						</div>
					{elseif $aResume.status == 'approved'}
						{if $aResume.is_published }
							{phrase var="resume.published"}
						{else}
							{phrase var="resume.private"}
						{/if}
					{elseif $aResume.status == 'none'}
						--
					{else}
						{phrase var="resume.".$aResume.status}
					{/if}
				</td>
				<!-- Views -->
				<td class="table_row_column">
					{$aResume.total_favorite}
				</td> 
				<!-- Views -->
				<td class="table_row_column">
					{$aResume.total_view}
				</td> 
				<!-- Created -->
				<td class="table_row_column">
					<?php echo date('d F, Y',$this->_aVars["aResume"]["time_stamp"]); ?>
				</td>
			</tr>
			{/foreach}
	</table>
	{pager}
	<!-- Delete selected button -->
	<div class="table_bottom">
        <input type="submit" name="delete_selected" id="delete_selected" disabled value="{phrase var='resume.delete_selected'}" class="sJsConfirm delete_selected button disabled" />
        <input type='hidden' name='task' value='do_delete_selected' />
    </div>
</form>
{else}
<div class="extra_info">{phrase var='resume.no_resumes_found'}</div>
{/if}
