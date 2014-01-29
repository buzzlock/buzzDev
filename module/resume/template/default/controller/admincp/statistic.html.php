
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
{/literal}
<div class="table_header">{phrase var='resume.statistics'}</div>
<!-- Search -->

<div class="table">
	<div class="info_left">
		{phrase var='resume.who_s_viewed_me'} : 
	</div>
	&nbsp;{$aForms.whoview} {phrase var='resume.members'}
</div>

<div class="table">
	<div class="info_left">
		{phrase var='resume.view_all_resumes'} : 
	</div>
	&nbsp;{$aForms.view} {phrase var='resume.members'}
</div>



