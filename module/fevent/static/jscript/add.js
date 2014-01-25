$Behavior.addNewEvent = function()
{
	$('.js_event_change_group').click(function()
	{
		if ($(this).parent().hasClass('locked'))
		{
			return false;
		}
		
		aParts = explode('#', this.href);
		
		$('.js_event_block').hide();
		$('#js_event_block_' + aParts[1]).show();
		$(this).parents('.header_bar_menu:first').find('li').removeClass('active');
		$(this).parent().addClass('active');
		$('#js_event_add_action').val(aParts[1]);
	});
	
	$('.js_mp_category_list').change(function()
	{
        if($(this).val()=='')
        {
            var comboboxes = $("#categories .js_mp_category_list");
            for(var i=0; i<comboboxes.length; i++)
            {
                if(comboboxes[i].id==this.id && i>0)
                {
                    $(comboboxes[i-1]).change();
                }
            }
            return;
        }
        // Display custom fields if available
        $.ajaxCall("fevent.getCustomFields", "id=" + $(this).val());
        
		var iParentId = parseInt(this.id.replace('js_mp_id_', ''));
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

function custom_js_event_form()
{
    $('#js_event_form_msg').hide('');
    $('#js_event_form_msg').html('');
    var bIsValid = true;
    var fields = eval($("#required_custom_fields").val());
    if(fields!=null)
    for(var i=0; i<fields.length; i++)
    {
        var passed = true;
        switch(fields[i]['var_type'])
        {
            case "radio":
            case "checkbox":
                if($('input[id="cf_' + fields[i]['field_name']+'"]:checked').length==0)
                {
                    passed = false;
                }
                break;
            default:
            var value = $.trim($('#cf_' + fields[i]['field_name']).val());
            if(value == '' || value == null)
            {
                passed = false;
            }
        }
        if(!passed)
        {
            bIsValid = false; 
            $('#js_event_form_msg').message(oTranslations['fevent.the_field_field_name_is_required'].replace('{field_name}', fields[i]['phrase_name']), 'error');
            $('#cf_' + fields[i]['field_name']).addClass('alert_input');
        }
    }
    if (!bIsValid)
    {
        $('#js_event_form_msg').show();
        window.location.hash = '#pem';
    }
    return bIsValid && Validation_js_event_form();
}