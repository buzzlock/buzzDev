$.fn.ajaxCall = function(sCall, sExtra, bNoForm, sType, oSuccess)
{	
	if (empty(sType))
	{
		sType = 'POST';
	}
	
	switch (sCall){
		case 'share.friend':
		case 'share.email':
		case 'share.bookmark':
		case 'share.post':
			sType = 'POST';
			break;
		default:
			
			break;
	}	
	
	var sUrl = getParam('sJsAjax');
	
	if (typeof oParams['im_server'] != 'undefined' && sCall.indexOf('im.') > (-1))
	{
		sUrl = getParam('sJsAjax').replace(getParam('sJsHome'),getParam('im_server'));
	}
	
	var sParams = '&' + getParam('sGlobalTokenName') + '[ajax]=true&' + getParam('sGlobalTokenName') + '[call]=' + sCall + '' + (bNoForm ? '' : this.getForm());
	if (sExtra)
	{
		sParams += '&' + ltrim(sExtra, '&');
	}
	
	if (!sParams.match(/\[security_token\]/i))
	{
		sParams += '&' + getParam('sGlobalTokenName') + '[security_token]=' + oCore['log.security_token'];
	}
	
	sParams += '&' + getParam('sGlobalTokenName') + '[is_admincp]=' + (oCore['core.is_admincp'] ? '1' : '0');
	sParams += '&' + getParam('sGlobalTokenName') + '[is_user_profile]=' + (oCore['profile.is_user_profile'] ? '1' : '0');
	sParams += '&' + getParam('sGlobalTokenName') + '[profile_user_id]=' + (oCore['profile.user_id'] ? oCore['profile.user_id'] : '0');	

	if (getParam('bJsIsMobile')){
		sParams += '&js_mobile_version=true';
	}
	
	if(undefined !== oSuccess && null !== oSuccess) {
		oCacheAjaxRequest = $.ajax(
		{
				type: sType,
			  	url: sUrl,//getParam('sJsStatic') + "ajax.php",
			  	dataType: "script",	
				data: sParams, 
				success: oSuccess
			}
		);
	} else {
		oCacheAjaxRequest = $.ajax(
		{
				type: sType,
			  	url: sUrl,//getParam('sJsStatic') + "ajax.php",
			  	dataType: "script",	
				data: sParams			
			}
		);
	}

	return oCacheAjaxRequest;
}

// $.ajaxCall = function(sCall, sExtra, sType, oSuccess)
// {
// 	if(undefined !== oSuccess && null !== oSuccess) {
// 	    return $.fn.ajaxCall(sCall, sExtra, true, sType, oSuccess);
// 	} else {
// 	    return $.fn.ajaxCall(sCall, sExtra, true, sType);
// 	}
// }

function ynmt_trim(str, chars) {
	return ltrim(rtrim(str, chars), chars);
}

function ynmt_ltrim(str, chars) {
	chars = chars || "\\s";
	return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
}

function ynmt_rtrim(str, chars) {
	chars = chars || "\\s";
	return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
}

var ynmtMobileTemplate = 
{
	isCheckinFromHomepage: false
	, isChooseFileInPhoto: false
	, fullControllerName: ''
	, langText: {}
	, currHref: ''
	, init: function()
	{
		ynmtMobileTemplate.currHref = document.URL;
	} 			
	, initCheckIn: function()
	{
		ynmtMobileTemplate.isCheckinFromHomepage = true;
		if($('#mt_js_activity_feed_div').length > 0){
			$('#mt_js_activity_feed_div').show();
			$('#mt_global_attachment_photo_file_input').hide();
        	$('#btnShareStatus').show();
        	$('#btnSharePhoto').hide();
        	$('#mt_user_status').show();
        	$('#mt_status_info').hide();
        	
	    	$('#btnCheckIn').show();
	    	$('#btnPhotoIcon').hide();
        	
        	ynmtMobileTemplate.clickCheckin();
        	ynmtMobileTemplate.showSubHolder();
        	ynmtMobileTemplate.showCheckin();
		} else {
	        //      init
	        //      process
	        $Core.ajax('mobiletemplate.getStatusBlock',
	        {		
	            type: 'POST',
	            params:
	            {				
	                orderDetailID: ''
	            },
	            success: function(sOutput)
	            {		
	                var oOutput = $.parseJSON(sOutput);
	                if(oOutput.result == 'SUCCESS')
	                {
                    	//	assign variables to check-in object 
				        ynmtPlaces.sIPInfoDbKey = oOutput.sIPInfoDbKey;
				        ynmtPlaces.sGoogleKey = oOutput.sGoogleKey;
                        if ((oOutput["visitorLocationLat"] != null) && (oOutput["visitorLocationLat"].length != 0)
                        	&& (oOutput["visitorLocationLong"] != null) && (oOutput["visitorLocationLong"].length != 0)
                        ){
                        	ynmtPlaces.setVisitorLocation(oOutput.visitorLocationLat, oOutput.visitorLocationLong);
                        }
                        
	                	// var html = document.getElementById("mobile_sub_holder").innerHTML; 
	                	// document.getElementById("mobile_sub_holder").innerHTML = html + oOutput.content;
		            	$('#mobile_sub_holder').html('');		
		            	$('#mobile_sub_holder').html(oOutput.content);
	                	$('#mt_global_attachment_photo_file_input').hide();
	                	$('#btnShareStatus').show();
	                	$('#btnSharePhoto').hide();
	                	$('#mt_user_status').show();
	                	$('#mt_status_info').hide();
	                	
	                	$('#btnCheckIn').show();
	                	$('#btnPhotoIcon').hide();
	                	
						ynmtMobileTemplate.clickCheckin();	                	
	                	ynmtMobileTemplate.showSubHolder();
	                	ynmtMobileTemplate.showCheckin();
	                } else if(oOutput.result == 'FAILURE')
	                {
	                }
	            }
	        });  		
		}
        //      end 
        return false;		
	} 			
	, cancelCheckinFromHomepage: function()
	{
		$('#checkinTextSearch').val('');
		if(ynmtMobileTemplate.isCheckinFromHomepage == true){
			ynmtMobileTemplate.hideSubHolder();
		} else {
	    	$('#mt_status_share').show();
	    	$('#mt_check_in').hide();
		}
	} 
	, cancelStatusShareFromHomepage: function()
	{
		ynmtMobileTemplate.hideSubHolder();
	} 
	, showSubHolder: function()
	{
    	$('#mobile_sub_holder').show();
    	$('#mobile_holder').hide();
    	$('.ym-menu-slide').hide();
	} 
	, hideSubHolder: function()
	{
    	$('#mobile_sub_holder').hide();
    	$('#mobile_holder').show();
    	$('.ym-menu-slide').show();
	} 
	, showStatusShare: function()
	{
    	$('#mt_status_share').show();
    	$('#mt_check_in').hide();
	} 
	, showCheckin: function()
	{
    	$('#mt_status_share').hide();
    	$('#mt_check_in').show();
	} 
	, clickCheckin: function()
	{
    	ynmtPlaces.googleReady();
    	ynmtPlaces.initMaps();
		ynmtPlaces.textSearchRequestAuto();
		ynmtPlaces.displaySearching();
		
    	ynmtMobileTemplate.showSubHolder();
    	$('#mt_status_share').hide();
    	$('#mt_check_in').show();
	} 
	, clickPhoto: function()
	{
		ynmtMobileTemplate.isCheckinFromHomepage = false;
		if($('#mt_js_activity_feed_div').length > 0){
			$('#mt_js_activity_feed_div').show();
			$('#mt_global_attachment_photo_file_input').show();
        	$('#btnShareStatus').hide();
        	$('#btnSharePhoto').show();
        	$('#mt_user_status').hide();
        	$('#mt_status_info').show();
        	
        	$('#mt_check_in').hide();
        	$('#btnCheckIn').hide();
        	$('#btnPhotoIcon').show();
        	$('#mt_js_location_feedback').html('');
			$('#mt_status_info textarea').html('');
			$('#mt_status_info textarea').val('');
			document.getElementById("mt_js_activity_feed_form").reset();       	
        	ynmtMobileTemplate.isChooseFileInPhoto = false;
			$('#mt_activity_feed_upload_error_message').parent().hide();
			$('#mt_activity_feed_upload_error_message').html('');
        	
        	ynmtMobileTemplate.changeSharePhoto();
        
        	ynmtMobileTemplate.showStatusShare();
        	ynmtMobileTemplate.showSubHolder();	
		} else {
	        //      init
	        //      process
	        $Core.ajax('mobiletemplate.getStatusBlock',
	        {		
	            type: 'POST',
	            params:
	            {				
	                orderDetailID: ''
	            },
	            success: function(sOutput)
	            {		
	                var oOutput = $.parseJSON(sOutput);
	                if(oOutput.result == 'SUCCESS')
	                {
                    	//	assign variables to check-in object 
				        ynmtPlaces.sIPInfoDbKey = oOutput.sIPInfoDbKey;
				        ynmtPlaces.sGoogleKey = oOutput.sGoogleKey;
                        if ((oOutput["visitorLocationLat"] != null) && (oOutput["visitorLocationLat"].length != 0)
                        	&& (oOutput["visitorLocationLong"] != null) && (oOutput["visitorLocationLong"].length != 0)
                        ){
                        	ynmtPlaces.setVisitorLocation(oOutput.visitorLocationLat, oOutput.visitorLocationLong);
                        }
                        
	                	// var html = document.getElementById("mobile_sub_holder").innerHTML; 
	                	// document.getElementById("mobile_sub_holder").innerHTML = html + oOutput.content;
		            	$('#mobile_sub_holder').html('');		
		            	$('#mobile_sub_holder').html(oOutput.content);
	                	
	                	$('#mt_global_attachment_photo_file_input').show();
	                	$('#btnShareStatus').hide();
	                	$('#btnSharePhoto').show();
	                	$('#mt_user_status').hide();
	                	$('#mt_status_info').show();
	                	
	                	$('#btnCheckIn').hide();
			        	$('#mt_check_in').hide();
			        	$('#btnPhotoIcon').show();
			        	$('#mt_js_location_feedback').html('');
	                	ynmtMobileTemplate.isChooseFileInPhoto = false;
			        	
	                	ynmtMobileTemplate.changeSharePhoto();
	                	
	                	ynmtMobileTemplate.showStatusShare();
	                	ynmtMobileTemplate.showSubHolder();
	                } else if(oOutput.result == 'FAILURE')
	                {
	                }
	            }
	        });  		
		}
        //      end 
        return false;
	} 	
	, getStatusBlock: function()
	{
		ynmtMobileTemplate.isCheckinFromHomepage = false;
		if($('#mt_js_activity_feed_div').length > 0){
			$('#mt_js_activity_feed_div').show();
			$('#mt_global_attachment_photo_file_input').hide();
        	$('#btnShareStatus').show();
        	$('#btnSharePhoto').hide();
        	$('#mt_user_status').show();
        	$('#mt_status_info').hide();
        	
	    	$('#btnCheckIn').show();
	    	$('#btnPhotoIcon').hide();
        	
			$('#mt_activity_feed_upload_error_message').parent().hide();
			$('#mt_activity_feed_upload_error_message').html('');
        	
        	ynmtMobileTemplate.showStatusShare();
        	ynmtMobileTemplate.showSubHolder();
        	
	        $('#global_attachment_status textarea').html('')
	        $('#global_attachment_status textarea').val('')
	        $('#val_location_latlng').val('')
	        $('#val_location_name').val('')
	        $('#privacy').val('0')
        	
		} else {
	        //      init
	        //      process
	        $Core.ajax('mobiletemplate.getStatusBlock',
	        {		
	            type: 'POST',
	            params:
	            {				
	                orderDetailID: ''
	                , fullControllerName: ynmtMobileTemplate.fullControllerName
	            },
	            success: function(sOutput)
	            {		
	                var oOutput = $.parseJSON(sOutput);
	                if(oOutput.result == 'SUCCESS')
	                {
                    	//	assign variables to check-in object 
				        ynmtPlaces.sIPInfoDbKey = oOutput.sIPInfoDbKey;
				        ynmtPlaces.sGoogleKey = oOutput.sGoogleKey;
                        if ((oOutput["visitorLocationLat"] != null) && (oOutput["visitorLocationLat"].length != 0)
                        	&& (oOutput["visitorLocationLong"] != null) && (oOutput["visitorLocationLong"].length != 0)
                        ){
                        	ynmtPlaces.setVisitorLocation(oOutput.visitorLocationLat, oOutput.visitorLocationLong);
                        }
                        
	                	// var html = document.getElementById("mobile_sub_holder").innerHTML; 
	                	// document.getElementById("mobile_sub_holder").innerHTML = html + oOutput.content;
		            	$('#mobile_sub_holder').html('');		
		            	$('#mobile_sub_holder').html(oOutput.content);
	                	$('#mt_global_attachment_photo_file_input').hide();
	                	$('#btnShareStatus').show();
	                	$('#btnSharePhoto').hide();
	                	$('#mt_user_status').show();
	                	$('#mt_status_info').hide();
	                	
	                	$('#btnCheckIn').show();
	                	$('#btnPhotoIcon').hide();
	                	
	                	ynmtMobileTemplate.showStatusShare();
	                	ynmtMobileTemplate.showSubHolder();
	                } else if(oOutput.result == 'FAILURE')
	                {
	                }
	            }
	        });  		
		}
        //      end 
        return false;
	} 	
	, clickPrivacyControl: function(type, value)
	{
		$('#selectedPrivacy').val(value);
		$('#selectedPrivacyType').val(type);
		if(type == 'non_custom' && value == 4){
			$Core.box('privacy.getFriends', '', 'no_page_click=true&amp;privacy-array=');
		}
		ynmtMobileTemplate.clickYmPrivacyA();
		var styleClass = 'icon-public';
		if(value == 1){
			styleClass = 'icon-ff';
		}
		if(value == 2){
			styleClass = 'icon-fof';
		}
		if(value == 3){
			styleClass = 'icon-onlyme';
		}
		if(value == 4){
			styleClass = 'icon-st-custom';
		}
		$('#ym-privacy-a i').removeClass();
		$('#ym-privacy-a i').addClass(styleClass);
	} 	
	, userUpdateStatus: function()
	{
        //      init
        //      process
        var link = '';
        if(ynmtMobileTemplate.fullControllerName == 'mobile.index' || ynmtMobileTemplate.fullControllerName == 'feed.index' || ynmtMobileTemplate.fullControllerName == 'profile.index'){
        	link = 'user.updateStatus';
        	$('#mt_js_activity_feed_form').find('input[name="core[security_token]"]').val($('#js_activity_feed_form').find('input[name="core[security_token]"]').val());
    	} else if (ynmtMobileTemplate.fullControllerName == 'pages.view' || ynmtMobileTemplate.fullControllerName == 'event.view'){
    		if (ynmtMobileTemplate.fullControllerName == 'pages.view'){
    			link = 'pages.addFeedComment';
    		} else if (ynmtMobileTemplate.fullControllerName == 'event.view'){
    			link = 'event.addFeedComment';
    		}
    		
        	$('#mt_js_activity_feed_form').find('input[name="val[callback_item_id]"]').val($('#js_activity_feed_form').find('input[name="val[callback_item_id]"]').val());
        	$('#mt_js_activity_feed_form').find('input[name="val[callback_module]"]').val($('#js_activity_feed_form').find('input[name="val[callback_module]"]').val());
        	$('#mt_js_activity_feed_form').find('input[name="val[parent_user_id]"]').val($('#js_activity_feed_form').find('input[name="val[parent_user_id]"]').val());
        	$('#mt_js_activity_feed_form').find('input[name="core[security_token]"]').val($('#js_activity_feed_form').find('input[name="core[security_token]"]').val());
        	$('#mt_js_activity_feed_form').find('input[name="val[group_id]"]').val($('#js_activity_feed_form').find('input[name="val[group_id]"]').val());
        	// $('#mt_js_activity_feed_form').find('input[name="val[action]"]').val($('#js_activity_feed_form').find('input[name="val[action]"]').val());
        	$('#mt_js_activity_feed_form').find('input[name="val[iframe]"]').val($('#js_activity_feed_form').find('input[name="val[iframe]"]').val());
        	$('#mt_js_activity_feed_form').find('input[name="val[method]"]').val($('#js_activity_feed_form').find('input[name="val[method]"]').val());
        	$('#mt_js_activity_feed_form').find('input[name="val[connection][facebook]"]').val($('#js_activity_feed_form').find('input[name="val[connection][facebook]"]').val());
        	$('#mt_js_activity_feed_form').find('input[name="val[connection][twitter]"]').val($('#js_activity_feed_form').find('input[name="val[connection][twitter]"]').val());
    	}
        
	     $('#mt_js_activity_feed_form').ajaxCall(link, false, false, 'POST'); 

	     //	end 
	     ynmtMobileTemplate.hideSubHolder();

    	 return false;
	} 	
	, triggerAjaxSuccess: function()
	{
		// $( document ).ajaxSuccess(function(event, xhr, settings) {
		//   console.log( "Triggered ajaxSuccess handler." );
		// });	
	} 	
	, onChangeSharePhoto: function()
	{
		ynmtMobileTemplate.isChooseFileInPhoto = true;
	}
	, sharePhoto: function()
	{
		if(ynmtMobileTemplate.isChooseFileInPhoto === true){
	        if(ynmtMobileTemplate.fullControllerName == 'feed.index'){
	        	$('#mt_js_activity_feed_form').find('input[name="core[security_token]"]').val($('#js_activity_feed_form').find('input[name="core[security_token]"]').val());
	    	} else if (ynmtMobileTemplate.fullControllerName == 'pages.view' || ynmtMobileTemplate.fullControllerName == 'event.view'){
	        	$('#mt_js_activity_feed_form').find('input[name="val[callback_item_id]"]').val($('#js_activity_feed_form').find('input[name="val[callback_item_id]"]').val());
	        	$('#mt_js_activity_feed_form').find('input[name="val[callback_module]"]').val($('#js_activity_feed_form').find('input[name="val[callback_module]"]').val());
	        	$('#mt_js_activity_feed_form').find('input[name="val[parent_user_id]"]').val($('#js_activity_feed_form').find('input[name="val[parent_user_id]"]').val());
	        	$('#mt_js_activity_feed_form').find('input[name="core[security_token]"]').val($('#js_activity_feed_form').find('input[name="core[security_token]"]').val());
	        	$('#mt_js_activity_feed_form').find('input[name="val[group_id]"]').val($('#js_activity_feed_form').find('input[name="val[group_id]"]').val());
	        	// $('#mt_js_activity_feed_form').find('input[name="val[action]"]').val($('#js_activity_feed_form').find('input[name="val[action]"]').val());
	        	$('#mt_js_activity_feed_form').find('input[name="val[iframe]"]').val($('#js_activity_feed_form').find('input[name="val[iframe]"]').val());
	        	$('#mt_js_activity_feed_form').find('input[name="val[method]"]').val($('#js_activity_feed_form').find('input[name="val[method]"]').val());
	        	$('#mt_js_activity_feed_form').find('input[name="val[connection][facebook]"]').val($('#js_activity_feed_form').find('input[name="val[connection][facebook]"]').val());
	        	$('#mt_js_activity_feed_form').find('input[name="val[connection][twitter]"]').val($('#js_activity_feed_form').find('input[name="val[connection][twitter]"]').val());
	    	}
	    	
			$('#mt_status_info textarea').html($('#mt_status_info textarea').val())
			ynmtMobileTemplate.hideSubHolder();
			return true;
		} else {
			$('#mt_activity_feed_upload_error_message').parent().show();
			$('#mt_activity_feed_upload_error_message').html(oTranslations['mobiletemplate.please_choose_image']);
			// $('#activity_feed_upload_error_message').parent().show();
			return false;
		}
	} 	
	, changeSharePhoto: function()
	{
		$('#mt_js_activity_feed_form').attr('action', $('#mt_activity_feed_link_form').html()).attr('target', 'mt_js_activity_feed_iframe_loader');

		$sFormAjaxRequest = null;
		if (empty($('.mt_activity_feed_form_iframe').html()))
		{
			$('.mt_activity_feed_form_iframe').html('<iframe id="mt_js_activity_feed_iframe_loader" name="mt_js_activity_feed_iframe_loader" height="200" width="500" frameborder="1" style="display:none;"></iframe>');
		}		
	} 	
	, removeSelectedLocation: function()
	{
		$('#mt_val_location_latlng').val('');
		$('#mt_val_location_name').val('');
		$('#mt_js_location_feedback').html('');
		ynmtMobileTemplate.showCheckin();
		$('#btnCheckIn').removeClass('ym-checked-in');
	} 	
	, clickYmPrivacyA: function()
	{
		if ($("#privacyList").is(':visible')) {
	        $("#privacyList").hide();
	    } else {
	    	$("#privacyList").show();
	    	$(".privacy-control").removeClass('ym-privacy-control-active');
	    	$("#privacy-control-" + $("#selectedPrivacy").val()).addClass('ym-privacy-control-active');
	    }		
	} 	
	, getSharePost: function(frame, sBookmarkType, sBookmarkUrl, sBookmarkTitle, sShareModule, feed_id, is_feed_view)
	{
        //      init
        var params = {				
                type: sBookmarkType
                , url: sBookmarkUrl
                , title: sBookmarkTitle
                , currentURL: document.URL
                , frame: frame
            };
		if(null != sShareModule) {
			params['sharemodule'] = sShareModule;	
		}
		if(null != feed_id) {
			params['feed_id'] = feed_id;	
		}
		if(null != is_feed_view) {
			params['is_feed_view'] = is_feed_view;	
		}

        //      process
        $Core.ajax('mobiletemplate.getSharePost',
        {		
            type: 'POST',
            params: params,
            success: function(sOutput)
            {
                var oOutput = $.parseJSON(sOutput);
                if(oOutput.result == 'SUCCESS')
                {
	            	$('#mobile_sub_holder').html('');		
	            	$('#mobile_sub_holder').html(oOutput.content);
	            	ynmtMobileTemplate.showSubHolder();
                } else if(oOutput.result == 'FAILURE')
                {
                }
            }
        }); 		
	}
	, photoEditPhoto: function(photo_id)
	{
        //      init
        var params = {				
                currentURL: document.URL
                , photo_id: photo_id
                , js_mobile_version:'true'
            };

        //      process
        $Core.ajax('mobiletemplate.photoEditPhoto',
        {		
            type: 'POST',
            params: params,
            success: function(sOutput)
            {
                var oOutput = $.parseJSON(sOutput);
                if(oOutput.result == 'SUCCESS')
                {
	            	$('#mobile_sub_holder').html('');		
	            	$('#mobile_sub_holder').html(oOutput.content);
	            	ynmtMobileTemplate.showSubHolder();
	            	$Core.loadInit();
                } else if(oOutput.result == 'INVALID_PERMISSION')
                {
                	alert(oOutput.msg);
                } else if(oOutput.result == 'FAILURE')
                {
                }
            }
        }); 		
	}

}; 

$Behavior.ynmtInitMobileTemplate = function()
{
	ynmtMobileTemplate.init();
	
	$('#mobile_holder').on("scroll", function(e){
		if(undefined != $iReloadIteration && null != $iReloadIteration){
			if ($Core.isInView('.global_view_more')){
				$iReloadIteration++;
				$('#feed_view_more_loader').show();
				$('.global_view_more').hide();

				setTimeout("$.ajaxCall('feed.viewMore', $('#js_feed_pass_info').html().replace(/&amp;/g, '&') + '&iteration=" + $iReloadIteration + "', 'GET');", 1000);
			}			
		}
  	});		

	if($Core && $Core.friend){
		$Core.friend.addNewList = function(iListId, sName){	
			$('.friend_action_drop_down').each(function(){			
				var iFriendId = parseInt($(this).parents('.friend_row_holder:first').find('.js_friend_actual_user_id').val());
				$(this).append('<li><a href="#" rel="' + iListId + '|' + iFriendId + '"><span></span>' + sName + '</a></li>');
				$('.js_friend_action_edit_list').show();				
			});	
			
			$('.ym-menu-slide').append('<li class="mobile_main_menu"><a href="' + ynmt_trim(ynmtMobileTemplate.currHref, '#') + 'view_list/' + 'id_' + iListId + '/' + '">' + sName + '</a></li>');
			
			$Core.loadInit();
		}
	}
}

var ynmtPlaces = 
{
    /* If the browser does not support Navigator we can get the latitude and longitude using the IPInfoDBKey */
    sIPInfoDbKey: '',
    /* Google requires the key to be passed so we store it here*/
    sGoogleKey : '',
    /* Google object holding my location*/
    gMyLatLng : undefined,
    bGoogleReady: false,
    /* This is the google map object, we can control the map from this variable */
    gMap : undefined,
    /* The id of the div that will display the map of the current location */
    sMapId : 'mt_js_feed_check_in_map',
    /* Google's marker in the map */
    gMarker: undefined,
    /* Google's Geocoder object */
    gGeoCoder: undefined,
    /* Here we store the places gotten from Google and Pages. This array is reset as the user moves away from the found place */
    aPlaces : [],
    /* We store the maps and other information related to maps that only show when hovering over their locations in the feed entries */
    aHoverPlaces: {},
	init: function()
	{
	} 			
    /* Called from the template when we have the location of the visitor */
    , setVisitorLocation : function(fLat, fLng)
    {
        ynmtPlaces.gMyLatLng = new google.maps.LatLng(fLat, fLng);
        $(ynmtPlaces).trigger('gotVisitorLocation');
    }
    , googleReady : function()
    {
        ynmtPlaces.bGoogleReady = true;
    }
    /* Prepare our location. If we have the location of the user in the database this function is called After gMyLatLng has been defined. */
    , initMaps: function(oInfo)
    {
        if (ynmtPlaces.sGoogleKey.length < 1 )
        {
            return;
        }

		//	ynmt -- khi click vao check-in button
        $(ynmtPlaces).on('mapCreated', function(){
        	 ynmtPlaces.showMap(); 
    	 });
        if(ynmtPlaces.getVisitorLocation() === false){
        	ynmtPlaces.showMap();
        }
        
        $(ynmtPlaces).on('gotVisitorLocation', function(){ynmtPlaces.createMap();});
    }      
    , createMap : function()
    {
        /* Creating map */
        if (typeof ynmtPlaces.gMyLatLng == 'undefined' || typeof ynmtPlaces.gMap != 'undefined')
        {
            return;
        }

        /* Build the map*/
        var oMapOptions =
        {
            zoom: 13,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            center: ynmtPlaces.gMyLatLng,
            streetViewControl: false,
            scrollWheel: false
        };

        $('#jplaces_search_results').show(400);
        setTimeout( function()
        {
            ynmtPlaces.gMap = new google.maps.Map(document.getElementById(ynmtPlaces.sMapId), oMapOptions);
            /* Create the search object*/
            ynmtPlaces.gSearch = new google.maps.places.PlacesService(ynmtPlaces.gMap);
            /* Build the marker */
            ynmtPlaces.gMarker = new google.maps.Marker({
                map: ynmtPlaces.gMap,
                position: ynmtPlaces.gMyLatLng,
                draggable: true,
                animation: google.maps.Animation.DROP
            });

            /* Now attach an event for the marker */
            google.maps.event.addListener( ynmtPlaces.gMarker, 'mouseup', function()
            {
                /* Refresh gMyLatLng*/
                ynmtPlaces.gMyLatLng = new google.maps.LatLng(ynmtPlaces.gMarker.getPosition().lat(), ynmtPlaces.gMarker.getPosition().lng());

                /* Center the map */
                ynmtPlaces.gMap.panTo(ynmtPlaces.gMyLatLng);

                ynmtPlaces.getNewLocations();
            });

        }, 400);

        /* We need the name of the city to pre-populate the input */
        ynmtPlaces.gGeoCoder = new google.maps.Geocoder();
        ynmtPlaces.gGeoCoder.geocode({'latLng': ynmtPlaces.gMyLatLng }, function(oResults, iStatus)
        {
            if (iStatus == google.maps.GeocoderStatus.OK && oResults[1])
            {
                $('#hdn_location_name').val( oResults[1].formatted_address );
                $('#mt_val_location_name').val( oResults[1].formatted_address );
                $('#mt_val_location_name').val( oResults[1].geometry.location.lat() + ',' + oResults[1].geometry.location.lng() );
            }
        });

        ynmtPlaces.gBounds = new google.maps.LatLngBounds(
            new google.maps.LatLng( ynmtPlaces.gMyLatLng.lat() - 1, ynmtPlaces.gMyLatLng.lng()),
            new google.maps.LatLng( ynmtPlaces.gMyLatLng.lat(), ynmtPlaces.gMyLatLng.lng() + 1)
        );


        /* At this point gMyLatLng must exist */
        $.ajaxCall('mobiletemplate.loadEstablishments', 'latitude=' + ynmtPlaces.gMyLatLng.lat() + '&longitude=' + ynmtPlaces.gMyLatLng.lng());

        $(ynmtPlaces).trigger('mapCreated');
    }
    /* Ajax call to get more locations, needs to be called after a marker exists */
    , getNewLocations: function(bAuto)
    {
        if (typeof ynmtPlaces.gSearch == 'undefined')
        {
            ynmtPlaces.gSearch = new google.maps.places.PlacesService(ynmtPlaces.gMap);
        }
        var aTemp = [];
        ynmtPlaces.aPlaces.map(function(oPlace){
            if (typeof oPlace['page_id'] != 'undefined') aTemp.push(oPlace);
        });
        ynmtPlaces.aPlaces = aTemp;

        var sOut = '';

        ynmtPlaces.gSearch.nearbySearch({
            location: ynmtPlaces.gMyLatLng,
            radius: '500'
        }, function(aResults, iStatus){
            if (iStatus == google.maps.places.PlacesServiceStatus.OK)
            {
                for (var i = 0; i < aResults.length; i++)
                {
                    if (typeof bAuto == 'boolean' && bAuto == true)
                    {
                        aResults[i]['is_auto_suggested'] = true;
                    }
                    ynmtPlaces.aPlaces.push(aResults[i]);
                }
                ynmtPlaces.displaySuggestions();
            }
        });
    }
    /* Populates and displays the div to show establishments given the current position as defined by gMyLatLng.
     * it checks all of the items in aPlaces and gets the 10 nearer gMyLatLng, places the name of the city first.*/
    , displaySuggestions: function()
    {
        var sOut = '';
        ynmtPlaces.aPlaces.map(function(oPlace)
        {
            sOut += '<div class="js_div_place" onmouseover="ynmtPlaces.hintPlace(\''+oPlace['id']+'\');" onclick="ynmtPlaces.chooseLocation(\'' + oPlace['id'] + '\');">';
            if(undefined !== oPlace['photoFullPath'] && null !== oPlace['photoFullPath'] && oPlace['photoFullPath'].length > 0){
            	sOut += '<div class="js_div_place_name">' + '<img alt="" src="' + oPlace['photoFullPath'] + '"  width="43"" height="43"></div>';
            } else {
            	sOut += '<div class="js_div_place_name">' + '<img alt="" src="' + oPlace['icon'] + '"  width="43"" height="43"></div>';	
            }
            
            sOut += '<div >';
            sOut += '<div class="js_div_place_name">' + oPlace['name'] + '</div>';
            if (typeof oPlace['vicinity'] != 'undefined')
            {
                sOut += '<div class="js_div_place_vicinity">' + oPlace['vicinity'] + '</div>';
            } else if (typeof oPlace['formatted_address'] != 'undefined') {
            	sOut += '<div class="js_div_place_vicinity">' + oPlace['formatted_address'] + '</div>';
            }
            sOut += '</div>';
            sOut += '</div>';
        });

         $('#mt_places_search_results').html(sOut);
         ynmtPlaces.finishSearching();
    } 
    , getVisitorLocation :function(sFunction)
    {
        //$('#js_add_location, #js_add_location_suggestions').show();
        if (typeof ynmtPlaces.gMyLatLng != 'undefined')
        {
            if (typeof sFunction == 'function')
            {
                sFunction();
            }
            /* We already have a location */
            return false;
        }
        // Get the visitors location
        if(navigator.geolocation)
        {
            navigator.geolocation.getCurrentPosition(function(oPos)
                {
                    if (oPos.coords.latitude == 0 && oPos.coords.longitude == 0)
                    {
                        return;
                    }
                    ynmtPlaces.gMyLatLng = new google.maps.LatLng(oPos.coords.latitude, oPos.coords.longitude);
                    $(ynmtPlaces).trigger('gotVisitorLocation');
                    //$.ajaxCall('user.saveMyLatLng', 'lat=' + oPos.coords.latitude + '&lng=' + oPos.coords.longitude);
                },
                function(){ ynmtPlaces.getLocationWithoutHtml5(sFunction); }
            );
        }
        else
        {
            ynmtPlaces.getLocationWithoutHtml5();
        }
    }
    , getLocationWithoutHtml5: function(sFunction)
    {
    	alert('getLocationWithoutHtml5');
    } 
    /* Adds New places to the $Core.Feed.aPlaces array by scannig the existing items before adding a new one,
     * Receives a string in json format, called from an ajax response. The second parameter is an optional callback function */
    , storePlaces: function(jPlaces, isParse, oCallback)
    {
    	var oPlaces = null;
    	if(isParse == '1'){
    		oPlaces = $.parseJSON(jPlaces);
    	} else {
    		oPlaces = jPlaces;
    	}
        
        $(oPlaces).each(function(iPlace, oNewPlace)
        {
            var bAddPage = true;
            ynmtPlaces.aPlaces.map(function(oFeedPlace)
            {
                if (typeof oFeedPlace['page_id'] != 'undefined' && oFeedPlace['page_id'] == oNewPlace['page_id'])
                {
                    /* its a page that we already added*/
                    bAddPage = false;
                }
            });

            if (bAddPage)
            {
                if (typeof oNewPlace['id'] == 'undefined')
                {
                    oNewPlace['id'] = Math.round(1000000*Math.random());
                    oNewPlace['geometry']['location'] = new google.maps.LatLng( oNewPlace['geometry']['latitude'], oNewPlace['geometry']['longitude'] );
                }
                
                // get location of page
                var latlng = new google.maps.LatLng(oNewPlace['geometry']['latitude'], oNewPlace['geometry']['longitude']);
		        ynmtPlaces.gGeoCoder.geocode({'latLng': latlng }, function(oResults, iStatus)
		        {
		            if (iStatus == google.maps.GeocoderStatus.OK && oResults[0])
		            {
		            	//alert(oResults[1].formatted_address);
		            	oNewPlace['formatted_address'] = oResults[0].address_components[0].short_name + ' ' + oResults[0].address_components[1].short_name + ', ' + oResults[0].address_components[2].short_name + ', ' + oResults[0].address_components[3].short_name; 
		            }
		        });
				                      

                ynmtPlaces.aPlaces.push(oNewPlace);
            }
        });

        if (typeof oCallback == 'function')
        {
            oCallback();
        }
    } 
    /* This function is called after a map exists ($Core.Feed.createMap() has been executed), it only shows it like when clicking the button */
    , showMap : function()
    {
        if (typeof google == 'undefined')
        {
            ynmtPlaces.iTimeShowMap = setTimeout(ynmtPlaces.showMap, 1000);
            return;
        }

        if (typeof ynmtPlaces.iTimeShowMap != 'undefined')
        {
            clearTimeout(ynmtPlaces.iTimeShowMap);
        }


        var gTempLat = false;
        // $('#li_location_name, #js_location_input, #hdn_location_name, #js_add_location, #js_add_location_suggestions').show(400);
        // $('.activity_feed_form_button_position').hide(400);
        setTimeout(
            function()
            {
                $('#' + ynmtPlaces.sMapId).css('height', '300px');

                /*setTimeout(function(){*/
                if (gTempLat == true)
                {
                    return;
                }
                else
                {
                    gTempLat = true;
                }

                ynmtPlaces.getNewLocations(true);
                // $('#hdn_location_name').focus();
                /*setTimeout(function(){*/
                google.maps.event.trigger(ynmtPlaces.gMap, 'resize');
                ynmtPlaces.gMap.setCenter(ynmtPlaces.gMyLatLng);
                /*}, 700);*/

                /*}, 400);*/
            }, 400
        );

    }     
    , textSearchRequest : function()
    {
        var sOut = '';
        //ynmtPlaces.aPlaces = [];

        ynmtPlaces.gSearch.textSearch({
            location: ynmtPlaces.gMyLatLng,
            radius: '500', 
            query: $('#checkinTextSearch').val()
        }, function(aResults, iStatus){
            if (iStatus == google.maps.places.PlacesServiceStatus.OK)
            {
                for (var i = 0; i < aResults.length; i++)
                {
                    ynmtPlaces.aPlaces.push(aResults[i]);
                }
                ynmtPlaces.displaySuggestions();
            } else {
            	ynmtPlaces.resolveResponseFromGoogleAPI(aResults, iStatus);
            }
        });
    }     
    , hintPlace : function()
    {
    }     
    , chooseLocation : function(id)
    {
		var oPlace = false;
		ynmtPlaces.aPlaces.map(function(oCheck){
			if (oCheck['id'] == id){ oPlace = oCheck; return; }
		});
		if (oPlace == false)
		{
			return;
		}
		
		if (typeof oPlace['latitude'] != 'undefined')
		{
			$('#mt_val_location_latlng').val( oPlace['latitude'] + ',' + oPlace['longitude']);
		}
		else if (typeof oPlace['geometry'] != 'undefined')
		{
			$('#mt_val_location_latlng').val( oPlace['geometry']['location'].lat() + ',' +  oPlace['geometry']['location'].lng());
		}
		
		$('#hdn_location_name, #mt_val_location_name').val( oPlace['name']);
		$('#mt_js_location_feedback').html(ynmtMobileTemplate.langText['mobiletemplate.at_uppercase'] + ' <span class=\"\" onclick=\"ynmtMobileTemplate.removeSelectedLocation();\">' + oPlace['name'] + '<i>&nbsp;×&nbsp;</i></span>').show();
		
		$('#mt_check_in').hide();
		$('#mt_status_share').show();
		$( "#btnCheckIn" ).addClass( "ym-checked-in" );
    }     
    , resolveResponseFromGoogleAPI : function(aResults, iStatus)
    {
        if (iStatus == google.maps.places.PlacesServiceStatus.ERROR)
        {
        	alert(oTranslations['mobiletemplate.ga_status_code_error']);
        } else if (iStatus == google.maps.places.PlacesServiceStatus.INVALID_REQUEST)
        {
        	alert(oTranslations['mobiletemplate.ga_status_code_invalid_request']);
        } else if (iStatus == google.maps.places.PlacesServiceStatus.OVER_QUERY_LIMIT)
        {
        	alert(oTranslations['mobiletemplate.ga_status_code_over_query_limit']);
        } else if (iStatus == google.maps.places.PlacesServiceStatus.REQUEST_DENIED)
        {
        	alert(oTranslations['mobiletemplate.ga_status_code_request_denied']);
        } else if (iStatus == google.maps.places.PlacesServiceStatus.UNKNOWN_ERROR)
        {
        	alert(oTranslations['mobiletemplate.ga_status_code_unknown_error']);
        } else if (iStatus == google.maps.places.PlacesServiceStatus.ZERO_RESULTS)
        {
	        var sOut = '<div>' + oTranslations['mobiletemplate.ga_status_code_zero_results'] + '</div>';
	         $('#mt_places_search_results').html(sOut);
        }
        ynmtPlaces.finishSearching();
    }
    , loadEstablishments : function(sCallback)
    {
		//      init
        //      process
        $Core.ajax('mobiletemplate.loadEstablishments',
        {		
            type: 'POST',
            params:
            {				
                latitude: ynmtPlaces.gMyLatLng.lat()
                , longitude: ynmtPlaces.gMyLatLng.lng()
                , keyword: $('#checkinTextSearch').val()
                , callJS: 'no'
                , sCallback: sCallback
            },
            success: function(sOutput)
            {		
                var oOutput = $.parseJSON(sOutput);
                if(oOutput.result == 'SUCCESS')
                {
                	if(undefined !== oOutput.aPages && null !== oOutput.aPages){
                		ynmtPlaces.storePlaces(oOutput.aPages, '0');
                	}
                	
                	if(oOutput.sCallback == 'textSearchRequest'){
                		ynmtPlaces.textSearchRequest();
                	} else if(oOutput.sCallback == 'nearbySearchRequestAuto'){
                		ynmtPlaces.nearbySearchRequestAuto();
                	}
                } else if(oOutput.result == 'FAILURE')
                {
                }
            }
        }); 
    }
    , textSearchRequestAuto : function()
    {
		$("#checkinTextSearch" ).autocomplete({
		      source:function( request, response ) {
		      	ynmtPlaces.displaySearching();
		      	ynmtPlaces.aPlaces = [];
		      	
		      	if(request.term.length == 0){
		      		//ynmtPlaces.nearbySearchRequestAuto();
		      		ynmtPlaces.loadEstablishments('nearbySearchRequestAuto');
		      	} else {
		      		//ynmtPlaces.textSearchRequest();	
		      		ynmtPlaces.loadEstablishments('textSearchRequest');
		      	}		      	
		      },
	            autoFocus: true
	            ,  minLength: 0
		  });    	
    }     
    , nearbySearchRequestAuto : function()
    {
    	ynmtPlaces.getNewLocations(true);
    }     
    , displaySearching : function()
    {
    	$('#mt_places_search_results').hide();
    	$('#mt_places_search_results_loading').show();
    }     
    , finishSearching : function()
    {
    	$('#mt_places_search_results_loading').hide();
    	$('#mt_places_search_results').show();
    }     
    , showHoverMap : function(fLat, fLng, oObj)
    {
        /* Check if this item already has a map */
        if ($(oObj).siblings('.js_location_map').length > 0 )
        {
            if(!$('.js_location_map').is(':visible')) {
                $(oObj).siblings('.js_location_map').show();
            } else {
                $(oObj).siblings('.js_location_map').hide();
            }

            /* Trigger the resize to avoid visual glitches */
            return false;
        }

        var sId = 'js_map_' + Math.floor(Math.random() * 100000);

        var sInfoWindow = '<div class="js_location_map" id="' + sId + '"></div>';

        /* Load the map */
        $(oObj).after(sInfoWindow);

        var gLatLng = new google.maps.LatLng(fLat, fLng);
        var oMapOptions =
        {
            zoom: 13,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            center: gLatLng,
            streetViewControl: false,
            disableDefaultUI: true
        };

        ynmtPlaces.aHoverPlaces[sId] = {
            map: new google.maps.Map(document.getElementById(sId), oMapOptions),
            geometry: {location : gLatLng}
        };

        /* Build the marker */
        ynmtPlaces.gMarker = new google.maps.Marker({
            map: ynmtPlaces.aHoverPlaces[sId]['map'],
            position: gLatLng,
            draggable: true,
            animation: google.maps.Animation.DROP
        });

        google.maps.event.trigger( ynmtPlaces.aHoverPlaces[sId]['map'], 'resize');
        return false;
    }
    , getInfoFromMapStatic : function()
    {
		$('.mt_map_static_in_feed').each(function()
		{
			var oThis = this;
			var map = new google.maps.Map(document.getElementById('mt_js_static_map_in_feed'), {
			    mapTypeId: google.maps.MapTypeId.ROADMAP,
			    center: new google.maps.LatLng($(this).find('#mt_val_location_lat').val(), $(this).find('#mt_val_location_lng').val()),
			    zoom: 15
			  });
			  
			var request = {
			    reference: 'CnRkAAAAGnBVNFDeQoOQHzgdOpOqJNV7K9-c5IQrWFUYD9TNhUmz5-aHhfqyKH0zmAcUlkqVCrpaKcV8ZjGQKzB6GXxtzUYcP-muHafGsmW-1CwjTPBCmK43AZpAwW0FRtQDQADj3H2bzwwHVIXlQAiccm7r4xIQmjt_Oqm2FejWpBxLWs3L_RoUbharABi5FMnKnzmRL2TGju6UA4k'
			  };
			  
			var service = new google.maps.places.PlacesService(map);
			service.getDetails(request, function(place, status) {
			    if (status == google.maps.places.PlacesServiceStatus.OK) {
			    	if(place.icon){
			    		console.log(place.icon);	
			    	}
			    }
			  });						  			  
		});     	
    }           
}; 

$Behavior.ynmtInitPlaces = function()
{
	ynmtPlaces.init();
	//ynmtPlaces.getInfoFromMapStatic();
}
