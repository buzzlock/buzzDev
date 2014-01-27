(function(window, undefined) {
	var ynfundraising = {
		donate: { 
			selectPredefinedValue: function(iValue)	 {
				$('#ynfr_donate_amount').val(iValue);
			},
			selectOtherValue: function() {
				$('#ynfr_donate_amount').val('').focus();	
				
			}
		},
		addCategoryJsEventListener: function() {
			$('.js_mp_category_list').change(function()
			{	  
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

	
			$('.hover_action').each(function()
			{
				$(this).parents('.js_outer_video_div:first').css('width', this.width + 'px');
			});	
		},
		initializeValidator: function (element) {
			jQuery.validator.messages.required = oTranslations['fundraising.this_field_is_required'];
			jQuery.validator.messages.number = oTranslations['fundraising.please_enter_a_valid_number'];
			jQuery.validator.messages.email = oTranslations['fundraising.please_enter_a_valid_email'];
			jQuery.validator.messages.url = oTranslations['fundraising.please_enter_a_valid_url'];
			
			element.validate({
				errorPlacement: function (error, element) {
					if(element.is(":radio") || element.is(":checkbox")) {
						error.appendTo(element.parent());
					} else if(element.is('.js_predefined')) {
						error.insertAfter(element.parent().parent());
					}else if(element.is('.ynfr_donate_amount')) {
						error.appendTo(element.parent().parent());
					}else {
						error.insertAfter(element);
					}
				}
			});

			jQuery.validator.addClassRules("ynfr_positive_number", {
				range:[0,10000000000]
			});
			jQuery.validator.addClassRules("ynfr_campaign_title_max_length", {maxlength:255});
			jQuery.validator.addClassRules("ynfr_campaign_short_description_max_length", {maxlength:160});
		
			
		},
		addAgreeRequired: function (){
			jQuery.validator.addMethod("agree-required", function( value, element ) {
				var result = $(element).is(":checked");
				return result;
			}, oTranslations['fundraising.you_must_agree_with_terms_and_conditions']);	
		},
		
		ClickAll : function () {
			$(".friend_search_holder").each(function () {
				if($(this).find("input.checkbox[name=friend[]]").attr("checked") != "checked") {
					$(this).click();
				}
			});

			if($(".friend_search_holder").length == 0)
			{
				$('input.checkbox').each(function () {
					if($(this).attr("checked") != "checked") {
						$(this).attr('checked', 'checked');
						addFriendToSelectList(this, $(this).val());
					}
				});
			}
			
		},

		UnClickAll : function () {
			$(".friend_search_holder").each(function () {
				if($(this).find("input.checkbox[name=friend[]]").attr("checked") == "checked") {
					$(this).click();
				}
			});

			if($(".friend_search_holder").length == 0)
			{
				$('input.checkbox').each(function () {
					if($(this).attr("checked") == "checked") {
						$(this).attr('checked', false);
						addFriendToSelectList(this, $(this).val());
					}
				});
			}
		},

		overridedLoadInit : function () {
			debug('$Core.loadInit() Loaded');		
		
			$('*:not(#ynfr_gallery_slides *, #ynfr_slides *)').unbind();
			
			$.each($Behavior, function() 
			{		
				this(this);
			});	
		}


	};

	window.ynfundraising = ynfundraising;

})(window);