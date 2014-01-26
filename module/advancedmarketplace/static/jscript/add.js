
$Behavior.advancedmarketplaceAdd = function()
{
	$('.js_mp_category_list').change(function()
	{
		var $this = $(this);
		var iParentId = parseInt(this.id.replace('js_mp_id_', ''));
		// var iCatId = document.getElementById('js_mp_id_0').value;
		iCatId = $this.val();
		if(!iCatId) {
			iCatId = parseInt($this.parent().attr("id").replace('js_mp_holder_', ""));
		}

		$.ajaxCall('advancedmarketplace.frontend_loadCustomFields', 'catid='+iCatId+'&lid='+$("#ilistingid").val());
		$('.js_mp_category_list').each(function()
		{
			if (parseInt(this.id.replace('js_mp_id_', '')) > iParentId)
			{
				$('#js_mp_holder_' + this.id.replace('js_mp_id_', '')).hide();				
				
				this.value = '';
			}
		});
		
		$('#js_mp_holder_' + $(this).val()).show();
	});	
}
