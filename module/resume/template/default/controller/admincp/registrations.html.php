

{*if $iWhoViewedMMeGroupId && $iViewAllResumeGroupId*}

{literal}
<script type="text/javascript">
	function deleteAccount(account_id)
	{
		$.ajaxCall('resume.deleteAccount','account_id='+account_id);
	}
	function setApproveView(id, status) {
		$.ajaxCall('resume.setApproveView', 'id=' + id + '&status=' + status);
	}
	function setApproveWhoView(id, status) {
		$.ajaxCall('resume.setApproveWhoView', 'id=' + id + '&status=' + status);
	}
	function checkAllResume()
	{
		var checked = document.getElementById('resume_list_check_all').checked;
		$('.resume_view_checkbox').each(function(index,element){
			element.checked=checked;
			var sIdName = '#resume_view_' + element.value;
			
			if (element.checked == true) {
				$(sIdName).css({
					'backgroundColor' : '#FFFF88'
				});
			}
			else {
				if(element.value % 2 == 0){
					$(sIdName).css({
						'backgroundColor' : '#F0f0f0'
					});
				}
				else{
					$(sIdName).css({
						'backgroundColor' : '#F9F9F9'
					});
				}
			}
		});
		setDeleteSelectedButtonStatus(checked);
		return checked;
	}
	
	function setDeleteSelectedButtonStatus(status) {
	if (status) {
		$('.delete_selected').removeClass('disabled');
		$('.delete_selected').attr('disabled', false);
	}
	else {
		$('.delete_selected').addClass('disabled');
		$('.delete_selected').attr('disabled', true);
	}
}

	function checkDisableStatus()
	{
		var status = false;
		$('.resume_view_checkbox').each(function(index,element){
		var sIdName = '#resume_view_' + element.value;
		
		if (element.checked == true) {
			status = true;
			$(sIdName).css({
				'backgroundColor' : '#FFFF88'
			});
		}
		else {
			if(element.value % 2 == 0){
				$(sIdName).css({
					'backgroundColor' : '#F0f0f0'
				});
			}
			else{
				$(sIdName).css({
					'backgroundColor' : '#F9F9F9'
				});
			}
		}
		
	});
		setDeleteSelectedButtonStatus(status);
		return status;
	}
</script>
<style type="text/css">
	th{
		text-align: center !important;
	}
</style>
{/literal}
<div class="table_header">{phrase var='resume.admin_menu_manage_view_service_registration'}</div>
<!-- Search -->
<form method="post" action="{url link='admincp.resume.registrations'}">
	<div class="table">
		<div class="table_left">
			{phrase var='resume.search_from'}:
		</div>
		<div class="table_right">
			<input name="search[fromdate]" id="js_from_date_filter" type="text" value="{if isset($sFromDate) && $sFromDate}{$sFromDate}{/if}" />
			<a href="#" id="js_from_date_filter_anchor">
				<img src="<?php echo Phpfox::getLib('template')->getStyle('image', 'jquery/calendar.gif'); ?>" />
			</a>
		</div>
	</div>
	<div class="table">
		<div class="table_left">
			{phrase var='resume.to'}
		</div>
		<div class="table_right">
			<input name="search[todate]" id="js_to_date_filter" type="text" value="{if isset($sToDate) && $sToDate}{$sToDate}{/if}" />
			<a href="#" id="js_to_date_filter_anchor">
				<img src="<?php echo Phpfox::getLib('template')->getStyle('image', 'jquery/calendar.gif'); ?>" />
			</a>
		</div>
	</div>
	<div class="table">
		<div class="table_left">
			{phrase var='resume.type'}
		</div>
		<div class="table_right">
			<select name="search[type]">
				<option value="3" selected>{phrase var='resume.all'}</option>
				<option value="4" {if isset($sType) && $sType=="4"}selected{/if}>{phrase var='resume.who_viewed_me'}</option>	
				<option value="1" {if isset($sType) && $sType=="1"}selected{/if}>{phrase var='resume.view_resume'}</option>				
			</select>
		</div>
	</div>
	<!-- Submit button -->
	<div class="table_clear">
		<input type="submit" class="button" value="{phrase var='resume.search'}"></span>
	</div>
</form>

{if count($aResumes) > 0}
<form action="{url link='current'}" method="post" id="karaoke_recording_list" >
	<table align='center'>
		<!-- Table rows header -->
		<tr>
			<th><input type="checkbox" onclick="checkAllResume();" id="resume_list_check_all" name="resume_list_check_all"/></th>
			<th>{phrase var='resume.id'}</th>
			<th></th>
			<th width='10%' style="text-align: left !important;">{phrase var='resume.owner'}</th>
			
			
			<th>{phrase var='resume.approve_view'}</th>
			<th>{phrase var='resume.registered_date'}</th>
			
			
			<th>{phrase var='resume.approve_who_view'}</th>
			<th>{phrase var='resume.registered_date'}</th>
		</tr>
		<!-- Request rows -->
		{ foreach from=$aResumes key=iKey item=aResume}
		<tr id="resume_view_{$aResume.account_id}" class="checkRow{if is_int($iKey/2)} tr{else}{/if}">
			<!-- Check Box -->
			<td style="width:10px">				
				<input type = "checkbox" class="resume_view_checkbox" id="resume_{$aResume.account_id}" name="resume_row[]" value="{$aResume.account_id}" onclick="checkDisableStatus();"/>
			</td>

			<td style="text-align: center">{$aResume.account_id}</td>
			<td class="t_center">
				<a href="#" class="js_drop_down_link" title="Options">{img theme='misc/bullet_arrow_down.png' alt=''}</a>
				<div class="link_menu">
					<ul>
						<li><a href="javascript:void(0);" onclick="if(confirm( '{phrase var='resume.are_you_sure'}')) return deleteAccount('{$aResume.account_id}');">{phrase var='admincp.delete'}</a></li>					
					</ul>
				</div>	
			</td> 
			<td>
				<a href="{url link=''}{$aResume.user_name}/">{$aResume.full_name}</a>
			</td>
		
			
			
			<!-- Feature -->
			<td style="text-align: center" class="type_1">
				
				{if $aResume.view_resume>0}
				{if $aResume.user_group_id!=1}
				<a href="#" onclick="setApproveView({$aResume.account_id},'no');return false;" class="yes_button" style="display:{if $aResume.is_employer eq 0}none{else}block{/if}">
					{img theme='misc/bullet_green.png' alt=''}
				</a>
				<a href="#" onclick="setApproveView({$aResume.account_id},'yes');return false;" class="no_button"  style="display: {if $aResume.is_employer eq 0}block{else}none{/if};">
					{img theme='misc/bullet_red.png' alt=''}
				</a>
				{else}
					{img theme='misc/bullet_green.png' alt=''}
				{/if}
				{else}
					{phrase var='resume.none'}
				{/if}
				
			</td>
			<td style="text-align: center"><?php if($this->_aVars['aResume']['start_employer_time']==0 || $this->_aVars['aResume']['start_employer_time']==null) echo Phpfox::getPhrase('resume.n_a'); else echo Phpfox::getTime("m/d/Y", $this->_aVars['aResume']['start_employer_time']); ?></td>

			
			<!-- Statistic -->
				<td style="text-align: center" class="type_2">
				
				{if $aResume.view_resume==0}
				{if $aResume.user_group_id!=1}

				<a href="#" onclick="setApproveWhoView({$aResume.account_id},'no');return false;" class="yes_button" style="display:{if $aResume.is_employee eq 0}none{else}block{/if}">
					{img theme='misc/bullet_green.png' alt=''}
				</a>
				<a href="#" onclick="setApproveWhoView({$aResume.account_id},'yes');return false;" class="no_button"  style="display: {if $aResume.is_employee eq 0}block{else}none{/if};">
					{img theme='misc/bullet_red.png' alt=''}
				</a>
				{else}
					{img theme='misc/bullet_green.png' alt=''}
				{/if}
				{else}
					{phrase var='resume.none'}
				{/if}
				
			</td>
			<td style="text-align: center"><?php if($this->_aVars['aResume']['start_time']==0 || $this->_aVars['aResume']['start_time']==null) echo Phpfox::getPhrase('resume.n_a'); else echo Phpfox::getTime("m/d/Y", $this->_aVars['aResume']['start_time']); ?></td>
		</tr>
		{/foreach}
	</table>
	<!-- Delete selected button -->
	<div class="table_bottom">
        <input type="submit" name="delete_selected" id="delete_selected" disabled value="{phrase var='resume.delete'}" class="sJsConfirm delete_selected button disabled" />
        <input type='hidden' name='task' value='do_delete_selected' />
    </div>
</form>
{pager}
{else}
<div class="extra_info">{phrase var='resume.no_account_is_found'}</div>
{/if}

{*else*}
	{*phrase var='resume.please_chooose_default_group_for_who_s_view_me_service_and_view_all_resume_service_before_upgrading'*}
{*/if*}
