
	var ynjobposting = {
		overridedLoadInitForTabView : function () {
			debug('$Core.loadInit() Loaded');		
			
			$('*:not(#tabs_view *)').unbind();
			
			$.each($Behavior, function() 
			{		
				this(this);
			});
		},
		
		overridedLoadInitForTabViewAgain : function () {
			debug('$Core.loadInit() Loaded');
            
			$('*:not(.row_edit_bar_action *)').unbind();
			$.each($Behavior, function() 
			{		
				this(this);
			});
		},
		
        overridedLoadInit : function () {
			debug('$Core.loadInit() Loaded');		
		
			$('*:not(#ync_gallery_slides *, #ync_slides *)').unbind();
			
			$.each($Behavior, function() 
			{		
				this(this);
			});
		},
        
        application: {
            view: function(id, tb_name) {
                tb_show(tb_name, $.ajaxBox('jobposting.blockViewApplication', 'width=450&height=400&id=' + id));
            },
            
            confirm_delete: function(id, phrase_confirm) {
                if(confirm(phrase_confirm)) {
                    $.ajaxCall('jobposting.deleteApplication', 'id=' + id);
                }
            },
            
            reject: function(id) {
                $.ajaxCall('jobposting.updateApplicationStatus', 'id=' + id + '&status=rejected');
            },
            
            pass: function(id) {
                $.ajaxCall('jobposting.updateApplicationStatus', 'id=' + id + '&status=passed');
            }
        },
        
        company: {
        	confirmSponsor: function(permission, fee) {
				if (permission) {
					if (confirm(oTranslations['jobposting.do_you_want_to_sponsor_your_company_width'].replace('{fee}', fee))) {
						$('#js_jc_sponsor_checkbox').attr('checked', true);
					}
				}
				return true;
        	},
            
            sponsor: function(id, fee) {
                if (confirm(oTranslations['jobposting.pay_fee_to_sponsor_this_company'].replace('{fee}', fee))) {
                    $('.js_jc_sponsor_btn').attr('disabled', true).addClass('button_off');
                    $('.js_jc_add_loading').html($.ajaxProcess(oTranslations['jobposting.processing'])).show();
                    $.ajaxCall('jobposting.sponsorCompany', 'id=' + id);
                }
            },
            
            submitForm: function() {
                if(ynjobposting.company.totalFee() > 0) {
                    if(!confirm(oTranslations['jobposting.save_and_pay_fee_for_selected_package'].replace('{fee}', ynjobposting.company.totalFee()))) {
                        return false;
                    }
                }
                return true;
            },
            
            payPackages: function(id) {
                if(ynjobposting.company.totalCheck() > 0) {
                   	var val = $('input[name="val[packages][]"]').serialize();
                   	var currency = $('#currency_jobposting').val();
                    if(confirm(oTranslations['jobposting.pay_fee_for_selected_packages'].replace('{fee}', ynjobposting.company.totalFee() + ' ' + currency))) {
                    	$('.js_jc_package').attr('disabled', true);
                        $('.js_jc_pay_packages_btn').attr('disabled', true).addClass('button_off');
            	        $('.js_jc_add_loading').html($.ajaxProcess(oTranslations['jobposting.processing'])).show();
            	        $.ajaxCall('jobposting.payPackages', 'id=' + id + '&' + val);
                    }
                }
            },
            
            updatePayPackagesBtn: function() {
            	if(ynjobposting.company.totalCheck() > 0) {
                    $('.js_jc_pay_packages_btn').attr('disabled', false).removeClass('button_off');
                } else {
                    $('.js_jc_pay_packages_btn').attr('disabled', true).addClass('button_off');
                }
            },
            
            totalCheck: function() {
                var total = 0;
            	$('.js_jc_package').each(function() {
            		if($(this).is(':checked')) {
            			total++;
            		}
            	});
                return total;
            },
            
            totalFee: function() {
                var total = 0;
            	$('.js_jc_package').each(function() {
            		if($(this).is(':checked')) {
            			total += parseFloat($(this).attr('fee_value'));
            		}
            	});
                return total;
            },
            
            searchJobs: function(url) {
                var title = js_jc_form.search_title.value;
                var from = js_jc_form.js_from__datepicker.value.replace(/\//g,'-');
                var to = js_jc_form.js_to__datepicker.value.replace(/\//g,'-');
                var status = js_jc_form.search_status.value;
                
                var action = url + 'search_jobs/title_' + title + '/from_' + from + '/to_' + to + '/status_' + status;
                window.location.href = action;
            }
        },
        
        invite: {
            selectAll: function() {
                $('input.checkbox').each(function(i,e) {
                    if($(e).attr('checked') != 'checked') {
                        $(e).attr('checked', 'checked');
                        $('.friend_search_holder').addClass('friend_search_active');
                        addFriendToSelectList(e, $(e).val(), true);
                    }
                });
            },
            
            unselectAll: function() {
                $('input.checkbox').each(function(i,e) {
                    if($(e).attr('checked') == 'checked') {
                        $(e).attr('checked', false);
                        $('.friend_search_holder').removeClass('friend_search_active');
                        addFriendToSelectList(e, $(e).val(), false);
                    }
                });
            }
        }
	};

	window.ynjobposting = ynjobposting;

