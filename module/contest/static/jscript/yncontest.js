(function(window, undefined) {
	var yncontest = {
		announcement: {
			editAnnouncement : function(id) {
            $('#contest_announcement_headline').val($('#contest_headline_'+id).html());
            $('#contest_announcement_link').val($('#contest_link_'+id).html());
            $('#contest_announcement_content').val($('#contest_content_'+id).html());
            $('#yncontest_announcement_id').val(id);
            $('#contest_add_announcement').hide();
            $('#contest_update_announcement').show();
            $('#tabs-3').scrollTop(0);

			},
			cancelEditAnnouncement : function() {
	            $('#contest_announcement_headline').val('');
	            $('#contest_announcement_link').val('');
	            $('#contest_announcement_content').val('');
	            $('#yncontest_announcement_id').val('');
	            $('#contest_add_announcement').show();
            	$('#contest_update_announcement').hide();
			},
			deleteAnnouncement : function(id, phrase) {
				if(confirm(phrase)) 
				{
					$.ajaxCall('contest.deleteAnnouncement', 'announcement_id=' + id); 

					if( $('#yncontest_announcement_id').val() == id) 
					{	
						$('#core_js_contest_form_announcement')[0].reset(); 
						$('#contest_add_announcement').show();
            			$('#contest_update_announcement').hide();
					}
				}

				return false;
			}

		},	
		overridedLoadInitForTabView : function () {
			debug('$Core.loadInit() Loaded');		
		
			$('*:not(#tabs_view *)').unbind();
			
			$.each($Behavior, function() 
			{		
				this(this);
			});	
		},
		showErrorMessageAndHide: function(jEle) {
			//jEle is a jquery object
			jEle.show();
			setTimeout(function() {
				jEle.hide(500);
			}, 4000);
		},
		initializeValidator: function (element) {
			jQuery.validator.messages.required = oTranslations['contest.this_field_is_required'];
			jQuery.validator.messages.url = oTranslations['contest.please_enter_a_valid_url'];
			jQuery.validator.messages.range = oTranslations['contest.please_enter_an_amount_greater_or_equal'] + ' {0} ' + "" ;
			jQuery.validator.messages.accept = oTranslations['contest.please_enter_a_value_with_a_valid_extension'] ;
			
			element.validate({
				errorPlacement: function (error, element) {
					if(element.is(":radio") || element.is(":checkbox")) {
						error.appendTo(element.parent());
					} else {
						error.insertAfter(element);
					}
				}
			});

			jQuery.validator.addClassRules("yn_positive_number", {
				range:[0,10000000000]
			});

			jQuery.validator.addClassRules("yn_positive_number_greater_than_0", {
				range:[1,10000000000]
			});

			jQuery.validator.addClassRules("yn_validation_file_type", {
				accept: "jpg|gif|jpeg|png"
			});


			jQuery.validator.addClassRules("yn_contest_title_max_length", {maxlength:255});
			jQuery.validator.addClassRules("yn_contest_short_description_max_length", {maxlength:160});
		
			
		},
		addEntry : {
			createAjaxUrlForAddEntry: function () {
				var title = encodeURIComponent($('#yncontest_entry_title').val());
				var summary = encodeURIComponent($('#yncontest_entry_summary').val());
				var item_id = $('#yncontest_item_id').val();
				var item_type = $('#yncontest_item_type').val();
				var contest_id = $('#yncontest_contest_id').val();
				return $.ajaxBox('contest.submitEntry', "height=400&width=600" + 
					'&summary=' + summary +
					'&title=' + title + 
					'&item_id=' + item_id + 
					'&item_type=' + item_type + 
					'&contest_id=' + contest_id 
					);
			},

			createAjaxUrlForSubmitEntry: function () {
				var sUrl = yncontest.addEntry.createAjaxUrlForAddEntry();
				return sUrl + '&is_submit=1';
			},

			initializeClickOnEntryItem: function() {
				$('.yncontest_add_entry_item').click(function() {
					$('#yncontest_item_id').val($(this).attr('entry_item_id'));
					yncontest.addEntry.removeSelectedEntryItem();
					$(this).addClass('select');
				});
			},

			removeSelectedEntryItem: function() {
				$('.yncontest_add_entry_item.select').removeClass('select');
			},

			previewEntry: function(tb_phrase) {
				if(yncontest.addEntry.validateAddEntryForm())
				{
					tb_show(tb_phrase, yncontest.addEntry.createAjaxUrlForAddEntry());
				}
				
			},

			validateAddEntryForm: function() {
				if(parseInt($('#yncontest_item_id').val()) == 0)
				{
					yncontest.showErrorMessageAndHide($('#yncontest_must_select_an_item'));
					return false;
				}

				if($('#yncontest_entry_title').val() == '' || $('#yncontest_entry_summary').val() == '')
				{
					yncontest.showErrorMessageAndHide($('#yncontest_title_summary_required'));
					return false;
				}

				if($('#yncontest_entry_title').val().length > 255 )
				{
					yncontest.showErrorMessageAndHide($('#yncontest_title_max_length'));
					return false;
				}


				return true;
			},
			submitAddEntry: function() {
				if(yncontest.addEntry.validateAddEntryForm())
				{	
					$('#yncontest_submit_add_entry_button').attr('disabled', 'disabled');
					$.ajaxCall(yncontest.addEntry.createAjaxUrlForSubmitEntry());
				}

			},
			setChosenItem: function(item_id) {
				$('#yncontest_entry_item_' + item_id).trigger('click');
			},
			addAjaxForCreateNewItem: function(contest_id, contest_type) {
				//contest_type is integer
				$('#yncontest_create_new_item a').click(function() {
					$.ajaxCall('contest.setContestSession', 'contest_id=' + contest_id + '&contest_type=' + contest_type, 'GET');
					return false;
					// window.location = $(this).attr('href');
				});

				
			}
		},

		pay : {
			addRemoveFees: function() {
				var total = 0;
				$('.yncontest_fee').each(function() {

					if($(this).is(':checked') || $(this).attr('ynchecked')== 'checked')
					{
						var current = parseFloat($(this).attr('fee_value'));
						total = total + current;
					}
				});

				$('#yn_contest_total_fee').html(total);

			},

			bindOnclickAddRemoveFees : function() {
				$('.yncontest_fee').click(function() {
					yncontest.pay.addRemoveFees();
				});
			}
		},

		join : {
			showJoinContestPopup : function(contest_id, popup_phrase) {
				tb_show(popup_phrase, $.ajaxBox('contest.showJoinPopup', "width=450&contest_id=" + contest_id));
			},
			submitJoinContest : function(contest_id) {
				if(yncontest.join.validateJoinForm())
				{
					$('#yncontest_join_button').attr('disabled', 'disabled');
					$('#yn_contest_waiting_join').show();
					$.ajaxCall('contest.joinContest', 'contest_id=' + contest_id, 'GET');
				}
				
			},
			validateJoinForm : function() {
				if($('#yncontest_join_agree_term_condition').is(':checked'))				
				{
					return true;
				}	
				else
				{
					$('#yncontest_must_agree').show();
					return false;
				}
			}
		},

		invite : {
			clickAll : function () {
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

			unClickAll : function () {
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
		},

		addContest : {
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

			publishContest : function() {
				if($("#yncontest_main_info_form").valid())
				{	
					$("#yncontest_main_info_form").ajaxCall('contest.addNewContest');
				}
			},

			showPayPopup: function(contest_id) {
				tb_show("", $.ajaxBox('contest.showPayPopup', "width=400&contest_id=" + contest_id));
			},
			submitPayForm: function(contest_id) {
				$('#yn_contest_waiting_pay').show();
				$('#yncontest_pay_publish').attr('disabled', 'disabled');
				$('#yncontest_pay_cancel').attr('disabled', 'disabled');
				$("#yncontest_pay_form").ajaxCall('contest.processPayForPublishContest', 'contest_id=' + contest_id);
			},
			disableFields: function() {
				if(parseInt($('#yncontest_is_should_disable').val()))
				{
					$('.js_mp_category_list').attr("disabled", "disabled");
					$('.contest_add.contest_type_radio').attr("disabled", "disabled");
					$('#yncontest_add_contest_name').attr("disabled", "disabled");
					$('#image').attr("disabled", "disabled");
					$('.js_date_picker ').attr("disabled", "disabled");
					$('#start_submit_time_hour').attr("disabled", "disabled");
					$('#start_submit_time_minute').attr("disabled", "disabled");

					$('#stop_submit_time_hour').attr("disabled", "disabled");
					$('#stop_submit_time_minute').attr("disabled", "disabled");

					$('#end_time_hour').attr("disabled", "disabled");
					$('#end_time_minute').attr("disabled", "disabled");

					$('.yncontest_start_submit_time *').unbind('click');
					$('.yncontest_stop_submit_time *').unbind('click');
					$('.yncontest_end_time *').unbind('click');

					$('#maximum_entry').attr("disabled", "disabled");
					$('.privacy_setting_active').unbind('click');
					$('.privacy_setting_active').click(function(){
						return false;
					})
				}
			}
		},
		
        homepage: {
            changeFilter: function() {
                if ($('#entries-filter').val() == 'recent') {
                    $('.most-voted-entries').hide();
                    $('.recent-entries').show();
                }
                if ($('#entries-filter').val() == 'most_voted') {
                    $('.recent-entries').hide();
                    $('.most-voted-entries').show();
                }
            },
            
            changeType: function() {
                var url = window.location.href;
                url = url.replace(/\/type_.*?\//g, '/');
                url = url + 'type_' + $('#js_select_type').val() + '/';
                window.location.href = url;
            },
        },

	};

	window.yncontest = yncontest;

})(window);